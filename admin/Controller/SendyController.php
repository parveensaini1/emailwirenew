<?php 
App::uses('AppController', 'Controller');
class SendyController extends AppController {
	public $name = 'Sendy';
    public $uses = array('List',"Subscriber","Campaign","PressRelease","Login","ListsStaffUser");
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('menutitle', 'All list');
        $this->set('menutitle_add', 'Add list');
        $this->set('controller', 'sendy');
        $this->set('model', 'List');
        $this->set('appId', '1'); 
    }

    public function add(){
        $this->set('title_for_layout', __('All Lists'));
        $user_id=$this->Auth->user('id');
        if($this->request->is('post')){
            $name=trim($this->data['List']['name']);
            $count=$this->List->find('count',array("conditions"=>array("staff_user_id"=>$user_id,"name"=>$name)));
            if($count>0){
                $this->List->validationErrors['name'] = 'Name is already exist.';
            }else{
                $this->request->data['List']['name']=$name; 
                if($this->List->save($this->request->data)){
                    $lid=$this->List->getLastInsertId();
                    $this->redirect(array('action' => 'import_subscriber_csv',$lid));
                }
            }
        }
        $this->set(compact("user_id"));
    } 

    public function index(){
        //error_reporting(0); 
    	$this->set('title_for_layout', 'List');
        $user_id=$this->Auth->user('id');
        $limit=Configure::read('Admin.paging');
        $this->paginate = array("conditions"=>array("List.staff_user_id"=>$user_id),'order' => 'List.id ASC','limit' =>$limit);
        $data_array = $this->paginate('List');
        // pr($data_array);        die;
        $this->set('data_array', $data_array);
    } 

    public function edit($lid = null) {
        $this->set('title_for_layout', 'Update List name');
         $user_id=$this->Auth->user('id');
        $this->List->id = $lid;
        if (!$this->List->exists()) {
            throw new NotFoundException(__('Invalid email template'));
        } 
        if ($this->request->is('put')) {
            if ($this->List->save($this->request->data)) {
                $this->Session->setFlash(__('List name successfully updated.'), 'success');

                $this->redirect(array('action' => 'import_subscriber_csv',$lid));
            } else {
                $this->Session->setFlash(__('List name not updated.. Please, try again.'), 'error');
            }
        }
          $this->request->data=$this->List->read(null,$lid);
        $this->set(compact("user_id",'lid'));
    }

    function import_subscriber_csv($lid=''){
        $err = isset($_GET['e']) ? $_GET['e'] : '';
        $data=$this->List->read(null,$lid);
        $user_id=$this->Auth->user('id');
        $this->set(compact("lid","data","err","user_id"));

    }

    function add_subscriber($lid=''){
        $data=$this->List->read(null,$lid);
        $user_id=$this->Auth->user('id');
        $this->set(compact("lid","data","user_id"));
    }

    public function subscribers($lid=null){
        $this->set('model', 'Subscriber');
        $limit=Configure::read('Admin.paging');
        $user_id=$this->Auth->user('id');
        $this->paginate = array("conditions"=>array("list"=>$lid,"staff_user_id"=>$user_id),'order' => 'Subscriber.id DESC','limit' =>$limit);
        $subscribers = $this->paginate('Subscriber');
        $this->set(compact("lid","subscribers","user_id"));
    }

    public function delete($id = null,$lid) {
        $this->Subscriber->id = $id;
        if (!$this->Subscriber->exists()) {
            throw new NotFoundException('Invalid id', 'error');
        }
        if ($this->Subscriber->delete()) {
            $this->Session->setFlash(__('Subscriber successfully deleted'), 'success');
            return $this->redirect(array("action"=>"subscribers",$lid));
        }
    }

    public function sendinmail($prId=null){
        if($prId!=null){ 
        $this->loadModel("list");
        $user_id=$this->Auth->user('id');
        if($this->request->is("post")&&!empty($this->data)){
            $listId=$this->data['Campaign']['list_id'];
            $this->setCampSchedule($prId,$listId);
             $this->Session->setFlash(__('PR will be send.'),'success');
             return $this->redirect(array("controller"=>"PressReleases",'action' =>"index")); 
        }
        $lists =$this->list->find("list",array("conditions"=>array("staff_user_id"=>$user_id),"fields"=>array('id',"name")));
        $this->set(compact("lists","prId","user_id"));
      }else{
         return $this->redirect(array('action' => 'index'));
      }
    }



    public function setCampSchedule($prId=null,$list_id){
        $sendyMainUser=$this->Login->find('first',array("fields"=>array("timezone"),"conditions"=>array("id"=>"1")));
        
        // $timezone=$sendyMainUser['Login']['timezone'];
        // date_default_timezone_set($timezone!='0' && $timezone!='' ? $timezone : date_default_timezone_get());


        $sendyMainUser=$this->Login->find('first',array("fields"=>array("timezone"),"conditions"=>array("id"=>"1")));

        $campaign=$this->Campaign->find('first',array("fields"=>array("id"),"conditions"=>array('created LIKE'=>'%'.date("Y-m-d").'%','sent' => null)));

        $send_date=strtotime("+2 minutes");
        if(!empty($campaign)){
            $seconds = strtotime("+10 minutes");
            $rounded_seconds = round($seconds / (15 * 60)) * (15 * 60);
            $send_date=strtotime(date('Y:m:d H:i:s',$rounded_seconds)); 
        }

        $mainUserId=$appId="1";
        $user_id=$this->Auth->user('id');
        $from_email=Configure::read('sendy_email');
        $from_name=$this->Auth->user('first_name')." ".$this->Auth->user('last_name');
        $this->PressRelease->recursive=-1;
        $data=$this->PressRelease->find("first",array("fields"=>array("title","slug","body","staff_user_id"),"conditions"=>array("PressRelease.id"=>$prId)));
        $campaignData['Campaign']['title']=$data['PressRelease']['title'];
        $campaignData['Campaign']['press_release_id']=$prId;
        $campaignData['Campaign']['press_release_slug']=$data['PressRelease']['slug'];
        $campaignData['Campaign']['html_text']="<html><head><title></title></head><body>".$data['PressRelease']['body']." <p style='text-align:center;'><unsubscribe>Click here to unsubscribe</unsubscribe></p></body></html>";
        $campaignData['Campaign']['userID']=$user_id;
        $campaignData['Campaign']['staff_user_id']=$data['PressRelease']['staff_user_id'];
        $campaignData['Campaign']['from']="admin";
        $campaignData['Campaign']['app']=$appId;
        $campaignData['Campaign']['from_name']=$from_name;
        $campaignData['Campaign']['from_email']=$from_email;
        $campaignData['Campaign']['reply_to']="no-reply@emailwire.com";
        $campaignData['Campaign']['recipients']="0";
        $campaignData['Campaign']['wysiwyg']="1";
        $campaignData['Campaign']['lists']=$list_id;   // select list in popup
        $campaignData['Campaign']['opens_tracking']="1";
        $campaignData['Campaign']['links_tracking']="1";
        $campaignData['Campaign']['timezone']=date_default_timezone_get();  //$sendyMainUser['Login']['timezone']
        $campaignData['Campaign']['send_date']=$send_date;

         if($this->Campaign->save($campaignData)){
            $cid=$this->Campaign->getInsertID();
            return true;
        }
    }


    public function emailreport($userId  = null,$champId  = null){ 
        $this->set('title_for_layout', 'Sent mail report'); 
        if($champId!=null){
            // $userId=$this->Auth->user('id');
            $this->set(compact("champId","userId"));
        }else{
              $this->redirect('/notfound');
        }
 
    }

    public function assigntoclient($id=null,$status) {
        $this->List->id =$id;
        switch ($status) {
            case 0:
                $status = 1;
                $message="List activated for client";
            break;
            case 1:
                $status = 0;
                $message="List deactivated for client";
            break;
        }
        $this->List->saveField('list_for_client', $status);
        $this->Session->setFlash($message, array('class' => 'success'));
        $this->redirect(array('action' => 'index'));
        $this->autoRender = false; 
    }

    public function delete_subscriber($id='',$listId=""){
        try{
             $this->Subscriber->id = $id;
        
            if (!$this->Subscriber->exists()) {
                throw new NotFoundException(__('Invalid Detail.'));
            }
            if ($this->Subscriber->delete()) {
                $this->Session->setFlash(__('Email deleted successfully'), 'success');
            }else{
                throw new NotFoundException(__('Email name not deleted. Please, try again.')); 
            }
        }catch(Exception $exc){
            $message= $exc->getMessage();
            $this->Session->setFlash($message, 'error');
        }   
        $this->redirect(array('controller'=>"sendy",'action' => 'subscribers',$listId));
    }
}