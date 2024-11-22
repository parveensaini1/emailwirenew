<?php

App::uses('AppController', 'Controller');

class StaffActionsController extends AppController {

    public $name = 'StaffActions';
    public $uses = array('StaffAction');
    public $helpers = array('Acl');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'staffActions');
        $this->set('model', 'StaffAction');
    } 
    /**
     * Filter method, listed all method in defined class
     * @param type $class
     * @return type
     */
    public function filterMethods($class) {
        $methods = array();
        foreach ($class as $cls) {
            $ext = pathinfo($cls, PATHINFO_EXTENSION);
            if ($ext == 'php') {
                include APP . 'Controller' . DS . $cls;
                $cls2 = basename($cls, '.php') . PHP_EOL;
                $cls2 = preg_replace('/\s+/', '', $cls2);
                $fn_arr = $this->getDeclaredMethods($cls2);
                if (!empty($fn_arr))
                    $methods[$cls2] = $fn_arr;
            }
        }
        return $methods;
    }

    /**
     * Insert all pending function and listed them
     * @return type
     */
    public function generate() {
        $this->set('title_for_layout', 'Site actions');

        $dir = scandir(APP . 'Controller');
        $new_files = array();
        foreach ($dir as $key => $value) {
            if (strpos($value, ".php") && $value != 'StaffActionsController.php' && $value != 'AppController.php')
                $new_files[] = $value;
        }

        $methods = $this->filterMethods($new_files);

        $count = 0;

        foreach ($methods as $controller => $function) {

            $controller_data = array();
            $controller_data['StaffAction']['name'] = $controller;
            $controller_data['StaffAction']['label'] = $controller;
            $con_data = $this->StaffAction->find('first', array('conditions' => array('StaffAction.name' => $controller), 'fields' => array('StaffAction.id')));
            if (!empty($con_data))
                $controller_data['StaffAction']['id'] = $con_data['StaffAction']['id'];
            $this->StaffAction->create();
            if ($this->StaffAction->save($controller_data)) {
                $function_data = array();
                if (empty($con_data))
                    $function_data['StaffAction']['staff_action_id'] = $this->StaffAction->getLastInsertID();
                else
                    $function_data['StaffAction']['staff_action_id'] = $con_data['StaffAction']['id'];
                foreach ($function as $k => $v) {
                    $this->StaffAction->create();
                    $function_data['StaffAction']['name'] = $v;
                    $function_data['StaffAction']['label'] = $v;
                    $this->StaffAction->save($function_data);
                    $count++;
                }
                unset($function_data['StaffAction']['staff_action_id']);
            }
        }

        $this->Session->setFlash(__("$count Actions Generated successfully"), 'success');
        return $this->redirect(array('action' => 'index'));
    }

    /**
     * Listed all controller list with function
     */
    public function index() {
        $this->StaffAction->bindModel(array('hasMany' => array('StaffAction2' => array('className' => 'StaffAction', 'foreignKey' => 'staff_action_id'))));
        $function_arr = $this->StaffAction->find('all', array('conditions' => array('StaffAction.staff_action_id' => '')));
        $this->set('function_arr', $function_arr);
    }

    public function check_functions($class_name, $function_name) {

        App::uses('AppModel', 'Model');

        $Model = new AppModel(array(
            'name' => 'a',
            'table' => 'staff_actions'
        ));
        $this->StaffAction->recursive = -1;
        $joins = array(
            array(
                'table' => 'staff_actions',
                'alias' => 'b',
                'type' => 'INNER',
                'foreignKey' => false,
                'conditions' => array('b.staff_action_id = a.id'),
            )
        );
 
        $conditions = array();
        $conditions[] = array('a.name' => $class_name, 'b.name' => $function_name);
        return $Model->find('count', array('joins' => $joins, 'conditions' => $conditions, 'fields' => array('a.name', 'b.name')));
    }

    /**
     * Find all method are defind on current class
     * @param type $className
     * @return type
     * 
     */
    function getDeclaredMethods($className) {
        $reflector = new ReflectionClass($className);
        $methodNames = array();
        $lowerClassName = strtolower($className);
        foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (strtolower($method->class) == $lowerClassName) {
                if ($method->name != 'beforeFilter') {
                    if (!$this->check_functions($className, $method->name))
                        $methodNames[] = $method->name;
                }
            }
        }
        return $methodNames;
    }

    /**
     * Used to change action label
     */
    function change_label() {
        $this->request->data['Action']['id'] = $this->data['id'];
        $this->request->data['Action']['label'] = $this->data['label'];
        $this->StaffAction->save($this->data);
        $this->autoRender = false;
    }

    function delete_action_new() {
        $id = $this->data['id'];
        if ($this->data['answer'] == 'no') {
            $this->Action->updateAll(array('Action.is_show' => "'0'"), array("Action.id = '$id'"));
        } else {
            $this->Action->delete($id);
        }

        $this->autoRender = false;
    }

    /**
     * Used to delete class
     */
    function update_status() {
        $id = $this->data['id'];
        $status_data = $this->StaffAction->read(array('StaffAction.status'), $id);
        $data = array();
        $data['StaffAction']['id'] = $id;
        if ($status_data['StaffAction']['status'])
            $data['StaffAction']['status'] = 0;
        else
            $data['StaffAction']['status'] = 1;
        $this->StaffAction->save($data);
        echo $data['StaffAction']['status'];
        $this->autoRender = false;
    }

    /**
     * Used to set permission for the user according to role
     */
    public function permission() {  
        $this->StaffAction->bindModel(array('hasMany' => array('StaffAction2' => array('className' => 'StaffAction', 'foreignKey' => 'staff_action_id','conditions'=>array('StaffAction2.status'=>0)))));
        $function_arr = $this->StaffAction->find('all', array('conditions' => array('StaffAction.staff_action_id' => '','StaffAction.status'=>0)));
        $this->set('function_arr', $function_arr);
        $this->loadModel('StaffRole');
        $conditions = array();
        $this->set('role_list', $this->StaffRole->find('list'));
    }
 
    /**
     * change permission status
     */
    function change_permission() {
        $this->loadModel('StaffUserAction');
        $this->StaffUserAction->recursive = -1;
        $count = $this->StaffUserAction->find('count', array('conditions' => array('StaffUserAction.staff_role_id' => $this->data['role_id'], 'StaffUserAction.staff_action_id' => $this->data['action_id'])));

        if ($count) {
            $this->StaffUserAction->deleteAll(array('StaffUserAction.staff_role_id' => $this->data['role_id'], 'StaffUserAction.staff_action_id' => $this->data['action_id']));
            echo 0;
        } else {
             
            $this->StaffAction->bindModel(array('belongsTo' => array('StaffAction2' => array('className' => 'StaffAction', 'foreignKey' => 'staff_action_id'))));
            $data = $this->StaffAction->find('first', array('conditions' => array('StaffAction.id' => $this->data['action_id'])));
            
            $this->request->data['StaffUserAction']['action_name'] = $data['StaffAction']['name'];
            $this->request->data['StaffUserAction']['controller_name'] = $data['StaffAction2']['name'];
            $this->request->data['StaffUserAction']['staff_role_id'] = $this->data['role_id'];
            $this->request->data['StaffUserAction']['staff_action_id'] = $this->data['action_id'];

            $this->StaffUserAction->save($this->data);
            echo 1;
        }
        $this->autoRender = false;
    } 
}
