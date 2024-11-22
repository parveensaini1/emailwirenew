<?php
App::uses('AppController', 'Controller');
App::uses('HomesController', 'Controller');
/**
 * Pages Controller
 *
 * @property Page $Page
 */
class PagesController extends AppController {
    public $name = 'Pages';
    //public $components = array(''); 
    public $uses = array('Page','PlanCategory','PressRelease','Company',"Cart");
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'site_default';
        $this->Auth->allow(array('index','notfound','newsroom','support','contact_us','sitemap','sitemaps','latest_news_sitemap','ewire'));
    }

    /**

     * index method

     *

     * @return void

     */

    public function index() {

        $banner="";

        $slug =!empty($this->params->pass)?$this->params->pass:"home";

        $conditions = array('Page.slug' =>$slug, 'Page.status' => 1);

        $data_array = $this->Page->find('first', array('conditions' => $conditions, 'contain' => array(

                'PageTemplate' => array('fields' => array('PageTemplate.template_slug')),)));



        if(!empty($data_array['Page']['banner_path'])&&!empty($data_array['Page']['banner_path'])){

            $banner=SITEURL.'files/company/press_image/'.$data_array['Page']['banner_path'].'/'.$data_array['Page']['banner_image'];

        }



        if(!empty($data_array)){

            $title_for_layout = __(ucfirst($data_array['Page']['title']));

            $meta_title =(isset($data_array['Page']['meta_title'])&&!empty($data_array['Page']['meta_title']))?$data_array['Page']['meta_title']:$title_for_layout;

            $meta_keyword =(isset($data_array['Page']['meta_keyword'])&&!empty($data_array['Page']['meta_keyword']))?$data_array['Page']['meta_keyword']:"";

            $meta_description =(isset($data_array['Page']['meta_description'])&&!empty($data_array['Page']['meta_description']))?$data_array['Page']['meta_description']:"";

            

            $this->set(compact('data_array','title_for_layout','meta_title','meta_keyword','meta_description','banner'));
            
            if($data_array['Page']['page_template_id'] != 1) {

                $page = trim($data_array['PageTemplate']['template_slug']);

                $this->$page(); 

                $this->render($page); 

            }

        }else{ 

            //$this->redirect('/notfound');

        }

    }

    

    private function home(){  
        $nofeaturePr='3';
        /*To check joins is working so remove Distribution*/
        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));
       $featuredLimit=strip_tags(Configure::read('Featured.Pr.limit'));
       $todaydate=date("Y-m-d");
       $featured_arr=$this->PressRelease->find('all',array(
        'joins' => array(
             array(
                'table' => 'distributions_press_releases',
                'alias' => 'DistributionPressRelease',
                'type' => 'INNER',
                'conditions' => array( 
                    'DistributionPressRelease.press_release_id = PressRelease.id'
                )
            )
        ),'conditions'=>array('PressRelease.status'=>"1",'DistributionPressRelease.distribution_id'=>"2","PressRelease.release_date"=>$todaydate),'fields'=>array('PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','Company.id','Company.name','Company.slug','Company.status','Company.logo','Company.logo_path','PressRelease.language','Company.banner_image','Company.banner_path'),'limit'=>$featuredLimit,'order'=>"PressRelease.id DESC"));
        $excludeprIds=[];
        $latestPrLmt=strip_tags(Configure::read('Featured.Home.latest.limit'));
       if(!empty($featured_arr)){
            foreach ($featured_arr as $key => $featured){
                $excludeprIds[]=$featured['PressRelease']['id'];
            }
       }else{
               $latestPrLmt +=4;
       }
        $latestPr=$this->PressRelease->find('all',array('conditions'=>array('PressRelease.id !='=>$excludeprIds,'PressRelease.status'=>"1",'PressRelease.release_date <=' => date('Y-m-d')),'fields'=>array('PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','Company.id','Company.name','Company.slug','Company.status','PressRelease.language','Company.logo','Company.logo_path','PressRelease.language','Company.banner_image','Company.banner_path'),'limit'=>$latestPrLmt,'order'=>"PressRelease.id DESC"));
        /*
        'joins' => array(
                        array( 
                            'table' => 'press_releases',
                            'alias' => 'PressRelease',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Company.id = PressRelease.company_id AND PressRelease.status=1'
                            )
                        )
                    ),
        */
        $newsrooms=$this->Company->find('all',array(
            'fields'=>array('slug','name','logo_path','logo','banner_path','banner_image','slug','description','status'),'conditions'=>array('Company.status' => 1),'group'=>"Company.id",'order'=>"Company.id DESC","limit"=>"8"));
            $this->set(compact('featured_arr','nofeaturePr','latestPr','newsrooms'));
    }
    private function updatePrSlug(){ 
        $dataArr=$this->PressRelease->find('list');
        App::uses('Inflector', 'Utility');
        $counter=0;
        foreach ($dataArr as $id => $title) { 
            if(!empty($title)){
                $savedata['PressRelease'][$counter]['slug'] = $id.'-'.strtolower(Inflector::slug($title, '-')).".html";
                $savedata['PressRelease'][$counter]['id']=$id;
                $counter++;
            }
        } 
        die;
    }

    

    private function email_wire_plans(){
       
        $this->loadModel('StaffUser');
        /*
        $uId= ($this->Auth->user('id'))?$this->Auth->user('id'):"0"; //By Jaswinder Singh
        if($uId!=0){
            if($this->Session->check('ClientUser.signup')){
                $this->Session->delete('ClientUser.signup');
            }
            if($this->Auth->user('email_confirmed')==0){
               $this->set('user_email_status', $this->StaffUser->find('count', array('conditions' => array('StaffUser.id' =>$uId,'StaffUser.email_confirmed' => 0))));
            }
        }*/

        $newsroom_slug=""; 
        $user_id=$this->Auth->user("id");
        if(AuthComponent::user()&&!empty($this->params->pass)&&isset($this->params->pass[2])&&!empty($this->params->pass[2])){
            $newsroom_slug=trim($this->params->pass[2]);
            $newsroom=$this->Company->find("first",["conditions"=>["Company.slug"=>$newsroom_slug],"fields"=>["Company.id"]]);
            $checkNewsroomInCart=$this->Cart->find("first",["conditions"=>["Cart.company_id"=>$newsroom["Company"]["id"]],"fields"=>["Cart.cart_session_id"]]);
            if(!empty($checkNewsroomInCart)){
                $this->Cart->query("UPDATE `carts` SET `cart_session_id` = '".$checkNewsroomInCart['Cart']['cart_session_id']."' WHERE `carts`.`staff_user_id` = '$user_id' AND `carts`.`cart_type` ='plan' AND `carts`.`is_newsroom_incart` ='0'");
            }
        }
        $this->set('model', 'PlanCategory');
        $cartData=array();
        $cart_plans=array();
        $count_newsroom=""; 
        //$this->Session->read('FrontCart.coupon')
        $this->Session->delete('FrontCart.coupon');
        if(AuthComponent::user()){
            if($this->Session->check("ew_cartdata")){
                    $cartData=$this->Session->read("ew_cartdata");
                    $newsroomAssign=($cartData['newsroom_amount']>0)?'1':"0";
                if($cartData['totals']['subtotal']>0)
                    $cart_plans=$this->Custom->addToCartWithDb($cartData,'',$this->Auth->user("id"),$newsroomAssign);
                    $this->Session->delete("ew_cartdata");
            }else{
                 $cart_plans=$this->Custom->getUserCartData($this->Auth->user("id"));
                 
            }

        }else{
            $cart_plans=$this->Session->read("ew_cartdata");
            if(!empty($cart_plans)){
                $cart_plans['promo_code']='';
                $cart_plans['discount_id']='';
                $cart_plans['totals']['discount']='0.00';
                $cart_plans['totals']["total"]=$this->Custom->get_cart_total($cart_plans["newsroom_amount"],$cart_plans['totals']['subtotal'],$cart_plans['totals']["discount"]);
                $this->Session->write("ew_cartdata",$cart_plans);
                
            }
        } 
        $categorySlug=(isset($this->request->pass[1])&&!empty($this->request->pass[1]))?$this->request->pass[1]:Configure::read('Site.Default.Selected.Plan');
        
        $this->PlanCategory->recursive=2;
        $data=$this->PlanCategory->find('first',array("conditions"=>array('slug'=>$categorySlug)));
        
        
        
        /*
        if(!empty($data['PlanCategory']['banner_path'])&&!empty($data['PlanCategory']['banner_path'])){
            $banner=SITEURL.'files/company/press_image/'.$data['PlanCategory']['banner_path'].'/'.$data['PlanCategory']['banner_image'];
            $this->set('banner',$banner);
        }*/

        if(!empty($data)){
            $data_array=$this->PlanCategory->find('all',array("conditions"=>array('parent_id'=>$data['PlanCategory']['id'],'PlanCategory.status'=>"1"),'order'=>"lft ASC"));
            $this->set(compact("data",'data_array',"categorySlug","count_newsroom","cart_plans","newsroom_slug"));
        }else{
            $this->redirect('/notfound');
        }

    }

    

    private function testimonials(){



    }

    public function notfound(){

        $this->set('title_for_layout','Page not found');

    }

 

    public function newsrooms_list($slug=''){ 
        $this->set('title_for_layout','Newsrooms');
        $newsroomLimit=strip_tags(Configure::read('Site.newsroom.page.limit'));
        $conditions = array(); 
        $this->paginate = array('joins' => array(
                        array(
                            'table' => 'press_releases',
                            'alias' => 'PressRelease',
                            'type' => 'LEFT',
                            'conditions' => array( 
                                "Company.id = PressRelease.company_id AND PressRelease.status=1 AND PressRelease.release_date <= '".date('Y-m-d')."'"
                            )
                        )
                    ),

            'fields'=>array('slug','description','name','logo_path','logo','Company.status','banner_path','banner_image'),'conditions'=>array('Company.status' => 1),'group'=>"Company.id",'order'=>"Company.id DESC","limit"=>$newsroomLimit);

        $newsrooms = $this->paginate('Company');  
        $model='Company';
        $this->set(compact('newsrooms','model'));

    }



    public function newsroom($slug='',$newsroomFilter='prnews',$currentpage='1'){ 
        $isFullwidth="true";
        $model='Company';
    	if(!empty($slug)){

	        $prarray =$conditions = array();

	        $this->Company->bindModel(array('belongsTo'=>array('OrganizationType','Country') ) );

	        $this->request->data=$data=$this->Company->find("first",array('conditions'=>array('Company.slug'=>$slug,'Company.status' => array(1,2))));

            if(isset($data) && empty($data)){ 
	              $this->redirect('/notfound'); 
	        }

            $this->loadModel('CompanyDocument');

	        $this->set('title_for_layout',ucfirst($data['Company']['name']));

	        if($newsroomFilter=='prnews'){

	            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));

	            $prconditions[] = array('PressRelease.status' =>'1','PressRelease.release_date <=' => date('Y-m-d'),"company_id"=>$data['Company']['id']);



	            $this->paginate = array('conditions' => $prconditions, 'limit' => Configure::read('Site.paging'), 'order' => 'PressRelease.release_date DESC');

	            $prarray = $this->paginate('PressRelease');  

	        }else if($newsroomFilter=='social'){

	            require APP.'Vendor'.DS.'tumblr'.DS.'tumblr.php';

	            $obj=new tumblrFeed(); 

	            if(!empty($data['Company']['tumblr'])){

	                $blogName=str_replace(array("http:","/"),array("",""),$data['Company']['tumblr']);

	                $tumblrData=$obj->fetchfeeds($blogName,'10');

	                $this->set('tumblrData',$tumblrData);

	            }



	        }else if($newsroomFilter==='companyassets'){

	            $this->newsroom_assets($data['Company']['id'],$data['StaffUser']['id'],$currentpage,$slug);

                //$doc_data = $this->CompanyDocument->find('all',array('conditions'=>array('company_id'=>$data['Company']['id'])));

                // $this->set('doc_data',$doc_data);

	        }

            
            $doc_files=$doc_video=$doc_files=$doc_image ="";
	        $this->set(compact('data','prarray','slug','newsroomFilter',"doc_files","doc_video","doc_files","doc_image","isFullwidth","model"));

        }else{

        	$this->redirect('/notfound');

        }

    }



    public function newsroom_assets($newsroomId,$user_id,$currentpage,$slug){

        // $limit=Configure::read('Site.paging');

       $limit=5;

       $totalCounts=$this->Page->query("CALL newsroom_media_count(".$newsroomId.",".$user_id.");");

       $totalCount=(isset($totalCounts[0][0]['totalcount'])&&!empty($totalCounts[0][0]['totalcount']))?$totalCounts[0][0]['totalcount']:"0";

       $totalpages=($totalCount/$limit);

       $offset=(($currentpage-1)*$limit);

       $media_array=$this->Page->query("CALL newsroom_media(".$newsroomId.",".$user_id.",".$limit.",".$offset.");"); 

       $controller='newsroom';

       $action=$slug.'/companyassets';
       
       if(!empty($media_array)){

           foreach ($media_array as $key => $mediadata) {

            if(isset($mediadata['PI']['image_path'])&&!empty($mediadata['PI']['image_path'])){

                $imageUrl=SITEURL.'files/company/press_image/'.$mediadata['PI']['image_path'].'/'.$mediadata['PI']['image_name'];
                $ext = pathinfo($mediadata['PI']['image_name'], PATHINFO_EXTENSION);
                $dest = ROOT . DS . "app" . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'press_image' . DS . 'thumb' . DS . str_replace("." . $ext, "", $mediadata['PI']['image_name']) . '-' . $this->thumbWidth . 'x' . $this->thumbHeight . '.' . $ext;
                $fileUrl = ROOT . DS . "app" . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'press_image' . DS . $mediadata['PI']['image_path'] . DS . $mediadata['PI']['image_name'];
                if(!file_exists($fileUrl)){
                    $this->Custom->crop_resize_image($fileUrl,$dest,80,$this->thumbWidth,$this->thumbHeight); 
                   }
            }

           }

       }

       $this->set(compact('media_array','totalCount','totalpages','currentpage','action','controller'));

    }



    public function support(){

        $this->set('title_for_layout','Create ticket');

        // $this->render('/Users/support'); 

    }
 


    public function contact_us(){

      $this->set('title_for_layout', __('Contact us'));

      $this->set('title',"Contact us");

    }



    public function featured_press_release(){

        $this->set('title_for_layout','Featured Press release');

        $this->set('model','PressRelease');

        $featuredLimit=strip_tags(Configure::read('Site.paging'));

        $this->paginate=array(

        'joins' => array(

             array(

                'table' => 'distributions_press_releases',

                'alias' => 'DistributionPressRelease',

                'type' => 'INNER',

                'conditions' => array( 

                    'DistributionPressRelease.press_release_id = PressRelease.id'

                )

            )

        ), 

        'conditions'=>array('PressRelease.status'=>"1",'PressRelease.release_date <=' => date('Y-m-d'),'DistributionPressRelease.distribution_id'=>"2"),'fields'=>array('PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','Company.id','Company.name','Company.slug','Company.status','Company.logo','Company.logo_path','Company.banner_image','Company.banner_path'),'limit'=>$featuredLimit,'order'=>"PressRelease.id DESC");

         $data_array = $this->paginate('PressRelease');

         $this->set("data_array",$data_array);

    }





    public function sitemap(){

        $this->layout=false;

        

        $this->PressRelease->recursive=-2;

        $prConditions = array('PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));

        $fields=['PressRelease.slug','PressRelease.release_date'];

        $data_array=$this->PressRelease->find('list', array('conditions' => $prConditions,'limit'=>50,'fields'=>$fields, 'order' => 'PressRelease.release_date DESC') );



        $pages = $this->Page->find('list', array('conditions' =>['Page.status'=>"1"],'fields'=>['slug','created']));

        



        $this->PlanCategory->recursive="-1";

        $plancategory=$this->PlanCategory->find('list',array("conditions"=>array('PlanCategory.parent_id'=>"0",'PlanCategory.status'=>"1"),'fields'=>array("PlanCategory.slug","PlanCategory.name")));



        $this->set(compact("data_array","pages",'plancategory'));

        $this->render("/Pages/xml/sitemap");  

        // $this->RequestHandler->respondAs('xml');

    }



    public function latest_news_sitemap(){

        $this->layout=false;

        

        $this->PressRelease->recursive=-2;

        $prConditions = array('PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));

        $fields=['PressRelease.title','PressRelease.company_id','PressRelease.language','PressRelease.slug','PressRelease.release_date','Language.code','Language.name','Language.slug'];

        $data_array=$this->PressRelease->find('all', array('conditions' => $prConditions,'limit'=>50,'fields'=>$fields, 'order' => 'PressRelease.release_date DESC') );

        $this->set(compact("data_array"));

        $this->render("/Pages/xml/latest-news-sitemap");

        // $this->RequestHandler->respondAs('xml');

    }





    public function sitemaps(){ 

        

        $this->PressRelease->recursive=-2;

        $prConditions = array('PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));

        $fields=['PressRelease.slug','PressRelease.title'];

        // $data_array=$this->PressRelease->find('list', array('conditions' => $prConditions,'limit'=>50,'fields'=>$fields, 'order' => 'PressRelease.release_date DESC') );



        $this->paginate=array('conditions' => $prConditions,'limit'=>50,'fields'=>$fields, 'order' => 'PressRelease.release_date DESC');

        $data_array = $this->paginate('PressRelease');

         $pages = $this->Page->find('list', array('conditions' =>['Page.status'=>"1"],'fields'=>['slug','title']));

        



        $this->PlanCategory->recursive="-1";

        $plancategory=$this->PlanCategory->find('list',array("conditions"=>array('PlanCategory.parent_id'=>"0",'PlanCategory.status'=>"1"),'fields'=>array("PlanCategory.slug","PlanCategory.name")));



        $this->set(compact("data_array","pages",'plancategory')); 

    }



    public function ewireform($value=''){

        $this->layout=false;

    }

}    

