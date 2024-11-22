<?php

App::uses('AppController', 'Controller');

/**

 * Pages Controller

 *

 * @property Page $Page

 */

class QueriesController extends AppController {

    public $name = 'Queries';

    public $uses =['Query'];

    public function beforeFilter() {

        parent::beforeFilter();

        $this->set('menutitle', 'Queries'); 

        $this->set('controller', 'queries');

        $this->set('model', 'Query');

    }

    /**

     * index method

     *

     * @return void

     */

    public function index() {

        $this->set('title_for_layout', __('All Queries'));

        if(isset($this->request->params['named']['page']) && !empty($this->request->params['named']['page']) && isset($this->params->query['keyword']) && !empty($this->params->query['keyword'])){
            $url = str_replace('/page:'.$this->request->params['named']['page'],'',$_SERVER['REQUEST_URI']);
            $url = str_replace('admin/','',$url);
            $this->redirect($url);
        }

        $conditions = array();

        if (isset($this->params->query['keyword'])) {

            $keyword = $this->params->query['keyword'];

            $conditions[] = array('or' => array(

                    'Query.title like' => '%' . $keyword . '%',

            ));

        }

        $limit=Configure::read('Admin.paging');

        $this->paginate = array('conditions' => $conditions, 'order' => 'Query.id ASC', 'limit' => $limit);

        $data_array = $this->paginate('Query');

        $this->set('data_array', $data_array);

    }

  

    public function replyto($id=''){

        $data=$this->Query->find('first',array('conditions'=>['id'=>$id]));

        $name=ucfirst($data['Query']['contact_name']);

        $emailTo=trim($data['Query']['email']);

        $subject=trim($data['Query']['subject']);

        $reason=(!empty($this->request->query['reason']))?$this->request->query['reason']:"We will contact you soon.";

        $check=$this->Custom->sendemail($emailTo,$reason,$name,"Reply for ". $subject,'EmailWire Query');

        $this->Session->setFlash(__('Mail successfully sent.'), 'success');

        $this->redirect(array("controller"=>"queries",'action' =>'index'));

    }

}



