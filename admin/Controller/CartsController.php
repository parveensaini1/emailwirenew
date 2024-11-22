<?php

App::uses('AppController', 'Controller');

/**

 * Invoices Controller

 *

 * @property Newsroom $Newsroom

 */

class CartsController extends AppController {

	public $name = 'Carts';

    public $uses = array('Company','Cart');

    public function beforeFilter() {

        parent::beforeFilter(); 

        $this->set('controller', 'Carts');

        $this->set('model', 'Cart');

        $this->set('currencySymbol',Configure::read('Site.currency')); 

    }





    public function index(){

        $currency=Configure::read('Site.currency');

        $this->set('placeholder','Please enter first name,Last name,Email');

        $this->set('title_for_layout', __('Cart')); 

        $data_array=[]; 

        $this->Cart->recursive="1";

        if(isset($this->request->params['named']['page']) && !empty($this->request->params['named']['page']) && isset($this->params->query['s']) && !empty($this->params->query['s'])){
            $url = str_replace('/page:'.$this->request->params['named']['page'],'',$_SERVER['REQUEST_URI']);
            $url = str_replace('admin/','',$url);
            $this->redirect($url);
        }

        $conditions[]=array('Cart.cart_type'=>"plan"); 

        if(isset($this->params->query['s'])&&!empty($this->params->query['s'])){

            $this->set('keyword',$this->params->query['s']);

            $conditions[]=array('OR '=>array(

                'StaffUser.email like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.first_name like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.last_name like ' => '%'.trim($this->params->query['s']). '%'

            ));

        }

        $fields=array('Cart.id','StaffUser.first_name','StaffUser.last_name','total','status');

        $this->paginate = array('conditions' => $conditions,'order' => 'Cart.id DESC','limit' => '15');

        $data_array = $this->paginate('Cart');

      

        //pr( $data_array);die;

        $this->set(compact("data_array",'currency'));

    }



    public function prcart(){

        $currency=Configure::read('Site.currency');

        $this->set('placeholder','Please enter first name,Last name,Email');

        $this->set('title_for_layout', __('PR Cart')); 

        $data_array=[]; 

        $this->Cart->recursive="1";

        $conditions[]=array('Cart.cart_type'=>"pr",'OR'=>array('extra_words >'=>0,'extra_msa >'=>0,'extra_category >'=>0,'extra_state >'=>0,'Cart.translate_charges >'=>0,'distribution_ids NOT'=>null)); 

        if(isset($this->params->query['s'])&&!empty($this->params->query['s'])){

            $this->set('keyword',$this->params->query['s']);

            $conditions[]=array('OR '=>array(

                'StaffUser.email like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.first_name like ' => '%'.trim($this->params->query['s']). '%',

                'StaffUser.last_name like ' => '%'.trim($this->params->query['s']). '%'

            ));

        }

        $fields=array('Cart.id','StaffUser.first_name','StaffUser.last_name','total','status');

        $this->paginate = array('conditions' => $conditions,'order' => 'Cart.id DESC', 'limit' => '15');

        $data_array = $this->paginate('Cart');

        // pr( $data_array);die;

        $this->set(compact("data_array",'currency'));

    } 



    public function sendmail($cartId='',$redirect){

        $this->loadModel('StaffUser');



        $data=$this->Cart->find('first',array('conditions'=>array('Cart.id'=>$cartId),'fields'=>array('StaffUser.first_name','StaffUser.last_name','StaffUser.email','Cart.id','Cart.plan_id','Cart.press_release_id')));

        $name=$data['StaffUser']['first_name'].' '.$data['StaffUser']['last_name'];



        $url=SITEFRONTURL.'/plans/online-distribution/';

        $message="Your Plan or Newsroom pending in your cart. Please click here to <a target='_blank' href='".$url."'>purchase your plan</a>.";



        if($redirect=='prcart'){

        $url=SITEFRONTURL.'users/makepayment/'.$data['Cart']['plan_id'].'/'.$data['Cart']['press_release_id'];

        $message="Your PR payment has been pending.Please click here to <a target='_blank' href='".$url."'>payment</a>.";

        }

        



        $subject="Cart pending";

        $title="Email Wire cart pending";

       if($this->Custom->sendemail($data['StaffUser']['email'],$message,$name,$subject,$title)){

                 $this->Session->setFlash(__('Mail sent successfully.'), 'success');

            return $this->redirect(array('action' =>$redirect));

        }else{

            $this->Session->setFlash(__('Mail not sent. Please try again.'), 'error');

        }

    }

}

