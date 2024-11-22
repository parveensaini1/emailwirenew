<?php

class Section extends AppModel {

    public $name = 'Section'; 
    
    public $validate = array( 
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This name has already been taken.', 
            ),
        )
    );

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        App::uses('Inflector', 'Utility');
       // $this->data['City']['slug'] = strtolower(Inflector::slug($this->data['City']['name'], '-'));
    }

}
