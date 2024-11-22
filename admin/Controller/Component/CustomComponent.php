<?php

/**
 * Default Component
 *
 * PHP version 5
  This component consists the website default functions
 */
App::uses('AuthComponent', 'Controller/Component');
App::uses('AWSSESComponent', 'Controller/Component');
App::uses('Inflector', 'Utility');
class CustomComponent extends Component
{

    protected $_controller = null;
    public $components = array('AWSSES');
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

    function seoUrl($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

    function get_center()
    {
        return ClassRegistry::init('Center')->find('list', array('conditions' => array('Center.status' => 1)));
    }

    function get_class()
    {
        return ClassRegistry::init('ClassManager')->find('list', array('conditions' => array('ClassManager.status' => 1)));
    }
    public function document_upload($filename, $tmpname, $path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $a = move_uploaded_file($tmpname, $path . "/" . $filename);
        //echo "</br>temp : ".$tmpname."</br>path : ".$path."</br>filename : ".$filename."</br>";
        //var_dump($a); die;
    }



    public function compress_resize_image($src, $dest, $is_resize = false, $quality = 50, $thum_size = 2048)
    {
        $img = new Imagick();
        $img->readImage($src);
        $img->setImageFormat('jpg');
        $img->setImageCompression(imagick::COMPRESSION_JPEG);
        $img->setImageCompressionQuality($quality);
        $img->stripImage();
        if ($is_resize)
            $img->thumbnailImage($thum_size, null);

        $ext = pathinfo($dest, PATHINFO_EXTENSION);
        if (!in_array($ext, array('jpg', 'jpeg', 'JPG', 'JPEG'))) {
            $dest = str_replace($ext, 'jpg', $dest);
        }
        $img->writeImage($dest);
    }

    public function changeStatus()
    {
    }


    public function sendapproval($user_id, $message = '')
    {
        $site_name = strip_tags(Configure::read('Site.name'));
        $obj = ClassRegistry::init("StaffUser");
        $obj->recursive = -1;
        $data = $obj->read(array('StaffUser.name', "StaffUser.email"), $user_id);
        if (!empty($data['StaffUser']['email'])) {
            $email = $this->get_email_template('newsroom-action');
            $subject = $email['subject'];
            $title = $email['title'];
            $emailFindReplace = array(
                '##USER_NAME##' => $data['StaffUser']['name'],
                "##SUBJECT##" => $subject,
                "##MESSAGE##" => $message,
                '##SITE_NAME##' => $site_name,
            );
            $newTitle = (!empty($title)) ? $title : $subject;
            $this->AWSSES->from = $newTitle . " <" . $email['from'] . ">";
            $this->AWSSES->to = trim($data['StaffUser']['email']);
            $this->AWSSES->subject = $site_name . " : " . strtr($email['subject'], $emailFindReplace);
            $this->AWSSES->replayto = trim($email['reply_to_email']);
            $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

            App::uses('CakeEmail', 'Network/Email');
            $Email = new CakeEmail('default');
            $Email->from(array($email['from'] => $email['title']));
            $Email->to(trim($data['StaffUser']['email']));
            $Email->replyTo($email['reply_to_email']);
            $Email->subject($email['subject']);
            $Email->emailFormat('html');
            $Email->viewVars(array('site_name' => $site_name, 'name' => $data['StaffUser']['name'], 'message' => $message));
            $Email->template('disapprovalmail');
            try {
                if (!$this->AWSSES->_aws_ses()) {
                    $Email->send();
                }
                return true;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
        return true;
    }

    public function get_email_template($slug)
    {
        $data = ClassRegistry::init('EmailTemplate')->find('first', array('conditions' => array('EmailTemplate.alias' => $slug)));
        return $data['EmailTemplate'];
    }


    public function sendmailonaction($name, $emailTo, $message = '', $subject = '', $premsg = '')
    {
        $site_name = strip_tags(Configure::read('Site.name'));
        if (!empty($emailTo)) {
            $email = $this->get_email_template('newsroom-action');

            $emailFindReplace = array(
                '##USER_NAME##' => $name,
                "##SUBJECT##" => $subject,
                "##MESSAGE##" => "<p>$premsg</p> <p>$message</p>",
                '##SITE_NAME##' => $site_name,
            );
            $newTitle = (!empty($title)) ? $title : $subject;
            $this->AWSSES->from = $newTitle . " <" . $email['from'] . ">";
            $this->AWSSES->to = trim($emailTo);
            $this->AWSSES->subject = $site_name . " : " . strtr($email['subject'], $emailFindReplace);
            $this->AWSSES->replayto = trim($email['reply_to_email']);
            $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

            $email = $this->get_email_template('newsroom-action');
            App::uses('CakeEmail', 'Network/Email');
            $Email = new CakeEmail('default');
            $Email->from(array($email['from'] => $email['title']));
            $Email->to(trim($emailTo));
            $Email->replyTo($email['reply_to_email']);
            $Email->subject($subject);
            $Email->emailFormat('html');
            $Email->viewVars(array('site_name' => $site_name, 'name' => $name, 'message' => $message, 'premessage' => $premsg));
            $Email->template('sendmailonaction');
            try {
                if (!$this->AWSSES->_aws_ses()) {
                    $Email->send();
                }
                return true;
            } catch (Exception $exc) {
                // echo $exc->getTraceAsString();
            }
        }
        return true;
    }

    public function sendPrMailOnAction($name, $emailTo, $message = '', $subject = '', $premsg = '')
    {
        $site_name = strip_tags(Configure::read('Site.name'));
        if (!empty($emailTo)) {
            $email = $this->get_email_template('pressrelease-action');
            $emailFindReplace = array(
                '##USER_NAME##' => $name,
                "##SUBJECT##" => $subject,
                "##MESSAGE##" => "<p>$premsg</p> <p>$message</p>",
                '##SITE_NAME##' => $site_name,
            );
            $newTitle = (!empty($title)) ? $title : $subject;
            $this->AWSSES->from = $newTitle . " <" . $email['from'] . ">";
            $this->AWSSES->to = trim($emailTo);
            $this->AWSSES->subject = $site_name . " : " . strtr($email['subject'], $emailFindReplace);
            $this->AWSSES->replayto = trim($email['reply_to_email']);
            $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

            App::uses('CakeEmail', 'Network/Email');
            $Email = new CakeEmail('default');
            $Email->from(array($email['from'] => $email['title']));
            $Email->to(trim($emailTo));
            $Email->replyTo($email['reply_to_email']);
            $Email->subject($subject);
            $Email->emailFormat('html');
            $Email->viewVars(array('site_name' => $site_name, 'name' => $name, 'message' => $message, 'premessage' => $premsg));
            $Email->template('sendmailonaction');
            $email = $this->get_email_template('comman-email-template');


            try {
                if (!$this->AWSSES->_aws_ses()) {
                    $Email->send();
                }
                return true;
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }
        }
        return true;
    }


    public function sendMailOnActionToAdmin($message = '', $name = 'Admin', $subject = 'Information', $title = '')
    {

        //$emailTo=strip_tags(Configure::read('Site.admin_email'));
        //$emailfrom=strip_tags(Configure::read('EmailTemplate.from_email')); 
        //App::uses('CakeEmail', 'Network/Email');
        $email = $this->get_email_template('comman-email-template');
        $site_name = strip_tags(Configure::read('Site.name'));

        $emailFindReplace = array(
            '##USER_NAME##' => $name,
            "##SUBJECT##" => $subject,
            "##MESSAGE##" => $message,
            '##SITE_NAME##' => $site_name,
        );
        $newTitle = (!empty($title)) ? $title : $subject;
        $this->AWSSES->from = $newTitle . " <" . $email['from'] . ">";
        $this->AWSSES->to = trim($emailTo);
        $this->AWSSES->subject = $site_name . " : " . strtr($email['subject'], $emailFindReplace);
        $this->AWSSES->replayto = trim($email['reply_to_email']);
        $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

        $Email = new CakeEmail('default');
        $Email->from(array($email['from'] => $newTitle));
        $Email->to(trim($emailTo));
        $Email->replyTo($email['reply_to_email']);
        $Email->subject($site_name . " : " . strtr($email['subject'], $emailFindReplace));
        $Email->emailFormat('html');
        $Email->viewVars(array('site_name' => $site_name, 'name' => $name, 'message' => $message));
        $Email->template('sendmailontoadmin');

        try {
            if (!$this->AWSSES->_aws_ses()) {
                $Email->send();
            }
            return true;
        } catch (Exception $exc) {
            // echo $exc->getTraceAsString();
        }
        return true;
    }

    public function sendemail($emailTo = "", $message = '', $name = 'User', $subject = '', $title = '', $attachment = "")
    {
        $site_name = strip_tags(Configure::read('Site.name'));
        $email = $this->get_email_template('comman-email-template');
        $subject = (!empty($subject)) ? $subject : strip_tags(Configure::read('Site.name')) . " : " . strtr($email['subject'], $emailFindReplace);

        $emailFindReplace = array(
            '##USER_NAME##' => $name,
            "##SUBJECT##" => $subject,
            "##MESSAGE##" => $message,
            '##SITE_NAME##' => strip_tags(Configure::read('Site.name')),
        );

      
        $newTitle = (!empty($title)) ? $title : $subject;
        App::uses('CakeEmail', 'Network/Email');
        $Email = new CakeEmail('default');
        $Email->from(array($email['from'] => $newTitle));
        $Email->to(trim($emailTo));
        $Email->replyTo($email['from']);
        $Email->subject($subject);
        $Email->emailFormat('html');
        $Email->viewVars(array('site_name' => $site_name, 'name' => $name, 'message' => $message));
        $bodyHtml = $Email->template('sendemail'); 
        if (!empty($attachment)) {
            $Email->attachments($attachment);
        } else { 
            $this->AWSSES->from = $newTitle . " <" . $email['from'] . ">";
            $this->AWSSES->to = $emailTo;
            $this->AWSSES->subject = $subject;
            $this->AWSSES->replayto = trim($email['reply_to_email']);
            $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);
        }
        
        try {
            if (!empty($attachment)) {
                $Email->send();    
            } else if (!$this->AWSSES->_aws_ses()) {
                $Email->send();
            }
        } catch (Exception $exc) {
            //  $exc->getTraceAsString();
        }
        
        return true;
    }


    public function sendInvoiceMail($emailTo = "", $message = '', $name = 'User', $subject = 'Information', $title = '', $attachment = "")
    {
        try {
            $emailfrom = strip_tags(Configure::read('EmailTemplate.from_email'));
            $site_name = strip_tags(Configure::read('Site.name'));
            App::uses('CakeEmail', 'Network/Email');
            $Email = new CakeEmail('default');
            if (!empty($attachment)) {
                $Email->attachments($attachment);
            }
            $emailFindReplace = array(
                '##NAME##' => $name,
                '##SITE_NAME##' => $site_name,
            );
            $this->AWSSES->from = $title . " <" . $emailfrom . ">";
            $this->AWSSES->to = trim($emailTo);
            $this->AWSSES->subject = $subject;
            $this->AWSSES->replayto = trim($emailfrom);
            $this->AWSSES->htmlMessage = strtr($message, $emailFindReplace);

            if (!$this->AWSSES->_aws_ses()) {
                App::uses('CakeEmail', 'Network/Email');
                $currency = Configure::read('Site.currency');
                $Email = new CakeEmail('default');
                $Email->from(array("$emailfrom" => "$title"));
                $Email->to(trim($emailTo));
                $Email->replyTo(trim($emailfrom));
                $Email->subject($subject);
                $Email->emailFormat('html');
                $Email->viewVars(array('site_name' => $site_name, 'name' => $name, 'message' => $message));
                $Email->template('sendinvoicemail');
                $Email->send();
                $Email->send($message);
                return true;
            }
        } catch (Exception $exc) {
            // echo $exc->getTraceAsString();
        }
        return true;
    }

    public function getprcartdata($user_id = '', $plan_id = '', $pressrelease_id = '')
    {
        $Cart = ClassRegistry::init('Cart');
        $obj = ClassRegistry::init('Plan');
        $index = "0";
        $cart_plans['feature'] = $cart_plans['prlist'] = $cart_plans = [];
        $cart_plans['totals']['subtotal'] = $cart_plans['totals']['discount'] = $cart_plans['totals']['tax'] = $cart_plans['totals']['total'] = $famount = $tax = $discount = $plan_amount = '0.00';
        $currency = Configure::read('Site.currency');

        $conditions = array('Cart.staff_user_id' => $user_id, 'plan_id' => $plan_id, 'cart_type' => 'pr');
        if ($pressrelease_id != "") {
            $conditions = array('Cart.staff_user_id' => $user_id, 'plan_id' => $plan_id, 'cart_type' => 'pr', 'press_release_id' => $pressrelease_id);
        }
        $checkcart = $Cart->find('first', array('conditions' => $conditions));

        if ($checkcart) {
            $plan = $obj->find('first', array('conditions' => array('Plan.id' => $plan_id)));
            if ($checkcart['Cart']['extra_words'] > 0) {
                $amt = ceil(($checkcart['Cart']['extra_words'] / 100)) * $plan['Plan']['add_word_amount'];
                $amount = number_format($amt, 2);
                $cart_plans['prlist'][$index]["plan_id"] = $plan_id;
                $cart_plans['prlist'][$index]["title"] = "Additional charges words";
                $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amount;
                $plan_amount += $amount;
                $index++;
            }

            if ($checkcart['Cart']['extra_category'] > 0) {
                $amt = ($checkcart['Cart']['extra_category']) * ($plan['Plan']['add_word_amount']);
                $amount = number_format($amt, 2);
                $cart_plans['prlist'][$index]["plan_id"] = $plan_id;
                $cart_plans['prlist'][$index]["title"] = "Additional charges words";
                $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amount;
                $plan_amount += $amt;
                $index++;
            }

            if ($checkcart['Cart']['extra_msa'] > 0) {
                $amtmsa = ceil($checkcart['Cart']['extra_msa'] / $plan['Plan']['msa_limit']) * ($plan['Plan']['add_msa_charges']);
                $amountmsa = number_format($amtmsa, 2);
                $cart_plans['prlist'][$index]["plan_id"] = $plan_id;
                $cart_plans['prlist'][$index]["title"] = "Additional msa charges";
                $cart_plans['prlist'][$index]["amount"] = $currency . '' . $amountmsa;
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

            if (!empty($checkcart['Cart']['distribution_ids'])) {
                $features = unserialize($checkcart['Cart']['distribution_ids']);
                foreach ($features as $index => $value) {
                    $feature[$index]['distribution_id'] = $value['distribution_id'];
                    $featureData = $this->getprfeatureprice($value['distribution_id']);
                    $cart_plans['feature'][$index]['price'] = $featureData['amount'];
                    $cart_plans['feature'][$index]['name'] = $featureData['name'];
                    $cart_plans['feature'][$index]['class'] = 'feature-' . $value['distribution_id'];
                    $famount = $famount + $featureData['amount'];
                }
            }

            $plan_amount += $famount;
            $cart_plans['totals']['subtotal'] = number_format($plan_amount, 2);
            $cart_plans['totals']['discount'] = number_format($discount, 2);
            $cart_plans['totals']['tax'] = $tax;
            $cart_plans['totals']['total'] = $this->get_cart_total('0', $plan_amount, $discount);
        }
        return $cart_plans;
    }

    function getprfeatureprice($featureId = "")
    {
        if (!empty($featureId)) {
            $obj = ClassRegistry::init('Distribution');
            $data = $obj->find('first', array('conditions' => array('id' => $featureId), 'fields' => array('name', 'amount')));
            return $data['Distribution'];
        }
    }

    public function get_cart_total($newsroomAmount = '0.00', $subtotal, $discount = "0.00", $tax = "0.00")
    {
        if ($tax > 0)
            $tax = $this->taxcalculation($tax);
        $price = round(((($newsroomAmount + $subtotal) - $discount) + $tax), 2);
        return ($price > 0) ? number_format($price, 2) : "0.00";
    }


    public function getUserCartData($user_id, $coupon_data = '', $newsroom_amount = '0.00', $plan_details = '')
    {
        $cartData = [];
        $cartData['plans'] = [];
        $cartData["newsroom_amount"] = $newsroom_amount;
        $total = $subtotal = $discount = "0.00";
        $data_array = $this->fetchCartData($user_id);
        if (!empty($data_array)) {
            foreach ($data_array as $index => $sess_data) {
                if ($sess_data["is_newsroom_incart"] > 0) {
                    $cartData["newsroom_amount"] = ($sess_data["is_newsroom_incart"] > 0) ? Configure::read('Site.newsroom.amount') : $newsroom_amount;
                } else {
                    $amount = ($sess_data['bulk_discount_amount'] > 0) ? $sess_data['bulk_discount_amount'] : $sess_data['price'];

                    if (empty($plan_details))
                        $plan_details = $this->fetchPlanData($sess_data['plan_id']);

                    if (isset($plan_details) && !empty($plan_details)) {
                        $cartData['plans'][$index]["plan_type"]   =   $plan_details['Plan']['plan_type'];
                    } else {
                        $cartData['plans'][$index]["plan_type"]   =   '';
                    }
                    $cartData['plans'][$index]["plan_id"] = $sess_data['plan_id'];
                    $cartData['plans'][$index]["title"] = $sess_data['name'];
                    $cartData['plans'][$index]["amount"] = $amount;
                    $subtotal += $amount;
                }
            }
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

    public function fetchCartData($user_id)
    {
        $obj = ClassRegistry::init('CartDetail');
        $data = $obj->find('all', array('conditions' => array('cart_type' => 'plan', 'staff_user_id' => $user_id)));
        $data = Set::extract('/CartDetail/.', $data);
        return (!empty($data)) ? $data : [];
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

    public function getLinksMailList($cId = '')
    {
        $result = "";
        if (!empty($cId)) {
            $result = ClassRegistry::init('Link')->find("list", array('conditions' => ['campaign_id' => $cId], 'fields' => array('link', 'clicks')));
        }
        return $result;
    }
    public function getClippingReportHtml($data_array = '', $prdata = '')
    {
        $prRow = "";
        if (!empty($prdata)) {

            $obj = ClassRegistry::init('Company');
            $conditions = array("Company.id" => $prdata['company_id']);
            $company_data = $obj->find('first', array('conditions' => $conditions));
            $site_name = strip_tags(Configure::read('Site.name'));
            $dateformate = "m-d-Y";
            $plan_obj = ClassRegistry::init('Plan');
            $plan_data = $plan_obj->find('first', array('conditions' => array('Plan.id' => $prdata['plan_id'])));
            $plan_name = $plan_data['PlanCategory']['name'];
            $pr_title = $prdata['title'];
            $pdf_obj = ClassRegistry::init('PdfSetting');
            $pdf_data = $pdf_obj->find('first', array('conditions' => array('PdfSetting.id' => '1')));
            $pdfFindReplace = array('##PLAN_NAME##' => $plan_name);

            $pdf_title                  =   $pdf_data['PdfSetting']['title'];
            $pdf_logo                   =   $pdf_data['PdfSetting']['logo'];
            $pdf_welcome_text           =   strtr($pdf_data['PdfSetting']['welcome_text'], $pdfFindReplace);
            $pdf_network_description    =   $pdf_data['PdfSetting']['network_description'];
            $pdf_email_description      =   $pdf_data['PdfSetting']['email_distribution_description'];
            $pdf_footer      =   $pdf_data['PdfSetting']['footer_text'];

            $pdf_logo_path = ROOT . DS.'app'.DS.'webroot'.DS .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
            $pdf_logo=$this->convertImageIntoBase64($pdf_logo_path); 

            $company_name = $company_data['Company']['name'];

            $company_logo = SITEFRONTURL . "files/company/logo/" . $company_data['Company']['logo_path'] . "/" . $company_data['Company']['logo'];

            
            $prRow = " <table style='margin:0 0 5px 0; padding-top: 60px; border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5'>
                    <tr>
                        <th align='center' style='padding-bottom: 5px;'>Site name</th>
                        <th align='center' style='padding-bottom: 5px;'>Views</th>
                        <th align='center' style='padding-bottom: 5px;'>Release date</th> 
                    </tr>
                    <tr><td style='text-align:left;padding-bottom: 5px;'>$site_name</td> <td style='text-align:center;padding-bottom: 5px;'>" . $prdata['views'] . "</td><td style='text-align:left;padding-bottom: 5px;'>" . date($dateformate, strtotime($prdata['release_date'])) . "</td></tr>
                    </table>";
        }
        $countryReport = $links = $spamMailList = $unsubscribedMailList = $newsletterMailList = $sentMailReportdata = $bouncedMailList = "";
        $champ = $this->getCampaignDetails($prdata['id']);
        $newsletterMailList = $this->getNewsletterOpenMailList($prdata['id']);
        $newsletterSentMailList = $this->getNewsletterSentMailList($prdata['id']);

        if (!empty($champ)) {
            $sentMailReportdata = $this->getSentMailReport($champ['opens']);
            $countryReport = $this->getSendyCountryReport($champ['opens']);
            $unsubscribedMailList = $this->getSendyUnsubscribedMailList($champ['id']);
            $bouncedMailList = $this->getSendyBouncedMailList($champ['id']);
            $spamMailList = $this->getSendySpamMailList($champ['id']);
            $links = $this->getLinksMailList($champ['id']);
        }
        $clippingRow = "<table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                    <tr>
                        <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                        <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Site name</th>
                        <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Published url</th> 
                        <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                        <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Views</th>
                      
                        <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                    </tr>";
        foreach ($data_array as $index => $data) {
            if (isset($data['ClippingReport'])) {
                if ($data["ClippingReport"]['distribution_type'] == "network_feed") {
                    $release_page_url = "<a style='text-decoration:none;color:black' target='_blank' href=" . $data["ClippingReport"]['release_page_url'] . ">" . $data["ClippingReport"]['release_page_url'] . "</a>";
                    $clippingRow .= "<tr style='padding-top:3px'><td>" . ($index + 1) . "</td><td>" . $data["ClippingReport"]['site_name'] . "</td><td>$release_page_url</td><td align='center'>" . str_replace("_feed", " ", ucfirst($data["ClippingReport"]['distribution_type'])) . "</td> <td align='center'>" . $data["ClippingReport"]['views'] . "</td><td align='left'>" . date($dateformate, strtotime($data["ClippingReport"]['created'])) . "</td></tr>";
                }
            }
        }
        $clippingRow   .= "</table> ";


        $clippingRow .= "<div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                    Rss media distribution
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Name</th> 
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Published url</th> 
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Views</th>
                  
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        foreach ($data_array as $index => $rssmedia) {
            if (isset($rssmedia['ClippingReport'])) {
                if ($rssmedia["ClippingReport"]['distribution_type'] == "rss_feed") {
                    $release_page_url = "<a style='text-decoration:none;color:black' target='_blank' href=" . $rssmedia["ClippingReport"]['release_page_url'] . ">" . $rssmedia["ClippingReport"]['release_page_url'] . "</a>";
                    $clippingRow .= "<tr style='padding-top:3px'><td>" . ($index + 1) . "</td><td>" . $rssmedia["ClippingReport"]['site_name'] . "</td><td>$release_page_url</td><td align='center'>" . str_replace("_feed", " ", ucfirst($rssmedia["ClippingReport"]['distribution_type'])) . "</td> <td align='center'>" . $rssmedia["ClippingReport"]['views'] . "</td><td align='left'>" . date($dateformate, strtotime($rssmedia["ClippingReport"]['created'])) . "</td></tr>";
                }
            }
        }
        $clippingRow   .= "</table> ";

        $clippingRow .= "<div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                    Js media distribution
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Name</th> 
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Published url</th> 
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Views</th>
                  
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        foreach ($data_array as $index => $jsmedia) {
            if (isset($jsmedia['ClippingReport'])) {
                if ($jsmedia["ClippingReport"]['distribution_type'] == "js_feed") {
                    $release_page_url = "<a style='text-decoration:none;color:black' target='_blank' href=" . $jsmedia["ClippingReport"]['release_page_url'] . ">" . $jsmedia["ClippingReport"]['release_page_url'] . "</a>";
                    $clippingRow .= "<tr style='padding-top:3px'><td>" . ($index + 1) . "</td><td>" . $jsmedia["ClippingReport"]['site_name'] . "</td><td>$release_page_url</td><td align='center'>" . str_replace("_feed", " ", ucfirst($jsmedia["ClippingReport"]['distribution_type'])) . "</td> <td align='center'>" . $jsmedia["ClippingReport"]['views'] . "</td><td align='left'>" . date($dateformate, strtotime($jsmedia["ClippingReport"]['created'])) . "</td></tr>";
                }
            }
        }
        $clippingRow   .= "</table> ";


        $clippingRow .= "<div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                     Social media distribution
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Name</th> 
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Published url</th> 
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Views</th>
                  
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        foreach ($data_array as $index => $data) {
            if (isset($data['ClippingReport'])) {
                if ($data["ClippingReport"]['distribution_type'] == "social_media_feed") {
                    $release_page_url = "<a style='text-decoration:none;color:black' target='_blank' href=" . $data["ClippingReport"]['release_page_url'] . ">" . $data["ClippingReport"]['release_page_url'] . "</a>";
                    $clippingRow .= "<tr style='padding-top:3px'><td>" . ($index + 1) . "</td><td>" . $data["ClippingReport"]['site_name'] . "</td><td>$release_page_url</td><td align='center'>Social media</td> <td align='center'>" . $data["ClippingReport"]['views'] . "</td><td align='left'>" . date($dateformate, strtotime($data["ClippingReport"]['created'])) . "</td></tr>";
                }
            }
        }
        $clippingRow   .= "</table> ";




        $clippingRow  .= "<div style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>
                <div class='col-lg-6'>
                    Email Distribution
                </div>
                
                </div>
                <div style='margin:0 10px 10px 10px;color: #4d4d4d;'>
                $pdf_email_description
                </div>";



        /*Newsleter  sent emails */
        $clippingRow .= "
                <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                     Subscribers who recevied press release
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Name</th> 
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Email</th> 
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th align='center' style='padding-right: 20px;padding-bottom: 2px;'>Views</th>
                  
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        if (!empty($newsletterSentMailList)) {
            $count = 0;
            foreach ($newsletterSentMailList as $key => $data) {
                $mail = $data['StaffUser']['email'];
                $email = $this->getEmailforClippingReport($mail);
                $emailPart = explode("@", $mail);
                $name = ucfirst($emailPart[0]);
                $clippingRow .= "<tr style='padding-top:3px'><td>" . ($count + 1) . "</td><td>" . $name . "</td><td>$email</td><td align='center'>Mail</td> <td align='center'>-</td><td align='left'>" . date($dateformate, strtotime($data['NewsletterLog']['created'])) . "</td></tr>";
                $count++;
            }
        } else {
            $clippingRow .= "<tr style='padding-top:3px' ><td align='center' colspan='6'>No result found.</td></tr>";
        }
        $clippingRow   .= "</table>";

        /*Newsleter open emails */
        $clippingRow .= "
                <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                    Subscribers who opened press release 
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Name</th> 
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Email</th> 
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>View</th>
                  
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        if ($newsletterMailList) {
            $count = 0;
            foreach ($newsletterMailList as $id => $data) {
                $mail = $data['ClippingReport']['email'];
                $view = $data['ClippingReport']['views'];
                $email = $this->getEmailforClippingReport($mail);
                $emailPart = explode("@", $mail);
                $name = ucfirst($emailPart[0]);
                $clippingRow .= "<tr style='padding-top:3px'><td>" . ($count + 1) . "</td><td>" . $name . "</td><td>$email</td><td align='center'>Mail</td> <td align='center'>$view</td><td align='left'>" . date($dateformate, strtotime($data['ClippingReport']['created'])) . "</td></tr>";
                $count++;
            }
        } else {
            $clippingRow .= "<tr style='padding-top:3px' ><td align='center' colspan='6'>No result found.</td></tr>";
        }
        $clippingRow   .= "</table>";

        /* Sendy clipping reports*/
        $clippingRow  .= "<div  style='margin:20 0px 20px 0px' class='col-lg-6'>
                   Client media list opened
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Name</th> 
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Email</th> 
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Views</th>
                  
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        if (!empty($sentMailReportdata)) {
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
                $clippingRow .= "<tr style='padding-top:3px'><td>" . ($count + 1) . "</td><td>" . $name . "</td><td>$email</td><td align='center'>Mail</td> <td align='center'>$views</td><td align='left'>" . date($dateformate, strtotime($champ['created'])) . "</td></tr>";
                $count++;
            }
        } else {
            $clippingRow .= "<tr style='padding-top:3px' ><td align='center' colspan='6'>No opens yet!.</td></tr>";
        }
        $clippingRow   .= "</table>";
        /*Links emails */
        $clippingRow .= "
                <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                    Links
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Link (URL)</th> 
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Unique</th> 
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Total</th> 
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        if ($links) {
            $count = 0;
            foreach ($links as $link => $clicks) {
                $unique_clicks = $total_clicks = "0";
                if (!empty($clicks)) {
                    $total_clicks_array = explode(',', $clicks);
                    $total_clicks = count($total_clicks_array);
                    $unique_clicks = count(array_unique($total_clicks_array));
                }

                $clippingRow .= "<tr style='padding-top:3px'><td>" . ($count + 1) . "</td><td>" . $link . "</td><td align='center'>$total_clicks</td><td align='center'>$unique_clicks</td><td align='center'>Mail</td><td align='left'>" . date($dateformate, strtotime($champ['created'])) . "</td></tr>";
                $count++;
            }
        } else {
            $clippingRow .= "<tr style='padding-top:3px' ><td align='center' colspan='6'>No links found.</td></tr>";
        }
        $clippingRow   .= "</table>";



        /*
       $clippingRow .="
                <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                    Unsubscribed list
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Name</th> 
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Email</th> 
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Status</th>
                  
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        if($unsubscribedMailList){
            $count=0;
            foreach ($unsubscribedMailList as $id => $mail){
                $email= $this->getEmailforClippingReport($mail);
                $emailPart=explode("@",$sentmail);
                $name=ucfirst($emailPart[0]); 
                $clippingRow .= "<tr style='padding-top:3px'><td>".($count+1)."</td><td>".$name."</td><td>$email</td><td>Mail</td> <td>Unsubscribed</td><td>".date($dateformate, strtotime($champ['created']))."</td></tr>";
                $count++;    
            }
        }else{
            $clippingRow .= "<tr style='padding-top:3px' ><td align='center' colspan='6'>No one unsubscribed.</td></tr>";
        }
        $clippingRow   .="</table>";

        $clippingRow .="
                <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                    Bounced email list
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Name</th> 
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Email</th> 
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Status</th>
                  
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        if($bouncedMailList){
            $count=0;
            foreach ($bouncedMailList as $id => $mail){
                $email= $this->getEmailforClippingReport($mail);
                $emailPart=explode("@",$sentmail);
                $name=ucfirst($emailPart[0]); 
                $clippingRow .= "<tr style='padding-top:3px'><td>".($count+1)."</td><td>".$name."</td><td>$email</td><td>Mail</td> <td>Bounced</td><td>".date($dateformate, strtotime($champ['created']))."</td></tr>";
                $count++;    
            }
        }else{
            $clippingRow .= "<tr style='padding-top:3px' ><td align='center' colspan='6'>No emails bounced.</td></tr>";
        }
        $clippingRow   .="</table>";

        $clippingRow .="
                <div style='margin:20 0px 20px 0px' class='col-lg-6'>
                    Spam email list
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Name</th> 
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Email</th> 
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Distribution type</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Status</th>
                  
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        if($spamMailList){
            $count=0;
            foreach ($spamMailList as $id => $mail){
                $email= $this->getEmailforClippingReport($mail);
                $emailPart=explode("@",$sentmail);
                $name=ucfirst($emailPart[0]); 
                $clippingRow .= "<tr style='padding-top:3px'><td>".($count+1)."</td><td>".$name."</td><td>$email</td><td>Mail</td> <td>Marked as spam</td><td>".date($dateformate, strtotime($champ['created']))."</td></tr>";
                $count++;    
            }
        }else{
            $clippingRow .= "<tr style='padding-top:3px' ><td align='center' colspan='6'>No emails spam.</td></tr>";
        }
        $clippingRow   .="</table>";  */

        /*Country Report emails */
        $clippingRow .= "
                <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
                    Client media list opened by country
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>S/N</th>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Country</th>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Views</th>
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution type</th> 
                    <th style='text-align:center;padding-right: 20px;padding-bottom: 2px;'>Distribution date</th> 
                </tr>";
        if ($countryReport) {
            $count = 0;
            $unique_countries = array_unique($countryReport);
            $counts = array_count_values($countryReport);
            foreach ($unique_countries as $index => $country) {
                $views = "0";
                $name = $this->getCountryNameByCountyCode($country);
                if (!empty($country)) {
                    $views = $counts[$country];
                }
                $clippingRow .= "<tr style='padding-top:3px'><td>" . ($count + 1) . "</td><td>" . $name . "</td><td align='center'>$views</td><td align='center'>Mail</td><td align='left'>" . date($dateformate, strtotime($champ['created'])) . "</td></tr>";
                $count++;
            }
        } else {
            $clippingRow .= "<tr style='padding-top:3px' ><td align='center' colspan='6'>No links found.</td></tr>";
        }
        $clippingRow   .= "</table>";

        $update_time    = date('F d Y', time());
        $replaceCompanyName = ["##COMPANYNAME##" => $company_name];
        $replaceFooterText = ["##PHONE##" => strip_tags(Configure::read('Site.phone')), "##YEAR##" => date('Y')];
        $html = "<style>.fontbold{font-weight: bold;} tr:nth-child(even) {background-color: #f2f2f2;}</style> 
            <div>

            <div style='color: #6a6a6a; font-size: 25px; font-weight: 600;font-family:sans-serif; padding-bottom: 5px; padding-top: 5px;float: left; width: 100%;text-align:center'>
                <h3><strong> " . strtr($pdf_title, $replaceCompanyName) . "</strong></h3>
            </div> 
                <div style='text-align:center'><br/><br/><br/><br/><br/></div>
   
                <div style='text-align:center'>
                <br/>
                <br/>
                <img src='" . $company_logo . "'> 
                </div>
                <br/>
                <br/>

                <div style='text-align:center'><h3>$pr_title</h3></div>
                  <br/>
                <div style='text-align:center'>Press Release Distribution by $site_name</div>
                <br/>
                <div align='center'>
                <br/><br/>
                <img eidth='100%' src='" .$pdf_logo . "'> 
                </div>
                <br/>
                <br/>
                <div style='text-align:center'>EmailWireClip&trade; Clipping Report</div>
                <br/>
                <br/>
                <div style='text-align:center'> Updated " . $update_time . " </div>
                <br/>

                <br/>
                <div style='text-align:center; margin:0 10px 0 10px'>&nbsp;$pdf_welcome_text</div>
                <div style='text-align:center'><br/></div>
                <div style='margin-bottom:50px'>&nbsp;</div>
                <div style='margin:5px 0 0 0 ;color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8; margin-bottom: 15px; padding-bottom: 5px; padding-top: 5px;float: left; width: 100%;' >$site_name</div> $prRow<br/> <br/> 
                <br/>
                <div  style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>
                    <div class='col-lg-6'>
                        $site_name Network  
                    </div>
                   
               </div> 
                <div style='margin:0 10px 10px 10px;color: #4d4d4d;'>
                $pdf_network_description
                </div>
                $clippingRow
                <div style='font-size:14px; color: #4d4d4d; text-align: center; margin-top:50px;'> " . strtr($pdf_footer, $replaceFooterText) . "</div> 
                 <div style='text-align:center'><br/><br/></div>
      
                <div style='text-align:center'><br/></div>
            </div><style>td,th{line-height: 30px;}@media print {.pagebreak { page-break-after: always!important;} }  
                    
                  table {
                        font-size:10px;
                        font-family:sans-serif;
                        border-left: 0.01em solid #ccc;
                        border-right: 0;
                        border-top: 0.01em solid #ccc;
                        border-bottom: 0;
                        border-collapse: collapse;
                    }
                    table td,
                    table th {
                        border-left: 0;
                        border-right: 0.01em solid #ccc;
                        border-top: 0;
                        border-bottom: 0.01em solid #ccc;
                        
                    }
              body { font-family: sans-serif !important; border: 0px solid #666666; }</style>";
        // echo $html; die;
        return $html;
    }

    public function send_invoice_mail($data = '', $email, $uName, $mailTo)
    {
        App::uses('CakeEmail', 'Network/Email');
        $currency = Configure::read('Site.currency');
        $Email = new CakeEmail('default');
        $Email->from(array($email['from'] => "Invoice Email wire"));
        $Email->to(trim($mailTo));
        $Email->replyTo($email['reply_to_email']);
        $Email->subject($email['subject']);
        $Email->emailFormat('html');
        $Email->viewVars(array('data' => $data, 'name' => $uName, 'currency' => $currency));
        $Email->template('payment_invoice');
        try {
            $Email->send();
            return true;
        } catch (Exception $exc) {
            // echo $exc->getTraceAsString();
        }
        return true;
    }


    public function send_mailOnNewsroomCreation($uName, $mailTo)
    {
        $site_name = strip_tags(Configure::read('Site.name'));
        $email = $this->get_email_template('newsroom-under-review');

        $emailFindReplace = array(
            '##NAME##' => $uName,
            '##SITE_NAME##' => $site_name,
        );
        $this->AWSSES->from = $email['title'] . " <" . $email['from'] . ">";
        $this->AWSSES->to = trim($mailTo);
        $this->AWSSES->subject = $email['subject'];
        $this->AWSSES->replayto = trim($email['reply_to_email']);
        $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);
        try {
            if (!$this->AWSSES->_aws_ses()) {
                App::uses('CakeEmail', 'Network/Email');
                $currency = Configure::read('Site.currency');
                $Email = new CakeEmail('default');
                $Email->from(array($email['from'] => "Invoice Email wire"));
                $Email->to(trim($mailTo));
                $Email->replyTo($email['reply_to_email']);
                $Email->subject($email['subject']);
                $Email->emailFormat('html');
                $Email->viewVars(array('data' => $data, 'name' => $uName, 'currency' => $currency));
                $Email->template('newsroom-under-review');
                $Email->send();
            }
            return true;
        } catch (Exception $exc) {
            // echo $exc->getTraceAsString();
        }
        return true;
    }

    public function getCampaignDetails($prId)
    {
        $obj = ClassRegistry::init('Campaign');
        $check = $obj->find('first', array("fields" => array("id", "sent", "recipients", "to_send", "timezone", "send_date", 'createdfrom', 'userID', 'created', 'opens'), 'conditions' => array("Campaign.press_release_id" => $prId)));
        return (!empty($check)) ? $check['Campaign'] : [];
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
    public function get_newsletter_sendmail_date($id = '')
    {
        $obj = ClassRegistry::init('StaffUser');
        $check = $obj->find('first', array("fields" => array("newsletter_send_mail_date", "newsletter_cycle"), 'conditions' => array("StaffUser.id" => $id)));
        $sendmaildate = (!empty($check['StaffUser']['newsletter_send_mail_date'])) ? $check['StaffUser']['newsletter_send_mail_date'] : "";
        $today = strtotime(date('Y-m-d'));
        $savedDate = strtotime($check['StaffUser']['newsletter_send_mail_date']);
        if (empty($sendmaildate) || ($savedDate < $today)) {
            $sendmaildate = $this->getdatebynewslettercycle($check['StaffUser']['newsletter_cycle']);
            $saveData['StaffUser']['id'] = $id;
            $saveData['StaffUser']['newsletter_send_mail_date'] = $sendmaildate;
            $obj->save();
        }
        return $sendmaildate;
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

    public function fetchPlanData($plan_id)
    {
        $obj = ClassRegistry::init('Plan');
        $data = $obj->find('first', array('conditions' => array('Plan.id' => $plan_id)));
        return $data;
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
                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>$name</td>
                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>
                    </tr>";
            }
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
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>S.No.</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Transaction id</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Transaction Type</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Amount</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Date</th>
                </tr>
            </thead>";
                foreach ($recurringData as $loop => $recurringPayment) {
                    $total = (!empty($recurringPayment['Transaction']['total'])) ? $recurringPayment['Transaction']['total'] : "0.00";
                    $tx_id = (!empty($recurringPayment['Transaction']['tx_id'])) ? $recurringPayment['Transaction']['tx_id'] : "-";
                    $txnType = $this->getTransactionType($recurringPayment['Transaction']['txn_type']);
                    $date  = date('d F Y', strtotime($recurringPayment['Transaction']['paymant_date']));
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

        $pdf_logo_path = ROOT . DS.'app'.DS.'webroot'.DS .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
        $pdf_logo=$this->convertImageIntoBase64($pdf_logo_path); 

        $notesFindReplace = array('##PLAN##' => $name, '##PAGEURL##' => "<br/>" . SITEFRONTURL . 'plans/' . $plan_url);
        $notes = strtr($pdf_data['PdfSetting']['email_distribution_description'], $notesFindReplace);

        $company = (isset($data['Company']) && !empty($data['Company']['name'])) ? $data['Company']['name'] : "";
        if (empty($company) && isset($data['StaffUser']['Company'][0]['name'])) {
            $company = $data['StaffUser']['Company'][0]['name'];
        }
        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>
               <style>body, html { background: #ffffff; color: #555555; font-family: sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }
               table {margin:0; padding: 0;}
               </style>            
               </head>
                <body style='max-width:650px; margin:auto;'>
                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                  <tr>
                    <td width='300'><img style='width: 160px;margin: 30px;' src='" .$pdf_logo . "' alt='logo'></td>
                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>
                  </tr>
                  <tr>
                    <td valign='top' rowspan='3' style='padding-left: 20px;'>" . Configure::read('Site.address') . "</td>
                    <td width='120' align='right' style='padding-right: 8px;'>Invoice #:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . $data['Transaction']['tx_id'] . "</td>
                  </tr>
                  <tr>
                    <td width='120' align='right' style='padding-right: 8px;'>Invoice date:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . date('M d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                  </tr>
                  <tr>
                    <td width='120' align='right' style='padding-right: 8px;'>Payment Date:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . date("M d,Y", strtotime($data['Transaction']['paymant_date'])) . "</td>
                  </tr>
                  <tr>
                  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>
                            " . strip_tags(Configure::read('Site.email')) . "
                            " . SITEFRONTURL . "</td>
                    <td align='center' colspan='2'>
                        <table width='100' align='center' style='margin-top: 30px; margin-left: auto; margin-right: auto; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>
                            <tr>
                                <td align='center' style='text-align: center; '>
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
                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; margin: auto; border-right: 1px solid #e6e6e6; padding: 20px 0; background: #ffffff;border-collapse:collapse'>
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
                    <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                        <tbody>
                        <tr>
                            <td style='vertical-align: top; padding: 20px;'><strong>Notes: </strong>$notes 
                            <td style='vertical-align: top; padding: 20px;'><strong>Terms and Conditions</strong>$tandc</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='vertical-align: top; padding: 20px;'><strong>Notes: </strong>Plan invoice pdf attached.</td>
                        </tr
                        <tr>
                        <td colspan='2' style=' border-top: 1px solid #e6e6e6; vertical-align: top; padding: 20px;'>" . str_replace("##YEAR##", date('Y'), Configure::read('Site.Copyright')) . "</td>
                        </tr>
                        </tbody>
                    </table>
                </body>
                </html>";
        // echo $html; die;
        return $html;
    }

    public function getPlanInvoiceHtml($data)
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
                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>$name</td>
                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>
                    </tr>";
            }
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
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>S.No.</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Transaction id</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Transaction Type</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Amount</th>
                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Date</th>
                </tr>
            </thead>";
                foreach ($recurringData as $loop => $recurringPayment) {
                    $total = (!empty($recurringPayment['Transaction']['total'])) ? $recurringPayment['Transaction']['total'] : "0.00";
                    $tx_id = (!empty($recurringPayment['Transaction']['tx_id'])) ? $recurringPayment['Transaction']['tx_id'] : "-";
                    $txnType = $this->getTransactionType($recurringPayment['Transaction']['txn_type']);
                    $date  = date('d F Y', strtotime($recurringPayment['Transaction']['paymant_date']));
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

        $pdf_logo_path = ROOT . DS.'app'.DS.'webroot'.DS .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
        $pdf_logo=$this->convertImageIntoBase64($pdf_logo_path); 

        $notesFindReplace = array('##PLAN##' => $name, '##PAGEURL##' => "<br/>" . SITEFRONTURL . 'plans/' . $plan_url);
        $notes = strtr($pdf_data['PdfSetting']['email_distribution_description'], $notesFindReplace);
        //
        $company = (isset($data['Company']) && !empty($data['Company']['name'])) ? $data['Company']['name'] : "";
        if (empty($company) && !empty($data['StaffUser']['Company']) && isset($data['StaffUser']['Company'][0]['name'])) {
            $company = $data['StaffUser']['Company'][0]['name'];
        }
        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>
               <style>body, html { background: #ffffff; color: #555555; font-family: sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }
               table {margin:0; padding: 0;}
               </style>            
               </head>
                <body>
                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                  <tr>
                    <td width='300'><img style='width: 160px;margin: 30px;' src='" . $pdf_logo . "' alt='logo'></td>
                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>
                  </tr>
                  <tr>
                    <td valign='top' rowspan='3' style='padding-left: 20px;'>" . Configure::read('Site.address') . "</td>
                    <td width='120' align='right' style='padding-right: 8px;'>Invoice #:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . $data['Transaction']['tx_id'] . "</td>
                  </tr>
                  <tr>
                    <td width='120' align='right' style='padding-right: 8px;'>Invoice date:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . date('M d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                  </tr>
                  <tr>
                    <td width='120' align='right' style='padding-right: 8px;'>Payment Date:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . date("M d,Y", strtotime($data['Transaction']['paymant_date'])) . "</td>
                  </tr>
                  <tr>
                  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>
                            " . strip_tags(Configure::read('Site.email')) . "
                            " . SITEFRONTURL . "</td>
                    <td align='center' colspan='2'>
                        
                        <table width='100' align='center' style='margin-top: 30px; margin-left: auto; margin-right: auto; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>
                            <tr>
                                <td align='center' style='text-align: center; '>
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
                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; margin: auto; border-right: 1px solid #e6e6e6; padding: 20px 0; background: #ffffff;border-collapse:collapse'>
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
                    <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                        <tbody>
                        <tr>
                            <td style='vertical-align: top; padding: 20px;'><strong>Notes: </strong>$notes</td>   
                            <td style='vertical-align: top; padding: 20px;'><strong>Terms and Conditions</strong>$tandc</td>
                        </tr>
                        </tbody>
                    </table>
                </body>
                </html>";
        // echo $html; die;
        return $html;
    }
    // public function getPlanInvoiceHtml($data){
    //     $currencySymbol=Configure::read('Site.currency');
    //     $recurringHtml="";
    //     $rows='';
    //     if(!empty($data['TransactionPlan'])){
    //         $i = 0;

    //         foreach($data['TransactionPlan'] AS $index =>$plan) {
    //         $i++;
    //             $plan_details=$this->fetchPlanData($plan['plan_id']);
    //             $name  =  $plan_details['PlanCategory']['name'];
    //             $type  =  $plan_details['Plan']['plan_type'];
    //             $rows .="<tr style='padding-top:3px'><td>$i</td><td>$name </td> <td>$type</td> <td>".$currencySymbol.$plan['plan_amount']."</td></tr>";
    //         }
    //     } 
    //     if($data['Transaction']['subscr_id']&&$data['Transaction']['subscr_id']!= NULL){
    //         $recurringData= $this->getRecurringTransaction($data['Transaction']['subscr_id'],$data['Transaction']['id']);
    //         if(!empty($recurringData)){
    //         $recRows='';
    //         $recurringHtml .="
    //         <div style='text-align:left'><h3>Recurring Transaction Summary</h3></div>
    //         <table id='detail_table' style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
    //         <thead>
    //             <tr>
    //                 <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>S.No.</th>
    //                 <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Transaction id</th>
    //                 <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Transaction Type</th>
    //                 <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Amount</th>
    //                 <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Date</th>
    //             </tr>
    //         </thead>";
    //             foreach ($recurringData as $loop => $recurringPayment){
    //                $total=(!empty($recurringPayment['Transaction']['total']))?$recurringPayment['Transaction']['total']:"0.00";
    //                $tx_id=(!empty($recurringPayment['Transaction']['tx_id']))?$recurringPayment['Transaction']['tx_id']:"-";
    //                $txnType=$this->getTransactionType($recurringPayment['Transaction']['txn_type']);
    //                $date  =date('d F Y',strtotime($recurringPayment['Transaction']['paymant_date']));
    //                $recRows .="<tr style='padding-top:3px'><td>".($loop+1)."</td><td>$tx_id</td> <td>$txnType</td> <td>".$currencySymbol.$total."</td><td>$date</td></tr>";
    //             }
    //         $recurringHtml .="<tbody>$recRows</tbody></table>";
    //         }
    //     }


    //     $promo_code='';  
    //     $total_amount=(isset($data['Transaction']['total'])&&$data['Transaction']['total']>0)?$currencySymbol.$data['Transaction']['total']:$currencySymbol."0.00";
    //     $subtotal=(isset($data['Transaction']['subtotal'])&&$data['Transaction']['subtotal']>0)?$currencySymbol.$data['Transaction']['subtotal']:$currencySymbol."0.00";
    //     $discount=(isset($data['Transaction']['discount'])&&$data['Transaction']['discount']>0)?$currencySymbol.$data['Transaction']['discount']:$currencySymbol."0.00";
    //     $tax=(isset($data['Transaction']['tax'])&&$data['Transaction']['tax']>0)?$currencySymbol.$data['Transaction']['tax']:$currencySymbol."0.00";

    //     $pdf_obj        = ClassRegistry::init('PdfSetting');
    //     $pdf_data       =   $pdf_obj->find('first',array('conditions' =>array('PdfSetting.id'=>'2')));

    //     $pdf_title      =   $pdf_data['PdfSetting']['title'];
    //     $pdf_logo       =   $pdf_data['PdfSetting']['logo'];

    //     $html="<div  style='border:1px solid #C0C0C0;padding:10px;border-radius: 8px; width:98%; display: inline-block;'>
    //     <div style='color: #6a6a6a; font-size: 25px; font-weight: 600; border-bottom: 8px solid #f5830a;font-family:sans-serif; margin-bottom:5px; padding-bottom: 5px; padding-top: 5px;float: left; width: 100%;text-align:center'>
    //             <div style='text-align:center;width:90%'><strong>$pdf_title</strong></div>
    //             </div>
    //         <div style='text-align:left'><h3 style='margin-top:5px;'>Transaction Summary</h3></div>
    //         <div>
    //             <div style='margin:0 0 5px 0;display:inline-block;border:1px solid #dee2e6;border-bottom: 4px solid #d2d6de; height:40px;width:100%;'>

    //                         <div style='padding-bottom:0;width:48%;display:inline-block;padding-left:1%;border-right:1px solid #dee2e6;margin-top:3px; padding-top:10px;padding-bottom:10px;'><h4 style='margin:0;'>".$data["StaffUser"]["email"]."</h4></div>
    //                         <div style='text-align:right;padding-bottom:0;width:49%;display:inline-block;padding-right:1%;'><h4 style='margin:0;padding-top:10px;padding-bottom:10px;'>".$data["StaffUser"]["first_name"]." ".$data["StaffUser"]["last_name"]."</h4></div>



    //             </div>

    //             <table class='pad-tb' border='0' style='margin:5px 0 25px 0;border-bottom: 4px solid #d2d6de; border-collapse: collapse; margin-bottom: 5px;width:100%;'>
    //                 <tbody>
    //                     <tr>
    //                         <th valign='center' align='left' style='padding-bottom: 2px;border:1px solid #ccc; padding:0.5em;'>Transaction ID</td>
    //                         <td style='border:1px solid #ccc; padding:0.5em;'>".$data['Transaction']['tx_id']."</td>
    //                     </tr>
    //                     <tr>
    //                         <th align='left' style='border:1px solid #ccc; padding:0.5em;'>Subtotal </td>
    //                         <td style='border:1px solid #ccc; padding:0.5em;'>$subtotal </td>
    //                     </tr>
    //                     <tr>    
    //                         <th align='left' style='border:1px solid #ccc; padding:0.5em;'>Discount </td>
    //                         <td style='border:1px solid #ccc; padding:0.5em;'>$subtotal </td>
    //                     </tr>
    //                     <tr>                            
    //                         <th align='left' style='border:1px solid #ccc; padding:0.5em;'>Tax </td>
    //                         <td style='border:1px solid #ccc; padding:0.5em;'>$tax </td>
    //                     </tr>    
    //                     <tr>
    //                         <th align='left' style='border:1px solid #ccc; padding:0.5em;'>Total </td>
    //                         <td style='border:1px solid #ccc; padding:0.5em;'>$total_amount </td>
    //                     </tr>
    //                 <tbody>
    //             </table>
    //         </div>

    //         <table class='pad-tb' id='detail_table' style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%; border-color:#ccc;' border='1px solid #ccc' cellspacing='5' cellpadding='5' id='dataTables-example'>
    //         <thead>
    //             <tr>
    //                 <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;border-color:#ccc'>S.No.</th>
    //                 <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;border-color:#ccc'>Plan</th>
    //                 <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;border-color:#ccc'>Plan Type</th>
    //                 <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;border-color:#ccc'>Amount</th>
    //             </tr>
    //         </thead>
    //         <tbody>$rows</tbody>
    //     </table>

    //     $recurringHtml

    //    <div style='display: inline-block; text-align: center;width:100%;'>
    //     <div style='margin-bottom:0;'><img src='".SITEFRONTURL."files/pdf_settings/".$pdf_logo."'></div>
    //         ".Configure::read('Site.address')."
    //     </div> 
    //     </div>

    //         <style>
    //             * { box-sizing: border-box; }
    //             table { width: 100%; margin-bottom: 30px; color: #212529; border: 1px solid #dee2e6; border-collapse: collapse; }
    //             table th { text-align: left; }
    //             table th, table td { padding: 0.75rem; vertical-align: top; border: 1px solid #dee2e6; }
    //             table tfoot tr { background-color: rgba(0,0,0,.05); font-weight: bold; }
    //             table tbody + tbody { border-top: 2px solid #dee2e6; }
    //             .pad-tb td, .pad-tb th{padding:0.6rem;border-color:#ccc;}
    //         </style>

    //             <style>
    //             td,th{line-height: 30px;}

    //             #detail_table {

    //                     border-left: 0.01em solid #ccc;
    //                     border-right: 0;
    //                     border-top: 0.01em solid #ccc;
    //                     border-bottom: 0;
    //                     border-collapse: collapse;
    //                 }
    //                 #detail_table td,
    //                 #detail_table th {
    //                     border-left: 0;
    //                     border-right: 0.01em solid #ccc;
    //                     border-top: 0;
    //                     border-bottom: 0.01em solid #ccc;

    //                 }  

    //           </style>";  
    //          // echo $html; die;
    //     return $html;
    // }   



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
        $pdf_logo_path = ROOT . DS.'app'.DS.'webroot'.DS .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
        $pdf_logo=$this->convertImageIntoBase64($pdf_logo_path); 
        $extra_charge = "   <tr>
                            
                            <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Transaction ID : </th>
                            <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>" . $data['Transaction']['tx_id'] . "</td>
                        </tr>";

        if ($transdata['extra_words'] > 0) {
            $lbl = ($transdata['extra_words'] > 1) ? "words" : "word";
            $rows .= "<tr>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Word charges</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_category'] . " Extra $lbl</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                    </tr>";
        }

        if ($transdata['extra_category'] > 0) {
            $lbl = ($transdata['extra_category'] > 1) ? "categories" : "category";
            $rows .= "<tr>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                    
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Category charges</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_category'] . " Extra $lbl</td>    
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                </tr>";
        }
        if ($transdata['extra_msa'] > 0)
            $rows .= "<tr>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra MSA charges</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['msa_amount'] . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>
                </tr>";

        if ($transdata['extra_state'] > 0) {
            $lbl = ($transdata['extra_state'] > 1) ? "states" : "state";
            $rows .= "<tr>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra State charges</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_state'] . " Extra $lbl</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['state_amount'] . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>
                </tr>";
        }
        if (!empty($transdata['translate_charges']))
            $rows .= "<tr>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Content translate charges</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>
                </tr>";

        $features = unserialize($transdata['distribution_ids']);
        if (!empty($features)) {
            foreach ($features as $index => $feature) {
                $rows .= "<tr>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $feature['name'] . " : </th>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td>
                 </tr>";
            }
        }

        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>
               <style>body, html { background: #ffffff; color: #555555; font-family: sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }
               table {margin:0; padding: 0;}
               </style>            
               </head>
                <body>
                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                  <tr>
                    <td width='300'><img style='width: 160px;margin: 30px;' src='" . $pdf_logo . "' alt='logo'></td>
                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>
                  </tr>
                  <tr>
                    <td valign='top' rowspan='3' style='padding-left: 20px;'>" . Configure::read('Site.address') . "</td>
                    <td width='120' align='right' style='padding-right: 8px;'>Invoice #:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . $data['Transaction']['tx_id'] . "</td>
                  </tr>
                  <tr>
                    <td width='120' align='right' style='padding-right: 8px;'>Invoice date:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . date('M d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                  </tr>
                  <tr>
                    <td width='120' align='right' style='padding-right: 8px;'>Payment Date:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . date("M d,Y", strtotime($data['Transaction']['paymant_date'])) . "</td>
                  </tr>
                  <tr>
                  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>
                            " . strip_tags(Configure::read('Site.email')) . "
                            " . SITEFRONTURL . "</td>
                    <td align='center' colspan='2'>
                        
                        <table width='100' align='center' style='margin-top: 30px; margin-left: auto; margin-right: auto; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>
                            <tr>
                                <td align='center' style='text-align: center; '>
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


                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; margin: auto; border-right: 1px solid #e6e6e6; padding: 20px 0; background: #ffffff;border-collapse:collapse'>
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

                        
                    </tbody>    
                    <tfoot>
                        <tr>
                           <td align='center' colspan='2'></td>
                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>
                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$total_amount</td>
                        </tr>
                    </tfoot>
                </table>
                    <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                        <tbody>

                        <tr>
                            <td colspan='2' style='vertical-align: top; padding: 20px;'><strong>Notes: </strong>Press Release invoice pdf attached.</td>
                        </tr
                       
                        </tbody>
                    </table>
                </body>
                </html>";




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

        $pdf_logo_path = ROOT . DS.'app'.DS.'webroot'.DS .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
        $pdf_logo=$this->convertImageIntoBase64($pdf_logo_path); 

        $extra_charge = "   <tr>
                            
                            <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Transaction ID : </th>
                            <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>" . $data['Transaction']['tx_id'] . "</td>
                        </tr>";

        if ($transdata['extra_words'] > 0) {
            $lbl = ($transdata['extra_words'] > 1) ? "words" : "word";
            $rows .= "<tr>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Word charges</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_words'] . " Extra $lbl</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                    </tr>";
        }

        if ($transdata['extra_category'] > 0) {
            $lbl = ($transdata['extra_category'] > 1) ? "categories" : "category";
            $rows .= "<tr>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                    
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Category charges</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_category'] . " Extra $lbl</td>    
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td>
                </tr>";
        }
        if ($transdata['extra_msa'] > 0)
            $rows .= "<tr>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra MSA charges</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_msa'] . " Extra MSA</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['msa_amount'] . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>
                </tr>";

        if ($transdata['extra_state'] > 0) {
            $lbl = ($transdata['extra_state'] > 1) ? "states" : "state";
            $rows .= "<tr>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra State charges</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_state'] . " Extra $lbl</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['state_amount'] . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>
                </tr>";
        }
        if (!empty($transdata['translate_charges']))
            $rows .= "<tr>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Content translate charges</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>
                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td>
                </tr>";

        $features = unserialize($transdata['distribution_ids']);
        if (!empty($features)) {
            foreach ($features as $index => $feature) {
                $rows .= "<tr>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['created'])) . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $feature['name'] . " : </th>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td>
                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td>
                 </tr>";
            }
        }

        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>
               <style>body, html { background: #ffffff; color: #555555; font-family: sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }
               table {margin:0; padding: 0;}
               </style>            
               </head>
                <body style='max-width:650px; margin:auto;'>
                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                  <tr>
                    <td width='300'><img style='width: 160px;margin: 30px;' src='" .$pdf_logo . "' alt='logo'></td>
                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>
                  </tr>
                  <tr>
                    <td valign='top' rowspan='3' style='padding-left: 20px;'>" . Configure::read('Site.address') . "</td>
                    <td width='120' align='right' style='padding-right: 8px;'>Invoice #:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . $data['Transaction']['tx_id'] . "</td>
                  </tr>
                  <tr>
                    <td width='120' align='right' style='padding-right: 8px;'>Invoice date:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . date('M d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                  </tr>
                  <tr>
                    <td width='120' align='right' style='padding-right: 8px;'>Payment Date:</td>
                    <td width='150' align='left' style='padding-right: 20px;'>" . date("M d,Y", strtotime($data['Transaction']['paymant_date'])) . "</td>
                  </tr>
                  <tr>
                  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>
                            " . strip_tags(Configure::read('Site.email')) . "
                            " . SITEFRONTURL . "</td>
                    <td align='center' colspan='2'>
                        
                        <table width='100' align='center' style='margin-top: 30px; margin-left: auto; margin-right: auto; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>
                            <tr>
                                <td align='center' style='text-align: center; '>
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


                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; margin: auto; border-right: 1px solid #e6e6e6; padding: 20px 0; background: #ffffff;border-collapse:collapse'>
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

                        
                    </tbody>    
                    <tfoot>
                        <tr>
                           <td align='center' colspan='2'></td>
                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>
                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$total_amount</td>
                        </tr>
                    </tfoot>
                </table>
                    <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                        <tbody>

                        <tr>
                            <td colspan='2' style='vertical-align: top; padding: 20px;'><strong>Notes: </strong>Press Release invoice pdf attached.</td>
                        </tr
                        <tr>
                        <td colspan='2' style=' border-top: 1px solid #e6e6e6; vertical-align: top; padding: 20px;'>" . str_replace("##YEAR##", date('Y'), Configure::read('Site.Copyright')) . "</td>
                        </tr>
                        </tbody>
                    </table>
                </body>
                </html>";

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
        $obj = ClassRegistry::init("Msa");
        $data = $obj->find('first', array('conditions' => array('Msa.id' => $id)));
        $parts = explode(",", $data['Msa']['name']);
        return $parts[0];
    }
    public function summaryPrefix($msaId = '', $stateId = '', $date = '')
    {
        $msa = (!empty($msaId)) ? $this->getMsaNameById($msaId['0']) . ', ' : '';
        $site_name = strip_tags(Configure::read('Site.name'));

        if (!empty($msa)) {
            $string = (!empty($stateId)) ? $msa . $this->getStateNameById($stateId['0']) . ', ' : '';
        } else {
            $string = (!empty($stateId)) ? $this->getStateNameById($stateId['0']) . ', ' : '';
        }

        return $string . date('M d, Y', strtotime($date)) . ", /<a style='text-decoration:none;color:black' target='_blank' rel='nofollow' href='" . SITEFRONTURL . "' title='" . $site_name . ".com'>" . $site_name . "</a>/ -- ";
    }

    public function getprnumber($id)
    {
        $obj = ClassRegistry::init('Plan');
        $data = $obj->find('first', array('conditions' => array('Plan.id' => $id), 'fields' => array('number_pr')));
        return ($data['Plan']['number_pr'] > "0") ? $data['Plan']['number_pr'] : "0";
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

    public function getClippingReportViewHtml($data_array = [], $prdata = '', $nwrelationships, $user_data, $approved_data)
    {

        $prRow = "";
        if (!empty($prdata)) {

            $obj = ClassRegistry::init('Company');
            $conditions = array("Company.id" => $prdata['company_id']);
            $company_data = $obj->find('first', array('conditions' => $conditions));
            $site_name = strip_tags(Configure::read('Site.name'));
            $dateformate = strip_tags(Configure::read('Site.DateFromat'));
            // $dateformate="m-d-Y";
            $plan_obj = ClassRegistry::init('Plan');
            $plan_data = $plan_obj->find('first', array('conditions' => array('Plan.id' => $prdata['plan_id'])));
            $plan_name = $plan_data['PlanCategory']['name'];
            $pr_title = $prdata['title'];
            $pdf_obj = ClassRegistry::init('PdfSetting');
            $pdf_data = $pdf_obj->find('first', array('conditions' => array('PdfSetting.id' => '1')));
            $pdfFindReplace = array('##PLAN_NAME##' => $plan_name);

            $pdf_title                  =   $pdf_data['PdfSetting']['title'];
            $pdf_logo                   =   $pdf_data['PdfSetting']['logo'];
            $pdf_welcome_text           =   strtr($pdf_data['PdfSetting']['welcome_text'], $pdfFindReplace);
            $pdf_network_description    =   $pdf_data['PdfSetting']['network_description'];
            $pdf_email_description      =   $pdf_data['PdfSetting']['email_distribution_description'];
            $pdf_footer      =   $pdf_data['PdfSetting']['footer_text'];

            $pdf_logo_path = ROOT . DS.'app'.DS.'webroot'.DS .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
            $pdf_logo=$this->convertImageIntoBase64($pdf_logo_path); 

            $company_name = $company_data['Company']['name'];

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
            $company_logo_path = ROOT . '/app/webroot/' . "files/company/logo/" . $company_data['Company']['logo_path'] . "/" . $company_data['Company']['logo'];
            //die;
            // print_r($company_logo_path);die;
            $path_info = pathinfo($company_logo_path);
            //pr($path_info); die;
            png_to_jpg($company_logo_path, $path_info['dirname'] . '/' . $path_info['filename'] . '.jpg', 100);

            $company_logo = SITEFRONTURL . "files/company/logo/" . $company_data['Company']['logo_path'] . "/" . $path_info['filename'] . '.jpg';

        }
        $countryReport = $links = $spamMailList = $unsubscribedMailList = $newsletterMailList = $sentMailReportdata = $bouncedMailList = "";
        $champ = $this->getCampaignDetails($prdata['id']);
        $newsletterMailList = $this->getNewsletterOpenMailList($prdata['id']);
        $newsletterSentMailList = $this->getNewsletterSentMailList($prdata['id']);
        $newsletterReceivedMailList = $this->getNewsletterReceivedMailList($prdata['id']);

        if (!empty($champ)) {
            $sentMailReportdata = $this->getSentMailReport($champ['opens']);
            $countryReport = $this->getSendyCountryReport($champ['opens']);
            // $unsubscribedMailList = $this->getSendyUnsubscribedMailList($champ['id']);
            // $bouncedMailList = $this->getSendyBouncedMailList($champ['id']);
            // $spamMailList = $this->getSendySpamMailList($champ['id']);
            // $links = $this->getLinksMailList($champ['id']);
        }
        $clippingRow = '';


        $clippingRow .= "
        <style>
        </style>
  
                <table style=' padding: 0.75rem;vertical-align: top;vertical-align: middle;' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:left;'>S/N</th>
                    <th style='text-align:left;'>Site Identity</th>
                    <th style='text-align:left;'>Media Name</th>
                    <th style='text-align:left;'>Website</th> 
                    <th style='text-align:left;'>Location</th>
                    <th style='text-align:left;'>Media Type</th>
                    <th style='text-align:left;'>Potential<br>Audience</th>
                </tr>";
        if (isset($nwrelationships) && !empty($nwrelationships)) {
            $i = $total_potential_audience = 0;
            //pr($nwrelationships); die;
            $fc_content_url = "markets.financialcontent.com";
            $fc_content = 2;
            foreach ($nwrelationships as $index => $nwrelationship) {



                $i++;
                $networkwebsite = $nwrelationship;

                $word = "markets.financialcontent.com";
                $mystring = $nwrelationship['NwRelationships']['press_release_link'];

                if ((strpos($mystring, $word) !== false) && $fc_content == 2) {
                    $ab = "a1";
                    $company_logo_path = SITEFRONTURL . "website/img/blank.jpg";
                    $fc_content++;
                } else {
                    $ab = "a2";
                    $company_logo_path = SITEFRONTURL . "website/img/blank.jpg";
                }

                // echo "<pre>";
                // print_r($networkwebsite);
                // echo "<pre>";




                if (strpos($mystring, $word) == false) {
                    $ab = "a3";
                    $company_logo_path = SITEFRONTURL . $networkwebsite['NwRelationships']['site_logo'];
                }
                // if(empty($company_logo_path))
                // {
                //     $company_logo_path = "/home/h0p8zyrz29ni/public_html/email_wire/app/webroot/website/img/"
                // }

                $link = $nwrelationship['NwRelationships']['press_release_link'];
                $link_parse = parse_url($link);
                $link_host = '';
                if (isset($link_parse['host'])) {
                    $link_host = $link_parse['host'];
                }

                $pa = $nwrelationship['NwRelationships']['potential_audience'];
                if ($pa == '0' || $pa == '') {
                    $pa = "NA";
                }

                $clippingRow .= "
                <tr style='padding-top:3px'>
                  <td style='text-align:left;'>" . $i . "</td>
                  <td style='text-align:left;'><img width='120px' alt='" . ucwords($networkwebsite['NwRelationships']['site_name']) . "' src='" . $company_logo_path . "'></td>
                  <td style='text-align:left;'>" . ucwords($networkwebsite['NwRelationships']['site_name']) . "</td>
                  <td style='text-align:left;'><a class='white-space-nowrap' target='_blank' href=" . $nwrelationship['NwRelationships']['press_release_link'] . ">View Release</a></td>
                  <td style='text-align:left;'>" . $nwrelationship['NwRelationships']['location'] . "</td>
                  <td style='text-align:left;' class='white-space-nowrap' >" . $nwrelationship['NwRelationships']['type'] . "</td>
                  <td style='text-align:left;'>" . $this->numberFormatAsUs($pa)  . "</td>
                </tr>";

                $total_potential_audience = $total_potential_audience + $nwrelationship['NwRelationships']['potential_audience'];
            }
            $total_sn = $i;
            $totalPotentialAudience=$this->numberFormatAsUs($total_potential_audience);
            $pdf_network_description = str_replace("##PR-AUDIENCE##", $totalPotentialAudience, $pdf_network_description);
            $pdf_network_description = str_replace("##PR-PICKUPS##", $total_sn, $pdf_network_description);
        }
        $clippingRow   .= "</table> ";

        $clippingRow .= "<div style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>
                    RSS Media Distribution
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:left;'>S/N</th>
                    <th style='text-align:left;'>Name</th> 
                    <th style='text-align:left;'>Published URL</th> 
                    <th style='text-align:left;'>Distribution Type</th>
                    <th style='text-align:left;'>Views</th>                  
                    <th style='text-align:left;'>Distribution Date</th> 
                </tr>";
        $rss_sno = 0;
        foreach ($data_array as $index => $rssmedia) {

            if (isset($rssmedia['ClippingReport'])) {

                if ($rssmedia["ClippingReport"]['distribution_type'] == "rss_feed") {
                    $rss_sno++;
                    $release_page_url = "<a style='text-decoration:none;color:black' target='_blank' href=" . $rssmedia["ClippingReport"]['release_page_url'] . ">" . $rssmedia["ClippingReport"]['domain'] . "</a>";
                    $clippingRow .= "<tr style='padding-top:3px;text-align:left;'><td>" . $rss_sno . "</td><td style='text-align:left;'>" . $rssmedia["ClippingReport"]['site_name'] . "</td><td style='text-align:left;'>$release_page_url</td><td style='text-align:left;'>" . str_replace("_FEED", " ", strtoupper($rssmedia["ClippingReport"]['distribution_type'])) . "</td> <td style='text-align:left;'>" . $rssmedia["ClippingReport"]['views'] . "</td><td style='text-align:left;'>" . date($dateformate, strtotime($rssmedia["ClippingReport"]['created'])) . "</td></tr>";
                }
            }
        }
        $clippingRow   .= "</table> ";

        $clippingRow .= "<div style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>
                    JS Media Distribution
                </div>
                <table style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>
                <tr>
                    <th style='text-align:left;'>S/N</th>
                    <th style='text-align:left;'>Name</th> 
                    <th style='text-align:left;'>Published URL</th> 
                    <th style='text-align:left;'>Distribution Type</th>
                    <th style='text-align:left;'>Views</th>                  
                    <th style='text-align:left;'>Distribution Date</th>
                </tr>";
        $js_sno = 0;
        foreach ($data_array as $index => $jsmedia) {
            if (isset($jsmedia['ClippingReport'])) {
                if ($jsmedia["ClippingReport"]['distribution_type'] == "js_feed") {
                    $js_sno++;
                    $release_page_url = "<a style='text-decoration:none;color:black' target='_blank' href=" . $jsmedia["ClippingReport"]['release_page_url'] . ">" . $jsmedia["ClippingReport"]['release_page_url'] . "</a>";
                    $clippingRow .= "<tr style='padding-top:3px'><td>" . $js_sno . "</td><td style='text-align:left;'>" . $jsmedia["ClippingReport"]['site_name'] . "</td><td style='text-align:left;'>$release_page_url</td><td style='text-align:left;'>" . str_replace("_FEED", " ", strtoupper($jsmedia["ClippingReport"]['distribution_type'])) . "</td> <td style='text-align:left;'>" . $jsmedia["ClippingReport"]['views'] . "</td><td style='text-align:left;'>" . date($dateformate, strtotime($jsmedia["ClippingReport"]['created'])) . "</td></tr>";
                }
            }
        }
        $clippingRow   .= "</table> ";

        if (!empty($newsletterSentMailList)) {

            $clippingRow  .= "<div style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>Email Distribution</div>
                <div style='margin:0 10px 10px 10px;color: #4d4d4d;'>
                $pdf_email_description
                </div>";





            $clippingRow .= "
                <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
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
        }
        $clippingRow   .= "</table>";


        /*Newsleter  sent emails */

        if (!empty($newsletterReceivedMailList)) {
            $clippingRow .= "
            <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
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
        }
        $clippingRow   .= "</table>";

        /*Newsleter open emails */

        if ($newsletterMailList) {

            $clippingRow .= "
                <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
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
        }
        $clippingRow   .= "</table>";

        /* Sendy clipping reports*/

        if (!empty($sentMailReportdata)) {

            $clippingRow  .= "<div  style='margin:20 0px 20px 0px' class='col-lg-6'>
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
        }
        $clippingRow   .= "</table>";

        /*Country Report emails */

        if ($countryReport) {
            $clippingRow .= "
            <div style='margin:20px 0px 20px 0px' class='col-lg-6'>
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
        }
        $clippingRow   .= "</table>";

        $update_time    = date('F d, Y', time());
        $replaceCompanyName = ["##COMPANYNAME##" => $company_name];
        $replaceFooterText = ["##PHONE##" => strip_tags(Configure::read('Site.phone')), "##YEAR##" => date('Y')];
        $html = "
          <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
          <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js'></script>
           <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
           <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js'></script>

        <style>.fontbold{font-weight: bold;} tr:nth-child(even) {background-color: #ffffff;}</style> 
            <div class='container'>

            <div style='color: #6a6a6a; font-size: 25px; font-weight: 600;font-family:sans-serif; padding-bottom: 5px; padding-top: 5px;float: left; width: 100%;text-align:center'>
                <h3><strong> " . strtr($pdf_title, $replaceCompanyName) . "</strong></h3>
                </div> 
                <div style='text-align:center'><br/><br/><br/></div>
                <div style='text-align:center'><h3>$pr_title</h3></div>
                <div style='text-align:center'>
                <img src='" . $company_logo . "'> 
                </div>
                 <br><br>

                <div style='text-align:center'>Press Release Distribution by $site_name</div>

                <div align='center'>
               
                <img width='100%' src='" . $pdf_logo . "'>
                </div>
                <br>
      
                <div style='text-align:center; margin:0 10px 0 10px'>&nbsp;$pdf_welcome_text</div>

                <div style='text-align:center'> Updated " . $update_time . " </div>
                <br/>
                <div style='text-align:center'><br/></div>
                <!--<div style='margin-bottom:50px'>&nbsp;</div>
                <div style='display:none;'><div style='margin:5px 0 0 0 ;color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8; margin-bottom: 15px; padding-bottom: 5px; padding-top: 5px;float: left; width: 100%;' >$site_name</div> $prRow</div><br/> <br/> 
                <br/>-->
                <div  style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>Media Pickup</div>
                <div style='margin:0 10px 10px 10px;color: #4d4d4d;'>$pdf_network_description</div>
                $clippingRow
                <div style='font-size:14px; color: #4d4d4d; text-align: center; margin-top:50px;'> " . strtr($pdf_footer, $replaceFooterText) . "</div> 
                <div style='text-align:center'><br/><br/></div>
          
                <div style='text-align:center'><br/></div></div>
				<style>
                @media print {.pagebreak { page-break-after: always!important;} }
                table td, table th,
                table td img, table th img { background: #ffffff; }
                table { border-collapse: collapse; border: 1px solid #dee2e6; width: 100%; margin-bottom: 1rem; color: #212529; text-align: left; }
                body table td, body table th {  padding: 0.75rem; vertical-align: top; border-top: 1px solid #ffffff; border-width: 0; vertical-align: middle; border-bottom: 1px solid #e5e5e5; line-height: 30px; padding: 5px; }
              </style>
              ";

        // echo $html;die;
        return $html;
    }
    
    
      /*
     * @params:  
     * @Function use: getCountryList: This function use to get the country list
     * @created by: Vikram Singh
     * @Created: 22-Oct-2024
     */
    public function getMasterPlanList()
    {
       
        $data = ClassRegistry::init('MasterPlan')->find('first');
        return $data['MasterPlan'];
            // $this->MasterPlan = ClassRegistry::init('MasterPlan');
            // $list = $this->MasterPlan->find('list');
            // return $list;
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
     * @Function use: showTrashButton: This function use to get the country list
     * @created by: Hitesh verma
     * @Created: 16-07-2022
     */

    public function showTrashButton($model, $trashStatusCode = '4')
    {
        $obj = ClassRegistry::init("$model");
        $count = $obj->find('count', array("conditions" => array("$model.status" => $trashStatusCode)));
        return ($count > 0) ? true : false;
    }

    /*
     * @params:  
     * @Function use: getRecordIds: This function using in plan name manager
     * @created by: Hitesh verma
     * @Created: 22-09-2022
     */

    function getRecordIds($datadArr, $model)
    {
        $responseId = [];
        if (!empty($datadArr)) {
            foreach ($datadArr as $data) {
                $responseId[$data['id']] = $data['id'];
            }
        }
        return $responseId;
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
            $list = $this->Language->find('list', array("fields"=>["code","name"],'conditions' => array('Language.status' => '1'), 'order' => "name ASC"));
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
            $list = $this->Language->find('list', array("fields"=>["code","id"],'conditions' => array('Language.status' => '1'), 'order' => "name ASC"));
            Cache::write('language_code_list', $list, 'long');
         }
        return (!empty($list)&&!empty($list[$code]))?$list[$code]:'1'; // Default Language is english
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

    public function getClippingReportViewHtmlSend($prdata = '', $nwrelationships=[],$clippingReportData = '')
    {
        $prRow = ""; 
        if (!empty($prdata)) {

            $obj = ClassRegistry::init('Company');
            $conditions = array("Company.id" => $prdata["PressRelease"]['company_id']);
            $company_data = $obj->find('first', array('conditions' => $conditions));
            $site_name = strip_tags(Configure::read('Site.name'));
        
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
            
            
            if($company_data['Company']['logo']){
             $company_logo_path = ROOT . '/app/webroot/' . "files/company/logo/" . $company_data['Company']['logo_path'] . "/" . $company_data['Company']['logo'];
             $companyLogo=$this->convertImageIntoBase64($company_logo_path); 
            }else{
                $companyLogo= '';
            }
            $pdf_logo_path = ROOT . DS.'app'.DS.'webroot'.DS .'files' . DS . 'pdf_settings' . DS . $pdf_logo;
            $pdf_logo=$this->convertImageIntoBase64($pdf_logo_path); 
            /*
            $path_info = pathinfo($company_logo_path);
             $this->png_to_jpg($company_logo_path, $path_info['dirname'] . '/' . $path_info['filename'] . '.jpg', 100);
             $companyLogo=SITEFRONTURL."company/logo/" . $company_data['Company']['logo_path'] . "/" . $company_data['Company']['logo'];
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
                <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js'></script>
        */
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
                        body{font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif,DejaVu Sans; }
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
                        body { font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,DejaVu Sans,sans-serif !important; border: 0px solid #666666; }
                </style>  
                </head>
                <body>
                <table style='vertical-align: bottom; color: #4d4d4d;border: none; line-height:1.5em;'>
                    <tbody>
                        <tr>
                            <td style='text-align: left;border: none;line-height:1em'><h2><strong> <img style='width: 120px;' src='".$companyLogo."'></strong></h2></td>
                        
                            <td style='text-align: right;border: none;line-height:.5em'><img src='".$pdf_logo."'></td>
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


    public function getNewsletterReceivedMailList($pId = '')
    {
        $results = [];
        if (!empty($pId)) {
            $results = ClassRegistry::init('NewsletterLog')->find("all", array('conditions' => ['NewsletterLog.press_release_id' => $pId, 'NewsletterLog.is_mail_sent' => 1], 'fields' => array('StaffUser.id', 'StaffUser.email', 'NewsletterLog.created'), 'order' => 'NewsletterLog.id DESC'));
        }
        return $results;
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
                        $websiteDirPath = ROOT . '/app/webroot/'.$nwrelationship['NwRelationships']['site_logo']; 

                        $websiteLogo=(!empty($nwrelationship['NwRelationships']['site_logo'])&&file_exists($websiteDirPath))?SITEFRONTURL.$nwrelationship['NwRelationships']['site_logo']:SITEFRONTURL."website/img/blank.jpg";
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
    public function getStatisticsHtml($data=[],$planName=""){
        $dateFormat = strip_tags(Configure::read('Site.DateFromat'));
        $totalPotentialAudience=(!empty($data['0']['potentialAudienceCount']))?$this->numberFormatAsUs($data['0']['potentialAudienceCount']):'NA';
        $totalViews=(!empty($data['PressRelease']['views']))?$this->numberFormatAsUs($data['PressRelease']['views']):'NA';
        $networkFeedCount=(!empty($data['0']['networkFeedCount']))?$this->numberFormatAsUs($data['0']['networkFeedCount']):'NA';
        $socialShareCount=(!empty($data['0']['networkFeedCount']))?$this->numberFormatAsUs($data['0']['networkFeedCount']):'NA';
        $printCount=(!empty($data['0']['networkFeedCount']))?$this->numberFormatAsUs($data['0']['networkFeedCount']):'NA';
        $emailCount=(!empty($data['0']['networkFeedCount']))?$this->numberFormatAsUs($data['0']['networkFeedCount']):'NA';
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
