<?php

App::uses('AppController', 'Controller');
App::uses('Inflector', 'Utility');
App::uses('AuthComponent', 'Controller/Component');

class ScriptsController extends AppController {
	public $uses = array('StaffUser','EmailTemplate','Company','OrganizationType','Transaction','Country','PressRelease',"Category","Msa",'Cart','PlanCategory','Plan','PressImage','PressSeo');   
	public $name = 'Scripts';
    public $useDbConfig = 'ewire';
    public $importedUserIds=[4,7,2455,22718,30970,34692,37720,41099,139709];
    public function beforeFilter() {
        $this->Auth->allow();
        parent::beforeFilter();
        $this->set('controller', 'scripts'); 
    }  


    protected function getRoleId($level){

        switch ($level) {
            case '1': // Admin
                $role=1; 
            break;
            case '2': //affiliate
                $role=5;
            break;    
            case '10': //Journalist
                $role=6;
            break;    
            case '4': // Client
                $role=3;
            break;
            case '3': // Subscriber
                $role=4;
            break;
            default:
                $role=4;
            break;
        }
        return $role;
    }

    public function mergeUser($offset="0",$limit=8){ 
        $this->loadModel('LiveUser');
        // $count=$this->LiveUser->find("count");  var_dump($count);die;
        // $condition=['LiveUser.email'=>['press@outskirtspress.com','raviraj.tak@marketsandmarkets.com','akash.r@maximizemarketresearch.com','jeberhard@ca.rr.com','info@premierdiamondltd.com','healthyreferral@gmail.com'] ];
        $condition=['LiveUser.email'=>['info@groupweb.com','jtnchor@hotmail.com','press@outskirtspress.com','jtnchor@gmail.com','raviraj.tak@marketsandmarkets.com','akash.r@maximizemarketresearch.com','jeberhard@ca.rr.com','info@premierdiamondltd.com','healthyreferral@gmail.com'] ];
        
        $dataArr=$this->LiveUser->find("all",['conditions'=>$condition,"offset"=>$offset,"limit"=>$limit]); 
        // pr($dataArr);die;
    
        $newOffset=$offset+$limit;
        if(!empty($dataArr)){
            $this->loadModel('StaffUser'); 
                foreach($dataArr as $liveUser){
                    // $sv['StaffUser']['password']=AuthComponent::password($this->data['StaffUser']['password']); 
                    $data=$liveUser['LiveUser'];
                    $sv=[];
                    $sv['StaffUser']['id']=$data['user_ID'];
                    $sv['StaffUser']['staff_role_id']=$this->getRoleId($data['level']);
                    $sv['StaffUser']['username']=$data['username'];
                    $sv['StaffUser']['email']=$data['email'];
                    $sv['StaffUser']['password']=$data['password'];
                    $sv['StaffUser']['first_name']=$data['firstname'];
                    $sv['StaffUser']['last_name']=$data['lastname'];
                    $sv['StaffUser']['phone']=$data['phone'];
                    $sv['StaffUser']['profile_image']=null;
                    $sv['StaffUser']['ipaddress']=null;

                    $sv['StaffUser']['address']=$data['address'];
                    $sv['StaffUser']['city']=$data['city'];
                    $sv['StaffUser']['state']=$data['state_us'];
                    $sv['StaffUser']['country']=(!empty($data['country']))?$data['country']:"United State";
                    $sv['StaffUser']['zip']=$data['zip'];

                    if($data['level']==3){
                        $sv['StaffUser']['newsletter_subscription']=1;
                        $sv['StaffUser']['newsletter_cycle']='d';
                        $sv['StaffUser']['newsletter_send_mail_date']=null;
                    }

                    $sv['StaffUser']['email_confirmed']=1;
                    $sv['StaffUser']['pr_plan_paid']=null;
                    $sv['StaffUser']['status']=1;
                    $sv['StaffUser']['subscriber_type']=0; 
                    $this->StaffUser->create();
                    if($this->StaffUser->save($sv)){
                        // echo "saved";
                    }
                    unset($sv);
                }

                // echo "<script src='https://code.jquery.com/jquery-3.5.1.min.js'></script>
                // <script type='text/javascript'> 
                // setTimeout(function(){
                //     window.location.href ='https://devsite.emailwire.com/admin/scripts/mergeUser/".$newOffset."';
                //     },1000);
                //     </script>"; 
        }else{
            echo "completed";

        }        
        exit();

    }


    protected function dateFormat($dateString){
        return date('Y-m-d H:i:s',$dateString);
    }
 
    private function savePressImage($data,$lastId){
        $saveImage=[];
        if(!empty($data['image1'])){
            $imagePart=explode("/",$data['image1']);
            if(is_array($imagePart) ){
                $saveImage['PressImage'][0]['press_release_id']= $lastId;
                $saveImage['PressImage'][0]['image_name']= end($imagePart);
                $saveImage['PressImage'][0]['image_path']= '2020';
                $saveImage['PressImage'][0]['image_text']= $data['imgtitle_1'];
                $saveImage['PressImage'][0]['describe_image']= $data['imgtext_1'];
            } 
        }

        if(!empty($data['image2'])){
            $imagePart1=explode("/",$data['image2']);
            if(is_array($imagePart1) ){
                $saveImage['PressImage'][1]['press_release_id']= $lastId;
                $saveImage['PressImage'][1]['image_name']= end($imagePart1);
                $saveImage['PressImage'][1]['image_path']= '2020';
                $saveImage['PressImage'][1]['image_text']= $data['imgtitle_2'];
                $saveImage['PressImage'][1]['describe_image']= $data['imgtext_2'];
            } 
        }
        if(!empty($saveImage)){
            // echo "===Images===";
            // pr($saveImage);
            $this->PressImage->saveMany($saveImage['PressImage']);
        }
    }

    private function savePressKeywords($data,$lastId){
        $savePressSeo=[];
        if(!empty($data['keywords'])){
            $keywords=explode(",",$data['keywords']);
            if(is_array($keywords) ){
                foreach($keywords as $index => $keyword){
                    $savePressSeo['PressSeo'][$index]['keyword']=$keyword;
                    $savePressSeo['PressSeo'][$index]['press_release_id']=$lastId;
                    $savePressSeo['PressSeo'][$index]['slug'] = Inflector::slug(strtolower($keyword), '-');
                }
            }else{
                $savePressSeo['PressSeo'][0]['keyword']=$data['keywords'];
                $savePressSeo['PressSeo'][0]['press_release_id']=$lastId;
                $savePressSeo['PressSeo'][0]['slug'] = Inflector::slug(strtolower($data['keywords']), '-');
            }
            // echo "===SEO===";
            // pr($savePressSeo);
            if(!empty($savePressSeo['PressSeo'])){
                $this->PressSeo->saveMany($savePressSeo['PressSeo']);
            }
        }
    }

    function mergeRelease($offset="0",$limit=3000){ 
        // To Do:-
       // User specific
        // $userId=139709; //30970
        // // 22718 == 171
        // // 30970 == 46312
        // // 34692 == 60
        // // 37720 == 312
        // // 139709 =626
        $this->loadModel('LiveRelease'); 
        // $dataCount=$this->LiveRelease->find("count"); 
        // var_dump($dataCount);die;
        $imported=$this->PressRelease->find('list',['fields'=>['id','id'],'conditions'=>['staff_user_id'=>$userId] ]);
        $companyImported=$this->Company->find('list',['fields'=>['id','id'],'conditions'=>['staff_user_id'=>$userId] ]);
        // pr($companyImported);

        // $imported[1139049]=1139049;  // Duplicate
        // $imported[1139050]=1139050;  // Duplicate
        
        // pr($imported);die;
        $condition=['company_ID'=>$companyImported,'release_ID !='=>$imported];    // [' release_ID !='=>["999485","1086527","1113249"]]
        $dataArr=$this->LiveRelease->find("all",["offset"=>$offset,"limit"=>$limit,'conditions'=>$condition]); 
          $dataCount=$this->LiveRelease->find("count",['conditions'=>$condition]); 
           pr($dataCount);  die;
           //die;
        // pr($dataArr);die;
        $newOffset=$offset+$limit;
        if(!empty($dataArr)){
            $this->loadModel('PressRelease'); 
                foreach($dataArr as $liveRelease){
                    if(!empty($liveRelease)){
                        // $sv['StaffUser']['password']=AuthComponent::password($this->data['StaffUser']['password']); 
                        $data=$liveRelease['LiveRelease']; 

                        $sv=[];

                        $sv['PressRelease']['id']=$data['release_ID'];
                        $sv['PressRelease']['title']=$data['title'];
                        $slug=(!empty($data['friendlyURL']))?$data['friendlyURL'].".html":  ( $data['release_ID'].'-'.strtolower(Inflector::slug($sv['PressRelease']['title'], '-')).'.html');
                        $sv['PressRelease']['slug']=  $slug;
                        $sv['PressRelease']['summary']=$data['sumary'];
                        $sv['PressRelease']['body']=str_replace(array("\r\n","\n"), "<br />", $data['body']);
                        $sv['PressRelease']['body']=preg_replace("/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w\- .\/?%&=]*)?/", "<a target='_blank' href='$0'>$0</a>", $sv['PressRelease']['body']);
                        $sv['PressRelease']['created']=$this->dateFormat($data['release_creation_date']);
                        $sv['PressRelease']['release_date']=$this->dateFormat($data['release_date']);
                        $sv['PressRelease']['country_id']=(!empty($data['country_id']) &&  $data['country_id']=="US")?'233':null;
                        $sv['PressRelease']['contact_name']=$data['contactname'];
                        $sv['PressRelease']['contact_phone']=$data['contactphone'];
                        $sv['PressRelease']['email']=$data['contactemail'];
                        $sv['PressRelease']['zip_code']=$data['zip']; 
                        $sv['PressRelease']['stock_ticker']=$data['ticker'];
                        $sv['PressRelease']['tos']=1;
                        $sv['PressRelease']['views']=$data['read'];
                        $sv['PressRelease']['staff_user_id']=$data['authorID'];
                        $sv['PressRelease']['admin_user_id']=$data['authorID'];
                        $sv['PressRelease']['company_id']=$data['company_ID'];

                        $sv['PressRelease']['source_country']=(!empty($data['country_id']) &&  $data['country_id']=="US")?'233':null;
                        // stateCode  To get state id
                        $sv['PressRelease']['state_id']=(!empty($data['stateCode']) &&  $data['stateCode']=="US")?'233':null;
                        $sv['PressRelease']['source_state']=(!empty($data['stateCode']) &&  $data['stateCode']=="US")?'233':null;
                        $sv['PressRelease']['status']=$data['status'];  
                        $sv['PressRelease']['is_old_release']=1;  
                        $sv['PressRelease']['language']="en";
                        $sv['PressRelease']['plan_id']="44"; // set default  
                        $sv['old_plan_id']['plan_id']=(!empty($data['plan_ID']))?$data['plan_ID']:'44';
                        //  pr($sv);die;
                        $this->PressRelease->create();
                        $savedRelease=$this->PressRelease->save($sv);

                        if($savedRelease==false){
                            // $log = $this->Company->getDataSource()->getLog(true, true);
                            $errorMessage=json_encode($this->PressRelease->validationErrors);
                            $reqArr['release_id']=$data['release_ID'];
                            $reqArr['title']=$data['title'];
                            CakeLog::write('debug', 'mergePressRelease : Error Data: ' . $errorMessage." : Request Data:".json_encode($reqArr));
                        }else{ 

                            if($savedRelease){ 
                                $lastId=(!empty($savedRelease['PressRelease']['id']))?$savedRelease['PressRelease']['id']:$sv['PressRelease']['id'];
                                $this->savePressImage($data,$lastId);
                                $this->savePressKeywords($data,$lastId);
                                // echo "Saved Release";
                                // pr($savedRelease);
                            }
                        }
                        unset($sv);
                    }
                }
                

                echo "<script src='https://code.jquery.com/jquery-3.5.1.min.js'></script>
                <script type='text/javascript'> 
                setTimeout(function(){
                    window.location.href ='https://devsite.emailwire.com/admin/scripts/mergeRelease/';
                    },2000);
                    </script>";  
        }else{
            echo "completed";

        }        
        exit();

    }

    function mergeCompanies($offset="0",$limit=150){ 
        // To Do:-
        try {
            $this->loadModel('LiveCompnay'); 
            // $dataCount=$this->LiveCompnay->find("count"); 
            // var_dump($dataCount);die;
            
            $imported=$this->Company->find('list',['fields'=>['id','id']]);
            $condition=['user_ID'=>$this->importedUserIds,'deleted'=>'','company_ID !='=>$imported];
            $dataArr=$this->LiveCompnay->find("all",['conditions'=>$condition,"offset"=>$offset,"limit"=>$limit]);  
            $newOffset=$offset+$limit;
            if(!empty($dataArr)){
                $this->loadModel('LiveCompnay'); 
                    foreach($dataArr as $liveCompnay){
                        if(!empty($liveCompnay)){
                            // $sv['StaffUser']['password']=AuthComponent::password($this->data['StaffUser']['password']); 
                            
                            $data=$liveCompnay['LiveCompnay']; 
                            if(!empty($data['company_name'])){
                                $sv=[];
            
                                $sv['Company']['id']=$data['company_ID'];
                                $sv['Company']['staff_user_id']=$data['user_ID'];    
                                $sv['Company']['name']=$data['company_name'];
                                $sv['Company']['contact_name']=$data['contactname'];
                                $sv['Company']['phone_number']=$data['contactphone'];
                                $sv['Company']['email']=$data['contactemail'];
                                $sv['Company']['slug']= Inflector::slug(strtolower($data['company_name']), '-');
                                $sv['Company']['description']=str_replace(array("\r\n","\n"), "<br />", $data['about_company']);
                                $sv['Company']['description']=preg_replace("/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w\- .\/?%&=]*)?/", "<a target='_blank' href='$0'>$0</a>", $sv['Company']['description']); 
                                $sv['Company']['status']=(!empty($data['deleted']))?$data['deleted']:1;   
                                $sv['Company']['newsroom_created_by']="admin";
                                $sv['Company']['approved_by']="1";
                                $this->Company->create();
                                $savedCompany=$this->Company->save($sv);
                               ;
                                if($savedCompany==false){
                                    // $log = $this->Company->getDataSource()->getLog(true, true);
                                    $errorMessage=json_encode($this->Company->validationErrors);
                                    CakeLog::write('debug', 'mergeCompanies : Error Data: ' . $errorMessage." : Request Data:".json_encode($sv));
                                }
                                unset($sv); 
                            }
                        }
                    }
                    
        
                    // echo "<script src='https://code.jquery.com/jquery-3.5.1.min.js'></script>
                    // <script type='text/javascript'> 
                    // setTimeout(function(){
                    //     window.location.href ='https://devsite.emailwire.com/admin/scripts/mergeCompanies/".$newOffset."';
                    //     },2000);
                    //     </script>"; 
            }else{
                echo "completed";
        
            }        
        }catch(Exception $_ex) {
            var_dump($_ex->getMessage());
        }
        exit();
    
    }
 

    /*https://ew.thrdev.com/admin/Scripts/importCategories */
    public function importCategories(){
    	 $this->loadModel("LiveCategory");
         $categories=$this->LiveCategory->find('all');
         if(!empty($categories)){
            $saveMany=[];
            foreach($categories as $index => $category){
                if(!empty($category['LiveCategory']['category'])){
                    $saveMany[$index]["id"]=$category['LiveCategory']['category_ID'];
                    $saveMany[$index]["name"]=$category['LiveCategory']['category'];
                    $saveMany[$index]["parent_id"]=$category['LiveCategory']['parentID'];
                    $saveMany[$index]['slug'] = Inflector::slug(strtolower($category['LiveCategory']['category']), '-');
                    $saveMany[$index]['status'] =1;
                }
            }
            if($this->Category->saveAll($saveMany)){
                echo "Saved!";
            }
            // pr($saveMany);
            die;
         }
    }
}
