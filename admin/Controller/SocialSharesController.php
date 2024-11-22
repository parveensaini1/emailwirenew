<?php



App::uses('Folder', 'Utility');

App::uses('File', 'Utility');

/**

 * SocialShares Controller

 *

 * PHP version 5

 *

 * @category Controller

 * @package  Croogo

 * @version  1.0

 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>

 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License

 * @link     http://www.croogo.org

 */

class SocialSharesController extends AppController {



    /**

     * Controller name

     *

     * @var string

     * @access public

     */

    public $name = 'SocialShares';



    /**

     * Models used by the Controller

     *

     * @var array

     * @access public

     */

    public $uses = array('SocialShare');



    /**

     * Helpers used by the Controller

     *

     * @var array

     * @access public

     */

    public $helpers = array('Html', 'Form');



    public function beforeFilter() {

        parent::beforeFilter();

        $this->set('controller', 'SocialShares');

        $this->set('model', 'SocialShare');

    }



    public function index() {

        $this->set('title_for_layout', __('All SocialShares'));
 

        $this->paginate = array("order" => "SocialShare.weight ASC");

        $this->set('data_array', $this->paginate());

        $this->set('menutitle', 'SocialShare');

        $this->set('menutitle_add', 'SocialShare');

    }



    public function view($id = null) {

        if (!$id) { 

            $this->Session->setFlash(__('Report uploaded successfully.'), 'error');


            return $this->redirect(array('action' => 'index'));
            exit;

        }

        $this->set('SocialShare', $this->SocialShare->read(null, $id));

    }



    public function add() {

        $this->set('title_for_layout', __('Add SocialShare'));

    	$this->loadModel('SocialShare');



        if (!empty($this->request->data)) {

            $dirFile = ROOT.DS.'app'.DS.'webroot'.DS.'files' . DS .'social-share' . DS;
           
            $dir = new Folder($dirFile, true, 0755);

            if (!file_exists($dirFile)) {

                mkdir($dirFile, 0777, true);

            }

            if(!empty($this->request->data['SocialShare']['icon_url']['tmp_name'])){

                move_uploaded_file($this->request->data['SocialShare']['icon_url']['tmp_name'], $dirFile.'/'. $this->request->data['SocialShare']['icon_url']['name']);

            }

            $filename = $this->request->data['SocialShare']['icon_url']['name'];

            unset($this->request->data['SocialShare']['icon_url']);

            $this->request->data['SocialShare']['icon_url'] ='files/social-share/'.$filename;



            if ($this->SocialShare->save($this->request->data)) {

                $this->Session->setFlash(__('The SocialShare has been saved'), 'success');
                return $this->redirect(array('action' => 'index'));
                exit;

            } else {

                $this->Session->setFlash(__('The SocialShare could not be saved. Please, try again.'),'error');

            }

        }

    }



    public function edit($id = null) {

        $this->set('title_for_layout', __('Add SocialShare'));

    	$this->loadModel('SocialShare');


        if (!empty($this->request->data)) {

            $dirFile = ROOT.DS.'app'.DS.'webroot'.DS.'files' . DS .'social-share' . DS;
            
            $dir = new Folder($dirFile, true, 0755);

            if (!file_exists($dirFile)) {

                mkdir($dirFile, 0777, true);

            }
         
            if(!empty($this->request->data['SocialShare']['icon_url']['tmp_name'])){
                move_uploaded_file($this->request->data['SocialShare']['icon_url']['tmp_name'], $dirFile.$this->request->data['SocialShare']['icon_url']['name']);

	            $filename = $this->request->data['SocialShare']['icon_url']['name'];

	            unset($this->request->data['SocialShare']['icon_url']);

	            $this->request->data['SocialShare']['icon_url'] ='files/social-share/'.$filename;

            }else{

	            unset($this->request->data['SocialShare']['icon_url']);

            }

            if ($this->SocialShare->save($this->request->data)) {

                $this->Session->setFlash(__('The SocialShare has been edited'),'success');

                return $this->redirect(array('action' => 'index'));
                exit;

            } else {

                $this->Session->setFlash(__('The SocialShare could not be saved. Please, try again.'),'error');

            }

        }

        if (empty($this->request->data)) {

            $this->request->data = $this->SocialShare->read(null, $id);

        }

    }



    public function delete($id = null) {

        if (!$id) {

            $this->Session->setFlash(__('Invalid id for SocialShare'), 'error');

            $this->redirect(array('action' => 'index'));

        }

        if ($this->SocialShare->delete($id)) {

            $this->Session->setFlash(__('SocialShare deleted'), 'success');

            $this->redirect(array('action' => 'index'));

        }

    }



 



    public function moveup($id, $step = 1) {

        if ($this->SocialShare->moveup($id, $step)) {

            $this->Session->setFlash(__('Moved up successfully'), array('class' => 'success'));

        } else {

            $this->Session->setFlash(__('Could not move up'), array('class' => 'error'));

        }



        $this->redirect(array('controller' => 'SocialShares', 'action' => 'index'));

    }



    public function movedown($id, $step = 1) {

        if ($this->SocialShare->movedown($id, $step)) {

            $this->Session->setFlash(__('Moved down successfully'), array('class' => 'success'));

        } else {

            $this->Session->setFlash(__('Could not move down'), array('class' => 'error'));

        }



        $this->redirect(array('controller' => 'SocialShares', 'action' => 'index'));

    }





    public function change_status($id=null,$status) {

        $this->SocialShare->id =$id;

        switch ($status) {

            case 0:

                $status = 1;

                $message="Social button is active";

                break;

            case 1:

                $status = 0;

                $message="Social button is deactive";

                break;

        }

        $this->SocialShare->saveField('status', $status);

        $this->Session->setFlash($message, array('class' => 'success'));

        $this->redirect(array('action' => 'index'));

        $this->autoRender = false; 



    }



}

