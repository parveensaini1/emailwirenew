<?php
App::uses('AppController', 'Controller');
class RssController extends AppController {
    public $name = 'Rss';
    public $limit=5;
    public $components = array('RequestHandler');
    public $uses =array('PressSeo','Country','State','Company','PressRelease','Msa','Category',"ClippingReport");
    public function beforeFilter() {
        $this->layout ='default';
        parent::beforeFilter();
        $this->set('controller', 'releases');
        $this->set('model', 'PressRelease');
        $this->Auth->allow('js_feed','release','tag','category','company','msa','country','newsbydate','latest',"gif","headlines");
    }
 	
    public function js_feed() {
        $joins='';
         $limit=(isset($this->request->query['ew_limit'])&&!empty($this->request->query['ew_limit']))?$this->request->query['ew_limit']:$this->limit;
        // $offset=(isset($this->request->query['ew_offset'])&&!empty($this->request->query['ew_offset']))?$this->request->query['ew_offset']:'0';
        $page_no=(isset($this->request->query['ew_page'])&&!empty($this->request->query['ew_page']))?$this->request->query['ew_page']:'1';
        $offset = ($page_no-1) * $limit; 
        $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','PressRelease.status' => 1);
        if(isset($this->request->query['ew_cat'])&&!empty($this->request->query['ew_cat'])){
           $slug=trim($this->request->query['ew_cat']);
           $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Category.slug'=>$slug,'PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
           $joins= array(
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
                        ),
                        array(
                            'table' => 'plans',
                            'alias' => 'Plan',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Plan.id = PressRelease.plan_id'
                            )
                        ),
                        array(
                            'table' => 'plan_categories',
                            'alias' => 'PlanCategory',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'PlanCategory.id = Plan.plan_category_id'
                            )
                        )
                    );

        }else if(isset($this->request->query['ew_pcat'])&&!empty($this->request->query['ew_pcat'])){
            $pCategoryIds = $this->Category->find('list', array('fields'=>array('Category.id','Category.id'),'conditions' => array('status' => 1, 'parent_id' =>$this->request->query['ew_pcat'])));    
            $pCategoryIds=array_values($pCategoryIds);
            $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Category.id'=>$pCategoryIds,'PressRelease.status' => 1);
             $joins= array(
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
                        ),
                        array(
                            'table' => 'plans',
                            'alias' => 'Plan',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Plan.id = PressRelease.plan_id'
                            )
                        ),
                        array(
                            'table' => 'plan_categories',
                            'alias' => 'PlanCategory',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'PlanCategory.id = Plan.plan_category_id'
                            )
                        )
                    );

        }else if(isset($this->request->query['ew_country'])&&!empty($this->request->query['ew_country'])){
           $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Country.slug'=>$this->request->query['ew_country'],'PressRelease.status' => 1);
             $joins = array(
                        array(
                            'table' => 'countries',
                            'alias' => 'Country',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Country.id = PressRelease.country_id'
                            )
                        ),
                        array(
                            'table' => 'plans',
                            'alias' => 'Plan',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Plan.id = PressRelease.plan_id'
                            )
                        ),
                        array(
                            'table' => 'plan_categories',
                            'alias' => 'PlanCategory',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'PlanCategory.id = Plan.plan_category_id'
                            )
                        )
                    );
        }elseif(isset($this->request->query['ew_company'])&&!empty($this->request->query['ew_company'])){
           $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 

           $slug=$this->request->query['ew_company'];
           $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Company.slug'=>$slug,'PressRelease.status' => 1);
           $joins=array(
           array(
               'table' => 'plans',
               'alias' => 'Plan',
               'type' => 'INNER',
               'conditions' => array( 
                   'Plan.id = PressRelease.plan_id'
               )
           ),
           array(
               'table' => 'plan_categories',
               'alias' => 'PlanCategory',
               'type' => 'INNER',
               'conditions' => array( 
                   'PlanCategory.id = Plan.plan_category_id'
               )
               ),array('table' => 'companies','alias' => 'Company','type' => 'INNER','conditions' => array('Company.id = PressRelease.company_id')));

        }elseif(isset($this->request->query['ew_msa'])&&!empty($this->request->query['ew_msa'])){
            $slug=$this->request->query['ew_msa'];
            $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Msa.slug'=>$slug,'PressRelease.status' => 1);
            $joins  =array(
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
                        ),
                        array(
                            'table' => 'plans',
                            'alias' => 'Plan',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Plan.id = PressRelease.plan_id'
                            )
                        ),
                        array(
                            'table' => 'plan_categories',
                            'alias' => 'PlanCategory',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'PlanCategory.id = Plan.plan_category_id'
                            )
                        )
                    );
        }
       
        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 
        $this->PressRelease->recursive="-1";
        $this->loadModel('PressImage');
        $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.summary','PressRelease.release_date','PressRelease.body'];
        $data_arr=$this->PressRelease->find('all',array('joins' =>$joins,'conditions' =>$prConditions,'offset' =>$offset,'limit' =>$limit,'fields'=>$fields,'order' => 'PressRelease.release_date DESC'));
       
       
        $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 
        $total_data_count=$this->PressRelease->find('count',array('joins' =>$joins,'conditions' =>$prConditions));
        $newdata_arr=[];

        $hostname=(isset($this->request->query['hostname'])&&!empty($this->request->query['hostname']))?$this->request->query['hostname']:'';
        $releasepageurl=(isset($this->request->query['releasepageurl'])&&!empty($this->request->query['releasepageurl']))?$this->request->query['releasepageurl']:'';
       
        foreach ($data_arr as $index => $data) {
            $newdata_arr[$index]['title']=$data['PressRelease']['title'];
            $newdata_arr[$index]['description']=$data['PressRelease']['summary'];
            $newdata_arr[$index]['body']=$data['PressRelease']['body'];
            $newdata_arr[$index]['id']=$data['PressRelease']['id'];
            $newdata_arr[$index]['pubdate']=date("d, M Y",strtotime($data['PressRelease']['release_date']));
            $newdata_arr[$index]['publink']=SITEURL.'release/'.$data['PressRelease']['slug'];
            $imgConditions=array();
            $image_data_arr=array();
            $imgConditions=array('PressImage.press_release_id'=>$data['PressRelease']['id']);
            $image_data_arr=$this->PressImage->find('all',array('conditions' =>$imgConditions, 'limit' =>$limit));
            if(!empty($image_data_arr)&&$image_data_arr[0]['PressImage']['image_name'] !='')
            {
               $newdata_arr[$index]['ab']=SITEURL.'files/company/press_image/'.$image_data_arr[0]['PressImage']['image_path'].'/'.$image_data_arr[0]['PressImage']['image_name'];  
            }
            $newdata_arr[$index]['total']=$total_data_count;
            $newdata_arr[$index]['nummer_of_page'] = ceil ($total_data_count / $limit);
            

            $this->updateClippingReport($data['PressRelease']['id'],$hostname,$releasepageurl,"js_feed");
            
        }
        header("Content-type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($newdata_arr);
        
        $this->autoRender=false;
        die;
 
    }

    public function release(){
        $this->RequestHandler->respondAs('text/xml');
        $slug=($this->request->query)?$this->request->query['s']:'';
        $content=(isset($this->request->query['c'])&&!empty($this->request->query['c']))?$this->request->query['c']:'full'; 

        if($this->RequestHandler->isRss()&&!empty($slug)) {
            $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','PressRelease.slug'=>$slug,'PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 

            $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date',];
            $data_arr=$this->PressRelease->find('all',array('joins' => array(
                array(
                    'table' => 'plans',
                    'alias' => 'Plan',
                    'type' => 'INNER',
                    'conditions' => array( 
                        'Plan.id = PressRelease.plan_id'
                    )
                ),
                array(
                    'table' => 'plan_categories',
                    'alias' => 'PlanCategory',
                    'type' => 'INNER',
                    'conditions' => array( 
                        'PlanCategory.id = Plan.plan_category_id'
                    )
                )
            ),
            'conditions' => $prConditions, 'limit' =>$this->limit,'fields'=>$fields,
                 'contain' => array(
                'PressImage' => array('fields' => array('PressImage.image_name','PressImage.image_path')),
                'Category' => array('fields' => array('Category.name')),
                ),
                'order' => 'PressRelease.release_date DESC'));
            $this->set(compact('data_arr','content'));
            $this->render('index');
        }
    }


    public function latest() { 
        $hostname="";
        $this->RequestHandler->respondAs('application/xml');
        if($this->RequestHandler->isRss()) {
            $content=(isset($this->request->query['c'])&&!empty($this->request->query['c']))?$this->request->query['c']:'summary';
            $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 

            $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date',];
            $data_arr=$this->PressRelease->find('all',array('joins' => array(
                array(
                    'table' => 'plans',
                    'alias' => 'Plan',
                    'type' => 'INNER',
                    'conditions' => array( 
                        'Plan.id = PressRelease.plan_id'
                    )
                ),
                array(
                    'table' => 'plan_categories',
                    'alias' => 'PlanCategory',
                    'type' => 'INNER',
                    'conditions' => array( 
                        'PlanCategory.id = Plan.plan_category_id'
                    )
                )
            ),'conditions' => $prConditions, 'limit' =>$this->limit,'fields'=>$fields,
                 'contain' => array(
                    'PressImage' => array('fields' => array('PressImage.image_name','PressImage.image_path')),
                    'Category' => array('fields' => array('Category.name')),
                ),
                'order' => 'PressRelease.release_date DESC')); 
            $this->set(compact('data_arr','content'));
            $this->render('index');
        }
    }



 

    public function company() {
        $slug=($this->request->query)?$this->request->query['s']:''; 
        $content=(isset($this->request->query['c'])&&!empty($this->request->query['c']))?$this->request->query['c']:'summary';
            $filterby='category';
        	$this->RequestHandler->respondAs('application/xml');
            $prconditions = array();

	    if($this->RequestHandler->isRss()&&!empty($slug)) { 
            
            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 
            $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Company.slug'=>$slug,'PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
            
             $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date',];
            $data_arr=$this->PressRelease->find('all',array(
                'joins' => array(
                        array(
                            'table' => 'companies',
                            'alias' => 'Company',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Company.id = PressRelease.company_id'
                            )
                        ),
                        array(
                            'table' => 'plans',
                            'alias' => 'Plan',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Plan.id = PressRelease.plan_id'
                            )
                        ),
                        array(
                            'table' => 'plan_categories',
                            'alias' => 'PlanCategory',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'PlanCategory.id = Plan.plan_category_id'
                            )
                        )
                    ),
                'conditions' => $prConditions, 'limit' =>$this->limit,'fields'=>$fields,
                 'contain' => array(
                'PressImage' => array('fields' => array('PressImage.image_name','PressImage.image_path')),
                'Category' => array('fields' => array('Category.name')),
                ),
                'order' => 'PressRelease.release_date DESC'));
           
            $this->set(compact('data_arr','content'));
            $this->render('index');
        }else{
            $this->redirect('/notfound');
        } 
    }

    public function country() {
         $hostname="";
        $slug=($this->request->query)?$this->request->query['s']:''; 
        $content=(isset($this->request->query['c'])&&!empty($this->request->query['c']))?$this->request->query['c']:'summary';
        $filterby='category';
        $this->RequestHandler->respondAs('application/xml');
        $prconditions = array();
        if($this->RequestHandler->isRss()&&!empty($slug)) { 
            
            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Company'))); 
            $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Country.slug'=>$slug,'PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
            
             $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date',];
            $data_arr=$this->PressRelease->find('all',array(
                 'joins' => array(
                        array(
                            'table' => 'countries',
                            'alias' => 'Country',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Country.id = PressRelease.country_id'
                            )
                        ),
                        array(
                            'table' => 'plans',
                            'alias' => 'Plan',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Plan.id = PressRelease.plan_id'
                            )
                        ),
                        array(
                            'table' => 'plan_categories',
                            'alias' => 'PlanCategory',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'PlanCategory.id = Plan.plan_category_id'
                            )
                        )
                    ),
                'conditions' => $prConditions, 'limit' =>$this->limit,'fields'=>$fields,
                 'contain' => array(
                    'PressImage' => array('fields' => array('PressImage.image_name','PressImage.image_path')),
                    'Category' => array('fields' => array('Category.name')),
                ),
                'order' => 'PressRelease.release_date DESC'));
                $this->set(compact('data_arr','content'));
                $this->render('index');
            }else{
                $this->redirect('/notfound');
            } 
    }

    public function category(){
        $content=(isset($this->request->query['c'])&&!empty($this->request->query['c']))?$this->request->query['c']:'summary';
            $slug=($this->request->query)?$this->request->query['cat']:''; 
        $hostname="";
        if($this->RequestHandler->isRss()&&!empty($slug)) {     
        $filterby='category';
        $this->RequestHandler->respondAs('application/xml');
        $prconditions = array();
     
            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 

            $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Category.slug'=>$slug,'PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
            if(isset($this->request->query['pc'])&&!empty($this->request->query['pc'])){
                $pCategoryIds = $this->Category->find('list', array('fields'=>array('Category.id','Category.id'),'conditions' => array('status' => 1, 'parent_id' =>$this->request->query['pc'])));    
                $pCategoryIds=array_values($pCategoryIds);
                $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Category.id'=>$pCategoryIds,'PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
                
            }
            $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date'];
            $data_arr=$this->PressRelease->find('all',array(
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
                        ),
                        array(
                            'table' => 'plans',
                            'alias' => 'Plan',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Plan.id = PressRelease.plan_id'
                            )
                        ),
                        array(
                            'table' => 'plan_categories',
                            'alias' => 'PlanCategory',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'PlanCategory.id = Plan.plan_category_id'
                            )
                        )
                    ),
                'conditions' => $prConditions, 'limit' =>$this->limit,
                 'contain' => array( 
                    'PressImage' => array('fields' => array('PressImage.image_name','PressImage.image_path')),
                    'Category' => array('fields' => array('Category.name')),
                    'PressPoadcast' => array('fields' => array('PressPoadcast.url')),
                    'PressYoutube' => array('fields' => array('PressYoutube.url')),
                ),
                'group'=>'PressRelease.id',
                'order' => 'PressRelease.release_date DESC',)); 
            $this->set(compact('data_arr','content','hostname'));
            $this->render('index'); 
        }else{
            $this->redirect('/notfound');
        }
    }

    public function msa() {
        $hostname='';
        $slug=($this->request->query)?$this->request->query['s']:''; 
        $content=(isset($this->request->query['c'])&&!empty($this->request->query['c']))?$this->request->query['c']:'summary';
            $filterby='category';
            $this->RequestHandler->respondAs('application/xml');
            $prconditions = array();

           if($this->RequestHandler->isRss()&&!empty($slug)){ 
            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 
            $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','Msa.slug'=>$slug,'PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
            
             $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date',];
            $data_arr=$this->PressRelease->find('all',array(
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
                        ),
                        array(
                            'table' => 'plans',
                            'alias' => 'Plan',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'Plan.id = PressRelease.plan_id'
                            )
                        ),
                        array(
                            'table' => 'plan_categories',
                            'alias' => 'PlanCategory',
                            'type' => 'INNER',
                            'conditions' => array( 
                                'PlanCategory.id = Plan.plan_category_id'
                            )
                        )
                    ),
                'conditions' => $prConditions, 'limit' =>$this->limit,'fields'=>$fields,
                 'contain' => array(
                'PressImage' => array('fields' => array('PressImage.image_name','PressImage.image_path')),
                'Category' => array('fields' => array('Category.name')),
                'PressPoadcast' => array('fields' => array('PressPoadcast.url')),
                'PressYoutube' => array('fields' => array('PressYoutube.url'))
                ),
                'order' => 'PressRelease.release_date DESC'));
             
            $this->set(compact('data_arr','content','hostname'));
                $this->render('index');
        }else{
            $this->redirect('/notfound');
        }
    }



    public function headlines() {  
           $content=(isset($this->request->query['c'])&&!empty($this->request->query['c']))?$this->request->query['c']:'summary';
           $this->RequestHandler->respondAs('application/xml');
            $prConditions = array('PlanCategory.feed_publish_type'=>'gwn','PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 

            $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date',];
            $data_arr=$this->PressRelease->find('all',array('joins' => array(
                array(
                    'table' => 'plans',
                    'alias' => 'Plan',
                    'type' => 'INNER',
                    'conditions' => array( 
                        'Plan.id = PressRelease.plan_id'
                    )
                ),
                array(
                    'table' => 'plan_categories',
                    'alias' => 'PlanCategory',
                    'type' => 'INNER',
                    'conditions' => array( 
                        'PlanCategory.id = Plan.plan_category_id'
                    )
                )
            ),
            'conditions' => $prConditions, 'limit' =>$this->limit,'fields'=>$fields,
                 'contain' => array(
                'PressImage' => array('fields' => array('PressImage.image_name','PressImage.image_path')),
                'Category' => array('fields' => array('Category.name')),
                'PressPoadcast' => array('fields' => array('PressPoadcast.url')),
                'PressYoutube' => array('fields' => array('PressYoutube.url'))
                ),
                'order' => 'PressRelease.release_date DESC'));
 
        $this->set(compact('data_arr','content'));
        $this->render('index');
         // $this->render("xml/headlines");  
    
    }

    public function gif(){ 
        $this->layout ='default';
        header('Content-type: image/gif');
        echo base64_decode('R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');  
        if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
            $id=$this->request->query['v'];
            $feedurl= $_SERVER['HTTP_REFERER'];
            $domain= $this->Custom->get_domain(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST));
            $this->updateClippingReport($id,$domain,$feedurl,'rss_feed');
        }
        $this->autoRender=false;
    }

     public function updateClippingReport($prId='',$hostname='',$releasePageUrl='',$distributionType=''){
        if(!empty($hostname)){
            $site_name=$hostname;
            $extractHostname=explode(".",$hostname);
            if(is_array($extractHostname)){
                $elementCount=count($extractHostname);
                $site_name= $extractHostname[$elementCount-2];
            }
            $conditions = array(
                     'ClippingReport.press_release_id' => $prId,
                     'ClippingReport.site_name' => $site_name,
                     'ClippingReport.distribution_type' => $distributionType
                );
                  
            $check= $this->ClippingReport->find('first',array('conditions' =>$conditions,"fields"=>array("ClippingReport.id","ClippingReport.views") ) );
          
            if(!empty($check['ClippingReport'])){
                $data['ClippingReport']['views']=$check['ClippingReport']['views']+1;
                $data['ClippingReport']['id']=$check['ClippingReport']['id'];
                $data['ClippingReport']['release_page_url']=$releasePageUrl;
            }else{
                $data['ClippingReport']['distribution_type']=$distributionType;
                $data['ClippingReport']['press_release_id']=$prId;
                $data['ClippingReport']['domain']='http://'.rtrim($hostname,"/");
                $data['ClippingReport']['site_name']=ucfirst($site_name);
                $data['ClippingReport']['release_page_url']=$releasePageUrl;
                $data['ClippingReport']['views']=1;
                $this->ClippingReport->create();
            }
            $this->ClippingReport->save($data);
        
       }  
    }


    /* Rss Fied According to plan */ 
    public function pnsxml() { 
        $hostname="";
        $this->RequestHandler->respondAs('application/xml');
        if($this->RequestHandler->isRss()) {
            $content=(isset($this->request->query['c'])&&!empty($this->request->query['c']))?$this->request->query['c']:'full';
            $prConditions = array('PlanCategory.feed_publish_type'=>'pns','PressRelease.status' => 1,"PressRelease.release_date <="=>date('Y-m-d'));
            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Msa','State','Distribution'),'belongsTo'=>array('Plan','Company'))); 

            $fields=['PressRelease.id','PressRelease.title','PressRelease.slug','PressRelease.body','PressRelease.summary','PressRelease.release_date',];
            $data_arr=$this->PressRelease->find('all',array('joins' => array(
                array(
                    'table' => 'plans',
                    'alias' => 'Plan',
                    'type' => 'INNER',
                    'conditions' => array( 
                        'Plan.id = PressRelease.plan_id'
                    )
                ),
                array(
                    'table' => 'plan_categories',
                    'alias' => 'PlanCategory',
                    'type' => 'INNER',
                    'conditions' => array( 
                        'PlanCategory.id = Plan.plan_category_id'
                    )
                )
            ),'conditions' => $prConditions, 'limit' =>$this->limit,'fields'=>$fields,
                 'contain' => array(
                    'PressImage' => array('fields' => array('PressImage.image_name','PressImage.image_path')),
                    'Category' => array('fields' => array('Category.name')),
                ),
                'order' => 'PressRelease.release_date DESC')); 
            $this->set(compact('data_arr','content'));
            $this->render('index');
        }
    }
 
}
