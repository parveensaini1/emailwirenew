<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

App::uses('Inflector', 'Utility');


class NetworkWebsitesController extends AppController {
    public $name = 'NetworkWebsites'; 
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('menutitle', 'Network Website');
        $this->set('menutitle_add', 'Network Website');
        $this->set('controller', 'NetworkWebsites');
        $this->set('model', 'NetworkWebsite');
    }

    public function index() {
        
        $this->set('title_for_layout', __('All Network Websites'));
        $keyword = '';
       $this->set('showTrashButton',$this->Custom->showTrashButton($this->modelClass,$this->trashStatus));
       $conditions[] = array('NetworkWebsite.status !=' =>$this->trashStatus);
        $this->Prg->commonProcess();
        $searchValues=$this->{$this->modelClass}->data[$this->modelClass]=$this->passedArgs; 
        
        $keyword = (!empty($searchValues)&&!empty($searchValues["name"]))?$searchValues["name"]:""; 
        
        if (isset($searchValues['category'])) {
            
            if ($searchValues['category'] == 1 || $searchValues['category'] == 3) {
                
                $conditions[] = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
            } elseif ($searchValues['category'] == 2) {
                
                $conditions[] = [
                    'NetworkWebsite.website_media_type LIKE' => '%' . $keyword . '%'
                ];
            }
           
        }

        $this->set('keyword', $keyword);
        $this->paginate = array('conditions' => $conditions, 'order' => 'NetworkWebsite.website_name ASC', 'limit' => $this->limit);
        $data = $this->paginate('NetworkWebsite');
        $this->set('data_array', $data);
    }

    // public function index() {        
    //     $conditions=[];
    //     if(isset($this->request->params['named']['page']) && !empty($this->request->params['named']['page']) && isset($this->params->query['s']) && !empty($this->params->query['s'])){
    //         $url = str_replace('/page:'.$this->request->params['named']['page'],'',$_SERVER['REQUEST_URI']);
    //         $url = str_replace('admin/','',$url);
    //         $this->redirect($url);
    //     }

    //     $this->set('title_for_layout', __('EmailWire Global Media Distribution List'));

    //     $this->set('placeholder', 'Please enter title..');

    //     if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

    //         $this->set('keyword', $this->params->query['s']);

    //         $conditions[] = array('NetworkWebsite.website_name like ' => '%' . $this->params->query['s'] . '%');

    //     }

    //     $this->paginate = array('conditions' =>$conditions,'limit' => $this->limit, 'order' => 'NetworkWebsite.id DESC');

        
    //      $data_array= $this->paginate('NetworkWebsite');

    //     $this->set('data_array', $data_array);

    //     $page_number=$this->request->paging['NetworkWebsite']['page'];

    //     $this->set('page_number', $page_number);



    // }



    public function add($selectedplan_type='single') { 
        try{
            $this->set('title_for_layout', __('Add New Network Websites'));

            if (!empty($this->data)){ 
                //$dirFile = $_SERVER['DOCUMENT_ROOT'].'/app/webroot/files/networkwebsite'; 
                $dirFile = ROOT.DS.'app'.DS.'webroot'.DS.'files'.DS.'networkwebsite';
                $dir = new Folder($dirFile, true, 0755);

                if (!file_exists($dirFile)) {

                    mkdir($dirFile, 0777, true);

                }

                if(!empty($this->request->data['NetworkWebsite']['website_logo']['tmp_name'])){
                    $ext = pathinfo($this->request->data['NetworkWebsite']['website_logo']['name'], PATHINFO_EXTENSION);
                    $fileName=Inflector::slug(strtolower($this->request->data['NetworkWebsite']['website_logo']['name']), '-').".$ext";
                    $moved = move_uploaded_file($this->request->data['NetworkWebsite']['website_logo']['tmp_name'], $dirFile.DS.$fileName);
                    $filename = $this->request->data['NetworkWebsite']['website_logo']['name'];
                    unset($this->request->data['NetworkWebsite']['website_logo']);
                    $this->request->data['NetworkWebsite']['website_logo'] = $fileName;

                }else{
                    $this->request->data['NetworkWebsite']['website_logo'] = null;
                    unset($this->request->data['NetworkWebsite']['website_logo']);
                }
                
    
                if ($this->NetworkWebsite->save($this->request->data)) {
                    $this->Session->setFlash(__('Detail successfully added'), 'success');
                    return $this->redirect(array('action' => 'index'));

                } else {
                    $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
                }

            }
        }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        } 

    }



    public function edit($id = null) {
        try{
            if(!$id){
                throw new NotFoundException('Invalid request.');
            }
            $id=base64_decode($id);

            $this->set('title_for_layout', __('Edit Website')); 

            if (!empty($this->data)) {
                
                // $abcde=$this->request->data['NetworkWebsite']['website_media_type'];
                //$dirFile = $_SERVER['DOCUMENT_ROOT'].'/app/webroot/files/networkwebsite'; // jaswinder code
                $dirFile = ROOT.DS.'app'.DS.'webroot'.DS.'files'.DS.'networkwebsite';
                $dir = new Folder($dirFile, true, 0755);
                if (!file_exists($dirFile)) {
                    mkdir($dirFile, 0777, true);
                }
                
                if(!empty($this->request->data['NetworkWebsite']['website_logo']['tmp_name'])){
                    $ext = pathinfo($this->request->data['NetworkWebsite']['website_logo']['name'], PATHINFO_EXTENSION);
                    $fileName=Inflector::slug(strtolower($this->request->data['NetworkWebsite']['website_logo']['name']), '-').".$ext";
                    $moved = move_uploaded_file($this->request->data['NetworkWebsite']['website_logo']['tmp_name'], $dirFile.DS.$fileName);
                    $filename = $this->request->data['NetworkWebsite']['website_logo']['name'];
                    unset($this->request->data['NetworkWebsite']['website_logo']);
                    $this->request->data['NetworkWebsite']['website_logo'] = $fileName;
                }else{
                    unset($this->request->data['NetworkWebsite']['website_logo']);
                } 
                $this->request->data['NetworkWebsite']['id'] = $id;
                if ($this->NetworkWebsite->save($this->request->data)) {
                    
                    $this->Session->setFlash(__('Detail successfully updated'), 'success');
                    $this->loadModel("NwRelationship");
 
                    $sv['NwRelationship']['network_website_id'] = $id;
                    $sv['NwRelationship']['website_domain'] = "'".$this->data['NetworkWebsite']['website_domain']."'";
                    $sv['NwRelationship']['site_name'] = "'".trim($this->data['NetworkWebsite']['website_name'])."'";
                    $sv['NwRelationship']['type'] = "'".$this->data['NetworkWebsite']['website_media_type']."'";
                    $sv['NwRelationship']['potential_audience'] = "'".$this->data['NetworkWebsite']['potential_audience']."'";
                    $sv['NwRelationship']['location'] =  "'".$this->data['NetworkWebsite']['website_location']."'";
                    if(!empty($fileName)){
                        $sv['NwRelationship']['site_logo'] = "files/networkwebsite/".$fileName;
                    } 
                    $this->NwRelationship->updateAll($sv['NwRelationship'],array('NwRelationship.network_website_id' =>$id));
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
                }
            } else {
                $this->request->data = $this->NetworkWebsite->read(null, $id);

            }
        }catch(Exception $exc){
            // $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        } 

    }



    public function trash($id = null) {
        try{
            if(!$id){
                throw new NotFoundException('Invalid request.');
            }
            $id=base64_decode($id);
            $this->NetworkWebsite->id=$id;
            if(!$this->NetworkWebsite->exists()) {
                throw new NotFoundException('Invalid request.');
            }   
            if($this->movetotrash("NetworkWebsite",$id)){
                $this->clearcache('networkwebsite_list');
                $this->Session->setFlash(__('Detail successfully trashed.'), 'success');
                $this->redirect(array('action' => 'index'));
            }
         }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        }  
    }
    public function trashed() {
        $this->set('title_for_layout', __('All NetworkWebsite'));
        $keyword = '';
        $conditions[] = array('NetworkWebsite.status' =>$this->trashStatus);
     
        $this->Prg->commonProcess();
        $searchValues=$this->{$this->modelClass}->data[$this->modelClass]=$this->passedArgs; 
        $keyword = (!empty($searchValues)&&!empty($searchValues["name"]))?$searchValues["name"]:""; 
        $conditions[] = $this->{$this->modelClass}->parseCriteria($this->passedArgs);   
        $this->set('keyword', $keyword);
        $this->paginate = array('conditions' => $conditions, 'order' => 'NetworkWebsite.website_name asc', 'limit' =>$this->limit);
        $data = $this->paginate('NetworkWebsite');
        $this->set('data_array', $data);
        $this->render("index");
    }
    public function restore($id = null) {
        try{
            if(!$id){
                throw new NotFoundException('Invalid request.');
            }
            $id=base64_decode($id);
            $this->NetworkWebsite->id=$id;
            if(!$this->NetworkWebsite->exists()) {
                throw new NotFoundException('Invalid request.');
            }  
            if ($this->restorefromtrash("NetworkWebsite",$id)){
                $this->clearcache('networkwebsite_list');
                $this->Session->setFlash(__('Detail successfully restore.'), 'success');
                $this->redirect(array('action' => 'index'));
            }

        }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        } 
    }

    
    public function delete($id = null) {
        try{
            if(!$id){
                throw new NotFoundException('Invalid request.');
            }
            $id=base64_decode($id);
            $this->NetworkWebsite->id=$id;
            if(!$this->NetworkWebsite->exists()) {
                throw new NotFoundException('Invalid request.');
            }    
            if ($this->NetworkWebsite->delete($id)) {
                $this->Session->setFlash(__('Detail successfully deleted'), 'success', array('class' => 'success'));
                $this->redirect(array('action' => 'index'));
            }
         }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        }  
    }

}

