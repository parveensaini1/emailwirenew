<?php

App::uses('AppController', 'Controller');

class InvoicesController extends AppController {

    public $name = 'Invoices';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'invoices');
        $this->set('model', 'Invoice');
    }
    
    public function index(){
        
    } 
}
