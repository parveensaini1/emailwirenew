<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('AppController', 'Controller');
class CronsController extends AppController {
    public $name = 'crons';
    public $components = array('AWSSES');
    public $networkIds="";
    public $networkName="";
    public $uses = array('StaffUser','Transaction','PressRelease',"CompaniesStaffUser",'Cart','Coupon','Plan','TransactionPlan','TransactionPressRelease','RemainingUserPlan','ClippingReport','Cart','EmailTemplate','NewsletterLog');
    public function beforeFilter(){
        parent::beforeFilter();
       // $this->Auth->allow('publishembargopost','autopostonsocialmedia','ipnhandler','sendmailtoclientforprcartpayment','sendmailtoclientforcartpayment','sendmailtosubscribers','updateClippingBySitemap','updateClippingByFcApis','testmail'); 
       $this->Auth->allow();
    }
    
    private function checkEmailSendOptionInFeature($id=''){
      $this->loadModel('DistributionsPressRelease');
      $check=$this->DistributionsPressRelease->find('count',array("conditions"=>array('press_release_id'=>$id,'distribution_id'=>"8")));
      return $check;
    }

    private function updateCampaignStatus($prSlug){
      $this->loadModel('Campaign');
       $campaign=$this->Campaign->find('first',array("fields"=>array("id"),"conditions"=>array('press_release_slug '=>$prSlug,'sent' => null)));
       $send_date=strtotime("+2 minutes");
       $checkCampOndate=$this->Campaign->find('first',array("fields"=>array("id"),"conditions"=>array('created LIKE'=>'%'.date("Y-m-d").'%','sent' => null)));
       if(!empty($checkCampOndate)){
            $seconds = strtotime("+10 minutes");
            $rounded_seconds = round($seconds / (15 * 60)) * (15 * 60);
            $send_date=strtotime(date('Y:m:d H:i:s',$rounded_seconds)); 
        }

       if(!empty($campaign)){
          $savedata['Campaign']['id']=$campaign['Campaign']['id'];
          $savedata['Campaign']['status']="1";
          $savedata['Campaign']['send_date']=$send_date;
          $this->Campaign->save($savedata);
       }
    }

    private function getSubscriber($catIds="",$msaIds=""){
      if(!empty($catIds)&&!empty($msaIds)){
        $conditions =['StaffUser.staff_role_id'=>4, 'StaffUser.status' => 1,'StaffUser.newsletter_subscription' => 1,'StaffUser.email_confirmed' => 1,'OR'=>['MsasStaffUser.msa_id'=>$msaIds,'CategoryStaffUser.category_id'=>$catIds] ];
      }else if(!empty($catIds)){
        $conditions =['StaffUser.staff_role_id'=>4, 'StaffUser.status' => 1,'StaffUser.newsletter_subscription' => 1,'StaffUser.email_confirmed' => 1,'CategoryStaffUser.category_id'=>$catIds ];
      }else if(!empty($msaIds)){
        $conditions =['StaffUser.staff_role_id'=>4, 'StaffUser.status' => 1,'StaffUser.newsletter_subscription' => 1,'StaffUser.email_confirmed' => 1,'MsasStaffUser.msa_id'=>$msaIds ];
      }
      $data=$this->StaffUser->find("list",array(
        'joins' => array(
                array(
                    'table' => 'categories_staff_users',
                    'alias' => 'CategoryStaffUser',
                    'type' => 'LEFT',
                    'conditions' => array( 
                        'CategoryStaffUser.staff_user_id = StaffUser.id'
                    )
                ),
                array(
                    'table' => 'msas_staff_users',
                    'alias' => 'MsasStaffUser',
                    'type' => 'LEFT',
                    'conditions' => array( 
                        'MsasStaffUser.staff_user_id = StaffUser.id'
                    )
                )
            ),'conditions' => $conditions,"fields"=>['StaffUser.id','StaffUser.email'],'order' => 'StaffUser.id DESC'));
        return $data;
    }

    function addInSubscriberMailList($prid,$catIds="",$msaIds=""){
      $savedata=[];
      $this->loadModel('NewsletterMailList');
      $subscribers=$this->getSubscriber($catIds,$msaIds);
      if(!empty($subscribers)){
        $count=0;
        foreach ($subscribers as $userId => $email) {
          $check=$this->NewsletterMailList->find('count',array("fields"=>array("id"),"conditions"=>array('press_release_id '=>$prid,'staff_user_id'=>$userId)));
           if($check==0){
                $savedata[$count]['staff_user_id']=$userId;
                $savedata[$count]['subscriber_email']=$email;
                $savedata[$count]['press_release_id']=$prid;
                $savedata[$count]['send_date']=$this->Custom->get_newsletter_sendmail_date($userId);
            }
            $count++;
        }
        if(!empty($savedata))
          $this->NewsletterMailList->saveMany($savedata);
      } 
    }

    public function publishembargopost($roll = null, $id = null) {
        $this->layout  = false;
        $this->AutoRender  = false;
        $this->loadModel('PressRelease');
        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressImage'),'hasAndBelongsToMany'=>array('State','Distribution')));
        $current_time=time();
        $conditions = array('PressRelease.status' => '2');
        // $this->PressRelease->recursive=-1;
        $press_release_details=$this->PressRelease->find('all', array('conditions' => $conditions));
        if(isset($press_release_details) && !empty($press_release_details)){
            foreach($press_release_details as $press_release_detail ){
                $pr_time=strtotime($press_release_detail['PressRelease']['release_date']);
                $update_array=array();
                if($pr_time<=$current_time){
                    $update_array['PressRelease']['id']=$press_release_detail['PressRelease']['id'];
                    $update_array['PressRelease']['status']='1';
                    if($this->PressRelease->save($update_array)){
                        $check=$this->checkEmailSendOptionInFeature($press_release_detail['PressRelease']['id']);
                        if($check>0){
                             $this->updateCampaignStatus($press_release_detail['PressRelease']['slug']);
                        }
						// $catIds=$this->Custom->getCatArr($press_release_detail['Category']);
						// $msaIds=$this->Custom->getMsaArr($press_release_detail['Msa']);
					//	$this->addInSubscriberMailList($id,$catIds,$msaIds);
                    }
                }
            }

        }
       die;
    }



    public function getServices($username,$password){
        $socialMediaApiUrl=strip_tags(Configure::read('Social.media.api.url'));
        $response="";
        $comma="";
        $query = $socialMediaApiUrl."user/networks";
        $ch    = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:  application/json"));
        curl_setopt($ch, CURLOPT_URL, $query);
        curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password);

        $output = curl_exec($ch);
        curl_close($ch);
        $results=json_decode($output);
            if(!empty($results)&&!empty($results->networks)){
                foreach ($results->networks as $key =>$result){
                    $this->networkIds  .=$comma.$result->id;
                    $this->networkName .=$comma.$result->slug;
                    $comma=",";
                }
            } 
    }
    public function getsocialshareposts(){
        $this->loadModel('PressRelease'); 
        $this->PressRelease->recursive=-1;
        $fields=['id','title','slug','summary','body','status'];
        $data= $this->PressRelease->find('all',array(
              'joins' => array(
                    array(
                        'table' => 'distributions_press_releases',
                        'alias' => 'DistributionsPressRelease',
                        'type' => 'INNER',
                        'conditions' => array('DistributionsPressRelease.press_release_id = PressRelease.id')
                    )
                ),
          'conditions' =>array('DistributionsPressRelease.distribution_id'=>"7",'PressRelease.status'=>1,'PressRelease.release_date <=' => date('Y-m-d'),'sharedpostsocialmedia'=>0), 'limit' =>100,'fields'=>$fields, 'order' => 'PressRelease.release_date DESC') );
        $data = Set::extract('/PressRelease/.', $data);
      
        return $data;

    }

    private function udateClippingReportForSocialMedia($prId){
    	$networks=$this->networkName;
    	$socialslugs=explode(",",$networks);
        $ids=explode(",",$this->networkIds);
    	if(!empty($socialslugs)){
    		foreach($socialslugs as $index => $socialslug){
    			$name=ucfirst($socialslug);
    			$count=$this->ClippingReport->find('count',array('conditions'=>['press_release_id'=>$prId,'site_name'=>$name]));
    			if($count==0){
			    	$saveData['ClippingReport']['views']='1';
                    $saveData['ClippingReport']['press_release_id']=$prId;
			    	$saveData['ClippingReport']['release_page_url']=null;
			    	$saveData['ClippingReport']['domain']='www.'.$socialslug.".com";
			    	$saveData['ClippingReport']['site_name']=$name;
                    $saveData['ClippingReport']['distribution_type']='social_media_feed'; //social_media_feed
                     // $saveData['ClippingReport']['social_network_id']=$ids[$index];
                    $this->ClippingReport->create();
			    	$this->ClippingReport->save($saveData);
		    	}
	    	}
    	} 
    	return true;
    }
    public function autopostonsocialmedia(){
        $this->loadModel('PressRelease'); 
        $socialMediaApiUrl=strip_tags(Configure::read('Social.media.api.url'));
        $username=strip_tags(Configure::read('Social.media.username'));
        $password=htmlspecialchars_decode(strip_tags(Configure::read('Social.media.password')));

        // $username="hitesh netleon"; // $password="netleon@123";
        $networks=$this->getServices($username,$password);
        $posts=$this->getsocialshareposts();

        if(!empty($posts)){
            foreach ($posts as $index =>$post) {
                
                $description=rawurlencode(substr(strip_tags(trim($post['body'])),0,500));
                $url=rawurlencode(SITEURL."release/".$post['slug']);
                $title=rawurlencode(trim($post['title']));
                $tags=""; 
                $output = ""; 
                $query= $socialMediaApiUrl."add/bookmark?url=$url&title=$title&tags=$tags&description=$description&scheduled=Now&networks=".$this->networkIds;  


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$query);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                $result=curl_exec ($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
                curl_close ($ch);
                if($status_code){
                    $savedatapr['PressRelease']['id']=$post['id'];
                    $savedatapr['PressRelease']['sharedpostsocialmedia']='1';
                    if($this->PressRelease->save($savedatapr)){
                    	$this->udateClippingReportForSocialMedia($post['id']);
                    }
                }
               
            }
        }else{
            echo "Post not found.";
        }    
    die;
    }


    /*Temporary function */
    public function socialtracking(){
        $socialMediaApiUrl=strip_tags(Configure::read('Social.media.api.url'));
        $username=strip_tags(Configure::read('Social.media.username'));
        $password=strip_tags(Configure::read('Social.media.password'));
        $response="";
        $comma="";
        $query = "https://www.onlywire.com/api/submissions";
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:  application/json"));
        curl_setopt($ch, CURLOPT_URL, $query);
        curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password);
        $output = curl_exec($ch);
        curl_close($ch);
        $results=json_decode($output);
        pr($results); 
        die;
    }



    /**
     * ipnhandler using for save payment data
     *
     * @return void
     */
    public function ipnhandler(){  
        $log_file= WWW_ROOT.DS."ipn.log";
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        $req = 'cmd=_notify-validate';
        foreach ($myPost as $key => $value){
            $value = urlencode($value);
            $req .= "&$key=$value";
        }
        $paypal_url=(strip_tags(Configure::read('Site.payment.environment'))!='live')?strip_tags(Configure::read('Site.payment.sandbox.url')):strip_tags(Configure::read('Site.payment.live.url'));
 
        $ch = curl_init($paypal_url);
        if ($ch == FALSE) {
            return FALSE;
        }
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        if(Configure::read('debug') ){
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        $res = curl_exec($ch);
        //

        $tokens = explode("\r\n\r\n", trim($res));
        $res = trim(end($tokens)); 
        if ($res=='VERIFIED') {
            $postjson=json_encode($_POST);
            $this->ipnhandlermanage($postjson);
            
            // file_put_contents(WWW_ROOT.DS.'filenamennew.txt', print_r($_POST, true));
            $this->loadModel('Payment');
            $data=array();
            $check=$this->Payment->find('first',['conditions'=>['transaction_id'=>$_POST['txn_id']]]);
            if(!empty($check)){
                $data['Payment']['transaction_id']      =$check['Payment']['id']; 
            }
            $data['Payment']['transaction_id']      =$_POST['txn_id'];
            $data['Payment']['status']              =$_POST['payment_status']; 
            $data['Payment']['record_json']         =$postjson;
            $this->Payment->save($data);

            if(Configure::read('debug')) {
                error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, $log_file);
            }
        } else if ($res=="INVALID") {
            if(Configure::read('debug')) {
                error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, $log_file);
            }
        }

        return true;
        die;
    }

    public function ipnhandlermanage($str=''){
        $this->loadModel('Transaction'); 
        $transaction =json_decode($str,true);
        // file_put_contents(WWW_ROOT.DS.'ipnhandlermanage.txt', print_r($transaction, true));
        try{
            $saveData=[];
            if(!empty($transaction)){
                if(!empty($transaction['custom']))
                    $userPlanCartIds=explode(",",$transaction['custom']);//0=>User id,1=>Plan id,2=> Cart ID end value is always cart id 

                $data=$this->Transaction->find('first',array('fields'=>['Transaction.id'],'conditions'=>array('tx_id'=>$transaction['txn_id']))); 

                if(!empty($data)){
                    $saveData['Transaction']['id']=$data['Transaction']['id'];
                    $saveData['Transaction']['txn_type']=$transaction['txn_type'];
                    $saveData['Transaction']['subscr_id']=(isset($transaction['subscr_id'])&&!empty($transaction['subscr_id']))?$transaction['subscr_id']:"";
                    $saveData['Transaction']['payment_type'] = $transaction['payment_type'];
                    $saveData['Transaction']['status']=(strtolower($transaction['payment_status'])=='completed')?"Success":$transaction['payment_status'];
                    $saveData['Transaction']['response']=$str;
                    $this->Transaction->save($saveData);
                }else{
                    $this->loadModel('Cart'); 
                    $userId=(isset($userPlanCartIds[0])&&!empty($userPlanCartIds[0]))?$userPlanCartIds[0]:"";
                    $planId=(isset($userPlanCartIds[1])&&!empty($userPlanCartIds[1]))?$userPlanCartIds[1]:"";
                    $cartSessionId=end($userPlanCartIds);  // last value is cart_session_id
                    $cartData=$this->Cart->find('first',array("conditions"=>['Cart.cart_session_id'=>$cartSessionId]));
                   
                  
                    if(!empty($cartData) ){ // Cart not empty mean transaction details not saved
                        if($cartData['Cart']['cart_type']=="pr"){
                                $prId=(isset($userPlanCartIds[2])&&!empty($userPlanCartIds[2]))?$userPlanCartIds[2]:"";
                                $prcartdata=$this->Custom->getprcartdata($userId,$planId,$prId); 
                                $this->save_prtransactions($transaction,$userId,$planId,$prId,$prcartdata,$str);
                                die;
                        }else{  //Plan and newsroom
                            $newsroom_signup=$this->Custom->get_save_transaction_formateddata($userId);
                            $this->save_transactions($transaction,$userId,$newsroom_signup,$cartData['Cart']['company_id'],$cartSessionId,$str);
                            return true;
                        }
                    }else{  
                        /*Recuring payment case */
                        if($transaction['txn_type']=="subscr_payment"){
                            $this->save_recurring_payment($transaction,$userId,$planId,$str);
                        }else if($transaction['txn_type']=="subscr_cancel" || $transaction['txn_type']=="subscr_eot"){
                            $this->subscr_cancel_plan($transaction,$userId,$planId,$str);
                        }else{
                            $this->save_activites_paypal($transaction,$userId,$planId,$str);
                        }

                    } 
                }
            }
        }catch(Exception $exc){
            $message= $exc->getMessage();
        } 
        return true;
    }

    private function subscr_cancel_plan($transaction='',$user_id,$plan_id,$str=[]){
        if(!empty($transaction)){
            $lastTrxn=$this->Custom->checkLastTransactionStatusOfSubscrPayment($transaction['subscr_id']);
            $data_array['Transaction']['staff_user_id'] =$user_id;
            $data_array['Transaction']['txn_type'] = $transaction['txn_type'];
            $data_array['Transaction']['subscr_id']=$transaction['subscr_id'];
            $data_array['Transaction']['subscr_status']="0";
            $data_array['Transaction']['paymant_date'] = date('Y-m-d H:i:s');
            $data_array['Transaction']['reason_unsubscriber']=$lastTrxn['reason_unsubscriber'];
            $data_array['Transaction']['response']=(!empty($str))?$str:null;
             if($this->Transaction->save($data_array))
                $this->send_subscription_canceled_mail($lastTrxn['id'],$transaction['subscr_id']);

            // $updateData['Transaction']['id']=$lastTrxn['id'];

        }
    }
    private function send_subscription_canceled_mail($id,$subscr_id){ 
        $flg=true;
        $userData=$this->StaffUser->find('first',array('fields'=>['first_name','last_name','email'],'conditions'=>array("StaffUser.id"=>$user_id)) );
        $this->loadModel('EmailTemplate');
        $email = $this->EmailTemplate->selectTemplate('subscription-canceled');
        $firstName=$userData['StaffUser']['first_name'];
        $lastName=$userData['StaffUser']['last_name'];
        $url=SITEURL.'users/transaction_view/'.$id;
        $emailFindReplace = array(
                '##NAME##' =>$firstName.' '.$lastName,
                '##SITE_NAME##' => Configure::read('Site.name'), 
                '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),
                '##SUBSCRIPTION_ID##' =>$subscr_id,
                '##TRANSACTION_LINK##' =>$url,
                '##FROM_EMAIL##' => $email['from'],
                '##SITE_LOGO##' => Router::url(array(
                    'controller' => 'img',
                    'action' => '/',
                    'logo.png',
                    'admin' => false
                        ), true)
            );

        $this->AWSSES->from =$email['title']." <".$email['from'].">";
        $this->AWSSES->to   =$userData['StaffUser']['email'];
        $this->AWSSES->subject=$email['subject'];
        $this->AWSSES->replayto=$email['reply_to_email'];
        $this->AWSSES->htmlMessage=strtr($email['description'], $emailFindReplace);
        if(!$this->AWSSES->_aws_ses()){
            $this->Email->from =$email['title']." <".$email['from'].">";
            $this->Email->replyTo =$email['reply_to_email'];
            $this->Email->to = $userData['StaffUser']['email'];
            $this->Email->subject = strtr($email['subject'], $emailFindReplace);
            $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';   
            $description=strtr($email['description'], $emailFindReplace);
            if(!$this->Email->send($description))
                $flg=false;
        }
        return $flg;
    }

    private function save_activites_paypal($transaction='',$user_id,$plan_id,$str=[]){
        if(!empty($transaction)){
            $data_array['Transaction']['staff_user_id'] =$user_id;
            $data_array['Transaction']['txn_type'] = $transaction['txn_type'];
            $data_array['Transaction']['subscr_id']=(isset($transaction['subscr_id']))?$transaction['subscr_id']:"";
            $data_array['Transaction']['paymant_date'] = date('Y-m-d H:i:s');
            $data_array['Transaction']['currency']=(isset($transaction['mc_currency']))?$transaction['mc_currency']:"";
            $data_array['Transaction']['payment_type']=(isset($transaction['payment_type']))?$transaction['payment_type']:"";
            $data_array['Transaction']['discount_id'] = null;
            $data_array['Transaction']['tax']=(isset($txnData['Transaction']['tax']))?$txnData['Transaction']['tax']:"0.00"; 
            $data_array['Transaction']['subtotal']=(isset($transaction['mc_gross']))?$transaction['mc_gross']:"0.00";  
            $data_array['Transaction']['total']=(isset($transaction['mc_gross']))?$transaction['mc_gross']:"0.00";  
            $data_array['Transaction']['status']=(isset($transaction['payment_status']))?$transaction['payment_status']:"";
            $data_array['Transaction']['response']=(!empty($str))?$str:null;
             if($this->Transaction->save($data_array))
             	return true;
        }
    }
    private function save_recurring_payment($transaction,$user_id,$plan_id,$str=[]){
            $txnData=$this->Transaction->find('first',array('fields'=>['Transaction.id','Transaction.tax'],'conditions'=>array('Transaction.subscr_id'=>$transaction['subscr_id'])));
            $errorString=""; 
            $data_array = array();        
            $data_array['Transaction']['staff_user_id'] =$user_id;
            $data_array['Transaction']['tx_id'] = $transaction['txn_id'];
            $data_array['Transaction']['txn_type'] = $transaction['txn_type'];
            $data_array['Transaction']['subscr_id']=$transaction['subscr_id']; 
            $data_array['Transaction']['paymant_date'] = date('Y-m-d H:i:s');
            $data_array['Transaction']['currency']=$transaction['mc_currency'];
            $data_array['Transaction']['payment_type']=$transaction['payment_type'];
            $data_array['Transaction']['discount_id'] = null;
            $data_array['Transaction']['tax']=(isset($txnData['Transaction']['tax']))?$txnData['Transaction']['tax']:"0.00"; 
            $data_array['Transaction']['subtotal']=$transaction['mc_gross'];  
            $data_array['Transaction']['total']=$transaction['mc_gross'];  
            $data_array['Transaction']['status']=(strtolower($transaction['payment_status'])=='completed')?"Success":$transaction['payment_status'];
            $data_array['Transaction']['cart_type'] =null;  // not found in paypal 
            $data_array['Transaction']['error_message'] =$errorString; 
            $data_array['Transaction']['response']=(!empty($str))?$str:null;

            if($txnData['TransactionPlan']){
                unset($txnData['TransactionPlan']['0']['transaction_id']);
                $data_array['TransactionPlan']=$txnData['TransactionPlan'];
            }else{
                $plan = $this->Custom->fetchPlanData($plan_id);
                $amount=($plan['Plan']['bulk_discount_amount']>0)?$plan['Plan']['bulk_discount_amount']:$plan['Plan']['price'];
                $data_array['TransactionPlan']["plan_id"]=$plan_id;
                $data_array['TransactionPlan']["plan_amount"]=$amount;
            }

            $previousplan=$this->RemainingUserPlan->find('first',array('fields'=>['RemainingUserPlan.id','RemainingUserPlan.number_pr'],'conditions'=>['staff_user_id'=>$user_id,'plan_id'=>$plan_id]));
            $number_pr=$this->Custom->getprnumber($plan_id);
            $remaingPRArr['RemainingUserPlan']['staff_user_id']=$user_id;
            $remaingPRArr['RemainingUserPlan']['plan_id']=$plan_id;
            $remaingPRArr['RemainingUserPlan']['number_pr']=$number_pr;
            if(!empty($previousplan)){
                $remaingPRArr['RemainingUserPlan']['id']=$previousplan['RemainingUserPlan']['id'];
                $remaingPRArr['RemainingUserPlan']['number_pr']=($previousplan['RemainingUserPlan']['number_pr']+$number_pr);
            }
            if($this->Transaction->saveAll($data_array)){
                if($transaction['txn_type']!='subscr_cancel'){  
                    $this->RemainingUserPlan->save($remaingPRArr);

                    $userData=$this->StaffUser->find('first',array('fields'=>['pr_plan_paid','first_name','email'],'conditions'=>array("StaffUser.id"=>$user_id)) );

                    $uName=$userData['StaffUser']['first_name']; 
                    $transactionsDetail=$this->Transaction->read(null,$data_array['Transaction']['tx_id']);

                    $html = $this->Custom->getPlanInvoiceHtmlForMail($transactionsDetail);
                    $emailTemplate = $this->EmailTemplate->findByAlias('payment-invoice');
                    $this->Custom->send_invoice_mail($html, $emailTemplate['EmailTemplate'], $uName, $$userData['StaffUser']['email']);
                }
                return true;
            }
    }

    private function saveprtrans($user_id,$selectedplan,$transId="0",$status=''){ 
	        $cart_plans['totals']['subtotal']=$cart_plans['totals']['discount']=$cart_plans['totals']['tax']=$cart_plans['totals']['total']=$famount=$tax=$discount=$plan_amount='0.00';
	        $this->Cart->recursive='1';
	        $this->Cart->bindModel(array('belongsTo'=>array('Plan')));
	        $cart_plans=$this->Cart->find('first',array('conditions'=>array('staff_user_id'=>$user_id,'plan_id'=>$selectedplan,'cart_type'=>'pr')));
	        $cart_plans['TransactionPressRelease']=$cart_plans['Cart'];
	        $cart_plans['TransactionPressRelease']['transaction_id']=$transId;
	        $cart_plans['TransactionPressRelease']['staff_user_id']=$cart_plans['Cart']['staff_user_id'];
            $plan = $cart_plans['Plan'];
            $cart_plans['TransactionPressRelease']["word_amount"]="0.00";
            if($cart_plans['Cart']['extra_words']>0){  
                $amt=ceil(($cart_plans['Cart']['extra_words']/100))*$plan['add_word_amount'];
                $amount=number_format($amt,2);
                $cart_plans['TransactionPressRelease']["word_amount"]=$amount;
                $plan_amount +=$amount; 
            }

            $cart_plans['TransactionPressRelease']["category_amount"]="0.00";
            if($cart_plans['Cart']['extra_category']>0){  
                $amt=($cart_plans['Cart']['extra_category'])*($plan['add_word_amount']);
                $amount=number_format($amt,2);
                $cart_plans['TransactionPressRelease']["category_amount"]=$amount;
                $plan_amount +=$amt; 
            }

            $cart_plans['TransactionPressRelease']["msa_amount"]="0.00";
            if($cart_plans['Cart']['extra_msa']>0){
                $amtmsa=ceil($cart_plans['Cart']['extra_msa']/$plan['msa_limit'])*($plan['add_msa_charges']);
                $amountmsa=number_format($amtmsa,2);
                $cart_plans['TransactionPressRelease']["msa_amount"]=$amountmsa;
                $plan_amount +=$amtmsa; 
            } 


            $cart_plans['TransactionPressRelease']["state_amount"]="0.00";
            if($cart_plans['Cart']['extra_state']>0){
                $amtstate=ceil($cart_plans['Cart']['extra_state']/$plan['state_limit'])*($plan['add_state_charges']);
                $amountstate=number_format($amtstate,2);
                $cart_plans['TransactionPressRelease']["state_amount"]=$amountstate;
                $plan_amount +=$amtstate; 
            } 
            
            if($cart_plans['Cart']['translate_charges']>0){
                $amttx=$plan['translation_amount'];
                $amttx=number_format($amttx,2);
               $cart_plans['TransactionPressRelease']["translation_amount"]=$amttx;
                $plan_amount +=$amttx; 
            } 
            $cart_plans['feature']=[];
            if(!empty($cart_plans['Cart']['distribution_ids'])){
                $features=unserialize($cart_plans['Cart']['distribution_ids']);
                foreach ($features as $index => $value) {
                   $featureData=$this->Custom->getprfeatureprice($value['distribution_id']);
                   $cart_plans['feature'][$index]['distribution_id']=$value['distribution_id'];
                   $listId=$cart_plans['Cart']['list_id'];
                   $featureAmount=$this->Custom->getAmountMailList($listId,$value['distribution_id'],$featureData['amount'],$featureData['number']);
                   $cart_plans['feature'][$index]['name']=$featureData['name'];
                   $cart_plans['feature'][$index]['price']=$featureAmount;
                   $famount =$famount+$cart_plans['feature'][$index]['price'];
                }
                
            	$cart_plans['Cart']['distribution_ids']=serialize($cart_plans['feature']);
                	unset($cart_plans['Cart']['distribution_ids']);
            	$cart_plans['TransactionPressRelease']['distribution_ids']=serialize($cart_plans['feature']); 
                	unset($cart_plans['feature']);
            }
            
        $plan_amount +=$famount;    
        $cart_plans['TransactionPressRelease']['subtotal']=number_format($plan_amount,2);
        $cart_plans['TransactionPressRelease']['discount']=number_format($discount,2);
        $cart_plans['TransactionPressRelease']['tax']=$tax;
        $cart_plans['TransactionPressRelease']['total']=$this->Custom->get_cart_total('0',$plan_amount,$discount); 

        unset($cart_plans['Cart']);
        unset($cart_plans['Plan']); 
        unset($cart_plans['TransactionPressRelease']['id']);
        unset($cart_plans['TransactionPressRelease']['cart_type']);
        unset($cart_plans['TransactionPressRelease']['is_newsroom_incart']); 
        // unset($cart_plans['TransactionPressRelease']['staff_user_id']);
        if($this->TransactionPressRelease->save($cart_plans)){
            $lastId=$this->TransactionPressRelease->getLastInsertID();
            $distribution_ids=unserialize($cart_plans['TransactionPressRelease']['distribution_ids']);
            $distributionsArr=[];
            if(!empty($distribution_ids)){
                foreach ($distribution_ids as $key => $distribution_id) {
                     $distributionsArr[$key]['transaction_press_release_id']=$lastId;
                     $distributionsArr[$key]['distribution_id']=$distribution_id['distribution_id'];
                }
            $this->loadModel('DistributionsTransactionPressRelease');
            $this->DistributionsTransactionPressRelease->saveMany($distributionsArr);
            }
        }
        return $cart_plans;
    }

    private function save_prtransactions($transaction,$user_id,$plan_id,$prId,$sess_data,$str=[]){
        $this->loadModel('Transaction');
        $userData=$this->StaffUser->find('first',array('fields'=>['pr_plan_paid','first_name','email'],'conditions'=>array("StaffUser.id"=>$user_id)) );
        $txnData=$this->Transaction->find('first',array('conditions'=>array('tx_id'=>$transaction['txn_id'])));
            $errorString = "";
            $data_array = array();        
            if(!empty($txnData)){
                $data_array['Transaction']['id'] =$txnData['Transaction']['id'];
            }
            $data_array['Transaction']['transaction_type'] = 'pr';
            $data_array['Transaction']['cart_session_id'] =(!empty($sess_data['cart_session_id']))?$sess_data['cart_session_id']:null;
            $data_array['Transaction']['staff_user_id'] =$user_id;
            $data_array['Transaction']['txn_type'] = $transaction['txn_type']; 
            $data_array['Transaction']['tx_id'] = $transaction['txn_id']; 
            $data_array['Transaction']['paymant_date'] = date('Y-m-d H:i:s'); 
            $data_array['Transaction']['discount_id'] = isset($sess_data['discount_id'])?$sess_data['discount_id']:"0";
            $data_array['Transaction']['newsroom_amount'] ="0.00";
            $data_array['Transaction']['subtotal'] = $sess_data['totals']['subtotal'];
            $data_array['Transaction']['discount'] = $sess_data['totals']['discount'];
            $data_array['Transaction']['tax']=$sess_data['totals']['tax']; 
            $data_array['Transaction']['total']=$transaction['mc_gross'];
            $data_array['Transaction']['status']=(strtolower($transaction['payment_status'])=='completed')?"Success":$transaction['payment_status'];
            $data_array['Transaction']['currency']=$transaction['mc_currency'];
            $data_array['Transaction']['payment_type']=$transaction['payment_type'];
            $data_array['Transaction']['response']=(!empty($str))?$str:null;

            $data_array['Transaction']['cart_type'] ="";
            $data_array['Transaction']['error_message'] = $errorString; 
     
            if($this->Transaction->save($data_array)){ 
                   $txId=(!empty( $data_array['Transaction']['id']))? $data_array['Transaction']['id']: $this->Transaction->getLastInsertID();
                   $updatePrCreadits=$this->RemainingUserPlan->find('first',array('conditions'=>array('RemainingUserPlan.plan_id'=>$plan_id,'RemainingUserPlan.staff_user_id'=>$user_id,'number_pr !='=>'0'),'fields'=>array('number_pr','id'))); 
                    if($updatePrCreadits>0){
                       $number_pr=$updatePrCreadits['RemainingUserPlan']['number_pr']-1;
                       $this->RemainingUserPlan->id=$updatePrCreadits['RemainingUserPlan']['id'];
                       $this->RemainingUserPlan->saveField('number_pr',$number_pr);
                    }
                    $this->saveprtrans($user_id,$plan_id,$txId);
                    if($prId){ 
                        $savedatapr['PressRelease']['id']=$prId;
                        $savedatapr['PressRelease']['status']='0';
                        $savedatapr['PressRelease']['transaction_id']=$data_array['Transaction']['tx_id'];
                        $this->PressRelease->save($savedatapr);
                    }
                     
                    $emailTemplate = $this->EmailTemplate->selectTemplate('payment-invoice');
                    $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('press_release_id' => $prId, 'staff_user_id' => $user_id)));
                    $html=$this->Custom->getPrInvoiceHtmlForMail($data_array,$transdata);
                    $this->Custom->send_invoice_mail($html,$emailTemplate,$userData['StaffUser']['first_name'],$userData['StaffUser']['email']);

                    if($prId){ 
                        $this->Cart->deleteAll(['Cart.press_release_id' => $prId, 'Cart.staff_user_id' => $user_id, 'cart_type' => 'pr'], false);
                    }elseif(isset($sess_data['cart_session_id'])){
                        $this->Cart->deleteAll(['Cart.cart_session_id' => $sess_data['cart_session_id'], 'Cart.staff_user_id' => $user_id, 'cart_type' => 'pr'], false);
                    }else{
                        $this->Cart->deleteAll(['Cart.staff_user_id'=>$user_id,'plan_id'=>$plan_id,'cart_type'=>'pr'],false);
                    }
                    $this->Session->delete("pr_selectedplan");
            } 
    }

    private function save_transactions($transaction,$user_id,$sess_data,$company_id,$cartSessionId,$str=[]){
        $this->loadModel('StaffUser');
        $userData=$this->StaffUser->find('first',array('fields'=>['pr_plan_paid','first_name','email'],'conditions'=>array("StaffUser.id"=>$user_id)) );
        $pr_plan_paid=$userData['StaffUser']['pr_plan_paid'];
        $errorString=""; 
        $data_array = array();        
        $data_array['Transaction']['staff_user_id'] =$user_id;
        $data_array['Transaction']['cart_session_id'] =$cartSessionId;
        $data_array['Transaction']['tx_id'] = $transaction['txn_id'];
        $data_array['Transaction']['txn_type'] = $transaction['txn_type']; 
        $data_array['Transaction']['paymant_date'] = date('Y-m-d H:i:s');
        $data_array['Transaction']['currency'] = $transaction['mc_currency'];
        $data_array['Transaction']['payment_type'] = $transaction['payment_type'];
        $data_array['Transaction']['discount_id'] = $sess_data['Transaction']['discount_id'];
        $data_array['Transaction']['newsroom_amount'] = $sess_data['Transaction']['newsroom_amount'];
        $data_array['Transaction']['subtotal'] = $sess_data['Transaction']['subtotal'];
        $data_array['Transaction']['discount'] = $sess_data['Transaction']['discount'];
        $data_array['Transaction']['tax'] = $sess_data['Transaction']['tax'];
        $data_array['Transaction']['total'] = $transaction['mc_gross'];  
        $data_array['Transaction']['status'] =(strtolower($transaction['payment_status'])=='completed')?"Success":$transaction['payment_status'];
        $data_array['Transaction']['cart_type'] =null;  // not found in paypal 
        $data_array['Transaction']['error_message'] =$errorString; 
        $data_array['Transaction']['company_id'] =$company_id;
        $data_array['Transaction']['response']=(!empty($str))?$str:null;

        $data_array['Transaction']['is_plan_newsroom'] =(!empty($sess_data['TransactionPlan'])&&$sess_data['Transaction']['newsroom_amount']>0)?"1":"0"; 
        
        $this->loadModel('Transaction'); 
        if($this->Transaction->save($data_array)){
            $this->Cart->deleteAll(array('Cart.staff_user_id' => $user_id, 'Cart.cart_session_id' => $cartSessionId), false);

            //pr($sess_data['TransactionPlan']);die;
            $txId=$this->Transaction->getLastInsertID();

            if(isset($sess_data['TransactionPlan'])&&!empty($sess_data['TransactionPlan'])){
                $this->loadModel('TransactionPlan');
                $this->loadModel('RemainingUserPlan');
                $remaingPRArr=[];
                foreach ($sess_data['TransactionPlan'] as $index => $sess_data){
                    $newdataArr[$index]['transaction_id']= $txId;
                    $newdataArr[$index]['plan_id']=$sess_data['plan_id'];
                    $newdataArr[$index]['plan_amount']=$sess_data['plan_amount'];

                    $data_array['TransactionPlan'][$index]['title']= $sess_data['title'];
                    $data_array['TransactionPlan'][$index]['plan_id']=$sess_data['plan_id'];
                    $data_array['TransactionPlan'][$index]['plan_amount']=$sess_data['plan_amount'];

                    $number_pr=$this->Custom->getprnumber($sess_data['plan_id']);
                    $remaingPRArr[$index]['staff_user_id']=$user_id;
                    $remaingPRArr[$index]['transaction_id']=$txId;
                    $remaingPRArr[$index]['number_pr']=$number_pr;
                    $remaingPRArr[$index]['plan_id']=$sess_data['plan_id'];

                    $previousplan=$this->RemainingUserPlan->find('first',array('fields'=>['RemainingUserPlan.id','RemainingUserPlan.number_pr'],'conditions'=>['staff_user_id'=>$user_id,'plan_id'=>$sess_data['plan_id']]));
                    if(!empty($previousplan)){
                        $remaingPRArr[$index]['id']=$previousplan['RemainingUserPlan']['id'];
                        $remaingPRArr[$index]['number_pr']=($previousplan['RemainingUserPlan']['number_pr']+$number_pr);
                    }

                } 

                $this->TransactionPlan->saveMany($newdataArr,array('deep'=>true));
                $this->RemainingUserPlan->saveMany($remaingPRArr,array('deep'=>true));
                $pr_plan_paid=(empty($errorString))?"1":"0";
            }

            $this->StaffUser->id=$user_id;
            $this->StaffUser->saveField('pr_plan_paid',$pr_plan_paid);
            $this->Session->write('Auth.User.pr_plan_paid', $pr_plan_paid);
 
            if(strtolower($transaction['payment_status'])=='completed'){    
                
                
                $this->loadModel('EmailTemplate');
                $this->loadModel('PressRelease'); 
                $this->Session->delete("ew_cartdata"); 

                $uName=$userData['StaffUser']['first_name'];
                //as UserController
                $transactionsDetail=$this->Transaction->read(null,$txId);
                $html=$this->Custom->getPlanInvoiceHtmlForMail($transactionsDetail);
                //as UserController

                $emailTemplate = $this->EmailTemplate->selectTemplate('payment-invoice');
                $mailTo=$userData['StaffUser']['email']; 
                $this->Custom->send_invoice_mail($html,$emailTemplate,$uName,$mailTo);
               
                // $newsroomcount=$this->Company->find('count', array('conditions' => array('Company.staff_user_id' =>$user_id)));
         

                // $pressReleaseCount=$this->PressRelease->find('count',array('conditions'=>array("PressRelease.staff_user_id"=>$user_id)));  

                if($company_id!=0){  
                    App::import('Controller', 'Users');
                    $UsersController = new UsersController;
                    $UsersController->removeNewsroomCookie();
                    $csavedate['Company']['id']=$company_id;
                    $csavedate['Company']['payment_status']='1';
                    $this->Company->save($csavedate);
                } 
            } 
        }
    } 

    public function getEmailTemplate($cart){ 
        $flg=true;
        $this->loadModel('EmailTemplate');
        $emailtempname='cart-pending-email';
        $url=SITEURL.'plans/online-distribution';
        if($cart['Cart']['cart_type']=='pr'){
            $emailtempname='pr-cart-pending-email';   
            $url=SITEURL.'users/makepayment/'.$cart['Cart']['plan_id'].'/'.$cart['Cart']['press_release_id'];
        }
        $email = $this->EmailTemplate->selectTemplate($emailtempname);
        $emailFindReplace = array(
                '##NAME##' =>$cart['StaffUser']['first_name'].' '.$cart['StaffUser']['last_name'],
                '##SITE_NAME##' =>strip_tags(Configure::read('Site.name')), 
                '##PAYMENT_LINK##' =>$url,
                '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),
                '##FROM_EMAIL##' => $email['from'],
                '##SITE_LOGO##' => Router::url(array(
                    'controller' => 'img',
                    'action' => '/',
                    'logo.png',
                    'admin' => false
                        ), true)
            );
        $this->AWSSES->from =$email['title']." <".$email['from'].">";
        $this->AWSSES->to =  $cart['StaffUser']['email'];
        $this->AWSSES->subject =$email['subject'];
        $this->AWSSES->replayto=$email['reply_to_email'];
        $this->AWSSES->htmlMessage=strtr($email['description'], $emailFindReplace);
        if(!$this->AWSSES->_aws_ses()){
            $this->Email->from =$email['title']." <".$email['from'].">";
            $this->Email->replyTo =$email['reply_to_email'];
            $this->Email->to = $cart['StaffUser']['email'];
            $this->Email->subject = $email['subject'];
            $this->Email->sendAs = ($email['is_html'])?'html':'text';   
            $description=strtr($email['description'], $emailFindReplace);
            if(!$this->Email->send($description))
                $flg=false;
        }
        return $flg;
    }

     /*
     * @params: 
     * @Function use: send mail to client if cart is pending, this function also use in send mail manually by admin
     * @created by: Hitesh verma
     * @Created: 16-09-2019
     */
    public function sendmailtoclientforcartpayment($carttype=''){
        $ismailsent=0;
        $update_data=[];
        $conditions=array('Cart.next_email'=>date('Y-m-d'));
        if(!empty($carttype)){
            $conditions=array('Cart.cart_type'=>$carttype);
        }
        $fields=['Cart.id','Cart.plan_id','Cart.sent_time','Cart.press_release_id','Cart.cart_type','StaffUser.email','StaffUser.first_name','StaffUser.last_name'];
        $data_array=$this->Cart->find('all',array( 'fields'=>array('Cart.*','StaffUser.email'),'joins' => array( 
             array(
                'table' => 'staff_users',
                'alias' => 'StaffUser',
                'type' => 'INNER',
                'conditions' => array('Cart.staff_user_id  = StaffUser.id')
            )),"fields"=>$fields,"conditions"=>$conditions,"group"=>"StaffUser.id"));

        if(!empty($data_array)){
            foreach ($data_array as $key => $cart){
               $ismailsent= $this->getEmailTemplate($cart);
               if($ismailsent&&empty($carttype)){
                    $update_data['Cart']['id']=$cart['Cart']['id'];
                    $update_data['Cart']['sent_time']=$cart['Cart']['sent_time']+1;
                    $update_data['Cart']['next_email']=date('Y-m-d', strtotime('+14 days'));
                    if($cart['Cart']['sent_time'] < 2){ 
                        $this->Cart->save($update_data);
                    }
                }
            }
        } 

        // if($ismailsent){
        //     echo "1";
        // }
        echo "1";
        die;
    }
    
    private function getpr($catIds='',$msaIds=''){ 
    	 	$this->PressRelease->recursive=-1;
    	 	//,'release_date'=>date('Y-m-d') when development is finished put in release_date  
	    	$prConditions =['PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'),'OR'=>['Msa.msa_id'=>$msaIds,'CategoryPressRelease.category_id'=>$catIds] ];
            $fields=['PressRelease.id','PressRelease.country_id','PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','release_date'];
    	 	$data=$this->PressRelease->find("all",array(
    	 	 	'joins' => array(
			            array(
			                'table' => 'categories_press_releases',
			                'alias' => 'CategoryPressRelease',
			                'type' => 'INNER',
			                'conditions' => array( 
			                    'CategoryPressRelease.press_release_id = PressRelease.id'
			                )
			            ),
			            array(
			                'table' => 'msas_press_releases',
			                'alias' => 'Msa',
			                'type' => 'INNER',
			                'conditions' => array( 
			                    'Msa.press_release_id = PressRelease.id'
			                )
			            )
			        ),'conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields, 'order' => 'PressRelease.release_date DESC'));
    }

    public function sendmailtosubscriber(){
    	  $this->loadModel('NewsletterMailList');  
    	  $this->NewsletterMailList->bindModel(
		        array('belongsTo' => array(
		                'PressRelease' => array(
		                    'className' => 'PressRelease',
		                    'foreignKey' => 'press_release_id'
		                )
		            )
		        )
		    );
    	  
    	$fields=['NewsletterMailList.id','staff_user_id','subscriber_email','press_release_id','PressRelease.title','PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.body']; 
    	$subscribers =$this->NewsletterMailList->find('all',array("fields"=>$fields,'conditions'=>['send_date'=>date('Y-m-d'),'is_mail_sent'=>0]) );
    	if(!empty($subscribers)){
	    	foreach ($subscribers as $key => $data) {
	    		$user_id=$data['NewsletterMailList']['staff_user_id'];
	    		$unsubscribeUrl=$this->Custom->genrateSubscriberToken("unsubscribe-newsletter",$user_id);
	    		
                $this->AWSSES->to = $data['NewsletterMailList']['subscriber_email'];
		        $this->AWSSES->subject =strip_tags($data['PressRelease']['title']);
		       
                $this->AWSSES->htmlMessage =$data['PressRelease']['body'].'<p style="text-align: center"><a href="'.$unsubscribeUrl.'" target="_blank" rel="noreferrer">Click here to unsubscribe</a></p>';
		       
                if ($this->AWSSES->_aws_ses() ){ 
		           $this->NewsletterMailList->id=$data['NewsletterMailList']['id'];
	    		   $this->NewsletterMailList->saveField('is_mail_sent','1');
                   if(!empty($user_id)){
    		   		   $savedata["StaffUser"]['id']=$user_id;
    	               $savedata['StaffUser']['newsletter_send_mail_date']=$this->Custom->get_newsletter_sendmail_date($user_id);
    	               $this->StaffUser->save($savedata);
                   }
		        }
	    	}
    	}
    	die;
    }


    public function get_subscriber_press_release($msaIds='',$categoriesIds,$user){
            $html="";
            $prIds=[]; 
            $user_id=$user['id'];
            $useremail=$user['email'];
            $cycle=$user['newsletter_cycle'];
            $alreadySent=$this->getAlreadySentPrIds($user_id,$cycle);
            $lastSentdate=$this->Custom->get_last_mail_sent_date($cycle);
            $today=date('Y-m-d');
            $prfields=['PressRelease.id','PressRelease.title','PressRelease.slug'];
            if(!empty($msaIds)&&!empty($categoriesIds)){
                $prConditions=['PressRelease.status'=>1,'PressRelease.id !='=>$alreadySent,'PressRelease.release_date BETWEEN ? and ?' => array($lastSentdate, $today),'OR'=>['Msa.msa_id'=>$msaIds,'CategoryPressRelease.category_id'=>$categoriesIds]];
            }else if(!empty($msaIds)){
                $prConditions=['PressRelease.status'=>1,'Msa.msa_id'=>$msaIds,'PressRelease.id !='=>$alreadySent,'PressRelease.release_date BETWEEN ? and ?' => array($lastSentdate, $today)];
            }else if(!empty($categoriesIds)){
                $prConditions=['PressRelease.status'=>1,'CategoryPressRelease.category_id'=>$categoriesIds,'PressRelease.id !='=>$alreadySent,'PressRelease.release_date BETWEEN ? and ?' => array($lastSentdate, $today)];
            }

            $prs=$this->PressRelease->find("all",array(
                    'joins' => array(
                        array(
                            'table' => 'categories_press_releases',
                            'alias' => 'CategoryPressRelease',
                            'type' => 'LEFT',
                            'conditions' => array( 
                                'CategoryPressRelease.press_release_id = PressRelease.id'
                            )
                        ),
                        array(
                            'table' => 'msas_press_releases',
                            'alias' => 'Msa',
                            'type' => 'LEFT',
                            'conditions' => array( 
                                'Msa.press_release_id = PressRelease.id'
                            )
                        )
                    ),'conditions' => $prConditions,'fields'=>$prfields,
                    'contain' =>[
                        'Category' => ['fields' =>['id','name']],
                        // 'Msa' =>['fields' => ['id','name'] ]
                    ],
                    'group' => 'PressRelease.id'));

            if($prs){
                $html .="<ul>";
                foreach ($prs as $loop => $pr){
                     $categories=$this->createCatCommaSeprated($pr['Category']);
                    $prIds[$loop]=$pr['PressRelease']['id'];
                    $margin=($loop!=0)?"style='margin-top: 20px;'":"";
                    $html .="<li $margin><a href='".SITEURL."release/".$pr['PressRelease']['slug']."?e=".urldecode($useremail)."&usr=".urldecode('subscriber')."'>".$pr['PressRelease']['title']."</a><p style='margin-top: 10px;'>$categories</p></li>";
                }    
                $html .="</ul>";
            }

        return array("html"=>$html,"prIds"=>$prIds);                    
    }       
    public function getAlreadySentPrIds($user_id,$cycle=''){
        $alreadySent=[];
        $lastSentdate=$this->Custom->get_last_mail_sent_date($cycle);
        $today=date('Y-m-d'); 
        $alreadySent=$this->NewsletterLog->find('list',['conditions'=>['staff_user_id'=>$user_id,'NewsletterLog.created BETWEEN ? and ?' => array($lastSentdate, $today) ],'fields'=>['id','press_release_id']]);
        if(!empty($alreadySent)){
           $alreadySent=array_values($alreadySent);
        }
        return $alreadySent;
    }
    public function get_latest_press_release($user){
           $user_id=$user['id'];
           $useremail=$user['email'];
           $cycle=$user['newsletter_cycle'];
           $alreadySent=$this->getAlreadySentPrIds($user_id,$cycle);
           $lastSentdate=$this->Custom->get_last_mail_sent_date($cycle);
           $today=date('Y-m-d H:i:s');
           $html="";
           $prIds=[];
           $prfields=['PressRelease.id','PressRelease.title','PressRelease.slug'];
           $prs=$this->PressRelease->find("all",[
            'conditions'=>['PressRelease.status'=>"1",'PressRelease.release_date <=' => date('Y-m-d'),'PressRelease.id !='=>$alreadySent,'PressRelease.release_date BETWEEN ? and ?' => array($lastSentdate, $today)],
             'contain' =>['Category' => ['fields' =>['id','name']],],
            'fields'=>$prfields,    
            'limit' => Configure::read('Site.paging'),'order'=>"PressRelease.release_date DESC"]);
          if($prs){
                $html .="<ul>";
                foreach ($prs as $loop => $pr){
                    $categories=$this->createCatCommaSeprated($pr['Category']);

                    $prIds[$loop]=$pr['PressRelease']['id'];
                    $margin=($loop!=0)?"style='margin-top: 20px;'":"";
                    $html .="<li $margin><a href='".SITEURL."release/".$pr['PressRelease']['slug']."?e=".urldecode($useremail)."'>".$pr['PressRelease']['title']."</a><p style='margin-top: 10px;'>$categories</p></li>";
                }    
                $html .="</ul>";
            }        
        return array("html"=>$html,"prIds"=>$prIds); 
    }

    public function createCatCommaSeprated($categories=''){
        $comma=$category="";
        if(!empty($categories)){
            foreach ($categories as $key => $cat){
             $category .=$comma."<strong>".$cat['name']."</strong>";
             $comma=',';
            }
        }
        return $category;
    }
   public function sendmailtosubscribers($cycle='d'){

        if(isset($_GET['cycle']) && !empty($_GET['cycle'])){
            $cycle = $_GET['cycle'];
        }

        $this->loadModel('NewsletterLog');
        $fields=['StaffUser.id','email','first_name','last_name','newsletter_send_mail_date','StaffUser.newsletter_cycle']; 
        $data_array = $this->StaffUser->find(
            'all',
            [
               'conditions' =>["StaffUser.staff_role_id"=>4,'StaffUser.newsletter_cycle' => $cycle,'StaffUser.email_confirmed'=>"1","StaffUser.status"=>"1","newsletter_subscription"=>"1",'StaffUser.newsletter_send_mail_date <='=>date('Y-m-d',strtotime('+1 days'))],
                'fields'=>$fields,
                'contain' =>[
                    'Category' => ['fields' =>['id']],
                    'Msa' =>['fields' => ['id'] ]
                ]
            ],

        ); 
        foreach ($data_array as $key =>$subscribers){ 
            $issent=false;
            if($subscribers['Category']){
                $categoriesIds=[];
                foreach ($subscribers['Category'] as $catIndex => $category){
                    $categoriesIds[$catIndex]=$category['id'];
                }
            }
            if($subscribers['Msa']){
                $msaIds=[];
                foreach ($subscribers['Msa'] as $msaIndex => $msa){
                    $msaIds[$msaIndex]=$msa['id'];
                }
            }
            if(!empty($subscribers['Category']) || !empty($subscribers['Msa'])){
                  $presslisthtml=$this->get_subscriber_press_release($msaIds,$categoriesIds,$subscribers['StaffUser']);
                  
            }else{
                  $presslisthtml=$this->get_latest_press_release($subscribers['StaffUser']);
            }
            if(!empty($presslisthtml['html'])){
            	$sent_emails_check = $this->NewsletterLog->find('first',array('conditions' => array('NewsletterLog.staff_user_id' => $subscribers['StaffUser']['id'],'NewsletterLog.press_release_id' => $presslisthtml['prIds'][0])));

                if(!$sent_emails_check){
                	$issent=$this->process_sendmailtosubscribers($subscribers['StaffUser'],$presslisthtml['html']);
                }
              }
           if(!empty($subscribers['StaffUser']['id'])){
               $savedata["StaffUser"]['id']=$subscribers['StaffUser']['id'];
               $savedata['StaffUser']['newsletter_send_mail_date']=$this->Custom->get_newsletter_sendmail_date($subscribers['StaffUser']['id']);
               $this->StaffUser->save($savedata);

                if($issent==true && !empty($presslisthtml['prIds'])){
                    foreach ($presslisthtml['prIds'] as $key => $ids) { 
                        $logs['NewsletterLog']['press_release_id']=$ids;
                        $logs['NewsletterLog']['staff_user_id']=$subscribers['StaffUser']['id'];
                        $logs['NewsletterLog']['is_mail_sent']=1;
                        $this->NewsletterLog->create();
                        $this->NewsletterLog->save($logs);
                    }
               }elseif($issent==false && !empty($presslisthtml['prIds'])){
                    foreach ($presslisthtml['prIds'] as $key => $ids) { 
                        $logs['NewsletterLog']['press_release_id']=$ids;
                        $logs['NewsletterLog']['staff_user_id']=$subscribers['StaffUser']['id'];
                        $logs['NewsletterLog']['is_mail_sent']=0;
                        $this->NewsletterLog->create();
                        $this->NewsletterLog->save($logs);
                    }
                }
           }
        }
        die;
   }

   public function process_sendmailtosubscribers($user,$presslisthtml){ 
            $this->loadModel('EmailTemplate');
            $unsubscribeUrl=$this->Custom->genrateSubscriberToken("unsubscribe-newsletter",$user['id']);
            $email = $this->EmailTemplate->selectTemplate('subscriber-newsletter');
            $emailFindReplace = array(
                    '##USER_NAME##' =>ucfirst($user['first_name']).' '.$user['last_name'],
                    '##SITE_NAME##' => Configure::read('Site.name'), 
                    '##PRESS_RELEASE_LIST##' =>$presslisthtml,
                    '##UNSUBSCRIBELINK##' => $unsubscribeUrl,
                    '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),
                    '##FROM_EMAIL##' => $email['from'],
                    '##SITE_LOGO##' => Router::url(array(
                        'controller' => 'img',
                        'action' => '/',
                        'logo.png',
                        'admin' => false
                            ), true)
                );

            // $this->AWSSES->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
            $this->AWSSES->from =$email['title']." <".$email['from'].">";
            $this->AWSSES->to =  $user['email'];
            $this->AWSSES->subject = $email['subject'];
            $this->AWSSES->replayto=$email['reply_to_email'];
            $this->AWSSES->htmlMessage=strtr($email['description'], $emailFindReplace);

            $flg=true;
            if(!$this->AWSSES->_aws_ses()){
                $this->Email->from =$email['title']." <".$email['from'].">";
                $this->Email->replyTo =$email['reply_to_email'];
                $this->Email->to = $user['email'];
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';   
                $description=strtr($email['description'], $emailFindReplace);
                
                if(!$this->Email->send($description)){
                    $flg=false;
                }
            }
            return $flg;
    } 

    public function updateClippingByFcApis(){
    	$this->loadModel('NetworkWebsite');
        $this->loadModel('NwRelationship');

        /* -------- start foreach ----------  */
        $today_date = date("Y-m-d");
        // $PressRelease = $this->PressRelease->find('all',array('conditions' => array('PressRelease.release_date' => $today_date),'fields'=>array('id')));
        // if(isset($PressRelease) && !empty($PressRelease)){
        //     foreach($PressRelease as $pr){
            //    $pr_id= $pr['PressRelease']['id']; 
               $pr_id= 63; 
               $api_url = [];
               $api_url[] ='https://markets.financialcontent.com/emailwire?Module=clipping-all&SourceID=https%3A%2F%2Femailwire.com%2Ffeeds%2Fpr_feeds_finance_rss.php%3FprID%3D1119846';
               $api_url[] ='https://tracking.newsrpm.com/sites?provider=emailwire&network=marketminute&sourceId=https://emailwire.com/feeds/pr_feeds_finance_rss.php?prID=1119846&T=4smfwwe8dga';
               $api_url[] ='https://tracking.newsrpm.com/sites?provider=emailwire&network=barchart&sourceId=https://emailwire.com/feeds/pr_feeds_finance_rss.php?prID=1119846&T=4smfwwe8dga';
               $api_url[] ='https://tracking.newsrpm.com/sites?provider=emailwire&network=synacor&sourceId=https://emailwire.com/feeds/pr_feeds_finance_rss.php?prID=1119846&T=4smfwwe8dga';
               $i=1;
               foreach($api_url as $key =>$url){
                   if($key == 0)
                   {
                    $rss_feed = simplexml_load_file($url);
					if(!empty($rss_feed)) {
			        	foreach ($rss_feed->channel->item as $feed_item) {

                            // echo "<pre>";
                            // print_r($feed_item);
                            // echo "<pre>";

                            $parse_link = parse_url($feed_item->link,PHP_URL_HOST);
                            $exist_data=$this->NetworkWebsite->find('first',array("conditions"=>array('website_domain LIKE'=>$parse_link)));

                            $NwRelationship = $this->NwRelationship->find('list',array('conditions' => array('NwRelationship.press_release_id =' =>$pr_id, 'NwRelationship.press_release_link =' => $feed_item->link)));                       
                            $savedata= [];
                            if(count($NwRelationship)<1){

                                if(count($exist_data)<1){

                                    $city = [];
                                    $state = [];
                                    $mediaType = [];
                                    $logo = [];
                                    $region = [];

                                    $feed_item->registerXPathNamespace('p', 'http://www.financialcontent.com');
                                    $city = $feed_item->xpath('//item['.$i.']/p:City');
                                    $state = $feed_item->xpath('//item['.$i.']/p:State');
                                    $mediaType = $feed_item->xpath('//item['.$i.']/p:MediaType');
                                    $logo = $feed_item->xpath('//item['.$i.']/p:Logo');
                                    $region = $feed_item->xpath('//item['.$i.']/p:Region');
        
                                    $host = parse_url($feed_item->link,PHP_URL_HOST);
                                    $host = str_ireplace('www.', '', $host);
                                    $host = str_ireplace('.cm', '', $host);
                                    $host = preg_split('/(?=\.[^.]+$)/', $host);
                                    
                                    $this->NwRelationship->create();
                                    $nw_id=0;
                                    $savedata['NwRelationship']['network_website_id']=$nw_id;
                                    $savedata['NwRelationship']['press_release_id']=$pr_id;
                                    $savedata['NwRelationship']['press_release_link']=$feed_item->link;
                                    $savedata['NwRelationship']['site_name']=$feed_item->name;
                                    if(isset($city) && !empty($city)){
                                        $savedata['NwRelationship']['location']=$city[0].', '.$state[0];
                                    }
                                    $savedata['NwRelationship']['actual']=$logo[0];

                                 
                                    $filename = $logo[0];
                                    $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                                    
                                    if($img_ext=="png")
                                    {   $company_logo_path=$filename;
                                        // print_r($company_logo_path);die;

                                        if(empty(is_dir(WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y')))){
                                            mkdir(WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y'));
                                            }
                                            if(empty(is_dir(WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y').DS.date('m')))){
                                            mkdir(WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y').DS.date('m'));
                                            }
                                            $path_info=WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y').DS.date('m').DS.$feed_item->name."_".$pr_id."_".$nw_id.".jpg";
                                        // $path_info = WWW_ROOT."files/clippingreportsnw/".$feed_item->name."_".$pr_id."_".$nw_id.".jpg";
                                        png_to_jpg($company_logo_path,$path_info, 100);
                                    }


                                    if(isset($logo) && !empty($logo)){
                                        $url_exists=does_url_exists($url);
                                        if($url_exists=="yes")
                                        {   if($img_ext=="png")
                                            {
                                                $db_path_info= WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y').DS.date('m').DS.$feed_item->name."_".$pr_id."_".$nw_id.".jpg";
                                                $savedata['NwRelationship']['site_logo']=$db_path_info;
                                            }else
                                            {   $savedata['NwRelationship']['site_logo']=$logo[0];
                                                
                                            }
                                           
                                        }else
                                        {
                                            $savedata['NwRelationship']['site_logo']="files/networkwebsite/blank.jpg";
                                        }
                                       
                                    }
                                    else{
                                        $savedata['NwRelationship']['site_logo']="files/networkwebsite/blank.jpg";
                                    }
                                    $savedata['NwRelationship']['type']="Internet media";
                                    
                                    // echo "----<br>if condition";
                                    // echo "<pre>";
                                    // print_r($savedata);
                                    // echo "</pre>";
                                    // echo "if condition<br>----";
                                    $this->NwRelationship->save($savedata);
                                    $i++;
                                
                                }else
                                {
                                    $this->NwRelationship->create();
                                    $nw_id=0;
                                    $savedata['NwRelationship']['network_website_id']=$nw_id;
                                    $savedata['NwRelationship']['press_release_id']=$pr_id;
                                    $savedata['NwRelationship']['press_release_link']=$feed_item->link;
                                    $savedata['NwRelationship']['site_name']=$exist_data['NetworkWebsite']['website_name'];
                                    $savedata['NwRelationship']['location']=$exist_data['NetworkWebsite']['website_location'];
                                    $savedata['NwRelationship']['site_logo']="files/networkwebsite/".$exist_data['NetworkWebsite']['website_logo'];
                                    $savedata['NwRelationship']['type']=$exist_data['NetworkWebsite']['website_media_type'];
                                    $savedata['NwRelationship']['potential_audience']=$exist_data['NetworkWebsite']['potential_audience'];
                                    $savedata['NwRelationship']['from_db']=1;
                                    // echo "----<br>else condition";
                                    // echo "<pre>";
                                    // print_r($savedata);
                                    // echo "</pre>";
                                    // echo "if condition<br>----";
                                    $this->NwRelationship->save($savedata);
                                    $i++;
                                    
                                }
                               
                           
                                
                            }
			        	}
					}
                   }else {
                    $json_data = file_get_contents($url);
                    $response_data = json_decode($json_data);
                    $site_data = $response_data->sites;
                    if(count($site_data)>0){
                        foreach($site_data as $site){

                            // echo "<pre>";
                            // print_r($site);
                            // echo "<pre>";
                           
                            $parse_link = parse_url($site->link,PHP_URL_HOST);
                            $exist_data=$this->NetworkWebsite->find('first',array("conditions"=>array('website_domain LIKE'=>$parse_link)));

                            $NwRelationship = $this->NwRelationship->find('list',array('conditions' => array('NwRelationship.press_release_id =' =>$pr_id, 'NwRelationship.press_release_link =' => $site->link)));                       
                            $savedata= [];
                            if(count($NwRelationship)<1){

                                if(count($exist_data)<1){
                                    
                                    $host = parse_url($site->link,PHP_URL_HOST);
                                    $host = str_ireplace('www.', '', $host);
                                    $host = str_ireplace('.cm', '', $host);
                                    $host = preg_split('/(?=\.[^.]+$)/', $host);
                                    $this->NwRelationship->create();
                                    $nw_id=0;
                                    $savedata['NwRelationship']['network_website_id']=$nw_id;
                                    $savedata['NwRelationship']['press_release_id']=$pr_id;
                                    $savedata['NwRelationship']['press_release_link']=$site->link;
                                    $savedata['NwRelationship']['site_name']=$site->name;
                                    if(isset($site->city) && !empty($site->city)){
                                        $savedata['NwRelationship']['location']=$site->city.', '.$site->state;
                                    }
                                    $savedata['NwRelationship']['actual']=$site->logo;

                                    $filename = $site->logo;
                                    $img_ext = pathinfo($filename, PATHINFO_EXTENSION);
                                    
                                    if($img_ext=="png")
                                    {   $company_logo_path=$filename;
                                        // print_r($company_logo_path);die;
                                        if(empty(is_dir(WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y')))){
                                            mkdir(WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y'));
                                            }
                                            if(empty(is_dir(WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y').DS.date('m')))){
                                            mkdir(WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y').DS.date('m'));
                                            }
                                            $path_info=WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y').DS.date('m').DS.$site->name."_".$pr_id."_".$nw_id.".jpg";
                                        png_to_jpg($company_logo_path,$path_info, 100);
                                    }
                                    
                               
                                    if(isset($site->logo) && !empty($site->logo)){
                                        // $savedata['NwRelationship']['site_logo']=$site->logo;

                                        $url_exists=does_url_exists($site->logo);
                                        if($url_exists=="yes")
                                        {    if($img_ext=="png")
                                            {    $db_path_info=WWW_ROOT.'files'.DS.'clippingreportsnw'.DS.date('Y').DS.date('m').DS.$site->name."_".$pr_id."_".$nw_id.".jpg";
                                                $savedata['NwRelationship']['site_logo']=$db_path_info;
                                            }else
                                            {
                                                $savedata['NwRelationship']['site_logo']=$site->logo;
                                            }
                                            
                                        }else
                                        {
                                            $savedata['NwRelationship']['site_logo']="files/networkwebsite/blank.jpg";
                                        }
                                    }
                                    else{
                                        $savedata['NwRelationship']['site_logo']="files/networkwebsite/blank.jpg";
                                    }
                                    $savedata['NwRelationship']['type']="Internet media";

                                    // echo "----<br>next if condition";
                                    // echo "<pre>";
                                    // print_r($savedata);
                                    // echo "</pre>";
                                    // echo "if condition<br>----";
                                    $this->NwRelationship->save($savedata);
                                
                                
                                }else{
                                 
                              
                                    $this->NwRelationship->create();
                                    $nw_id=0;
                                    $savedata['NwRelationship']['network_website_id']=$nw_id;
                                    $savedata['NwRelationship']['press_release_id']=$pr_id;
                                    $savedata['NwRelationship']['press_release_link']=$site->link;
                                    $savedata['NwRelationship']['site_name']=$exist_data['NetworkWebsite']['website_name'];
                                    $savedata['NwRelationship']['location']=$exist_data['NetworkWebsite']['website_location'];
                                    $savedata['NwRelationship']['site_logo']="files/networkwebsite/".$exist_data['NetworkWebsite']['website_logo'];
                                    $savedata['NwRelationship']['type']=$exist_data['NetworkWebsite']['website_media_type'];
                                    $savedata['NwRelationship']['potential_audience']=$exist_data['NetworkWebsite']['potential_audience'];
                                    $savedata['NwRelationship']['from_db']=1;

                                    // echo "----<br>next else condition";
                                    // echo "<pre>";
                                    // print_r($savedata);
                                    // echo "</pre>";
                                    // echo "else condition<br>----";
                                    $this->NwRelationship->save($savedata);

                                }

        
                            }
                        }
                    }

                   }
   
            //    }
            // } 
        }
        echo 'done';die;
    }


    function getDomain($url){
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)){
            return $regs['domain'];
        }
        return FALSE;
    }
    
    
    function does_url_exists($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        if ($code == 200) {
            $status = "yes";
        } else {
            $status = "no";
        }
        curl_close($ch);
        return $status;
    }
    
    function png_to_jpg($company_logo_path, $company_logo_path1, $quality) {
        $image = imagecreatefrompng($company_logo_path);
        list($width, $height) = getimagesize($company_logo_path);
        $output = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($output,  255, 255, 255);
        imagefilledrectangle($output, 0, 0, $width, $height, $white);
        imagecopy($output, $image, 0, 0, 0, 0, $width, $height);
        imagejpeg($output, $company_logo_path1, $quality);
        imagedestroy($image);
      }

      /**
       * Test of image resize module
       *
       * @return void
       */
      function testImgMagicModule($pressReleaseId=null){
        if($this->Custom->rzImagePressRelease($pressReleaseId)){
            echo ":done";
        }else{
            echo "Image not Resized";
        }
        exit;
      }
    
      /**
       * Test mail server for email 
       *
       * @return void
       */
    function testmail(){
        /*
                App::import('Vendor', 'Swift', array('file' => 'SwiftMailer' . DS .'vendor'.DS .'autoload.php'));

                $transport = (new Swift_SmtpTransport('mail.devsite.emailwire.com', 587))
                ->setUsername('smtp@devsite.emailwire.com')
                ->setPassword('WPiO[Z@n,M23')->setEncryption('tls');
        
                $mailer = new Swift_Mailer($transport);
                $message = (new Swift_Message('Wonderful Subject'))->setFrom(['devsite@emailwire.com' => 'EmailWire'])->setTo(['hitesh.verma0@gmail.com'=>"Hitesh", 'testdevlopertest123@gmail.com' => 'A name'])->setBody('Here is the message itself');
        

                
                $result = $mailer->send($message);
        var_dump($result);die;*/

            App::uses('CakeEmail', 'Network/Email');

            $currency = Configure::read('Site.currency');

            $Email = new CakeEmail('default');

            $Email->from(array('devsite@emailwire.com' =>"Email Wire testing"));

            $Email->to('testdevlopertest123@gmail.com');

            $Email->replyTo('info@emailwire.com');

            $Email->subject('Email Wire testing');

            $Email->emailFormat('html');

            pr($Email->send("Default configration --"));
                echo "SDf";die;
    } 
     /*
     * @params: 
     * @Function use: updateClippingByGroupMediaNetwork: Clipping report by Group media network xml
     * @created by: Hitesh verma
     * @Created: 21-10-2022
     */
    public function updateClippingByGroupMediaNetwork(){
        $log_file= WWW_ROOT.DS.'tmp'.DS.'logs'.DS."gmn_xml.log";
    	$this->loadModel('NetworkWebsite');
    	$this->loadModel('NwRelationship');
		$NetworkWebsite = $this->NetworkWebsite->find('all',["conditions"=>['xml_link IS NOT NULL','last_ran_cron <='=>date('Y-m-d H:i:s')]]);
      	if(isset($NetworkWebsite) && !empty($NetworkWebsite)){
			foreach ($NetworkWebsite as $key => $nwdata) {
				$nw_id = $nwdata['NetworkWebsite']['id'];
				$webXmlLink = $nwdata['NetworkWebsite']['xml_link'];

				if(isset($webXmlLink) && !empty($webXmlLink)){
    				$rss_feed = simplexml_load_file($webXmlLink); 
					if(!empty($rss_feed&&!empty($rss_feed->channel->item))) {
                        $totalSavedCount = $this->NwRelationship->find('count', array("conditions" => array('NwRelationship.press_release_id' => $prId)));
			        	foreach ($rss_feed->channel->item as $item) { 
                            // echo "<pre>";
                            // print_r($nwdata);
			        		$title = (strtok($item->title,"|")===true)? explode("|", $item->title, 2):$item->title;
                            $title =(is_array($title))?$title[0]:$title;
                            $this->PressRelease->recursive=-1;
			        		$pressRelease = $this->PressRelease->find('first',array('conditions' => array('PressRelease.title' =>$title,'PressRelease.status' =>1,'PressRelease.release_date <=' => date('Y-m-d')),'fields'=>array('id','title')));
                           
					    	if(isset($pressRelease) && !empty($pressRelease)){
                                $press_release_id = $pressRelease['PressRelease']['id'];
                                $press_release_title = $pressRelease['PressRelease']['title'];
                                $NwRelationship = $this->NwRelationship->find('count',array('conditions' => array('NwRelationship.press_release_id =' => $press_release_id, 'NwRelationship.network_website_id =' => $nw_id)));
                                if(empty($NwRelationship)){
                                    
                                    $savedata['NwRelationship']['id'] = '';
                                    $savedata['NwRelationship']['network_website_id']=$nw_id;
                                    $savedata['NwRelationship']['press_release_id']=$press_release_id;
                                    $savedata['NwRelationship']['press_release_link']=(string)$item->link;
                                    $savedata['NwRelationship']['site_logo']="files/networkwebsite/".$nwdata['NetworkWebsite']['website_logo'];
                                    $savedata['NwRelationship']['potential_audience']=$nwdata['NetworkWebsite']['potential_audience'];
                                    $savedata['NwRelationship']['site_name']=$nwdata['NetworkWebsite']['website_name'];
                                    $savedata['NwRelationship']['location']=$nwdata['NetworkWebsite']['website_location'];
                                    $savedata['NwRelationship']['type']=$nwdata['NetworkWebsite']['website_media_type'];
                                    $savedata['NwRelationship']['type']=$nwdata['NetworkWebsite']['website_media_type'];
                                    $savedata['NwRelationship']['order_num'] = ++$totalSavedCount; // only new insert
                                    $this->NwRelationship->create();
                                    $this->NwRelationship->save($savedata);
                                }
					        }
                          
			        	}
					}else{
                    //    error_log(date('[Y-m-d H:i e] '). "item not found : $webXmlLink" . PHP_EOL, 3, $log_file);
                    }
				}else{
                  //  error_log(date('[Y-m-d H:i e] '). "webXmlLink not found" . PHP_EOL, 3, $log_file);
                }
                $sv['NetworkWebsite']['id']=$nw_id;
                $sv['NetworkWebsite']['last_ran_cron']=date("Y-m-d")." 23:59:59";
                $this->NetworkWebsite->save($sv); 
                sleep(2);  // Time interval to read on each xml
                
			}
		}

        echo 'done'; die;
    }

}

