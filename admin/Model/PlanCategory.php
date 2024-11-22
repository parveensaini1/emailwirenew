<?php
class PlanCategory extends AppModel {
    public $name = 'PlanCategory';
    public $hasMany=array('Plan');
    public $hasAndBelongsToMany = array('Country','Msa','State');
    public $actsAs = array('Tree');
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
       $slug="";
        if(!empty($this->data['PlanCategory']['slug'])){
            $slug=$this->data['PlanCategory']['slug'];
        }elseif(isset($this->data['PlanCategory']['name'])){
            $slug=$this->data['PlanCategory']['name'];
        }
       if(!empty($slug)){
           $this->data['PlanCategory']['slug'] = Inflector::slug(strtolower($slug), '-');
       }
    }
}
