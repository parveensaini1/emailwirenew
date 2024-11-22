<?php 
App::uses('AppController', 'Controller');

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Inflector', 'Utility');
App::uses('Xml', 'Utility');
use Dompdf\Dompdf;
class PaypalsController extends AppController {
    public $name = 'Paypals';
    public $components = array('Cookie');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('controller', 'Paypals');
        $this->set('model', 'Paypal');
        $this->Auth->allow('index','paypal_paypals', 'process_paypals', 'success', 'recurring_response');
    }


    public function index(){
        $this->set('title_for_layout', 'Paypal Payment');

    }

    public function paypal_paypals() {
        $this->set('title_for_layout', 'Paypal');
        
    }

    public function process_paypals() {
        $this->set('title_for_layout', 'Paypal paypals Process');
    }

    public function success() {
        $this->set('title_for_layout', 'Paypal Status');
    }

    public function recurring_response() {
        $this->set('title_for_layout', 'Recurring Response');
    }


}
