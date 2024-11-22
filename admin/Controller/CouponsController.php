<?php
App::uses('AppController', 'Controller');
class CouponsController extends AppController {
    public $name = 'Coupons'; 
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('menutitle', 'Coupons');
        $this->set('menutitle_add', 'Coupon');
        $this->set('controller', 'Coupons');
        $this->set('model', 'Coupon');
    }

    public function index() {
        $this->set('title_for_layout', __('All Coupons'));
        $this->paginate = array('limit' => Configure::read('Admin.paging'), 'order' => 'Coupon.id DESC');
        $data_array = $this->paginate('Coupon');
        $this->set('data_array', $data_array);
    }

    public function add() { 
        $this->set('title_for_layout', __('Add a new Coupon'));        
        if (!empty($this->data)) {
            if ($this->Coupon->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully added'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        }
        $this->loadModel('CouponCategory'); 
    }

    public function edit($id) {
        $this->set('title_for_layout', __('Edit Coupon')); 
        if (!empty($this->data)) {
            if ($this->Coupon->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully updated'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->Coupon->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->Coupon->id = $id;
        if (!$this->Coupon->exists()) {
            throw new NotFoundException('Invalid id', 'error');
        }
        if ($this->Coupon->delete()) {
            $this->Session->setFlash(__('Detail successfully deleted'), 'success');
            $this->redirect(array('action' => 'index'));
        }
    } 
}
