<?php

class Category extends AppModel {

    public $name = 'Category';
    public $actsAs = array('Tree');
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            )
        ),
        // 'isUnique' => array(
        //     'rule' => 'isUnique',
        //     'message' => 'Category already in exist.',
        //     'on'=>'create'
        // )
         
    );

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        App::uses('Inflector', 'Utility');
        if (!empty($this->data['Category']['name'])) { //echo "hi"; die;
            // $pSlug=$this->getPerentSlug($this->data['Category']['parent_id']); $pSlug.'-'.
            $this->data['Category']['slug'] = strtolower(Inflector::slug($this->data['Category']['name'], '-'));
        }
    }

    function getPerentSlug($id=''){
        $slug='';
        if(!empty($id)){
            $data=$this->find('first',['conditions'=>$id]);
            $slug= $data['Category']['slug'];
        }
        return $slug;
    }

}
