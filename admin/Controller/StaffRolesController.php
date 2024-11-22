<?php

App::uses('AppController', 'Controller');

class StaffRolesController extends AppController {

    public $name = 'StaffRoles';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'staffRoles');
        $this->set('model', 'StaffRole');
    }

    public function index() {
        $this->set('title_for_layout', __('All roles'));
        $this->paginate = array('order' => 'StaffRole.id desc', 'limit' => '15');
        $pageList = $this->paginate('StaffRole');
        $this->set('data_array', $pageList);
    }

    public function add() {
        $this->set('title_for_layout', __('Add a new role'));
        if (!empty($this->data)) {
            if ($this->StaffRole->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully added'), 'success');
                return $this->redirect(array('action' => 'index'));
            }else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        }
    }

    public function edit($id) {
        $this->set('title_for_layout', __('Edit role'));
        if (!empty($this->data)) { 
            if ($this->StaffRole->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully updated'), 'success');
                return $this->redirect(array('action' => 'index'));
            }else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->StaffRole->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->StaffRole->id = $id;
        if (!$this->StaffRole->exists()) {
             throw new NotFoundException('Invalid id', 'error');
        } 
        if ($this->StaffRole->delete()) {
            $this->Session->setFlash(__('Detail successfully deleted'), 'success');
            $this->redirect(array('action' => 'index'));
        }
    }
}