<?php
App::uses('AppController', 'Controller');
class PlanCategoriesController extends AppController
{
    public $name = 'PlanCategories';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('menutitle', 'Plan names');
        $this->set('menutitle_add', 'Plan name');
        $this->set('controller', 'PlanCategories');
        $this->set('model', 'PlanCategory');
    }

    public function index()
    {
        $this->set('title_for_layout', __('All plan Names'));
        $data_array = $this->PlanCategory->generateTreeList(
            array(),
            null,
            null,
            '__'
        );

        $this->set('data_array', $data_array);
    }

    public function add()
    {
        $mas_list = $state_list = [];
        $this->loadModel('Country');
        $this->set('title_for_layout', __('Add a new plan name'));
        if (!empty($this->data)) {
            if (empty($this->data['PlanCategory']['banner_image']))
                $this->request->data['PlanCategory']['banner_image'] = null;

            if (empty($this->data['PlanCategory']['banner_image']))
                $this->request->data['PlanCategory']['banner_path'] = null;

            if ($this->data['PlanCategory']['is_country_allowed'] == 1) {
                if ($this->data['PlanCategory']['is_allowed_all_country'] == 1) {
                    unset($this->request->data['Country']);
                } else {
                    unset($this->request->data['PlanCategory']['is_allowed_all_country']);
                }
            }
            if ($this->data['PlanCategory']['is_state_allowed'] == 1) {
                if ($this->data['PlanCategory']['is_allowed_all_state'] == 1) {
                    unset($this->request->data['State']);
                } else {
                    unset($this->request->data['PlanCategory']['is_allowed_all_state']);
                }
            }
            if ($this->data['PlanCategory']['is_msa_allowed'] == 1) {
                if ($this->data['PlanCategory']['is_allowed_all_msa'] == 1) {
                    unset($this->request->data['Msa']);
                } else {
                    unset($this->request->data['PlanCategory']['is_allowed_all_msa']);
                }
            }

            if ($this->PlanCategory->saveAll($this->data)) {
                $this->Session->setFlash(__('Detail successfully updated'), 'message', array('class' => 'success'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'message', array('class' => 'error'));
            }
        }
        // $mas_list = $this->Msa->find('list', array('conditions' => array('status' => 1)));
        // $state_list = $this->State->find('list',array('conditions' => array('country_id'=>'231')));

        $country_list = $this->Country->find('list', array('conditions' => array('status' => 1)));

        $data_array = $this->PlanCategory->generateTreeList(null, null, null, '___');
        $this->set(compact('data_array', 'mas_list', 'country_list', 'state_list'));
    }

    public function edit($id)
    {
        $this->loadModel('Msa');
        $this->loadModel('Country');
        $this->loadModel('State');
        $this->loadModel('MsasPlanCategory');
        $this->loadModel('CountriesPlanCategory');
        $this->loadModel('StatesPlanCategory');
        $this->set('title_for_layout', __('Edit plan name'));

        if (!empty($this->data)) {

            if (empty($this->data['PlanCategory']['banner_image']))
                $this->request->data['PlanCategory']['banner_image'] = null;

            if (empty($this->data['PlanCategory']['banner_image']))
                $this->request->data['PlanCategory']['banner_path'] = null;

            if ($this->data['PlanCategory']['is_country_allowed'] == 1) {
                if ($this->data['PlanCategory']['is_allowed_all_country'] == 1) {
                    unset($this->request->data['Country']);
                } else {
                    unset($this->request->data['PlanCategory']['is_allowed_all_country']);
                }
            }
            if ($this->data['PlanCategory']['is_state_allowed'] == 1) {
                if ($this->data['PlanCategory']['is_allowed_all_state'] == 1) {
                    unset($this->request->data['State']);
                } else {
                    unset($this->request->data['PlanCategory']['is_allowed_all_state']);
                }
            }
            if ($this->data['PlanCategory']['is_msa_allowed'] == 1) {
                if ($this->data['PlanCategory']['is_allowed_all_msa'] == 1) {
                    unset($this->request->data['Msa']);
                } else {
                    unset($this->request->data['PlanCategory']['is_allowed_all_msa']);
                }
            }
            
            if ($this->PlanCategory->saveAll($this->data)) {
                $this->Session->setFlash(__('Detail successfully updated'), 'message', array('class' => 'success'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'message', array('class' => 'error'));
            }
        } else {
            $this->PlanCategory->recursive = 2;
            $data = $this->request->data = $this->PlanCategory->find("first", ['conditions' => ['id' => $id]]);
        }

        $data_array = $this->PlanCategory->generateTreeList(null, null, null, '___');

        $mas_list = $state_list = [];
        $countriesIds = (!empty($data["Country"])) ? $this->Custom->getRecordIds($data["Country"], "Country") : [];
        $stateIds = (!empty($data["State"])) ? $this->Custom->getRecordIds($data["State"], "State") : [];

        if (!empty($countriesIds)) {
            if (is_array($countriesIds) && count($countriesIds) > 1) {
                $stateCondition = array('status' => '1', 'country_id IN' => $countriesIds);
            } else {
                $stateCondition = array('status' => '1', 'country_id' => $countriesIds);
            }
            $state_list = $this->State->find('list', array('conditions' => $stateCondition));
        }



        if (!empty($stateIds)) {
            if (is_array($stateIds) && count($stateIds) > 1) {
                $msaCondition = array('status' => '1', 'state_id IN' => $stateIds);
            } else {
                $msaCondition = array('status' => '1', 'state_id' => $stateIds);
            }
            $mas_list = $this->Msa->find('list', array('conditions' => $msaCondition));
        }
        $country_list = $this->Country->find('list', array('conditions' => array('status' => 1)));
        $this->set(compact('data_array', 'mas_list', 'country_list', 'state_list'));
    }



    public function delete($id = null)
    {
        $this->PlanCategory->id = $id;
        if (!$this->PlanCategory->exists()) {
            $this->Session->setFlash(__('Invalid id.'), 'message', array('class' => 'error'));
        }
        // $saveData['PlanCategory']['id'] = $id;
        // $saveData['PlanCategory']['status'] = 2;
        if ($this->PlanCategory->delete($id)) {
            $this->Session->setFlash(__('Detail successfully deleted'), 'message', array('class' => 'success'));
            $this->redirect(array('action' => 'index'));
        }
    }

    public function movedown($id = null, $delta = null)
    {
        $this->PlanCategory->id = $id;
        if (!$this->PlanCategory->exists()) {
            throw new NotFoundException(__('Invalid category'));
        }

        if ($delta > 0) {
            $this->PlanCategory->moveDown($this->PlanCategory->id, abs($delta));
            $this->Session->setFlash(__('Detail successfully updated'), 'success');
        } else {
            $this->Session->setFlash('Please provide a number of positions the category should' .
                'be moved up.', 'error');
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function moveup($id = null, $delta = null)
    {
        $this->PlanCategory->id = $id;
        if (!$this->PlanCategory->exists()) {
            throw new NotFoundException(__('Invalid category'));
        }

        if ($delta > 0) {
            $this->PlanCategory->moveUp($this->PlanCategory->id, abs($delta));
            $this->Session->setFlash(__('Detail successfully updated'), 'success');
        } else {
            $this->Session->setFlash('Please provide a number of positions the category should' .
                'be moved up.', 'error');
        }

        return $this->redirect(array('action' => 'index'));
    }
}
