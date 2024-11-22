<?php
App::uses('AppController', 'Controller');
class CountriesController extends AppController {
	public $name = 'Country';
	public $uses = array();
	public function beforeFilter() {
        parent::beforeFilter();
        $this->set('menutitle', 'Countries');
        $this->set('menutitle_add', 'Countries');
        $this->set('controller', 'countries');

        $this->set('model', 'Country');
    
    }
 
      public function index() {
        $this->set('title_for_layout', __('All Countries'));
        $keyword = '';
        $this->set('showTrashButton',$this->Custom->showTrashButton($this->modelClass,$this->trashStatus));
        $conditions = array("status !="=>$this->trashStatus);
        $this->Prg->commonProcess();
        $searchValues=$this->{$this->modelClass}->data[$this->modelClass]=$this->passedArgs; 
        $keyword = (!empty($searchValues)&&!empty($searchValues["name"]))?$searchValues["name"]:""; 
        $conditions[] = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
        $this->set('keyword', $keyword);
        $this->paginate = array('conditions' => $conditions, 'order' => 'Country.name ASC', 'limit' => $this->limit);
        $data = $this->paginate('Country');
        $this->set('data_array', $data);
      }
    public function add() {
        $this->set('title_for_layout', __('Add a new Country'));
        if (!empty($this->data)) {
            if ($this->Country->save($this->request->data)) {
                $this->clearcache('country_list');
                $this->Session->setFlash(__('User successfully added.'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('User not added, Please try again.'), 'error');
            }
           
        }
       
    }
    public function edit($id=null) {
        $this->set('title_for_layout', __('Edit Country'));
        try{
            if(!$id){
                throw new NotFoundException('Invalid page.');
            }
            $id=base64_decode($id);
            $this->Country->id=$id;
            if(!$this->Country->exists()) {
                throw new NotFoundException('Invalid page.');
            } 
            
            if (!empty($this->data)) {
                if ($this->Country->save($this->request->data)) {
                    $this->clearcache('country_list');
                    $this->Session->setFlash(__('Details successfully updated.'), 'success');
                    return $this->redirect(array('action' => 'index'));
                } else {
                    throw new NotFoundException('Details not added, Please try again.');
                }
            }else{
                $this->request->data = $this->Country->read(null, $id); 
            }
        }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        } 
    }

    public function trash($id = null) {
        try{
            if(!$id){
                throw new NotFoundException('Invalid request.');
            }
            $id=base64_decode($id);
            $this->Country->id=$id;
            if(!$this->Country->exists()) {
                throw new NotFoundException('Invalid request.');
            }   
            if($this->movetotrash("Country",$id)){
                $this->clearcache('bhamashah_donation_list');
                $this->Session->setFlash(__('Detail successfully deleted'), 'success');
                $this->redirect(array('action' => 'index'));
            }
         }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        }  
    }
    public function trashed() {
        $this->set('title_for_layout', __('All Country'));
        $keyword = '';
        $conditions[] = array('Country.status' =>$this->trashStatus);
     
        $this->Prg->commonProcess();
        $searchValues=$this->{$this->modelClass}->data[$this->modelClass]=$this->passedArgs; 
        $keyword = (!empty($searchValues)&&!empty($searchValues["name"]))?$searchValues["name"]:""; 
        $conditions[] = $this->{$this->modelClass}->parseCriteria($this->passedArgs);   
        $this->set('keyword', $keyword);
        // $this->loadModel('District');
        // $district_list = $this->District->find('list',array('conditions' => array('status' =>1)));
        // $this->set('district_list', $district_list); 
        $this->paginate = array('conditions' => $conditions, 'order' => 'Country.name asc', 'limit' =>$this->limit);
        $data = $this->paginate('Country');
        $this->set('data_array', $data);
        $this->render("index");
    }
    public function restore($id = null) {
        try{
            if(!$id){
                throw new NotFoundException('Invalid request.');
            }
            $id=base64_decode($id);
            $this->Country->id=$id;
            if(!$this->Country->exists()) {
                throw new NotFoundException('Invalid request.');
            }  
            if ($this->restorefromtrash("Country",$id)){
                $this->clearcache('country_list');
                $this->Session->setFlash(__('Detail successfully restore.'), 'success');
                $this->redirect(array('action' => 'index'));
            }

        }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        } 
    }  
}
