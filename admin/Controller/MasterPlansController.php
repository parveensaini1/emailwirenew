<?php

App::uses('AppController', 'Controller');

class MasterPlansController extends AppController {

    public $name = 'MasterPlans'; 

    public function beforeFilter() {

        parent::beforeFilter();

        $this->set('menutitle', 'plans');

        $this->set('menutitle_add', 'MasterPlan');

        $this->set('controller', 'MasterPlans');

        $this->set('model', 'MasterPlan');

    }



    public function index() { 
        $this->set('title_for_layout', __('All Master Plans'));
        $this->paginate = array('limit' => Configure::read('Admin.paging'), 'order' => 'MasterPlan.id DESC');
        $data_array = $this->paginate('MasterPlan');
        $this->set('data_array', $data_array);
    }

 

    public function editDuration($id) {
        $this->set('title_for_layout', __('Edit Duration')); 
        if (!empty($this->data)) {
            if ($this->MasterPlan->save($this->request->data)) {
              $this->Session->setFlash(__('Detail successfully updated'), 'success');
              return $this->redirect(array('controller' => 'Distributions', 'action' => 'index'));
            }else{
              $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->MasterPlan->read(null, $id);
        }
       
    }
    
    
     public function editPrice($id) {
        $this->set('title_for_layout', __('Edit Price')); 
        if (!empty($this->data)) {
            if ($this->MasterPlan->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully updated'), 'success');
                return $this->redirect(array('controller' => 'Distributions', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->MasterPlan->read(null, $id);
        }
       
    }

 

    
}
