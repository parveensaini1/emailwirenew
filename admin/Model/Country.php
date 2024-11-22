<?php

class Country extends AppModel {
    public $name = 'Country';
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

}
