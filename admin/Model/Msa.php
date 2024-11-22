<?php

class Msa extends AppModel {
    public $name = 'Msa';
    public $actsAs = array('Containable','Search.Searchable');  
    public $belongsTo=['State','Country'];
    public $filterArgs = array(
        array('name'=>'name','type'=>'like'),    
    );
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Msa already in exist.',
                'on'=>'create'
            )
        ), 
    );
    
     public function beforeSave($options = array()) {
        parent::beforeSave($options);
        App::uses('Inflector', 'Utility'); 
        
        if(!empty($this->data['Msa']['name']))
        $this->data['Msa']['slug'] = strtolower(Inflector::slug($this->data['Msa']['name'], '-'));
    }
}
