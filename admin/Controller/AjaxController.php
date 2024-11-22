<?php

App::uses('AppController', 'Controller');
App::uses('CustomHelper', 'View/Helper');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Inflector', 'Utility');

include_once(APP . 'Vendor' . DS . 'dompdf/autoload.inc.php');
use Dompdf\Dompdf;


class AjaxController extends AppController {

    public $name = 'Ajax';
    public $uses = array('Company','Country', "State", 'StaffUser', 'Plan', 'Coupon', 'PlanCategoriesState', 'CountryPlanCategory', 'MsasPlanCategory', 'Msa', 'Cart', 'CartDistribution', 'EmailTemplate', 'Transaction','NwRelationships','NetworkWebsite');
    public function beforeFilter() {
        // $this->layout = false;
        parent::beforeFilter();
        $this->set('controller', 'ajax');
        $this->Auth->allow('sendmail','generatePdfDownloadReceipt');
    }
    
    function sendmailold(){   
    	$this->layout = false;
        $other_email_array=explode(",", $_POST['other_email']);
        $this->loadModel('ClippingReport');
        $this->loadModel('PressRelease');
        $prId=$_POST['pr_id'];
        $other_email=$_POST['other_email'];
        $registered_email=$_POST['registered_email'];

        $this->ClippingReport->recursive = -1;
        $prconditions[] = array('ClippingReport.press_release_id' => $prId);
  

        $data_array = $this->ClippingReport->find('all', array('conditions' => $prconditions, 'order' => 'ClippingReport.id DESC'));
        $this->PressRelease->recursive = -1;
        $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id")));
  		
  		$this->loadModel('NwRelationships');
        $nwrelationships = $this->NwRelationships->find('all', array('conditions' => array('NwRelationships.press_release_id' => $prId)));

        $a=$this->generatePdfDownloadReceipt($pr_data, $nwrelationships,$data_array,$prId, $other_email,$registered_email);
        echo $a;
       
        $this->autoRender = false;
       
    }

    public function sendmail()
    {   $response=[];
        try{
     
            $this->layout = false; 
              $this->loadModel('ClippingReport');
              $this->loadModel('PressRelease');
              $this->loadModel('ClickThroughClient');
              $prId=$this->data['pr_id'];
              $registered_email=$_POST['registered_email'];
              $otherEmails=$_POST['other_email'];

            if(!$prId){
                throw new NotFoundException('Invalid request.');
            } 

            $prconditions[] = array('ClippingReport.press_release_id' => $prId, 'ClippingReport.distribution_type !=' => 'mail_feed',);
            if (!empty($this->request->query('type'))) {
                $prconditions[] = array('ClippingReport.distribution_type' => $this->request->query('type'));
            }
            if (!empty($this->request->query('sname'))) {
                $prconditions[] = array('ClippingReport.site_name' => $this->request->query('sname'));
            }
            //$this->ClippingReport->recursive = -1;
            $clippingReportData =[]; //$this->ClippingReport->find('all', array('conditions' => $prconditions, 'order' => 'ClippingReport.id DESC'));

            $nwrelationships = $this->NwRelationships->find('all', array('conditions' => array('NwRelationships.press_release_id' => $prId,'NwRelationships.status'=>'1'), 'order' => 'NwRelationships.id DESC'));
            // $this->PressRelease->recursive = -1;
            // $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id")));
            
            $this->PressRelease->recursive = 2; 
            $socialShareCountSql="select SUM(clicked) FROM ".$this->ClickThroughClient->table." where type='1' AND `domain` NOT IN ('email','print') AND press_release_id=PressRelease.id";
            $emailCountSql="select SUM(clicked) FROM ".$this->ClickThroughClient->table." where type='1' AND `domain` NOT IN ('email') AND press_release_id=PressRelease.id";
            $printCountSql="select SUM(clicked) FROM ".$this->ClickThroughClient->table." where type='1' AND `domain` NOT IN ('print') AND press_release_id=PressRelease.id";
            $networkFeedCountSql="select SUM(clicked) FROM ".$this->ClickThroughClient->table." where type='2' AND press_release_id=PressRelease.id";
            $potentialAudienceCountSql="select SUM(potential_audience) FROM ".$this->NwRelationships->table." where press_release_id=PressRelease.id AND `status`='1' ";
            $prData=$this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), 
            
            "fields" => array("($socialShareCountSql) as socialShareCount","($networkFeedCountSql) as networkFeedCount","($potentialAudienceCountSql) as potentialAudienceCount",
            "($emailCountSql) as emailCount","($printCountSql) as printCount",
            "id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id","approved_by","slug"))); 

            if(empty($prData)){
                throw new NotFoundException('Invalid Press release.');
            }

            $this->generatePdfDownloadReceipt($prData, $nwrelationships,$clippingReportData,$prId, $otherEmails,$registered_email);
            $response=['status'=>'sucess','message'=>"Clipping report has been sent."];
        }catch(Exception $exc){
            // $this->Session->setFlash($exc->getMessage(), 'error');
              $response=['status'=>'failed','message'=>$exc->getMessage()];
        } 

        echo json_encode($response);
        $this->autoRender = false;
    }

      function generatePdfDownloadReceipt($prdata, $nwrelationships,$data_array,$prId, $otherEmails='',$registered_email){
        $dompdf = new Dompdf();
       	$this->layout = false;
        $staff_user_id=$prdata['PressRelease']['staff_user_id'];
        $dompdf->set_option('enable_remote', TRUE);
        $this->loadModel('MaintenancePayment');
        $html = $this->Custom->getClippingReportViewHtmlSend($prdata, $nwrelationships,$data_array);
       
      
        $dompdf->load_html($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        //$dompdf->stream();    
        // $dompdf->Output(WWW_ROOT.$pr_data['PressRelease']['title'].".pdf", 'F');        
        // $dompdf->stream($pr_data['PressRelease']['title'].".pdf", array("Attachment" => true));        
        $file = $dompdf->output();
         $fileName=ROOT.DS."app".DS.'webroot'.DS.'files'.DS."attached-clipping-reports".DS.Inflector::slug(strtolower($prdata['PressRelease']['slug']), '-').".pdf";
         file_put_contents($fileName, $file);


         $plan_name = $prdata["Plan"]['PlanCategory']['name']; 
        $statisticsHtml= $this->Custom->getStatisticsHtml($prdata,$plan_name);

        App::uses('CakeEmail', 'Network/Email');
        $currency=Configure::read('Site.currency');
        $Email = new CakeEmail('default');  
        $b=(strtok($otherEmails,",") !== false)?explode(",", $otherEmails):$otherEmails;
        $b[]=$registered_email;

        $Email->to(array_unique($b));

        $Email->replyTo('emailwireweb@gmail.com');
        $Email->subject($prdata['PressRelease']['title']);
        $Email->emailFormat('html');
        $Email->viewVars(array('statisticsHtml' => $statisticsHtml));
        $Email->template('clipping_report_mail');
        
        $Email->attachments($fileName) ;
        $allmail=$otherEmails.",".$registered_email;
        
        try {
            $Email->send(); 
            // return "Clipping Report sent successfully to ". $allmail ." email adrressess.";
             return "sent";
        } catch (Exception $exc) {
            return "Sorry,Clipping Report could not be sent.". $allmail ." email adrressess.";
        }

        $this->autoRender = false;
     }

    public function export_client_list($id){
        $this->loadModel('StaffUser');
        $condition = [];
        $condition[] = array('staff_role_id' => $id);
        $data_array = $this->StaffUser->find('all', array('conditions' => $condition));
        // echo '<pre>';
        // print_r($data_array);die;

        header("Content-type: application/force-download");
        header('Content-Disposition: inline; filename="Clients List - '.date('d-M-Y').'.csv"');
        echo '"id","First Name","Last Name","Email","Phone","Newsletter Subscription","Email Confirmed","Paid PR Plan","Status","Subscriber Type","Created","Updated"'."\r\n";
        if(isset($data_array) && !empty($data_array[0]['StaffUser'])){
            foreach ($data_array as $key => $arr_value) {
                echo '"'.$arr_value['StaffUser']['id'].'","'.$arr_value['StaffUser']['first_name'].'","'.$arr_value['StaffUser']['last_name'].'","'.$arr_value['StaffUser']['email'].'","'.$arr_value['StaffUser']['phone'].'","'.$arr_value['StaffUser']['newsletter_subscription'].'","'.$arr_value['StaffUser']['email_confirmed'].'","'.$arr_value['StaffUser']['pr_plan_paid'].'","'.$arr_value['StaffUser']['status'].'","'.$arr_value['StaffUser']['subscriber_type'].'","'.date("F d, Y", strtotime($arr_value['StaffUser']['created'])).'","'.date("F d, Y", strtotime($arr_value['StaffUser']['updated'])).'"'."\r\n";
            }exit();
        }
    }

    public function get_plan_field(){
        $this->loadModel('PlanCategory');
        $this->set("model","Plan");
        $cid=$this->request->data['cat_id'];
        $planCategory=$this->PlanCategory->find('count',array("conditions"=>array('PlanCategory.id'=>$cid,'PlanCategory.is_translated'=>'1')));
        $this->set("count",$planCategory);
    }
    
    public function get_state() {
        $condition = array();
        if(is_array($this->data['country_id'])){
          $condition[] = array('country_id IN' => $this->data['country_id']);
        }else{
          $condition[] = array('country_id' => $this->data['country_id']);
        }
        $state_list = $this->State->find('list', array('conditions' => $condition));
        $this->set('state_list', $state_list);
    }
    
    public function get_multi_states() {
        $condition = array();
        if($this->data['country_id']=='all'){
          $state_list = $this->Custom->getStateList();
        }else{
          if(is_array($this->data['country_id'])){
            $condition[] = array('country_id IN' => $this->data['country_id']);
          }else{
            $condition[] = array('country_id' => $this->data['country_id']);
          }
          $state_list = $this->State->find('list', array('conditions' => $condition));
        }
        
        $options='';
        // if(!empty($state_list)){
        //   foreach($state_list as $id => $label){
        //     $options .="<option value='$id'>$label</option>" ;
        //   }
          
        // }
        $this->set('state_list', $state_list);
    }
    
    public function get_multi_msas() {
      $this->loadModel('Msa');
      $condition = array();
      if($this->data['state_id']=='all'){
        $msa_list = $this->Custom->getMsaList();
      }else{
        $stateIds=$this->data['state_id'];
        
        if(is_array($stateIds)&&count($stateIds) > 1){
          $condition = array('state_id IN' => $this->data['state_id']);
        }else{
          $condition = array('state_id' => $stateIds);
        }
        $msa_list = $this->Msa->find('list', array('conditions' => $condition)); 
      }
      
      $this->set('msa_list', $msa_list);
  }

    public function file_upload() { 
        $hotel_image_path = GLOBALWEBROOT . 'files/hotels/' . $hotel_id;
        $width = getimagesize($file_path);
        $size = filesize($file_path);
        $n_w = 2048;
        $crop = false;
        if ($width[0] >= 2048) {
            $crop = true;
        } else if (round($size / 1024) > 180) {
            $n_w = $width[0];
            $crop = true;
        }
        $image_name = substr(base_convert(time(), 10, 36) . md5(microtime()), 0, 16) . "_" . "jpg";
        $this->Custom->compress_resize_image($file_path, $hotel_image_path . '/room_type/' . $image_name, $crop, 50, $n_w);
    }


    public function load_company_detail() {
        $condition = array();
        $condition[] = array("Company.id" => $this->data['company_id']);
        $data_array = $this->Company->find('first', array('conditions' => $condition));
        
        $response = array();
        $response['userid'] =$data_array['StaffUser']['id'];
        $response['contact_name'] = $data_array['Company']['media_contact_name'];
        $response['email'] = $data_array['Company']['media_email'];
        $response['phone_number'] = $data_array['Company']['media_phone_number'];
        $response['job_title'] = $data_array['Company']['media_job_title'];
        echo json_encode($response);
        exit();
    }

    public function search_company() {
        if(!empty($this->data['company_name'])){
        $condition[] = array("name like '%" . $this->data['company_name'] . "%'");
        $old_company_list = $this->Company->find('count', array('conditions' => $condition));
            if($old_company_list==0){
             $status="true";   
             $message="<span class='text-success'>This company is avilable.</span>";
            }else{
                $status="false";
                $message="<span class='text-danger'>This company is already listed with us .</span>";
            }

        }else{
            $status="empty";
            $message= "<span class='text-danger'>Please enter company name.</span>";
        }
        echo json_encode(array("status"=>$status,"message"=>$message));
        $this->autoRender = false;
    }

    public function change_status() {
    try{
        $this->layout = 'ajax';
        $model = $this->data['model'];
        $this->loadModel($model);
        $this->$model->id = $this->data['id'];
        $updateStatus = $this->data['status'];
        switch ($updateStatus) {
            case 0:
                $updateStatus = 1;
                $status="activated";
                break;
            case 1:
                $updateStatus = 0;
                $status="Inactivated";
                break;
        }
        $this->$model->saveField('status', $updateStatus);
        $data['status']=$status;
        $data['message']="$model successfully $status.";
        $customHelper = new CustomHelper(new View());
        $data['html']=$customHelper->changeStatus($this->data['id'],$updateStatus,$model);
      }catch(Exception $exc){
      $data['status']="failed";
      $data['message']=$exc->getMessage();
    }  
    echo json_encode($data);
    $this->autoRender = false;
    }


    /*
   * @params: 
   * @Function use: index: Upload Student Document
   * @created by: Hitesh verma
   * @Created: 12-11-2019
   */
  public function upload_pdf(){  
    $data['status']="success";
    try{
      if(!empty($this->data)){
          $data["documents"]=[];
          $dataArr=[];
          $press_release_id=$this->data['press_release_id'];   
          $files=$this->data["uploadpdf"];

          if(!empty($files)){
            foreach ($files as $count => $file){
            $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
            $imagePart=explode(".",strtolower($file['name']));
            $fileName=str_replace(" ","-",$imagePart[0])."-".$press_release_id.".".$ext;
            $tmpName=$file['tmp_name'];
            $arr_ext = array('pdf');

            if(!in_array($ext, $arr_ext)){
                throw new NotFoundException(__('Reports not valid. Please upload image or PDF files.'));
            }
            
            $path ='files/clippingAdditionalReports/'.$press_release_id;
            
            $maxsize = ini_get('upload_max_filesize');
            $maxFileSize = ((int)$maxsize) * 1024 * 1024;
            if($maxFileSize<=$file['size']){
              throw new NotFoundException(__("Reports too large. Please upload $maxFileSizeM"));
            }
            $doc_path=$dir=ROOT.DS."app".DS.'webroot'.DS.'files'.DS."clippingAdditionalReports".DS.$press_release_id;
            if (!file_exists($dir)) {
              $doc_path = new Folder($dir, true, 0777);
            }
            if(move_uploaded_file($tmpName,$doc_path.DS.$fileName)){
              $this->loadModel('ClippingAdditionalReport');
              $data=array('press_release_id'=>$press_release_id,'file_name' =>$fileName,'path' =>$path);
              $this->ClippingAdditionalReport->create();
              $this->ClippingAdditionalReport->save($data);
              $lastInsert_id=$this->ClippingAdditionalReport->getLastInsertID();
              $dataArr[$count]['id']=$lastInsert_id;
              $dataArr[$count]['file_name']=$fileName;
            }
          }
          $data['message']="Reports uploaded successfully.";
          $data["documents"]=$dataArr;
        }else{
          throw new NotFoundException(__('Please select atleast one document.'));
        }
      }else{
          throw new NotFoundException(__('Please select atleast one document.'));
      }
    }catch(Exception $exc){
        $data['status']="failed";
        $data['message']=$exc->getMessage();
    }
    echo json_encode($data);
    $this->autoRender = false;
  }

  function removedocument(){
    $data['status']="success";
    $this->loadModel("ClippingAdditionalReport");
    try{
      $result=$this->ClippingAdditionalReport->find("first",array("conditions"=>['ClippingAdditionalReport.id'=>$this->data['id']]));
      if (empty($result)){
          throw new NotFoundException('Report not found.Please try again.','error');
      }

      $del_file_path=ROOT.DS."app".DS.'webroot'.DS.$result['ClippingAdditionalReport']['path']. DS .$result['ClippingAdditionalReport']['file_name'];
      $delfile1= new File($del_file_path, false, 0777);
      $delfile1->delete();
      $this->ClippingAdditionalReport->delete($this->data['id']);
      $data['message']="Report removed.";
    }catch(Exception $exc){
        $data['status']="failed";
        $data['message']=$exc->getMessage();
    }
    echo json_encode($data);
    $this->autoRender = false;
  }



  
  public function get_states()
  {
      $state_list = $condition = array();
      if(!empty($this->data['countryId'])){
          $condition = array('State.status' => 1, 'country_id' => $this->data['countryId']);
          $state_list = $this->State->find('list', array('conditions' => $condition));
      }
      $model= (!empty($this->data['model'])) ? $this->data['model'] : '';
      $isCity= (!empty($this->data['is_city_also'])) ? $this->data['is_city_also'] : '';
      $this->set(compact('state_list','model','isCity'));
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

  public function get_pr_state()
  {
      $state_list = $condition = array();
      if (!empty($this->data['planId']) && !empty($this->data['is_plan_include']) && $this->data['is_plan_include'] == "yes") {
          $planDetail = $this->Plan->find('first', array('conditions' => array('Plan.id' => $this->data['planId'])));
          if (!empty($planDetail['PlanCategory']['is_state_allowed']) && $planDetail['PlanCategory']['is_state_allowed'] == 1) {
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

  public function get_pr_msas()
  {
      $msa_list = $condition = array();
      $stateIds = explode(",", $this->data['stateId']);
      if (!empty($this->data['planId']) && !empty($this->data['is_plan_include']) && $this->data['is_plan_include'] == "yes") {
          $planDetail = $this->Plan->find('first', array('conditions' => array('Plan.id' => $this->data['planId'])));
          if (!empty($planDetail['PlanCategory']['is_msa_allowed']) && $planDetail['PlanCategory']['is_msa_allowed'] == 1) {
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


  public function filterrecords()
  {
    $term=$this->request->query['term'];
    $model=$this->request->query['model']; 
    $this->loadModel($model);
       $condition = array();
              $states = $this->$model->find('list', array(
                'conditions' => array("$model.status" => 1, 'name LIKE' => "%" . $term ."%"),
                  'fields' => array("$model.id", "$model.name")
              ));
          
            //    $log = $this->$model->getDataSource()->getLog(false, false);debug($log);
        $mappedStates=$this->mapArrForAutocomplete($states);
      echo json_encode($mappedStates);
      $this->autoRender = false;
  }

    private function mapArrForAutocomplete($dataArr=[]){
      $response=[];
      if(!empty($dataArr)){
          $count=0;
          foreach($dataArr as $id=> $label){
              $response[$count]['value']=$id;
              $response[$count]['label']=$label;
              $count++;
          }
      }
      return $response;
    }


  function updateClippingReportTableData($model="NwRelationships",$networkWebisteId=""){ 
    $data['status']="success";
    $this->loadModel($model);
    try{
      $result=$this->{$model}->find("first",array("conditions"=>['id'=>$this->data['pk']]));
      if (empty($result)){
          throw new NotFoundException('Report not found.Please try again.','error');
      }
      $field=$this->data['name'];
      $value=$this->data['value'];
      $this->$model->id=$this->data['pk'];
      if($this->$model->saveField($field, $value) && !empty($networkWebisteId)){
        $arr=['site_name'=>'website_name','press_release_link'=>'website_domain','location'=>'website_location','type'=>"website_media_type"];
        $field=(!empty($arr[$this->data['name']]))?$arr[$this->data['name']]:$this->data['name'];
        $value=$this->data['value'];
          $saveData['NetworkWebsite'][$field]=$value;
          $saveData['NetworkWebsite']['id']=$networkWebisteId;
        if($field=="website_name"){
            $saveData['NetworkWebsite']['slug']=Inflector::slug(strtolower($value), '-');
        }
        $this->NetworkWebsite->save($saveData);
      }
      $data['message']="Value updated.";
    }catch(Exception $exc){
        $data['message']=$exc->getMessage();
    }
    echo json_encode($data);
    $this->autoRender = false; 
  }

  function updatetableContent($model="NwRelationships"){ 
    $data['status']="success";
    $this->loadModel($model);
    try{
      $result=$this->{$model}->find("first",array("conditions"=>['id'=>$this->data['pk']]));
      if (empty($result)){
          throw new NotFoundException('Report not found.Please try again.','error');
      }
      $field=$this->data['name'];
      $value=$this->data['value'];
      $this->$model->id=$this->data['pk'];
      $this->$model->saveField($field, $value);
      $data['message']="Value updated.";
    }catch(Exception $exc){
        $data['message']=$exc->getMessage();
    }
    echo json_encode($data);
    $this->autoRender = false; 
  }

}


