<?php

App::uses('AppController', 'Controller');

class SupportsController extends AppController {
	public $components = array('Zoho');
	public $name = 'Supports';
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'supports');
        $this->Auth->allow('zohoauth');
        // $this->set('model', 'clippingreport');
    } 

    public function zohoauth(){
        pr($this->request);
        die;
    }

    public function index(){
    	 
		$data = array(
			'First Name',
			'Last Name',
			'Email',
		);
		$scope = 'Leads';
    	$getdata=$this->Zoho->getRecords($scope, $data);
    	pr($getdata);
    	die;
    }
}
