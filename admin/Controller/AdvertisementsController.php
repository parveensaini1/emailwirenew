<?php

/**
 * Advertisements Controller
 *
 * @property Page $Page
 */
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class AdvertisementsController extends AppController {
    public $name = 'Advertisements'; 
    public $uses=array('Advertisement');
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('menutitle', 'Advertisements');
        $this->set('menutitle_add', 'Advertisement');
        $this->set('controller', 'advertisements');
        $this->set('model', 'Advertisement');
    }
    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->set('title_for_layout', __('All Advertisements'));
        $limit= strip_tags(Configure::read('Admin.paging'));
        $conditions=[];
        $this->paginate = array('conditions' => $conditions, 'order' => 'Advertisement.id DESC', 'limit' =>$limit);
        $data_array = $this->paginate('Advertisement');
        $this->set(compact('data_array'));
    }
    /**
     * add method
     *
     * @return void
     */
    public function add() {
        $this->set('title_for_layout', __('Add Advertisement'));
        if(!empty($this->data)){
                try{
                    if ($this->data['Advertisement']['image']['name']!= '') {
                        $file_path =ROOT.DS.'app'.DS.'webroot'.DS.'files'.DS.'ads';
                        $delfile = new File($file_path, false, 0777);
                        $image=uniqid().".png";
                        if(move_uploaded_file($this->data['Advertisement']['image']['tmp_name'], $file_path.DS.$image)){
                            unset($this->request->data['Advertisement']['image']);
                            $this->request->data['Advertisement']['image'] =$image; 
                        }
                    }else{
                        unset($this->request->data['Advertisement']['image']);
                        $this->request->data['Advertisement']['image']=null;
                    }
                    $this->Session->setFlash(__('Ads successfully added.'), 'success');
                    $this->Advertisement->save($this->request->data);
            }catch(Exception $e){
                $this->Session->setFlash($e->getMessage(), 'error');
            }
            $this->redirect(array('action' => 'index'));
        }
    }
    
    public function edit($id=null) {
        $this->set('title_for_layout', __('Edit Advertisement'));
        if(!empty($this->data)){
            try{
                if ($this->data['Advertisement']['new_image']['name']!= '') {
                    $file_path =ROOT.DS.'app'.DS.'webroot'.DS.'files'.DS.'ads';
                    $delfile = new File($file_path, false, 0777);
                    $image=uniqid().".png";
                    if(move_uploaded_file($this->data['Advertisement']['new_image']['tmp_name'], $file_path.DS.$image)){
                        unset($this->request->data['Advertisement']['new_image']);
                    
                    $fileDestination = WWW_ROOT.'/files/ads/';
                    if(file_exists($fileDestination.$this->data['Advertisement']['image'])){
                        $delfile = new File($fileDestination.DS.$this->data['Advertisement']['image'], false, 0777);
                        $delfile->delete();
                    }
                    $this->request->data['Advertisement']['image'] =$image;
                    }
                }else{
                    unset($this->request->data['Advertisement']['new_image']); 
                }
                $this->Session->setFlash(__('Ads successfully updated.'), 'success');
                $this->Advertisement->save($this->request->data);
            }catch(Exception $e){
                $this->Session->setFlash($e->getMessage(), 'error');
            }
            $this->redirect(array('action' => 'index'));
        }
        $this->request->data = $this->Advertisement->read(null, $id);
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->Advertisement->id = $id;
        if(!$this->Advertisement->exists()){
            throw new NotFoundException(__('Invalid page'));
        }
         $data = $this->Advertisement->find("first",array('conditions'=>array('Advertisement.id'=>$id)));
        if ($this->Advertisement->delete()) {
            $fileDestination = WWW_ROOT.'/files/ads/';
            if(file_exists($fileDestination.$data['Advertisement']['image'])){
                $delfile = new File($fileDestination.DS.$data['Advertisement']['image'], false, 0777);
                $delfile->delete();
            }

            $this->Session->setFlash(__('Advertisement deleted'), 'success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Advertisement deleted successfully'), 'error');
        $this->redirect(array('action' => 'index'));
    }


    public function disapprove($id = null) {
        $this->Advertisement->id = $id;
        if(!$this->Advertisement->exists()){
            throw new NotFoundException(__('Invalid page'));
        }
        $savedata  = array();
        $savedata['Advertisement']['status']="0";
        if($this->Advertisement->save($savedata)){
            $this->Session->setFlash(__('Advertisement inactive successfully'), 'success');
            $this->redirect(array('action' => 'index'));
        }else{
            $this->Session->setFlash(__('Advertisement not inactive'), 'error');
        }
        
        
    }

    public function approve($id = null) {
        $this->Advertisement->id = $id;
        if(!$this->Advertisement->exists()){
            throw new NotFoundException(__('Invalid page'));
        }
        $savedata  = array();
        $savedata['Advertisement']['status']="1";
        if($this->Advertisement->save($savedata)){
            $this->Session->setFlash(__('Advertisement active successfully'), 'success');
            $this->redirect(array('action' => 'index'));
        }else{
            $this->Session->setFlash(__('Advertisement not active'), 'error');
        }
        
        
    }
}

