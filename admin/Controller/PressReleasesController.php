<?php

App::uses('AppController', 'Controller');

use Dompdf\Dompdf;

class PressReleasesController extends AppController
{

    public $name = 'PressReleases';

    public $uses = array('StaffUser', 'EmailTemplate', 'Company', 'OrganizationType', 'Transaction', 'Country', 'PressRelease', "Category", "Msa", "TakeOverCompany", "CompaniesStaffUser", 'Cart', 'Coupon', 'Plan', 'PlanCategory', 'Distribution', 'State', "PressImage", "ClippingReport", "Campaign", 'List','ClickThroughClient');

    public function beforeFilter()
    {

        parent::beforeFilter();
        $this->set('menutitle', 'PressReleases');
        $this->set('menutitle_add', 'PressRelease');
        $this->set('controller', 'PressReleases');
        $this->set('model', 'PressRelease');
        $this->set('draftcount', $this->PressRelease->find('count', array('conditions' => array('submitted_by' => 'admin', 'PressRelease.status' => "3"))));
    }

    /**

     * index method

     *

     * @return void

     */

    public function index()
    {

        $this->set('title_for_layout', __('All Approved PressReleases'));

        $data_array = [];

        if (isset($this->request->params['named']['page']) && !empty($this->request->params['named']['page']) && isset($this->params->query['s']) && !empty($this->params->query['s'])) {
            $url = str_replace('/page:' . $this->request->params['named']['page'], '', $_SERVER['REQUEST_URI']);
            $url = str_replace('admin/', '', $url);
            $this->redirect($url);
        }


        $this->set('placeholder', 'Please enter title..');

        $conditions[] = array('PressRelease.status' =>1,'PressRelease.release_date <=' => date('Y-m-d'));

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('PressRelease.title like ' => '%' . $this->params->query['s'] . '%');
        }

        $clippingReportTbl=$this->ClickThroughClient->table;
        // $clippingReportTbl=$this->ClippingReport->table;
        // $this->PressRelease->unbindModel(array("belongsTo"=>array('Plan','Company'),"hasAndBelongsToMany"=>array('Category','Msa','State','Distribution'),"hasMany"=>array('PressSeo', 'PressYoutube', 'PressImage','PressPoadcast')));
        // $socialShareCountSql="select views FROM $clippingReportTbl where distribution_type='social_media_feed' AND press_release_id=PressRelease.id";
        // $networkFeedCountSql="select views FROM $clippingReportTbl where distribution_type='network_feed' AND press_release_id=PressRelease.id";
  
        $socialShareCountSql="select SUM(clicked) FROM $clippingReportTbl where type='1' AND press_release_id=PressRelease.id";
        $networkFeedCountSql="select SUM(clicked) FROM $clippingReportTbl where type='2' AND press_release_id=PressRelease.id";

        $this->paginate = array('fields' => array("($socialShareCountSql) as socialShareCount","($networkFeedCountSql) as networkFeedCount",'PressRelease.*','StaffUser.*'),"conditions" => $conditions, 'limit' => Configure::read('Admin.paging'), 'order' => 'PressRelease.id DESC');

        $data_array = $this->paginate('PressRelease');



        $this->set("data_array", $data_array);
    }

    public function draft()
    {

        $this->set('title_for_layout', __('All PressReleases'));

        $this->set('placeholder', 'Please enter title..');

        $conditions[] = array('PressRelease.status' => '3', "submitted_by" => 'admin');

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('PressRelease.title like ' => '%' . $this->params->query['s'] . '%');
        }



        $data_array = [];

        $this->paginate = array("conditions" => $conditions, 'limit' => Configure::read('Admin.paging'), 'order' => 'PressRelease.id DESC');

        $data_array = $this->paginate('PressRelease');

        $this->set("data_array", $data_array);
    }



    public function add($selectedLang='en',$selectedplan = '', $id = '', $action = '3')
    {
        $languages=$allStates = $allCountries = $allMsas = $country_list = $msa_list = $state_list = [];
        $currencySymbol = Configure::read('Site.currency');
        $this->set('title_for_layout', __('Add a new press releases'));
        if (!empty($this->data)) {
                   

            $status = '3';
            $fmessage = "Press release save successfully in draft";
            if ($this->data['PressRelease']['submittype'] == "indraft") {
                $fmessage = "Press release successfully saved in draft.";
            } else if ($this->data['PressRelease']['submittype'] == "preview") {
                $fmessage = "Please review your press release.";
            }
            $this->request->data['PressRelease']['status'] = $status;
            $this->request->data['PressRelease']['submitted_by'] = 'admin';
            $this->request->data['staff_user_id'] = (empty($this->data['PressRelease']['staff_user_id'])) ? $this->data['PressRelease']['staff_user_id'] : $this->Auth->user('id');
            
            if (empty($this->data['PressRelease']['is_source_manually'])) {
                if (!empty($this->data['PressRelease']['media_country_id'])) {
                    $allCountries = $this->Custom->getCountryList();
                    $mediaCountryId = $this->data['PressRelease']['media_country_id'];
                    $this->request->data['PressRelease']['source_country'] = (!empty($allCountries[$mediaCountryId])) ? $allCountries[$mediaCountryId] : null;
                }

                if (!empty($this->data['PressRelease']['media_state_id'])) {
                    $allState = $this->Custom->getStateList();
                    $mediaStateId = $this->data['PressRelease']['media_state_id'];
                    $this->request->data['PressRelease']['source_state'] = (!empty($allState[$mediaStateId])) ? $allState[$mediaStateId] : null;
                }

                if (!empty($this->data['PressRelease']['media_msa_id'])) {
                    $allMsas = $this->Custom->getMsaList();
                    $mediaMsaId = $this->data['PressRelease']['media_msa_id'];
                    $this->request->data['PressRelease']['source_msa'] = (!empty($allMsas[$mediaMsaId])) ? $allMsas[$mediaMsaId] : null;
                }
            } else {
                unset($this->request->data['PressRelease']['media_country_id']);
                unset($this->request->data['PressRelease']['media_state_id']);
                unset($this->request->data['PressRelease']['media_msa_id']);
            }
            
            if (!empty($this->data['PressRelease']['body'])) {
                $this->loadModel('StockTicker');
                $this->request->data['PressRelease']['body'] = $this->Custom->strockticker($this->request->data['PressRelease']['body']);

                /*
                    $contentPrefix=$this->Custom->summaryPrefix($this->data['Msa']['Msa'],$this->data['State']['State'],$this->data['PressRelease']['release_date']); 
                    $body=array($this->data['PressRelease']['body']);
                    if(!strpos(strip_tags($body[0]),"EmailWire")){
                        $body[0]=preg_replace("/<p>/","<p>".$contentPrefix,$body[0],1);
                        $this->request->data['PressRelease']['body']=implode("</p>",$body);
                    }elseif(!strpos(strip_tags($body[0]),$contentPrefix)){
                        $content=explode("/ --",$body[0]);
                        $content[0]=$contentPrefix;
                        $body=$content;
                        $this->request->data['PressRelease']['body']=implode("",$body);
                    }
                */

            }

            $this->request->data['PressRelease']['language'] = $this->data['PressRelease']['language'];
            $press_seo = array_values($this->request->data['PressSeo']);
            unset($this->request->data['PressSeo']);
            $this->loadModel('PressSeo');
            
            foreach ($press_seo as $key => $seo) {
                if (!empty($seo['keyword'])) {
                    if (isset($this->data['PressRelease']['id']) && !empty($this->data['PressRelease']['id'])) {
                        $this->request->data['PressSeo'][$key]['press_release_id'] = $this->data['PressRelease']['id'];
                    }
                    $this->request->data['PressSeo'][$key]['keyword'] = $seo['keyword'];
                   $this->request->data['PressSeo'][$key]['slug'] = strtolower(Inflector::slug($seo['keyword'], '-')) . '-' . time();
                    if (isset($seo['id']))
                        $this->request->data['PressSeo'][$key]['id'] = $seo['id'];
                        // $this->request->data['PressSeo'][$key]['urls'] = $seo['urls'];
                } else {
                    if (isset($seo['id']) && empty($seo['keyword'])) {
                        $this->PressSeo->id = $seo['id'];
                        if ($this->PressSeo->exists()) {
                            $this->PressSeo->delete();
                        }
                    }
                }
            }
            $press_youtubes = array_values($this->request->data['PressYoutube']);
            unset($this->request->data['PressYoutube']);
            $this->loadModel('PressYoutube');
            foreach ($press_youtubes as $key => $youtubes) {
                if (!empty($youtubes['url'])) {
                    if (isset($this->data['PressRelease']['id']) && !empty($this->data['PressRelease']['id'])) {
                        $this->request->data['PressYoutube'][$key]['press_release_id'] = $this->data['PressRelease']['id'];
                    }
                    $this->request->data['PressYoutube'][$key]['url'] = $youtubes['url'];
                    $this->request->data['PressYoutube'][$key]['description'] = $youtubes['description'];
                    if (isset($youtubes['id'])){
                        $this->request->data['PressYoutube'][$key]['id'] = $youtubes['id'];
                    }
                } else {
                    if (isset($youtubes['id']) && empty($youtubes['url'])) {
                        $this->PressYoutube->id = $youtubes['id'];
                        if ($this->PressYoutube->exists()) {
                            $this->PressYoutube->delete();
                        }
                    }
                }
            }

            $this->loadModel('PressPoadcast');
            $press_podcasts = array_values($this->request->data['PressPoadcast']);
            unset($this->request->data['PressPoadcast']);
            foreach ($press_podcasts as $key => $podcast) {
                if (!empty($podcast['url'])) {
                    if (!empty($this->data['PressRelease']['id']) && !empty($this->data['PressRelease']['id'])) {
                        $this->request->data['PressPoadcast'][$key]['press_release_id'] = $this->data['PressRelease']['id'];
                    }
                    $this->request->data['PressPoadcast'][$key]['url'] = $podcast['url'];
                    $this->request->data['PressPoadcast'][$key]['description'] = $podcast['description'];
                    if (isset($podcast['id']))
                        $this->request->data['PressPoadcast'][$key]['id'] = $podcast['id'];
                } else { 
                    if (isset($podcast['id']) && empty($podcast['url'])) {
                        $this->PressPoadcast->id = $podcast['id'];
                        if ($this->PressPoadcast->exists()) {
                            $this->PressPoadcast->delete();
                        }
                    }
                }
            } 
            


            if(!empty($this->request->data['PressImage'])){
                $press_image = array_values($this->request->data['PressImage']);
                foreach ($press_image as $index => $image) {
                    if (empty($image['image_name'])) {
                        unset($this->request->data['PressImage'][$index]);
                    }else{
                        if (isset($image['image_name']['tmp_name']) && $image['image_name']['tmp_name'] == '') {
                            unset($this->request->data['PressImage'][$index]);
                        }
                        if (isset($image['id']) && !empty($image['id'])) {
                            $this->PressImage->id = $image['id'];
                            if (empty($image['image_name']) && $this->PressImage->exists()) {
                                $this->PressImage->delete();
                            }
                        }
                    }
                }
            }else{
                unset($this->request->data['PressImage']);
            }
 
            $this->request->data['PressRelease']['release_date'] = date('Y-m-d', strtotime($this->request->data['PressRelease']['release_date']));

            $this->request->data['PressRelease']['admin_user_id'] = $this->Auth->user('id');



            $this->request->data['PressRelease']['is_paid'] = "0";

            $this->request->data['PressRelease']['approved_by'] = $this->Auth->user('id');
            $this->request->data['PressRelease']['language_id'] = $this->Custom->getLanguageIdByCode($this->data['PressRelease']['language']);
            if ($this->PressRelease->saveAll($this->request->data)) {
                if (isset($this->request->data['PressRelease']['id']) && !empty($this->request->data['PressRelease']['id'])) {
                    $prId = $this->data['PressRelease']['id'];
                } else {
                    $prId = $this->PressRelease->getLastInsertID();
                }
                /*Now Image save with ajax */
                //$this->save_press_image($prId, $press_image);
                $this->Session->setFlash($fmessage, 'success');
                if ($this->data['PressRelease']['submittype'] == 'indraft') {
                    return $this->redirect(array('action' => 'draft'));
                } else {
                    return $this->redirect(array('action' => 'preview',$selectedplan, $prId));
                }
            } else {

                $this->Session->setFlash(__('Press Release not submitted. Please, try again.'), 'error');
            }
        }

        $parent_conditions = array('Category.is_deleted' => '0', 'status' => 1, 'Category.parent_id' => 0);
        $pCategory_list = $this->Category->find('list', array('conditions' => $parent_conditions, 'order' => 'name'));
        $categories = [];
        if ($pCategory_list) {

            foreach ($pCategory_list as $cpid => $pCatname) {

                $categories[$pCatname] = [];

                $child_conditions = array('Category.is_deleted' => '0', 'status' => 1, 'Category.parent_id' => $cpid);

                $category_list = $this->Category->find('list', array('conditions' => $child_conditions, 'order' => 'name'));

                foreach ($category_list as $cId => $category) {

                    $categories[$pCatname][$cId] = $category;
                }
            }
        }
        $country_list = $this->Country->find('list', array('conditions' => array('status' => 1)));
        $company_list = $this->Company->find('list', array('conditions' => array('status' => 1)));
        $this->PlanCategory->virtualFields['name'] = 'CASE WHEN word_limit >0 THEN CONCAT(PlanCategory.name, " - ",PlanCategory.word_limit," words") ELSE PlanCategory.name END';

        $plancat_list = $this->PlanCategory->find('list', array('fields' => array('id', 'name'), 'conditions' => array('status' => 1)));

        $plan_list = [];

        if ($plancat_list) {

            $this->Plan->recursive = -1;

            foreach ($plancat_list as $pcId => $planCatname) {

                $plan_list[$planCatname] = [];

                $getplan = $this->Plan->find('all', array('fields' => array('id', 'price', 'bulk_discount_amount'), 'conditions' => array('Plan.plan_category_id' => $pcId)));

                $getplan = Set::extract('/Plan/.', $getplan);

                if (!empty($getplan)) {

                    foreach ($getplan as $index => $plan) {

                        $price = ($plan['bulk_discount_amount'] > 0) ? $currencySymbol . $plan['bulk_discount_amount'] : $currencySymbol . $plan['price'];

                        $categoryname = preg_replace('/\d+/', '', str_replace(array("-", "words"), array("", ""), $planCatname));

                        $plan_list[$planCatname][$plan['id']] = trim($categoryname) . ' - ' . $price;
                    }
                } else {

                    unset($plan_list[$planCatname]);
                }
            }
        }

        $planDetail = ""; 
        if (!empty($selectedplan)) { 
            $this->Plan->recursive = 2; 
            $planDetail = $this->Plan->find('first', array('conditions' => array('Plan.id' => $selectedplan)));
        }



        $state_list = "";

        if ($id) {
            $this->request->data = $this->PressRelease->read(null, $id);
            $this->request->data['PressRelease']['language']=($selectedLang==$this->request->data['PressRelease']['language'])?$this->request->data['PressRelease']['language']:$selectedLang;
            $countryId = $this->request->data['PressRelease']['country_id'];
            $state_list = $this->State->find('list', array('conditions' => array("State.status" => "1", 'country_id' => $countryId)));
            $mediaCountryId = $this->request->data['PressRelease']['media_country_id'];
            $allStates = $this->State->find('list', array('conditions' => array("State.status" => "1", 'country_id' => $mediaCountryId)));
            $mediaStateId = $this->request->data['PressRelease']['media_state_id'];
            $allMsas = $this->Msa->find('list', array('conditions' => array("Msa.status" => "1", 'state_id' => $mediaStateId)));
            if ($state_list) {
                $stateIds = [];
                foreach ($state_list as $stateId => $stvl) {
                    $stateIds[$stateId] = $stateId;
                }
                $msa_list = $this->Msa->find('list', array(
                    'joins' => array(
                        array(
                            'table' => 'press_releases_states',
                            'alias' => 'PressReleasesState',
                            'type' => 'INNER',
                            'conditions' => array('Msa.state_id = PressReleasesState.state_id')
                        ),
                    ),
                    'conditions' => array("Msa.status" => "1", "PressReleasesState.press_release_id" => $id, 'Msa.state_id' => $stateIds)
                ));
            }
        }

        $this->Distribution->virtualFields['name'] = 'CONCAT(name, " ($",amount,")")';

        $distribution_list = $this->Distribution->find('list', array('id', 'name'));

        $email_list = $this->List->find('list', array('conditions' => array('staff_user_id' => $this->Auth->user('id')), 'fields' => array('List.id', 'List.name')));

        
        if (empty($allCountries)) {
            $allCountries = $this->Custom->getCountryList();
        }
        
        $languages=$this->Custom->getLanguages();
        
        $this->set(compact('msa_list', 'country_list', 'company_list', 'plan_list', 'categories', 'selectedplan', 'plancat_list', 'planDetail', 'distribution_list', 'state_list', "email_list", "allStates", "allMsas", "allCountries",'id','languages','selectedLang'));
    }



    public function preview($selectedplan = '', $id = '')
    {

        $action = 'index';

        $data = $this->PressRelease->read(null, $id);

        $status = 1;

        $slug = 'release/' . $data['PressRelease']['slug'];

        $title = $data['PressRelease']['title'];

        $release_date = strtotime($data['PressRelease']['release_date']);

        if (!empty($this->data)) {
            $today = strtotime(date('Y-m-d'));
            $message = 'Press release has been successfully submitted.';
            if ($release_date > $today) {
                $status = "1";  //$status = "2";
                $message = 'PR successfully submitted and move in embargoed.';
                $slug = 'users/press-releases/2';
                $action = 'embargoed';
            }
            $this->PressRelease->id = $id;
            $this->PressRelease->saveField("status", $status);
            $msg = "<h2>$message<h2>";
            $msg .= "<p>Click here to check <a target='_blank' title='$title' href='" . SITEFRONTURL . "$slug'>Press Release</p>";
            $this->Custom->sendemail($data['PressRelease']['email'], $msg, ucfirst($data['PressRelease']['contact_name']), 'Submit Presss Release from admin', "Press Release Info");
            $this->Session->setFlash(__($message), 'success');
            return $this->redirect(array('action' => $action));
        }
        $this->set(compact('selectedplan', 'data', 'id'));
    }

    public function pending()
    {
        $url = $this->request->url;
        $this->set('title_for_layout', __(ucfirst($url) . " list"));
        $keyword = '';
        $conditions[] = array('PressRelease.status' => '0');
        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {
            $this->set('keyword', $this->params->query['s']);
            $conditions[] = array('PressRelease.title like ' => '%' . $this->params->query['s'] . '%');
        }
        $this->set('title_for_layout', __('All pending Press releases'));
        $data_array = [];
        $this->PressRelease->bindModel(
            array(
                'hasOne' => array(
                    'TransactionPressRelease' => array(
                        'className' => 'TransactionPressRelease',
                        'foreignKey' => 'press_release_id',
                    )
                )
            )
        );

        $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube', 'PressImage'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution')));
        $this->paginate = array("conditions" => $conditions, 'limit' => Configure::read('Admin.paging'), 'order' => 'PressRelease.modified DESC');
        $data_array = $this->paginate('PressRelease');
        $this->set("data_array", $data_array);
    }
    
    public function trashed($redirect = '')
    {
        $keyword = "";
        $this->set('title_for_layout', __('All Trash '));
        switch ($redirect) {
            case 'Draft':
                $role_ids = 3;
                break;
            case 'Pending':
                $role_ids = 0;
                break;
            case 'trash':
                $role_ids = 5;
                break;
            case 'disapprove':
                $role_ids = 4;
                break;
            case 'Embargoed':
                $role_ids = 2;
                break;
            default:
               $role_ids = 1;

                break;
        }

        $conditions = array('PressRelease.status' => '5');

        if (!empty($this->params->query['keyword']) && $this->params->query['keyword'] != '') {

            $keyword = $this->params->query['keyword'];

            $conditions = array(

                'or' => array(

                    'PressRelease.contact_name like ' => '%' . $keyword . '%',

                    'PressRelease.email like ' => '%' . $keyword . '%',

                )

            );
        }

        $this->set('keyword', $keyword);

        $this->PressRelease->recursive = "-1";

        $this->paginate = array('conditions' => $conditions, 'group' => 'PressRelease.id', 'order' => 'PressRelease.id desc', 'limit' => '15');

        $data = $this->paginate('PressRelease');

        $this->set('check_trash', $this->check_trash());

        $this->set('data_array', $data);
    }



    public function check_trash()
    {

        switch ($this->request->url) {

            case 'Draft':

                $role_ids = 3;

                break;

            case 'Pending':

                $role_ids = 0;

                break;

            case 'trash':

                $role_ids = 5;

                break;

            case 'disapprove':

                $role_ids = 4;

                break;

            case 'Embargoed':

                $role_ids = 2;

                break;

            default:

                $role_ids = 1;

                break;
        }



        $conditions = array('PressRelease.status' => '5');

        return $this->PressRelease->find('count', array('conditions' => $conditions));
    }



    public function move_trash($id = '', $redirect = '')
    {

        $this->PressRelease->id = $id;

        $savedata['PressRelease']['status'] = '5';

        if ($this->PressRelease->save($savedata)) {

            $this->Session->setFlash(__('Detail successfully trashed.'), 'success');

            return $this->redirect($redirect);
        }
    }



    public function delete($id = null)
    {

        try {

            $data = $this->PressRelease->find('first', array('conditions' => array("PressRelease.id" => $id)));



            if (empty($data)) {

                throw new NotFoundException(__('Press Release not exists.'));
            }



            if (!empty($data['PressImage'])) {

                foreach ($data['PressImage'] as $key => $image) {

                    $file_path = WWW_ROOT . 'files' . DS . 'company' . DS . 'press_image' . DS . $image['image_path'] . DS . $image['image_name'];

                    $delfile1 = new File($file_path, false, 0777);

                    $delfile1->delete();
                }
            }



            $this->loadModel('PressYoutube');

            $this->loadModel('PressSeo');

            $this->loadModel('PressPoadcast');

            $this->loadModel('TransactionPressReleases');

            $this->loadModel('ClippingReport');

            $this->loadModel('NewsletterLog');

            $this->loadModel('PressImage');

            $this->loadModel('Cart');

            $this->PressYoutube->deleteAll(['press_release_id' => $id]);

            $this->PressSeo->deleteAll(['press_release_id' => $id]);

            $this->PressPoadcast->deleteAll(['press_release_id' => $id]);

            $this->PressImage->deleteAll(['press_release_id' => $id]);

            $this->NewsletterLog->deleteAll(['press_release_id' => $id]);

            $this->Cart->deleteAll(['press_release_id' => $id]);

            $this->TransactionPressReleases->deleteAll(['press_release_id' => $id]);

            $this->ClippingReport->deleteAll(['press_release_id' => $id]);



            if ($this->PressRelease->delete($id, true)) {

                $this->Session->setFlash(__('Press release successfully deleted .'), 'success');
            } else {

                $this->Session->setFlash(__('Press release not deleted. Please try again. '), 'error');
            }
        } catch (Exception $exc) {

            $message = $exc->getMessage();

            $this->Session->setFlash(__($message), 'error');
        }

        $this->redirect(array('action' => "trashed"));
    }



    public function suspend($id = null, $redirect)
    {

        $this->set('check_trash', $this->check_trash());

        $this->StaffUser->id = $id;

        if (!$this->StaffUser->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        if ($this->StaffUser->saveField('status', '2')) {

            $this->Session->setFlash(__('Detail successfully trashed'), 'success');

            return $this->redirect($redirect);
        }
    }





    public function restore($id = '', $oldStatus = '')
    {

        $this->PressRelease->id = $id;

        $savedata['PressRelease']['status'] = $oldStatus;

        if ($this->PressRelease->save($savedata)) {

            $this->Session->setFlash(__('Detail successfully trashed.'), 'success');

            $status = $this->Custom->getPRSUrlStatus($oldStatus);

            $this->redirect(array('action' => $status));
        }
    }



    public function disapproved()
    {

        $this->set('placeholder', 'Please enter title..');

        $conditions[] = array('PressRelease.status' => '4');

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('PressRelease.title like ' => '%' . $this->params->query['s'] . '%');
        }

        $this->set('title_for_layout', __('All disapproved press releases'));

        $data_array = [];

        $this->paginate = array("conditions" => $conditions, 'limit' => Configure::read('Admin.paging'), 'order' => 'PressRelease.id DESC');

        $data_array = $this->paginate('PressRelease');



        $this->set("data_array", $data_array);
    }



    public function embargoed()
    {

        $this->set('placeholder', 'Please enter title..');

        // $conditions[] = array('PressRelease.status' => '2');
        $conditions[] = array('PressRelease.status' =>1,'PressRelease.release_date >' => date('Y-m-d'));
        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('PressRelease.title like ' => '%' . $this->params->query['s'] . '%');
        }

        $this->set('title_for_layout', __('All Embargoed Press Releases'));

        $data_array = [];

        $this->paginate = array("conditions" => $conditions, 'limit' => Configure::read('Admin.paging'), 'order' => 'PressRelease.id DESC');

        $data_array = $this->paginate('PressRelease');



        $this->set("data_array", $data_array);
    }





    public function checkEmailSendOptionInFeature($id = '')
    {

        $this->loadModel('DistributionsPressRelease');

        $check = $this->DistributionsPressRelease->find('count', array("conditions" => array('press_release_id' => $id, 'distribution_id' => "8")));

        return $check;
    }



    function updateCampaignStatus($prSlug)
    {

        $this->loadModel('Campaign');

        $campaign = $this->Campaign->find('first', array("fields" => array("id"), "conditions" => array('press_release_slug ' => $prSlug, 'sent' => null)));



        $send_date = strtotime("+2 minutes");

        $checkCampOndate = $this->Campaign->find('first', array("fields" => array("id", 'send_date'), "conditions" => array('created LIKE' => '%' . date("Y-m-d") . '%', 'sent' => null)));



        if (!empty($checkCampOndate)) {

            $seconds = $checkCampOndate['Campaign']['send_date'] + 600; // 6000 mean 10  min

            $rounded_seconds = round($seconds / (15 * 60)) * (15 * 60);

            $send_date = strtotime(date('Y:m:d H:i:s', $rounded_seconds));
        }

        if (!empty($campaign)) {

            $savedata['Campaign']['id'] = $campaign['Campaign']['id'];

            $savedata['Campaign']['status'] = "1";

            $savedata['Campaign']['send_date'] = $send_date;

            $this->Campaign->save($savedata);
        }
    }



    public function getSubscriber($catIds = "", $msaIds = "")
    {

        if (!empty($catIds) && !empty($msaIds)) {

            $conditions = ['StaffUser.staff_role_id' => 4, 'StaffUser.status' => 1, 'StaffUser.newsletter_subscription' => 1, 'StaffUser.email_confirmed' => 1, 'OR' => ['MsasStaffUser.msa_id' => $msaIds, 'CategoryStaffUser.category_id' => $catIds]];
        } else if (!empty($catIds)) {

            $conditions = ['StaffUser.staff_role_id' => 4, 'StaffUser.status' => 1, 'StaffUser.newsletter_subscription' => 1, 'StaffUser.email_confirmed' => 1, 'CategoryStaffUser.category_id' => $catIds];
        } else if (!empty($msaIds)) {

            $conditions = ['StaffUser.staff_role_id' => 4, 'StaffUser.status' => 1, 'StaffUser.newsletter_subscription' => 1, 'StaffUser.email_confirmed' => 1, 'MsasStaffUser.msa_id' => $msaIds];
        }

        $data = $this->StaffUser->find("list", array(

            'joins' => array(

                array(

                    'table' => 'categories_staff_users',

                    'alias' => 'CategoryStaffUser',

                    'type' => 'LEFT',

                    'conditions' => array(

                        'CategoryStaffUser.staff_user_id = StaffUser.id'

                    )

                ),

                array(

                    'table' => 'msas_staff_users',

                    'alias' => 'MsasStaffUser',

                    'type' => 'LEFT',

                    'conditions' => array(

                        'MsasStaffUser.staff_user_id = StaffUser.id'

                    )

                )

            ), 'conditions' => $conditions, "fields" => ['StaffUser.id', 'StaffUser.email'], 'order' => 'StaffUser.id DESC'
        ));

        return $data;
    }



    function addInSubscriberMailList($prid, $catIds = "", $msaIds = "")
    {

        $savedata = [];

        $this->loadModel('NewsletterMailList');

        $subscribers = $this->getSubscriber($catIds, $msaIds);

        if (!empty($subscribers)) {

            $count = 0;

            foreach ($subscribers as $userId => $email) {

                $check = $this->NewsletterMailList->find('count', array("fields" => array("id"), "conditions" => array('press_release_id ' => $prid, 'staff_user_id' => $userId)));

                if ($check == 0) {

                    $savedata[$count]['staff_user_id'] = $userId;

                    $savedata[$count]['subscriber_email'] = $email;

                    $savedata[$count]['press_release_id'] = $prid;

                    $savedata[$count]['send_date'] = $this->Custom->get_newsletter_sendmail_date($userId);
                }

                $count++;
            }

            if (!empty($savedata))

                $this->NewsletterMailList->saveMany($savedata);
        }

        return true;
    }



    public function active_pr($id = null, $redirect = '')
    {

        $this->PressRelease->id = $id;

        if (!$this->PressRelease->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube', 'PressImage'), 'hasAndBelongsToMany' => array('State', 'Distribution', 'ClippingReport', 'Company')));

        $data = $this->PressRelease->find('first', array("conditions" => array('PressRelease.id' => $id), 'fields' => array('release_date', 'slug')));

        $release_date = strtotime($data['PressRelease']['release_date']);

        $today = strtotime(date('Y-m-d'));

        $message = 'Press release is activated.';

        $savedata['PressRelease']['status'] = "1";

        $savedata['PressRelease']['approved_by'] = $this->Auth->user('id');

        if ($release_date > $today) {

            // $savedata['PressRelease']['status'] = "2";
            $savedata['PressRelease']['status'] = "1";

            $message = 'Press release activated and move in embargoed.';
        } else {

            $check = $this->checkEmailSendOptionInFeature($id);

            if ($check > 0) {

                $this->updateCampaignStatus($data['PressRelease']['slug']);
            }
        }

        $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube', 'PressImage'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution')));

        $this->PressRelease->bindModel(

            array(

                'belongsTo' => array(

                    'StaffUser' => array(

                        'className' => 'StaffUser',

                        'foreignKey' => 'staff_user_id',

                    )

                )

            )

        );

        $data = $this->PressRelease->read(null, $id);

        if (isset($data['StaffUser']['email'])) {

            $uName = $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'];

            $reason = "Your " . strtolower($message);

            $check = $this->Custom->sendPrMailOnAction($uName, $data['StaffUser']['email'], $reason, 'Approved Press release');
        }

        $savedata['PressRelease']['disapproval_reason'] = null;

        if ($this->PressRelease->save($savedata)) {

            $this->Session->setFlash(__($message), 'success');

            $this->redirect(array("controller" => "PressReleases", 'action' => $redirect));
        }
    }

    public function suspend_pr($id = null, $redirect = "")
    {

        $this->PressRelease->id = $id;

        if (!$this->PressRelease->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        $data['PressRelease']['status'] = "4";

        $data['PressRelease']['disapproved_by'] = $this->Auth->user('id');



        if ($this->PressRelease->save($data)) {



            $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube', 'PressImage'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution')));

            $this->PressRelease->bindModel(

                array(

                    'belongsTo' => array(

                        'StaffUser' => array(

                            'className' => 'StaffUser',

                            'foreignKey' => 'staff_user_id',

                        )

                    )

                )

            );

            $data = $this->PressRelease->read(null, $id);



            if (isset($data['StaffUser']['email'])) {

                $uName = $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'];

                $reason = (!empty($this->request->query['reason'])) ? $this->request->query['reason'] : "Your press release has been disapproved.";

                $check = $this->Custom->sendPrMailOnAction($uName, $data['StaffUser']['email'], $reason, 'Disapproved Press release', 'Your newsroom has disaproved due to');
            }



            $this->Session->setFlash(__('Press pelease disaprroved.'), 'success');

            $this->redirect(array("controller" => "PressReleases", 'action' => $redirect));
        }
    }



    public function inactive_pr($id = null, $redirect = "")
    {

        $this->PressRelease->id = $id;



        $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube', 'PressImage'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution')));

        $this->PressRelease->bindModel(

            array(

                'belongsTo' => array(

                    'StaffUser' => array(

                        'className' => 'StaffUser',

                        'foreignKey' => 'staff_user_id',

                    )

                )

            )

        );

        $data = $this->PressRelease->read(null, $id);



        if (isset($data['StaffUser']['email'])) {



            $uName = $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'];

            $reason = (!empty($this->request->query['reason'])) ? $this->request->query['reason'] : "Your press release has been disapproved.";

            $check = $this->Custom->sendPrMailOnAction($uName, $data['StaffUser']['email'], $reason, 'Disapproved Press release', 'Your newsroom has disaproved due to');



            $savedata['PressRelease']['disapproval_reason'] = $reason;
        }



        if (!$this->PressRelease->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        $savedata['PressRelease']['status'] = "4";

        $savedata['PressRelease']['disapprove_date'] = date('Y-m-d H:i:s');

        $savedata['PressRelease']['disapproved_by'] = $this->Auth->user('id');

        if ($this->PressRelease->save($savedata)) {

            $this->Session->setFlash(__('Press release disaprroved.'), 'success');

            $this->redirect(array("controller" => "PressReleases", 'action' => $redirect));
        }
    }





    private function save_press_image($press_release_id, $press_image)
    {

        App::uses('Folder', 'Utility');

        $date = date('Y') . DS . date('m') . DS . date('d');

        $file_path = ROOT . DS . 'app' . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'press_image' . DS . $date;

        $dir = new Folder($file_path, true, 0755);

        $this->loadModel('PressImage');

        foreach ($press_image as $image) {

            if ($image['image_name']['tmp_name'] != '') {

                $file_data = array();

                $file_data['PressImage']['describe_image'] = $image['describe_image'];

                $file_data['PressImage']['image_text'] = $image['image_text'];

                $file_data['PressImage']['press_release_id'] = $press_release_id;

                $file_data['PressImage']['image_path'] =  date('Y') . '/' . date('m') . '/' . date('d');

                $file_data['PressImage']['image_name'] = uniqid() . ".png";

                move_uploaded_file($image['image_name']['tmp_name'], $file_path . DS . $file_data['PressImage']['image_name']);

                $this->PressImage->create();

                $this->PressImage->save($file_data);
            } else {

                if (isset($image['id']) && empty($image['oldimage_name'])) {

                    unlink(ROOT . DS . 'app' . DS . 'webroot' . DS . $image['url']);

                    $this->PressImage->id = $image['id'];

                    if ($this->PressImage->exists()) {

                        $this->PressImage->delete();
                    }
                }
            }
        }
    }









    public function view($id = null, $redirect = '')
    {

        $this->set('title_for_layout', __('Press release View'));

        $this->PressRelease->recursive = 2;

        $this->PressRelease->bindModel(

            array(

                'hasOne' => array(

                    'TransactionPressRelease' => array(

                        'className' => 'TransactionPressRelease',

                        'foreignKey' => 'press_release_id',

                    )

                )

            )

        );



        $data = $this->PressRelease->read(null, $id);



        $this->set('data', $data);

        $plan_id  = $data['PressRelease']['plan_id'];

        $user_id  = $data['PressRelease']['staff_user_id'];



        $cartdata  = $this->Custom->getprcartdata($user_id, $plan_id, $id);

        $transdata = [];

        if ($cartdata['totals']['total'] == 0) {

            $this->loadModel('TransactionPressRelease');

            $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('staff_user_id' => $user_id, 'press_release_id' => $id)));
        }

        $this->set(compact('cartdata', 'transdata'));
    }



    public function frontview($id = null)
    {
    $this->set('title_for_layout', __('Release Front View'));
        $conditions = array();
        $conditions[] = array('PressRelease.id'=>$id);
        $this->PressRelease->recursive=2;
        $data=$this->PressRelease->find("first",array('conditions'=>$conditions));
        
       
        $meta_keyword ="";
        $meta_description =(isset($data['PressRelease']['summary'])&&!empty($data['PressRelease']['summary']))?$data['PressRelease']['summary']:""; 

        $company =(isset($data['Company']['name'])&&!empty($data['Company']['name']))?$data['Company']['name']:""; 
        $release_date =(isset($data['PressRelease']['release_date'])&&!empty($data['PressRelease']['release_date']))?$data['PressRelease']['release_date']:""; 
        $companylogo =(isset($data['Company']['logo'])&&!empty($data['Company']['logo']))?SITEFRONTURL.'files/company/logo/'.$data['Company']['logo_path'].'/'.$data['Company']['logo']:""; 
        $contact_name =(isset($data['Company']['contact_name'])&&!empty($data['Company']['contact_name']))?$data['Company']['contact_name']:""; 
        $singleImage=(!empty($data['PressImage']))?$data['PressImage']:""; 
        $this->set(compact('data','meta_description','meta_keyword',"company","release_date","companylogo","contact_name","singleImage"));
    }







    public function viewreport($prId = "")
    {

        $this->set('title_for_layout', __('Clipping report detail'));

        $model = "ClippingReport";

        $this->PressRelease->recursive = 2;

        $type = $this->request->query('type');

        $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "title", "views", "release_date", "staff_user_id")));



        $prconditions[] = array('ClippingReport.press_release_id' => $prId);

        if (!empty($this->request->query('type')) && $this->request->query('type') != 'mail_feed') {

            $prconditions[] = array('ClippingReport.distribution_type' => $this->request->query('type'));
        }

        if (!empty($this->request->query('sname'))) {

            $prconditions[] = array('ClippingReport.site_name' => $this->request->query('sname'));
        }

        $msts = $this->request->query('msts');

        if (!empty($this->request->query('type')) && $this->request->query('type') == 'mail_feed') {

            $this->loadModel("Subscriber");

            $cId = $this->request->query('cid');

            // $userId=$pr_data['PressRelease']['staff_user_id'];

            $champ = $this->Custom->getCampaignDetails($prId);

            $userId = (isset($champ["userID"])) ? $champ["userID"] : "";

            $conditions = $this->sendySubscriberCondi($msts, $userId, $cId);

            if ($msts == "clicked") {

                $model = "Link";

                $this->loadModel("Link");

                $this->paginate = array('conditions' => $conditions, 'limit' => Configure::read('Site.paging'), 'fields' => array("link", "clicks", 'id'));

                $data_array = $this->paginate('Link');
            } else {

                $model = "Subscriber";

                $this->paginate = array('conditions' => $conditions, 'limit' => Configure::read('Site.paging'), 'fields' => array('email', 'id'), 'order' => 'Subscriber.id DESC');



                $data_array = $this->paginate('Subscriber');
            }
        } else {



            $this->paginate = array('conditions' => $prconditions, 'limit' => Configure::read('Site.paging'), 'order' => 'PressRelease.release_date DESC');

            $data_array = $this->paginate('ClippingReport');
        }

        $this->set(compact('data_array', "model", 'pr_data', 'prId', 'type', "msts"));
    }





    public function sendySubscriberCondi($msts = '', $userId, $cId, $lid = '')
    {

        switch ($msts) {

            case 'admin':

                $campaign = $this->Campaign->find('first', array("fields" => array("to_send_lists"), "conditions" => array('id' => $cId)));

                $listId = $campaign['Campaign']['to_send_lists'];

                $condition = array("list" => $listId, "unsubscribed" => 0, "bounced" => 0, "complaint" => 0);



                break;

            case 'clicked':

                $condition = array('campaign_id' => $cId);

                break;

            case 'link-subscriber':

                $this->loadModel("Link");

                $linkId = $this->request->query('link');

                $linkData = $this->Link->find("first", array('fields' => array("clicks"), 'conditions' => array('id' => $linkId)));

                $total_clicks_array = explode(',', $linkData['Link']['clicks']);

                $unique_clicks = array_unique($total_clicks_array);

                $condition = array("id" => $unique_clicks, "unsubscribed" => 0, "bounced" => 0, "complaint" => 0);

                break;

            default:



                $campaign = $this->Campaign->find('first', array("fields" => array("opens"), "conditions" => array('id' => $cId)));

                $last_subscriber_ids = [];

                if (!empty($campaign['Campaign']['opens'])) {

                    $last_opens_array = explode(',', $campaign['Campaign']['opens']);

                    $loop_no = count(array_unique($last_opens_array));

                    for ($z = 0; $z < $loop_no; $z++) {

                        $last_opens_array2 = array_reverse(array_unique($last_opens_array));

                        $subscriber_id = explode(':', $last_opens_array2[$z]);

                        $last_subscriber_ids[] = $subscriber_id[0];
                    }
                }

                $condition = array("id" => $last_subscriber_ids);

                break;
        }



        return $condition;
    }





    public function download($prId = '')
    {

        $this->PressRelease->recursive = 2;

        $prconditions[] = array('ClippingReport.press_release_id' => $prId);

        if (!empty($this->request->query('type'))) {

            $prconditions[] = array('ClippingReport.distribution_type' => $this->request->query('type'));
        }

        if (!empty($this->request->query('sname'))) {

            $prconditions[] = array('ClippingReport.site_name' => $this->request->query('sname'));
        }

        $this->paginate = array('conditions' => $prconditions, 'limit' => 1000, 'order' => 'PressRelease.release_date DESC');



        $data_array = $this->paginate('ClippingReport');

        $this->PressRelease->recursive = -1;

        $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "company_id", "title", "views", "release_date", "staff_user_id")));



        $this->generatePdfDownloadReceipt($data_array, $pr_data);
    }

    function generatePdfDownloadReceipt($data_array, $pr_data)
    {

        include_once(APP . 'Vendor' . DS . 'dompdf/autoload.inc.php');

        $dompdf = new Dompdf();

        $dompdf->set_option('isRemoteEnabled', TRUE);

        /* $view = new View(null, false);

          $view->set(compact('variable1', 'variable2'));

          $view->viewPath = 'MaintenancePayments'; */

        //$output = $view->render('pdf_file');



        $this->loadModel('MaintenancePayment');

        $html = $this->Custom->getClippingReportHtml($data_array, $pr_data['PressRelease']);

        //echo $html;die;

        $dompdf->load_html($html);



        // (Optional) Setup the paper size and orientation

        $dompdf->setPaper('A4');

        // Render the HTML as PDF

        $dompdf->render();

        // echo $dompdf->output();



        // Output the generated PDF (1 = download and 0 = preview) 

        $filename = "clipping" . rand(0, 1000);

        $dompdf->stream($filename, array("Attachment" => 1));



        // $filename = "clipping".rand(0,1000).".pdf";

        // $filename = "clipping.pdf";

        // $fileUrl=ROOT .DS. 'app' . DS . 'webroot' . DS .'files'. DS . 'clippingreport' . DS . $filename;

        // file_put_contents($fileUrl, $dompdf->output());

    }





    public function clientReleases()
    {

        $this->set('title_for_layout', __('All PressReleases'));

        $data_array = [];



        $this->set('placeholder', 'Please enter title..');

        $conditions[] = array('PressRelease.staff_user_id' => $this->params['pass'][0]);

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('PressRelease.title like ' => '%' . $this->params->query['s'] . '%');
        }

        $this->paginate = array("conditions" => $conditions, 'limit' => Configure::read('Admin.paging'), 'order' => 'PressRelease.id DESC');

        $data_array = $this->paginate('PressRelease');



        $this->set("data_array", $data_array);
    }



    public function approvedby($user_id)
    {

        $this->set('title_for_layout', __('All PressReleases'));

        $data_array = [];



        $this->set('placeholder', 'Please enter title..');

        $conditions[] = array('PressRelease.approved_by' => $user_id);

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('PressRelease.title like ' => '%' . $this->params->query['s'] . '%');
        }

        $this->paginate = array("conditions" => $conditions, 'limit' => Configure::read('Admin.paging'), 'order' => 'PressRelease.id DESC');

        $data_array = $this->paginate('PressRelease');



        $this->set("data_array", $data_array);
    }
}
