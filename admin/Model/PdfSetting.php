<?php

class PdfSetting extends AppModel {

    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'PdfSetting';
    /**
     * Validation
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Title cannot be empty.',
                'last' => true,
            ),
        ),
        /*
        'welcome_text' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Welcome text cannot be empty.',
                'last' => true,
            ),
        ),
        'network_description' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Network description cannot be empty.',
                'last' => true,
            ),
        ),
        */
        'email_distribution_description' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Email distribution cannot be empty.',
                'last' => true,
            ),
        )
    );

}

?>