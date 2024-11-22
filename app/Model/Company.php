<?php



App::uses('AppModel', 'Model');



class Company extends AppModel {

      public $actsAs = array('Containable');

   
    //   public $hasAndBelongsToMany = array('StaffUser');
    public $belongsTo = array('StaffUser');
    public $hasMany = [
        'CompanyDocument'=>array('className' => 'CompanyDocument','foreignKey' => 'company_id'),
        'CompanyPresentation'=>array('className' => 'CompanyPresentation','foreignKey' => 'company_id'),
        'CompanyPodcast'=>array('className' => 'CompanyPodcast','foreignKey' => 'company_id'),
        'CompanyEbook'=>array('className' => 'CompanyEbook','foreignKey' => 'company_id'),
    ];   
    //   public $hasOne = array('Transaction' =>array('className' => 'Transaction','foreignKey' => 'company_id')); // Due to  1066 Not unique table/alias: 'Transaction'
  
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notBlank'),
                'message' => 'This field can not be left blank.',
            ),
        ),
        'phone_number' => array(
            'notempty' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter a valid phone number.',
            ), 
        ), 
        
        /*
        'address' => array( 
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),
        'city' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),  

        'country_id' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )

        ),

        'organization_type_id' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),

        'job_title' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),
        'contact_name' => array( 
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            ) 
        ),
        'state' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),
        'zip_code' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),
        'description' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),*/



         // 'web_site' => array(
        //     'rule1' => array(
        //         'rule' => 'notBlank',
        //         'message' => 'This field can not be left blank.',
        //     )
        // ),
        // 'hear_about_us' => array(
        //     'rule1' => array(
        //         'rule' => 'notBlank',
        //         'message' => 'This field can not be left blank.',
        //     )
        // )

    );





    public function beforeSave($options = array()) {

        parent::beforeSave($options);

        App::uses('Inflector', 'Utility');

    

        if (isset($this->data['Company']['name'])&&!empty($this->data['Company']['name'])){

            $this->data['Company']['slug'] = strtolower(Inflector::slug($this->data['Company']['name'], '-'));

        }

    }



}

