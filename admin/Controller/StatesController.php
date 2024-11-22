<?php
class StatesController extends AppController {
    public $name = 'States';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('menutitle', 'States');
        $this->set('menutitle_add', 'State');
        $this->set('controller', 'states');
        $this->set('model', 'State');
    } 

    public function index() {
        $this->set('title_for_layout', __('All States'));
        $keyword = '';
        $this->set('showTrashButton',$this->Custom->showTrashButton($this->modelClass,$this->trashStatus));
        $conditions[] = array('State.status !=' =>$this->trashStatus);
        $this->Prg->commonProcess();
        $searchValues=$this->{$this->modelClass}->data[$this->modelClass]=$this->passedArgs; 
        $keyword = (!empty($searchValues)&&!empty($searchValues["name"]))?$searchValues["name"]:""; 
        $conditions[] = $this->{$this->modelClass}->parseCriteria($this->passedArgs);
        $this->set('keyword', $keyword);
        $this->loadModel('Country');
        $country_list = $this->Country->find('list',array('conditions' => array('status' =>1)));
        $this->set('country_list', $country_list); 
        $this->paginate = array('conditions' => $conditions, 'order' => 'State.name asc', 'limit' =>$this->limit);
        $data = $this->paginate('State');
        $this->set('data_array', $data);
    }

    public function add() {
        $this->set('title_for_layout', __('Add a state'));
        $this->loadModel('Country');
        $country_list = $this->Country->find('list',array('conditions' => array('status' =>1)));
        $this->set('country_list', $country_list);

        if (!empty($this->data)) { 
            if ($this->State->save($this->request->data)) {
                $this->clearcache('state_list');
                $this->Session->setFlash(__('Detail successfully added'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        }
    }

    public function edit($id=null) {
        $this->set('title_for_layout', __('Edit state'));
        try{
            if(!$id){
                throw new NotFoundException('Invalid page.');
            }
            $id=base64_decode($id);
            $this->State->id=$id;
            if(!$this->State->exists()) {
                throw new NotFoundException('Invalid page.');
            }  
            $this->loadModel('Country');
            $country_list = $this->Country->find('list',array('conditions' => array('status' =>1)));
            $this->set('country_list', $country_list);

            if (!empty($this->data)) {
                
                if ($this->State->save($this->request->data)) {
                    $this->clearcache('state_list');
                    $this->Session->setFlash(__('Detail successfully updated'), 'success');
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
                }
            } else {
                $this->request->data = $this->State->read(null, $id);
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
            $this->State->id=$id;
            if(!$this->State->exists()) {
                throw new NotFoundException('Invalid request.');
            }   
            if($this->movetotrash("State",$id)){
                $this->clearcache('state_list');
                $this->Session->setFlash(__('Detail successfully deleted'), 'success');
                $this->redirect(array('action' => 'index'));
            }
         }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        }  
    }

    public function trashed() {
        $this->set('title_for_layout', __('All States'));
        $keyword = '';
        $conditions[] = array('State.status' =>$this->trashStatus);
     
        $this->Prg->commonProcess();
        $searchValues=$this->{$this->modelClass}->data[$this->modelClass]=$this->passedArgs; 
        $keyword = (!empty($searchValues)&&!empty($searchValues["name"]))?$searchValues["name"]:""; 
        $conditions[] = $this->{$this->modelClass}->parseCriteria($this->passedArgs);   
        $this->set('keyword', $keyword);
        $this->loadModel('State');
 
        $this->paginate = array('conditions' => $conditions, 'order' => 'State.name asc', 'limit' =>$this->limit);
        $data = $this->paginate('State');
        $this->set('data_array', $data);
        $this->render("index");
    }
    public function restore($id = null) {
        try{
            if(!$id){
                throw new NotFoundException('Invalid request.');
            }
            $id=base64_decode($id);
            $this->State->id=$id;
            if(!$this->State->exists()) {
                throw new NotFoundException('Invalid request.');
            }  
            if ($this->restorefromtrash("State",$id)){
                $this->clearcache('state_list');
                $this->Session->setFlash(__('Detail successfully deleted'), 'success');
                $this->redirect(array('action' => 'index'));
            }

        }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        } 
    }
}
