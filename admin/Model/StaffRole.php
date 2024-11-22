<?php

class StaffRole extends AppModel {

    public $name = 'StaffRole';

	public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Title cannot be empty.',
                'last' => true,
            )
        ),
        'alias' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This alias has already been taken.',
                'last' => true,
            ),
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Alias cannot be empty.',
                'last' => true,
            ),
            'validAlias' => array(
                'rule' => 'alphaNumeric',
                'message' => 'This field must be alphanumeric',
                'last' => true,
            ),
        ),
    );

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        App::uses('Inflector', 'Utility');
        $this->data['StaffRole']['alias'] = Inflector::slug($this->data['StaffRole']['title'], '-');
    }

}
