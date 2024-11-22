<?php
class Plan extends AppModel {
    public $name = 'Plan';     
    public $belongsTo=array('PlanCategory');
    public $validate = array(
        'plan_type' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            )
        ),
        'plan_category_id' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            )
        ), 
        'words_release_amount' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            )
        ),
        'number_pr' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            )
        ),
        'pr_per_day' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            )
        ),
         'cycle_period' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            )
        ),
    ); 


    public function beforeSave($options = array()) {
        parent::beforeSave($options);
            App::uses('Inflector', 'Utility');
    }
}