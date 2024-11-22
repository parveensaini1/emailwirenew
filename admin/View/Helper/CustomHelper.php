<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Helper', 'View/Helper');

class CustomHelper extends AppHelper {
        public $helpers = array('Html', 'Form');
        public function getredirect_action($staff_role_id=''){
            switch ($staff_role_id) {
                case '1':
                $redirect="administrators";
                break;
                case '2':
                $redirect="editors";
                break;
                case '3':
                $redirect="clients";
                break; 
                default:
                $redirect="subscribers"; 
                break;
            }
            return $redirect;
        }    
      public function getUserStatus($status) {
        switch ($status) {
            case '1':
            $status = '<span class="label label-success">Approved</span>';
            break;
            case '2':
            $status = '<span class="label light-green">Suspended</span>';
            break;
            case '3':
            $status = '<span class="label label-danger">Disapproved</span>';
            break;
            default:
            $status = '<span class="label label-warning">Pending</span>';
            break;
        } 
        return $status;
    }

    public function docIcon($file){ 
        if(empty($file)){
            $document="<img style='width: 60%;' src='". SITEURL.'img/no-document-icon.png'."'>";
         }else{
            $fileTypeArr=explode("/",$this->mime_content_type($file));
            $file_type=$fileTypeArr['0'];
            if($file_type=='application'){
              $file_type=$fileTypeArr['1'];
            }
            switch ($file_type) {
                case 'image':  
                $document="<i style='font-size: 40px; color: rgb(255, 77, 77);' class='far fa fa-file-image'></i>";
                break;
                case 'pdf':
                $document="<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa far fa fa-file-pdf'></i>";
                break;
                case 'msword':
                $document="<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa far fa-file-word'></i>";
                break;
                case 'docx':
                $document="<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa far fa-file-word'></i>";
                break;
                default:
                $document="<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa far fa-file'></i>";
            }
         }
         return $document;
    }

    public function getEmailStatus($status) {
        switch ($status) {
            case '1':
            $status = '<span class="label label-success">Confirmed</span>';
            break; 
            default:
            $status = '<span class="label label-warning">Pending</span>';
            break;
        } 
        return $status;
    }

    public function statusActiveInactive($status) {

        if ($status) {

            $status = '<span class="label label-success">Activated</span>';
        } else {

            $status = '<span class="label label-warning">InActive</span>';
        }

        return $status;
    }
    
    public function getCheckBoxStatus($id,$status) {
        if ($status) {
            $status = '<a href="'.SITEURL.'socialShares/change_status/'.$id.'/'.$status.'"><i class="text-success fa fa-check-square-o"></i></a>';
        } else {
            $status ='<a href="'.SITEURL.'socialShares/change_status/'.$id.'/'.$status.'" ><i class="fa fa-square"></i></a>';
        }
        return $status;
    }


    public function array_equal($required, $data) {
        if (count(array_intersect_key(array_flip($required), $data)) === count($required)) {

            return 1;
        } else {

            return 0;
        }
    }
 
    public function month_select_box($field_name = 'month', $year = null, $month) {

        $month_options = '';

        for ($i = 1; $i <= 12; $i++) {

            $month_num = str_pad($i, 2, 0, STR_PAD_LEFT);

            $selected = '';

            if ($month_num == $month) {
                $selected = "selected=selected";
            }

            $month_name = date('F', mktime(0, 0, 0, $i + 1, 0, 0, 0));

            // $month_name = date('F',  strtotime($i));

            $month_options .= "<option $selected  value='" . $year . '-' . $month_num . "'>" . $month_name . ", " . $year . '</option>';
        }

        return '<select onchange="get_cal(this.value);" class="form-control" name="' . $field_name . '">' . $month_options . '</select>';
    }

    public function get_date($source = null) {

        if ($source == '') {

            $source = date('Y-m-d');
        }

        $date = new DateTime($source);

        return $date->format($this->get_date_format());
    }

    public function change_date($source = null) {

        $date = new DateTime($source);

        return $date->format('Y-m-d');
    }

    public function get_cake_date_format() {

        $d = str_split(str_replace('-', '', Configure::read('Site.DateFromat')));

        $d = array_unique($d);

        return strtolower(implode('', $d));
    }

    public function get_date_format() {

        $d = str_split(str_replace('-', '', Configure::read('Site.DateFromat')));

        $d = array_unique($d);

        $d = strtolower(implode('-', $d));

        return str_replace('y', 'Y', $d);
    }

    public function item_status($status) {

        switch ($status) {

            case '0':

                return '<button class="btn btn-xs btn-warning"><i class="fa fa-fw fa-clock-o"></i> Pending</button>';

                break;

            case '1':

                return '<button class="btn btn-xs btn-success"><i class="fa fa-fw fa-check"></i> Confirm</button>';

                break;

            case '2':

                return '<button class="btn btn-xs btn-danger"><i class="fa fa-fw fa-close"></i> Cancel</button>';

                break;
        }
    }

    /* -------------------------------------- */

    public function get_status($status) {

        switch ($status) {

            case 1:

                return '<span class="label label-success">Active</span>';

                break;

            case 0:

                return '<span class="label label-danger">InActive</span>';

                break;
        }
    }

    public function CountryNameById($countryId=null) {
        $obj = ClassRegistry::init('Country');
        $country=$obj->find('first', array('fields' => array('Country.name'),'conditions' => array('Country.id' => $countryId,)));
        return $country['Country']['name'];
    }

    public function organizationTypeById($orgId=null) {
        $obj = ClassRegistry::init('OrganizationType');
        $org=$obj->find('first', array('fields' => array('OrganizationType.name'),'conditions' => array('OrganizationType.id' => $orgId,)));
        return $org['OrganizationType']['name'];
    }
   
 
  

    
    public function plan_type(){
        $type=[];
        $type['single']="Single";
        $type['bulk']="Bulk";
        $type['subscription']="Subscription";
        return $type;

    } 

    public function cycle_period(){
        $cycle_period=array("monthly"=>"Monthly","yearly"=>"Yearly","halfyearly"=>"Half yearly","quarterly"=>"Quarterly");
        return $cycle_period;

    }

    public function getCategoryCount() {
        $obj = ClassRegistry::init('Category');
        $count=$obj->find('count', array('conditions' => array('Category.parent_id !=' =>'0','Category.status' =>'1')));
        return $count;
    }

    public function getMSACount() {
        $obj = ClassRegistry::init('Msa');
        $count=$obj->find('count', array('conditions' => array('Msa.status' =>'1')));
        return $count;
    }

     public function getStateCount() {
        $obj = ClassRegistry::init('State');
        $count=$obj->find('count', array('conditions' => array('State.status' =>'1')));
        return $count;
    }
    public function checkNewsroomIncart($user_id=''){
        $Cart = ClassRegistry::init('Cart');
        $checkcart=$Cart->find('count',array('conditions'=>array('staff_user_id'=>$user_id,'is_newsroom_incart'=>1,'cart_type'=>'plan')));
        return $checkcart;
    }
    
    public function getprcartdata($user_id='',$plan_id=''){
            $Cart = ClassRegistry::init('Cart');
            $obj = ClassRegistry::init('Plan');
            $index="0";
            $cart_plans['feature']= $cart_plans['prlist']=$cart_plans=[];
            $cart_plans['totals']['subtotal']=$cart_plans['totals']['discount']=$cart_plans['totals']['tax']=$cart_plans['totals']['total']=$famount=$tax=$discount=$plan_amount='0.00';
            $currency=Configure::read('Site.currency');
            $checkcart=$Cart->find('first',array('conditions'=>array('staff_user_id'=>$user_id,'plan_id'=>$plan_id,'cart_type'=>'pr')));
                if($checkcart){
                     $plan = $obj->find('first',array('conditions' =>array('Plan.id'=>$plan_id)));
                    if($checkcart['Cart']['extra_words']>0){  
                        $amt=ceil(($checkcart['Cart']['extra_words']/100))*$plan['Plan']['add_word_amount'];
                        $amount=number_format($amt,2);
                        $cart_plans['prlist'][$index]["plan_id"]=$plan_id;
                        $cart_plans['prlist'][$index]["title"]="Additional charges words";
                        $cart_plans['prlist'][$index]["amount"]=$currency.''.$amount;
                        $plan_amount +=$amount;
                        $index++;
                    }

                    if($checkcart['Cart']['extra_category']>0){  
                        $amt=($checkcart['Cart']['extra_category'])*($plan['Plan']['add_word_amount']);
                        $amount=number_format($amt,2);
                        $cart_plans['prlist'][$index]["plan_id"]=$plan_id;
                        $cart_plans['prlist'][$index]["title"]="Additional charges words";
                        $cart_plans['prlist'][$index]["amount"]=$currency.''.$amount;
                        $plan_amount +=$amt;
                        $index++;
                    }

                    if($checkcart['Cart']['extra_msa']>0){
                        $amtmsa=ceil($checkcart['Cart']['extra_msa']/$plan['Plan']['msa_limit'])*($plan['Plan']['add_msa_charges']);
                        $amountmsa=number_format($amtmsa,2);
                        $cart_plans['prlist'][$index]["plan_id"]=$plan_id;
                        $cart_plans['prlist'][$index]["title"]="Additional msa charges";
                        $cart_plans['prlist'][$index]["amount"]=$currency.''.$amountmsa;
                        $plan_amount +=$amtmsa;
                        $index++;
                    } 

                    if($checkcart['Cart']['extra_state']>0){
                        $amtstate=ceil($checkcart['Cart']['extra_state']/$plan['Plan']['state_limit'])*($plan['Plan']['add_msa_charges']);
                        $amountstate=number_format($amtstate,2);
                        $cart_plans['prlist'][$index]["plan_id"]=$plan_id;
                        $cart_plans['prlist'][$index]["title"]="Additional state charges";
                        $cart_plans['prlist'][$index]["amount"]=$currency.''.$amountstate;
                        $cart_plans['prlist'][$index]["class"]='state_charges';
                        $plan_amount +=$amtstate;
                        $index++;
                    } 

                    if($checkcart['Cart']['translate_charges']>0){
                        $amttx=$plan['Plan']['translation_amount'];
                        $amttx=number_format($amttx,2);
                        $cart_plans['prlist'][$index]["plan_id"]=$plan_id;
                        $cart_plans['prlist'][$index]["title"]="Additional page tranlate charges";
                        $cart_plans['prlist'][$index]["amount"]=$currency.''.$amttx;
                        $cart_plans['prlist'][$index]["class"]='trans_charges';
                        $plan_amount +=$amttx;
                        $index++;
                    } 

                  if(!empty($checkcart['Cart']['distribution_ids'])){
                        $features=unserialize($checkcart['Cart']['distribution_ids']);
                        foreach ($features as $index => $value) {
                           $feature[$index]['distribution_id']=$value['distribution_id']; 
                           $featureData=$this->getprfeatureprice($value['distribution_id']);
                           $cart_plans['feature'][$index]['price']=$featureData['amount'];
                           $cart_plans['feature'][$index]['name']=$featureData['name'];
                           $cart_plans['feature'][$index]['class']='feature-'.$value['distribution_id'];
                           $famount =$famount+$featureData['amount'];
                        } 
                    }

                $plan_amount +=$famount;    
                $cart_plans['totals']['subtotal']=number_format($plan_amount,2);
                $cart_plans['totals']['discount']=number_format($discount,2);
                $cart_plans['totals']['tax']=$tax;
                $cart_plans['totals']['total']=$this->get_cart_total('0',$plan_amount,$discount);
            }    
            return $cart_plans;   
    }

    function getprfeatureprice($featureId=""){
        if(!empty($featureId)){
            $obj = ClassRegistry::init('Distribution');
            $data=$obj->find('first',array('conditions'=>array('id'=>$featureId),'fields'=>array('name','amount')));
           return $data['Distribution'];
        }
    }

    public function get_cart_total($newsroomAmount='0.00',$subtotal,$discount="0.00",$tax="0.00"){
        if($tax>0)
        $tax =$this->taxcalculation($tax);
        $price=round(((($newsroomAmount+$subtotal)-$discount)+$tax),2);
        return ($price>0)?number_format($price,2):"0.00";
    }

    public function checkPrIncart($user_id='',$prId){
        $Cart = ClassRegistry::init('Cart');
        $checkcart=$Cart->find('count',array('conditions'=>array('staff_user_id'=>$user_id,'press_release_id'=>$prId,'cart_type'=>'pr')));
        return $checkcart;
    }

    public function fetchPlanData($plan_id){
        $obj = ClassRegistry::init('Plan');
        $data=$obj->find('first',array('conditions'=>array('Plan.id'=>$plan_id)));
        return $data;
    }

    public function getPRImages($image_path='',$image=''){
        $imageUrl=SITEFRONTURL.'files/company/logo/'.$logo_path.'/'.$logo;
        $fileUrl=WWW_ROOT.'files'.DS.'company'.DS.'logo'.DS.$logo_path.DS.$logo;
        if(!file_exists($fileUrl)){
            $imageUrl=SITEFRONTURL."img/no-logo-provided.png";
        }
        $logo="<img src='".$imageUrl."' width='50px' height='50px' />";
        return $logo;
    }

    public function getEmbedCode($url=''){
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';
        if (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        if (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        $videoIfram="<iframe width='100%' height='300' src='https://www.youtube.com/embed/$youtube_id' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
        return $videoIfram ;
    }
    
     public function createprslug($slug=''){
        return SITEFRONTURL.'release/'.$slug;
    }

    public function getprimage($path='',$name=''){
      
        return SITEFRONTURL.'files/company/press_image/'.$path.'/'.$name;
    }


    public function getCartDataTotals($checkcart=null,$plan=''){
 
        $index="0";
        $cart_plans['feature']= $cart_plans['prlist']=$cart_plans=[];
        $cart_plans['totals']['subtotal']=$cart_plans['totals']['discount']=$cart_plans['totals']['tax']=$cart_plans['totals']['total']=$famount=$tax=$discount=$plan_amount='0.00';
        if($checkcart){ 
            $plan_id=$plan['id'];
            if($checkcart['extra_words']>0){  
                $amt=ceil(($checkcart['extra_words']/100))*$plan['add_word_amount'];
                $amount=number_format($amt,2);
                $plan_amount +=$amount;
                $index++;
            }

            if($checkcart['extra_category']>0){  
                $amt=($checkcart['extra_category'])*($plan['add_word_amount']);
                $amount=number_format($amt,2); 
                $plan_amount +=$amt;
                $index++;
            }

            if($checkcart['extra_msa']>0){
                $amtmsa=ceil($checkcart['extra_msa']/$plan['msa_limit'])*($plan['add_msa_charges']);
                $amountmsa=number_format($amtmsa,2); 
                $plan_amount +=$amtmsa;
                $index++;
            } 

            if($checkcart['extra_state']>0){
                $amtstate=ceil($checkcart['extra_state']/$plan['state_limit'])*($plan['add_state_charges']);
                $amountstate=number_format($amtstate,2); 
                $plan_amount +=$amtstate;
                $index++;
            } 
            
            if($checkcart['translate_charges']>0){
                $amttx=$plan['translation_amount'];
                $amttx=number_format($amttx,2); 
                $plan_amount +=$amttx;
                $index++;
            } 

            if(!empty($checkcart['distribution_ids'])){
                $features=unserialize($checkcart['distribution_ids']);
                foreach ($features as $index => $value) {
                   $feature[$index]['distribution_id']=$value['distribution_id']; 
                   $featureData=$this->getprfeatureprice($value['distribution_id']);
                   $featureAmount=$this->getAmountMailList($checkcart['list_id'],$value['distribution_id'],$featureData['amount'],!empty($featureData['number']));
                   $famount =$famount+$featureAmount;
                } 
            }

            $plan_amount +=$famount;    
            $cart_plans['totals']['subtotal']=number_format($plan_amount,2);
            $cart_plans['totals']['discount']=number_format($discount,2);
            $cart_plans['totals']['tax']=$tax;
            $cart_plans['totals']['total']=$this->get_cart_total('0',$plan_amount,$discount);
        }    
        return $cart_plans;   
    }

    public function getAmountMailList($listId,$distribution_id,$amount,$number){
        if($distribution_id==8&&$listId >0){
            $subscriber = ClassRegistry::init('Subscriber');
            $count=$subscriber->find("count",array('conditions'=>array("Subscriber.list"=>$listId)));
            if($count>$number){
                $amount=ceil($count/$number)*$amount;
            }
            
        }
        return ($amount>0)?number_format($amount,2):"0.00";;
    }

   public function getUserCartData($user_id,$coupon_data='',$newsroom_amount='0.00',$plan_details=''){
        $cartData=[];
        $cartData['plans']=[];
        $cartData["newsroom_amount"]=$newsroom_amount;
        $total= $subtotal=$discount="0.00";
        $data_array=$this->fetchCartData($user_id);
        if(!empty($data_array)){
            foreach ($data_array as $index => $sess_data){
              if($sess_data["is_newsroom_incart"]>0){
                $cartData["newsroom_amount"]=($sess_data["is_newsroom_incart"]>0)?Configure::read('Site.newsroom.amount'):$newsroom_amount;
              }else{
                 $amount=($sess_data['bulk_discount_amount']>0)?$sess_data['bulk_discount_amount']:$sess_data['price'];
             
                 if(empty($plan_details))
                  $plan_details = $this->fetchPlanData($sess_data['plan_id']);

                 if(isset($plan_details) && !empty($plan_details)){
                    $cartData['plans'][$index]["plan_type"]   =   $plan_details['Plan']['plan_type'];
                  }else{
                    $cartData['plans'][$index]["plan_type"]   =   '';
                  }  
                  $cartData['plans'][$index]["plan_id"]=$sess_data['plan_id'];
                  $cartData['plans'][$index]["title"]=$sess_data['name'];
                  $cartData['plans'][$index]["amount"]=$amount;
                  $subtotal +=$amount;  
              }
            }
        } 
        $cartData['totals']["subtotal"]=(($subtotal+$cartData["newsroom_amount"])>0)?round(($subtotal+$cartData["newsroom_amount"]),2):"0.00";
        $cartData["discount_id"]='';
        $cartData["promo_code"]='';
        if(!empty($coupon_data)){
            $cartData["discount_id"]=$coupon_data['Coupon']['id'];
            $cartData["promo_code"]=$coupon_data['Coupon']['code'];
            $discount=$this->getCouponAmount($coupon_data['Coupon']['type'],$coupon_data['Coupon']['value'],$cartData['totals']["subtotal"]); 
        }

        $cartData['totals']["discount"]=round($discount,2);
        $cartData['totals']["tax"]="0.00";
        $cartData['totals']["total"]=$this->get_cart_total($cartData["newsroom_amount"],$subtotal,$discount);
        return $cartData;
    }    

    public function getCoupons($cou_id='',$cart_session_id){
        $coupon_data="";
        if(!empty($cou_id)){
            $coupon=ClassRegistry::init('Coupon');
            $coupon_data=$coupon->find('first',array('conditions'=>array('id'=>trim($cou_id),'release_date <='=>date('Y-m-d'),'end_date >='=>date('Y-m-d') ) ) );
            if(empty($coupon_data)){
                $cart=ClassRegistry::init('Cart');
                $cart->query("UPDATE `carts` SET `coupon_id` = '0' WHERE `carts`.`cart_session_id` = '$cart_session_id'");
            }
        }

        return $coupon_data;
    }
    
    public function getCouponAmount($type,$value='',$subtotal){
        $discount=$value;
        if($type=='percentage'){
            $discount=($subtotal*$value)/100;
        }
        return $discount;
    }

    public function fetchCartData($user_id){
        $obj = ClassRegistry::init('CartDetail');
        $data=$obj->find('all',array('conditions'=>array('cart_type'=>'plan','staff_user_id'=>$user_id)));
        $data = Set::extract('/CartDetail/.', $data);
        return (!empty($data))?$data:[];
    }

    public function getEmailforClippingReport($getemail=''){
            $email="";
            $emailFirstPart=explode("@",$getemail);
            $countemailchar=strlen($emailFirstPart[0]);
            $newemailpart=$emailFirstPart[0]; 
            for($loop=0; $loop < $countemailchar; $loop++) { 
                if($loop>1){
                    if($loop==($countemailchar-1)){
                     $email .=$newemailpart[$loop];
                    }else{
                            $email .="*";   
                    }
                 }else{
                    $email .=$newemailpart[$loop];
                }
            }
       
        return $email."@".$emailFirstPart[1];
    }

    public function countprcart($user_id='',$plan_id='',$prId=''){
        $Cart = ClassRegistry::init('Cart'); 
        $count=$Cart->find('count',array('conditions'=>array('staff_user_id'=>$user_id,'plan_id'=>$plan_id,'cart_type'=>'pr','Cart.press_release_id'=>$prId)));
        
        return $count;   
    }

    public function subscrber_count($prId=''){
        $NewsletterLog = ClassRegistry::init('NewsletterLog'); 
        $count=$NewsletterLog->find('count',array('conditions'=>array('NewsletterLog.press_release_id'=>$prId),'group'=>"NewsletterLog.staff_user_id"));
        
        return $count;   
    }

    public function all_transaction($subscriber_id='',$excludeId) {
        $obj = ClassRegistry::init('Transaction');
        return $obj->find("all",array('conditions'=>array('subscr_id'=>$subscriber_id,'Transaction.id !='=>$excludeId),'order'=>'Transaction.id DESC'));
    }

    public function getTransactionType($type=''){
        switch ($type) {
            case 'subscr_eot':
            $t="Cancel";
            break;
            case 'subscr_eot':
            $t="Cancel";
            break;
            case 'subscr_payment':
            $t="Reccuring payment";
            break;
            default:
            $t=str_replace("_"," ",$type);
            break;
        }

        return $t;
    }


    public function getUserNameById($id=null) {
        //die($id);
        $obj = ClassRegistry::init('StaffUser');
        $obj->recursive=2;
        $data=$obj->find('first', array('fields' => array('StaffRole.title','StaffUser.first_name','StaffUser.last_name'),'conditions' => array('StaffUser.id' => $id,)));
     
        return "<a href='".SITEURL."PressReleases/approvedby/$id'>".$data['StaffUser']['first_name'].' '.$data['StaffUser']['last_name']." (".$data['StaffRole']['title'].")</a>";
    }

    public function changeStatus($id,$status,$model) {
        switch ($status) {
            case 1:
                return '<span style="cursor: pointer;" id="change_status_'.$id.'" class="label label-danger " onclick="changeStatus('.$id.','.$status.','."'".$model."'".');">In Active</span>';
                break;
            case 0:
                return '<span style="cursor: pointer;" id="change_status_'.$id.'" class="label label-success" onclick="changeStatus('.$id.','.$status.','."'".$model."'".');">Active</span>';
                break;
        }
    }

     /*
 * @params:  
 * @Function use: getAdditionalClippingReport: Get get Additional Clipping Report
 * @created by: Hitesh verma
 * @Created: 12-11-2019
 */  
    public function getAdditionalClippingReport($press_release_id){
        $obj=ClassRegistry::init("ClippingAdditionalReport"); 
        return $obj->find("list",["conditions"=>["ClippingAdditionalReport.press_release_id"=>$press_release_id],"fields"=>['id',"file_name"]]);
    }


    public function getNewsRoomPaymentStatus($companyId=""){
        $obj = ClassRegistry::init('Transaction');
        return $obj->find("count",array('conditions'=>array('Transaction.company_id'=>$companyId,'Transaction.status'=>["Success","Completed"])));
    }

       /*
 * @params:  student_school_id
 * @Function use: getAdditionalClippingReport: Get get Additional Clipping Report
 * @created by: Hitesh verma
 * @Created: 12-11-2019
 */  
    public function getAdditionalClippingReportDetails($press_release_id){
        $obj=ClassRegistry::init("ClippingAdditionalReport"); 
        return $obj->find("all",["conditions"=>["ClippingAdditionalReport.press_release_id"=>$press_release_id]]);
    }

    public function get_nw_relation_data($nwid){
        $NetworkWebsite = ClassRegistry::init('NetworkWebsite');
        $result_data = $NetworkWebsite->find("all",array('conditions'=>array("NetworkWebsite.id"=>$nwid)));
        return $result_data[0];
    }

    public function get_nw(){

        return  $this->request->here() ;
    }

    public function getUserEmail($staff_user_id) {
        $obj = ClassRegistry::init('StaffUser');
        $staff_detail=$obj->find('first', array('conditions' => array('StaffUser.id' => $staff_user_id)));
        return isset($staff_detail['StaffUser']['email']);
    }


     /*
    * @params: 
    * @Function use: getRoleById: Get the user role detail at lie decleration 
    * @created by: Hitesh verma
    * @Created: 21-09-2020
    */
    public function getRoleById($roleId,$user_id="") {
        $obj=ClassRegistry::init("StaffRole");  
        if(!($roleDetails = Cache::read("userRole_$user_id",'short_30_min'))){ 
            $roleDetails=$obj->find('first',array("conditions"=>array("StaffRole.id"=>$roleId)));
            Cache::write("userRole_$user_id",$roleDetails, 'short_30_min');
        }  
        return (!empty($roleDetails))?$roleDetails["StaffRole"]["title"]:"NILL";
    }
      /*
     * @params: status
     * @Function use: Global function Check status
     * @created by: Hitesh verma
     * @Created: 16-07-2022
     */  
    public function checkStatusIcon($status) {
        
        switch ($status) {
            case "1":
                return '<span class="text-success"><i style=
                font-size:20px;" class="fas fa-check-square" aria-hidden="true"></i></span>';
                break;
            case "0":
                return '<span class="text-danger"><i style=
                font-size:20px;" class="fas fa-square-full"></i></span>';
                break;
        } 
    }


    public function summaryPrefix($date = '')
    {

        return date('F d, Y', strtotime($date)) . "/<a style='text-decoration:none;color:black' target='_blank' rel='nofollow' href='" . SITEFRONTURL . "' title='EMAILWIRE.COM'>EMAILWIRE.COM</a>/-- ";
    }

    public function numberFormatAsUs($number=""){
        
        if(!empty($number)){
            setlocale(LC_MONETARY, 'en_US');
            $number = $this->money_format('%!i', $number);
            return $number;
        }
        return $number;
    }
    public function money_format($format, $number)
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

    
    public function docType($file)
    {

        if (empty($file)) {

            $document = "<img style='width: 60%;' src='" . SITEURL . 'img/no-document-icon.png' . "'>";
        } else {

            $fileTypeArr = explode("/", $this->mime_content_type($file));

            $document = $fileTypeArr['0'];

            if ($document == 'application') {

                $document = $fileTypeArr['1'];
            }
        }

        return $document;
    }



    function mime_content_type($filename)
    {

        $mime_types = array(

            'txt' => 'text/plain',

            'htm' => 'text/html',

            'html' => 'text/html',

            'php' => 'text/html',

            'css' => 'text/css',

            'js' => 'application/javascript',

            'json' => 'application/json',

            'xml' => 'application/xml',

            'swf' => 'application/x-shockwave-flash',

            'flv' => 'video/x-flv',

            // images

            'png' => 'image/png',

            'jpe' => 'image/jpeg',

            'jpeg' => 'image/jpeg',

            'jpg' => 'image/jpeg',

            'gif' => 'image/gif',

            'bmp' => 'image/bmp',

            'ico' => 'image/vnd.microsoft.icon',

            'tiff' => 'image/tiff',

            'tif' => 'image/tiff',

            'svg' => 'image/svg+xml',

            'svgz' => 'image/svg+xml',

            // archives

            'zip' => 'application/zip',

            'rar' => 'application/x-rar-compressed',

            'exe' => 'application/x-msdownload',

            'msi' => 'application/x-msdownload',

            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video

            'mp4' => 'audio/mpeg',
            'mp3' => 'audio/mpeg',

            'qt' => 'video/quicktime',

            'mov' => 'video/quicktime',

            // adobe

            'pdf' => 'application/pdf',

            'psd' => 'image/vnd.adobe.photoshop',

            'ai' => 'application/postscript',

            'eps' => 'application/postscript',

            'ps' => 'application/postscript',

            // ms office

            'doc' => 'application/msword',

            'docx' => 'application/msword',

            'rtf' => 'application/rtf',

            'xls' => 'application/vnd.ms-excel',

            'ppt' => 'application/vnd.ms-powerpoint',

            // open office

            'odt' => 'application/vnd.oasis.opendocument.text',

            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

        );

        //$ext = strtolower(array_pop(explode('.',$filename)));

        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (array_key_exists($ext, $mime_types)) {

            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {

            return 'application/octet-stream';
        }
    }
}

?>