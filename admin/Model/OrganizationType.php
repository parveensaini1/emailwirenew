<?php

class OrganizationType extends AppModel {

    public $name = 'OrganizationType';
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            )
        ),
        'slug' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This slug has already been taken.'
            ),
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Slug cannot be empty.'
            )
        ),
    );
    
     public function beforeSave($options = array()) {
        parent::beforeSave($options);
        App::uses('Inflector', 'Utility');
        if (!isset($this->data['OrganizationType']['id'])) { //echo "hi"; die;
            $this->data['OrganizationType']['slug'] = strtolower(Inflector::slug($this->data['OrganizationType']['slug'], '-'));
        }
    }
}
