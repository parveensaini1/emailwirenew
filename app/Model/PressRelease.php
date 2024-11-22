<?php

App::uses('AppModel', 'Model');

class PressRelease extends AppModel {

    public $actsAs = array('Containable');
    public $hasAndBelongsToMany = array('Category','Msa','State','Distribution');
    public $hasMany = array('PressSeo', 'PressYoutube', 'PressImage','PressPoadcast','ClippingReport');
    public $belongsTo = array('Plan','Company','StaffUser',"Language");
     public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Title cannot be empty.',
                'last' => true,
            ),
        ),
         'company_id' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Please select newsroom.',
                'last' => true,
            ),
        ), 
         'tos' => array(
            'notEmpty' => array(
               'rule' => array('comparison', 'equal to', 1),

               'allowEmpty' => false,

               // 'on' => 'create',

               'message' => 'You have to agree Term and conditions'

               

            ),

        ),  

    );



    public function beforeSave($options = array()) {

        parent::beforeSave($options);

        App::uses('Inflector', 'Utility');

        $id=(isset($this->data['PressRelease']['id'])&&!empty($this->data['PressRelease']['id']))?$this->data['PressRelease']['id']:rand(10,9999);



        if (isset($this->data['PressRelease']['title'])&&!empty($this->data['PressRelease']['title'])){

            $this->data['PressRelease']['slug'] = $id.'-'.strtolower(Inflector::slug($this->data['PressRelease']['title'], '-')).'.html';

        }

    }

}