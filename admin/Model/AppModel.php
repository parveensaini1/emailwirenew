<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Model', 'Model');
App::uses('SessionComponent', 'Controller/Component');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    function changeFromEmail($from_address = null) {
        if (!empty($from_address)) {
            if (preg_match('|<(.*)>|', $from_address, $matches)) {
                return $matches[1];
            } else {
                return $from_address;
            }
        }
    }

    public function uploadFile($check) {

        $key = key($check);

        $uploadData = array_shift($check);

        $ext = pathinfo($uploadData['name']);

        if ($uploadData['size'] == 0 || $uploadData['error'] !== 0) {
            return false;
        }

        $uploadFolder = WWW_ROOT . 'files' . DS . 'profile_image';
        $fileName = time() . '.' . $ext['extension'];
        $uploadPath = $uploadFolder . DS . $fileName;

        if (!file_exists($uploadFolder)) {
            $oldmask = umask(0);
            mkdir($uploadFolder, 0777);
            umask($oldmask);
        } 
        move_uploaded_file($uploadData['tmp_name'], $uploadPath);
       // $this->compress_resize_image($uploadData['tmp_name'], $uploadPath, 50, 400);
        if (1) {
            if (isset($this->data[$this->alias]['id'])) {
                $this->unlinkFile($key);
            }

            $this->set('profile_image', $fileName);
            SessionComponent::write('Auth.User.profile_image', $fileName);
            $this->data[$this->alias][$key] = $fileName;
            return true;
        }

        return false;
    }

    public function compress_resize_image($src, $dest, $quality = 50, $thum_size = 400) {
        $img = new Imagick();
        $img->readImage($src);
        $img->setImageFormat('jpg');
        $img->setImageCompression(imagick::COMPRESSION_JPEG);
        $img->setImageCompressionQuality($quality);
        $img->stripImage();
        $img->thumbnailImage($thum_size, null);
        $img->writeImage($dest);
    }

    public function unlinkFile($key) {
        if (isset($this->data[$this->alias]['id'])) {
            $files = $this->find('first', array('conditions' => array($this->alias . '.id' => $this->data[$this->alias]['id']), 'fields' => array($this->alias . "." . $key)));
            @unlink(WWW_ROOT . 'files/profile_image/' . $files[$this->alias][$key]);
        }
    }

}
