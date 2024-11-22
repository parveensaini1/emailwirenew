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

class AppController extends Controller
{
    public $thumbWidth=150;
    public $thumbHeight=150;
    public $sliderWidth=800;
    public $sliderHeight=400;
    public $limit = 100;
    public $role_id = "";
    public $accessAllow = array(1);
    public $trashStatus = "4";
    public $siteName = "4";
    // public $siteEnv = 'production';
    public $siteEnv = 'development';
    var $helpers = array('Form', 'Html', 'Session', 'Js', 'Text', 'Paginator', 'Js' => array("Jquery"), "Sendy");

    public $components = array(
        'Session',
        'Cookie',
        "Acl",
        'RequestHandler',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'staffUsers',
                'action' => 'login'
            ),
            'authError' => 'Did you really think you are allowed to see that?',
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'StaffUser',
                    'fields' => array('username' => 'email')
                )
            ),
            'logoutRedirect' => array(
                'controller' => 'staffUsers',
                'action' => 'dashboard'
            ),
        ),
        'Paginator', 'Search.Prg' => array(
            'presetForm' => array(
                'paramType' => 'named',
                'model' => null
            ),
            'commonProcess' => array(
                'formName' => null,
                'keepPassed' => true,
                'action' => null,
                'modelMethod' => 'validateSearch',
                'allowedParams' => array(),
                'paramType' => 'named',
                'filterEmpty' => false
            )
        ),
        'Email',
        'Export.Export',
        'Custom',
        'AWSSES'
    );



    function beforeFilter()
    {
        parent::beforeFilter();
        $this->limit=strip_tags(Configure::read('Admin.paging'));
        $this->siteName=strip_tags(Configure::read('Site.name'));
        $this->set('siteName',$this->siteName);
        $accessAllow = $this->accessAllow;

        if (AuthComponent::user() == true) {
            $userName = $this->Auth->user('name');
            $role_id = $this->role_id = $this->Auth->user('staff_role_id');
            $this->set(compact('userName', 'role_id'));
            if ($role_id == 3 || $role_id == 4) {
                $this->redirect(FRONTURL . 'users/dashboard');
            }
        }

        $trashStatusCode = $this->trashStatus;
        $this->set(compact('accessAllow', 'trashStatusCode'));
        $this->response->disableCache(); 
        $this->set('action', $this->params->action);
        //commented by developer for make development version
        // $this->siteEnv = $env = (!empty(Configure::read('Site.Environment'))) ? strip_tags(Configure::read('Site.Environment')) : $this->siteEnv;
        $this->siteEnv = $env = "development";
        $this->set('env', $env);

        if (AuthComponent::user() && !in_array($this->role_id, $this->accessAllow)) {
            if (isset($_COOKIE['logged_in'])) {
                setcookie('logged_in', $_COOKIE['logged_in'], time() + 31556926, '/');
            }

            $role_id = $this->Auth->user('staff_role_id');
            $controller = $this->params->controller;
            $action = $this->params->action;
            $this->loadModel('StaffUserAction');
            $conditions = array();
            $conditions[] = array('StaffUserAction.staff_role_id' => $role_id);
            $conditions[] = array('StaffUserAction.controller_name' => ucfirst($controller) . "Controller");
            $conditions[] = array('StaffUserAction.action_name' => $action);
            $permission = $this->StaffUserAction->find('count', array('conditions' => $conditions));

            if ($this->RequestHandler->isAjax() && !($permission)) {
                echo "NoPermission";
                die;
            }
            

            if (!$permission) {
                $this->Session->setFlash(__('You are not allow to access this location'), 'error');
                //$this->redirect($this->referer());
                // echo "here".$this->Auth->user('role_id');die();
                // if (AuthComponent::user() && $this->Auth->user('role_id') == 1) {
                if (AuthComponent::user() && 1 == 1) {
                    //return $this->redirect(array('action' => 'dashboard'));
                }
                //$this->redirect($this->referer());
            }
        }

        $dateformate=strip_tags(Configure::read('Site.DateFromat'));
        $thumbWidth=$this->thumbWidth;
        $sliderWidth=$this->sliderWidth;
        $thumbHeight=$this->thumbHeight;
        $sliderHeight=$this->sliderHeight;
        $this->set(compact('sliderWidth','sliderHeight','thumbWidth','thumbHeight','dateformate'));
    }

    function beforeRender()
    {
        // $this->_setErrorLayout();
    }

    function _setErrorLayout()
    {

        if ($this->name == 'CakeError') {

            $this->layout = 'error';
        }
    }


    /*
   * @params: 
   * @Function use: clearcache: this function use to reset the cache filed on update data.This is for admin.
   * @created by: Hitesh verma
   * @Created: 06-07-2022
   */
    public function clearcache($field, $CacheType = "long")
    {
        Cache::delete($field, $CacheType);
    }

    /*
   * @params: 
   * @Function use: movetotrash: This is common function for movet to data into trash.
   * @parameter: model name and id. status should be exist in database table.
   * @created by: Hitesh verma
   * @Created: 30-07-2022
   */
    public function movetotrash($model = '', $id, $old_status = "0")
    {
        $this->loadModel($model);
        $saveStatus[$model]['id'] = $id;
        $saveStatus[$model]['status'] = $this->trashStatus;
        return $this->$model->save($saveStatus);
    }

      /*
   * @params: 
   * @Function use: restorefromtrash: This is common function for restore data from trash.
   * @parameter: model name and id. status should be exist in database table.
   * @created by: Hitesh verma
   * @Created: 18-08-2022
   */
  public function restorefromtrash($model='',$id){
    $this->loadModel($model);
    $saveStatus[$model]['id']=$id;
    $saveStatus[$model]['status']='1';
    return $this->$model->save($saveStatus);
}

}
