<?php
// app/Controller/PaymentsController.php
App::uses('AppController', 'Controller');
App::import('Vendor', 'Stripe', array('file' => 'stripe-php/init.php'));
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Price;


class PaymentsController extends AppController
{
    public $name = 'Payments';

    public $uses = array('Cart', 'Plan', "Transaction", "TransactionPlan");
     
    public function initialize(): void
    {
        parent::initialize();
        //  Stripe::setApiKey(Configure::read('Stripe.secret'));
     
    }

    public function embedded()
    {
        // This action will render the embedded payment form
    }
    public function checkout()
    { 
        
        Stripe::setApiKey(Configure::read('Stripe.secret'));
        $YOUR_DOMAIN = Router::url('/', true);
        try {

            
            $flag = "true";
            $plan_id = "";
            $recurring = false;
            $cycle = "";
            $cycle_period = "";
            $user_id = $this->Auth->user('id');
            $first_name = $this->Auth->user('first_name');
            $last_name = $this->Auth->user("last_name");
            $newsroom_slug = "";

        
            $checkCart = $this->Cart->find('first', array('fields' => ['cart_session_id'], 'conditions' => array('Cart.staff_user_id' => $user_id, 'cart_type' => "plan"), 'order' => "Cart.id desc"));

            if (empty($checkCart))
                throw new NotFoundException(__('Please purchase a PR plan or create newsroom.'));
            $cartSessionId = $checkCart['Cart']['cart_session_id'];

            $coupon_data = '';
            if ($this->Session->check('FrontCart.coupon')) {
                $couponId = $this->Session->read('FrontCart.coupon.coupon_id');
                $coupon_data = $this->Coupon->find('first', array('conditions' => array('Coupon.id' => $couponId, 'release_date <=' => date('Y-m-d'), 'end_date >=' => date('Y-m-d')), 'fields' => array('value', 'type', 'code', 'id')));
                if (empty($coupon_data)){
                    throw new NotFoundException(__('Your coupon expired.'));
                }
            }


            $cartData = $this->Custom->fetchCartData($user_id, $cartSessionId);

            


            $newsroom_signup = $this->Custom->get_save_transaction_formateddata($user_id, $cartSessionId, $coupon_data);

           

            if ($newsroom_signup['Transaction']["total"] <= 0)
                throw new NotFoundException(__('Please purchase a PR plan or create newsroom.'));

            $subtotal = $newsroom_signup['Transaction']['subtotal'];
            $newsroom_amount = $newsroom_signup['Transaction']['newsroom_amount'];
            $discount = $newsroom_signup['Transaction']['discount'];
            $tax = $newsroom_signup['Transaction']['tax'];

            $total_amount = str_replace(",", "", $newsroom_signup['Transaction']["total"]);

            $total_amount = floatval($total_amount);


            $product_name = 'Newsroom';

         
            if (isset($newsroom_signup['TransactionPlan']) && !empty($newsroom_signup['TransactionPlan'])) {

                $plan_title = [];
                foreach ($newsroom_signup['TransactionPlan'] as $key => $value) {
                    if(!in_array($value['title'], $plan_title)){
                    $plan_title[] = $value['title'];
                    }
                }
                $product_name = implode(', ', $plan_title);

                $plan_id = $newsroom_signup['TransactionPlan'][0]['plan_id'];

                $plan_details = $this->Plan->find('first', array('conditions' => array("Plan.id" => $plan_id)));

                if (isset($plan_details) && !empty($plan_details)) {

                    if ($plan_details['Plan']['plan_type'] == 'subscription') {

                        // $plan_details['Plan']['cycle_period']='daily'; // only for testing
                        switch ($plan_details['Plan']['cycle_period']) {
                            case 'monthly':
                                $cycle             =   'month';
                                $cycle_period      =   1;
                                $recurring         =   true;
                                break;
                            case 'quarterly':
                                $cycle              =   'month';
                                $cycle_period       =   3;
                                $recurring          =   true;
                                break;
                            case 'halfyearly':
                                $cycle             =   'month';
                                $cycle_period      =   6;
                                $recurring         =   true;
                                break;
                            case 'yearly':
                                $cycle             =   'year';
                                $cycle_period      = 1;
                                $recurring         = true;
                                break;
                            default:
                                $cycle       =   'day';
                                $cycle_period =   1;
                                $recurring   =   true;
                        }
                    }
                }
            }


            // $transactionData = array(
            //     'staff_user_id' => $user_id,
            //     'cart_session_id' => $cartSessionId,
            //     'tx_id' => "",
            //     'txn_type' => "stripe_txn",
            //     'payment_date' => date("Y-m-d H:i:s"),
            //     'paymant_date' => date("Y-m-d H:i:s"),
            //     'currency' => "USD",
            //     'payment_type' => "instant",
            //     'newsroom_amount' => $newsroom_amount,
            //     'subtotal' => $subtotal,
            //     'discount' => $discount,
            //     'tax' => $tax,
            //     'total' => $total_amount,
            //     'status' => "Pending",
            //     "cart_type" => "",
            //     "is_plan_newsroom" => "0",
            // );

           


            // $res = $this->Transaction->save($transactionData);


            // $transaction_id = $res['Transaction']['id'];

            // foreach($newsroom_signup['TransactionPlan'] as $tp){
            //     $this->TransactionPlan->save([
            //         'transaction_id' => $transaction_id,
            //         'plan_id' => $tp['plan_id'],
            //         'plan_amount' => $tp['plan_amount'],
            //     ]);
            // }


            // $transaction_plan_data = [];
            // foreach ($newsroom_signup['TransactionPlan'] as $tp) {
            //     $transaction_plan_data[] = [
            //         'transaction_id' => $transaction_id,
            //         'plan_id' => $tp['plan_id'],
            //         'plan_amount' => $tp['plan_amount'],
            //     ];
            // }
            // $this->TransactionPlan->saveMany($transaction_plan_data);
     

            if($recurring){

                $price = Price::create([
                    'unit_amount' => $total_amount * 100, // Price in cents
                    'currency' => 'USD',
                    'recurring' => [
                        'interval' => $cycle, // or 'year' for annual subscriptions
                        'interval_count' => $cycle_period,
                    ],
                    'product_data' => [
                        'name' => $product_name,
                    ],
                ]);

                
                $checkoutSession = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price' => $price->id,
                        'quantity' => 1,
                    ]],
                    'mode' => 'subscription',
                    'metadata' => [
                        'user_id' => $user_id,
                        'cart_id' => $cartSessionId,
                        'plan_id' => $plan_id,
                        'total_amount' => $total_amount,
                    ],
                    'success_url' => $YOUR_DOMAIN . 'stripe/success?cart_id=' . $cartSessionId . '&session_id={CHECKOUT_SESSION_ID}',
                    // 'cancel_url' => $YOUR_DOMAIN . '/stripe/cancel?cart_id=' . $cartSessionId . '&transaction_id=' . $transaction_id . '&session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => $YOUR_DOMAIN . 'plans',
                ]);


            }else{
                $lineItems[] = [
                    'price_data' => [
                        'currency' => "USD",
                        'product_data' => [
                            'name' => $product_name,  // Product name
                        ],
                        'unit_amount' => $total_amount * 100,  // Price in cents
                    ],
                    'quantity' => 1,  // Default quantity
                ];
    
        
                $checkoutSession = Session::create([
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'metadata' => [
                        'user_id' => $user_id,
                        'cart_id' => $cartSessionId,
                        'plan_id' => $plan_id,
                        'total_amount' => $total_amount,
                    ],
                    'success_url' => $YOUR_DOMAIN . 'stripe/success?cart_id=' . $cartSessionId . '&session_id={CHECKOUT_SESSION_ID}',
                    // 'cancel_url' => $YOUR_DOMAIN . '/stripe/cancel?cart_id=' . $cartSessionId . '&transaction_id=' . $transaction_id . '&session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => $YOUR_DOMAIN . 'plans',
                ]);
            }

            



            return $this->redirect($checkoutSession->url);
            
          

        } catch (\Exception $e) {
            // Handle any errors that occurred during the creation of the session
            $this->Flash->error('Something went wrong: ' . $e->getMessage());
            return $this->redirect(['action' => 'error']);
        }
    }


    //   public function checkout(){ 
        
    //     Stripe::setApiKey(Configure::read('Stripe.secret'));
    //     $YOUR_DOMAIN = Router::url('/', true);
    //     try {

    //         $cartData = $this->Session->read('Stripe.cartData'); 
    //         $totalAmount=(isset($cartData['totals']['total'])&&$cartData['totals']['total']>0)?$cartData['totals']['total']:"0.00"; 
    //         $cartSessionId= $cartData['cart_session_id'];
    //         $userId = $this->Auth->user('id');
    //          $plan_id = $this->Session->read('pr_selectedplan');

    //         $selectedplan=$this->request->query['selectedplan'];
    //         $prId=$this->request->query['prId'];
           
    //         $data_array = array();        
    //         $data_array['Transaction']['staff_user_id'] =$userId;
    //         $data_array['Transaction']['cart_session_id'] =$cartSessionId;
    //         $data_array['Transaction']['tx_id'] = "";
    //         $data_array['Transaction']['txn_type'] = "stripe_txn"; 
    //         $data_array['Transaction']['paymant_date'] = date('Y-m-d H:i:s');
    //         $data_array['Transaction']['currency'] = "USD";
    //         $data_array['Transaction']['payment_type'] = "instant";
    //         $data_array['Transaction']['discount_id'] = "";
    //         $data_array['Transaction']['newsroom_amount'] = "";
    //         $data_array['Transaction']['subtotal'] = $cartData['totals']['subtotal'];
    //         $data_array['Transaction']['discount'] = $cartData['totals']['discount'];
    //         $data_array['Transaction']['tax'] = $cartData['totals']['tax'];
    //         $data_array['Transaction']['total'] = $cartData['totals']['total'];  
    //         $data_array['Transaction']['status'] ="Pending";
    //         $data_array['Transaction']['cart_type'] =null;  // not found in paypal 
    //         $data_array['Transaction']['error_message'] =null; 
    //         $data_array['Transaction']['company_id'] =null;
    //         $data_array['Transaction']['response']=null;
    //         $data_array['Transaction']['is_plan_newsroom'] =(!empty($sess_data['TransactionPlan'])&&$sess_data['Transaction']['newsroom_amount']>0)?"1":"0"; 
            
    //         $this->loadModel('Transaction'); 
    //         $this->loadModel('RemainingUserPlan'); 
    //           $this->loadModel('PressRelease'); 
    //         if(  $this->Transaction->save($data_array)){
    //              $txId = $this->Transaction->getLastInsertID();
    //              $updatePrCreadits = $this->RemainingUserPlan->find('first', array('conditions' => array('RemainingUserPlan.plan_id' => $plan_id, 'RemainingUserPlan.staff_user_id' => $userId, 'number_pr !=' => '0'), 'fields' => array('number_pr', 'id')));
    //             if (!empty($updatePrCreadits)) {
    //                 $number_pr = $updatePrCreadits['RemainingUserPlan']['number_pr'] - 1;
    //                 $this->RemainingUserPlan->id = $updatePrCreadits['RemainingUserPlan']['id'];
    //                 $this->RemainingUserPlan->saveField('number_pr', $number_pr);
    //             }
               
    //             $cart_plans = $this->saveprtrans($userId, $plan_id, $txId);
    //             //  debug($cart_plans);die;
    //             if ($prId) {
    //                 $savedatapr['PressRelease']['id'] = $prId;
    //                 $savedatapr['PressRelease']['status'] = '0';
    //                 $savedatapr['PressRelease']['transaction_id']=$data_array['Transaction']['tx_id'];
    //                 $this->PressRelease->save($savedatapr);
    //             }
    //             $coupon_data = '';
    //              if ($this->Session->check('FrontCart.coupon')) {
    //                 $couponId = $this->Session->read('FrontCart.coupon.coupon_id');
    //                 $coupon_data = $this->Coupon->find('first', array('conditions' => array('Coupon.id' => $couponId, 'release_date <=' => date('Y-m-d'), 'end_date >=' => date('Y-m-d')), 'fields' => array('value', 'type', 'code', 'id')));
    //                 if (empty($coupon_data)){
    //                     throw new NotFoundException(__('Your coupon expired.'));
    //                 }
    //             }

    //             $newsroom_signup = $this->Custom->get_save_transaction_formateddata($userId, $cartSessionId, $coupon_data);
    //             // debug($newsroom_signup);die;
    //             $sess_data= $newsroom_signup ;
    //             if (isset($sess_data['TransactionPlan']) && !empty($sess_data['TransactionPlan'])) {
    //                 $this->loadModel('TransactionPlan');
    //                 $remaingPRArr = [];
    //                 foreach ($sess_data['TransactionPlan'] as $index => $sess_data) {
    //                     $newdataArr[$index]['transaction_id'] = $txId;
    //                     $newdataArr[$index]['plan_id'] = $sess_data['plan_id'];
                        
    //                     $newdataArr[$index]['plan_amount'] = $sess_data['plan_amount'];
    //                     $data_array['TransactionPlan'][$index]['title'] = $sess_data['title'];
    //                     $data_array['TransactionPlan'][$index]['plan_id'] = $sess_data['plan_id'];
    //                     $data_array['TransactionPlan'][$index]['plan_amount'] = $sess_data['plan_amount'];
    //                     $number_pr = $this->Custom->getprnumber($sess_data['plan_id']);
    //                     $remaingPRArr[$index]['transaction_id'] = $txId;
    //                     $remaingPRArr[$index]['staff_user_id'] = $userId;
    //                     $remaingPRArr[$index]['number_pr'] = $number_pr;
    //                     $remaingPRArr[$index]['plan_id'] = $sess_data['plan_id'];
    //                     $previousplan = $this->RemainingUserPlan->find('first', array('fields' => ['RemainingUserPlan.id', 'RemainingUserPlan.number_pr'], 'conditions' => ['staff_user_id' => $userId, 'plan_id' => $sess_data['plan_id']]));
    //                     if (!empty($previousplan)) {
    //                         $remaingPRArr[$index]['id'] = $previousplan['RemainingUserPlan']['id'];
    //                         $remaingPRArr[$index]['number_pr'] = ($previousplan['RemainingUserPlan']['number_pr'] + $number_pr);
    //                    }
    //                }
    //                 $this->TransactionPlan->saveMany($newdataArr, array('deep' => true));
    //                 $this->RemainingUserPlan->saveMany($remaingPRArr, array('deep' => true));
    //                 $pr_plan_paid = (empty($errorString)) ? "1" : "0";
    //            }
    //             // $uName = $this->Auth->user('first_name');
    //             // $emailTemplate = $this->EmailTemplate->selectTemplate('payment-invoice');
    //             // $mailTo = $this->Auth->user('email');
                 
    //             // $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('press_release_id' => $prId, 'staff_user_id' => $user_id)));
    //             // $html=$this->Custom->getPrInvoiceHtmlForMail($data_array,$transdata);
    //             // $this->Custom->send_invoice_mail($html,$emailTemplate,$uName,$mailTo);
    //             // if (!empty($prId)) {
    //             //     $this->Cart->deleteAll(['Cart.press_release_id' => $prId, 'Cart.staff_user_id' => $user_id, 'cart_type' => 'pr'], false);
    //             // } else if (!empty($cartSessionId)) {
    //             //     $this->Cart->deleteAll(['Cart.cart_session_id' => $cartSessionId, 'Cart.staff_user_id' => $user_id, 'cart_type' => 'pr'], false);
    //             // }
    //             // $this->Session->delete("pr_selectedplan");
                
    //         }
            
    
    //         $totalAmountInCents = intval($totalAmount * 100); 
            
    //         $currency = 'usd';
    //         $lineItems=[];
    //         foreach ($cartData['prlist'] as $feature) {
    //             $priceInCents = intval(floatval(str_replace(['US$', '$'], '', $feature['amount'])) * 100);
    
    //             // Add each feature as a line item
    //             $lineItems[] = [
    //                 'price_data' => [
    //                     'currency' => $currency,
    //                     'product_data' => [
    //                         'name' => $feature['title'],  // Product name
    //                     ],
    //                     'unit_amount' => $priceInCents,  // Price in cents
    //                 ],
    //                 'quantity' => 1,  // Default quantity
    //             ];
    //         }
    //         foreach ($cartData['feature'] as $feature) {
    //             $priceInCents = intval(floatval(str_replace(['US$', '$'], '', $feature['price'])) * 100);
    
    //             // Add each feature as a line item
    //             $lineItems[] = [
    //                 'price_data' => [
    //                     'currency' => $currency,
    //                     'product_data' => [
    //                         'name' => $feature['name'],  // Product name
    //                     ],
    //                     'unit_amount' => $priceInCents,  // Price in cents
    //                 ],
    //                 'quantity' => 1,  // Default quantity
    //             ];
    //         }
    //         // Create the checkout session
    //          $checkoutSession = Session::create([
    //             'line_items' => $lineItems,
    //             'mode' => 'payment',
    //             'success_url' => $YOUR_DOMAIN . '/stripe/processPayment?transaction_id='.$txId.'&session_id={CHECKOUT_SESSION_ID}',
    //             'cancel_url' => $YOUR_DOMAIN . '/stripe/cancel?transaction_id='.$txId.'&session_id={CHECKOUT_SESSION_ID}',
    //         ]);

    //         // Redirect the user to Stripe's checkout page
    //         return $this->redirect($checkoutSession->url);

    //     } catch (\Exception $e) {
    //         // Handle any errors that occurred during the creation of the session
    //         $this->Flash->error('Something went wrong: ' . $e->getMessage());
    //         return $this->redirect(['action' => 'error']);
    //     }
    //   }
    
    
    public function processPayment()
    {
        Stripe::setApiKey(Configure::read('Stripe.secret'));  // Your Stripe API key
    
        // Retrieve the session_id and txnId from the URL query parameters
        $sessionId = $this->request->query['session_id'];
        $txnId = $this->request->query['transaction_id'];
        $cartData = $this->Session->read('Stripe.cartData');
      
    
        if (!$sessionId) {
            $this->Flash->error('Session ID not found.');
            return $this->redirect(['action' => 'error']);
        }
        if (!$txnId) {
            $this->Flash->error('Transaction ID not found.');
            return $this->redirect(['action' => 'error']);
        }
    
        try {
            // Retrieve the checkout session from Stripe using the session_id
            $checkoutSession = Session::retrieve($sessionId);
    
            // Extract the payment intent ID (transaction ID)
            $paymentIntentId = $checkoutSession->payment_intent;
    
            // Retrieve additional information about the payment intent
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            $status = $paymentIntent->status;
    
           
    
            // Update transaction based on status
            $paymentStatus = $this->mapPaymentStatus($status);


            $this->updateTransaction($txnId, $paymentIntentId, $paymentStatus, $paymentIntent);
    
            // Save payment details
            $this->savePayment($paymentIntentId, $paymentStatus, $paymentIntent);
    
            // Handle redirects based on payment status
            // Handle redirects based on payment status and pass transaction data
            switch ($paymentStatus) {
                case 'Success':
                    return $this->redirect([
                        'controller' => 'Payments', 
                        'action' => 'success', 
                        '?' => ['transaction_id' => $txnId, 'payment_intent' => $paymentIntentId]
                    ]);
                case 'Pending':
                    return $this->redirect([
                        'controller' => 'Payments', 
                        'action' => 'pending', 
                        '?' => ['transaction_id' => $txnId, 'payment_intent' => $paymentIntentId]
                    ]);
                case 'Canceled':
                    return $this->redirect([
                        'controller' => 'Payments', 
                        'action' => 'cancel', 
                        '?' => ['transaction_id' => $txnId, 'payment_intent' => $paymentIntentId]
                    ]);
                case 'Failed':
                default:
                    return $this->redirect([
                        'controller' => 'Payments', 
                        'action' => 'error', 
                        '?' => ['transaction_id' => $txnId, 'payment_intent' => $paymentIntentId]
                    ]);
            }
    
        } catch (\Exception $e) {
            // Handle any errors that occurred during the retrieval of the session or payment intent
            $this->Flash->error('Something went wrong: ' . $e->getMessage());
            return $this->redirect(['action' => 'error']);
        }
    }

    private function mapSubscriptionStatus($status){
        switch ($status) {
            case 'active':
            case 'trialing':
                return 'Success';
            case 'incomplete':
            case 'past_due':
                return 'Pending';
            case 'canceled':
                return 'Canceled';
            case 'incomplete_expired':
            case 'unpaid':
            default:
                return 'Failed';
        }
    }

    private function mapPaymentStatus($status)
    {
        switch ($status) {
            case 'succeeded':
                return 'Success';
            case 'requires_payment_method':
            case 'requires_action':
            case 'processing':
                return 'Pending';
            case 'canceled':
                return 'Canceled';
            default:
                return 'Failed';
        }
    }

    private function updateTransaction($txnId, $paymentIntentId, $paymentStatus, $paymentIntent)
    {
        $this->loadModel('Transaction');
        $transactionData = $this->Transaction->find('first', ['conditions' => ['Transaction.id' => $txnId]]);
        if (!empty($transactionData)) {
            $transactionData = [
                'Transaction' => [
                    'id' => $transactionData['Transaction']['id'], 
                    'tx_id' => $paymentIntentId,
                    'intent' => $paymentIntentId,
                    'status' => $paymentStatus,
                    'response' => json_encode($paymentIntent)
                ]
            ];
            $this->Transaction->save($transactionData);
        }
    }

    private function savePayment($paymentIntentId, $paymentStatus, $paymentIntent)
    {
        $this->loadModel('Payment');
        $check = $this->Payment->find('first', ['conditions' => ['transaction_id' => $paymentIntentId]]);
        $data = [
            'Payment' => [
                'transaction_id' => $paymentIntentId,
                'status' => $paymentStatus,
                'record_json' => json_encode($paymentIntent)
            ]
        ];
        if (!empty($check)) {
            $data['Payment']['transaction_id'] = $paymentIntentId;
        }
        $this->Payment->save($data);
    }

    public function success()
    {

        Stripe::setApiKey(Configure::read('Stripe.secret'));        

        $params = $this->request->query;

        $sessionId = $params['session_id'];

        // $txnId = $this->request->query['transaction_id'];

        // Retrieve the checkout session from Stripe using the session_id
        $checkoutSession = Session::retrieve($sessionId);

       
        if($checkoutSession->mode == "subscription"){
            $paymentIntentId = $checkoutSession->subscription;

            
            
            $paymentIntent = \Stripe\Subscription::retrieve($paymentIntentId);

          

        }else{
            $paymentIntentId = $checkoutSession->payment_intent;
    
            // Retrieve additional information about the payment intent
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        }

        // // Extract the payment intent ID (transaction ID)
        

        $metadata = $checkoutSession->metadata;
       

        $status = $paymentIntent->status;

        $txn_id = $paymentIntent->id;

        if($checkoutSession->mode == "subscription"){
            $paymentStatus = $this->mapSubscriptionStatus($status);
        }else{
            $paymentStatus = $this->mapPaymentStatus($status);
        }
       

        // $this->updateTransaction($txnId, $paymentIntentId, $paymentStatus, $paymentIntent);
    
        // Save payment details
        // $this->savePayment($paymentIntentId, $paymentStatus, $paymentIntent);

        // $user_id.",".$plan_id.",".$cartSessionId;

        switch($paymentStatus){
            case 'Success':
                // if(isset($_SESSION['cart_session_id'])){
                //     $this->Cart->deleteAll(array('Cart.cart_session_id' => $_SESSION['cart_session_id']), false);
                //     unset($_SESSION['cart_session_id']); 
                // }

               

                return $this->redirect([
                    'controller' => 'users',
                    'action' => 'paymentsuccess',
                    '?' => [
                        'crt' => $metadata->cart_id,
                        'tx' => $txn_id,
                        'cm' => ($metadata->user_id . "," .  $metadata->plan_id . "," . $metadata->cart_id),
                        'st' => "Success",
                        'cc' => 'USD',
                        'amt' => $metadata->total_amount,
                    ]
                ]);

            default:
                return $this->redirect([
                    'controller' => 'users',
                    'action' => 'paymentcancel',
                ]);
                
        }


       

        

        // echo "<pre>";
        // print_r($paymentIntent);
        // echo "</pre>";
        // die();


        // $transactionId = $this->request->query['transaction_id'];
        // // $paymentIntent = $this->request->query['payment_intent'];
        // $this->set('transactionId', $transactionId);
        // $this->set('paymentIntent', $paymentIntent);

    }
    
    public function cancel()
    {
        $params = $this->request->query;

       
        $transactionId = $this->request->query['transaction_id'];
        $this->set('transactionId', $transactionId);
    }
    
    public function failed()
    {
        $transactionId = $this->request->query['transaction_id'];
        $this->set('transactionId', $transactionId);
    }
    
    public function pending()
    {
        $transactionId = $this->request->query['transaction_id'];
        $this->set('transactionId', $transactionId);
    }
    
    public function saveprtrans($user_id, $selectedplan, $transId = "0", $status = '')
    {

        $this->loadModel('TransactionPressRelease');
          $this->loadModel('Cart');

        $cart_plans['totals']['subtotal'] = $cart_plans['totals']['discount'] = $cart_plans['totals']['tax'] = $cart_plans['totals']['total'] = $famount = $tax = $discount = $plan_amount = '0.00';

        $this->Cart->recursive = '1';

        $this->Cart->bindModel(array('belongsTo' => array('Plan')));

        $cart_plans = $this->Cart->find('first', array('conditions' => array('staff_user_id' => $user_id, 'plan_id' => $selectedplan, 'cart_type' => 'pr')));

        $cart_plans['TransactionPressRelease'] = $cart_plans['Cart'];

        $cart_plans['TransactionPressRelease']['transaction_id'] = $transId;

        $cart_plans['TransactionPressRelease']['staff_user_id'] = $cart_plans['Cart']['staff_user_id'];

        $plan = $cart_plans['Plan'];

        $cart_plans['TransactionPressRelease']["word_amount"] = "0.00";

        if ($cart_plans['Cart']['extra_words'] > 0) {

            $amt = ceil(($cart_plans['Cart']['extra_words'] / 100)) * $plan['add_word_amount'];

            $amount = number_format($amt, 2);

            $cart_plans['TransactionPressRelease']["word_amount"] = $amount;

            $plan_amount += $amount;
        }

        $cart_plans['TransactionPressRelease']["category_amount"] = "0.00";

        if ($cart_plans['Cart']['extra_category'] > 0) {

            $amt = ($cart_plans['Cart']['extra_category'] * $plan['add_category_charges']);

            $amount = number_format($amt, 2);

            $cart_plans['TransactionPressRelease']["category_amount"] = $amount;

            $plan_amount += $amt;
        }

        $cart_plans['TransactionPressRelease']["msa_amount"] = "0.00";

        if ($cart_plans['Cart']['extra_msa'] > 0) {

            $amtmsa = ceil($cart_plans['Cart']['extra_msa'] / $plan['msa_limit']) * ($plan['add_msa_charges']);

            $amountmsa = number_format($amtmsa, 2);

            $cart_plans['TransactionPressRelease']["msa_amount"] = $amountmsa;

            $plan_amount += $amtmsa;
        }

        $cart_plans['TransactionPressRelease']["state_amount"] = "0.00";

        if ($cart_plans['Cart']['extra_state'] > 0) {

            $amtstate = ceil($cart_plans['Cart']['extra_state'] / $plan['state_limit']) * ($plan['add_state_charges']);

            $amountstate = number_format($amtstate, 2);

            $cart_plans['TransactionPressRelease']["state_amount"] = $amountstate;

            $plan_amount += $amtstate;
        }

        if ($cart_plans['Cart']['translate_charges'] > 0) {

            $amttx = $plan['translation_amount'];

            $amttx = number_format($amttx, 2);

            $cart_plans['TransactionPressRelease']["translation_amount"] = $amttx;

            $plan_amount += $amttx;
        }

        $cart_plans['feature'] = [];

        if (!empty($cart_plans['Cart']['distribution_ids'])) {

            $features = unserialize($cart_plans['Cart']['distribution_ids']);

            foreach ($features as $index => $value) {

                $featureData = $this->Custom->getprfeatureprice($value['distribution_id']);

                $cart_plans['feature'][$index]['distribution_id'] = $value['distribution_id'];

                $listId = $cart_plans['Cart']['list_id'];

                $featureAmount = $this->Custom->getAmountMailList($listId, $value['distribution_id'], $featureData['amount'], $featureData['number']);

                $cart_plans['feature'][$index]['name'] = $featureData['name'];

                $cart_plans['feature'][$index]['price'] = $featureAmount;

                $famount = $famount + $cart_plans['feature'][$index]['price'];
            }

            $cart_plans['Cart']['distribution_ids'] = serialize($cart_plans['feature']);

            unset($cart_plans['Cart']['distribution_ids']);

            $cart_plans['TransactionPressRelease']['distribution_ids'] = serialize($cart_plans['feature']);

            unset($cart_plans['feature']);
        }


        $plan_amount += $famount;

        $cart_plans['TransactionPressRelease']['subtotal'] = number_format($plan_amount, 2);

        $cart_plans['TransactionPressRelease']['discount'] = number_format($discount, 2);

        $cart_plans['TransactionPressRelease']['tax'] = $tax;

        $cart_plans['TransactionPressRelease']['total'] = $this->Custom->get_cart_total('0', $plan_amount, $discount);

        unset($cart_plans['Cart']);

        unset($cart_plans['Plan']);

        unset($cart_plans['TransactionPressRelease']['id']);

        unset($cart_plans['TransactionPressRelease']['cart_type']);

        unset($cart_plans['TransactionPressRelease']['is_newsroom_incart']);

        // unset($cart_plans['TransactionPressRelease']['staff_user_id']);

        if ($this->TransactionPressRelease->save($cart_plans)) {

            $lastId = $this->TransactionPressRelease->getLastInsertID();

            $distribution_ids = unserialize($cart_plans['TransactionPressRelease']['distribution_ids']);

            $distributionsArr = [];

            if (!empty($distribution_ids)) {

                foreach ($distribution_ids as $key => $distribution_id) {

                    $distributionsArr[$key]['transaction_press_release_id'] = $lastId;

                    $distributionsArr[$key]['distribution_id'] = $distribution_id['distribution_id'];
                }

                $this->loadModel('DistributionsTransactionPressRelease');

                $this->DistributionsTransactionPressRelease->saveMany($distributionsArr);
            }
        }

        return $cart_plans;
    }
    public function fetchPlanData($plan_id)
    {

        $obj = ClassRegistry::init('Plan');

        $data = $obj->find('first', array('conditions' => array('Plan.id' => $plan_id)));

        return $data;
    }
}