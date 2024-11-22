<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Inflector', 'Utility');
class AjaxController extends AppController
{

    public $name = 'Ajax';

    public $uses = array('Company', "State", 'StaffUser', 'Plan', 'Coupon', 'PlanCategoriesState', 'CountryPlanCategory', 'MsasPlanCategory', 'Msa', 'Cart', 'CartDistribution', 'EmailTemplate', 'Transaction','CompanyDocument');

    public function beforeFilter()
    {
        $this->layout = false;
        parent::beforeFilter();
        $this->set('controller', 'ajax');
        $this->Auth->allow('check_user_email', 'search_company', "get_compnies", "addtocart", "removecartitem", "clearcart", "applycoupon", "removecoupon", "uploadimage", "getsentmail", "removePrImage", "pruploadimage", "mediafileupload", "cancel_subscription", "sendquery", "removeBannerImage", "saveChartImage",'trackpr','countemails');
    }


    public function load_company_detail()
    {
        $condition = array();
        $condition[] = array("id" => $this->data['company_id']);
        $this->Company->recursive=-1;
        $data_array = $this->Company->find('first', array('conditions' => $condition));
        $response = array();
        $response['contact_name'] = $data_array['Company']['media_contact_name'];
        $response['email'] = $data_array['Company']['media_email'];
        $response['phone_number'] = $data_array['Company']['media_phone_number'];
        $response['job_title'] = $data_array['Company']['media_job_title']; 
        return json_encode($response);
    }

    public function get_state()
    {
        $state_list = $condition = array();
        if (!empty($this->data['planId']) && !empty($this->data['is_plan_include']) && $this->data['is_plan_include'] == "yes") {
            $planDetail = $this->Plan->find('first', array('conditions' => array('Plan.id' => $this->data['planId'])));
            if(!empty($planDetail['PlanCategory']['is_allowed_all_state']) && $planDetail['PlanCategory']['is_allowed_all_state'] == 1) {
                $msa_list = $this->State->find('list', array(
                    'conditions' => array('State.status' => 1,'State.country_id' => $this->data['countryId']),
                    'fields' => array('State.id', 'State.name')
                ));
            }else if (!empty($planDetail['PlanCategory']['is_state_allowed']) && $planDetail['PlanCategory']['is_state_allowed'] == 1) {
                $state_list = $this->PlanCategoriesState->find('list', array(
                    'joins' => array(
                        array(
                            'table' => 'states',
                            'alias' => 'State',
                            'type' => 'INNER',
                            'conditions' => array(
                                'State.id = PlanCategoriesState.state_id'
                            )
                        ),
                    ),
                    'conditions' => array('State.status' => 1, 'plan_category_id' => $planDetail['PlanCategory']['id'], 'country_id' => $this->data['countryId']),
                    'fields' => array('State.id', 'State.name')
                ));
            }
        }
        if (empty($this->data['planId']) || empty($state_list)) {
            $condition = array('State.status' => 1, 'country_id' => $this->data['countryId']);
            $state_list = $this->State->find('list', array('conditions' => $condition));
        }
        $isMultiple = (!empty($this->data['isMultiple']) && $this->data['isMultiple'] != "yes") ? false : true;
        $fieldName = (!empty($this->data['fnm'])) ? $this->data['fnm'] : 'State.State';
        $isPlanDataInclude = (!empty($this->data['is_plan_include'])) ? $this->data['is_plan_include'] : 'yes';
        $cityFldNm = (!empty($this->data['cityFldNm'])) ? $this->data['cityFldNm'] : 'Msa.Msa';
        $isCityMultiple = (!empty($this->data['isCityMultiple'])) ? $this->data['isCityMultiple'] : 'yes';
        $cityResId = (!empty($this->data['cityResId'])) ? $this->data['cityResId'] : $cityFldNm . '_box';
        $this->set(compact('state_list', 'isMultiple', 'fieldName', 'cityFldNm', 'isCityMultiple', 'cityResId', 'isPlanDataInclude'));
    }


    public function get_msas()
    {
        $msa_list = $condition = array();
        $stateIds = explode(",", $this->data['stateId']);
        if (!empty($this->data['planId']) && !empty($this->data['is_plan_include']) && $this->data['is_plan_include'] == "yes") {
            $planDetail = $this->Plan->find('first', array('conditions' => array('Plan.id' => $this->data['planId'])));

            if (!empty($planDetail['PlanCategory']['is_allowed_all_msa']) && $planDetail['PlanCategory']['is_allowed_all_msa'] == 1) {
                $msa_list = $this->Msa->find('list', array(
                    'conditions' => array('Msa.status' => 1,'Msa.state_id' => $stateIds),
                    'fields' => array('Msa.id', 'Msa.name')
                ));
            }else if (!empty($planDetail['PlanCategory']['is_msa_allowed']) && $planDetail['PlanCategory']['is_msa_allowed'] == 1) {
                $msa_list = $this->MsasPlanCategory->find('list', array(
                    'joins' => array(
                        array(
                            'table' => 'msas',
                            'alias' => 'Msa',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Msa.id = MsasPlanCategory.msa_id'
                            )
                        ),
                    ),
                    'conditions' => array('Msa.status' => 1, 'plan_category_id' => $planDetail['PlanCategory']['id'], 'Msa.state_id' => $stateIds),
                    'fields' => array('Msa.id', 'Msa.name')
                ));
            }
        }
        if (empty($this->data['planId']) || empty($msa_list)) {
            $condition = array('Msa.status' => 1, 'state_id' => $stateIds);
            $msa_list = $this->Msa->find('list', array('conditions' => $condition));
        }
        $isMultiple = (!empty($this->data['isMultiple']) && $this->data['isMultiple'] != "yes") ? false : true;
        $fieldName = (!empty($this->data['fnm'])) ? $this->data['fnm'] : 'Msa.Msa';
        $this->set(compact('msa_list', 'isMultiple', 'fieldName'));
    }


    public function get_source_states()
    {
        $state_list = $condition = array();
        if(!empty($this->data['countryId'])){
            $condition = array('State.status' => 1, 'country_id' => $this->data['countryId']);
            $state_list = $this->State->find('list', array('conditions' => $condition));
        }
        $model= (!empty($this->data['model'])) ? $this->data['model'] : 'PressRelease';
        $this->set(compact('state_list','model'));
    }

    public function get_source_cities()
    {   
        if(!empty($this->data['stateId'])){
            $msa_list = $condition = array();
            $condition = array('Msa.status' => 1, 'state_id' => $this->data['stateId']);
            $msa_list = $this->Msa->find('list', array('conditions' => $condition));
        }
        $model= (!empty($this->data['model'])) ? $this->data['model'] : 'PressRelease';
        $this->set(compact('msa_list','model'));
    }



    public function search_company()
    {
        if (!empty($this->data['company_name'])) {
            $condition[] = array("Company.name like '%" . $this->data['company_name'] . "%'");
            $condition[] =["Company.status !="=>4];
            $this->Company->recursive=-1;
            $old_company_list = $this->Company->find('count', array('conditions' => $condition));
            if ($old_company_list == 0) {
                $status = "true";
                $message = "<span class='text-success'>This company is avilable.</span>";
            } else {
                $status = "false";
                $url = (isset($this->data['redirect']) && !empty($this->data['redirect'])) ? 'login?r=' . $this->data['redirect'] : 'login';
                if ($this->Auth->loggedIn()) {
                    // $url='take-over-company';
                    $url = 'take-over-publishing';
                }
                $message = "<span class='text-danger'>This company is already listed with us <a href='" . SITEURL . "users/$url' >Click here to take over his company</a>.</span>";
            }
        } else {
            $status = "empty";
            $message = "<span class='text-danger'>Please enter company name.</span>";
        }
        echo json_encode(array("status" => $status, "message" => $message));
        $this->autoRender = false;
    }





    public function get_compnies()
    {
        $condition = array();
        $this->Company->recursive=-1;
        $condition[] = array("name like " => '%' . $this->request->query['term'] . '%',);
        $data_array = $this->Company->find('list', array('conditions' => $condition));
        $response = array();
        $counter = 1;
        foreach ($data_array as $id => $name) {
            $response[$counter]['label'] = $name;
            $response[$counter]['id'] = $id;
            $counter++;
        }
        echo json_encode($response);
        exit();
    }



    public function addtocart()
    {   
        try {
            $cart_plans=$cartData = [];
            $status = "true";
            if ($this->request->is("post")) {
                if (!empty($this->data['plan_id'])) {
                    $plan_id = $this->data['plan_id'];
                    if ($this->Auth->loggedIn()) {
                        $user_id = $this->Auth->user("id");
                        $check = $this->Custom->checkcart($user_id, $plan_id, 'plan');


                        if ($check > 0)
                            throw new NotFoundException(__('This plan already in your cart.'));


                        $checkCartType = $this->Custom->checkCartType($user_id, $plan_id);


                        if (!$checkCartType)
                            throw new NotFoundException(__('You can not add single and bluk plans with subscription plans or you can not add multiple subscription plans.'));


                        $cart_plans = ($this->Session->check("ew_cartdata")) ? $this->Session->read("ew_cartdata") : "";
                        $cartData = $this->Custom->addToCartWithDb($cart_plans, $plan_id, $user_id);
                    } else {
                        $cart_plans = ($this->Session->check("ew_cartdata")) ? $this->Session->read("ew_cartdata") : "";
                        $cartData = $this->Custom->addToCartWithSession($cart_plans, $plan_id);
                        $this->Session->write("ew_cartdata", $cartData);
                    }
                    $message = "Plan successfully added in cart.";
                } else {
                    throw new NotFoundException(__('Invalid plan please try again.'));
                }
            } else {
                throw new NotFoundException(__('Invalid Method used.'));
            }
        } catch (Exception $exc) {
            $status = "false";
            $message = $exc->getMessage();
        }
        echo json_encode(array("status" => $status, "message" => $message, "data" => $cartData));;
        die;
    }





    public function removecartitem()
    {
        try {
            $cartData = [];
            $status = "true";
            $clear_cart_btn = "";
            if ($this->request->is("post")) {
                if (!empty($this->data['plan_id'])) {
                    $plan_id = $this->data['plan_id'];
                    $cart_session_id = (isset($this->data['cart_session_id']) && !empty($this->data['cart_session_id'])) ? $this->data['cart_session_id'] : "";
                    if ($this->Auth->loggedIn()) {
                        $user_id = $this->Auth->user("id");
                        $conditions = array('Cart.staff_user_id' => $user_id, 'cart_type' => "plan");
                        if (!empty($cart_session_id)) {
                            $conditions = array('Cart.staff_user_id' => $user_id, 'Cart.cart_session_id' => $cart_session_id, 'cart_type' => "plan");
                        }
                        $check = $this->Cart->find('first', array('conditions' => $conditions));
                        if (empty($check)) {
                            throw new NotFoundException(__('Cart is empty.Please buy a PR plan.'));
                        } else {
                            $delCondi = array('Cart.staff_user_id' => $user_id, 'Cart.plan_id' => $this->data['plan_id']);
                            if (!empty($cart_session_id)) {
                                $delCondi = array('Cart.staff_user_id' => $user_id, 'Cart.cart_session_id' => $cart_session_id, 'Cart.plan_id' => $this->data['plan_id']);
                            }
                            $this->Cart->deleteAll($delCondi, false);
                            $cartData = $this->Custom->getUserCartData($user_id, $cart_session_id);
                            $message = "Cart clear successfully.";
                        }
                    } else {
                        if (!empty($this->Session->read("ew_cartdata"))) {
                            $cart_plans = $this->Session->read("ew_cartdata");
                            $cartData = $this->Custom->getRemoveItemData($cart_plans, $plan_id);
                            $this->Session->write("ew_cartdata", $cartData);
                        } else {
                            throw new NotFoundException(__('Cart is empty.Please buy a PR plan.'));
                        }
                    }
                    if (count($cartData['plans']) <= 0) {
                        $clear_cart_btn = "hide";
                    }
                    $message = "Plan successfully remove from cart.";
                } else {
                    throw new NotFoundException(__('Invalid plan please try again.'));
                }
            } else {
                throw new NotFoundException(__('Invalid request!'));
            }
        } catch (Exception $exc) {
            $status = "false";
            $message = $exc->getMessage();
        }
        echo json_encode(array("status" => $status, "message" => $message, "data" => $cartData, "clear_cart_btn" => $clear_cart_btn));
        die;
    }



    public function clearcart()
    {
        $cartData = [];
        $status = "true";
        try {
            if ($this->Auth->loggedIn()) {
                $user_id = $this->Auth->user("id");
                $cart_session_id = (isset($this->data['cart_session_id']) && !empty($this->data['cart_session_id'])) ? $this->data['cart_session_id'] : "";
                $count = $this->Cart->find('count', array('conditions' => array('Cart.staff_user_id' => $user_id, 'Cart.is_newsroom_incart' => '0')));
                if ($count == 0) {
                    throw new NotFoundException(__('Cart is empty.Please buy a PR plan.'));
                } else {
                    $this->Cart->deleteAll(array('Cart.staff_user_id' => $user_id, 'Cart.is_newsroom_incart' => '0', 'cart_type' => "plan"), false);
                    $cartData = $this->Custom->getUserCartData($user_id);
                    $message = "Cart clear successfully.";
                }
            } else {
                if (!empty($this->Session->read("ew_cartdata"))) {
                    $cart_plans = $this->Session->read("ew_cartdata");
                    $message = "Cart clear successfully.";
                    $totals = $discount = $subtotal = "0.00";
                    $cartData['plans'] = [];
                    $cartData["newsroom_amount"] = isset($cart_plans["newsroom_amount"]) ? $cart_plans["newsroom_amount"] : "0.00";
                    $cartData["discount_id"] = "";
                    $cartData["promo_code"] = "";
                    $cartData['totals']["subtotal"] = (($subtotal + $cartData["newsroom_amount"]) > 0) ? round(($subtotal + $cartData["newsroom_amount"]), 2) : "0.00";;
                    $cartData['totals']["discount"] = $discount;
                    $cartData['totals']["tax"] = "0.00";
                    $cartData['totals']["total"] = $this->Custom->get_cart_total($cartData["newsroom_amount"], $subtotal, $cartData['totals']["discount"]);
                    $this->Session->write("ew_cartdata", $cartData);
                } else {
                    throw new NotFoundException(__('Cart is empty.Please buy a PR plan.'));
                }
            }
        } catch (Exception $exc) {
            $status = "false";
            $message = $exc->getMessage();
        }
        echo json_encode(array("status" => $status, "message" => $message, "data" => $cartData, "clear_cart_btn" => 'hide'));
        die;
    }







    public function applycoupon()
    {
        try {
            $status = "true";
            $cartData = [];
            if ($this->request->is("post")) {
                if ($this->data['promo_code']) {
                    $cart_session_id = $this->data['cart_session_id'];
                    $coupon_data = $this->Coupon->find('first', array('conditions' => array('Coupon.status' => 1, 'code' => trim($this->data['promo_code']), 'release_date <=' => date('Y-m-d'), 'end_date >=' => date('Y-m-d'))));
                    if (empty($coupon_data))
                        throw new NotFoundException(__('Invalid Promo Code.'));


                    if (!empty($coupon_data['Coupon']['coupon_limit']) && $coupon_data['Coupon']['coupon_limit'] > 0) {
                        $countUseCoupon = $this->Transaction->find('count', array('conditions' => array('Transaction.discount_id' => $coupon_data['Coupon']['id'])));
                        if ($coupon_data['Coupon']['coupon_limit'] <= $countUseCoupon)
                            throw new NotFoundException(__('Promo Code has been expired or exceed limit.'));
                    }


                    if ($this->Auth->loggedIn()) {
                        $user_id = $this->Auth->user("id");
                        $check = $this->Cart->find('first', array('fields' => ["Cart.cart_session_id"], 'conditions' => array('Cart.staff_user_id' => $user_id, 'Cart.cart_session_id' => $cart_session_id)));
                        //,'Cart.is_newsroom_incart'=>'0' 


                        if (empty($check))
                            throw new NotFoundException(__('Something is wrong! Please refresh this page and try again.'));


                        $this->Cart->query("UPDATE `carts` SET `coupon_id` = '" . $coupon_data['Coupon']['id'] . "' WHERE `carts`.`staff_user_id` = '$user_id' AND `carts`.`cart_session_id` = '" . $cart_session_id . "'");


                        $cartData = $this->Custom->getUserCartData($user_id, $cart_session_id, $coupon_data);


                        $couponArray['promo_code'] = $this->data['promo_code'];
                        $couponArray['coupon_id'] = $coupon_data['Coupon']['id'];
                        $couponArray['discount'] = $cartData['totals']['discount'];
                        $this->Session->write('FrontCart.coupon', $couponArray);


                        $message = "Promo code successfully apply.";
                    } else {
                        if ($this->Session->check("ew_cartdata")) {
                            $cart_plans = $this->Session->read("ew_cartdata");
                            $cartData = $this->Custom->applyCouponWithSession($cart_plans, $coupon_data);
                            $this->Session->write("ew_cartdata", $cartData);
                            $message = "Promo code successfully apply.";
                        } else {
                            throw new NotFoundException(__('Something is wrong! Please refresh this page and try again.'));
                        }
                    }
                } else {
                    throw new NotFoundException(__('Please Enter promo code.'));
                }
            } else {
                throw new NotFoundException(__('Invalid request!'));
            }
        } catch (Exception $exc) {
            $status = "false";
            $message = $exc->getMessage();
        }
        echo json_encode(array("status" => $status, "message" => $message, "data" => $cartData));
        die;
    }



    public function removecoupon()
    {
        try {
            $status = "true";
            $cart_plans = [];
            if ($this->request->is("post")) {
                if ($this->data['promo_code']) {
                    if ($this->Auth->loggedIn()) {
                        $user_id = $this->Auth->user("id");
                        $this->Session->delete('FrontCart.coupon');
                        $cart_session_id = $this->data['cart_session_id'];
                        $this->Cart->query("UPDATE `carts` SET `coupon_id` = '0' WHERE `carts`.`staff_user_id` = '$user_id' AND `carts`.`cart_session_id` = '" . $cart_session_id . "'");
                        $cart_plans = $this->Custom->getUserCartData($user_id, $cart_session_id);
                        $message = "Promo code removed successfully apply.";
                    } else {
                        $user_id = $this->Auth->user("id");
                        $cart_plans = $this->Session->read("ew_cartdata");
                        if (count($cart_plans['plans']) > 0) {
                            $totals = $discount = $subtotal = "0.00";
                            $subtotal = $cart_plans['totals']["subtotal"];
                            $discount_id = '';
                            $cart_plans["newsroom_amount"] = isset($cart_plans["newsroom_amount"]) ? $cart_plans["newsroom_amount"] : "0.00";
                            $cart_plans["discount_id"] = $discount_id;
                            $cart_plans["promo_code"] = (!empty($this->data['promo_code'])) ? $this->data['promo_code'] : "";
                            $cart_plans['totals']["subtotal"] = $subtotal + $cart_plans["newsroom_amount"];;
                            $cart_plans['totals']["discount"] = round($discount, 2);
                            $cart_plans['totals']["tax"] = "0.00";


                            $cart_plans['totals']["total"] = $this->Custom->get_cart_total($cart_plans["newsroom_amount"], $subtotal, $cart_plans['totals']["discount"]);
                            $this->Session->write("ew_cartdata", $cart_plans);
                            $message = "Promo code removed successfully apply.";
                        } else {
                            throw new NotFoundException(__('Please add plan in cart.'));
                        }
                    }
                } else {
                    throw new NotFoundException(__('Please Enter promo code.'));
                }
            } else {
                throw new NotFoundException(__('Invalid request!'));
            }
        } catch (Exception $exc) {
            $status = "false";
            $message = $exc->getMessage();
        }
        echo json_encode(array("status" => $status, "message" => $message, "data" => $cart_plans));
        die;
    }





    public function check_user_email()
    {
        if (!empty($this->data['email_user'])) {
            $condition[] = array("StaffUser.email like '%" . $this->data['email_user'] . "%'");
            $condition[] =['StaffUser.status !='=>3];
            $email_count = $this->StaffUser->find('count', array('conditions' => $condition));
            if ($email_count == 0) {
                $status = "true";
                $message = "<span class='text-success'>This email is avilable.</span>";
            } else {
                $status = 'false';
                $message = "<span class='text-danger'>This email is already exist.</span>";
            }
        } else {
            $status = "empty";
            $message = "<span class='text-danger'>Please enter email.</span>";
        }
        echo json_encode(array("status" => $status, "message" => $message));
        $this->autoRender = false;
    }





    public function praddtocart()
    {
        try {
            $transdata=[];
            $status = 'true';
            $message = '';
            $index = "0";
            if ($this->data['plan_id']) {
                $cart_plans['prlist'] = $cart_plans = $savedata = [];
                $famount = $tax = $discount = $plan_amount = '0.00';
                $listId = "";
                $user_id = $this->Auth->user("id");
                $plan_id = $this->data['plan_id'];
                $pr_id = (!empty($this->data['pr_id']) && !empty($this->data['pr_id'])) ? $this->data['pr_id'] : "";
                $currency = Configure::read('Site.currency');
                $totalword = (!empty($this->data['totalword'])) ? $this->data['totalword'] : "0";
                $catcount = (!empty($this->data['catcount'])) ? $this->data['catcount'] : "0";
                $msacount = (!empty($this->data['msacount'])) ? $this->data['msacount'] : "0";
                $trans_applied = (isset($this->data['trans_applied']) && $this->data['trans_applied'] > 0) ? $this->data['trans_applied'] : "0";

                $statecount = (isset($this->data['statecount'])) ? $this->data['statecount'] : "0";
                $plan = $this->Plan->find('first', array('conditions' => array('Plan.id' => $plan_id)));
                if(!empty($pr_id)){
                    $this->loadModel('TransactionPressRelease');
                    $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('staff_user_id' => $user_id, 'press_release_id' => $pr_id)));

                    $checkcart = $this->Cart->find('first', array('conditions' => array('staff_user_id' => $user_id, 'press_release_id' => $pr_id, 'cart_type' => 'pr')));
                }else{
                    $checkcart = $this->Cart->find('first', array('conditions' => array('staff_user_id' => $user_id, 'plan_id' => $plan_id, 'cart_type' => 'pr','press_release_id'=>"0")));
                }
                if (!empty($checkcart)){
                    $savedata['Cart']['id'] = $checkcart['Cart']['id'];
                }

                $savedata['Cart']['cart_type'] = 'pr';
                $savedata['Cart']['plan_id'] = $plan_id;
                $savedata['Cart']['press_release_id'] = $pr_id;
                $savedata['Cart']['staff_user_id'] = $user_id;
                $savedata['Cart']['extra_words'] = $savedata['Cart']['extra_category'] = "0";


                if (!empty($checkcart['Cart'])) {
                    $listId = $checkcart['Cart']['list_id'];
                }
                $listId = (isset($this->data['list_id']) && !empty($this->data['list_id'])) ? $this->data['list_id'] : $listId;

                
                $maxExtraWordLimit=(!empty($transdata['TransactionPressRelease']['extra_words']) && !empty($transdata['TransactionPressRelease']['word_amount']))?$transdata['TransactionPressRelease']['extra_words']+$plan['PlanCategory']['word_limit']:$plan['PlanCategory']['word_limit'];

                if ($totalword > 0 && $plan['Plan']['plan_type'] == 'single' && $maxExtraWordLimit > 0 && $totalword >= $maxExtraWordLimit) {
                    $savedata['Cart']['extra_words'] = ($totalword - $maxExtraWordLimit);
                    $amtw = ceil(($savedata['Cart']['extra_words'] / 100)) * $plan['Plan']['add_word_amount'];
                    $amount = number_format($amtw, 2);
                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;
                    $cart_plans['prlist'][$index]["title"] = "Additional 100 words charges";
                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amount;
                    $cart_plans['prlist'][$index]["class"] = 'words_charges';
                    $plan_amount += $amtw;
                    $index++;
                }


                $maxCategoryLimit=(!empty($transdata['TransactionPressRelease']['extra_category']))?$transdata['TransactionPressRelease']['extra_category']+$plan['Plan']['category_limit']:$plan['Plan']['category_limit'];

                if ($catcount > 0 && $maxCategoryLimit > 0 && $catcount > $maxCategoryLimit) {
                    $savedata['Cart']['extra_category'] = $catcount - $maxCategoryLimit;
                    $amtcat = ceil($savedata['Cart']['extra_category'] * $plan['Plan']['add_category_charges']);
                    $amount1 = number_format($amtcat, 2);
                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;
                    $cart_plans['prlist'][$index]["title"] = "Additional category charges";
                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amount1;
                    $cart_plans['prlist'][$index]["class"] = 'category_charges';
                    $plan_amount += $amtcat;
                    $index++;
                }


                $maxMsaLimit=(!empty($transdata['TransactionPressRelease']['extra_msa']))?$transdata['TransactionPressRelease']['extra_msa']+$plan['Plan']['msa_limit']:$plan['Plan']['msa_limit'];

                if ($msacount > 0 && $maxMsaLimit > 0 && $msacount > $maxMsaLimit) {
                    $savedata['Cart']['extra_msa'] = $msacount - $maxMsaLimit;


                    $amtmsa = ceil($savedata['Cart']['extra_msa'] / $maxMsaLimit) * ($plan['Plan']['add_msa_charges']);
                    $amountmsa = number_format($amtmsa, 2);
                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;
                    $cart_plans['prlist'][$index]["title"] = "Additional msa charges";
                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amountmsa;
                    $cart_plans['prlist'][$index]["class"] = 'msa_charges';
                    $plan_amount += $amtmsa;
                    $index++;
                }

                $maxStateLimit=(!empty($transdata['TransactionPressRelease']['extra_state']))?$transdata['TransactionPressRelease']['extra_state']+$plan['Plan']['state_limit']:$plan['Plan']['state_limit'];

                if ($statecount > 0 && $maxStateLimit  > 0 && $statecount > $maxStateLimit) {


                    $savedata['Cart']['extra_state'] = $statecount - $maxStateLimit;
                    $amtstate = ceil($savedata['Cart']['extra_state'] / $maxStateLimit) * ($plan['Plan']['add_msa_charges']);
                    $amountstate = number_format($amtstate, 2);
                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;
                    $cart_plans['prlist'][$index]["title"] = "Additional state charges";
                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amountstate;
                    $cart_plans['prlist'][$index]["class"] = 'state_charges';
                    $plan_amount += $amtstate;
                    $index++;
                }
                $savedata['Cart']['translate_charges'] = $trans_applied;
                $isPaidTranslateCharges=(!empty($transdata['TransactionPressRelease']['translate_charges']))?$transdata['TransactionPressRelease']['translate_charges']:"";

                if ($trans_applied > 0 && $plan['Plan']['translation_amount'] > 0 &&  empty($isPaidTranslateCharges)) {
                    $savedata['Cart']['translate_charges'] = $trans_applied;
                    $amttx = $plan['Plan']['translation_amount'];
                    $amttx = number_format($amttx, 2);
                    $cart_plans['prlist'][$index]["plan_id"] = $plan_id;
                    $cart_plans['prlist'][$index]["title"] = "Additional page tranlate charges";
                    $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amttx;
                    $cart_plans['prlist'][$index]["class"] = 'trans_charges';
                    $plan_amount += $amttx;
                    $index++;
                }


                $savedata['Cart']['distribution_ids'] = null;
                $cart_plans['feature'] = $feature = $removeFeature = [];

                $getPaidAdditionalFeature=(!empty($transdata['TransactionPressRelease']['distribution_ids']))?unserialize($transdata['TransactionPressRelease']['distribution_ids']):"";
                $mapPaidAdditionalFeature=$this->Custom->mapAdditionFeatureInArr($getPaidAdditionalFeature);

                $selectedfeatures = (isset($this->data['selectedfeatures']) & !empty($this->data['selectedfeatures'])) ? $this->data['selectedfeatures'] : "";
                if (!empty($selectedfeatures)) {
                    foreach ($selectedfeatures as $index => $selectedfeatureId) {
                        if(!empty($mapPaidAdditionalFeature)&& in_array($selectedfeatureId,$mapPaidAdditionalFeature)){
                            continue;  // if user paid for a service never ask for payment
                        }
                        if ($selectedfeatureId != 8) {
                            $feature[$index]['distribution_id'] = $selectedfeatureId;
                            $featureData = $this->Custom->getprfeatureprice($selectedfeatureId);
                            $featureAmount = $this->Custom->getAmountMailList($listId, $selectedfeatureId, $featureData['amount'], $featureData['number']);
                            if (isset($plan['PlanCategory']['is_featured_pr']) && $plan['PlanCategory']['is_featured_pr'] == 1 && $selectedfeatureId == 2) {
                                $featureAmount = 0;
                            }


                            $cart_plans['feature'][$index]['price'] = $currency . '' . $featureAmount;
                            $cart_plans['feature'][$index]['name'] = $featureData['name'];
                            $cart_plans['feature'][$index]['class'] = 'feature-' . $selectedfeatureId;
                            $famount = $famount + $featureAmount;
                        } elseif ($selectedfeatureId == 8 && $listId != "" && $this->data['isremovefeature'] != "8") {


                            $feature[$index]['distribution_id'] = $selectedfeatureId;
                            $featureData = $this->Custom->getprfeatureprice($selectedfeatureId);
                            $featureAmount = $this->Custom->getAmountMailList($listId, $selectedfeatureId, $featureData['amount'], $featureData['number']);
                            if (isset($plan['PlanCategory']['is_featured_pr']) && $plan['PlanCategory']['is_featured_pr'] == 1 && $selectedfeatureId == 2) {
                                $featureAmount = 0;
                            }
                            $cart_plans['feature'][$index]['price'] = $currency . '' . $featureAmount;
                            $cart_plans['feature'][$index]['name'] = $featureData['name'];
                            $cart_plans['feature'][$index]['class'] = 'feature-' . $selectedfeatureId;
                            $famount = $famount + $featureAmount;
                        }
                    }
                }


                if ($this->data['isremovefeature'] == 8 && $pr_id > 0) {
                    $listId = "0";
                }




                $getCartSession = $this->Custom->getCartSessionId($user_id, $plan_id, 'pr');
                $savedata['Cart']['cart_session_id'] = (!empty($getCartSession)) ? $getCartSession['cart_session_id'] : Security::hash(CakeText::uuid(), 'sha1', true);
                $savedata['Cart']['list_id'] = $listId;
                $savedata['Cart']['distribution_ids'] = (!empty($feature)) ? serialize($feature) : null;
                $savedata['Cart']['next_email'] = date('Y-m-d', strtotime('+7 days'));
                $plan_amount += $famount;
                $this->Cart->save($savedata);


                $cart_plans['totals']['subtotal'] = number_format($plan_amount, 2);
                $cart_plans['totals']['discount'] = number_format($discount, 2);
                $cart_plans['totals']['tax'] = $tax;
                $cart_plans['totals']['total'] = $this->Custom->get_cart_total(0, $plan_amount, $discount);
            }
        } catch (Exception $exc) {
            $status = "false";
            $message = $exc->getMessage();
        }
        echo json_encode(array("status" => $status, "message" => $message, "data" => $cart_plans));
        die;
    }



    public function getprcart()
    {   
        $cart_plans="";
        try {
            $status = 'true';
            $message = '';
            $user_id = $this->Auth->user("id");
            $plan_id = $this->data['plan_id'];
            $selectedfeatures = (isset($this->data['selectedfeatures'])) ? $this->data['selectedfeatures'] : "";
            $prId = $this->data['prId'];
            if(!empty($prId)){
                $cart_plans = $this->Custom->getprcartdata($user_id, $plan_id, $prId, $selectedfeatures);
            }else{
                throw new Exception("Cart is empty.");
            }
        } catch (Exception $exc) {
            $status = "false";
            $message = $exc->getMessage();
        }
        echo json_encode(array("status" => $status, "message" => $message, "data" => $cart_plans));
        die;
    }





    public function addseotaginbodycontent()
    {
        try {


            $seo_tag = $this->data['seotag'];
            $tag_url = (!empty($this->data['tag_url'])) ? $this->data['tag_url'] : "#";
            $content = $this->data['content'];
            $aTag = "<a rel='nofollow' href='$tag_url' target='_blank'>$seo_tag</a>";
            if (!empty($this->data['seotag']) && $this->data['content']) {
                echo str_replace("$seo_tag", $aTag, $content);
            } else {
                echo $this->data['content'];
            }
            die;
        } catch (Exception $exc) {
            $status = "false";
            $message = $exc->getMessage();
        }


        die;
    }



    public function removehtml()
    {
        if (!empty($this->data['content'])) {
            $content = str_replace(array("<li>", "</li>"), array(" ", " "), $this->data['content']);
            echo strip_tags($content);
        } else {
            echo $this->data['content'];
        }
        die;
    }





    public function uploadimage($value = '')
    {
        $field = 'image_path';
        $field1 = 'image_name';
        try {
            if (!empty($this->request->data)) {
                $image_type = $this->request->data["image_type"];
                $date = date('Y') . DS . date('m');
                $name = uniqid() . ".png";


                if (!empty($this->request->data["oldimage"])) {
                    $del_file_path = str_replace(SITEURL, WWW_ROOT, $this->request->data["oldimage"]);
                    if ($image_type == "profile") {
                        $del_file_path = str_replace(SITEADMIN, ROOT . DS . 'admin' . DS . 'webroot' . DS, $this->request->data["oldimage"]);
                    }
                    $delfile1 = new File($del_file_path, false, 0777);
                    $delfile1->delete();
                }


                if ($image_type == "logo") {
                    $file_path = WWW_ROOT . 'files' . DS . 'company' . DS . 'logo' . DS . $date;
                    $img_url = SITEURL . 'files/company/logo/' . $date . '/' . $name;
                } else if ($image_type == "banner") {
                    $file_path = WWW_ROOT . 'files' . DS . 'company' . DS . 'banner' . DS . $date;
                    $img_url = SITEURL . 'files/company/banner/' . $date . '/' . $name;
                } else if ($image_type == "profile") {
                    $file_path = WWW_ROOT . 'files' . DS . 'profile_image';
                    $img_url = SITEURL . 'files/profile_image/' . $name;
                }


                if (!file_exists($file_path)) {
                    $dir = new Folder($file_path, true, 0755);
                }

                $base64 = $this->data['base64'];
                $base64 = substr($base64, strpos($base64, ',') + 1);
                $data = base64_decode($base64);


                file_put_contents($file_path . DS . $name, $data);
                $json = json_encode(array("status" => "success", "message" => "success", $field => $date, $field1 => $name, "img_url" => $img_url));
            }
        } catch (Exception $exc) {
            $status = "failed";
            $message = $exc->getMessage();
            $json = json_encode(array("status" => "failed", "message" => $message, $field => "", $field1 => "", "img_url" => ""));
        }
        echo $json;
        die;
    }





    public function getsentmail()
    {
        $this->loadModel("Campaign");
        if ($this->request->is("post")) {
            $prId = $this->data["pid"];
            $uId = $this->data["uid"];
            $lid = $this->data["lid"];
            $check = $this->Campaign->find("count", array("conditions" => array("Campaign.staff_user_id" => $uId, "Campaign.lists" => $lid, "Campaign.press_release_id" => $prId)));
            echo $check;
            $this->autoRender = false;
        }
    }







    public function removeBannerImage()
    {
        try {
            if (!empty($this->request->data["oldimage"])) {
                $del_file_path = str_replace(SITEURL, WWW_ROOT, $this->request->data["oldimage"]);
                $delfile1 = new File($del_file_path, false, 0777);
                if ($delfile1->delete()) {
                    if (isset($this->request->data["prImgId"]) && !empty($this->request->data["prImgId"])) {
                        $model = $this->request->data["model"];
                        $this->loadModel($model);
                        $this->$model->id = $this->request->data['prImgId'];
                        if ($this->$model->exists()) {
                            $saveData[$model]['id'] = $this->request->data['prImgId'];
                            $saveData[$model]['banner_path'] = Null;
                            $saveData[$model]['banner_image'] = Null;
                            $this->$model->save($saveData);
                        }
                    }
                    $json = json_encode(array("status" => "success", "message" => "success"));
                } else {
                    throw new Exception("Image not uploaded. Please try again");
                }
            } else {
                throw new Exception("Image not uploaded. Please try again");
            }
        } catch (Exception $exc) {
            $status = "failed";
            $message = $exc->getMessage();
            $json = json_encode(array("status" => "failed", "message" => $message));
        }
        echo $json;
        die;
    }

    public function removePrImage()
    {
        $this->loadModel('PressImage');
        try {
            if (!empty($this->request->data["oldimage"])) {
                $del_file_path = str_replace(SITEURL, WWW_ROOT, $this->request->data["oldimage"]);


                $delfile1 = new File($del_file_path, false, 0777);
                if ($delfile1->delete()) {
                    if (isset($this->request->data["prImgId"]) && !empty($this->request->data["prImgId"])) {
                        $this->PressImage->id = $this->request->data['prImgId'];
                        if ($this->PressImage->exists()) {
                            $this->PressImage->delete();
                        }
                    }
                    $json = json_encode(array("status" => "success", "message" => "success"));
                } else {
                    throw new Exception("Image not uploaded. Please try again");
                }
            } else {
                throw new Exception("Image not uploaded. Please try again");
            }
        } catch (Exception $exc) {
            $status = "failed";
            $message = $exc->getMessage();
            $json = json_encode(array("status" => "failed", "message" => $message));
        }
        echo $json;
        die;
    }

    public function pruploadimage()
    {
        try {
            if (!empty($_FILES)) {
                App::uses('File', 'Utility');
                App::uses('Folder', 'Utility');
                $files = $_FILES['formData'];
                if (!empty($files['tmp_name'])) {
                    $date = date('Y') . DS . date('m') . DS . date('d');
                    $file_path = WWW_ROOT . 'files' . DS . 'company' . DS . 'press_image' . DS . $date;
                    $dir = new Folder($file_path, true, 0755);
                    $image_path =  date('Y') . '/' . date('m') . '/' . date('d');
                    $fname = uniqid() . ".png";
                    $fileUrl = SITEURL . "files/company/press_image/" . $image_path . '/' . $fname;


                    if (move_uploaded_file($files['tmp_name'], $file_path . DS . $fname)) {
                        $json = json_encode(array("status" => "success", "message" => "success", "image_path" => $date, "image_name" => $fname, "img_url" => $fileUrl));
                    } else {
                        throw new Exception("Image not valid. Please try again");
                    }
                } else {
                    throw new Exception("Please upload image.");
                }
            }
        } catch (Exception $exc) {
            $status = "failed";
            $message = $exc->getMessage();
            $json = json_encode(array("status" => "failed", "message" => $message, "image_path" => "", "image_name" => "", "img_url" => ""));
        }
        echo $json;
        die;
    }



    public function mediafileupload()
    {
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');


        $files = $_FILES['upload'];
        $file_path = WWW_ROOT . 'files' . DS . "mediafileupload";
        $dir = new Folder($file_path, true, 0755);


        $fname = uniqid() . ".png";
        $fileUrl = SITEURL . "files/mediafileupload/" . $fname;


        if (move_uploaded_file($files['tmp_name'], $file_path . DS . $fname)) {
            echo json_encode(array("fileName" => $fname, "url" => $fileUrl, "uploaded" => "1"));
        } else {
            echo json_encode(array("fileName" => $fname, "url" => $fileUrl, "uploaded" => "0"));
        }
        die;
    }



    public function checkplanincart()
    {
        $status = "success";
        $message = "";
        try {
            $cartData = [];
            $status = "true";
            if (!empty($this->data['plan_id'])) {
                $plan_id = $this->data['plan_id'];
                if ($this->Auth->loggedIn()) {
                    $user_id = $this->Auth->user("id");
                    $check = $this->Custom->checkprcart($user_id, $plan_id);
                    if (!empty($check)) {
                        $message = 'This plan is already used in another PR and it`s payment is pending. Please <a href="' . SITEURL . 'users/makepayment/' . $plan_id . '/' . $check["Cart"]['press_release_id'] . '">make a payment</a>.';
                        throw new NotFoundException($message);
                    }
                }
            }
        } catch (Exception $exc) {
            $status = "failed";
            $message = $exc->getMessage();
        }
        $json = json_encode(array("status" => $status, "message" => $message));
        echo $json;
        die;
    }





    public function uploadadvertiseimage()
    {
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        $files = $_FILES['upload'];
        $file_path = WWW_ROOT . 'files' . DS . "advertisements";
        $dir = new Folder($file_path, true, 0755);
        $fname = uniqid() . ".png";
        $image_path = "files/advertisements/";


        if (move_uploaded_file($files['tmp_name'], $file_path . DS . $fname)) {
            echo json_encode(array("image" => $fname, "image_path" => $image_path));
        } else {
            echo json_encode(array("image" => $fname, "image_path" => $image_path));
        }
        die;
    }





    public function sendquery()
    {
        $status = "success";
        try {
            if ($this->request->is("post")) {
                $organisation_type = "";
                $name = $this->request->data['Query']['contact_name'];
                $contact_email = trim(strip_tags($this->request->data['Query']['email']));
                $phone = ($this->request->data['Query']['phone']) ? $this->request->data['Query']['phone'] : "";
                $subject = (!empty($this->request->data['Query']['subject'])) ? $this->request->data['Query']['subject'] : "";
                $message = $this->request->data['Query']['message'];
                if (!empty($this->request->data['Query']['organization_type_id'])) {
                    $organisation_type = $this->Custom->getOrganizationName($this->request->data['Query']['organization_type_id']);
                }


                $email = $this->EmailTemplate->selectTemplate('contact-us');
                $emailFindReplace = array(
                    '##NAME##' => $name,
                    '##SITE_NAME##' => Configure::read('Site.name'),
                    '##PHONE##' => $phone,
                    '##ORGANISATION##' => $organisation_type,
                    '##SUBJECT##' => $subject,
                    '##MESSAGE##' => $message,
                    '##FROM_EMAIL##' => $contact_email,
                    '##SITE_LOGO##' => Router::url(array('controller' => 'img', 'action' => '/', 'logo.png', 'admin' => false), true)
                );
                $mailTo= Configure::read('Site.admin_email');
                $this->AWSSES->from = $email['title'] . " <" . $email['from'] . ">";
                $this->AWSSES->to = $mailTo;
                $this->AWSSES->subject = strtr($email['subject'], $emailFindReplace);
                $this->AWSSES->replayto = $contact_email;
                $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);


                if ($this->AWSSES->_aws_ses()) {
                    $message = 'Your message has been sent successfully.';
                    $this->loadModel('Query');
                    $this->Query->save($this->request->data);
                } else { 
                    $isMailSent=$this->Custom->sendMailByServer($mailTo,strtr($email['subject'], $emailFindReplace),strtr($email['description'], $emailFindReplace));
                    if($isMailSent){
                        $this->Query->save($this->request->data);
                    }else{
                        throw new NotFoundException(__('Message not sent.'));   
                    }
                }
            } else {
                throw new NotFoundException(__('Invalid Method used.'));
            }
        } catch (Exception $exc) {
            $status = "failed";
            $message = $exc->getMessage();
        }
        echo json_encode(array("status" => $status, "message" => $message));
        die;
    }





    public function countemails()
    {
        $this->loadModel('Subscriber');
        $listId = $this->request->query['id'];
        $count = $this->Subscriber->find("count", array('conditions' => array("Subscriber.list" => $listId)));
        echo $count;
        die;
    }



    public function png_to_jpg($company_logo_path, $company_logo_path1, $quality)
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

    public function saveChartImage()
    {
        $fileBin = file_get_contents($this->data['img']);
        file_put_contents(WWW_ROOT . "chart_temp/" . $this->data['filename'] . '.png', $fileBin);
        $path_info = pathinfo(WWW_ROOT . "chart_temp/" . $this->data['filename'] . '.png');
        $this->png_to_jpg(WWW_ROOT . "chart_temp/" . $this->data['filename'] . '.png', $path_info['dirname'] . '/' . $path_info['filename'] . '.jpg', 100);
        die;
    }

    
    public function trackpr()
    {
        $this->loadModel("ClickThroughClient");
        try {
            if ($this->request->is("post")&&!empty($this->data['link'])&&!empty($this->data['prId'])){
                $link=$this->data['link'];
                $type=$this->data['type'];
                if($type==1){
                    $site_name = $hostname =$link;
                }else{
                    $parse =parse_url($link);
                    $site_name=$hostname=$parse['host'];
                }
                $prId=base64_decode($this->data['prId']);
                /*
                $extractHostname = explode(".", $hostname);
                if (is_array($extractHostname)) {
                    $elementCount = count($extractHostname);
                    $site_name = $extractHostname[$elementCount - 2];
                } */
                // parse_url($url);
                $conditions = array(
                    'ClickThroughClient.press_release_id' => $prId, 
                    'ClickThroughClient.domain' => $hostname,
                    'ClickThroughClient.clicked_url LIKE' => '%' . trim($link) . '%'
                ); 
                
                    $check = $this->ClickThroughClient->find('first', array('conditions' => $conditions, "fields" => array("ClickThroughClient.id", "ClickThroughClient.clicked")));
                    if (!empty($check['ClickThroughClient'])) {
                        $data['ClickThroughClient']['clicked'] = $check['ClickThroughClient']['clicked'] + 1;
                        $data['ClickThroughClient']['id'] = $check['ClickThroughClient']['id']; 
                        $data['ClickThroughClient']['type'] = $type; 
                    } else { 
                        $data['ClickThroughClient']['press_release_id'] = $prId;
                        $data['ClickThroughClient']['domain'] = $hostname;
                        $data['ClickThroughClient']['site_name'] = ucfirst($site_name);
                        $data['ClickThroughClient']['clicked_url'] = $link;
                        $data['ClickThroughClient']['clicked'] = 1; 
                        $data['ClickThroughClient']['type'] = $type; 
                    } 
                    $this->ClickThroughClient->save($data);
            }
        } catch (Exception $exc) { 
            $message = $exc->getMessage(); 
            CakeLog::write('warning', 'ERROR-:'.$message,'clickedthrough');
        } 
        echo "svd";
        $this->autoRender = false;
    }


    public function removeNewsroomExtraDocument()
    {
        
        try {
            if (!empty($this->request->data["imgId"])) { 
                $data=$this->CompanyDocument->find('first',['conditions'=>['CompanyDocument.id'=>trim($this->request->data["imgId"])]]);
                if(!empty($data)){
                    $del_file_path=WWW_ROOT.'files'.DS.'company'.DS.'docfile'.DS.$data['CompanyDocument']["file_path"].DS.$data['CompanyDocument']["file_name"];
                    $this->CompanyDocument->id = $data['CompanyDocument']["id"];
                    if ($this->CompanyDocument->exists()) {
                        $this->CompanyDocument->delete();
                        $delfile1 = new File($del_file_path, false, 0777);
                        if ($delfile1->delete()) {
                            if (isset($this->request->data["imgId"]) && !empty($this->request->data["imgId"])) {
                                
                            }
                            $json = json_encode(array("status" => "success", "message" => "success"));
                        } else {
                            throw new Exception("Document not found. Please try again");
                        }
                    }
                    
                }else{
                    throw new Exception("Document not found. Please try again");
                }
            } else {
                throw new Exception("Image not uploaded. Please try again");
            }
        } catch (Exception $exc) {
            $status = "failed";
            $message = $exc->getMessage();
            $json = json_encode(array("status" => "failed", "message" => $message));
        }
        echo $json;
        die;
    }


    public function search_user_by_email()
    {
        $data=[];
        if (!empty($this->data['email_user'])) {
            $condition[] = array("StaffUser.email ='" . $this->data['email_user'] . "'");
            $condition[] =['StaffUser.status !='=>3];
            $this->StaffUser->recursive=-1;
            $user = $this->StaffUser->find('first', array('conditions' => $condition,"order"=>"id DESC"));
            if(empty($user)) {
                $status = "true";
                $message = "This email is avilable.";
            } else {
                $status = 'false';
                $message = "This email is already exist. Do you want to create newsroom for this user.";
                $data=$user['StaffUser'];
            }
        } else {
            $status = "empty";
            $message = "<span class='text-danger'>Please enter email.</span>";
        }
        echo json_encode(array("status" => $status, "message" => $message,"data"=>$data));
        $this->autoRender = false;
    }


}
