<?php

App::uses('AppController', 'Controller');

class OrganizationTypesController extends AppController {

    public $name = 'OrganizationTypes';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'organizationTypes');
        $this->set('model', 'OrganizationType');
    }

    public function index() {
        $this->set('title_for_layout', __('All Organizations'));
        $this->paginate = array('limit' => Configure::read('Admin.paging'), 'order' => 'OrganizationType.name ASC');
        $data_array = $this->paginate('OrganizationType');
        $this->set('data_array', $data_array);
    }

    public function add() {
        $this->set('title_for_layout', __('Add a new Organization'));        
        if (!empty($this->data)) {
            if ($this->OrganizationType->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully added'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        }
    }

    public function edit($id) {
        $this->set('title_for_layout', __('Edit Organization')); 
        if (!empty($this->data)) {
            if ($this->OrganizationType->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully updated'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->OrganizationType->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->OrganizationType->id = $id;
        if (!$this->OrganizationType->exists()) {
            throw new NotFoundException('Invalid id', 'error');
        }
        if ($this->OrganizationType->delete()) {
            $this->Session->setFlash(__('Detail successfully deleted'), 'success');
            $this->redirect(array('action' => 'index'));
        }
    } 
}
