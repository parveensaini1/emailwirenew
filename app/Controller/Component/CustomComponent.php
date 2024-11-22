<?php

/**
 * Default Component
 *
 * PHP version 5
 * This component consists the website default functions
 */

class CustomComponent extends Component
{
    protected $_controller = null;
    public $components = array('AWSSES','Qimage');
    public $dateFormat="";
    public $defaultSendMail="devsite@emailwire.com";
    /**
     * Remove any special character in string and used only numeric and digit
     *
     * @return void
     */
    public function beforeFilter()
    {   
        parent::beforeFilter(); 
    }

    public function initialize(Controller $controller)
    {
        $this->_controller = &$controller;
    }

    public function getUserCompanies($userId = null)
    {

        $obj = ClassRegistry::init('Company');

        $company_list = [];

        $company_list = $obj->find('list', array(

            'joins' => array( 

                array(
                    'table' => 'transactions',
                    'alias' => 'Transaction',
                    'type' => 'INNER',
                    'conditions' => array(
                       'Transaction.company_id = Company.id'
                    )
                ),
            ),
            'conditions' => array(

                'Company.staff_user_id' => $userId,

                'Company.status !=' => array('3', '2'),

                'Transaction.status' => "Success"
            ),
            'fields' => array('Company.id', 'Company.name'),
            'order' => 'Company.name ASC'

        ));
 

        return $company_list;
    }

    
    public function checkUserCompaniesPayment($userId = null)
    {

        $obj = ClassRegistry::init('Company');
        $companycount = $obj->find('count', array(
            'joins' => array( 
                array(
                    'table' => 'transactions',
                    'alias' => 'Transaction',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Transaction.company_id = Company.id'
                    )
                ),
            ),
            'conditions' => array(
                'Company.staff_user_id' => $userId,
                'Transaction.status !=' => "Success"
            ),
            'fields' => array('Company.id', 'Company.name'),
            'order' => 'Company.name ASC'
        )); 

        return $companycount;
    }



    public function checkSuspendedCompanies($userId = null)
    {

        $obj = ClassRegistry::init('Company');

        $count = $obj->find('count', array(
            'conditions' => array(
                'Company.staff_user_id' => $userId,
                'Company.status' => "2"
            ),
            'fields' => array('Company.id', 'Company.name'),
            'order' => 'Company.name ASC'
        ));
        return $count;
    }


    /*
     * Resize press release image
     * @return void
     */
    function rzImagePressRelease($pressReleaseId=null){
        $obj = ClassRegistry::init('PressRelease');
        if(!empty($pressReleaseId)){ 
            return $this->Qimage->rzImage($pressReleaseId,$obj);
        }
        return true;
    }

    public function checkDisapprovedCompanies($userId = null)
    {

        $obj = ClassRegistry::init('Company');

        $count = $obj->find('count', array(
            'conditions' => array(
                'Company.staff_user_id' => $userId,
                'Company.status' => "3"
            ),
            'fields' => array('Company.id', 'Company.name'),
            'order' => 'Company.name ASC'
        ));
        return $count;
    }



    public function get_cart_total($newsroomAmount = '0.00', $subtotal, $discount = "0.00", $tax = "0.00")
    {

        if ($tax > 0)

            $tax = $this->taxcalculation($tax);

        $price = round(((($newsroomAmount + $subtotal) - $discount) + $tax), 2);

        return ($price > 0) ? number_format($price, 2) : "0.00";
    }

    public function taxcalculation($tax = '0')
    {



        return $tax;
    }



    public function getCouponAmount($type, $value = '', $subtotal)
    {

        $discount = $value;

        if ($type == 'percentage') {

            $discount = ($subtotal * $value) / 100;
        }

        return $discount;
    }



    public function getPlanInvoiceHtmlForMail($data)
    {
        $currencySymbol = Configure::read('Site.currency');

        $site_name = strip_tags(Configure::read('Site.name'));

        $recurringHtml = "";

        $name = $plan_url = $rows = '';

        if (!empty($data['TransactionPlan'])) {

            $i = 0;

            foreach ($data['TransactionPlan'] as $index => $plan) {

                $i++;

                $plan_details = $this->fetchPlanData($plan['plan_id']);

                $plan_url = $this->getPlanCategorySlug($plan_details['PlanCategory']['parent_id']);

                $name  =  $plan_details['PlanCategory']['name'];

                $type  =  $plan_details['Plan']['plan_type'];

                // $rows .="<tr style='padding-top:3px'><td>$i</td><td>$name </td> <td>$type</td> <td>".$currencySymbol.$plan['plan_amount']."</td></tr>";

                $rows .= "<tr>

                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>

                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>$name</td>

                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>

                    </tr>";
            }
        }

        if ($data['Transaction']['newsroom_amount'] > 0) {

            $rows .= "<tr>

                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>

                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Newsroom</td>

                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['Transaction']['newsroom_amount'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['Transaction']['newsroom_amount'] . "</td>

                    </tr>";
        }

        if ($data['Transaction']['subscr_id'] && $data['Transaction']['subscr_id'] != NULL) {

            $recurringData = $this->getRecurringTransaction($data['Transaction']['subscr_id'], $data['Transaction']['id']);

            if (!empty($recurringData)) {

                $recRows = '';

                $recurringHtml .= "

            <div style='text-align:left'><h3>Recurring Transaction Summary</h3></div>

            <table id='detail_table' style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>

            <thead>

                <tr>

                    <th style='text-align:left;'>S.No.</th>

                    <th style='text-align:left;'>Transaction id</th>

                    <th style='text-align:left;'>Transaction Type</th>

                    <th style='text-align:left;'>Amount</th>

                    <th style='text-align:left;'>Date</th>

                </tr>

            </thead>";

                foreach ($recurringData as $loop => $recurringPayment) {

                    $total = (!empty($recurringPayment['Transaction']['total'])) ? $recurringPayment['Transaction']['total'] : "0.00";

                    $tx_id = (!empty($recurringPayment['Transaction']['tx_id'])) ? $recurringPayment['Transaction']['tx_id'] : "-";

                    $txnType = $this->getTransactionType($recurringPayment['Transaction']['txn_type']);

                    $date  = date('F d, Y', strtotime($recurringPayment['Transaction']['paymant_date']));

                    $recRows .= "<tr style='padding-top:3px'><td>" . ($loop + 1) . "</td><td>$tx_id</td> <td>$txnType</td> <td>" . $currencySymbol . $total . "</td><td>$date</td></tr>";
                }

                $recurringHtml .= "<tbody>$recRows</tbody></table>";
            }
        }



        $promo_code = '';

        $total_amount = (isset($data['Transaction']['total']) && $data['Transaction']['total'] > 0) ? $currencySymbol . $data['Transaction']['total'] : $currencySymbol . "0.00";

        $subtotal = (isset($data['Transaction']['subtotal']) && $data['Transaction']['subtotal'] > 0) ? $currencySymbol . $data['Transaction']['subtotal'] : $currencySymbol . "0.00";

        $discount = (isset($data['Transaction']['discount']) && $data['Transaction']['discount'] > 0) ? $currencySymbol . $data['Transaction']['discount'] : $currencySymbol . "0.00";

        $tax = (isset($data['Transaction']['tax']) && $data['Transaction']['tax'] > 0) ? $currencySymbol . $data['Transaction']['tax'] : $currencySymbol . "0.00";



        $pdf_obj        =   ClassRegistry::init('PdfSetting');

        $pdf_data       =   $pdf_obj->find('first', array('conditions' => array('PdfSetting.id' => '2')));



        $pdf_title      =   $pdf_data['PdfSetting']['title'];

        $pdf_logo       =   $pdf_data['PdfSetting']['logo'];

        $tandc          =   $pdf_data['PdfSetting']['footer_text'];



        $notesFindReplace = array('##PLAN##' => $name, '##PAGEURL##' => "<br/>" . SITEURL . 'plans/' . $plan_url);

        $notes = strtr($pdf_data['PdfSetting']['email_distribution_description'], $notesFindReplace);



        // $company =(isset($data['Company'])&&!empty($data['Company']['name']))?$data['Company']['name']:$data['StaffUser']['Company'][0]['name'];

        if (isset($data['Company']) && !empty($data['Company']['name'])) {

            $company =  $data['Company']['name'];
        } elseif (isset($data['StaffUser']['Company']) && !empty($data['StaffUser']['Company'][0]['name'])) {

            $company =  $data['StaffUser']['Company'][0]['name'];
        } else {

            $company =  '';
        }
         
        $company_logo_path = ROOT . '/app/webroot/' . "files/pdf_settings/" . $pdf_logo;
        // $companyLogo=$this->convertImageIntoBase64($company_logo_path); 
        $companyLogo=SITEURL. "files/pdf_settings/" . $pdf_logo;

        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>

               <style>body, html { background: #ffffff; color: #555555; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }

               table {margin:0; padding: 0;}

               </style>            

               </head>

                <body style='max-width:650px; margin:auto;'>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-bottom:none;'>

                  <tr>

                    <td><img style='width: 160px;margin: 30px;' src='" . $companyLogo . "' alt='logo'></td>

                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>

                  </tr>

                  <tr>

                    <td valign='top' rowspan='3' colspan='2' style='padding-left: 20px;'>" . Configure::read('Site.address') . "</td>

                    <td  width='150' align='right' style='padding-right: 20px; width: 300px; text-align: right;'><strong>Invoice #: </strong>" . $data['Transaction']['invoice_no'] . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Invoice Date: </strong>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Payment Date: </strong>" . date("F d,Y", strtotime($data['Transaction']['paymant_date'])) . "</td>

                  </tr>

                  <tr>

                  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>

                            " . strip_tags(Configure::read('Site.email')) . "

                            " . SITEURL . "</td>

                    <td align='right' colspan='2'>

                        <table align='right' style='margin-top: 30px; margin-right: 20px; width: 300px; float: right; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>

                            <tr>

                                <td align='center' style='text-align: center; padding: 5px;'>

                                    <span style='width:100%; text-align: center; display: block;'>Amount Due:</span>

                                    <strong style='width:100%; text-align: center; margin-top: 10px; font-size: 24px; display: inline-block;'>$total_amount</strong>

                                </td>

                            </tr>

                        </table>

                    </td>

                  </tr>

                   

                    <tr>

                        <td align='left' style='padding-left: 20px;'><h4>Bill To:</h4>

                        $company<br>

                        " . $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'] . "<br>

                        " . $data['StaffUser']['email'] . "</td>

                        <td colspan='2'></td>

                    </tr>

                </table>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; padding: 20px 0; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-top:none; border-bottom:none;'>

                    <thead>

                        <tr>

                          <th width='80' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-right: 0;padding: 8px;'>Date</th>

                          <th style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Description</th>  

                          <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Quantity</th>  

                          <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Price</th> 

                          <th align='center' width='100' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 8px;'>Amount</th>

                        </tr>

                    </thead>

                    <tbody>

                        $rows

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Subtotal</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$subtotal</td>

                        </tr>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Discount</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$discount</td>

                        </tr>

                    </tbody>

                    <tfoot>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$total_amount</td>

                        </tr>

                    </tfoot>

                </table>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-top:none;'>

                    <tbody>

                    <tr>

                        <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Notes: </strong>$notes 

                        <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Terms and Conditions: </strong>$tandc</td>

                    </tr>

                    <tr>

                    <td colspan='2' style='vertical-align: top; padding: 20px; border-top: 1px solid #e6e6e6;'>" . str_replace("##YEAR##", date('Y'), Configure::read('Site.Copyright')) . "</td>

                    </tr>

                    </tbody>

                </table>

                </body>

                </html>";

        // echo $html; die;

        return $html;
    } 
    public function send_mailOnNewsroomCreation($company_id, $user_email)
    {
        $obj = ClassRegistry::init('Companies');
        $conditions = array('id' => $company_id);
        $data = $obj->find('first', array('conditions' => $conditions));
        $newsroom = $data['Companies'];
        $newsroom_name = $newsroom['name'];
        if (isset($newsroom['payment_status']) && (!empty($newsroom['payment_status']) && $newsroom['payment_status'] != 0)) {
            $payment_status = 'Success';
        }
        $here_about_us = $newsroom['hear_about_us'];
        $contact_name = $newsroom['contact_name'];
        $phone_number = $newsroom['phone_number'];
        $msg = '
        <table align="left" cellpadding="0" cellspacing="0" style="height: 280px; width: 600px; border: 1px solid #ccc; padding: 50px;">
            <tbody>
                <tr>
                    <td style="text-align:center"><img alt="' . $newsroom_name . '" src="' . SITEURL . 'website/img/emailwire-logo-inner.png" /></td>
                </tr>
                <tr>
                    <td>
                    <table align="left" cellpadding="0" cellspacing="0" style="width:630px">
                        <tbody>
                            <tr>
                                <td style="text-align:center"><h2>Details of new newsroom.</h2></td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><b>Company Name: </b> ' . $newsroom_name . '</td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><b>Contact Name: </b> ' . $contact_name . '</td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><b>Email: </b> ' . $user_email . '</td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><b>Phone: </b> ' . $phone_number . '</td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><b>Hear About Us: </b> ' . $here_about_us . '</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center">Thank You</td>
                            </tr>
                            <tr>
                                <td style="text-align:center"><a href="' . SITEURL . '">Email Wire</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';
        $site_name = strip_tags(Configure::read('Site.name'));
        $subject = "Details of news newsroom";
        $newTitle = $subject;
        $this->AWSSES->from = $newTitle . " <".$this->defaultSendMail.">";
        $this->AWSSES->to = trim(trim(strip_tags(Configure::read('Site.admin_email'))));
        $this->AWSSES->subject = $subject;
        $this->AWSSES->replayto = trim(strip_tags(Configure::read('Site.admin_email')));
        $this->AWSSES->htmlMessage = $msg;
        try {
            if (!$this->AWSSES->_aws_ses()) {
                App::uses('CakeEmail', 'Network/Email');
                $currency = Configure::read('Site.currency');
                $Email = new CakeEmail('smtp');
                $Email->from(array($this->defaultSendMail => $subject));
                $Email->to(trim(strip_tags(Configure::read('Site.admin_email'))));
                $Email->replyTo($email['reply_to_email']);
                $Email->subject($subject);
                $Email->emailFormat('html');
                ;
            } 
            return true;
        } catch (Exception $exc) {
              //$exc->getTraceAsString();
        }
        return true;
    }





    public function send_invoice_mail($msg = 'Emailwire Invoice', $email, $uName, $mailTo)
    {   error_reporting(false);
        $site_name = strip_tags(Configure::read('Site.name'));
        $subject = $email['subject'];
        $title = $email['title'];
        $newTitle = (!empty($title)) ? $title : $subject;
        $this->AWSSES->from = $newTitle . " <" . $email['from'] . ">";
        $this->AWSSES->to = trim($mailTo);
        $this->AWSSES->subject = $email['subject'];
        $this->AWSSES->replayto = trim($email['reply_to_email']);
        $this->AWSSES->htmlMessage = $msg;
        try {
            if (!$this->AWSSES->_aws_ses()) {
                App::uses('CakeEmail', 'Network/Email'); 
                $Email = new CakeEmail('smtp');
                $Email->from(array($email['from'] => $email['title'])); 
                $Email->to(trim($mailTo)); 
                $Email->replyTo($email['reply_to_email']); 
                $Email->subject($email['subject']); 
                $Email->emailFormat('html'); 
                $Email->send($msg);
            }

            return true;
        } catch (Exception $exc) {

            $exc->getTraceAsString();
        }
    }
 
 

    public function fetchCartData($user_id, $cartSessionId = '')
    {
        $obj = ClassRegistry::init('CartDetail');
        $conditions = array('cart_type' => 'plan', 'staff_user_id' => $user_id);
        if (!empty($cartSessionId)) {
            $conditions = array('cart_session_id' => $cartSessionId, 'cart_type' => 'plan', 'staff_user_id' => $user_id);
        }
        $data = $obj->find('all', array('conditions' => $conditions));
        $data = Set::extract('/CartDetail/.', $data);
        return (!empty($data)) ? $data : [];
    }

    public function fetchPlanData($plan_id)
    {

        $obj = ClassRegistry::init('Plan');

        $data = $obj->find('first', array('conditions' => array('Plan.id' => $plan_id)));

        return $data;
    }
    
    
     public function fetchMasterPlanData()
    {

        $obj = ClassRegistry::init('MasterPlan');

        $data = $obj->find('first');

        return $data;
    }



    public function checkCoupon($user_id, $coupon_id)
    {

        $obj = ClassRegistry::init('Coupon');

        $data = $obj->find('first', array('conditions' => array('Coupon.id' => $coupon_id, 'release_date <=' => date('Y-m-d'), 'end_date >=' => date('Y-m-d'))));

        if (empty($data)) {

            $cart = ClassRegistry::init('Cart');

            $cart->query("UPDATE `carts` SET `coupon_id` = '0' WHERE `carts`.`staff_user_id` = '$user_id' AND `coupon_id`>0 AND `cart_type`='plan'");
        }



        return $data;
    }



    public function getUserCartData($user_id, $cart_session_id = "", $coupon_data = '', $newsroom_amount = '0.00')
    {

        $cartData = [];

        $cartData['plans'] = [];

        $cartData["newsroom_amount"] = $newsroom_amount;

        $total = $subtotal = $discount = "0.00";

        $data_array = $this->fetchCartData($user_id, $cart_session_id);
        $master_plan_obj = $this->fetchMasterPlanData();

        if (!empty($data_array)) {

            foreach ($data_array as $index => $sess_data) {

                $cartData["cart_session_id"] = $sess_data["cart_session_id"];

                if ($sess_data["is_newsroom_incart"] > 0) {

                    $cartData["newsroom_amount"] = ($sess_data["is_newsroom_incart"] > 0) ? $master_plan_obj["MasterPlan"]["price"] : $newsroom_amount;
                    $cartData["newsroom_duration"] = $master_plan_obj["MasterPlan"]["duration"];
                    $cartData["company_id"] = ($sess_data["company_id"] > 0) ? $sess_data["company_id"] : 0;
                } else {

                    $amount = ($sess_data['bulk_discount_amount'] > 0) ? $sess_data['bulk_discount_amount'] : $sess_data['price'];



                    $plan_details = $this->fetchPlanData($sess_data['plan_id']);

                    if (isset($plan_details) && !empty($plan_details)) {

                        $cartData['plans'][$index]["plan_type"] = $plan_details['Plan']['plan_type'];
                    } else {

                        $cartData['plans'][$index]["plan_type"] = '';
                    }

                    $cartData['plans'][$index]["plan_id"] = $sess_data['plan_id'];

                    $cartData['plans'][$index]["title"] = $sess_data['name'];

                    $cartData['plans'][$index]["amount"] = $amount;

                    $subtotal += $amount;
                }
            }

            if (!empty($data_array["0"]['coupon_id']))

                $coupon_data = $this->checkCoupon($user_id, $data_array["0"]['coupon_id']);
        }

        $cartData['totals']["subtotal"] = (($subtotal + $cartData["newsroom_amount"]) > 0) ? round(($subtotal + $cartData["newsroom_amount"]), 2) : "0.00";

        $cartData["discount_id"] = '';

        $cartData["promo_code"] = '';

        if (!empty($coupon_data)) {

            $cartData["discount_id"] = $coupon_data['Coupon']['id'];

            $cartData["promo_code"] = $coupon_data['Coupon']['code'];

            $discount = $this->getCouponAmount($coupon_data['Coupon']['type'], $coupon_data['Coupon']['value'], $cartData['totals']["subtotal"]);
        }

        $cartData['totals']["discount"] = round($discount, 2);

        $cartData['totals']["tax"] = "0.00";

        $cartData['totals']["total"] = $this->get_cart_total($cartData["newsroom_amount"], $subtotal, $discount);

        return $cartData;
    }







    public function checkcart($user_id, $plan_id, $cart_type = null)
    {

        $obj = ClassRegistry::init('Cart');

        $count = $obj->find('count', array('conditions' => array('Cart.staff_user_id' => $user_id, 'Cart.plan_id' => $plan_id, 'Cart.cart_type' => $cart_type)));

        return $count;
    }



    public function checkCartType($user_id, $plan_id)
    {

        $obj = ClassRegistry::init('CartDetail');

        $data = $obj->find('first', array('fields' => ['plan_type'], 'conditions' => array('cart_type' => 'plan', 'staff_user_id' => $user_id)));

        if (!empty($data)) {

            $plan = ClassRegistry::init('Plan');

            $plan_detail = $plan->find('first', array('fields' => ['plan_type'], 'conditions' => array('Plan.id' => $plan_id)));

            if ($data['CartDetail']['plan_type'] == 'subscription') {



                //if($plan_detail['Plan']['plan_type']!='subscription')

                return false;
            } else {

                if ($plan_detail['Plan']['plan_type'] == 'subscription')

                    return false;
            }
        }

        return true;
    }



    public function getRemoveItemData($cart_plans = '', $plan_id)
    {

        $cartData = [];

        $promo_code = $discount_id = "";

        $discount = $total = $plan_amount = $subtotal = "0.00";

        $discount_id = $cart_plans["discount_id"];

        $promo_code = $cart_plans["promo_code"];

        $counter = 0;

        $cartData['plans'] = [];

        foreach ($cart_plans['plans'] as $index => $sess_data) {

            if ($sess_data['plan_id'] != $plan_id) {

                $cartData['plans'][$counter]["plan_id"] = $sess_data['plan_id'];

                $cartData['plans'][$counter]["title"] = $sess_data['title'];

                $cartData['plans'][$counter]["amount"] = $sess_data['amount'];

                $counter++;

                $plan_amount += $sess_data['amount'];
            }
        }

        $subtotal = ($plan_amount > 0) ? $plan_amount : "0.00";

        $discount = (!empty($cart_plans['totals']["discount"])) ? $cart_plans['totals']["discount"] : "0.00";

        if ($plan_amount <= 0) {

            $promo_code = $discount_id = "";

            $discount = "0.00";
        }

        $cartData["newsroom_amount"] = isset($cart_plans["newsroom_amount"]) ? $cart_plans["newsroom_amount"] : "0.00";

        $cartData["discount_id"] = $discount_id;

        $cartData["promo_code"] = $promo_code;

        $cartData['totals']["subtotal"] = (($subtotal + $cartData["newsroom_amount"]) > 0) ? round(($subtotal + $cartData["newsroom_amount"]), 2) : "0.00";

        $cartData['totals']["discount"] = round($discount, 2);

        $cartData['totals']["tax"] = "0.00";

        $cartData['totals']["total"] = $this->get_cart_total($cartData["newsroom_amount"], $subtotal, $cartData['totals']["discount"]);

        return $cartData;
    }





    public function addToCartWithSession($cart_plans = '', $plan_id = '')
    {

        $obj = ClassRegistry::init('Plan');

        $plan_data = $obj->find('first', array('conditions' => array('Plan.id' => $plan_id)));

        $amount = ($plan_data['Plan']['bulk_discount_amount'] > 0) ? $plan_data['Plan']['bulk_discount_amount'] : $plan_data['Plan']['price'];

        $cartData = [];

        $plan_amount = $total = $subtotal = $discount = "0.00";

        if (!empty($cart_plans)) {

            foreach ($cart_plans['plans'] as $index => $sess_data) {

                $cartData['plans'][$index]["plan_id"] = $sess_data['plan_id'];

                $cartData['plans'][$index]["title"] = $sess_data['title'];

                $cartData['plans'][$index]["amount"] = $sess_data['amount'];

                $plan_amount += $sess_data['amount'];

                if ($sess_data['plan_id'] == $plan_id)

                    throw new NotFoundException(__('This plan already in your cart.'));
            }



            $count = count($cart_plans['plans']);

            $cartData['plans'][$count]["plan_id"] = $plan_data['Plan']['id'];

            $cartData['plans'][$count]["title"] = $plan_data['PlanCategory']['name'];

            $cartData['plans'][$count]["amount"] = $amount;

            $subtotal = $plan_amount + $cartData['plans'][$count]["amount"];

            $discount = (!empty($cart_plans['totals']["discount"])) ? $cart_plans['totals']["discount"] : "0.00";
        } else {

            $cartData['plans']['0']["plan_id"] = $plan_data['Plan']['id'];

            $cartData['plans']['0']["title"] = $plan_data['PlanCategory']['name'];

            $subtotal = $cartData['plans']['0']["amount"] = $amount;
        }



        $cartData["newsroom_amount"] = isset($cart_plans["newsroom_amount"]) ? $cart_plans["newsroom_amount"] : "0.00";

        $cartData["discount_id"] = "";

        $cartData["promo_code"] = "";

        $cartData['totals']["subtotal"] = (($subtotal + $cartData["newsroom_amount"]) > 0) ? round(($subtotal + $cartData["newsroom_amount"]), 2) : "0.00";;

        $cartData['totals']["discount"] = round($discount, 2);

        $cartData['totals']["tax"] = "0.00";

        $cartData['totals']["total"] = $this->get_cart_total($cartData["newsroom_amount"], $subtotal, $cartData['totals']["discount"]);

        return $cartData;
    }



    public function getCartSessionId($user_id, $plan_id, $cart_type = "plan")
    {

        $obj = ClassRegistry::init('CartDetail');

        $data = $obj->find('first', array('fields' => ['cart_id', 'cart_session_id', 'coupon_id'], 'conditions' => array('cart_type' => $cart_type, 'staff_user_id' => $user_id), 'order' => "cart_id desc"));



        if (empty($data)) {

            /*Check newsroom in cart*/

            $data = $obj->find('first', array('fields' => ['cart_id', 'cart_session_id', 'coupon_id'], 'conditions' => array('cart_type' => $cart_type, 'staff_user_id' => $user_id, 'is_newsroom_incart' => '1'), 'order' => "cart_id desc"));
        }

        return (!empty($data)) ? $data['CartDetail'] : [];
    }



    public function addToCartWithDb($cart_plans, $plan_id = '', $user_id, $is_newsroom_incart = '0')
    {

        $Cart = ClassRegistry::init('Cart');

        $currency = Configure::read('Site.currency');

        $plan_amount = $total = $subtotal = $discount = "0.00";

        $getCartSession = $this->getCartSessionId($user_id, $plan_id);

        $cart_session_id = (!empty($getCartSession)) ? $getCartSession['cart_session_id'] : Security::hash(CakeText::uuid(), 'sha1', true);

        $coupon_id = (!empty($getCartSession['coupon_id'])) ? $getCartSession['coupon_id'] : "";

        if (!empty($cart_plans)) {

            if ($cart_plans['totals']['subtotal'] > 0) {

                foreach ($cart_plans['plans'] as $index => $sess_data) {

                    $saveData['Cart'][$index]["plan_id"] = $sess_data['plan_id'];

                    $saveData['Cart'][$index]["staff_user_id"] = $user_id;

                    $saveData['Cart'][$index]['cart_session_id'] = $cart_session_id;

                    $saveData['Cart'][$index]['coupon_id'] = $coupon_id;
                }

                $count = count($cart_plans['plans']);

                if ($is_newsroom_incart == 1) {

                    $saveData['Cart'][$count]['staff_user_id'] = $user_id;

                    $saveData['Cart'][$count]['plan_id'] = '';

                    $saveData['Cart'][$count]['is_newsroom_incart'] = $is_newsroom_incart;

                    $saveData['Cart'][$count]['cart_session_id'] = $cart_session_id;

                    $saveData['Cart'][$count]['coupon_id'] = $coupon_id;
                }

                $Cart->saveMany($saveData['Cart']);
            } else {

                $saveData['Cart']['cart_session_id'] = $cart_session_id;

                $saveData['Cart']['coupon_id'] = $coupon_id;

                $saveData['Cart']['staff_user_id'] = $user_id;

                $saveData['Cart']['plan_id'] = $plan_id;

                $saveData['Cart']['is_newsroom_incart'] = $is_newsroom_incart;

                $Cart->save($saveData);
            }
        } else {

            $saveData['Cart']['cart_session_id'] = $cart_session_id;

            $saveData['Cart']['coupon_id'] = $coupon_id;

            $saveData['Cart']['staff_user_id'] = $user_id;

            $saveData['Cart']['plan_id'] = $plan_id;

            $saveData['Cart']['next_email'] = date('Y-m-d', strtotime('+7 days'));

            $saveData['Cart']['is_newsroom_incart'] = $is_newsroom_incart;

            $Cart->save($saveData);
        }

        $cartData = $this->getUserCartData($user_id);

        return $cartData;
    }







    public function applyCouponWithSession($cart_plans, $coupon_data)
    {

        if (count($cart_plans['plans']) > 0 && $cart_plans['totals']["total"] > 0) {

            if (!empty($coupon_data)) {

                $discount_id = $coupon_data['Coupon']['id'];

                $promo_code = $coupon_data['Coupon']['code'];

                $subtotal = $cart_plans['totals']["subtotal"];

                $discount = $this->getCouponAmount($coupon_data['Coupon']['type'], $coupon_data['Coupon']['value'], $subtotal);



                $cart_plans["newsroom_amount"] = isset($cart_plans["newsroom_amount"]) ? $cart_plans["newsroom_amount"] : "0.00";

                $cart_plans["discount_id"] = $discount_id;



                $cart_plans["promo_code"] = (!empty($promo_code)) ? $promo_code : "";

                $cart_plans['totals']["subtotal"] = round(($subtotal + $cart_plans["newsroom_amount"]), 2);

                if ($discount >= $subtotal) {

                    $cart_plans['totals']["discount"] = $cart_plans['totals']["subtotal"];
                } else {

                    $cart_plans['totals']["discount"] = $discount;
                }

                $cart_plans['totals']["tax"] = "0.00";

                $cart_plans['totals']["total"] = $this->get_cart_total($cart_plans["newsroom_amount"], $subtotal, $cart_plans['totals']["discount"]);

                return $cart_plans;
            } else {

                throw new NotFoundException(__('Invalid Promo Code.'));
            }
        } else {

            throw new NotFoundException(__('Please add plan in cart.'));
        }
    }





    public function getprnumber($id)
    {

        $obj = ClassRegistry::init('Plan');

        $data = $obj->find('first', array('conditions' => array('Plan.id' => $id), 'fields' => array('number_pr')));

        return ($data['Plan']['number_pr'] > "0") ? $data['Plan']['number_pr'] : "0";
    }





    public function getRemaingPR($pid, $staffUserId)
    {

        $obj = ClassRegistry::init('RemainingUserPlan');

        $data = $obj->find('first', array('conditions' => array('RemainingUserPlan.plan_id' => $pid, 'RemainingUserPlan.staff_user_id' => $staffUserId, 'number_pr !=' => '0'), 'fields' => array('number_pr')));

        return (!empty($data)) ? $data['RemainingUserPlan']['number_pr'] : "0";
    }





    public function getprcartdata($user_id = '', $plan_id = '', $pressrelease_id = null, $selectedfeatures = '')
    {
        
        // echo "user_id_".$user_id."<br>";
        // echo "plan_id_".$plan_id."<br>";
        // echo "pressrelease_id_".$pressrelease_id."<br>";
        // echo "selectedfeatures_".$selectedfeatures."<br>";
        //die;
        $cart_plans['feature'] = $cart_plans['prlist'] = $cart_plans = [];
        $currency = Configure::read('Site.currency');
        $Cart = ClassRegistry::init('Cart');
        $obj = ClassRegistry::init('Plan');
        $DistributionsPressRelease = ClassRegistry::init('DistributionsPressRelease');
        if ($pressrelease_id) {
            $index = "0";

            $cart_plans['totals']['subtotal'] = $cart_plans['totals']['discount'] = $cart_plans['totals']['tax'] = $cart_plans['totals']['total'] = $famount = $tax = $discount = $plan_amount = '0.00';
            //$conditions = array('Cart.staff_user_id' => $user_id, 'Cart.plan_id' => $plan_id, 'cart_type' => 'pr');
            $conditions = array('Cart.staff_user_id' => $user_id, 'cart_type' => 'pr', 'press_release_id' => $pressrelease_id);
            $checkcart = $Cart->find('first', array('conditions' => $conditions));
            
            if(empty($checkcart)){
                $conditions = array('Cart.staff_user_id' => $user_id, 'cart_type' => 'pr', 'press_release_id' =>0);
                $checkcart = $Cart->find('first', array('conditions' => $conditions));
                $Cart->id = $checkcart['Cart']['id'];
                $Cart->saveField('press_release_id', $pressrelease_id);
                $conditions = array('Cart.staff_user_id' => $user_id, 'cart_type' => 'pr', 'press_release_id' => $pressrelease_id);
                $checkcart = $Cart->find('first', array('conditions' => $conditions));
            }
             
            if (!empty($checkcart)) {

                $cart_plans['cart_session_id'] = (!empty($checkcart['Cart']['cart_session_id'])) ? $checkcart['Cart']['cart_session_id'] : Security::hash(CakeText::uuid(), 'sha1', true);

                $plan = $obj->find('first', array('conditions' => array('Plan.id' => $plan_id)));

                if ($checkcart['Cart']['extra_words'] > 0) {

                    $amt = ceil(($checkcart['Cart']['extra_words'] / 100)) * $plan['Plan']['add_word_amount'];

                    $amount = number_format($amt, 2);

                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;

                    $cart_plans['prlist'][$index]["title"] = "Additional words charges";

                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amount;

                    $cart_plans['prlist'][$index]["class"] = 'words_charges';

                    $plan_amount += $amount;

                    $index++;
                }



                if ($checkcart['Cart']['extra_category'] > 0) {

                    // $amt=($checkcart['Cart']['extra_category'])*($plan['Plan']['add_word_amount']);

                    $amt = ($checkcart['Cart']['extra_category']) * ($plan['Plan']['add_category_charges']);

                    $amount = number_format($amt, 2);

                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;

                    $cart_plans['prlist'][$index]["title"] = "Additional category charges";

                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amount;

                    $cart_plans['prlist'][$index]["class"] = 'category_charges';

                    $plan_amount += $amt;

                    $index++;
                }



                if ($checkcart['Cart']['extra_msa'] > 0) {

                    $amtmsa = ceil($checkcart['Cart']['extra_msa'] / $plan['Plan']['msa_limit']) * ($plan['Plan']['add_msa_charges']);

                    $amountmsa = number_format($amtmsa, 2);

                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;

                    $cart_plans['prlist'][$index]["title"] = "Additional MSA charges";

                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amountmsa;

                    $cart_plans['prlist'][$index]["class"] = 'msa_charges';

                    $plan_amount += $amtmsa;

                    $index++;
                }



                if ($checkcart['Cart']['extra_state'] > 0) {

                    $amtstate = ceil($checkcart['Cart']['extra_state'] / $plan['Plan']['state_limit']) * ($plan['Plan']['add_state_charges']);

                    $amountstate = number_format($amtstate, 2);

                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;

                    $cart_plans['prlist'][$index]["title"] = "Additional state charges";

                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amountstate;

                    $cart_plans['prlist'][$index]["class"] = 'state_charges';

                    $plan_amount += $amtstate;

                    $index++;
                }

                if ($checkcart['Cart']['translate_charges'] > 0) {
                    $amttx = $plan['Plan']['translation_amount'];
                    $amttx = number_format($amttx, 2);
                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;
                    $cart_plans['prlist'][$index]["title"] = "Additional page tranlate charges";
                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amttx;
                    $cart_plans['prlist'][$index]["class"] = 'trans_charges';
                    $plan_amount += $amttx;
                    $index++;
                }

                $removefeaturesIds = "";

                $existsfeatureIds = $feature = [];

                if (!empty($pressrelease_id)) {

                    $features = $DistributionsPressRelease->find('list', array('conditions' => array("DistributionsPressRelease.press_release_id" => $pressrelease_id), 'fields' => ['distribution_id', 'distribution_id']));

                    if (!empty($features)) {

                        $comma = "";

                        $listId = $checkcart['Cart']['list_id'];

                        $cart_plans['list_id'] = $listId;

                        foreach ($features as $index => $featureId) {
                            $existsfeatureIds[$index] = $featureId;
                            $feature[$index]['distribution_id'] = $featureId;
                            $featureData = $this->getprfeatureprice($featureId);
                            $featureAmount = $this->getAmountMailList($listId, $featureId, $featureData['amount'], $featureData['number']);

                            if (isset($plan['PlanCategory']['is_featured_pr']) && $plan['PlanCategory']['is_featured_pr'] == 1 && $featureId == 2) {
                                $featureAmount = 0;
                            }

                            $cart_plans['feature'][$index]['price'] = $currency . '' . $featureAmount;
                            $cart_plans['feature'][$index]['name'] = $featureData['name'];
                            $cart_plans['feature'][$index]['class'] = 'feature-' . $featureId;
                            $famount = $famount + $featureAmount;
                        }
                    }
                } else {

                    $features = unserialize($checkcart['Cart']['distribution_ids']);

                    $listId = $checkcart['Cart']['list_id'];

                    $cart_plans['list_id'] = $listId;

                    $comma = "";



                    if (!empty($checkcart['Cart']['distribution_ids'])) {
                        foreach ($features as $index => $value) {
                            $existsfeatureIds[$index] = $value['distribution_id'];
                            $feature[$index]['distribution_id'] = $value['distribution_id'];
                            $featureData = $this->getprfeatureprice($value['distribution_id']);
                            $featureAmount = $this->getAmountMailList($listId, $value['distribution_id'], $featureData['amount'], $featureData['number']);

                            if (isset($plan['PlanCategory']['is_featured_pr']) && $plan['PlanCategory']['is_featured_pr'] == 1 && $value['distribution_id'] == 2) {
                                $featureAmount = 0;
                            }

                            $cart_plans['feature'][$index]['price'] = $currency . '' . $featureAmount;
                            $cart_plans['feature'][$index]['name'] = $featureData['name'];
                            $cart_plans['feature'][$index]['class'] = 'feature-' . $value['distribution_id'];
                            $famount = $famount + $featureAmount;
                        }
                    }
                }

                $updateCart['Cart']['id'] = $checkcart['Cart']['id'];

                $updateCart['Cart']['distribution_ids'] = (!empty($feature)) ? serialize($feature) : null;

                $Cart->save($updateCart);





                $plan_amount += $famount;

                $cart_plans['totals']['subtotal'] = number_format($plan_amount, 2);

                $cart_plans['totals']['discount'] = number_format($discount, 2);

                $cart_plans['totals']['tax'] = $tax;

                $cart_plans['totals']['total'] = $this->get_cart_total('0', $plan_amount, $discount);
            }
        }
        return $cart_plans;
    }







    function getprfeatureprice($featureId = "")
    {

        if (!empty($featureId)) {

            $obj = ClassRegistry::init('Distribution');

            $data = $obj->find('first', array('conditions' => array('id' => $featureId), 'fields' => array('name', 'amount', 'number')));

            return $data['Distribution'];
        }
    }



    public function getAmountMailList($listId, $distribution_id, $amount, $number)
    {

        if ($distribution_id == 8) {

            $subscriber = ClassRegistry::init('Subscriber');

            $count = $subscriber->find("count", array('conditions' => array("Subscriber.list" => $listId)));

            if ($count > $number) {

                $amount = ceil($count / $number) * $amount;
            }
        }

        return ($amount > 0) ? number_format($amount, 2) : "0.00";;
    }





    public function checkprcart($user_id = '', $plan_id = '', $pressrelease_id = '')
    {

        $Cart = ClassRegistry::init('Cart');



        $conditions = array('Cart.staff_user_id' => $user_id, 'plan_id' => $plan_id, 'cart_type' => 'pr');

        if ($pressrelease_id != "") {

            $conditions = array('Cart.staff_user_id' => $user_id, 'plan_id' => $plan_id, 'cart_type' => 'pr', 'press_release_id' => $pressrelease_id);
        }

        $checkcart = $Cart->find('first', array('conditions' => $conditions));

        return $checkcart;
    }



    public function get_save_transaction_formateddata($user_id, $cartSessionId = '', $coupon_data = '', $newsroom_amount = '0.00')
    {

        $cartData['Transaction'] = [];

        $cartData['TransactionPlan'] = [];

        $cartData['Transaction']["newsroom_amount"] = $newsroom_amount;

        $total = $subtotal = $discount = "0.00";

        $data_array = $this->fetchCartData($user_id, $cartSessionId);
        
        $planloop = 0;
$master_plan_obj = $this->fetchMasterPlanData();
        if (!empty($data_array)) {

            foreach ($data_array as $index => $sess_data) {

                if ($sess_data["is_newsroom_incart"] > 0) {

                    $cartData['Transaction']["newsroom_amount"] = ($sess_data["is_newsroom_incart"] > 0) ? $master_plan_obj["MasterPlan"]["price"] : $newsroom_amount;

                    $cartData['Transaction']["company_id"] = ($sess_data["company_id"] > 0) ? $sess_data["company_id"] : 0;
                } else {

                    $amount = ($sess_data['bulk_discount_amount'] > 0) ? $sess_data['bulk_discount_amount'] : $sess_data['price'];



                    $plan_details = $this->fetchPlanData($sess_data['plan_id']);
                    
                    

                    if (isset($plan_details) && !empty($plan_details)) {

                        //pr($plan_details);die;

                        $cartData['TransactionPlan'][$planloop]["plan_type"]   =   $plan_details['Plan']['plan_type'];
                    } else {

                        $cartData['TransactionPlan'][$planloop]["plan_type"]   =   '';
                    }

                    $cartData['TransactionPlan'][$planloop]["plan_id"] = $sess_data['plan_id'];

                    $cartData['TransactionPlan'][$planloop]["title"] = $sess_data['name'];

                    $cartData['TransactionPlan'][$planloop]["plan_amount"] = $amount;

                    $subtotal += $amount;

                    $planloop++;
                }
            }

            $coupon_data = $this->checkCoupon($user_id, $data_array["0"]['coupon_id']);
        }

        $cartData['Transaction']["subtotal"] = (($subtotal + $cartData['Transaction']["newsroom_amount"]) > 0) ? round(($subtotal + $cartData['Transaction']["newsroom_amount"]), 2) : "0.00";



        $cartData['Transaction']["promo_code"] = '';

        $cartData['Transaction']["discount_id"] = "";

        if (!empty($coupon_data)) {

            $cartData['Transaction']["discount_id"] = $coupon_data['Coupon']['id'];

            $cartData['Transaction']["promo_code"] = $coupon_data['Coupon']['code'];

            $discount = $this->getCouponAmount($coupon_data['Coupon']['type'], $coupon_data['Coupon']['value'], $cartData['Transaction']["subtotal"]);
        }



        $cartData['Transaction']["discount"] = round($discount, 2);

        $cartData['Transaction']["tax"] = "0.00";

        $cartData['Transaction']["total"] = $this->get_cart_total($cartData['Transaction']["newsroom_amount"], $subtotal, $discount);

        return $cartData;
    }





    public function updateview($id = null, $viewscount)
    {
        if ($id != null) {
            $obj = ClassRegistry::init('PressRelease');
            $savedata['PressRelease']['id'] = $id;
            $savedata['PressRelease']['views'] = $viewscount + 1;
            $obj->save($savedata);
        }
    }
    public function document_upload($filename, $tmpname, $path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        move_uploaded_file($tmpname, $path . "/" . $filename);
    }



    public function getYouTubeId($url = '')
    {
        $youtube_id = '';
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';
        if (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        if (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        return $youtube_id;
    }



    public function getPrSingleImage($imageArr = '')
    {

        $imageUrl = SITEURL . "img/no_image.jpeg";

        $image_alt = '';

        if (!empty($imageArr)) {

            $image_path = $imageArr[0]['image_path'];

            $image_name = $imageArr[0]['image_name'];



            $imageUrl = SITEURL . 'files/company/press_image/' . $image_path . '/' . $image_name;

            $fileUrl = WWW_ROOT . 'files' . DS . 'company' . DS . 'press_image' . DS . $image_path . DS . $image_name;

            if (!file_exists($fileUrl)) {

                $imageUrl = SITEURL . "img/no_image.jpeg";
            }
        }

        return  $imageUrl;
    }

    public function strockticker($str)
    {

        $obj = ClassRegistry::init('StockTicker');

        $stockTickers = $obj->find('list', array('fields' => array('name', 'replace_with')));

        if (!empty($stockTickers) && !empty($str)) {

            foreach ($stockTickers as $name => $replacewith) {

                $replacements[$name] = "<a style='text-decoration:none;color:black' target='_blank' rel='nofollow' href='" . $replacewith . "' title='$name'>$name</a>";;

                $replacements[str_replace(":", ": ", $name)] = "<a target='_blank' rel='nofollow' href='" . $replacewith . "' title='$name'>" . str_replace(":", ": ", $name) . "</a>";



                $replacements[str_replace(":", " :", $name)] = "<a target='_blank' rel='nofollow' href='" . $replacewith . "' title='$name'>" . str_replace(":", " :", $name) . "</a>";



                $replacements[str_replace(":", " : ", $name)] = "<a target='_blank' rel='nofollow' href='" . $replacewith . "' title='$name'>" . str_replace(":", " : ", $name) . "</a>";
            }

            $str = str_replace(array_keys($replacements), $replacements, $str);
        }

        return $str;
    }



    function get_domain($url)
    {

        $pieces = parse_url($url);

        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {

            return $regs['domain'];
        }

        return false;
    }



    public function getEmailforClippingReport($getemail = '')
    {

        $email = "";

        $emailFirstPart = explode("@", $getemail);

        $countemailchar = strlen($emailFirstPart[0]);

        $newemailpart = $emailFirstPart[0];

        for ($loop = 0; $loop < $countemailchar; $loop++) {

            if ($loop > 1) {

                if ($loop == ($countemailchar - 1)) {

                    $email .= $newemailpart[$loop];
                } else {

                    $email .= "*";
                }
            } else {

                $email .= $newemailpart[$loop];
            }
        }



        return $email . "@" . $emailFirstPart[1];
    }





    public function getCampaignDetails($prId)
    {

        $obj = ClassRegistry::init('Campaign');

        $check = $obj->find('first', array("fields" => array("sent", "recipients", "id", "to_send", "timezone", "send_date", 'createdfrom', 'userID', 'created', 'opens'), 'conditions' => array("Campaign.press_release_id" => $prId)));

        return (!empty($check)) ? $check['Campaign'] : [];
    }



    public function getSentMailReport($opens = '')
    {

        $result = [];

        if (!empty($opens)) {

            //$campaign=$this->Campaign->find('first',array("fields"=>array("opens"),"conditions"=>array('id' => $cid)));

            $last_subscriber_ids = [];

            if (!empty($opens)) {

                $last_opens_array = explode(',', $opens);

                $loop_no = count(array_unique($last_opens_array));

                for ($z = 0; $z < $loop_no; $z++) {

                    $last_opens_array2 = array_reverse(array_unique($last_opens_array));

                    $subscriber_id = explode(':', $last_opens_array2[$z]);

                    $last_subscriber_ids[] = $subscriber_id[0];
                }
            }

            $conditions = array("id" => $last_subscriber_ids);

            $obj = ClassRegistry::init('Subscriber');

            $result = $obj->find('list', array('conditions' => $conditions, 'fields' => array('id', 'email'), 'order' => 'Subscriber.id DESC'));
        }

        return $result;
    }





    public function getSendyUnsubscribedMailList($cId = '')
    {

        $result = "";

        if (!empty($cId)) {

            $result = ClassRegistry::init('Subscriber')->find("list", array('conditions' => ['last_campaign' => $cId, 'unsubscribed' => "1"], 'fields' => array('id', 'email'), 'order' => 'Subscriber.id DESC'));
        }

        return $result;
    }



    public function getNewsletterOpenMailList($pId = '')
    {

        $result = "";

        if (!empty($pId)) {

            $result = ClassRegistry::init('ClippingReport')->find("all", array('conditions' => ['press_release_id' => $pId, 'distribution_type' => "mail_feed", 'is_subscriber' => "1"], 'fields' => array('email', 'views', 'ClippingReport.created'), 'order' => 'ClippingReport.id DESC'));
        }

        return (!empty($result)) ? $result : $result;
    }

    public function getNewsletterSentMailList($pId = '')
    {

        $results = [];

        if (!empty($pId)) {

            $results = ClassRegistry::init('NewsletterLog')->find("all", array('conditions' => ['NewsletterLog.press_release_id' => $pId], 'fields' => array('StaffUser.id', 'StaffUser.email', 'NewsletterLog.created'), 'order' => 'NewsletterLog.id DESC'));
        }

        return $results;
    }

    public function getNewsletterReceivedMailList($pId = '')
    {

        $results = [];

        if (!empty($pId)) {

            $results = ClassRegistry::init('NewsletterLog')->find("all", array('conditions' => ['NewsletterLog.press_release_id' => $pId, 'NewsletterLog.is_mail_sent' => 1], 'fields' => array('StaffUser.id', 'StaffUser.email', 'NewsletterLog.created'), 'order' => 'NewsletterLog.id DESC'));
        }

        return $results;
    }





    public function getSendyBouncedMailList($cId = '')
    {

        $result = "";

        if (!empty($cId)) {

            $result = ClassRegistry::init('Subscriber')->find("list", array('conditions' => ['last_campaign' => $cId, 'bounce_soft' => "1"], 'fields' => array('id', 'email'), 'order' => 'Subscriber.id DESC'));
        }

        return $result;
    }



    public function getSendySpamMailList($cId = '')
    {

        $result = "";

        if (!empty($cId)) {

            $result = ClassRegistry::init('Subscriber')->find("list", array('conditions' => ['last_campaign' => $cId, 'complaint' => "1"], 'fields' => array('id', 'email'), 'order' => 'Subscriber.id DESC'));
        }

        return $result;
    }



    public function getCountryNameByCountyCode($sortname = '')
    {

        if (!empty($sortname)) {

            $obj = ClassRegistry::init('Country');

            $data = $obj->find('first', array('conditions' => array('sortname' => $sortname), 'fields' => array('name')));

            $sortname = $data['Country']['name'];
        }

        return $sortname;
    }



    public function getSendyCountryReport($opens = '')
    {
        $countries = [];
        if (!empty($opens)) {
            if (!empty($opens)) {
                $last_opens_array = explode(',', $opens);
                $loop_no = count(array_unique($last_opens_array));
                for ($z = 0; $z < $loop_no; $z++) {
                    $subscriber_id = explode(':', $last_opens_array[$z]);
                    if (isset($subscriber_id[1]) && !empty($subscriber_id[1])) {
                        $countries[] = $subscriber_id[1];
                    }
                }
            }
        }
        return $countries;
    }

    public function getLinksMailList($cId = '')
    {

        $result = "";

        if (!empty($cId)) {

            $result = ClassRegistry::init('Link')->find("list", array('conditions' => ['campaign_id' => $cId], 'fields' => array('link', 'clicks')));
        }

        return $result;
    } 

    function png_to_jpg($company_logo_path, $company_logo_path1, $quality)
    {

        $image = imagecreatefrompng($company_logo_path);

        list($width, $height) = getimagesize($company_logo_path);

        $output = imagecreatetruecolor($width, $height);

        $white = imagecolorallocate($output,  255, 255, 255);

        imagefilledrectangle($output, 0, 0, $width, $height, $white);

        imagecopy($output, $image, 0, 0, 0, 0, $width, $height);

        imagejpeg($output, $company_logo_path1, $quality);

        imagedestroy($image);
    }


    function rssMediaReport($data_array=[]){
        $dateFormat = strip_tags(Configure::read('Site.DateFromat'));
        $clippingRow="";
        if(!empty($data_array)){
            $clippingRow .= "<div style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'> RSS Media Distribution</div>
            <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='table table-striped'>
            <thead>
                <th style='text-align:left;'>S/N</th>
                <th style='text-align:left;'>Name</th> 
                <th style='text-align:left;'>Published URL</th> 
                <th style='text-align:left;'>Distribution Type</th>
                <th style='text-align:left;'>Views</th>                  
                <th style='text-align:left;'>Distribution Date</th> 
            </thead>";
            $rss_sno = 0;
           
            foreach ($data_array as $index => $rssmedia) {
                if (isset($rssmedia['ClippingReport'])) {
                    if ($rssmedia["ClippingReport"]['distribution_type'] == "rss_feed") {
                        $rss_sno++;
                        $release_page_url = "<a style='text-decoration:none;color:black' target='_blank' href=" . $rssmedia["ClippingReport"]['release_page_url'] . ">" . $rssmedia["ClippingReport"]['domain'] . "</a>";
                        $clippingRow .= "<tr style='padding-top:3px;text-align:left;'><td>" . $rss_sno . "</td><td style='text-align:left;'>" . $rssmedia["ClippingReport"]['site_name'] . "</td><td style='text-align:left;'>$release_page_url</td><td style='text-align:left;'>" . str_replace("_FEED", " ", strtoupper($rssmedia["ClippingReport"]['distribution_type'])) . "</td> <td style='text-align:left;'>" . $rssmedia["ClippingReport"]['views'] . "</td><td style='text-align:left;'>" . date($dateFormat, strtotime($rssmedia["ClippingReport"]['created'])) . "</td></tr>";
                    }
            }
            }

            $clippingRow   .= "</table> ";
        }
        return $clippingRow;
    }
    function jsMediaReport($data_array=[]){
        $dateFormat = strip_tags(Configure::read('Site.DateFromat'));
        $clippingRow="";
        if(!empty($data_array)){
            $clippingRow .= "<div style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>
                    JS Media Distribution
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <thead>
                    <th style='text-align:left;'>S/N</th>
                    <th style='text-align:left;'>Name</th> 
                    <th style='text-align:left;'>Published URL</th> 
                    <th style='text-align:left;'>Distribution Type</th>
                    <th style='text-align:left;'>Views</th>                  
                    <th style='text-align:left;'>Distribution Date</th>
                </thead>";
                $js_sno = 0;
                foreach ($data_array as $index => $jsmedia) {
                    if (isset($jsmedia['ClippingReport'])) {
                        if ($jsmedia["ClippingReport"]['distribution_type'] == "js_feed") {
                            $js_sno++;
                            $release_page_url = "<a style='text-decoration:none;color:black' target='_blank' href=" . $jsmedia["ClippingReport"]['release_page_url'] . ">" . $jsmedia["ClippingReport"]['release_page_url'] . "</a>";
                            $clippingRow .= "<tr style='padding-top:3px'><td>" . $js_sno . "</td><td style='text-align:left;'>" . $jsmedia["ClippingReport"]['site_name'] . "</td><td style='text-align:left;'>$release_page_url</td><td style='text-align:left;'>" . str_replace("_FEED", " ", strtoupper($jsmedia["ClippingReport"]['distribution_type'])) . "</td> <td style='text-align:left;'>" . $jsmedia["ClippingReport"]['views'] . "</td><td style='text-align:left;'>" . date($dateFormat, strtotime($jsmedia["ClippingReport"]['created'])) . "</td></tr>";
                        }
                    }
                }
                $clippingRow   .= "</table> "; 
        }
        return $clippingRow;
    }

    function socialMediaReport($data_array=[]){
        $dateFormat = strip_tags(Configure::read('Site.DateFromat'));
        $clippingRow="";
        if(!empty($data_array)){
            $clippingRow .= "<div style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>
                    Social Media Distribution
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <thead>
                    <th style='text-align:left;'>S/N</th>
                    <th style='text-align:left;'>Name</th> 
                    <th style='text-align:left;'>Published URL</th> 
                    <th style='text-align:left;'>Distribution Type</th>
                    <th style='text-align:left;'>Views</th>                  
                    <th style='text-align:left;'>Distribution Date</th>
                </thead>";
                $js_sno = 0;
                foreach ($data_array as $index => $jsmedia) {
                    if (isset($jsmedia['ClippingReport'])) {
                        if ($jsmedia["ClippingReport"]['distribution_type'] == "social_media_feed") {
                            $js_sno++;
                            $release_page_url = "<a style='text-decoration:none;color:black' target='_blank' href=" . $jsmedia["ClippingReport"]['release_page_url'] . ">" . $jsmedia["ClippingReport"]['release_page_url'] . "</a>";
                            $clippingRow .= "<tr style='padding-top:3px'><td>" . $js_sno . "</td><td style='text-align:left;'>" . $jsmedia["ClippingReport"]['site_name'] . "</td><td style='text-align:left;'>$release_page_url</td><td style='text-align:left;'>" . str_replace("_FEED", " ", strtoupper($jsmedia["ClippingReport"]['distribution_type'])) . "</td> <td style='text-align:left;'>" . $jsmedia["ClippingReport"]['views'] . "</td><td style='text-align:left;'>" . date($dateFormat, strtotime($jsmedia["ClippingReport"]['created'])) . "</td></tr>";
                        }
                    }
                }
                $clippingRow   .= "</table> "; 
        }
        return $clippingRow;
    } 
    function countryReportsByEmail($champ){
        $dateFormat = strip_tags(Configure::read('Site.DateFromat'));
        $clippingRow="";
        if ($champ) {
            $countryReport = $this->getSendyCountryReport($champ['opens']);
            if ($countryReport) {
                $clippingRow .= "
                <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                    Client media list opened by country
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' class='table table-striped'>
                <thead>
                    <th style='text-align:left;'>S/N</th>
                    <th style='text-align:left;'>Country</th>
                    <th style='text-align:left;'>Views</th>
                    <th style='text-align:left;'>Distribution Type</th> 
                    <th style='text-align:left;'>Distribution Date</th> 
                </thead>";
                $count = 0;
                $unique_countries = array_unique($countryReport);
                $counts = array_count_values($countryReport);
                foreach ($unique_countries as $index => $country) {
                    $views = "0";
                    $name = $this->getCountryNameByCountyCode($country);
                    if (!empty($country)) {
                        $views = $counts[$country];
                    }
                    $clippingRow .= "<tr style='padding-top:3px'><td style='text-align:left;'>" . ($count + 1) . "</td><td style='text-align:left;'>" . $name . "</td><td style='text-align:left;'>$views</td><td style='text-align:left;'>Mail</td><td style='text-align:left;'>" . date($dateFormat, strtotime($champ['created'])) . "</td></tr>";
                    $count++;
                }
                $clippingRow   .= "</table>";
            }
        }
        return $clippingRow;
    }
    
    public function decryptSubscriberToken($token = '')
    {

        $parts = explode("-", $token);

        return substr($parts[0], -1);
    }

    public function genrateSubscriberToken($slug, $userId)
    {

        $hash = Security::hash(CakeText::uuid(), 'sha1', true);

        $position = rand(0, strlen($hash));

        return SITEURL . $slug . '/' . substr_replace($hash, $userId . "-", $position, 0);
    }











    public function getCatArr($cats = '')
    {

        $categoryIds = [];

        if (!empty($cats)) {

            foreach ($cats as $key => $category) {

                $categoryIds[] = $category['id'];
            }
        }

        return $categoryIds;
    }



    public function getMsaArr($Msa = '')
    {

        $msaIds = [];

        if (!empty($Msa)) {

            foreach ($Msa as $key => $vale) {

                $msaIds[] = $vale['id'];
            }
        }

        return $msaIds;
    }



    public function get_newsletter_sendmail_date($id = null)
    {

        if (!empty($id)) {

            $staffUser = ClassRegistry::init('StaffUser');

            $check = $staffUser->find('first', array("fields" => array("newsletter_send_mail_date", "newsletter_cycle"), 'conditions' => array("StaffUser.id" => $id)));

            $sendmaildate = (!empty($check['StaffUser']['newsletter_send_mail_date'])) ? $check['StaffUser']['newsletter_send_mail_date'] : "";

            $today = strtotime(date('Y-m-d'));

            $savedDate = strtotime($check['StaffUser']['newsletter_send_mail_date']);

            if (empty($sendmaildate) || ($savedDate < $today)) {

                $cycle = (!empty($check['StaffUser']['newsletter_cycle'])) ? $check['StaffUser']['newsletter_cycle'] : "d";

                $sendmaildate = $this->getdatebynewslettercycle($cycle);

                $saveData['StaffUser']['id'] = $id;

                $saveData['StaffUser']['newsletter_cycle'] = $cycle;

                $saveData['StaffUser']['newsletter_send_mail_date'] = $sendmaildate;

                $staffUser->save($saveData);
            }

            return $sendmaildate;
        }
    }



    public function getdatebynewslettercycle($cycle = '')
    {

        switch ($cycle) {

            case 'w':

                $date = date('Y-m-d', strtotime('+7 days'));

                break;

            case 'y':

                $date = date('Y-m-d', strtotime('+30 days'));

                break;

            case 'y':

                $date = date('Y-m-d', strtotime('+365 days'));

                break;

            default:

                $date = date('Y-m-d', strtotime('+1 days'));

                break;
        }

        return $date;
    }



    public function deleteCart($cartSessionId = '')
    {
        $cart = ClassRegistry::init('Cart');
        if (!empty($cartSessionId))
            $cart->query("DELETE  FROM `carts` WHERE `cart_session_id` LIKE '$cartSessionId'");
        return true;
    }



    public function checkLastTransactionStatusOfSubscrPayment($subscr_id)
    {

        $trxn = ClassRegistry::init('Transaction');

        $fields = ['status', 'subscr_status', 'reason_unsubscriber', 'id'];

        $data = $trxn->find('first', array('conditions' => array('Transaction.subscr_id' => $subscr_id), "fields" => $fields, 'order' => "Transaction.paymant_date DESC"));

        return (!empty($data['Transaction'])) ? $data['Transaction'] : [];
    }



    public function get_last_mail_sent_date($cycle = '')
    {

        switch ($cycle) {

            case 'w':

                $date = date('Y-m-d', strtotime('-7 days'));

                break;

            case 'm':

                $date = date('Y-m-d', strtotime('-30 days'));

                break;

            case 'y':

                $date = date('Y-m-d', strtotime('-365 days'));

                break;

            default:

                $date = date('Y-m-d', strtotime('-1 days'));

                break;
        }

        return $date;
    }







    public function getRecurringTransaction($subscriber_id = '', $excludeId)
    {

        $obj = ClassRegistry::init('Transaction');

        $result = $obj->find("all", array('fields' => ['total', 'tx_id', 'txn_type', 'paymant_date'], 'conditions' => array('subscr_id' => $subscriber_id, 'Transaction.id !=' => $excludeId), 'order' => 'Transaction.id DESC'));

        return (!empty($result)) ? $result : [];
    }

    public function getTransactionType($type = '')
    {

        switch ($type) {

            case 'subscr_eot':

                $t = "Cancel";

                break;

            case 'subscr_eot':

                $t = "Cancel";

                break;

            case 'subscr_payment':

                $t = "Reccuring payment";

                break;

            default:

                $t = str_replace("_", " ", $type);

                break;
        }



        return $t;
    }



    public function getPlanCategorySlug($id = '')
    {

        if ($id) {

            $obj = ClassRegistry::init('PlanCategory');

            $result = $obj->find("first", array('fields' => ['PlanCategory.slug'], 'conditions' => array('PlanCategory.id' => $id)));

            return (!empty($result)) ? $result['PlanCategory']['slug'] : "online-distribution";
        }
    }



    public function getPlanInvoiceHtml($data)
    {
        
        $currencySymbol = Configure::read('Site.currency');
        $site_name = strip_tags(Configure::read('Site.name'));
        $recurringHtml = "";
        $name = $plan_url = $rows = '';
         $metadata = json_decode($data["Transaction"]["metadata"],true);
        if(!empty($metadata) && isset($metadata["features"])){
           // echo 1;
            foreach ($metadata["features"] as $feature) {
              //  echo 2;
            
               $name = $feature["name"];
               $price = $feature["price"];
                $rows .= "<tr>
                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>$name</td>
                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $price . "</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $price . "</td>
                    </tr>";
            }
        }
        if (!empty($data['TransactionPlan'])) {
            $i = 0;
            foreach ($data['TransactionPlan'] as $index => $plan) {
                $i++;
                $plan_details = $this->fetchPlanData($plan['plan_id']);
                $plan_url = $this->getPlanCategorySlug($plan_details['PlanCategory']['parent_id']);
                $name  =  $plan_details['PlanCategory']['name'];
                $type  =  $plan_details['Plan']['plan_type'];
                // $rows .="<tr style='padding-top:3px'><td>$i</td><td>$name </td> <td>$type</td> <td>".$currencySymbol.$plan['plan_amount']."</td></tr>";
                $rows .= "<tr>
                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>$name</td>
                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>
                    </tr>";
            }
        }
        if ($data['Transaction']['newsroom_amount'] > 0) {
            $rows .= "<tr>
                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Newsroom</td>
                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['Transaction']['newsroom_amount'] . "</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['Transaction']['newsroom_amount'] . "</td>
                    </tr>";
       }
        if ($data['Transaction']['subscr_id'] && $data['Transaction']['subscr_id'] != NULL) {
            $recurringData = $this->getRecurringTransaction($data['Transaction']['subscr_id'], $data['Transaction']['id']);
            if (!empty($recurringData)) {
                $recRows = '';
                $recurringHtml .= "
            <div style='text-align:left'><h3>Recurring Transaction Summary</h3></div>
            <table id='detail_table' style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
            <thead>
                <tr>
                    <th style='text-align:left;'>S.No.</th>
                    <th style='text-align:left;'>Transaction id</th>
                    <th style='text-align:left;'>Transaction Type</th>
                    <th style='text-align:left;'>Amount</th>
                    <th style='text-align:left;'>Date</th>
                </tr>
            </thead>";
                foreach ($recurringData as $loop => $recurringPayment) {
                    $total = (!empty($recurringPayment['Transaction']['total'])) ? $recurringPayment['Transaction']['total'] : "0.00";
                    $tx_id = (!empty($recurringPayment['Transaction']['tx_id'])) ? $recurringPayment['Transaction']['tx_id'] : "-";
                    $txnType = $this->getTransactionType($recurringPayment['Transaction']['txn_type']);
                    $date  = date('F d, Y', strtotime($recurringPayment['Transaction']['paymant_date']));
                    $recRows .= "<tr style='padding-top:3px'><td>" . ($loop + 1) . "</td><td>$tx_id</td> <td>$txnType</td> <td>" . $currencySymbol . $total . "</td><td>$date</td></tr>";
               }
                $recurringHtml .= "<tbody>$recRows</tbody></table>";
           }
       }

        $promo_code = '';
        $total_amount = (isset($data['Transaction']['total']) && $data['Transaction']['total'] > 0) ? $currencySymbol . $data['Transaction']['total'] : $currencySymbol . "0.00";
        $subtotal = $total_amount;
        
        // $subtotal = (isset($data['Transaction']['subtotal']) && $data['Transaction']['subtotal'] > 0) ? $currencySymbol . $data['Transaction']['subtotal'] : $currencySymbol . "0.00";
        $discount = (isset($data['Transaction']['discount']) && $data['Transaction']['discount'] > 0) ? $currencySymbol . $data['Transaction']['discount'] : $currencySymbol . "0.00";
        $tax = (isset($data['Transaction']['tax']) && $data['Transaction']['tax'] > 0) ? $currencySymbol . $data['Transaction']['tax'] : $currencySymbol . "0.00";

        $pdf_obj        =   ClassRegistry::init('PdfSetting');
        $pdf_data       =   $pdf_obj->find('first', array('conditions' => array('PdfSetting.id' => '2')));

        $pdf_title      =   $pdf_data['PdfSetting']['title'];
        $pdf_logo       =   $pdf_data['PdfSetting']['logo'];
        $tandc          =   $pdf_data['PdfSetting']['footer_text'];

  
        $company_logo_path = ROOT . '/app/webroot/' .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
        $companyLogo=$this->convertImageIntoBase64($company_logo_path); 
        $notesFindReplace = array('##PLAN##' => $name, '##PAGEURL##' => "<br/>" . SITEURL . 'plans/' . $plan_url);
        $notes = strtr($pdf_data['PdfSetting']['email_distribution_description'], $notesFindReplace);


        if (isset($data['Company']) && !empty($data['Company']['name'])) {

            $company =  $data['Company']['name'];
        } elseif (isset($data['StaffUser']['Company']) && !empty($data['StaffUser']['Company'][0]['name'])) {

            $company =  $data['StaffUser']['Company'][0]['name'];
        } else {

            $company =  '';
        }
        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>
               <style>body, html { background: #ffffff; color: #555555; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }
			   table {margin:0; padding: 0;}
			   </style>			   

			   </head>

                <body>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-bottom:none;'>

                  <tr>

                    <td><img style='width: 160px;margin: 30px;' src='" . $companyLogo . "' alt='logo'></td>

                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>

                  </tr>

                  <tr>

                    <td valign='top' rowspan='3' colspan='2' style='padding-left: 20px;'>" . Configure::read('Site.address') . "</td>

                    <td  width='150' align='right' style='padding-right: 20px; width: 300px; text-align: right;'><strong>Invoice #: </strong>" . $data['Transaction']['invoice_no'] . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Invoice date: </strong>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Payment Date: </strong>" . date("F d, Y", strtotime($data['Transaction']['paymant_date'])) . "</td>

                  </tr>

                  <tr>

				  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>

                            " . strip_tags(Configure::read('Site.email')) . "

                            " . SITEURL . "</td>

                    <td align='right' colspan='2'>

                        <table align='right' style='margin-top: 30px; margin-right: 20px; width: 300px; float: right; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>

                            <tr>

                                <td align='center' style='text-align: center; padding: 5px;'>

                                    <span style='width:100%; text-align: center; display: block;'>Amount Due:</span>

									<strong style='width:100%; text-align: center; margin-top: 10px; font-size: 24px; display: inline-block;'>$total_amount</strong>

								</td>

							</tr>

						</table>

                    </td>

                  </tr>

                   

                    <tr>

                        <td align='left' style='padding-left: 20px;'><h4>Bill To:</h4>

                        $company<br>

                        " . $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'] . "<br>

                        " . $data['StaffUser']['email'] . "</td>

                        <td colspan='2'></td>

                    </tr>

                </table>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; padding: 20px 0; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-top:none; border-bottom:none;'>

                    <thead>

                        <tr>

                          <th width='80' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-right: 0;padding: 8px;'>Date</th>

                          <th style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Description</th>  

                          <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Quantity</th>  

                          <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Price</th> 

                          <th align='center' width='100' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 8px;'>Amount</th>

                        </tr>

                    </thead>

                    <tbody>

                        $rows

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Subtotal</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$subtotal</td>

                        </tr>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Discount</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$discount</td>

                        </tr>

                        <!--<tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Discount</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$subtotal</td>

                        </tr>-->

                    </tbody>

                    <tfoot>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$total_amount</td>

                        </tr>

                    </tfoot>

                </table>

                    <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-top:none;'>

                        <tbody>

						<tr>

                            <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Notes: </strong>$notes</td>   

                            <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Terms and Conditions: </strong>$tandc</td>

                        </tr>

						</tbody>

                    </table>

                </body>

                </html>";

        // echo $html; die;

        return $html;
    } 


    public function getPrInvoiceHtmlForMail($data, $transdata)
    {

        $currencySymbol = Configure::read('Site.currency');
        $site_name = strip_tags(Configure::read('Site.name'));
        $promo_code = '';
        $rows  = '';
        $objPR = ClassRegistry::init('PressRelease');
        $pr_data = $objPR->find('first', array('conditions' => array('PressRelease.id' => $transdata['TransactionPressRelease']['press_release_id'])));
        $obj = ClassRegistry::init('Company');
        $conditions = array("Company.id" => $pr_data['PressRelease']['company_id']);
        $company_data = $obj->find('first', array('conditions' => $conditions));
        $company = $company_data['Company']['name'];
        $transdata = $transdata['TransactionPressRelease'];
        $total_amount = (isset($transdata['total']) && $transdata['total'] > 0) ? $currencySymbol . $transdata['total'] : "0.00";
        $subtotal = (isset($transdata['subtotal']) && $transdata['subtotal'] > 0) ? $currencySymbol . $transdata['subtotal'] : "0.00";
        $discount = (isset($transdata['discount']) && $transdata['discount'] > 0) ? $currencySymbol . $transdata['discount'] : "0.00";
        $tax = (isset($transdata['tax']) && $transdata['tax'] > 0) ? $transdata['tax'] : "0.00";
        $pdf_obj        = ClassRegistry::init('PdfSetting');
        $pdf_data       =   $pdf_obj->find('first', array('conditions' => array('PdfSetting.id' => '2')));
        $pdf_title      =   $pdf_data['PdfSetting']['title'];
        $pdf_logo       =   $pdf_data['PdfSetting']['logo'];
        $company_logo_path = ROOT . DS.'app'.DS.'webroot'.DS .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
        // $companyLogo=$this->convertImageIntoBase64($company_logo_path); 
        $companyLogo= SITEURL. "files/pdf_settings/" . $pdf_logo;

        $extra_charge = "   <tr>
                            <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Transaction ID : </th>
                            <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>" . $data['Transaction']['tx_id'] . "</td>
                        </tr>";
        if ($transdata['word_amount'] > 0) {
            $lbl = ($transdata['extra_words'] > 1) ? "words" : "word";
            $rows .= "<tr>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Word charges</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_words'] . " Extra $lbl</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                    </tr>";
       }
        if ($transdata['extra_category'] > 0) {
            $lbl = ($transdata['extra_category'] > 1) ? "categories" : "category";
            $rows .= "<tr>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                    
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Category charges</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_category'] . " Extra $lbl</td>    

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>

                </tr>";
        }

        if ($transdata['extra_msa'] > 0)

            $rows .= "<tr>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra MSA charges</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_msa'] . " Extra MSA</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['msa_amount'] . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>

                </tr>";



        if ($transdata['extra_state'] > 0) {

            $lbl = ($transdata['extra_state'] > 1) ? "states" : "state";

            $rows .= "<tr>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra State charges</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_state'] . " Extra $lbl</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['state_amount'] . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>

                </tr>";
        }

        if (!empty($transdata['translate_charges']))

            $rows .= "<tr>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Content translate charges</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>

                </tr>";



        $features = unserialize($transdata['distribution_ids']);

        if (!empty($features)) {

            foreach ($features as $index => $feature) {

                $rows .= "<tr>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $feature['name'] . " : </th>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td>

                 </tr>";
            }
        }



        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>

               <style>body, html { background: #ffffff; color: #555555; font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }

               table {margin:0; padding: 0;}

               </style>            

               </head>

                <body style='max-width:650px; margin:auto;'>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-bottom:none;'>

                  <tr>

                    <td><img style='width: 160px;margin: 30px;' src='" . $companyLogo. "' alt='logo'></td>

                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>

                  </tr>

                  <tr>

                    <td valign='top' rowspan='3' colspan='2' style='padding-left: 20px;'>" . Configure::read('Site.address') . "</td>

                    <td  width='150' align='right' style='padding-right: 20px; width: 300px; text-align: right;'><strong>Invoice #: </strong>" . $data['Transaction']['tx_id'] . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Invoice Date: </strong>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Payment Date: </strong>" . date("F d, Y", strtotime($data['Transaction']['paymant_date'])) . "</td>

                  </tr>

                  <tr>

                  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>

                            " . strip_tags(Configure::read('Site.email')) . "

                            " . SITEURL . "</td>

                    <td align='right' colspan='2'>

                        <table align='right' style='margin-top: 30px; margin-right: 20px; width: 300px; float: right; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>

                            <tr>

                                <td align='center' style='text-align: center; padding: 5px;'>

                                    <span style='width:100%; text-align: center; display: block;'>Amount Due:</span>

                                    <strong style='width:100%; text-align: center; margin-top: 10px; font-size: 24px; display: inline-block;'>$total_amount</strong>

                                </td>

                            </tr>

                        </table>

                    </td>

                  </tr>

                   

                    <tr>

                        <td align='left' style='padding-left: 20px;'><h4>Bill To:</h4>

                        $company<br>

                        " . $pr_data['StaffUser']['first_name'] . " " . $pr_data['StaffUser']['last_name'] . "<br>

                        " . $pr_data['StaffUser']['email'] . "</td>

                        <td colspan='2'></td>

                    </tr>

                </table> 

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; padding: 20px 0; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-top:none; border-bottom:none;'>

                    <thead>

                        <tr>

                          <th width='80' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-right: 0;padding: 8px;'>Date</th>

                          <th style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Description</th>  

                          <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Quantity</th>  

                          <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Price</th> 

                          <th align='center' width='100' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 8px;'>Amount</th>

                        </tr>

                    </thead>

                    <tbody>

                        $rows

                         <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Subtotal</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$subtotal</td>

                        </tr>



                        <!--<tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Tax</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$tax</td>

                        </tr>-->



                        

                    </tbody>    

                    <tfoot>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$total_amount</td>

                        </tr>

                    </tfoot>

                </table>

                    <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-top:none;'>

                        <tbody>

                        <tr>

                        <td colspan='2' style=' border-top: 1px solid #e6e6e6; vertical-align: top; padding: 20px;'>" . str_replace("##YEAR##", date('Y'), Configure::read('Site.Copyright')) . "</td>

                        </tr>

                        </tbody>

                    </table>

                </body>

                </html>";

        return $html;
    }

    public function getPrInvoiceHtml($data, $transdata)
    {
        $currencySymbol = Configure::read('Site.currency');
        $site_name = strip_tags(Configure::read('Site.name'));
        $promo_code = '';
        $rows  = '';
        $objPR = ClassRegistry::init('PressRelease');
        $pr_data = $objPR->find('first', array('conditions' => array('PressRelease.id' => $transdata['TransactionPressRelease']['press_release_id'])));
        $obj = ClassRegistry::init('Company');
        $conditions = array("Company.id" => $pr_data['PressRelease']['company_id']);
        $company_data = $obj->find('first', array('conditions' => $conditions));
        $company = $company_data['Company']['name'];
        $transdata = $transdata['TransactionPressRelease'];
        $total_amount = (isset($transdata['total']) && $transdata['total'] > 0) ? $currencySymbol . $transdata['total'] : "0.00";
        $subtotal = (isset($transdata['subtotal']) && $transdata['subtotal'] > 0) ? $currencySymbol . $transdata['subtotal'] : "0.00";
        $discount = (isset($transdata['discount']) && $transdata['discount'] > 0) ? $currencySymbol . $transdata['discount'] : "0.00";
        $tax = (isset($transdata['tax']) && $transdata['tax'] > 0) ? $transdata['tax'] : "0.00";

        $pdf_obj        = ClassRegistry::init('PdfSetting');
        $pdf_data       =   $pdf_obj->find('first', array('conditions' => array('PdfSetting.id' => '2')));
        $pdf_title      =   $pdf_data['PdfSetting']['title'];
        $pdf_logo       =   $pdf_data['PdfSetting']['logo'];
        $company_logo_path = ROOT . DS.'app'.DS.'webroot'.DS .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
        $companyLogo=$this->convertImageIntoBase64($company_logo_path); 
        $extra_charge = "   <tr>
                            <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Transaction ID : </th>
                            <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>" . $data['Transaction']['tx_id'] . "</td>
                        </tr>";

        if ($transdata['word_amount'] > 0) {

            $lbl = ($transdata['extra_words'] > 1) ? "words" : "word";

            $rows .= "<tr>

                                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Word charges</td>

                                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_words'] . " Extra $lbl</td>

                                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>

                                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>

                                    </tr>";
        }

        if ($transdata['extra_category'] > 0) {

            $lbl = ($transdata['extra_category'] > 1) ? "categories" : "category";

            $rows .= "<tr>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                    

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Category charges</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_category'] . " Extra $lbl</td>    

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['category_amount'] . "</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['category_amount'] . "</td>

                </tr>";
        }

        if ($transdata['extra_msa'] > 0)

            $rows .= "<tr>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra MSA charges</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_msa'] . " Extra MSA</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['msa_amount'] . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['msa_amount'] . "</td>

                </tr>";



        if ($transdata['extra_state'] > 0) {

            $lbl = ($transdata['extra_state'] > 1) ? "states" : "state";

            $rows .= "<tr>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra State charges</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_state'] . " Extra $lbl</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['state_amount'] . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['state_amount'] . "</td>

                </tr>";
        }

        if (!empty($transdata['translate_charges']))

            $rows .= "<tr>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Content translate charges</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>

                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>

                </tr>";



        $features = unserialize($transdata['distribution_ids']);

        if (!empty($features)) {

            foreach ($features as $index => $feature) {

                $rows .= "<tr>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $feature['name'] . " : </th>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td>

                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td>

                 </tr>";
            }
        }



        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>

               <style>body, html { background: #ffffff; color: #555555; font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }

               table {margin:0; padding: 0;}

               </style>            

               </head>

                <body>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto;  background: #ffffff;border-collapse:collapse;  border: 2px solid #e6e6e6; border-bottom:none;'>

                  <tr>

                    <td><img style='width: 160px;margin: 30px;' src='" .$companyLogo. "' alt='logo'></td>

                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>

                  </tr>

                  <tr>

                    <td valign='top' rowspan='3' colspan='2' style='padding-left: 20px;'>" . Configure::read('Site.address') . "</td>

                    <td  width='150' align='right' style='padding-right: 20px; width: 300px; text-align: right;'><strong>Invoice #: </strong>" . $data['Transaction']['tx_id'] . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Invoice Date: </strong>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Payment Date: </strong>" . date("F d,Y", strtotime($data['Transaction']['paymant_date'])) . "</td>

                  </tr>

                  <tr>

                  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>

                            " . strip_tags(Configure::read('Site.email')) . "

                            " . SITEURL . "</td>

                    <td align='right' colspan='2'>

                        <table align='right' style='margin-top: 30px; margin-right: 20px; width: 300px; float: right; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>

                            <tr>

                                <td align='center' style='text-align: center; padding: 5px;'>

                                    <span style='width:100%; text-align: center; display: block;'>Amount Due:</span>

                                    <strong style='width:100%; text-align: center; margin-top: 10px; font-size: 24px; display: inline-block;'>$total_amount</strong>

                                </td>

                            </tr>

                        </table>

                    </td>

                  </tr>

                   

                    <tr>

                        <td align='left' style='padding-left: 20px;'><h4>Bill To:</h4>

                        $company<br>

                        " . $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'] . "<br>

                        " . $data['StaffUser']['email'] . "</td>

                        <td colspan='2'></td>

                    </tr>

                </table> 

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; margin: auto;  padding: 20px 0; background: #ffffff;border-collapse:collapse; border: 2px solid #e6e6e6; border-top:none; border-bottom:none;'>

                    <thead>

                        <tr>

                          <th width='80' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-right: 0;padding: 8px;'>Date</th>

                          <th style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Description</th>  

                          <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Quantity</th>  

                          <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Price</th> 

                          <th align='center' width='100' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 8px;'>Amount</th>

                        </tr>

                    </thead>

                    <tbody>

                        $rows

                         <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Subtotal</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$subtotal</td>

                        </tr> 

                        <!---<tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Tax</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$tax</td>

                        </tr>-->

                    </tbody>    

                    <tfoot>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$total_amount</td>

                        </tr>

                    </tfoot>

                </table>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; background: #ffffff;border-collapse:collapse;border: 2px solid #e6e6e6; border-top:none;'>

                    <tbody>

                    </tbody>

                </table>

                </body>

                </html>";

        // echo  $html;die;

        return $html;
    }



    public function getStateNameById($id = '')
    {

        $obj = ClassRegistry::init("State");

        $data = $obj->find('first', array('conditions' => array('State.id' => $id)));

        return $data['State']['name'];
    }

    public function getMsaNameById($id = '')
    {

        $parts = [];

        $name = '';

        $obj = ClassRegistry::init("Msa");

        $data = $obj->find('first', array('conditions' => array('Msa.id' => $id)));

        if (!empty($data)) {

            $parts = explode(",", $data['Msa']['name']);

            $name = $data['Msa']['name'];
        }

        return (!empty($parts)) ? $parts[0] : $name;
    }

    public function summaryPrefix($msaId = '', $stateId = '', $date = '')
    {

        $site_name = strip_tags(Configure::read('Site.name'));

        $msa = (!empty($msaId)) ? $this->getMsaNameById($msaId['0']) . ', ' : '';



        if (!empty($msa)) {

            $string = (!empty($stateId)) ? $msa . $this->getStateNameById($stateId['0']) . ', ' : $msa;
        } else {

            $string = (!empty($stateId)) ? $this->getStateNameById($stateId['0']) . ', ' : '';
        }

        return $string . date('F d, Y', strtotime($date)) . ", /<a style='text-decoration:none;color:black' target='_blank' rel='nofollow' href='" . SITEURL . "' title='" . $site_name . ".com'>" . $site_name . "</a>/ -- ";
    }







    public function getOrganizationName($id)
    {

        $obj = ClassRegistry::init('OrganizationType');

        $data = $obj->find("first", array('conditions' => array('OrganizationType.id' => $id), 'fields' => ['name']));

        return $data['OrganizationType']['name'];
    }



    public function getPRSstatus($value)
    {

        switch ($value) {

            case 'approved':

                $status = 1;

                break;

            case 'embargoed':

                $status = 2;

                break;

            case 'draft':

                $status = 3;

                break;

            case 'disapproved':

                $status = 4;

                break;

            case 'trashed':

                $status = 5;

                break;

            default:

                $status = 0;

                break;
        }

        return $status;
    }





    public function getPRSUrlStatus($value)
    {

        switch ($value) {

            case 1:

                $status = 'approved';

                break;

            case 2:

                $status = 'embargoed';

                break;

            case 3:

                $status = 'draft';

                break;

            case 4:

                $status = 'disapproved';

                break;

            case 5:

                $status = 'trashed';

                break;

            default:

                $status = 'pending';

                break;
        }

        return $status;
    }





    public function countMediaEmails($listId)
    {

        $subscriber = ClassRegistry::init('Subscriber');

        $count = $subscriber->find("count", array('conditions' => array("Subscriber.list" => $listId)));

        return $count;
    }



    public function get_nw_relation_data($nwid)
    {

        $NetworkWebsite = ClassRegistry::init('NetworkWebsite');

        $result_data = $NetworkWebsite->find("all", array('conditions' => array("NetworkWebsite.id" => $nwid)));

        return $result_data[0];
    }


    /*
     * @params:  
     * @Function use: getCountryList: This function use to get the country list
     * @created by: Hitesh verma
     * @Created: 06-07-2022
     */
    public function getCountryList()
    {
        if (!($list = Cache::read('country_list', 'long'))) {
            $this->Country = ClassRegistry::init('Country');
            $list = $this->Country->find('list', array('conditions' => array('Country.status' => '1'), 'order' => "name ASC"));
            Cache::write('country_list', $list, 'long');
        }
        return $list;
    }



    /*
     * @params:  
     * @Function use: getCountryList: This function use to get the country list
     * @created by: Hitesh verma
     * @Created: 06-07-2022
     */
    public function getMsaList()
    {
        if (!($list = Cache::read('msa_list', 'long'))) {
            $this->Country = ClassRegistry::init('Msa');
            $list = $this->Country->find('list', array('conditions' => array('Msa.status' => '1'), 'order' => "name ASC"));
            Cache::write('msa_list', $list, 'long');
        }
        return $list;
    }


    /*
     * @params:  
     * @Function use: getParentCategories: This function use to get the Parent Caregory list
     * @created by: Hitesh verma
     * @Created: 24-07-2022
     */
    public function getParentCategories()
    {
        if (!($list = Cache::read('parent_categories', 'long'))) {
            $this->Category = ClassRegistry::init('Category');
            $list = $this->Category->find('list', array('conditions' => array('Category.is_deleted' => '0', 'status' => 1, 'Category.parent_id' => 0), 'order' => 'name'));
            Cache::write('parent_categories', $list, 'long');
        }
        return $list;
    }



    /*
     * @params:  
     * @Function use: getStateList: This function use to get the country list
     * @created by: Hitesh verma
     * @Created: 06-07-2022
     */
    public function getStateList()
    {
        if (!($list = Cache::read('state_list', 'long'))) {
            $this->Country = ClassRegistry::init('State');
            $list = $this->Country->find('list', array('conditions' => array('State.status' => '1'), 'order' => "name ASC"));
            Cache::write('state_list', $list, 'long');
        }
        return $list;
    }

    /*
     * @params:  
     * @Function use: getCategoryByChildCatSlugId: This function use to get the country list
     * @created by: Hitesh verma
     * @Created: 06-07-2022
     */
    function getCategoryByChildCatSlugId($pSlug, $childSlug = '')
    {
        $this->Category = ClassRegistry::init('Category');
        $pCategory = $this->Category->find('first', ['conditions' => ['slug' => "$pSlug"], 'fields' => ['id']]);

        $categoryId = $pCatId = $pCategory['Category']['id'];

        if (!empty($childSlug)) {
            $childCat = $this->Category->find('first', ['conditions' => ['parent_id' => $pCatId, 'slug' => "$childSlug"]]);
            if (!empty($childCat)) {
                $categoryId = $childCat['Category']['id'];
            }
        }
        return $categoryId;
    }

    /*
     * @params:  
     * @Function use: getCategories: This function use to get the category with child and parent list
     * @created by: Hitesh verma
     * @Created: 30-07-2022
     */
    public function getCategories($pcatId)
    {
        $this->Category = ClassRegistry::init('Category');
        $list = $this->Category->find('threaded', array(
            'joins' => array(
                array(
                    'table' => 'categories_press_releases',
                    'alias' => 'CategoryPressRelease',
                    'type' => 'INNER',
                    'conditions' => array(
                        'CategoryPressRelease.category_id = Category.id'
                    )
                ),
                array(
                    'table' => 'press_releases',
                    'alias' => 'PressRelease',
                    'type' => 'INNER',
                    'conditions' => array(
                        "PressRelease.id = CategoryPressRelease.press_release_id"
                    )
                ),
            ),
            'conditions' => array('Category.status' => '1', 'Category.parent_id' => $pcatId, 'PressRelease.status' => 1, 'PressRelease.release_date  <=' => date('Y-m-d')), 'group' => "Category.id", 'order' => "name ASC"
        ));
        return $list;
    }


    function mapAdditionFeatureInArr($getPaidAdditionalFeature)
    {
        $mapPaidAdditionalFeature = [];
        if (!empty($getPaidAdditionalFeature)) {
            foreach ($getPaidAdditionalFeature as $additionalFeature) {
                $mapPaidAdditionalFeature[] = $additionalFeature['distribution_id'];
            }
        }
        return $mapPaidAdditionalFeature;
    }

    public function checkCartbeforeSubmitPr($user_id = '', $plan_id = '')
    {

        $Cart = ClassRegistry::init('Cart');
        $conditions = array('Cart.staff_user_id' => $user_id, 'plan_id' => $plan_id, 'cart_type' => 'pr', "press_release_id" => "0");
        $checkcart = $Cart->find('first', array('conditions' => $conditions));
        return $checkcart;
    }


    /*
     * @params:  
     * @Function use: getLanguages: This function use to get the Languages list
     * @created by: Hitesh verma
     * @Created: 25-07-2022
     */
    public function getLanguages()
    {
        if (!($list = Cache::read('language_list', 'long'))) {
            $this->Language = ClassRegistry::init('Language');
            $list = $this->Language->find('list', array("fields" => ["code", "name"], 'conditions' => array('Language.status' => '1'), 'order' => "name ASC"));
            Cache::write('language_list', $list, 'long');
        }
        return $list;
    }

    /*
     * @params:  
     * @Function use: getLanguageIdByCode: This function use to get the Languages list
     * @created by: Hitesh verma
     * @Created: 25-07-2022
     */
    public function getLanguageIdByCode($code)
    {
        if (!($list = Cache::read('language_code_list', 'long'))) {
            $this->Language = ClassRegistry::init('Language');
            $list = $this->Language->find('list', array("fields" => ["code", "id"], 'conditions' => array('Language.status' => '1'), 'order' => "name ASC"));
            Cache::write('language_code_list', $list, 'long');
        }
        return (!empty($list) && !empty($list[$code])) ? $list[$code] : '1'; // Default Language is english
    }

     

    

    public function getClippingReportViewHtml($prdata = '', $nwrelationships=[],$clippingReportData = '')
    {
        $prRow = ""; 
        if (!empty($prdata)) {

            $obj = ClassRegistry::init('Company');
            $conditions = array("Company.id" => $prdata["PressRelease"]['company_id']);
            $company_data = $obj->find('first', array('conditions' => $conditions));
            $site_name = strip_tags(Configure::read('Site.name'));
            $dateformate = strip_tags(Configure::read('Site.DateFromat'));
            // $dateformate="m-d-Y";
            $plan_obj = ClassRegistry::init('Plan');
            // $plan_data = $plan_obj->find('first', array('conditions' => array('Plan.id' => $prdata["PressRelease"]['plan_id'])));
            $plan_name = $prdata["Plan"]['PlanCategory']['name']; 
            $pdf_obj = ClassRegistry::init('PdfSetting');
            $pdf_data = $pdf_obj->find('first', array('conditions' => array('PdfSetting.id' => '1')));
            $pdfFindReplace = array('##PLAN_NAME##' => $plan_name);

            $pdf_title                  =   $pdf_data['PdfSetting']['title'];
            $pdf_logo                   =   $pdf_data['PdfSetting']['logo'];
            $pdf_welcome_text           =   strtr($pdf_data['PdfSetting']['welcome_text'], $pdfFindReplace);
            $pdfNetworkDescription    =   $pdf_data['PdfSetting']['network_description'];
            $pdfEmailDescription      =   $pdf_data['PdfSetting']['email_distribution_description'];
            $pdf_footer      =   $pdf_data['PdfSetting']['footer_text'];
            $company_name = $company_data['Company']['name'];
            $company_logo_path = ROOT . '/app/webroot/' . "files/company/logo/" . $company_data['Company']['logo_path'] . "/" . $company_data['Company']['logo'];
            $companyLogo=$this->convertImageIntoBase64($company_logo_path); 
            
            /*
            $path_info = pathinfo($company_logo_path);
             $this->png_to_jpg($company_logo_path, $path_info['dirname'] . '/' . $path_info['filename'] . '.jpg', 100);
             $companyLogo=SITEURL."company/logo/" . $company_data['Company']['logo_path'] . "/" . $company_data['Company']['logo'];
            */
        }

        
        $mediaPickuphtml=$clippingRow = '';
        $mediaPickuphtml .=$this->getStatisticsHtml($prdata,$plan_name);
        $mediaPickuphtml .=$this->mediaPickup($nwrelationships,$pdfNetworkDescription); 

        /* TO DO : will use letter when start the sendy mail functionality
        $countryReport = $links = $spamMailList = $unsubscribedMailList = $newsletterMailList = $sentMailReportdata = $bouncedMailList = "";
        $champ = $this->getCampaignDetails($prdata["PressRelease"]['id']);
        $newsletterMailList = $this->getNewsletterOpenMailList($prdata["PressRelease"]['id']);
        $newsletterSentMailList = $this->getNewsletterSentMailList($prdata["PressRelease"]['id']);
        $newsletterReceivedMailList = $this->getNewsletterReceivedMailList($prdata["PressRelease"]['id']);
        $mediaPickuphtml .=$this->getNewsletterSentMailHtml($newsletterSentMailList,$pdfEmailDescription,$newsletterReceivedMailList); 
        $mediaPickuphtml .=$this->getNewsletterReceivedMailHtml($newsletterReceivedMailList,$newsletterSentMailList); 
        $mediaPickuphtml .=$this->getNewsletterMailHtml($newsletterMailList,$newsletterSentMailList); 
        $mediaPickuphtml .=$this->getSendyReportHtml($champ); */
       
        
        $update_time    = date('F d, Y', time());
        $replaceCompanyName = ["##COMPANYNAME##" => $company_name];
        $replaceFooterText = ["##PHONE##" => strip_tags(Configure::read('Site.phone')), "##YEAR##" => date('Y')];

        /*
         <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
                <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js'></script>
                <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
                <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js'></script> */
        $html = "<!doctype html>
                    <html>
                    <head>
                    <meta charset='utf-8'>
                    <style>
                        @page {
                            margin-top: .2in !important;
                            margin-bottom: .2in !important;
                            margin-left: .2in !important;
                            margin-right: .2in !important; 
                        }
                        * { font-family: DejaVu Sans, sans-serif; }
                        body{font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif; }
                        .fontbold{font-weight: bold;} tr:nth-child(even) {background-color: #ffffff;}
                        body td a { color: #000000; font-weight: 700; text-decoration: none; }
                        body td .white-space-nowrap { white-space: nowrap; }
                        table td, table th,
                        table td img, table th img { background: #ffffff; }
                        table { border-collapse: collapse; border: 1px solid #dee2e6; width: 100%; margin-bottom: 1rem; color: #212529; text-align: left; }
                        body table td,body table th{  padding: 0.75rem; vertical-align: top; border-top: 1px solid #ffffff; border-width: 0; vertical-align: middle; border-bottom: 1px solid #e5e5e5; line-height: 30px; padding: 5px; }
                        td,th{line-height: 30px;}@media print {.pagebreak { page-break-after: always!important;} }   
                        table {font-size:10px;font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;border-left: 0.01em solid #ccc;border-right: 0;border-top: 0.01em solid #ccc;border-bottom: 0;border-collapse: collapse;}
                        table td,table th {border-left: 0;border-right: 0.01em solid #ccc;border-top: 0;border-bottom: 0.01em solid #ccc;}
                        body { font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif !important; border: 0px solid #666666; }
                </style>  
            
                <table style='vertical-align: bottom; color: #4d4d4d;border: none; line-height:1.5em;'>
                    <tbody>
                        <tr>
                            <td style='text-align: left;border: none;line-height:1em'><h2><strong> <img style='width: 120px;' src='https://devsite.emailwire.com/files/company/logo/2022/03/6222a6ebc962e.png'></strong></h2></td>
                        
                            <td style='text-align: right;border: none;line-height:.5em'><img src='https://devsite.emailwire.com/files/pdf_settings/emailwire_logo_1613634874.jpg'></td>
                        </tr>
                    </tbody>
                </table>

                <table style='vertical-align: bottom;font-size:14px; color: #4d4d4d; text-align: center;border: none; line-height:1.5em;'>
                    <tbody>
                        <tr>
                            <td style='text-align: center;border: none;line-height:1.5em'><h1><strong> " . strtr($pdf_title, $replaceCompanyName) . "</strong></h1>
                            <p style='font-size: large;'>Press Release Distribution by $site_name</p></td>
                        </tr>
                      
                    </tbody>
                </table> 
                    $mediaPickuphtml
                <table style='vertical-align: bottom;font-size:14px; color: #4d4d4d; text-align: center;border: none; line-height:1.5em;'>
					<tbody>
						<tr>
							<td style='text-align: center;border: none;line-height:1.5em'>".strtr($pdf_footer, $replaceFooterText)."</td>
						</tr>
					</tbody>
				</table></body></html>";
        // echo $html;die;
        return $html;
    }
 
    
    /*
    * @params:  
    * @Function use: mediaPickup: Using for clipping report
    * @created by: Hitesh verma
    * @Created: 15-10-2022
    */

    private function mediaPickup($nwrelationships=[],$pdf_network_description=""){
        $clippingRow =$html="";
        if (isset($nwrelationships) && !empty($nwrelationships)) {
        $clippingRow .= " 
            <table style='font-size:14px; border-top: 5px solid #4d4d4d; vertical-align: top;' cellspacing='5' cellpadding='5'>
                <tr>
                    <th style='text-align:left;'>S/N</th>
                    <th style='text-align:left;'>Site Identity</th>
                    <th style='text-align:left;'>Media Name</th>
                    <th style='text-align:left;'>Website</th> 
                    <th style='text-align:left;'>Location</th>
                    <th style='text-align:left;'>Media Type</th>
                    <th style='text-align:left;'>Potential<br>Audience</th>
                </tr>";
                
                    $i = $total_potential_audience = 0;
                    //pr($nwrelationships); die;
                    foreach ($nwrelationships as $index => $nwrelationship) {
                        $i++; 
                        /*
                        $word = "markets.financialcontent.com";
                        $mystring = $nwrelationship['NwRelationships']['press_release_link'];

                        if ((strpos($mystring, $word) !== false) && $fc_content == 2) {
                            $ab = "a1";
                            $websiteLogo = SITEURL . "website/img/blank.jpg";
                            $fc_content++;
                        } else {
                            $ab = "a2";
                            $websiteLogo = SITEURL . "website/img/blank.jpg";
                        }

                        if (strpos($mystring, $word) == false) {
                            $ab = "a3";
                        // $company_logo_path = ROOT . '/app/webroot/' . "files/website/img/" .$networkwebsite['NwRelationships']['site_logo'];
                        //  $this->png_to_jpg($company_logo_path, $path_info['dirname'] . '/' . $path_info['filename'] . '.jpg', 100);
                        } */
                           /*
                        $link = $nwrelationship['NwRelationships']['press_release_link'];
                        $link_parse = parse_url($link);
                        $link_host = '';
                        if (isset($link_parse['host'])) {
                            $link_host = $link_parse['host'];
                        }*/

                        $websiteDirPath = ROOT . '/app/webroot/'.$nwrelationship['NwRelationships']['site_logo']; 

                        $websiteLogo=(!empty($nwrelationship['NwRelationships']['site_logo'])&&file_exists($websiteDirPath))?SITEURL.$nwrelationship['NwRelationships']['site_logo']:SITEURL."website/img/blank.jpg";
                        $pa = (!empty($nwrelationship['NwRelationships']['potential_audience']))?$nwrelationship['NwRelationships']['potential_audience']:"0";
                        $websiteLogoBase64=$this->convertImageIntoBase64($websiteLogo);
                        $websiteLogo=(!empty($websiteLogoBase64))?"<img width='120px' alt='" . ucwords($nwrelationship['NwRelationships']['site_name']) . "' src='" .$websiteLogoBase64. "'>":$websiteLogo;
                        $clippingRow .= "
                        <tr style='padding-top:3px'>
                            <td style='text-align:left;'>" . $i . "</td>
                            <td style='text-align:left;'>$websiteLogo</td>
                            <td style='text-align:left;'>" . ucwords($nwrelationship['NwRelationships']['site_name']) . "</td>
                            <td style='text-align:left;'><a class='white-space-nowrap' target='_blank' href=" . $nwrelationship['NwRelationships']['press_release_link'] . ">View Release</a></td>
                            <td style='text-align:left;'>" . $nwrelationship['NwRelationships']['location'] . "</td>
                            <td style='text-align:left;' class='white-space-nowrap' >" . $nwrelationship['NwRelationships']['type'] . "</td>
                            <td style='text-align:left;'>" . $this->numberFormatAsUs($pa) . "</td>
                        </tr>";

                        $total_potential_audience = $total_potential_audience + $nwrelationship['NwRelationships']['potential_audience'];
                    }
                    $total_sn = $i;
                    $totalPotentialAudience=$this->numberFormatAsUs($total_potential_audience);
                    $pdf_network_description = str_replace("##PR-AUDIENCE##", $totalPotentialAudience, $pdf_network_description);
                    $pdf_network_description = str_replace("##PR-PICKUPS##", $total_sn, $pdf_network_description);
                $clippingRow   .= "</table> ";

            $html = '<table align="left" style="border:none;margin: unset; text-align:left; color: #4d4d4d; padding-bottom: 5px; padding-top: 25px; width: 100%;">
                    <thead>
                    <th style=" text-align:left; font-size: 25px; border:none; font-weight: 600; ">Media Pickup</th>
                    <th style=" text-align:right; font-size: 14px; font-weight: 600;border:none; ">'.$pdf_network_description.'</th>
                    </thead>
                </table> ';
                $html  .=$clippingRow;
            }
        return $html;
    }

    /*
    * @params:  
    * @Function use: getStatisticsHtml: Using for clipping report
    * @created by: Hitesh verma
    * @Created: 16-10-2022
    */
    private function getStatisticsHtml($data=[],$planName=""){
        $dateFormat = strip_tags(Configure::read('Site.DateFromat'));
        $totalPotentialAudience=(!empty($data['0']['potentialAudienceCount']))?$this->numberFormatAsUs($data['0']['potentialAudienceCount']):0;
        $totalViews=(!empty($data['PressRelease']['views']))?$this->numberFormatAsUs($data['PressRelease']['views']):0;
        $networkFeedCount=(!empty($data['0']['networkFeedCount']))?$this->numberFormatAsUs($data['0']['networkFeedCount']):0;
        $socialShareCount=(!empty($data['0']['socialShareCount']))?$this->numberFormatAsUs($data['0']['socialShareCount']):0;
        $printCount=(!empty($data['0']['printCount']))?$this->numberFormatAsUs($data['0']['printCount']):0;
        $emailCount=(!empty($data['0']['emailCount']))?$this->numberFormatAsUs($data['0']['emailCount']):0;
        $today=date($dateFormat);
        $releaseDate=date($dateFormat, strtotime($data['PressRelease']['release_date']));
        $html="";
        $html .="<table class='table' style='border-top: 5px solid #f39c12;'> 
                    <tbody>
                    <tr>
                        <td style='width:25%; font-size: 14px; border: 1px solid #ccc; background-color: #ccc;'><strong class='text-center p-2'>Press Release Title</strong></td>
                        <td style='width:75%'><h2>".$data['PressRelease']['title']."</h2></td> 
                    </tr>
                    <tr>
                        <td style='width:25%; background-color: #ccc; border: 1px solid #ccc; font-size: 14px;'><strong class='text-center p-2'>Press Release ID:</strong></td>
                        <td style='width:75%  font-size: 15px;'><strong>".$data['PressRelease']['id']."</strong></td> 
                    </tr>
                    <tr>
                        <td style='width:25%; font-size: 14px; border: 1px solid #ccc; background-color: #ccc;'><strong class='text-center p-2'>Press Release Plan</strong></td>
                        <td style='width:75% ; font-size: 14px;'><strong>".$planName."</strong></td> 
                    </tr>
                    <tr>
                        <td style='width:25%; font-size: 14px; border: 1px solid #ccc; background-color: #ccc;'><strong class='text-center p-2'>Distribution Date</strong></td>
                        <td style='width:75%; font-size: 14px;'><strong>".$releaseDate."</strong></td> 
                    </tr> 
                    <tr>
                        <td style='width:25%; font-size: 14px; border: 1px solid #ccc; background-color: #ccc;'><strong class='text-center p-2'>Reporting Date</strong></td>
                        <td style='width:75%; font-size: 14px;'><strong>".$today."</strong></td> 
                    </tr> 
                    </tbody>
                </table>";

        $html .=' 
        <table align="left" style="border:none; margin-top: -5px;border-bottom: 5px solid #4d4d4d; text-align:left; color: #4d4d4d; font-size: 25px; font-weight: 600; padding-bottom: 5px; padding-top: 25px; width: 100%;">
            <thead>
            <th style=" text-align:left; ">Statistics</th>
            </thead>
        </table>
        <table style="font-size:14px; margin-top: -20px;"> 
            <tbody>
            <tr>
                <td style="font-size:14px;background-color: #ccc; border: 1px solid #ccc;"><strong style="text-align:center; padding: 0.5rem!important; ">Statistics</td>
                <td align="right" style="width:75%;padding-right: 15px;"><strong>'.$totalPotentialAudience.'</strong></td>
            </tr> 
            <tr>
                <td style="font-size:14px;background-color: #ccc; border: 1px solid #ccc;"><strong style="text-align:center; padding: 0.5rem!important;">Release Views/Reads:</strong></td>
                <td align="right" style="width:75%;padding-right: 15px;"><strong>'.$totalViews.'</strong></td> 
            </tr>
            <tr>
                <td style="font-size:14px;background-color: #ccc; border: 1px solid #ccc;"><strong style="text-align:center; padding: 0.5rem!important;">Click-Throughs</strong></td>
                <td align="right" style="width:75%;padding-right: 15px;"><strong>'.$networkFeedCount.'</strong></td> 
            </tr>
            <tr>
                <td style="font-size:14px;background-color: #ccc; border: 1px solid #ccc;"><strong style="text-align:center; padding: 0.5rem!important;">Social Shares</strong></td>
                <td align="right" style="width:75%;padding-right: 15px;" ><strong>'.$socialShareCount.'</strong></td> 
            </tr> 
            <tr>
                <td style="font-size:14px;background-color: #ccc; border: 1px solid #ccc;"><strong style="text-align:center; padding: 0.5rem!important;">Prints</strong></td>
                <td  align="right"style="width:75%;padding-right: 15px;"><strong>'.$printCount.'</strong></td> 
            </tr>
            <tr>
                <td style="font-size:14px;background-color: #ccc; border: 1px solid #ccc;"><strong style="text-align:center; padding: 0.5rem!important;">Emailed</strong></td>
                <td  align="right"style="width:75%;padding-right: 15px;"><strong>'.$emailCount.'</strong></td> 
            </tr>
            </tbody>
        </table>';
      return $html; 
    }

    /*
     * @params:  
     * @Function use: getSendyReportHtml: Using for clipping report
     * @created by: Hitesh verma
     * @Created: 16-10-2022
     */
    function getSendyReportHtml($champ=[]){
        $clippingRow="";
        if (!empty($champ)) {
                $sentMailReportdata = $this->getSentMailReport($champ['opens']);
                $countryReport = $this->getSendyCountryReport($champ['opens']);
                // $unsubscribedMailList = $this->getSendyUnsubscribedMailList($champ['id']);
                // $bouncedMailList = $this->getSendyBouncedMailList($champ['id']);
                // $spamMailList = $this->getSendySpamMailList($champ['id']);
                // $links = $this->getLinksMailList($champ['id']);

                /* Sendy clipping reports*/
            if(!empty($sentMailReportdata)){

                $clippingRow  .= "<div  style='text-align:left;margin:20 0px 20px 0px' class='col-lg-6'>
                    Client media list opened
                    </div>
                    <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                    <tr>
                        <th style='text-align:left;'>S/N</th>
                        <th style='text-align:left;'>Name</th> 
                        <th style='text-align:left;'>Email</th> 
                        <th style='text-align:left;'>Distribution Type</th>
                        <th style='text-align:left;'>Views</th>                  
                        <th style='text-align:left;'>Distribution Date</th> 
                    </tr>";
                $openSubscriber_ids = [];
                $sarr = explode(",", $champ['opens']);
                for ($z = 0; $z < count($sarr); $z++) {
                    $subscriber_id = explode(':', $sarr[$z]);
                    $openSubscriber_ids[] = $subscriber_id[0];
                }
                $count = 0;
                foreach ($sentMailReportdata as $subId => $sentmail) {
                    $views = count(array_keys($openSubscriber_ids, $subId));
                    $email = $this->getEmailforClippingReport($sentmail);
                    $emailPart = explode("@", $sentmail);
                    $name = ucfirst($emailPart[0]);
                    $clippingRow .= "<tr style='padding-top:3px'><td>" . ($count + 1) . "</td><td style='text-align:left;'>" . $name . "</td><td style='text-align:left;'>$email</td><td style='text-align:left;'>Mail</td> <td style='text-align:left;'>$views</td><td style='text-align:left;'>" . date($dateformate, strtotime($champ['created'])) . "</td></tr>";
                    $count++;
                }
                $clippingRow   .= "</table>";
            }

            /*Country Report emails */
            if ($countryReport) {
                $clippingRow .= "
                    <div style='text-align:left;margin:20px 0px 20px 0px' class='col-lg-6'>
                        Client media list opened by country
                    </div>
                    <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                    <tr>
                        <th style='text-align:left;'>S/N</th>
                        <th style='text-align:left;'>Country</th>
                        <th style='text-align:left;'>Views</th>
                        <th style='text-align:left;'>Distribution Type</th> 
                        <th style='text-align:left;'>Distribution Date</th> 
                    </tr>";
                    $count = 0;
                    $unique_countries = array_unique($countryReport);
                    $counts = array_count_values($countryReport);
                    foreach ($unique_countries as $index => $country) {
                        $views = "0";
                        $name = $this->getCountryNameByCountyCode($country);
                        if (!empty($country)) {
                            $views = $counts[$country];
                        }
                        $clippingRow .= "<tr style='padding-top:3px'><td style='text-align:left;'>" . ($count + 1) . "</td><td style='text-align:left;'>" . $name . "</td><td style='text-align:left;'>$views</td><td style='text-align:left;'>Mail</td><td style='text-align:left;'>" . date($dateformate, strtotime($champ['created'])) . "</td></tr>";
                        $count++;
                    }
                    $clippingRow   .= "</table>";
                }
                return $clippingRow;
        }
        return $clippingRow;
    }
    private function getNewsletterSentMailHtml($newsletterSentMailList=[],$pdfEmailDescription="",$newsletterReceivedMailList=[]){
        $clippingRow="";
        if (!empty($newsletterSentMailList)) {

            $clippingRow  .= "<div style='text-align:left;color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>Email Distribution</div>
                <div style='text-align:left;margin:0 10px 10px 10px;color: #4d4d4d;'> $pdfEmailDescription  </div>"; 

            $clippingRow .= "
                <div style='text-align:left;margin:20px 0px 20px 0px' class='col-lg-6'>
                     <strong>Email sent to subscribers</strong>
                </div>
                <div id='total_subscribers'></div>
                <img src='' id='total_subscribers_img'>

                <script type='text/javascript'>
                  google.charts.load('current', {'packages':['corechart']});
                  google.charts.setOnLoadCallback(drawChart);
                  function drawChart() {
                      var data = google.visualization.arrayToDataTable([
                      ['Task', 'Hours per Day'],
                      ['Total Subscribers: " . count($newsletterReceivedMailList) . "', 100]
                    ]);
                    var options = {'width':450, 'is3D': true, slices: {
                      0: { color: 'blue' },
                      1: { color: 'green' },
                      2: { color: 'orange' }
                    }};
                    var chart_area = document.getElementById('total_subscribers');
                    var chart = new google.visualization.PieChart(chart_area);

                    // google.visualization.events.addListener(chart, 'ready', function(){
                    //   chart_area.style.display = 'none';
                    //   document.getElementById('total_subscribers_img').src = chart.getImageURI();
                    // });
                    chart.draw(data, options);
                  }
                </script>

                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:left;'>S/N</th>
                    <th style='text-align:left;'>Name</th> 
                    <th style='text-align:left;'>Email</th> 
                    <th style='text-align:left;'>Distribution Type</th>
                    <th style='text-align:left;'>Distribution Date</th> 
                </tr>";
            $count = 0;
            foreach ($newsletterSentMailList as $key => $data) {
                $mail = $data['StaffUser']['email'];
                $email = $this->getEmailforClippingReport($mail);
                $emailPart = explode("@", $mail);
                $name = ucfirst($emailPart[0]);
                $clippingRow .= "<tr style='padding-top:3px'><td style='text-align:left;'>" . ($count + 1) . "</td><td style='text-align:left;'>" . $name . "</td><td style='text-align:left;'>$email</td><td style='text-align:left;'>Mail</td><td style='text-align:left;'>" . date($dateformate, strtotime($data['NewsletterLog']['created'])) . "</td></tr>";
                $count++;
            }
            $clippingRow   .= "</table>";
        }
        return $clippingRow;
    }

    private function getNewsletterReceivedMailHtml($newsletterReceivedMailList=[],$newsletterSentMailList=[]){
        $clippingRow="";
            if (!empty($newsletterReceivedMailList)) {
                $clippingRow .= "
                    <div style='text-align:left;margin:20px 0px 20px 0px' class='col-lg-6'>
                        <strong>Subscribers who received press release</strong>
                    </div>

                    <div id='recevied_subscribers'></div>

                    <script type='text/javascript'>
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);
                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                        ['Task', 'Hours per Day'],
                        ['Total Subscribers: " . count($newsletterSentMailList) . "', " . (100 - (count($newsletterReceivedMailList) / count($newsletterSentMailList) * 100)) . "],
                        ['Total Received: " . count($newsletterReceivedMailList) . "', " . (count($newsletterReceivedMailList) / count($newsletterSentMailList) * 100) . "]
                    ]);
                    var options = {'width':450, 'is3D': true, slices: {
                        0: { color: 'blue' },
                        1: { color: 'green' },
                        2: { color: 'orange' }
                    }};
                    var chart = new google.visualization.PieChart(document.getElementById('recevied_subscribers'));
                        chart.draw(data, options);
                    }
                    </script>

                    <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                    <tr>
                        <th style='text-align:left;'>S/N</th>
                        <th style='text-align:left;'>Name</th> 
                        <th style='text-align:left;'>Email</th> 
                        <th style='text-align:left;'>Distribution Type</th>
                        <th style='text-align:left;'>Distribution Date</th> 
                    </tr>";
                    $count = 0;
                    foreach ($newsletterReceivedMailList as $key => $data) {
                        $mail = $data['StaffUser']['email'];
                        $email = $this->getEmailforClippingReport($mail);
                        $emailPart = explode("@", $mail);
                        $name = ucfirst($emailPart[0]);
                        $clippingRow .= "<tr style='padding-top:3px'><td style='text-align:left;'>" . ($count + 1) . "</td><td style='text-align:left;'>" . $name . "</td><td style='text-align:left;'>$email</td><td style='text-align:left;'>Mail</td><td style='text-align:left;'>" . date($dateformate, strtotime($data['NewsletterLog']['created'])) . "</td></tr>";
                        $count++;
                    }
               $clippingRow   .= "</table>";
            }
            return $clippingRow;
        }

    private function getNewsletterMailHtml($newsletterMailList=[],$newsletterSentMailList=[]){
        $clippingRow = "";
        if ($newsletterMailList) {
            
                $clippingRow .= "
                    <div style='text-align:left;margin:20px 0px 20px 0px' class='col-lg-6'>
                        <strong>Subscribers who opened press release</strong>
                    </div>

                    <div id='opened_subscribers'></div>

                    <script type='text/javascript'>
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);
                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                        ['Task', 'Hours per Day'],
                        ['Total Received: " . count($newsletterSentMailList) . "', " . (100 - (count($newsletterMailList) / count($newsletterReceivedMailList) * 100)) . "],
                        ['Total Opened: " . count($newsletterMailList) . "', " . (count($newsletterMailList) / count($newsletterReceivedMailList) * 100) . "]
                    ]);
                    var options = {'width':450, 'is3D': true, slices: {
                        0: { color: 'blue' },
                        1: { color: 'green' },
                        2: { color: 'orange' }
                    }};
                    var chart = new google.visualization.PieChart(document.getElementById('opened_subscribers'));
                        chart.draw(data, options);
                    }
                    </script>

                    <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                    <tr>
                        <th style='text-align:left;'>S/N</th>
                        <th style='text-align:left;'>Name</th> 
                        <th style='text-align:left;'>Email</th> 
                        <th style='text-align:left;'>Distribution Type</th>
                        <th style='text-align:left;'>View</th>
                        <th style='text-align:left;'>Distribution Date</th> 
                    </tr>";
                $count = 0;
                foreach ($newsletterMailList as $id => $data) {
                    $mail = $data['ClippingReport']['email'];
                    $view = $data['ClippingReport']['views'];
                    $email = $this->getEmailforClippingReport($mail);
                    $emailPart = explode("@", $mail);
                    $name = ucfirst($emailPart[0]);
                    $clippingRow .= "<tr style='padding-top:3px'><td style='text-align:left;'>" . ($count + 1) . "</td><td style='text-align:left;'>" . $name . "</td><td style='text-align:left;'>$email</td><td style='text-align:left;'>Mail</td> <td style='text-align:left;'>$view</td><td style='text-align:left;'>" . date($dateformate, strtotime($data['ClippingReport']['created'])) . "</td></tr>";
                    $count++;
                }
            $clippingRow   .= "</table>";
        }
        return $clippingRow;
    }



     /*
     * @params:  
     * @Function use: convertImageIntoBase64: Using for clipping report
     * @created by: Hitesh verma
     * @Created: 16-10-2022
     */
 
    function convertImageIntoBase64($imagePath="",$imageName=""){
        if(!empty($imagePath)){
            $type = pathinfo($imagePath, PATHINFO_EXTENSION);
            $fileData = file_get_contents($imagePath);  // https://image.com/img_name.png
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($fileData);
            return $base64;
        }
        return false;
    }


      /*
     * @params:  
     * @Function use: numberFormatAsUs: Using for clipping report
     * @created by: Hitesh verma
     * @Created: 16-10-2022
     */
 
    public function numberFormatAsUs($number=""){
        
        if(!empty($number)){
            setlocale(LC_MONETARY, 'en_US');
            $number = $this->money_format('%!i', $number);
            return $number;
        }
        return (!empty($number))?$number:"-";
    }

    private function money_format($format, $number)
    {
        $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?' .
            '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
        if (setlocale(LC_MONETARY, 0) == 'C') {
            setlocale(LC_MONETARY, '');
        }
        $locale = localeconv();
        preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
        foreach ($matches as $fmatch) {
            $value = floatval($number);
            $flags = array(
                'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
                    $match[1] : ' ',
                'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
                'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                    $match[0] : '+',
                'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
                'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
            );
            $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
            $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
            $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
            $conversion = $fmatch[5];

            $positive = true;
            if ($value < 0) {
                $positive = false;
                $value  *= -1;
            }
            $letter = $positive ? 'p' : 'n';

            $prefix = $suffix = $cprefix = $csuffix = $signal = '';

            $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
            switch (true) {
                case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                    $prefix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                    $suffix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                    $cprefix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                    $csuffix = $signal;
                    break;
                case $flags['usesignal'] == '(':
                case $locale["{$letter}_sign_posn"] == 0:
                    $prefix = '(';
                    $suffix = ')';
                    break;
            }
            if (!$flags['nosimbol']) {
                $currency = $cprefix .
                    ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                    $csuffix;
            } else {
                $currency = '';
            }
            $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

            $value = number_format(
                $value,
                $right,
                $locale['mon_decimal_point'],
                $flags['nogroup'] ? '' : $locale['mon_thousands_sep']
            );
            $value = @explode($locale['mon_decimal_point'], $value);

            $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
            if ($left > 0 && $left > $n) {
                $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
            }
            $value = implode($locale['mon_decimal_point'], $value);
            if ($locale["{$letter}_cs_precedes"]) {
                $value = $prefix . $currency . $space . $value . $suffix;
            } else {
                $value = $prefix . $value . $space . $currency . $suffix;
            }
            if ($width > 0) {
                $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                    STR_PAD_RIGHT : STR_PAD_LEFT);
            }

            $format = str_replace($fmatch[0], $value, $format);
            
        }
        return  ($format > 0)?str_replace(".00","",$format):"-"; //N/A
    }


    function sendMailByServer($mailSendTo="",$subject="",$message="",$from=""){
        $from=(empty($from))?'emailwireweb@gmail.com':$from;
        App::uses('CakeEmail', 'Network/Email');
        $Email = new CakeEmail('smtp');
        $Email->from(array($from => $subject));
        $Email->to(trim($mailSendTo));
        $Email->replyTo($from);
        $Email->subject($subject);
        $Email->emailFormat('html');
        return $Email->send($message);
    }

    
    public function crop_resize_image($src, $dest, $quality = 50, $thum_width = 315, $thum_height = null)
    {

        if (extension_loaded('imagick')) {

            if (class_exists("Imagick")) {

                $img = new Imagick();

                $img->readImage($src);

                $img->setImageFormat('jpg');

                $img->setImageCompression(imagick::COMPRESSION_JPEG);

                $img->setImageCompressionQuality($quality);

                $img->stripImage();

                // $img->thumbnailImage($thum_width,$thum_height);

                $img->cropThumbnailImage($thum_width, $thum_height, true);

                $img->writeImage($dest);

                return true;
            } else {

                echo "<br /> Imagick class not found.";
            }
        } else {

            echo 'imagick extension not loaded';
        }
    }
    
}
