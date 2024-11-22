<?php
App::uses('AppController', 'Controller');
class StocktickersController extends AppController {
    public $name = 'StockTicker';
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('menutitle', 'Stock tickers');
        $this->set('menutitle_add', 'Stock ticker add');
        $this->set('controller', 'stocktickers');
        $this->set('model', 'StockTicker');
    }
    public function index() {
        $this->set('title_for_layout', 'Stock Ticker list');
        $this->paginate = array('order' => 'StockTicker.id ASC', 'limit' => '15');
        $pageList = $this->paginate('StockTicker');
        $this->set('data_array', $pageList);
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->set('title_for_layout', 'Update email template');

        $this->StockTicker->id = $id;
        if (!$this->StockTicker->exists()) {
            throw new NotFoundException(__('Invalid email template'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->StockTicker->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully updated.'), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->StockTicker->read(null, $id);
        }
    }

    public function add($id = null) {
        $this->set('title_for_layout', 'Add new email template');
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->StockTicker->save($this->request->data)) {
                $this->Session->setFlash(__('Detail successfully added.'), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        } else {
            $this->request->data = $this->StockTicker->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->StockTicker->id = $id;
        if (!$this->StockTicker->exists()) {
            throw new NotFoundException(__('Invalid id', 'message', array('class' => 'error')));
        }
        if ($this->StockTicker->delete()) {
            $this->Session->setFlash(__('Detail successfully deleted'), 'error');
            $this->redirect(array('action' => 'index'));
        }
    }
}
