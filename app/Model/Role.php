<?php

class Role extends AppModel {

    public $name = 'Role';

	public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
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
                'rule' => 'notEmpty',
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
        $this->data['Role']['slug'] = Inflector::slug($this->data['Role']['title'], '-');
    }
}