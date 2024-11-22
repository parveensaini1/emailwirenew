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
class PageTemplate extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'template_name' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'This field can not be left blank.',
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This Template has already been taken.',
            )
        ),
    );


    public function document($uploadData){ 
        if(!empty($uploadData['name'])){ 
            $ext = pathinfo($uploadData['name']);
            $fileType=strtolower($ext['extension']);
            if($fileType=='xlsx'){
                $str='.'.$ext['extension'];
                $imgName=str_replace($str,'',$uploadData["name"]);
                $uploadFolder=WWW_ROOT . 'files' . DS . 'import_excel';
                $fileName =str_replace(array(".", '+'),array("_", "_"),round(microtime(true) * 1000)).".".$ext['extension'];
                $uploadPath = $uploadFolder . DS . $fileName;
                move_uploaded_file($uploadData['tmp_name'], $uploadPath);
                if (1) {
                    return array('status'=>true,'error'=>$fileName,'file_nm'=>$uploadData["name"]);
                }
            }
            return array('status'=>false,'error'=>'File not valid.');
        }
        return array('status'=>false,'error'=>'Please upload Excel file.');
    }

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
            App::uses('Inflector', 'Utility');
            $this->data['PageTemplate']['template_slug'] = Inflector::slug(strtolower($this->data['PageTemplate']['template_name']), '_');
    }

}
