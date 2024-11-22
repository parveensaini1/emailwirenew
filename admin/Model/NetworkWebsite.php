<?php
class NetworkWebsite extends AppModel {
    public $name = 'NetworkWebsite';
    public $actsAs = array('Containable','Search.Searchable');  
    public $filterArgs = array(
        array('field'=>'website_name','name'=>'name','type'=>'like'),    
    );
	public $validate = array(
        'slug' => array(
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
        if(!empty($this->data['NetworkWebsite']['slug'])){
             $slug=$this->data['NetworkWebsite']['slug'];
        }else{
             $slug=(isset($this->data['NetworkWebsite']['website_name']))?$this->data['NetworkWebsite']['website_name']:"";
        }
        if(!empty($slug)){
             $this->data['NetworkWebsite']['slug'] = Inflector::slug(strtolower($slug), '-');
         }
     }
}
