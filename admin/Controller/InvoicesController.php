<?php

App::uses('AppController', 'Controller');

App::uses('Xml', 'Utility');

use Dompdf\Dompdf;

class InvoicesController extends AppController {

	public $name = 'Invoice';

    public $uses = array('Company','Transaction');

    public function beforeFilter() {

        parent::beforeFilter(); 

        $this->set('controller', 'Invoices');

        $this->set('model', 'Transaction');

        $this->set('currencySymbol',Configure::read('Site.currency')); 

    }



    public function index(){

        $this->loadModel('Invoice');

        $this->set('model', 'Invoice');        

    	$this->set('placeholder','Please enter Transaction id,Name,Email..');

        $this->set('title_for_layout', __('All Plan Invoice')); 


        if(isset($this->request->params['named']['page']) && !empty($this->request->params['named']['page']) && isset($this->params->query['s']) && !empty($this->params->query['s'])){
            $url = str_replace('/page:'.$this->request->params['named']['page'],'',$_SERVER['REQUEST_URI']);
            $url = str_replace('admin/','',$url);
            $this->redirect($url);
        }


        $this->Invoice->bindModel(array('belongsTo'=>['StaffUser']));

        $data_array=[]; 

        $this->Invoice->recursive="2"; 

        $conditions[]=array('Invoice.transaction_type' =>'plannewsroom','Invoice.tx_id !='=>null); 

        if(isset($this->params->query['s'])&&!empty($this->params->query['s'])){

            $this->set('keyword',$this->params->query['s']);

            $conditions[]=array('OR'=>array(

                'Invoice.tx_id like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.first_name like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.email like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.last_name like ' => '%'.trim($this->params->query['s']). '%'

            ));

        }



        $this->Invoice->recursive="-1";

     $fields=array('Invoice.id','Invoice.tx_id','StaffUser.first_name','StaffUser.last_name','subtotal','total','status',"payment_date");



    $this->paginate = array('conditions' => $conditions,'order' => 'Invoice.id DESC', 'limit' => '15');

    $data_array = $this->paginate('Invoice');  

 

    $this->set(compact("data_array"));

    }





    public function prinvoice($value=''){

        $this->set('placeholder','Please enter Transaction id,Name,Email..');

        $this->set('title_for_layout', __('All Invoice')); 

         $data_array=[]; 

        $this->Transaction->recursive="2";


        $conditions = [];

        // $conditions[]=array('Transaction.transaction_type' =>'pr'); 

        if(isset($this->params->query['s'])&&!empty($this->params->query['s'])){

            $this->set('keyword',$this->params->query['s']);

            $conditions[]=array('OR'=>array(

                'Transaction.tx_id like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.first_name like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.email like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.last_name like ' => '%'.trim($this->params->query['s']). '%'

            ));

        }



        $this->Transaction->recursive="-1";

        $fields=array('Transaction.id','Transaction.tx_id','StaffUser.first_name','StaffUser.last_name','subtotal','total','status',"payment_date");



        $this->paginate = array('conditions' => $conditions,'order' => 'Transaction.id DESC', 'limit' => '15');


        $data_array = $this->paginate('Transaction');  


        $this->set(compact("data_array"));


        $this->render('index');

    }



    public function view($id=''){

        $this->set('title_for_layout', __('View Invoice')); 

        $transdata=[];  

        $this->Transaction->recursive=2;

        $data=$this->Transaction->read(null,$id);

        if($data['Transaction']['transaction_type']=='pr'){

            $this->loadModel('TransactionPressRelease');

            $transdata  = $this->TransactionPressRelease->find("first",array('conditions'=>array('transaction_id'=>$id)));

        } 

        $this->set(compact('data','transdata'));

        



    }





    public function user_plan_invoice($plan_id='',$userId=''){

        $this->set('title_for_layout', __('View user Invoice')); 

        $this->loadModel('Transaction');

        $this->Transaction->unbindModel(array('belongsTo'=>array('StaffUser')));

        $data_array  = $this->Transaction->find("all",array(

              'joins' => array(

                        array(

                            'table' => 'transaction_plans',

                            'alias' => 'TransactionPlan',

                            'type' => 'INNER',

                            'conditions' => array( 

                               'TransactionPlan.transaction_id = Transaction.id'

                            )

                        ), 

                    ),



            'conditions'=>array('staff_user_id'=>$userId,'transaction_type'=>"plannewsroom","plan_id"=>$plan_id)) );

         

        $this->set(compact('data_array'));

    }





    public function sendinvoice($id=''){

        $transdata='';

        $this->Transaction->recursive=2;

        $data_array=$this->Transaction->read(null,$id);

        if($data_array['Transaction']['transaction_type']=='pr'){

            $this->loadModel('TransactionPressRelease');

            $transdata  = $this->TransactionPressRelease->find("first",array('conditions'=>array('transaction_id'=>$id)));

        } 

        $this->generatePdfDownloadReceipt($data_array,$transdata);

        $this->Session->setFlash(__('Invoice sent successfully.'), 'success');



        $this->redirect(array('action' => 'view',$id));

        die;

    }

    function generatePdfDownloadReceipt($data_array,$transdata=''){ 

        include_once(APP . 'Vendor' . DS . 'dompdf/autoload.inc.php');

        $dompdf = new Dompdf(); 

        $dompdf->set_option('enable_remote', TRUE);

        if($data_array['Transaction']['transaction_type']=='plannewsroom'){

            $invoicetype="Plan";

            $html=$this->Custom->getPlanInvoiceHtml($data_array); 

            $msg=$this->Custom->getPlanInvoiceHtmlForMail($data_array); 

        }else{

             $invoicetype="Press Release";

            $html=$this->Custom->getPrInvoiceHtml($data_array,$transdata); 

            $msg=$this->Custom->getPrInvoiceHtmlForMail($data_array,$transdata); ; 

        }

        $dompdf->load_html($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        

        $filename = uniqid()."-Invoice.pdf";

        $fileUrl=ROOT .DS. 'app' . DS . 'webroot' . DS .'files'. DS . 'clippingreport' . DS . $filename;

        file_put_contents($fileUrl, $dompdf->output());    

        $username=ucfirst($data_array['StaffUser']['first_name'])." ".$data_array['StaffUser']['last_name'];

        if($this->Custom->sendInvoiceMail($data_array['StaffUser']['email'],$msg,$username,"Emailwire $invoicetype invoice"," $invoicetype Invoice", $fileUrl)){unlink($fileUrl);

            return true; 

        } 

        return false; 

    }





    public function settings($id=null) {

           $this->loadModel('PdfSetting'); 

            $this->set('title_for_layout', __('Pdf Setting'));

            if($this->PdfSetting->validates($this->request->data)){



            if (isset($this->request->data) && !empty($this->request->data)) {

                if( isset($this->request->data['PdfSetting']['logo']) && !empty($this->request->data['PdfSetting']['logo']) && !empty($this->request->data['PdfSetting']['logo']['name'])){

                            

                            $url =  Router::url( null, true );

                            $filename = $this->request->data['PdfSetting']['logo']['name'];

                            $file_size = $this->request->data['PdfSetting']['logo']['size'];

                            $ext = pathinfo($filename, PATHINFO_EXTENSION);

                            $allowed = array('jpeg', 'jpg', 'png', 'gif', 'bmp');

                            if( ! in_array( $ext, $allowed ) ){

                            $this->Session->setFlash(__("Please upload 'jpeg', 'jpg', 'png', 'gif', 'bmp' file only."), 'error');

                            return $this->redirect(array('action' => 'settings/'.$id));

                            }else if(($file_size > 2097152)){

                                

                                $this->Session->setFlash(__('uploaded file is greater than 2MB.'), 'error');

                                return $this->redirect(array('action' => 'settings/'.$id));



                           }



                            $imgInfo = pathinfo($this->request->data['PdfSetting']['logo']['name']);



                            $this->request->data['PdfSetting']['logo']['name']  =   $imgInfo['filename'].'_users.'.$imgInfo['extension'];



                            $logoImageValue   =   $imgInfo['filename'].'_'.time().'.'.$imgInfo['extension'];

                            move_uploaded_file($this->request->data['PdfSetting']['logo']['tmp_name'],'/home/netleonin/public_html/email_wire/app/webroot/files/pdf_settings/'.$logoImageValue);

                            

                            $this->request->data['PdfSetting']['logo']          =   $logoImageValue;

                            

                        }else{

                            $pdf_data   =$this->PdfSetting->find('first',array("conditions"=>array('PdfSetting.id' => $id)));

                            $this->request->data['PdfSetting']['logo']      =   $pdf_data['PdfSetting']['logo'];

                        }

                    if ($this->PdfSetting->save($this->request->data)) {

                        $this->Session->setFlash(__('Detail successfully updated'), 'success');

                        //return $this->redirect(array('action' => 'index'));

                    } else {

                        $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');

                    }



                }else{

                    $this->request->data = $this->PdfSetting->read(null, $id);

                }

            }

        }

                     

}

