<?php
App::uses('AppController', 'Controller');
/**
 * Distributions Controller
 *
 * @property Distributions $Distributions
 */
class DistributionsController extends AppController {
    public $name = 'Distribution';
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('menutitle','distribution options');
        $this->set('menutitle_add','distribution option');
        $this->set('controller', 'distributions');
        $this->set('model', 'Distribution');
    } 
    public function index() {
        $this->set('title_for_layout', 'Distribution option list');
        $this->paginate = array('order' => 'Distribution.id ASC', 'limit' => '15');
        $data_array = $this->paginate('Distribution');
        
        
         $this->loadModel('MasterPlan');
        $master_plans_array = $this->Custom->getMasterPlanList();
        $this->set(compact('data_array', 'master_plans_array'));
        //$this->set('data_array', $pageList);
    }
 
    public function edit($id = null) {
        $this->set('title_for_layout', 'Update distribution');

        $this->Distribution->id = $id;
        if (!$this->Distribution->exists()) {
            throw new NotFoundException(__('Invalid distribution'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Distribution->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully updated.'), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->Distribution->read(null, $id);
        }
    }
    
    
      public function changeStatus($id,$status) {
        $this->set('title_for_layout', 'Update distribution');
        
        $updateStatus = ($status==1 ? 0 : 1);
        
        $data = ["status"=>$updateStatus];

        $this->Distribution->id = $id;
        if (!$this->Distribution->exists()) {
            throw new NotFoundException(__('Invalid distribution'));
        }
        if ($this->Distribution->save($data)) {
            $this->Session->setFlash(__('Detail successfully updated.'), 'success');
        } else {
            $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
        }
            $this->redirect(array('action' => 'index'));
        
    }

    public function add($id = null) {
        $this->set('title_for_layout', 'Add new distribution option');
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Distribution->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully added.'), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->Distribution->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->EmailTemplate->id = $id;
        if (!$this->EmailTemplate->exists()) {
            throw new NotFoundException(__('Invalid id', 'message', array('class' => 'error')));
        }
        if ($this->EmailTemplate->delete()) {
            $this->Session->setFlash(__('Detail successfully deleted'), 'error');
            $this->redirect(array('action' => 'index'));
        }
    }
}
