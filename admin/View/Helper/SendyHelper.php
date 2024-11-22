<?php 
App::uses('AppHelper', 'View/Helper');
App::uses('Helper', 'View/Helper');
App::uses('AuthComponent', 'Controller/Component');

class SendyHelper extends AppHelper {


    public function getSendyLoginDetail($appId=null) {
        $obj = ClassRegistry::init('Login');
        $login=$obj->find('first', array('conditions' => array('Login.id' => $appId)));
        return $login['Login'];
    }


    public function getSendyAppDetail($appId=null) {
        $obj = ClassRegistry::init('Apps');
        $data=$obj->find('first', array('conditions' => array('Apps.id' => $appId)));
        return $data['Apps'];
    }

    public function getSendyGdprOption($appId=null) {
        $obj = ClassRegistry::init('Apps');
        $data=$obj->find('first', array("fields"=>array("gdpr_options"),'conditions' => array('Apps.id' => $appId)));
        return $data['Apps']['gdpr_options'];
    }

    Public function getSendySubscriberCustomFields($lid=null){
    	$obj = ClassRegistry::init('List');
        $data=$obj->find('first', array("fields"=>array("custom_fields"),'conditions' => array('List.id' => $lid)));
        $th=$td="";
        if($data!=''){
			$custom_field_array = explode('%s%', $data['List']['custom_fields']);
			foreach($custom_field_array as $index =>$cf){
          	  $cf_array = explode(':', $cf);
	          $th .='<th>'.$cf_array[0].'</th>';
	          $td .='<td></td>';
	        }
	    }
        return array('th'=>$th,"td"=>$td);
    }

    public function getList($userId=''){
        $obj = ClassRegistry::init('List');
        $list=$obj->find('list', array("fields"=>array("id","name"),'conditions' => array('List.staff_user_id' => $userId)));
        return $list;
    
    }


    public function checkSentMail($userId,$prId){
        $obj = ClassRegistry::init('Campaign');
        $check=$obj->find('first', array("fields"=>array("sent","recipients","id","to_send","timezone","send_date",'createdfrom','userID'),'conditions' => array('Campaign.staff_user_id' => $userId,"Campaign.press_release_id"=>$prId)));
        return (!empty($check))?$check['Campaign']:[];
    
    }


}	

