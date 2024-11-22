<?php

App::uses('AppModel', 'Model');

/**
 * EmailTemplate Model
 *

 */
class EmailTemplate extends AppModel {

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field cannot be empty.',
                'last' => true,
            )
        ),
        'subject' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field cannot be empty.',
                'last' => true,
            )
        ),
        'from' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field cannot be empty.',
                'last' => true,
            )
        ),
        'reply_to_email' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field cannot be empty.',
                'last' => true,
            )
        ),
        'description' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field cannot be empty.',
                'last' => true,
            )
        ),
        'alias' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This alias has already been taken.',
                'last' => true,
            ),
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Alias cannot be empty.',
                'last' => true,
            ),
            'validAlias' => array(
                'rule' => 'alphaNumeric',
                'message' => 'This field must be alphanumeric',
                'last' => true,
            ),
        ),
    );

    public function beforeSave($options = array()) {


        parent::beforeSave($options);

        if (!isset($this->data['EmailTemplate']['id'])) {

            App::uses('Inflector', 'Utility');

            $this->data['EmailTemplate']['alias'] = Inflector::slug($this->data['EmailTemplate']['title'], '-');
        }
    }

    public function selectTemplate($tempName) {

        $emailTemplate = $this->find('first', array(
            'conditions' => array(
                'EmailTemplate.alias' => $tempName
            ),
            'fields' => array(
                'EmailTemplate.description',
                'EmailTemplate.subject',
                'EmailTemplate.title',
                'EmailTemplate.from',
                'EmailTemplate.reply_to_email',
                'EmailTemplate.is_html'
            ),
            'recursive' => -1
        ));


        return $emailTemplate['EmailTemplate'];
    }

}
