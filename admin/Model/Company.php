<?php



class Company extends AppModel {

    public $name = 'Company';

     public $actsAs = array('Containable');

    // public $hasAndBelongsToMany = array('StaffUser');
    public $belongsTo = array('StaffUser');
    public $hasMany = [
                        'CompanyDocument'=>array('className' => 'CompanyDocument','foreignKey' => 'company_id'),
                        'CompanyPresentation'=>array('className' => 'CompanyPresentation','foreignKey' => 'company_id'),
                        'CompanyPodcast'=>array('className' => 'CompanyPodcast','foreignKey' => 'company_id'),
                        'CompanyEbook'=>array('className' => 'CompanyEbook','foreignKey' => 'company_id'),
                    ];  

                    
    public $hasOne = array('Transaction' =>array('className' => 'Transaction','foreignKey' => 'company_id'));

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
        )
    );
}

