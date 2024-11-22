<?php

App::uses('AppController', 'Controller');

class PlansController extends AppController {

    public $name = 'Plans'; 

    public function beforeFilter() {

        parent::beforeFilter();

        $this->set('menutitle', 'plans');

        $this->set('menutitle_add', 'Plan');

        $this->set('controller', 'plans');

        $this->set('model', 'Plan');

    }



    public function index() { 
        $this->set('title_for_layout', __('All Plans'));
        $this->paginate = array('limit' => Configure::read('Admin.paging'), 'order' => 'Plan.id DESC');
        $data_array = $this->paginate('Plan');
        $this->set('data_array', $data_array);
    }

    public function add($selectedplan_type='single') { 
        $this->set('title_for_layout', __('Add a new plan'));        
        if (!empty($this->data)) {
            if($this->request->data['Plan']['plan_type'] == 'single'){
                $this->request->data['Plan']['number_pr'] = 1;
            }
            if ($this->Plan->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully added'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        }
        $this->loadModel('PlanCategory');
        $plan_cat_list=$this->PlanCategory->generateTreeList(array('status'=>1), null, null, '__');
        $this->set(compact("plan_cat_list","selectedplan_type"));
    }

    public function edit($id) {
        $this->set('title_for_layout', __('Edit plan')); 
        if (!empty($this->data)) {
            if($this->request->data['Plan']['plan_type'] == 'single'){
                $this->request->data['Plan']['number_pr'] = 1;
            }
            if ($this->Plan->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully updated'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->Plan->read(null, $id);
        }
        $this->loadModel('PlanCategory');
        $plan_cat_list=$this->PlanCategory->generateTreeList(array('status'=>1), null, null, '__');
        $this->set("plan_cat_list",$plan_cat_list);
    }



    public function delete($id = null) {
        $this->Plan->id = $id;
        if (!$this->Plan->exists()) {
            throw new NotFoundException('Invalid id', 'error');
        }
        if ($this->Plan->delete()) {
            $this->Session->setFlash(__('Detail successfully deleted'), 'success');
            $this->redirect(array('action' => 'index'));
        }
    } 

    public function assign_plans($defaultuserid=''){
        $this->loadModel('StaffUser');
        $this->loadModel('PlanCategory');
        if($this->request->is('post')&&!empty($this->data)){
            $this->loadModel('RemainingUserPlan');
            $plan_id=$this->data['RemainingUserPlan']['plan_id'];
            $user_id=$this->data['RemainingUserPlan']['staff_user_id'];
            $previousplan=$this->RemainingUserPlan->find('first',array('conditions'=>array('RemainingUserPlan.plan_id'=>$plan_id,'RemainingUserPlan.staff_user_id'=>$user_id,'number_pr !='=>'0'),'fields'=>array('number_pr','id'))); 
            $number_pr=$this->Custom->getprnumber($plan_id);
            $this->request->data['RemainingUserPlan']['number_pr']=$number_pr;
            $this->request->data['RemainingUserPlan']['transaction_id']="0";
            $this->request->data['RemainingUserPlan']['assign_from']="admin";
            if(isset($previousplan['RemainingUserPlan']['id'])&&!empty($previousplan['RemainingUserPlan']['id'])){
               $this->request->data['RemainingUserPlan']['id']=$previousplan['RemainingUserPlan']['id'];
               $this->request->data['RemainingUserPlan']['number_pr']=($previousplan['RemainingUserPlan']['number_pr']+$number_pr);
            } 

            if($this->RemainingUserPlan->save($this->request->data)){
                $this->Session->setFlash(__('Plan successfully assigned.'), 'success');
            }else{
                $this->Session->setFlash(__('Plan not added. assigned, try again.'), 'error');   
            }
            // if(isset($this->params->query['redirect'])&&!empty($this->params->query['redirect'])&&$this->params->query['redirect']=='clients'){
            //      $this->redirect("/clients");
            // }
            // $this->redirect(array('controller'=>'','action' => 'active_plans'));
            $this->redirect(array('controller' => 'staffUsers', 'action' => 'activated_plans',$user_id));
        }
        $currencySymbol=Configure::read('Site.currency');
        $this->set('title_for_layout', __('Assign plan to client')); 
        $this->StaffUser->virtualFields['name']='CONCAT(first_name," (",email,")")';
        $client_list=$this->StaffUser->find('list',array('conditions'=>array('staff_role_id'=>3,'status'=>1),'fields'=>['id','name']));
        $this->Plan->virtualFields['name']='CONCAT(cat_name," (",amount,")")';
        $this->PlanCategory->virtualFields['name']='CASE WHEN word_limit >0 THEN CONCAT(PlanCategory.name, " - ",PlanCategory.word_limit," words") ELSE PlanCategory.name END'; 
        $plancat_list =$this->PlanCategory->find('list',array('fields'=>array('id','name'),'conditions' => array('status' => 1)));
        $plan_list=[]; 
        if($plancat_list){
            $this->Plan->recursive=-1;
            foreach ($plancat_list as $pcId => $planCatname) {
            $plan_list[$planCatname]=[]; 
            $getplan =$this->Plan->find('all',array('fields'=>array('id','price','bulk_discount_amount'),'conditions' => array('Plan.plan_category_id'=>$pcId)));
              $getplan=Set::extract('/Plan/.', $getplan);
              if(!empty($getplan)){
                foreach ($getplan as $index => $plan) {
                  $price=($plan['bulk_discount_amount']>0)?$currencySymbol.$plan['bulk_discount_amount']:$currencySymbol.$plan['price'];
                  $categoryname=preg_replace('/\d+/','',str_replace(array("-","words"),array("",""),$planCatname));
                  $plan_list[$planCatname][$plan['id']]=trim($categoryname).' - '.$price;
                }
              }else{
                unset($plan_list[$planCatname]);
              }
            }
        } 
        $defaultPlanId=(isset($this->params->query['plan'])&&!empty($this->params->query['plan']))?$this->params->query['plan']:"";
        $this->set(compact("client_list",'plan_list','defaultPlanId','defaultuserid'));
    }
}
