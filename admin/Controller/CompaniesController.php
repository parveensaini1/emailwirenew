<?php

App::uses('AppController', 'Controller');

class CompaniesController extends AppController {
    public $name = 'Companies';
    public function beforeFilter() {

        parent::beforeFilter();

        $this->set('controller', 'companies');

        $this->set('model', 'Company');

    }

    public function index() {
        
        $this->set('title_for_layout', __('All Company'));
        $this->Company->recursive=2;
        $this->paginate = array('limit' => Configure::read('Admin.paging'), 'order' => 'Company.id DESC');
        $data_array = $this->paginate('Company');
        $this->set('data_array', $data_array);
    }



    public function add() {

        $this->set('title_for_layout', __('Add a new company'));        

        $this->loadModel('Country');

        $this->set('country_list',$this->Country->find('list'));

        if (!empty($this->data)) {

            if ($this->Company->save($this->request->data)) {

                $this->Session->setFlash(__('Detail successfully added'), 'success');

                return $this->redirect(array('action' => 'index'));

            } else {

                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');

            }

        }

    }



    public function edit($id) {

        $this->set('title_for_layout', __('Edit company')); 

        $this->set('country_list',$this->Country->find('list'));

        if (!empty($this->data)) {

            if ($this->Company->save($this->request->data)) {

                $this->Session->setFlash(__('Detail successfully updated'), 'success');

                return $this->redirect(array('action' => 'index'));

            } else {

                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');

            }

        } else {

            $this->request->data = $this->Company->read(null, $id);

        }

    }



    public function delete($id = null) {

        $this->Company->id = $id;

        if (!$this->Company->exists()) {

            throw new NotFoundException('Invalid id', 'error');

        }

        if ($this->Company->delete()) {

            $this->Session->setFlash(__('Detail successfully deleted'), 'success');

            $this->redirect(array('action' => 'index'));

        }

    } 

}

