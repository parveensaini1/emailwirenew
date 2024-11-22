<?php

App::uses('AppController', 'Controller');

/**

 * TakeOverCompanies Controller

 *

 * @property TakeOverCompany $TakeOverCompany

 */

class TakeOverCompaniesController extends AppController {

    public $name = 'TakeOverCompanies';

    public function beforeFilter() {

        parent::beforeFilter();

        $this->set('controller', 'TakeOverCompanies');

        $this->set('model', 'TakeOverCompany');

    }

    /**

     * index method

     *

     * @return void

     */

     public function index() {

        $this->set('title_for_layout', __('All TakeOver Companies'));

        if(isset($this->request->params['named']['page']) && !empty($this->request->params['named']['page']) && isset($this->params->query['keyword']) && !empty($this->params->query['keyword'])){
            $url = str_replace('/page:'.$this->request->params['named']['page'],'',$_SERVER['REQUEST_URI']);
            $url = str_replace('admin/','',$url);
            $this->redirect($url);
        }

        $conditions = array();

        if (isset($this->params->query['keyword'])) {

            $keyword = $this->params->query['keyword'];

            $conditions[] = array('or' => array(

                    'TakeOverCompany.title like' => '%' . $keyword . '%',

            ));

        }

        $limit=Configure::read('Admin.paging'); 

        $this->TakeOverCompany->bindModel(

               array(

                 'belongsTo'=>array(

                     'StaffUser'=>array(

                      'className' => 'StaffUser',

                      'foreignKey' => 'staff_user_id',

                      'fields'=>array("id",'email','first_name',"last_name"),

                    )        

               )

            )

        );

        $this->paginate = array('conditions' => $conditions, 'order' => 'TakeOverCompany.id ASC', 'limit' => $limit);

        $data_array = $this->paginate('TakeOverCompany');  

        $this->set('data_array', $data_array);

    } 

}



