<?php

App::uses('AppModel', 'Model');

class StaffUser extends AppModel
{

    public $hasAndBelongsToMany = array('Category', 'Msa'); 
    public $belongsTo = array('StaffRole'); 
    public $actsAs = array('Containable');
    public $hasMany = array('Company');
    public $validate = array(
        'subscriber_type' => array(
            'notempty' => array(
                'rule' => array('notBlank'),
                'message' => 'This field can not be left blank.',
            ),
        ),
        'first_name' => array(
            'notempty' => array(
                'rule' => array('notBlank'),
                'message' => 'This field can not be left blank.',
            ),
        ),
        'last_name' => array(
            'notempty' => array(
                'rule' => array('notBlank'),
                'message' => 'This field can not be left blank.',
            ),
        ),
        'current_password' => array(
            'rule1' => array(
                'rule' => 'check_currnt_password',
                'message' => 'Current password is not matched with the input password.',
            )
        ),
        'password' => array(
            'rule1' => array(
                'rule' => array('minLength', 8),
                'message' => 'Password must be at least 8 characters.',
            ),
            'rule2' => array(
                'rule' => array('maxLength', 20),
                'message' => 'Password must be 20 characters max.',
            ),
        ),
        'verify_password' => array(
            'rule1' => array(
                'rule' => array('minLength', 8),
                'message' => 'Password must be at least 8 characters.',
            ),
            'rule2' => array(
                'rule' => array('maxLength', 20),
                'message' => 'Password must be 20 characters max.',
            ),
            'rule' => 'validIdentical',
        ),
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address.',
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Email address already in use.',
                'on' => 'create'
            )
        ),
        'confirm_email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address.',
            ),
            'rule' => 'confirm_email'
        ),
        'newsletter_subscription' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),
        'notified_by_email' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),
    );

    function changeFromEmail($from_address = null)
    {

        if (!empty($from_address)) {

            if (preg_match('|<(.*)>|', $from_address, $matches)) {

                return $matches[1];
            } else {

                return $from_address;
            }
        }
    }



    public function phone()
    {

        $mobileNumber = intval($this->data['StaffUser']['mobile_number']); //$mobileNumber is now 447791234567

        if (preg_match('/(^\d{12}$)|(^\d{10}$)/', $mobileNumber) == TRUE) {

            return true;
        } else {

            return false;
        }
    }



    public function beforeSave($options = array())
    {

        parent::beforeSave($options);

        if (!empty($this->data['StaffUser']['password'])) {

            $this->data['StaffUser']['password'] = AuthComponent::password($this->data['StaffUser']['password']);
        } else {

            unset($this->data['StaffUser']['password']);
        }

        return true;
    }



    /**

     * _identical

     *

     * @param string $check

     * @return boolean

     * @deprecated Protected validation methods are no longer supported

     */

    protected function _identical($check)
    {

        return $this->validIdentical($check);
    }



    /**

     * validIdentical

     *

     * @param string $check

     * @return boolean

     */

    public function validIdentical($check)
    {

        if (isset($this->data['StaffUser']['password'])) {

            if ($this->data['StaffUser']['password'] != $check['verify_password']) {

                return 'Passwords do not match. Please, try again';
            }
        }

        return true;
    }



    /**

     * validIdentical

     *

     * @param string $check

     * @return boolean

     */

    public function confirm_email($check)
    {

        if (isset($this->data['StaffUser']['email'])) {

            if ($this->data['StaffUser']['email'] != $check['confirm_email']) {

                return 'Confirm email do not match. Please, try again';
            }
        }

        return true;
    }



    public function check_currnt_password()
    {

        if (isset($this->data['StaffUser']['current_password'])) {

            $this->recursive = -1;

            $user = $this->find('first', array('conditions' => array('StaffUser.id' => CakeSession::read('Auth.User.id'))));

            if (AuthComponent::password($this->data['StaffUser']['current_password']) != $user['StaffUser']['password']) {

                return false;
            }

            return true;
        }
    }
}
