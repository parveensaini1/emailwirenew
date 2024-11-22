<?php

App::uses('AppController', 'Controller');

/**

 * Pages Controller

 *

 * @property Page $Page

 */

class PagesController extends AppController {

    public $name = 'Pages';

    public function beforeFilter() {

        parent::beforeFilter();

        $this->set('menutitle', 'Pages');

        $this->set('menutitle_add', 'Page');

        $this->set('controller', 'pages');

        $this->set('model', 'Page');

    }

    /**

     * index method

     *

     * @return void

     */

    public function index() {

        $this->set('title_for_layout', __('All Pages'));

        $conditions = array();

        if(isset($this->request->params['named']['page']) && !empty($this->request->params['named']['page']) && isset($this->params->query['keyword']) && !empty($this->params->query['keyword'])){
            $url = str_replace('/page:'.$this->request->params['named']['page'],'',$_SERVER['REQUEST_URI']);
            $url = str_replace('admin/','',$url);
            $this->redirect($url);
        }

        if (isset($this->params->query['keyword'])) {

            $keyword = $this->params->query['keyword'];

            $conditions[] = array('or' => array(

                    'Page.title like' => '%' . $keyword . '%',

            ));

        }

        $limit=Configure::read('Admin.paging');

        $this->paginate = array('conditions' => $conditions, 'order' => 'Page.id ASC', 'limit' => $limit);

        $data_array = $this->paginate('Page');

        $this->set('data_array', $data_array);

    }

    /**

     * add method

     *

     * @return void

     */

    function getParentCategory($PageParentId,$pageName){

        if($PageParentId==''){

             $url =strtolower(Inflector::slug($pageName, '-'));

        }else{

            $i=0;

            $slugUrl=array();

            $loopmax=1;

            while ($i<$loopmax) {

                $conditions=array('Category.parent_id' => $PageParentId);

                if($i!=0){

                    $conditions=array('Category.id' => $PageParentId);

                }

                $data=$this->Category->find('first',array('conditions' => $conditions,'fields'=>array('Category.slug','Category.parent_id'),'recursive' => -1));

                $PageParentId=$data['Category']['parent_id'];

                $slugUrl[]= strtolower(Inflector::slug($data['Category']['slug'], '-'));

                if($PageParentId!='' && $PageParentId!=0){

                    $loopmax++;

                }else{

                    break;

                }    

                $i++;

            }

            $seprator = $url ="";

            foreach (array_reverse($slugUrl) as $catId) {

                    $url .= $seprator.$catId;

                    $seprator = "/";

            }

        }

       return $url;

    }  

    public function add() {

        $this->set('title_for_layout', __('Add a new page'));

		 $this->loadModel("PageTemplate");

        $pageTemplate=$this->PageTemplate->find('list',array('conditions'=>array('PageTemplate.type'=>1),'fields'=>array('id','template_name'),'order'=>'template_name asc'));

        $this->set('page_template_list',$pageTemplate);

        if (!empty($this->data)) {

            if(empty($this->data['Page']['banner_image']))

                $this->request->data['Page']['banner_image']=null;



            if(empty($this->data['Page']['banner_image']))

                $this->request->data['Page']['banner_path']=null;



            if ($this->Page->save($this->request->data)) {

                $this->Session->setFlash(__('Detail successfully added'), 'success');

                return $this->redirect(array('action' => 'index'));

            } else {

                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');

            }

        }

    }

    public function edit($id) {

        $this->set('title_for_layout', __('Edit page'));

		$this->loadModel("PageTemplate");

        if (!empty($this->data)) {

            

            if(empty($this->data['Page']['banner_image']))

                $this->request->data['Page']['banner_image']=null;



            if(empty($this->data['Page']['banner_image']))

                $this->request->data['Page']['banner_path']=null;



           if ($this->Page->save($this->request->data)) { 

                $this->Session->setFlash(__('Detail successfully added'), 'success');

                return $this->redirect(array('action' => 'index'));

            } else {

                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');

            }

        }

		$pageTemplate=$this->PageTemplate->find('list',array('conditions'=>array('PageTemplate.type'=>1),'fields'=>array('id','template_name')));

        $this->set('page_template_list',$pageTemplate);

        $this->request->data = $this->Page->read(null, $id);

        $this->set('slug',$this->request->data['Page']['slug']);

    }







    /**

     * delete method

     *

     * @param string $id

     * @return void

     */



    public function delete($id = null) {



        $this->Page->id = $id;



        if (!$this->Page->exists()) {



            throw new NotFoundException(__('Invalid page'));



        }







        if ($this->Page->delete()) {



            $this->Session->setFlash(__('Page deleted'), 'success');



            $this->redirect(array('action' => 'index'));



        }



        $this->Session->setFlash(__('Page deleted successfully'), 'error');



        $this->redirect(array('action' => 'index'));



    }







}



