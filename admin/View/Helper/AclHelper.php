<?php

App::uses('Helper', 'View');
App::uses('AppHelper', 'View/Helper');

class AclHelper extends AppHelper {
    
     
    public function getPermission($role_id, $action_id) {
        $obj = ClassRegistry::init('StaffUserAction');
        $obj->recursive = -1;
        return $obj->find('count', array('conditions' => array('StaffUserAction.staff_role_id' => $role_id, 'StaffUserAction.staff_action_id' => $action_id)));
    }

}
