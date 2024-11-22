<?php

class Language extends AppModel {
    public $name = 'Language';
    public $actsAs = array('Containable','Search.Searchable');  
    public $filterArgs = array(
        array('name'=>'name','type'=>'like'),    
    );
	public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'name cannot be empty.',
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This name has already been taken.',
                'on' => 'create', // or: 'update'
            ),
        ),
    );
    
    
    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        App::uses('Inflector', 'Utility');
        if(!empty($this->data['Language']['slug'])){
            $slug=$this->data['Language']['slug'];
        }else{
             $slug=(isset($this->data['Language']['name']))?$this->data['Language']['name']:"";
        }
        if(!empty($slug)){
             $this->data['Language']['slug'] = Inflector::slug(strtolower($slug), '-');
         }
     }

}
