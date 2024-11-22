<?php

class NewsletterMailList extends AppModel {

    public $name = 'NewsletterMailList';
    public $belongsTo = array('PressRelease');
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'This fields can not be left blank.'
            )
        ),
        'isUnique' => array(
            'rule' => 'isUnique',
            'message' => 'Category already in exist.',
            'on'=>'create'
        )
         
    );

   

}
