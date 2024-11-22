<?php
class MsasController extends AppController {
    public $name = 'Msas';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('menutitle', 'Msas');
        $this->set('menutitle_add', 'Msa');
        $this->set('controller', 'msas');
        $this->set('model', 'Msa');
    } 

    public function index() {
        $this->loadModel('State');
        $this->set('title_for_layout', __('All msas'));
        $keyword = '';
        $conditions[]= array('Msa.status !=' =>2);
        $this->Prg->commonProcess();
        $searchValues=$this->{$this->modelClass}->data[$this->modelClass]=$this->passedArgs; 
        $keyword = (!empty($searchValues)&&!empty($searchValues["name"]))?$searchValues["name"]:""; 
        $conditions[] = $this->{$this->modelClass}->parseCriteria($this->passedArgs);   
        $this->set('keyword', $keyword);
        $state_list = $this->Custom->getStateList();
        $this->set('state_list', $state_list); 
        $this->paginate = array('conditions' => $conditions, 'order' => 'Msa.name asc', 'limit' =>$this->limit);
        $data = $this->paginate('Msa');
        $this->set('data_array', $data);
    }

    public function add() {
        $this->set('title_for_layout', __('Add a Msa'));
        $state_list=[];
        $this->loadModel('State');
        $country_list = $this->Custom->getCountryList();
        $this->set(compact('country_list', 'state_list'));

        if (!empty($this->data)) { 
            if ($this->Msa->save($this->request->data)) {
                $this->clearcache('msa_list');
                $this->Session->setFlash(__('Detail successfully added'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        }
    }

    public function edit($id=null) {
        $this->set('title_for_layout', __('Edit Msa')); 
        try{
            if(!$id){
                throw new NotFoundException('Invalid page.');
            }
            $id=base64_decode($id);
            $this->Msa->id=$id;
            if(!$this->Msa->exists()) {
                throw new NotFoundException('Invalid page.');
            }  
            $this->loadModel('State');
            $country_list = $this->Custom->getCountryList();
            
            if (!empty($this->data)) {
                if ($this->Msa->save($this->request->data)) {
                    $this->clearcache('msa_list');
                    $this->Session->setFlash(__('Detail successfully updated'), 'success');
                    return $this->redirect(array('action' => 'index'));
                } else {
                    throw new NotFoundException('Detail not updated. Please, try again.'); 
                }
            } else {
                $data=$this->request->data = $this->Msa->read(null, $id);
            }
            $state_list = $this->State->find("list",["conditions"=>["country_id"=>$data["Msa"]['country_id'],'status'=>"1"]]);
            $this->set(compact('country_list','state_list'));

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
            $this->Msa->id=$id;
            if(!$this->Msa->exists()) {
                throw new NotFoundException('Invalid request.');
            } 
            if (!$this->Msa->exists()) {
                throw new NotFoundException('Invalid request.', 'error');
            }
            if ($this->Msa->saveField('status','2')){
                $this->clearcache('msa_list');
                $this->Session->setFlash(__('Detail successfully deleted.'), 'success');
                $this->redirect(array('action' => 'index'));
            }
        }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index')); 
        } 
    }

}
