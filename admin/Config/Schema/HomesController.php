<?php

App::uses('AppController', 'Controller');

class HomesController extends AppController {

    public $name = 'Homes';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'homes');        
        $this->Auth->allow(array('index'));
    }

    public function index() {
        
    }
 
}