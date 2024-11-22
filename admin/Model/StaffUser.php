<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
class StaffUser extends AppModel {
    public $name = 'StaffUser';
    public $ActAs=array('Containable'); 
    public $belongsTo = array('StaffRole');
    // public $hasAndBelongsToMany = array('Company');
    public $hasMany = array('Company');
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter first name.',
            ),
        ),
        'current_password' => array(
            'rule1' => array(
                'rule' => 'check_currnt_password',
                'message' => 'You have entered a wrong password',
            )
        ),
        'password' => array(
            'rule1' => array(
                'rule' => array('minLength', 8),
                'message' => 'Password must be at least 8 characters',
            ),
            'rule2' => array(
                'rule' => array('maxLength', 20),
                'message' => 'Password must be 20 characters max',
            ),
        ),
        'verify_password' => array(
            'rule1' => array(
                'rule' => array('minLength', 8),
                'message' => 'Password must be at least 8 characters',
            ),
            'rule2' => array(
                'rule' => array('maxLength', 20),
                'message' => 'Password must be 20 characters max',
            ),
            'rule' => 'validIdentical',
        ),
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address',
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Email address already in use',
            )
        ),
       // 'phone' => array(
       //     'required' => array(
       //         'allowEmpty' => true, // use notBlank as of CakePHP 2.7
       //          'message' => 'Please enter a valid.',
       //     ),
       //     'numeric' => array(
       //         'rule' => 'numeric',
       //         'message' => 'Numbers only'
       //     ),
       //     'phone-2' => array(
       //         'last' => true,
       //         'rule' => array('phone', '/^[0-9]( ?[0-9]){8} ?[0-9]$/'),
       //         'message' => 'Please enter a valid phone number'
       //     )
       // ),
        // 'profile_image' => array(
        //     'extension' => array(
        //         'rule' => array('extension', array('jpg', 'png')),
        //         'message' => 'Only jpg and png  files',
        //     ),
        //     'upload-file' => array(

        //         'rule' => array('uploadFile'),

        //         'message' => 'Error uploading file'

        //     )

        // ) 

    );



    public function beforeSave($options = array()) {

        parent::beforeSave($options);

        if (!empty($this->data['StaffUser']['password'])) {

            $this->data['StaffUser']['password'] = AuthComponent::password($this->data['StaffUser']['password']); 

        } else {

            unset($this->data['StaffUser']['password']);

        }

        return true;

    }



    public function check_currnt_password() {

        if (!empty($this->data['StaffUser']['current_password'])) {

            $this->recursive = -1;

            $user = $this->find('first', array('conditions' => array('StaffUser.id' => CakeSession::read('Auth.User.id'))));

            if (AuthComponent::password($this->data['StaffUser']['current_password']) != $user['StaffUser']['password']) {

                return false;

                }

            return true;

        }

    }



    /**

     * _identical

     *

     * @param string $check

     * @return boolean

     * @deprecated Protected validation methods are no longer supported

     */

    protected function _identical($check) {

        return $this->validIdentical($check);

    }



    /**

     * validIdentical

     *

     * @param string $check

     * @return boolean

     */

    public function validIdentical($check) {

        if (isset($this->data['StaffUser']['password'])) { 

            if ($this->data['StaffUser']['password'] != $check['verify_password']) {

                return 'Please put same password which is use in password';

            }

        }

        return true;

    }



}

