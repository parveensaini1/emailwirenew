<?php

class HomeController extends AppController {

    public $name = 'Home';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'site_default';
        $this->Auth->allow(array('index'));
    }

    public function index() {
        
    }

}
