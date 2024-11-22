<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $thumbWidth=150;
    public $thumbHeight=150;
    public $sliderWidth=800;
    public $sliderHeight=400;
    public $siteEnv='production';
    public $limit='50';
    public $siteName="EmailWire";
    var $helpers = array('Form', 'Html', 'Session', 'Js', 'Text',"Sendy",'Paginator', 'Js' => array("Jquery"), 'Custom', 'Post','Cache');
    public $components = array(
        'Session',
        'RequestHandler',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
            'authError' => 'Invalid login credentials.',
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'StaffUser',
                    'fields' => array('username' => 'email')
                )
            ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'dashboard'
            ),
        ),
        'Email',
        'Cookie',
        'Custom',
        'Qimage',
        'AWSSES'
    );

    function beforeFilter() {
        $this->siteName=strip_tags(Configure::read('Site.name'));
        $this->limit=(!empty(Configure::read('Site.paging')))?strip_tags(Configure::read('Site.paging')):$this->limit;
        parent::beforeFilter();
        $requestUrl=preg_replace('{/$}', '', $this->request->url);
        
        $this->response->disableCache(); 
        $this->set('siteName',$this->siteName);
        $this->set('controller',strtolower($this->params->controller));
        $this->set('action', $this->params->action);
        if (AuthComponent::user()) {
            if(in_array(strtolower($this->params->controller),array("users","pages","plans"))&&in_array($this->Auth->user('staff_role_id'),[1,2]) ){
                $this->redirect('/admin/staffUsers/dashboard');
            }
            $this->set('user_id',$this->Auth->user('id'));
            $this->set('role_id',$this->Auth->user('staff_role_id'));
            if($this->Auth->user('staff_role_id')==3){
                $this->client_top_header($this->Auth->user('id'));
            }
            if(isset($_COOKIE['logged_in'])){
                setcookie('logged_in',$_COOKIE['logged_in'],time()+31556926, '/');
            }
        }
         $this->plancategory();
         $dateformate=strip_tags(Configure::read('Site.DateFromat'));
         $thumbWidth=$this->thumbWidth;
         $sliderWidth=$this->sliderWidth;
         $thumbHeight=$this->thumbHeight;
         $sliderHeight=$this->sliderHeight;
        
         $this->siteEnv=$env=(!empty(Configure::read('Site.Environment')))?strip_tags(Configure::read('Site.Environment')):$this->siteEnv;
         $this->set('env', $env);
         $this->set(compact('sliderWidth','sliderHeight','thumbWidth','thumbHeight','dateformate','requestUrl','env'));
    }

    function beforeRender() {
         $this->_setErrorLayout();
    }

    function _setErrorLayout() {
        if ($this->name == 'CakeError') {  
            $this->layout = 'error';
        }
    }

    public function plancategory(){
        $this->loadModel("PlanCategory");
        $this->PlanCategory->recursive="-1";
        $plancategory=$this->PlanCategory->find('list',array("conditions"=>array('PlanCategory.parent_id'=>"0",'PlanCategory.status'=>"1"),'fields'=>array("PlanCategory.slug","PlanCategory.name"),'order'=>array('PlanCategory.rght ASC')));
        $this->set("plancategory",$plancategory);
    }

    public function client_top_header($user_id){
        if($this->params->controller=='users'||(isset($this->request->pass[0])&&$this->request->pass[0]=='plans')){
            $this->loadModel("CompaniesStaffUser");
            $this->loadModel("PressRelease");
            $this->loadModel("Company");
            // $this->CompaniesStaffUser->bindModel(["belongsTo"=>array("Company")]);
            //$newsroomcount=$this->CompaniesStaffUser->find('count', array('conditions' => array('CompaniesStaffUser.staff_user_id' =>$user_id)));
            $newsroomcount=$this->Company->find('count', array('conditions' => array('Company.staff_user_id' =>$user_id)));
            
            $is_plan_paid=$this->Auth->user('pr_plan_paid');

            if($is_plan_paid==0){
                $this->loadModel('RemainingUserPlan');
                $count=$this->RemainingUserPlan->find('count',["conditions"=>['RemainingUserPlan.staff_user_id' =>$user_id,'RemainingUserPlan.assign_from'=>"admin",'RemainingUserPlan.number_pr >'=>"0"]]);
                if($count>0){
                    $this->clientupdate($user_id);
                    $is_plan_paid=1;
                }
            }
            $pressReleaseCount=$this->PressRelease->find('count',array('conditions'=>array("PressRelease.staff_user_id"=>$user_id))); 
            $this->set(compact('newsroomcount','is_plan_paid','pressReleaseCount'));
        }
    }

    public function clientupdate($user_id=''){
        $this->StaffUser->id=$user_id;
        $this->StaffUser->saveField('pr_plan_paid',1);
        $this->Session->write('Auth.User.pr_plan_paid',1);

    }


        /*
   * @params: 
   * @Function use: clearcache: this function use to reset the cache filed on update data.This is for admin.
   * @created by: Hitesh verma
   * @Created: 06-07-2022
   */
    public function clearcache($field,$CacheType="long"){
        Cache::delete($field,$CacheType);
    }

 
}
