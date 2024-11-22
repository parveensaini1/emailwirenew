<?php
App::uses('AppController', 'Controller');
class ReleasesController extends AppController {
    public $name = 'Releases';
    public $uses =array('PressSeo','Country','State','Company','PressRelease','Msa','Category');
    public function beforeFilter() {
          $this->layout = 'site_default';
        parent::beforeFilter();
        $this->set('controller', 'releases');
        $this->set('model', 'PressRelease');
        $this->Auth->allow('ajax_js_feed_release','index','release','tag','newsbycategory','newsbydate','newsbycompany','newsbymsa','newsbycountry','feedsbycategories','newsfeedbycategory','search','test');
    } 
    /*latest news*/
    public function test(){

    }
    public function index(){
    	$title_for_layout=$title='Recent news';
        $data_array=array(); 
        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));

        $prConditions = array('PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));
        $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date','Company.name','Company.slug','Company.logo_path','Company.logo','Company.status','PressRelease.language',"PressRelease.is_old_release"];
        $this->paginate = array('conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields, 'order' => 'PressRelease.release_date DESC');
        $data_array = $this->paginate('PressRelease');
        $this->set(compact('data_array','title','title_for_layout')); 
    }

    public function newsbydate(){
    	$title_for_layout=$title='News by date';
    	if(!empty($this->request->query)){
    		$sd=$this->request->query['sd'];
    		$ed=$this->request->query['ed'];

	        $data_array=array(); 
	        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));

	        $prConditions = array('PressRelease.status' => 1,'PressRelease.release_date BETWEEN ? and ?' => array($sd, $ed));
	        $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','Company.name','Company.slug','Company.logo_path','Company.logo','Company.status','PressRelease.language'];
	        $this->paginate = array('conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields,'order' => 'PressRelease.release_date DESC');
	        $data_array = $this->paginate('PressRelease');
  
	        $this->set(compact('data_array','title','title_for_layout'));
	        $this->render('index');
        }
         $this->set(compact('title_for_layout'));
    }


    public function newsbycategory($slug='',$childCatSlug='') { 
        $this->set('title_for_layout', __('News by categories'));
        $filterby='category';
        $categories=[];
        if($slug==''){
            $pCategory_list = $this->Category->find('all', array('fields'=>["id","name","slug"],'conditions' => array('Category.status' => 1, 'Category.parent_id' => 0)));
            $categories=[]; 
            if($pCategory_list){
                foreach ($pCategory_list as $cpid => $pCat) {
                    $categories[$cpid]=$pCat;
                    $categories[$cpid]['children']=$this->Custom->getCategories($pCat["Category"]['id']); 
                }
            }     

        	 /* $pCategory_list = $this->Category->find('list', array('conditions' => array('Category.status' => 1, 'Category.parent_id' => 0)));
		        $categories=[]; 
		        if($pCategory_list){
		            foreach ($pCategory_list as $cpid => $pCatname) {
		            $categories[$pCatname]=[]; 
		            $category_list = $this->Category->find('list', array(
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
                        'fields'=>array('Category.slug','Category.name'),
                        'conditions' => array('Category.status' => 1, 'Category.parent_id' => $cpid,'PressRelease.status'=>1,'PressRelease.release_date  <='=>date('Y-m-d')),
                        'group'=>"PressRelease.id"
                        )
                    ); 
                    foreach ($category_list as $cId => $category) {
                        $categories[$pCatname][$cId]=$category;
                    }
		            }  
		        }*/

        	$this->set(compact('categories','filterby')); 
            $feedUrl=rtrim($this->request->url,"/");
            if($feedUrl=='news-feeds-by-categories'){
                $this->render('newsfeedbycategory');
            }

        }else{
        	$conditions = array(); 
            $categoryId=$this->Custom->getCategoryByChildCatSlugId($slug,$childCatSlug);
	        $titleSlug=(!empty($childCatSlug))?$childCatSlug:$slug;
	        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));
	        
	       	$prConditions = array('Category.id'=>$categoryId,'PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));

            $fields=['PressRelease.id','PressRelease.country_id','PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','Company.name','Company.slug','Company.logo_path','Company.logo','Company.status','PressRelease.language'];
           		$this->paginate = array(
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
			                'table' => 'categories',
			                'alias' => 'Category',
			                'type' => 'INNER',
			                'conditions' => array( 
			                    'Category.id = CategoryPressRelease.category_id'
			                )
			            )
			        ),
        	  'conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields,'group'=>"PressRelease.id",'order' => 'PressRelease.release_date DESC');
            $data_array = $this->paginate('PressRelease'); 
            $title_for_layout=str_replace("-"," ",ucfirst($titleSlug));
            $title='Category:- '.str_replace("-"," ",ucfirst($titleSlug));
	        $this->set(compact('data_array','title_for_layout','title'));

	        $this->render('index');
        }   
    }


    public function newsfeedbycategory($slug='') { 
        $this->set('title_for_layout', __('News by categories'));
        $filterby='category';
        if($slug==''){
            $this->Category->recursive=-1;
            $pCategory_list = $this->Category->find('all', array('fields'=>array('Category.id','Category.slug','Category.name'),'conditions' => array('status' => 1, 'parent_id' => 0)));
            $pCategory_list = Set::extract('/Category/.', $pCategory_list);
            $this->set(compact('pCategory_list','filterby')); 
        }   
    }
 	public function newsbycompany($slug='') { 
        $this->set('title_for_layout', __('News by company'));
        $filterby='company';
        if($slug==''){
        	$lists=$this->Company->find('list',array('joins' => array(
                        array(
                            'table' => 'press_releases',
                            'alias' => 'PressRelease',
                            'type' => 'INNER',
                            'conditions' => array( 
                                "Company.id = PressRelease.company_id AND PressRelease.status=1 AND PressRelease.release_date <= '".date('Y-m-d')."'"
                            )
                        )
                    ),
            'fields'=>array('slug','name'),'conditions'=>array('Company.status' => 1),'order'=>"name ASC"));
        	$this->set(compact('lists','filterby')); 
            $feedUrl=rtrim($this->request->url,"/");
            if($feedUrl=='news-feeds-by-companies'){
                $heading=__('Subscribe to News Releases by companies with Email or Add RSS Content to Your Website');
                $content=__('News feeds by Companies: Subscribe to news releases via email or news reeaders, or add news release feeds to your websites or blogs based on companies.');
                $this->set(compact('heading','content'));

                $this->render('filterfeedlist');;
            }else{
        	   $this->render('filterlist');
            }
        }else{
        	$conditions = array();
	        $slug=(isset($this->params->pass[0])&&!empty($this->params->pass[0]))?$this->params->pass[0]:"";
	        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast',),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));
	        $prConditions = array('Company.slug'=>$slug,'PressRelease.status'=>"1",'PressRelease.release_date <=' => date('Y-m-d'));
            $fields=['PressRelease.id','PressRelease.country_id','PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','PressRelease.body','Company.name','Company.slug','Company.logo_path','Company.logo','Company.status','PressRelease.language'];
           		$this->paginate = array(
	        
        	  'conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields, 'order' => 'PressRelease.release_date DESC');
            $data_array = $this->paginate('PressRelease'); 
            $title_for_layout=str_replace("-"," ",ucfirst($slug));
            $title='Company:- '.str_replace("-"," ",ucfirst($slug));
	        $this->set(compact('data_array','title_for_layout','title'));	
	        $this->render('index');
        }
        
    }
   
	public function newsbymsa($slug='') {
        $this->set('title_for_layout', __('News by MSA'));
        $filterby='msa';
        if($slug==''){
            $this->Msa->recursive=-1;
        	$lists=$this->Msa->find('list',array(
                'joins' => array(
                        array(
                            'table' => 'msas_press_releases',
                            'alias' => 'MsaPressRelease',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'MsaPressRelease.msa_id = Msa.id'
                            )
                        ),
                        array(
                            'table' => 'press_releases',
                            'alias' => 'PressRelease',
                            'type' => 'INNER',
                            'conditions' => array( 
                            "PressRelease.id = MsaPressRelease.press_release_id AND PressRelease.status=1 AND PressRelease.release_date <= '".date('Y-m-d')."'"
                            )
                        ),
                    ),
            'conditions'=>array('Msa.status' => 1),'fields'=>array('slug','name'),'order'=>"name ASC"));
            $this->set(compact('lists','filterby'));

        	$feedUrl=rtrim($this->request->url,"/");
            if($feedUrl=='news-feeds-by-msa'){
                $heading=__('Subscribe to News Releases by U.S. Major Cities with Email or Add RSS Content to Your Website');
                $content=__('News feeds by U.S. Major Cities (MSA): Subscribe to news releases via email or news reeaders, or add news release feeds to your websites or blogs based on MSA.');
                $this->set(compact('heading','content'));

                $this->render('filterfeedlist');;
            }else{
               $this->render('filterlist');
            }
        }else{
        	$conditions = array();
	        $slug=(isset($this->params->pass[0])&&!empty($this->params->pass[0]))?$this->params->pass[0]:"";
	        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));

	        $prConditions = array('Msa.slug'=>$slug,'PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));
            $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date','Company.name','Company.slug','Company.logo_path','Company.logo','Msa.slug','Company.status','PressRelease.language'];
           		$this->paginate = array(
	        	  'joins' => array(
			            array(
			                'table' => 'msas_press_releases',
			                'alias' => 'MsaPressRelease',
			                'type' => 'INNER',
			                'conditions' => array( 
			                    'MsaPressRelease.press_release_id = PressRelease.id'
			                )
			            ),
			            array(
			                'table' => 'msas',
			                'alias' => 'Msa',
			                'type' => 'INNER',
			                'conditions' => array( 
			                    'Msa.id = MsaPressRelease.msa_id'
			                )
			            )
			        ),
        	  'conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields, 'order' => 'PressRelease.release_date DESC');
            $data_array = $this->paginate('PressRelease'); 
            $title_for_layout=str_replace("-"," ",ucfirst($slug));
            $title='Msa:- '.str_replace("-"," ",ucfirst($slug));
	        $this->set(compact('data_array','title_for_layout','title'));	
	        $this->render('index');
        }
    }




    public function newsbycountry($slug='') { 
        $this->set('title_for_layout', __('News by country'));
        $filterby='country';
        if($slug==''){

        	$lists=$this->Country->find('list',array(
                'joins' => array(
                        array(
                            'table' => 'press_releases',
                            'alias' => 'PressRelease',
                            'type' => 'INNER',
                            'conditions' => array( 
                                "Country.id = PressRelease.country_id AND PressRelease.status=1 AND PressRelease.release_date <= '".date('Y-m-d')."'"
                            )
                        )
                    ),
            'fields'=>array('slug','name'),'order'=>"name ASC"));
        	$this->set(compact('lists','filterby'));
            $feedUrl=rtrim($this->request->url,"/");
        	if($feedUrl=='news-feeds-by-countries'){
                $heading=__('Subscribe to News Releases by Countries with Email or Add RSS Content to Your Website');
                $content=__('News feeds by Countries: Subscribe to news releases via email or news reeaders, or add news release feeds to your websites or blogs based on countries.');
                $this->set(compact('heading','content'));
                $this->render('filterfeedlist');;
            }else{
               $this->render('filterlist');
            }
        }else{
        	$conditions = array();
	        $slug=(isset($this->params->pass[0])&&!empty($this->params->pass[0]))?$this->params->pass[0]:"";
	        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));
	        
	        $prConditions = array('Country.slug'=>$slug,'PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));
            $fields=['PressRelease.id','PressRelease.country_id','PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','Company.name','Company.slug','Company.logo_path','Company.logo','Company.status','Country.name','Country.slug','PressRelease.language'];
           		$this->paginate = array(
	        	  'joins' => array(
			            array(
			                'table' => 'countries',
			                'alias' => 'Country',
			                'type' => 'INNER',
			                'conditions' => array( 
			                    'Country.id = PressRelease.country_id'
			                )
			            )
			        ),
        	  'conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields, 'order' => 'PressRelease.release_date DESC');
            $data_array = $this->paginate('PressRelease'); 
            $title_for_layout=str_replace("-"," ",ucfirst($slug));
            $title='Country:- '.str_replace("-"," ",ucfirst($slug));
	        $this->set(compact('data_array','title_for_layout','title'));	
	        $this->render('index');
        }
        
    }

    public function newsbystate($slug='') { 
        $this->set('title_for_layout', __('News by country'));
        $filterby='country';
        if($slug==''){
        	$lists=$this->State->find('list',array('fields'=>array('slug','name'),'order'=>"name ASC"));
        	$this->set(compact('lists','filterby'));
        	if($feedUrl=='news-feeds-by-state'){
                $this->set('title_for_layout', __('Subscribe to News Releases by U.S. Major Cities with Email or Add RSS Content to Your Website'));
                $heading=__('Subscribe to News Releases by Countries with Email or Add RSS Content to Your Website');
                $content=__('News feeds by Countries: Subscribe to news releases via email or news reeaders, or add news release feeds to your websites or blogs based on countries.');
                $this->set(compact('heading','content'));

                $this->render('filterfeedlist');;
            }else{
               $this->render('filterlist');
            }
        }else{
        	$conditions = array();
	        $slug=(isset($this->params->pass[0])&&!empty($this->params->pass[0]))?$this->params->pass[0]:"";
	        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));
	        
	        $prConditions = array('State.slug'=>$slug,'PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));
            $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','Company.name','Company.slug','Company.logo_path','Company.logo','Company.status','Country.name','Country.slug','PressRelease.language'];
           		$this->paginate = array(
	        	  'joins' => array(
			            array(
			                'table' => 'press_releases_states',
			                'alias' => 'StatePressRelease',
			                'type' => 'INNER',
			                'conditions' => array( 
			                    'StatePressRelease.press_release_id = PressRelease.id'
			                )
			            ),
			            array(
			                'table' => 'states',
			                'alias' => 'State',
			                'type' => 'INNER',
			                'conditions' => array( 
			                    'State.id = MsaPressRelease.msa_id'
			                )
			            )
			        ),
        	  'conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields, 'order' => 'PressRelease.release_date DESC');
            $data_array = $this->paginate('PressRelease'); 
            $title_for_layout=str_replace("-"," ",ucfirst($slug));
            $title='State:- '.str_replace("-"," ",ucfirst($slug));
	        $this->set(compact('data_array','title_for_layout','title'));	
	        $this->render('index');
        }
        
    }

    public function release() {
        
        // if(!$this->Auth->user('id')){
        //     $this->redirect('/users/login');
        // }
        $this->set('title_for_layout', __('Release'));
        $conditions = array();
        $slug=(isset($this->params->pass[0])&&!empty($this->params->pass[0]))?$this->params->pass[0]:"";
        $conditions[] = array('PressRelease.id'=>$slug,'PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));
        $this->PressRelease->recursive=2;
        $data=$this->PressRelease->find("first",array('conditions'=>$conditions));
        
        if(empty($data)){
            $this->redirect('/notfound');
        } 
        /*
        if(!empty($data['PressImage'])){
            foreach ($data['PressImage'] as $key => $image) {
                $imageUrl=SITEURL.'files/company/press_image/'.$image['image_path'].'/'.$image['image_name'];
                $this->Qimage->resize(array('height' =>$this->thumbHeight, 'width' =>$this->thumbWidth, 'file' =>$imageUrl, 'output' => WWW_ROOT. 'files/company/press_image/thumb/'));
                $this->Qimage->resize(array('height' =>$this->sliderHeight, 'width' =>$this->sliderWidth,'file' =>$imageUrl, 'output' => WWW_ROOT. 'files/company/press_image/thumb/'));
            }

        } 
        if(!empty($data['PressYoutube'])){
            foreach ($data['PressYoutube'] AS $index =>$video) {
                $videoUrl=$video['url'];
                $youTubeId=$this->Custom->getYouTubeId($videoUrl);
                $yThumbUrl='https://i.ytimg.com/vi/'.$youTubeId.'/hqdefault.jpg';
                $this->Qimage->resize(array('height' =>$this->thumbHeight, 'width' =>$this->thumbWidth, 'file' =>"$yThumbUrl", 'output' => WWW_ROOT. 'files/company/press_image/thumb/','proportional'=>'true','youtube_id'=>$youTubeId));
            }
        }*/

        $this->Custom->updateview($data['PressRelease']['id'],$data['PressRelease']['views']);
        $this->set('title_for_layout',$data['PressRelease']['title']);
        $meta_keyword ="";
        $meta_description =(isset($data['PressRelease']['summary'])&&!empty($data['PressRelease']['summary']))?$data['PressRelease']['summary']:""; 

        $company =(isset($data['Company']['name'])&&!empty($data['Company']['name']))?$data['Company']['name']:""; 
        $release_date =(isset($data['PressRelease']['release_date'])&&!empty($data['PressRelease']['release_date']))?$data['PressRelease']['release_date']:""; 
        $companylogo =(isset($data['Company']['logo'])&&!empty($data['Company']['logo']))?SITEURL.'files/company/logo/'.$data['Company']['logo_path'].'/'.$data['Company']['logo']:""; 
        $contact_name =(isset($data['Company']['contact_name'])&&!empty($data['Company']['contact_name']))?$data['Company']['contact_name']:""; 
        $singleImage=(!empty($data['PressImage']))?$data['PressImage']:"";
        $height=200;
        $width=200;
        $this->set(compact('data','meta_description','meta_keyword',"company","release_date","companylogo","contact_name","width","height","singleImage"));
    }

    public function ajax_js_feed_release() {
        $id = $this->request->query['id'];
        $conditions = array();
        $conditions[] = array('PressRelease.id'=>$id,"PressRelease.release_date <="=>date('Y-m-d'),'PressRelease.status' => 1);
        $data=$this->PressRelease->find("first",array('conditions'=>$conditions));
        echo json_encode($data);
        die;
    }

    public function tag(){
        $data_array=$conditions = array();
        $slug=(isset($this->params->pass[0])&&!empty($this->params->pass[0]))?$this->params->pass[0]:"";
        $this->set('title_for_layout', __('Releated Tag | '.str_replace("-"," ",ucfirst($slug))));
        $conditions = array('PressSeo.slug'=>$slug);

        $list=$this->PressSeo->find("list",array('conditions'=>$conditions,'fields'=>array("PressSeo.id","PressSeo.press_release_id")));

        if(!empty($list)){
                $id_list=array_unique($list);
                $ids=$list=array_values($id_list);
                if((count($list)<=1)){
                    $ids= $list[0];   
                }
            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));
            $prConditions = array('PressRelease.id'=>$ids,'PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'));
            $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','Company.name','Company.slug','Company.logo_path','Company.logo','Company.status','PressRelease.language'];
            $this->paginate = array('conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields, 'order' => 'PressRelease.release_date DESC');
            $data_array = $this->paginate('PressRelease');
        }
        $title='Tag:- '.str_replace("-"," ",ucfirst($slug));
        $this->set(compact('data_array','title'));
        $this->render('index');
    }

    function updateSlug(){ 
    	$this->loadModel('State');
        $dataArr=$this->State->find('list',array('fields'=>array('id','name')));
    
        $counter=0;
        foreach ($dataArr as $id => $title) { 
            if(!empty($title)){
                $savedata['State'][$counter]['slug'] = strtolower(Inflector::slug($title, '-'));
                $savedata['State'][$counter]['id']=$id;
                $counter++;
            }
        }
         
        //var_dump($this->State->saveMany($savedata['State']));
    }


    public function search(){
        $search_title = $this->params->query['srch-term'];
        $title_for_layout=$title='Search Result';
        $data_array=array(); 
        
        $this->loadModel('Page');
        $this->Page->unbindModel(array('belongsTo'=>array('PageTemplate')));

        $pages = $this->Page->find('all', array('fields'=>array('Page.id','Page.slug','Page.title'),'conditions' => array('Page.title like ' =>'%'.$search_title.'%')));
        
        $this->loadModel('PlanCategory');
        $plan_categories = $this->PlanCategory->find('all', array('fields'=>array('PlanCategory.id','PlanCategory.slug','PlanCategory.name'),'conditions' => array('PlanCategory.name like ' =>'%'.$search_title.'%','PlanCategory.parent_id'=>'0')));
        


        //pr($pages);die;
        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));

        
        $prConditions = array('PressRelease.status' => 1,'PressRelease.release_date <=' => date('Y-m-d'),'PressRelease.title LIKE' => '%'.$search_title.'%');
        $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date','Company.name','Company.slug','Company.logo_path','Company.logo','Company.status','PressRelease.language'];
        $this->paginate = array('conditions' => $prConditions, 'limit' => Configure::read('Site.paging'),'fields'=>$fields, 'order' => 'PressRelease.release_date DESC');
        $data_array = $this->paginate('PressRelease');
        $this->set(compact('data_array','title','title_for_layout','pages','plan_categories')); 
    }
   
}
