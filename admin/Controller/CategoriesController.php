<?php

App::uses('AppController', 'Controller');

class CategoriesController extends AppController {

    public $name = 'Categories';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'categories');
        $this->set('model', 'Category');
    }

    public function index() {
        $this->set('title_for_layout', __('All categories'));
        $this->loadModel('Category');
        $parent_conditions = [];
        $data_array = [];
        $parent_conditions = array('Category.is_deleted' =>'0','Category.parent_id'=>0);
        $parent = $this->Category->find('list',array('conditions'=>$parent_conditions,'order'=>'name'));
        foreach ($parent as $key => $value) {
                $child_conditions = array('Category.is_deleted' =>'0','Category.parent_id'=>$key);
                $child = $this->Category->find('list',array('conditions'=>$child_conditions,'order'=>'name'));
                $child = preg_filter('/^/', '__', $child);
                $key_possition = (array_search($key,array_keys($parent))+1);
                $res = array_slice($parent, 0, $key_possition, true) + $child + array_slice($parent, $key_possition, count($parent) - 1, true) ;
                $parent = $res;
        }
        $data_array = $parent;

        $this->set('data_array', $data_array);
    }

    public function add() {
//        $this->loadModel('NayaIndia');
//        $data = $this->NayaIndia->getCategory();
//       // pr($data); die;
//        foreach ($data as $da) {
//            @header('Content-Type: text/html; charset=utf-8');
//
//
//
//            $new = array();
//            $new['Category']['id'] = $da['tt']['id'];
//            $new['Category']['term_taxonomy_id'] = $da['tt']['term_taxonomy_id'];
//            $new['Category']['parent_id'] = $da['tt']['parent'];
//            $new['Category']['name'] = $da['t']['post_title'];
//            $new['Category']['slug'] = $da['t']['post_url'];
//            $this->Category->create();
//
//            $this->Category->save($new);
//            //pr($new); die;
//        }
//        pr($data);
//        die;
        $this->set('title_for_layout', __('Add a new category'));
        if (!empty($this->data)) {
            if ($this->Category->save($this->request->data)) { 
                $this->clearcache('category_list');
                $this->clearcache('parent_categories');
                $this->Session->setFlash(__('Detail successfully added'), 'message', array('class' => 'success'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        }
        $data_array = $this->Category->generateTreeList(
                null, null, null, '___'
        );

        $this->set('data_array', $data_array);
    }

    public function edit($id) {
        $this->set('title_for_layout', __('Edit category'));
        $data_array = $this->Category->generateTreeList(
                null, null, null, '___'
        );
        $this->set('data_array', $data_array);
        if (!empty($this->data)) {
            if ($this->Category->save($this->request->data)) {
                $this->clearcache('parent_categories');
                $this->clearcache('category_list');
                $this->Session->setFlash(__('Detail successfully updated'), 'message', array('class' => 'success')); 
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'message', array('class' => 'error'));                 
            }
        } else {
            $this->request->data = $this->Category->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->Category->id = $id;
        if (!$this->Category->exists()) {            
            $this->Session->setFlash(__('Invalid id.'), 'message', array('class' => 'error'));    
        }
        $this->request->data['Category']['id'] = $id;
        $this->request->data['Category']['is_deleted'] = 1;
        if ($this->Category->save($this->request->data)) {
            $this->clearcache('category_list');
                $this->clearcache('parent_categories');
            $this->Session->setFlash(__('Detail successfully deleted'), 'message', array('class' => 'success'));             
            $this->redirect(array('action' => 'index'));
        }
    }
    
    public function movedown($id = null, $delta = null) {
        $this->Category->id = $id;
        if (!$this->Category->exists()) {
            throw new NotFoundException(__('Invalid category'));
        }

        if ($delta > 0) {
            $this->Category->moveDown($this->Category->id, abs($delta));
            $this->Session->setFlash(__('Detail successfully updated'), 'success');
        } else {
            $this->Session->setFlash('Please provide a number of positions the category should' .
                    'be moved up.', 'error');
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function moveup($id = null, $delta = null) {
        $this->Category->id = $id;
        if (!$this->Category->exists()) {
            throw new NotFoundException(__('Invalid category'));
        }

        if ($delta > 0) {
            $this->Category->moveUp($this->Category->id, abs($delta));
            $this->Session->setFlash(__('Detail successfully updated'), 'success');
        } else {
            $this->Session->setFlash('Please provide a number of positions the category should' .
                    'be moved up.', 'error');
        }

        return $this->redirect(array('action' => 'index'));
    }

}
