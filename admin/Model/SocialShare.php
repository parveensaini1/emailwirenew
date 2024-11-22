<?php

class SocialShare extends AppModel {

    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'SocialShare';

    /**
     * Behaviors used by the Model
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Ordered' => array(
            'field' => 'weight',
            'foreign_key' => false,
        ),
        'Cached' => array(
            'prefix' => array(
                'socialshare_',
            ),
        ),
    );

    /**
     * Validation
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'title' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This key has already been taken.',
                'on'=>'create'
            ),
            'minLength' => array(
                'rule' => array('minLength', 1),
                'message' => 'Key cannot be empty.',
            ),
        ),
    );

    /**
     * afterSave callback
     *
     * @return void
     */
    public function afterSave($created, $options = array()) {
        parent::afterSave($created, $options);
    }
}

?>