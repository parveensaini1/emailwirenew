<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property User $User
 * @property Page $ParentPage
 * @property Section $Section
 * @property Page $ChildPage
 */
class Page extends AppModel {
    /**
    * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'title' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),
        'description' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            )
        ),
    );
    public function beforeSave($options = array()) {
       parent::beforeSave($options);
       App::uses('Inflector', 'Utility');
       if(!empty($this->data['Page']['slug'])){
            $slug=$this->data['Page']['slug'];
       }else{
            $slug=(isset($this->data['Page']['title']))?$this->data['Page']['title']:"";
       }
       if(!empty($slug)){
            $this->data['Page']['slug'] = Inflector::slug(strtolower($slug), '-');
        }
    }
   }
