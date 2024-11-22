<?php
App::uses('AppController', 'Controller');
/**
 * EmailTemplates Controller
 *
 * @property EmailTemplate $EmailTemplate
 */
class PagetemplatesController extends AppController {
    public $name = 'PageTemplate';
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'pagetemplates');
        $this->set('model', 'PageTemplate');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->set('title_for_layout', 'PageTemplate list');
        $this->paginate = array('order' => 'PageTemplate.id ASC', 'limit' => '15');
        $pageList = $this->paginate('PageTemplate');
        $this->set('data_array', $pageList);
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->set('title_for_layout', 'Update email template');

        $this->PageTemplate->id = $id;
        if (!$this->PageTemplate->exists()) {
            throw new NotFoundException(__('Invalid email template'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->PageTemplate->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully updated.'), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->PageTemplate->read(null, $id);
        }
    }

    public function add($id = null) {
        $this->set('title_for_layout', 'Add new email template');
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->PageTemplate->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully added.'), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->PageTemplate->read(null, $id);
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
