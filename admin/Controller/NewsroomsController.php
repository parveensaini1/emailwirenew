<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Inflector', 'Utility');
/**
 * Newsrooms Controller
 *
 * @property Newsroom $Newsroom
 */
class NewsroomsController extends AppController
{
    public $name = 'Newsrooms';
    public $uses = array('Company', 'CompanyLog', 'TempCompany', 'CompanyDocument', 'StaffUser', 'EmailTemplate', 'Company', 'OrganizationType', 'Transaction', 'Country', 'PressRelease', "Category", "Msa", "CompaniesStaffUser", 'Cart', 'Coupon', 'Plan', "CompanyPresentation", 'CompanyPodcast', 'CompanyEbook');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('menutitle', 'Newsrooms');
        $this->set('menutitle_add', 'Newsroom');
        $this->set('controller', 'newsrooms');
        $this->set('model', 'Company');
        $this->set('placeholder', 'Please enter company name..');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('title_for_layout', __('All Newsrooms'));
        $this->set('model', 'Company');
        $data_array = [];
        if (isset($this->request->params['named']['page']) && !empty($this->request->params['named']['page']) && isset($this->params->query['s']) && !empty($this->params->query['s'])) {
            $url = str_replace('/page:' . $this->request->params['named']['page'], '', $_SERVER['REQUEST_URI']);
            $url = str_replace('admin/', '', $url);
            $this->redirect($url);
        }
        $conditions[] = array('Company.status' => '1');
        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {
            $this->set('keyword', $this->params->query['s']);
            $conditions[] = array('Company.name like ' => '%' . $this->params->query['s'] . '%');
        }
        $this->Company->recursive = "-1";
        $fields = array('Company.id', 'Company.name', 'Company.slug', 'contact_name', 'logo', 'logo_path', "status");
        $this->paginate = array('conditions' => $conditions, "fields" => $fields, 'order' => 'Company.id DESC', 'limit' => '15');
        $data_array = $this->paginate('Company');
        $this->set(compact("data_array"));
    }


    public function removeNewsroomCookie()
    {
        $this->Cookie->delete('nr_first_name');
        $this->Cookie->delete('nr_last_name');
        $this->Cookie->delete('nr_email');
        $this->Cookie->delete('nr_confirm_email');
        $this->Cookie->delete('nr_contact_name');
        $this->Cookie->delete('nr_description');
        $this->Cookie->delete('nr_about_us');
        $this->Cookie->delete('nr_job_title');
        $this->Cookie->delete('nr_org_typ_id');
        $this->Cookie->delete('nr_company_name');
        $this->Cookie->delete('nr_phone_number');
        $this->Cookie->delete('nr_fax_number');
        $this->Cookie->delete('nr_address');
        $this->Cookie->delete('nr_city');
        $this->Cookie->delete('nr_state');
        $this->Cookie->delete('nr_country');
        $this->Cookie->delete('nr_org_name');
        $this->Cookie->delete('nr_country_name');
        $this->Cookie->delete('nr_zip_code');
        $this->Cookie->delete('nr_web_site');
        $this->Cookie->delete('nr_blog_url');
        $this->Cookie->delete('nr_linkedin');
        $this->Cookie->delete('nr_twitter_link');
        $this->Cookie->delete('nr_fb_link');
        $this->Cookie->delete('nr_pinterest');
        $this->Cookie->delete('nr_instagram');
        $this->Cookie->delete('nr_tumblr');
        $this->Cookie->delete('nr_user_id');
        $this->Cookie->delete('nr_password');
        $this->Cookie->delete('nr_verify_password');
        $this->Cookie->delete('nr_profile_image');
        $this->Cookie->delete('nr_banner_path');
        $this->Cookie->delete('nr_banner_image');
        $this->Cookie->delete('encodedprofile');
        $this->Cookie->delete('encodedbanner');
        $this->Cookie->delete('encodedlogo');
        $this->Cookie->delete('nr_logo_path');
        $this->Cookie->delete('nr_logo');
        $this->Session->delete('nr_description');
        $this->Session->delete('nr_about_us');
        $this->Cookie->delete('nr_docfile_path');
        $this->Cookie->delete('nr_docfile');
        $this->Cookie->delete('nr_docfiles');
        $this->Cookie->delete('remove_doc');
        $this->Cookie->delete('company_id');
        $this->Cookie->delete('nr_media_email');
        $this->Cookie->delete('nr_media_job_title');
        $this->Cookie->delete('nr_media_phone_number');
        $this->Cookie->delete('nr_media_contact_name');
        // var_dump($this->Cookie->read('nr_first_name'));
        // var_dump($this->Cookie->write('user_name', 'Larry'));
        // var_dump($this->Cookie->read('nr_address'));
        //  var_dump($this->Cookie->delete('nr_address'));
        // var_dump($this->Cookie->read('nr_address'));
    }


    public function readNewsroomCookie()
    {
        $this->request->data['StaffUser']['first_name'] = ($this->Cookie->read('nr_first_name')) ? $this->Cookie->read('nr_first_name') : "";
        $this->request->data['StaffUser']['last_name'] = ($this->Cookie->read('nr_last_name')) ? $this->Cookie->read('nr_last_name') : "";
        $this->request->data['StaffUser']['email'] = ($this->Cookie->read('nr_email')) ? $this->Cookie->read('nr_email') : "";
        $this->request->data['StaffUser']['id'] = ($this->Cookie->read('nr_user_id')) ? $this->Cookie->read('nr_user_id') : "";
        $this->request->data['StaffUser']['confirm_email'] = ($this->Cookie->read('nr_confirm_email')) ? $this->Cookie->read('nr_confirm_email') : "";
        $this->request->data['Company']['id'] = ($this->Cookie->read('company_id')) ? $this->Cookie->read('company_id') : "";
        $this->request->data['Company']['media_contact_name'] = ($this->Cookie->read('nr_media_contact_name')) ? $this->Cookie->read('nr_media_contact_name') : "";
        $this->request->data['Company']['media_job_title'] = ($this->Cookie->read('nr_media_job_title')) ? $this->Cookie->read('nr_media_job_title') : "";
        $this->request->data['Company']['media_email'] = ($this->Cookie->read('nr_media_email')) ? $this->Cookie->read('nr_media_email') : "";
        $this->request->data['Company']['media_phone_number'] = ($this->Cookie->read('nr_media_phone_number')) ? $this->Cookie->read('nr_media_phone_number') : "";
        $this->request->data['Company']['contact_name'] = ($this->Cookie->read('nr_contact_name')) ? $this->Cookie->read('nr_contact_name') : "";
        $this->request->data['Company']['job_title'] = ($this->Cookie->read('nr_job_title')) ? $this->Cookie->read('nr_job_title') : "";
        $this->request->data['Company']['organization_type_id'] = ($this->Cookie->read('nr_org_typ_id')) ? $this->Cookie->read('nr_org_typ_id') : "";
        $this->request->data['Company']['name'] = ($this->Cookie->read('nr_company_name')) ? $this->Cookie->read('nr_company_name') : "";
        $this->request->data['Company']['phone_number'] = ($this->Cookie->read('nr_phone_number')) ? $this->Cookie->read('nr_phone_number') : "";
        $this->request->data['Company']['fax_number'] = ($this->Cookie->read('nr_fax_number')) ? $this->Cookie->read('nr_fax_number') : "";
        $this->request->data['Company']['address'] = ($this->Session->read('nr_address')) ? $this->Session->read('nr_address') : "";
        $this->request->data['Company']['city'] = ($this->Cookie->read('nr_city')) ? $this->Cookie->read('nr_city') : "";
        $this->request->data['Company']['state'] = ($this->Cookie->read('nr_state')) ? $this->Cookie->read('nr_state') : "";
        $this->request->data['Company']['country_id'] = ($this->Cookie->read('nr_country')) ? $this->Cookie->read('nr_country') : "";
        $this->request->data['Company']['nr_country_name'] = ($this->Cookie->read('nr_country_name')) ? $this->Cookie->read('nr_country_name') : "";
        $this->request->data['Company']['nr_org_name'] = ($this->Cookie->read('nr_org_name')) ? $this->Cookie->read('nr_org_name') : "";
        $this->request->data['Company']['zip_code'] = ($this->Cookie->read('nr_zip_code')) ? $this->Cookie->read('nr_zip_code') : "";
        $this->request->data['Company']['web_site'] = ($this->Cookie->read('nr_web_site')) ? $this->Cookie->read('nr_web_site') : "";
        $this->request->data['Company']['blog_url'] = ($this->Cookie->read('nr_blog_url')) ? $this->Cookie->read('nr_blog_url') : "";
        $this->request->data['Company']['linkedin'] = ($this->Cookie->read('nr_linkedin')) ? $this->Cookie->read('nr_linkedin') : "";
        $this->request->data['Company']['twitter_link'] = ($this->Cookie->read('nr_twitter_link')) ? $this->Cookie->read('nr_twitter_link') : "";
        $this->request->data['Company']['fb_link'] = ($this->Cookie->check('nr_fb_link')) ? $this->Cookie->read('nr_fb_link') : "";
        $this->request->data['Company']['pinterest'] = ($this->Cookie->check('nr_pinterest')) ? $this->Cookie->read('nr_pinterest') : "";
        $this->request->data['Company']['instagram'] = ($this->Cookie->check('nr_instagram')) ? $this->Cookie->read('nr_instagram') : "";
        $this->request->data['Company']['tumblr'] = ($this->Cookie->check('nr_tumblr')) ? $this->Cookie->read('nr_tumblr') : "";
        $this->request->data['StaffUser']['password'] = ($this->Cookie->check('nr_password')) ? $this->Cookie->read('nr_password') : "";
        $this->request->data['StaffUser']['verify_password'] = ($this->Cookie->check('nr_verify_password')) ? $this->Cookie->read('nr_verify_password') : "";
        $this->request->data['StaffUser']['profile_image'] = ($this->Cookie->check('nr_profile_image')) ? $this->Cookie->read('nr_profile_image') : "";
        $this->request->data['StaffUser']['encodedprofile'] = ($this->Cookie->check('encodedprofile')) ? $this->Cookie->read('encodedprofile') : "";
        $this->request->data['Company']['encodedbanner'] = ($this->Cookie->check('encodedbanner')) ? $this->Cookie->read('encodedbanner') : "";
        $this->request->data['Company']['banner_path'] = ($this->Cookie->check('nr_banner_path')) ? $this->Cookie->read('nr_banner_path') : "";
        $this->request->data['Company']['banner_image'] = ($this->Cookie->check('nr_banner_image')) ? $this->Cookie->read('nr_banner_image') : "";
        $this->request->data['Company']['encodedlogo'] = ($this->Cookie->check('encodedlogo')) ? $this->Cookie->read('encodedlogo') : "";
        $this->request->data['Company']['logo_path'] = ($this->Cookie->check('nr_logo_path')) ? $this->Cookie->read('nr_logo_path') : "";
        $this->request->data['Company']['logo'] = ($this->Cookie->check('nr_logo')) ? $this->Cookie->read('nr_logo') : "";
        $this->request->data['Company']['docfile_path'] = ($this->Cookie->check('nr_docfile_path')) ? $this->Cookie->read('nr_docfile_path') : "";
        $this->request->data['Company']['docfile'] = ($this->Cookie->check('nr_docfile')) ? $this->Cookie->read('nr_docfile') : "";
        $this->request->data['Company']['description'] = ($this->Session->check('nr_description')) ? $this->Session->read('nr_description') : "";
        $this->request->data['Company']['hear_about_us'] = ($this->Session->check('nr_about_us')) ? $this->Session->read('nr_about_us') : "";
        // var_dump($this->Cookie->read('nr_first_name'));
        // var_dump($this->Cookie->write('user_name', 'Larry'));
        // var_dump($this->Cookie->read('nr_address'));
        //  var_dump($this->Cookie->delete('nr_address'));
        // var_dump($this->Cookie->read('nr_address'));
    }



    private function save_transactions($user_id, $company_id, $user_data)
    {
        $errorString = "";
        $status = "Success";
        $data_array = array();
        $data_array['Transaction']['staff_user_id'] = $user_id;
        $data_array['Transaction']['tx_id'] = "admin";
        $data_array['Transaction']['intent'] = 'sale';
        $data_array['Transaction']['paymant_date'] = date('Y-m-d H:i:s');
        $data_array['Transaction']['currency'] = "USD";
        $data_array['Transaction']['discount_id'] = "0";
        $data_array['Transaction']['newsroom_amount'] = "0.00";
        $data_array['Transaction']['subtotal'] = "0.00";
        $data_array['Transaction']['discount'] = "0.00";
        $data_array['Transaction']['tax'] = "0.00";
        $data_array['Transaction']['total'] = "0.00";
        $data_array['Transaction']['status'] = $status;
        $data_array['Transaction']['error_message'] = $errorString;
        $data_array['Transaction']['company_id'] = $company_id;
        $data_array['Transaction']['is_plan_newsroom'] = "1";
        $this->loadModel('Transaction');
        if ($this->Transaction->save($data_array)) {
            $txId = $this->Transaction->getLastInsertID();
            if (empty($errorString)) {
                $this->Session->delete("ew_cartdata");
                $uName = $user_data['first_name'];
                $emailTemplate = $this->EmailTemplate->selectTemplate('payment-invoice');
                $mailTo = $user_data['email'];
                $this->Custom->send_invoice_mail($data_array, $emailTemplate, $uName, $mailTo);
                if ($company_id != 0 && $status == 'Success') {
                    $this->removeNewsroomCookie();
                }
                $this->redirect(array('controller' => 'newsrooms', 'action' => 'published'));
            } else {
                $this->Session->setFlash($errorString, 'error');
                $this->redirect(array('controller' => 'users', 'action' => 'payment/?err=' . $errorString));
            }
        }
    }



    private function sendmail_aftersignup($data)
    {
        $email = $data['email'];
        $key = Security::hash(CakeText::uuid(), 'sha1', true);
        $hash = sha1($data['email'] . rand(0, 20));
        $url = SITEFRONTURL . "users/activate/" . $key . '#' . $hash;
        $ms = $url;
        $ms = wordwrap($ms, 1000);
        $fu['StaffUser']['tokenhash'] = $key;
        $this->StaffUser->id = $data['id'];
        if ($this->StaffUser->saveField('tokenhash', $fu['StaffUser']['tokenhash'])) {
            $email = $this->EmailTemplate->selectTemplate('user-signup');
            $emailFindReplace = array(
                '##NAME##' => $data['first_name'],
                '##SITE_NAME##' => Configure::read('Site.name'),
                '##SUPPORT_EMAIL##' => Configure::read('Site.support_email'),
                '##ACCOUNT_ACTIVATE_LINK##' => $url,
                '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),
                '##FROM_EMAIL##' => Configure::read('EmailTemplate.from_email'),
                '##SITE_LOGO##' => Router::url(array(
                    'controller' => 'img',
                    'action' => '/',
                    'logo.png',
                    'admin' => false
                ), true)
            );
            $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
            $this->Email->replyTo = ($email['reply_to_email'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to') : $email['reply_to_email'];
            $this->Email->to =  $data['email'];
            $this->Email->subject = strtr($email['subject'], $emailFindReplace);
            $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
            $description = strtr($email['description'], $emailFindReplace);
            $this->Email->send($description);
            return true;
        }
    }

    public function pending()
    {
        $this->set('title_for_layout', __('All Pending Newsrooms'));
        $this->set('model', 'Company');
        $data_array = [];

        $conditions[] = array('Company.status' => '0');

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('Company.name like ' => '%' . $this->params->query['s'] . '%');
        }

        $this->Company->recursive = "1";

        $fields = array('Company.id', 'Company.name', 'Company.slug', 'contact_name', 'logo', 'logo_path', "status", 'Transaction.newsroom_amount', 'Transaction.status');



        $this->paginate = array(
            'conditions' => $conditions, "fields" => $fields, 'order' => 'Company.id DESC', 'contain'       => array(

                'StaffUser'          => array(

                    'fields' => array('id')

                ),

                'Transaction'          => array(

                    'fields' => array('newsroom_amount', 'status')

                ),

            ),

            'limit' => '15'
        );

        $data_array = $this->paginate('Company');



        $this->set(compact("data_array"));
    }



    public function published()
    {

        $this->set('model', 'Company');

        $this->set('title_for_layout', __('All published newsrooms'));

        $data_array = [];


        $conditions[] = array('Company.status' => '1');

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('Company.name like ' => '%' . $this->params->query['s'] . '%');
        }

        $this->Company->recursive = "-1";

        $fields = array('Company.id', 'Company.name', 'Company.slug', 'hear_about_us', 'contact_name', 'logo', 'logo_path', "status", "approved_by");

        $this->paginate = array('conditions' => $conditions, "fields" => $fields, 'order' => 'Company.id DESC', 'limit' => '15');

        $data_array = $this->paginate('Company');

        $this->set(compact("data_array"));
    }



    public function suspended()
    {

        $this->set('title_for_layout', __('All suspended newsrooms'));

        $this->set('model', 'Company');

        $data_array = [];


        $conditions[] = array('Company.status' => '2');

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('Company.name like ' => '%' . $this->params->query['s'] . '%');
        }

        $this->Company->recursive = "-1";

        $fields = array('Company.id', 'Company.name', 'Company.slug', 'contact_name', 'logo', 'logo_path', 'disapproval_reason', "status", "suspended_date", "approved_by");

        $this->paginate = array('conditions' => $conditions, "fields" => $fields, 'order' => 'Company.id DESC', 'limit' => '15');

        $data_array = $this->paginate('Company');



        $this->set(compact("data_array"));
    }


    public function disapproved()
    {

        $this->set('title_for_layout', __('All Disapproved newsrooms'));

        $this->set('model', 'Company');

        $data_array = [];

        $conditions[] = array('Company.status' => '3');

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('Company.name like ' => '%' . $this->params->query['s'] . '%');
        }

        $this->Company->recursive = "-1";

        $fields = array('Company.id', 'Company.name', 'Company.slug', 'contact_name', 'logo', 'logo_path', "status", 'disapproval_reason', "status", "disapproval_date", "approved_by");

        $this->paginate = array('conditions' => $conditions, "fields" => $fields, 'order' => 'Company.id DESC', 'limit' => '15');

        $data_array = $this->paginate('Company');

        $this->set(compact("data_array"));
    }


    public function suspend_company($id = null, $redirect)
    {

        $this->Company->id = $id;

        if (!$this->Company->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        $reason = (!empty($this->request->query['reason'])) ? $this->request->query['reason'] : "Your newsroom has been suspended.";

        $save['Company']['suspended_date'] = date('Y-m-d H:i:s');

        $save['Company']['disapproval_reason'] = $reason;

        $save['Company']['approved_by'] = $this->Auth->user('id');

        $save['Company']['status'] = '2';

        if ($this->Company->save($save)) {

            $data = $this->Company->read(null, $id);

            if (isset($data['StaffUser']['0']['email']) && !empty($data['StaffUser']['0']['email'])) {

                $uName = $data['StaffUser']['0']['first_name'] . " " . $data['StaffUser']['0']['last_name'];

                $check = $this->Custom->sendmailonaction($uName, $data['StaffUser']['0']['email'], $reason, 'Newsroom suspended', 'Your newsroom has suspended due to');
            }

            $this->Session->setFlash(__('Newsroom successfully suspended.'), 'success');

            if ($redirect == 'view') {

                $this->redirect(array("controller" => "newsrooms", 'action' => $redirect, $id));
            } else {

                $this->redirect(array("controller" => "newsrooms", 'action' => $redirect));
            }
        }
    }

    public function active_company($id = null, $redirect = '')
    {

        $this->Company->id = $id;

        if (!$this->Company->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        $savedata['Company']['id'] = $id;

        $savedata['Company']['suspended_date'] = null;

        $savedata['Company']['disapproval_date'] = null;

        $savedata['Company']['disapproval_reason'] = null;

        $savedata['Company']['approved_by'] = $this->Auth->user('id');

        $savedata['Company']['status'] = "1";

        if ($this->Company->save($savedata)) {

            $data = $this->Company->read(null, $id);

            if (isset($data['StaffUser']['0']['email']) && !empty($data['StaffUser']['0']['email'])) {

                $uName = $data['StaffUser']['0']['first_name'] . " " . $data['StaffUser']['0']['last_name'];

                $mesg = 'Your newsroom has been activated.';

                if ($redirect == 'pending') {

                    $mesg = 'Your newsroom has been approved.';
                }

                $this->Custom->sendmailonaction($uName, $data['StaffUser']['0']['email'], $mesg, 'Newsroom Approved');
            }

            $this->Session->setFlash(__('Newsroom Approved.'), 'success');

            if ($redirect == 'view') {

                $this->redirect(array("controller" => "newsrooms", 'action' => $redirect, $id));
            } else {

                $this->redirect(array("controller" => "newsrooms", 'action' => $redirect));
            }
        }
    }

    public function inactive_company($id = null, $redirect = '')
    {

        $data = $this->Company->read(null, $id);

        if (isset($data['StaffUser']['0']['email']) && !empty($data['StaffUser']['0']['email'])) {

            $uName = $data['StaffUser']['0']['first_name'] . " " . $data['StaffUser']['0']['last_name'];

            $reason = (!empty($this->request->query['reason'])) ? $this->request->query['reason'] : "Your newsroom has been disapproved.";

            $check = $this->Custom->sendmailonaction($uName, $data['StaffUser']['0']['email'], $reason, 'Disapproved Newsroom', 'Your newsroom has disaproved due to');

            $savedata['Company']['disapproval_reason'] = $reason;
        }

        $savedata['Company']['id'] = $id;

        $savedata['Company']['disapproval_date'] = date('Y-m-d H:i:s');

        $savedata['Company']['status'] = "3";

        $savedata['Company']['approved_by'] = $this->Auth->user('id');

        if ($this->Company->save($savedata)) {

            $this->Session->setFlash(__('Newsroom Disapproved.'), 'success');

            if ($redirect == 'view') {

                $this->redirect(array("controller" => "newsrooms", 'action' => $redirect, $id));
            } else {

                $this->redirect(array("controller" => "newsrooms", 'action' => $redirect));
            }
        }
    }

    public function view($slug = '', $returnType = "published", $newsroomFilter = 'prnews', $currentpage = '1')
    {
        try {
            $isFullwidth = "";
            $model = "Company";
            if (!$slug) {
                throw new NotFoundException('Invalid Newsroom.');
            }
            $prarray = $conditions = array();
            $this->$model->bindModel(array('belongsTo' => array('OrganizationType', 'Country')));

            $this->request->data = $data = $this->$model->find("first", array('conditions' => array("$model.slug" => $slug)));
            if (empty($data)) {
                throw new NotFoundException('Invalid Newsroom.');
            }
            $companyId = $data[$model]['id'];
            $this->set('title_for_layout', ucfirst($data[$model]['name']));

            if ($newsroomFilter == 'prnews') {
                $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube', 'PressPoadcast'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution'), 'belongsTo' => array('Plan')));
                $prconditions[] = array('PressRelease.status' => '1', 'PressRelease.release_date <=' => date('Y-m-d'), "company_id" => $companyId);
                $this->paginate = array('conditions' => $prconditions, 'limit' => Configure::read('Site.paging'), 'order' => 'PressRelease.release_date DESC');
                $prarray = $this->paginate('PressRelease');
            } else if ($newsroomFilter == 'social') {
                require APP . 'Vendor' . DS . 'tumblr' . DS . 'tumblr.php';
                $obj = new tumblrFeed();
                if (!empty($data[$model]['tumblr'])) {
                    $blogName = str_replace(array("http:", "/"), array("", ""), $data[$model]['tumblr']);
                    $tumblrData = $obj->fetchfeeds($blogName, '10');
                    $this->set('tumblrData', $tumblrData);
                }
            }

            if ($newsroomFilter == 'companyassets') {
                $this->newsroom_assets($companyId, $data['StaffUser']['id'], $currentpage, $data[$model]['slug']);
                // $doc_data = $this->CompanyDocument->find('all',array('conditions'=>array('company_id'=>$data[$model]['id'])));
            }

            $doc_files = $doc_video = $doc_files = $doc_image = "";
            $this->set('model', $model);
            $this->set(compact('data', 'prarray', 'newsroomFilter', 'returnType', "doc_files", "doc_video", "doc_files", "doc_image", "isFullwidth", "companyId", "slug"));
        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => $returnType));
        }
    }

    // public function view($id = '')
    // {

    //     $this->set('title_for_layout', 'Newsroom');

    //     $conditions = array();

    //     $this->Company->bindModel(array('belongsTo' => array('OrganizationType', 'Country')));

    //     $data = $this->Company->read(null, $id);

    //     $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution'), 'belongsTo' => array('Plan')));

    //     $prconditions[] = array('PressRelease.status' => '1', "company_id" => $id);

    //     $this->paginate = array('conditions' => $prconditions, 'limit' => Configure::read('Admin.paging'), 'order' => 'PressRelease.release_date DESC');

    //     $prarray = $this->paginate('PressRelease');

    //     $this->set(compact('data', 'prarray'));
    // }

    public function edit_newsroom($id = null, $returnType = "published")
    {
        $modal = "TempCompany";
        $this->set('title_for_layout', __('Edit Newsroom'));
        $this->set('model', $modal);
        $date = date('Y') . DS . date('m');
        $nr_docfiles = "";
        try {
            if (!$id) {
                throw new NotFoundException('Invalid Newsroom.');
            }
            $data = [];
            $this->Company->id = $id;
            if (!$this->Company->exists()) {
                throw new NotFoundException('Invalid Newsroom.');
            }

            $data = $this->TempCompany->find('first', ['conditions' => ['TempCompany.company_id' => $id], 'order' => 'TempCompany.id DESC']);
            if (!empty($this->data)) {

                $this->request->data[$modal]['logo'] = $this->request->data[$modal]['temp_logo'];
                $this->request->data[$modal]['banner_image'] = $this->request->data[$modal]['temp_banner_image'];
                $this->request->data['StaffUser']['profile_image'] = $this->request->data['StaffUser']['temp_profile_image'];

                $save = $this->request->data;
                $save[$modal]['company_id'] = $id;
                $save[$modal]['staff_user_data'] = serialize($this->data['StaffUser']);

                $nr_docfiles = (!empty($save['CompanyDocument'])) ? $save['CompanyDocument'] : [];

                unset($save[$modal]['temp_logo']);
                unset($save[$modal]['temp_banner_image']);
                unset($save['StaffUser']);
                unset($save['CompanyDocument']);
                if (!empty($nr_docfiles)) {
                    $dirFile = ROOT . DS . "app" . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'docfile' . DS . $date;
                    $dir = new Folder($dirFile, true, 0755);
                    $docCount = 0;
                    $companyDocument = [];
                    foreach ($nr_docfiles as $nr_doc_key => $nr_doc_value) {
                        if (!empty($nr_doc_value['file_name']['tmp_name'])) {
                            $info=explode(".",$nr_doc_value['file_name']['name']);
                            $ext=end($info);
                            $fileName=Inflector::slug($info[0], '-').".$ext";
                            $this->Custom->document_upload($fileName, $nr_doc_value['file_name']['tmp_name'], $dirFile);

                            $companyDocument['CompanyDocument'][$docCount]['file_name'] =$fileName;
                            $companyDocument['CompanyDocument'][$docCount]['file_path'] = (!empty($nr_doc_value['file_path'])) ? $nr_doc_value['file_path'] : $date;
                            $companyDocument['CompanyDocument'][$docCount]['doc_caption'] = (!empty($nr_doc_value['doc_caption'])) ? $nr_doc_value['doc_caption'] : null;
                            $companyDocument['CompanyDocument'][$docCount]['id'] = (!empty($nr_doc_value['id'])) ? $nr_doc_value['id'] : null;
                        } else if (!empty($nr_doc_value['file_name']) && !empty($nr_doc_value['file_path'])) {
                            $companyDocument['CompanyDocument'][$docCount] = $nr_doc_value;
                        }
                        $docCount++;
                    }
                    $save['TempCompany']['extra_document'] = serialize($companyDocument);
                    // $this->CompanyDocument->saveMany($companyDocument['CompanyDocument']);

                } else {
                    $save['TempCompany']['extra_document'] = null;
                }
                unset($this->request->data['CompanyDocument']);
                $companyPresentation = [];
                $presentations = (!empty($this->data['CompanyPresentation'])) ? array_values($this->data['CompanyPresentation']) : [];
                unset($save['CompanyPresentation']);
                $companyPresentation = [];
                if (!empty($presentations)) {
                    $preCount = 0;
                    foreach ($presentations as $nr_doc_key => $presentation) {
                        if (!empty($presentation['url'])) {
                            $companyPresentation['CompanyPresentation'][$preCount]['url'] = $presentation['url'];
                            $companyPresentation['CompanyPresentation'][$preCount]['description'] = $presentation['description'];
                            $companyPresentation['CompanyPresentation'][$preCount]['company_id'] = (!empty($id)) ? $id : null;
                            if (!empty($presentation['id'])) {
                                $companyPresentation['CompanyPresentation'][$preCount]['id'] = $presentation['id'];
                            }
                        } else {
                            unset($presentation);
                        }
                        $preCount++;
                    }

                    $save['TempCompany']['presentation'] = serialize($companyPresentation);
                } else {
                    $save['TempCompany']['presentation'] = null;
                }


                $companyEbook = [];
                $ebooks = (!empty($this->data['CompanyEbook'])) ? array_values($this->data['CompanyEbook']) : [];
                unset($save['CompanyEbook']);
                $companyEbook = [];
                if (!empty($ebooks)) {
                    $eCount = 0;
                    foreach ($ebooks as $nr_doc_key => $ebooks) {
                        if (!empty($ebooks['embedded'])) {
                            $companyEbook['CompanyEbook'][$eCount]['embedded'] = $ebooks['embedded'];
                            $companyEbook['CompanyEbook'][$eCount]['description'] = $ebooks['description'];
                            $companyEbook['CompanyEbook'][$eCount]['company_id'] = (!empty($id)) ? $id : null;
                            if (!empty($ebooks['id'])) {
                                $companyEbook['CompanyEbook'][$eCount]['id'] = $ebooks['id'];
                            }
                        } else {
                            unset($ebooks);
                        }
                        $eCount++;
                    }

                    $save['TempCompany']['ebooks'] = serialize($companyEbook);
                } else {
                    $save['TempCompany']['ebooks'] = null;
                }

                $companyPodcast = [];
                $podcasts = (!empty($this->data['CompanyPodcast'])) ? array_values($this->data['CompanyPodcast']) : [];
                unset($save['CompanyPodcast']);
                $companyPodcast = [];
                if (!empty($podcasts)) {
                    $podCount = 0;
                    foreach ($podcasts as $nr_doc_key => $podcast) {
                        if (!empty($podcast['embedded'])) {
                            $companyPodcast['CompanyPodcast'][$podCount]['embedded'] = $podcast['embedded'];
                            $companyPodcast['CompanyPodcast'][$podCount]['description'] = $podcast['description'];
                            $companyPodcast['CompanyPodcast'][$podCount]['company_id'] = (!empty($id)) ? $id : null;
                            if (!empty($podcast['id'])) {
                                $companyPodcast['CompanyPodcast'][$podCount]['id'] = $podcast['id'];
                            }
                        } else {
                            unset($podcasts);
                        }
                        $podCount++;
                    }

                    $save['TempCompany']['podcasts'] = serialize($companyPodcast);
                } else {
                    $save['TempCompany']['podcasts'] = null;
                }

                $save['TempCompany']['staff_user_id'] = (!empty($this->data['StaffUser']['id'])) ? $this->data['StaffUser']['id'] : null;


                // unset($this->request->data['TempCompany']['docfile']);
                if ($this->TempCompany->validates()) { //$this->StaffUser->validates()&&
                    if (!empty($data['TempCompany']['id'])) {
                        $insertId = $save['TempCompany']['id'] = $data['TempCompany']['id'];
                    }
                    $this->TempCompany->save($save);
                    if (empty($insertId)) {
                        $insertId = $this->TempCompany->getLastInsertID();
                    }
                    $this->redirect(array('controller' => 'newsrooms', 'action' => 'edit_preview', $insertId, $returnType));
                } else {
                    $errors = $this->TempCompany->validationErrors;
                    $this->Session->setFlash(__('There is some error in below form. Please see red message.'), 'error');
                }
            } else {
                $this->request->data = $data;
                if (empty($data)) {
                    $data = $this->Company->read(null, $id);
                    $this->request->data = $data;
                    $this->request->data['TempCompany'] = $data['Company'];
                } else {
                    $data['StaffUser'] = (!empty($data["TempCompany"]['staff_user_data'])) ? unserialize($data["TempCompany"]['staff_user_data']) : [];
                    $nr_docfiles = (!empty($data["TempCompany"]['extra_document'])) ? unserialize($data["TempCompany"]['extra_document']) : [];
                    // pr($nr_docfiles);die;
                    $data['CompanyDocument'] = (!empty($nr_docfiles['CompanyDocument'])) ? $nr_docfiles['CompanyDocument'] : [];

                    $savedPresentation = (!empty($data["TempCompany"]['presentation'])) ? unserialize($data["TempCompany"]['presentation']) : [];

                    $this->request->data['CompanyPresentation'] = (!empty($savedPresentation['CompanyPresentation'])) ? $savedPresentation['CompanyPresentation'] : [];

                    $savedPodcast = (!empty($data["TempCompany"]['podcasts'])) ? unserialize($data["TempCompany"]['podcasts']) : [];
                    $this->request->data['CompanyPodcast'] = (!empty($savedPodcast['CompanyPodcast'])) ? $savedPodcast['CompanyPodcast'] : [];

                    $savedEbook = (!empty($data["TempCompany"]['ebooks'])) ? unserialize($data["TempCompany"]['ebooks']) : [];
                    $this->request->data['CompanyEbook'] = (!empty($savedEbook['CompanyEbook'])) ? $savedEbook['CompanyEbook'] : [];
                }
            }

            $country_list = $this->Custom->getCountryList();
            $nr_docfiles = (!empty($data['CompanyDocument'])) ? $data['CompanyDocument'] : [];
            $organization_list = $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1)));

            $this->set(compact('country_list', 'organization_list', 'returnType', 'nr_docfiles', 'id', 'data'));
        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function edit_preview($tempId = '', $returnType = "published", $newsroomFilter = 'prnews', $currentpage = '1')
    {
        $isFullwidth = "";
        $model = "TempCompany";
        if (!empty($tempId)) {
            $prarray = $conditions = array();
            $this->$model->bindModel(array('belongsTo' => array('OrganizationType', 'Country')));
            if (!empty($this->data) && $this->request->is("post")) {
                $this->Company->bindModel(array('hasMany' => array('CompanyDocument')));

                $data = $this->$model->find("first", array('conditions' => array("$model.id" => $this->data['TempCompany']['id'])));
                $doc_data = (!empty($data["TempCompany"]['extra_document'])) ? unserialize($data["TempCompany"]['extra_document']) : [];
                $companyId = $data["TempCompany"]['company_id'];
                $svTempId = $data["TempCompany"]['id'];
                $tempUserData = unserialize($data["TempCompany"]['staff_user_data']);
                $saveData['Company'] = $data["TempCompany"];
                $saveData['Company']['id'] = $companyId;

                $this->CompanyDocument->deleteAll(['company_id' => $companyId], false);
                if (!empty($data["TempCompany"]['extra_document'])) {
                    $extraDocument = unserialize($data["TempCompany"]['extra_document']);
                    if (!empty($extraDocument)) {
                        $this->loadModel('CompanyDocument');
                        $saveData['CompanyDocument'] = $extraDocument['CompanyDocument'];
                    }
                }

                $this->CompanyPresentation->deleteAll(['company_id' => $companyId], false);
                if (!empty($data["TempCompany"]['presentation'])) {
                    $companyPresentationData = unserialize($data["TempCompany"]['presentation']);
                    if (!empty($companyPresentationData)) {
                        $this->loadModel('CompanyPresentation');
                        $saveData['CompanyPresentation'] = $companyPresentationData['CompanyPresentation'];
                    }
                }

                $this->CompanyPodcast->deleteAll(['company_id' => $companyId], false);
                if (!empty($data["TempCompany"]['podcasts'])) {
                    $companyPodcastData = unserialize($data["TempCompany"]['podcasts']);
                    if (!empty($companyPodcastData)) {
                        $this->loadModel('CompanyPodcast');
                        $saveData['CompanyPodcast'] = $companyPodcastData['CompanyPodcast'];
                    }
                }


                $this->CompanyEbook->deleteAll(['company_id' => $companyId], false);
                if (!empty($data["TempCompany"]['ebooks'])) {
                    $companyEbookData = unserialize($data["TempCompany"]['ebooks']);
                    if (!empty($companyEbookData)) {
                        $this->loadModel('CompanyEbook');
                        $saveData['CompanyEbook'] = $companyEbookData['CompanyEbook'];
                    }
                }
                unset($data["TempCompany"]['id']);
                unset($data["TempCompany"]['staff_user_data']);
                unset($data["TempCompany"]['company_id']);
                unset($data["TempCompany"]['extra_document']);
                unset($data["TempCompany"]['podcasts']);
                unset($data["TempCompany"]['ebooks']);
                if ($this->Company->saveAll($saveData, array("deep" => true))) {
                    $saveUserData['StaffUser'] = $tempUserData;
                    // $saveUserData['StaffUser']['email']=strtolower($tempUserData['StaffUser']['email']);
                    $this->StaffUser->save($saveUserData);
                    $this->TempCompany->id = $svTempId;
                    $this->TempCompany->delete();
                }
                $this->Session->setFlash(__('Detail successfully updated'), 'success');
                $this->redirect(array('action' => $returnType));
                exit;
            }

            $data = $this->$model->find("first", array('conditions' => array("$model.id" => $tempId)));
            $data['StaffUser'] = unserialize($data["TempCompany"]['staff_user_data']);
            $data['Company'] = $data["TempCompany"];

            $doc_data = (!empty($data["TempCompany"]['extra_document'])) ? unserialize($data["TempCompany"]['extra_document']) : [];
            $this->request->data['CompanyDocument'] = (!empty($doc_data['CompanyDocument'])) ? $doc_data['CompanyDocument'] : [];

            $presentationData = (!empty($data["TempCompany"]['presentation'])) ? unserialize($data["TempCompany"]['presentation']) : [];
            $this->request->data['CompanyPresentation'] = (!empty($presentationData['CompanyPresentation'])) ? $presentationData['CompanyPresentation'] : [];

            $podData = (!empty($data["TempCompany"]['podcasts'])) ? unserialize($data["TempCompany"]['podcasts']) : [];
            $this->request->data['CompanyPodcast'] = (!empty($podData['CompanyPodcast'])) ? $podData['CompanyPodcast'] : [];

            $ebookData = (!empty($data["TempCompany"]['ebooks'])) ? unserialize($data["TempCompany"]['ebooks']) : [];

            $this->request->data['CompanyEbook'] = (!empty($ebookData['CompanyEbook'])) ? $ebookData['CompanyEbook'] : [];


            $this->loadModel('CompanyDocument');
            $this->set('title_for_layout', ucfirst($data[$model]['name']));
            if ($newsroomFilter == 'prnews') {
                $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube', 'PressPoadcast'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution'), 'belongsTo' => array('Plan')));
                $prconditions[] = array('PressRelease.status' => '1', 'PressRelease.release_date <=' => date('Y-m-d'), "company_id" => $data[$model]['company_id']);
                $this->paginate = array('conditions' => $prconditions, 'limit' => Configure::read('Site.paging'), 'order' => 'PressRelease.release_date DESC');
                $prarray = $this->paginate('PressRelease');
            } else if ($newsroomFilter == 'social') {
                require APP . 'Vendor' . DS . 'tumblr' . DS . 'tumblr.php';
                $obj = new tumblrFeed();
                if (!empty($data[$model]['tumblr'])) {
                    $blogName = str_replace(array("http:", "/"), array("", ""), $data[$model]['tumblr']);
                    $tumblrData = $obj->fetchfeeds($blogName, '10');
                    $this->set('tumblrData', $tumblrData);
                }
            }

            if ($newsroomFilter == 'companyassets') {
                $this->newsroom_assets($data["TempCompany"]['company_id'], $data['StaffUser']['id'], $currentpage, $data["TempCompany"]['slug']);
                // $doc_data = $this->CompanyDocument->find('all',array('conditions'=>array('company_id'=>$data[$model]['id'])));
            }

            $doc_files = $doc_video = $doc_files = $doc_image = "";
            $this->set('model', $model);
            $this->set(compact('data', 'prarray', 'newsroomFilter', 'returnType', "doc_files", "doc_video", "doc_files", "doc_image", "isFullwidth", "tempId", 'doc_data', "presentationData", 'ebookData', 'podData'));
        }
    }


    public function newsroom_assets($newsroomId, $user_id, $currentpage, $slug)
    {

        // $limit=Configure::read('Site.paging');

        $limit = 5;

        $totalCounts = $this->StaffUser->query("CALL newsroom_media_count(" . $newsroomId . "," . $user_id . ");");

        $totalCount = (isset($totalCounts[0][0]['totalcount']) && !empty($totalCounts[0][0]['totalcount'])) ? $totalCounts[0][0]['totalcount'] : "0";

        $totalpages = ($totalCount / $limit);

        $offset = (($currentpage - 1) * $limit);

        $media_array = $this->StaffUser->query("CALL newsroom_media(" . $newsroomId . "," . $user_id . "," . $limit . "," . $offset . ");");
        if (!empty($media_array)) {

            foreach ($media_array as $key => $mediadata) {

                if (isset($mediadata['PI']['image_path']) && !empty($mediadata['PI']['image_path'])) {

                    $imageUrl = SITEFRONTURL . 'files/company/press_image/' . $mediadata['PI']['image_path'] . '/' . $mediadata['PI']['image_name'];
                    $ext = pathinfo($mediadata['PI']['image_name'], PATHINFO_EXTENSION);
                    $dest = ROOT . DS . "app" . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'press_image' . DS . 'thumb' . DS . str_replace("." . $ext, "", $mediadata['PI']['image_name']) . '-' . $this->thumbWidth . 'x' . $this->thumbHeight . '.' . $ext;
                    $fileUrl = ROOT . DS . "app" . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'press_image' . DS . $mediadata['PI']['image_path'] . DS . $mediadata['PI']['image_name'];
                    if (!file_exists($fileUrl)) {
                        $this->Custom->crop_resize_image($fileUrl, $dest, 80, $this->thumbWidth, $this->thumbHeight);
                    }
                }
            }
        }
        $this->set(compact('media_array', 'totalCount', 'totalpages', 'currentpage'));
    }


    public function create_newsroom($companyId = null)
    {
       
        $modal = "TempCompany";
        $this->set('title_for_layout', __('Create Newsroom'));
        $this->set('model', $modal);
        $date = date('Y') . DS . date('m');
        $nr_docfiles = "";
        try {
            $nr_docfiles = $save = $data = [];
            if (!empty($this->data)) {
                $this->request->data[$modal]['logo'] = $this->request->data[$modal]['temp_logo'];
                $this->request->data[$modal]['banner_image'] = $this->request->data[$modal]['temp_banner_image'];
                if (!empty($this->request->data['StaffUser']['temp_profile_image'])) {
                    $this->request->data['StaffUser']['profile_image'] = $this->request->data['StaffUser']['temp_profile_image'];
                }
                // $this->request->data['Company']['status']='4';

                if (!empty($this->request->data['StaffUser']['id'])) {
                    unset($this->request->data['StaffUser']['password']);
                    unset($this->request->data['StaffUser']['verify_password']);
                }

                $save = $this->request->data;
                $save['TempCompany']['staff_user_id'] = (!empty($this->data['StaffUser']['id'])) ? $this->data['StaffUser']['id'] : null;
                $save[$modal]['staff_user_data'] = serialize($this->data['StaffUser']);
                $nr_docfiles = (!empty($save['CompanyDocument'])) ? $save['CompanyDocument'] : [];
                unset($save[$modal]['temp_logo']);
                unset($save[$modal]['temp_banner_image']);
                unset($save['StaffUser']);
                unset($save['CompanyDocument']);
                // if (!empty($nr_docfiles)) {
                //     $dirFile = ROOT . DS . "app" . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'docfile' . DS . $date;
                //     $dir = new Folder($dirFile, true, 0755);
                //     $docCount = 0;
                //     $companyDocument = [];
                //     foreach ($nr_docfiles as $nr_doc_key => $nr_doc_value) {
                //         if (!empty($nr_doc_value['tmp_name'])) {
                //             $this->Custom->document_upload($nr_doc_value['name'], $nr_doc_value['tmp_name'], $dirFile);
                //             $companyDocument['CompanyDocument'][$docCount]['file_name'] = $nr_doc_value['name'];
                //             $companyDocument['CompanyDocument'][$docCount]['file_path'] = $date;
                //             $companyDocument['CompanyDocument'][$docCount]['company_id'] = $companyId;
                //         } else if (!empty($nr_doc_value['file_name'])) {
                //             $companyDocument['CompanyDocument'][$docCount] = $nr_doc_value;
                //         }
                //         $docCount++;
                //     }
                //     $save['TempCompany']['extra_document'] = serialize($companyDocument);
                //     // $this->CompanyDocument->saveMany($companyDocument['CompanyDocument']);

                // } else {
                //     $save['TempCompany']['extra_document'] = null;
                // }

                if (!empty($nr_docfiles)) {
                    $dirFile = ROOT . DS . "app" . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'docfile' . DS . $date;
                    $dir = new Folder($dirFile, true, 0755);
                    $docCount = 0;
                    $companyDocument = [];
                    foreach ($nr_docfiles as $nr_doc_key => $nr_doc_value) {
                        if (!empty($nr_doc_value['file_name']['tmp_name'])) {
                            $info=explode(".",$nr_doc_value['file_name']['name']);
                            $ext=end($info);
                            $fileName=Inflector::slug($info[0], '-').".$ext";
                            $this->Custom->document_upload($fileName, $nr_doc_value['file_name']['tmp_name'], $dirFile);
                            $companyDocument['CompanyDocument'][$docCount]['file_name'] =$fileName;
                            $companyDocument['CompanyDocument'][$docCount]['file_path'] = (!empty($nr_doc_value['file_path'])) ? $nr_doc_value['file_path'] : $date;
                            $companyDocument['CompanyDocument'][$docCount]['doc_caption'] = (!empty($nr_doc_value['doc_caption'])) ? $nr_doc_value['doc_caption'] : null;
                            $companyDocument['CompanyDocument'][$docCount]['id'] = (!empty($nr_doc_value['id'])) ? $nr_doc_value['id'] : null;
                        } else if (!empty($nr_doc_value['file_name']) && !empty($nr_doc_value['file_path'])) {
                            $companyDocument['CompanyDocument'][$docCount] = $nr_doc_value;
                        }
                        $docCount++;
                    }
                    $save['TempCompany']['extra_document'] = serialize($companyDocument);
                    // $this->CompanyDocument->saveMany($companyDocument['CompanyDocument']);

                } else {
                    $save['TempCompany']['extra_document'] = null;
                }
                unset($this->request->data['CompanyDocument']);

                $companyPresentation = [];
                $presentations = (!empty($this->data['CompanyPresentation'])) ? array_values($this->data['CompanyPresentation']) : [];
                unset($save['CompanyPresentation']);
                $companyPresentation = [];
                if (!empty($presentations)) {
                    $preCount = 0;
                    foreach ($presentations as $nr_doc_key => $presentation) {
                        if (!empty($presentation['url'])) {
                            $companyPresentation['CompanyPresentation'][$preCount]['url'] = $presentation['url'];
                            $companyPresentation['CompanyPresentation'][$preCount]['description'] = $presentation['description'];
                            $companyPresentation['CompanyPresentation'][$preCount]['company_id'] = (!empty($companyId)) ? $companyId : null;
                            if (!empty($presentation['id'])) {
                                $companyPresentation['CompanyPresentation'][$preCount]['id'] = $presentation['id'];
                            }
                        } else {
                            unset($presentation);
                            unset($this->request->data['CompanyPresentation'][$preCount]);
                        }
                        $preCount++;
                    }

                    $save['TempCompany']['presentation'] = serialize($companyPresentation);
                } else {
                    $save['TempCompany']['presentation'] = null;
                }


                $companyEbook = [];
                $ebooks = (!empty($this->data['CompanyEbook'])) ? array_values($this->data['CompanyEbook']) : [];
                unset($save['CompanyEbook']);
                $companyEbook = [];
                if (!empty($ebooks)) {
                    $eCount = 0;
                    foreach ($ebooks as $nr_doc_key => $ebook) {
                        if (!empty($ebook['embedded'])) {
                            $companyEbook['CompanyEbook'][$eCount]['embedded'] = $ebook['embedded'];
                            $companyEbook['CompanyEbook'][$eCount]['description'] = $ebook['description'];
                            $companyEbook['CompanyEbook'][$eCount]['company_id'] = (!empty($companyId)) ? $companyId : null;
                            if (!empty($ebook['id'])) {
                                $companyEbook['CompanyEbook'][$eCount]['id'] = $ebook['id'];
                            }
                        } else {
                            unset($ebook);
                            unset($this->request->data['CompanyEbook'][$eCount]);
                        }
                        $eCount++;
                    }

                    $save['TempCompany']['ebooks'] = serialize($companyEbook);
                } else {
                    $save['TempCompany']['ebooks'] = null;
                }

                $CompanyPodcast = [];
                $podcasts = (!empty($this->data['CompanyPodcast'])) ? array_values($this->data['CompanyPodcast']) : [];
                unset($save['CompanyPodcast']);
                $CompanyPodcast = [];
                if (!empty($podcasts)) {
                    $podCount = 0;
                    foreach ($podcasts as $nr_doc_key => $podcast) {
                        if (!empty($podcast['embedded'])) {
                            $CompanyPodcast['CompanyPodcast'][$podCount]['embedded'] = $podcast['embedded'];
                            $CompanyPodcast['CompanyPodcast'][$podCount]['description'] = $podcast['description'];
                            $CompanyPodcast['CompanyPodcast'][$podCount]['company_id'] = (!empty($companyId)) ? $companyId : null;
                            if (!empty($podcast['id'])) {
                                $CompanyPodcast['CompanyPodcast'][$podCount]['id'] = $podcast['id'];
                            }
                        } else {
                            unset($podcast);
                            unset($this->request->data['CompanyPodcast'][$podCount]);
                        }
                        $podCount++;
                    }

                    $save['TempCompany']['podcasts'] = serialize($CompanyPodcast);
                } else {
                    $save['TempCompany']['podcasts'] = null;
                }

                $this->StaffUser->set($this->request->data['StaffUser']);
                $this->Company->set($this->request->data['TempCompany']);
                if ($this->StaffUser->validates() && $this->Company->validates()) {
                    $save['TempCompany']['newsroom_created_by'] = "admin";
                    $save['TempCompany']['approved_by'] = "1";
                    $save['TempCompany']['status'] = "1";
                    $tempData = $this->TempCompany->save($save['TempCompany']);
                    if (!empty($tempData)) {
                        if (empty($companyId)) {
                            $companyId = $tempData['TempCompany']['id'];
                        }
                        $this->redirect(array('controller' => 'newsrooms', 'action' => 'preview', $companyId));

                        //     $staffUserSvData['Company']['Company']=$companyId; 
                        //     $staffUserSvData['StaffUser']=$save['StaffUser']; 
                        //    if($this->StaffUser->saveAll($staffUserSvData,array("deep"=>true))){
                        //     $this->redirect(array('controller' => 'newsrooms', 'action' => 'preview',$companyId));

                        //         //$userId=$this->StaffUser->getLastInsertID();
                        //         //$this->request->data['Company']['staff_user_id']=$user_id; 
                        //         //$this->save_transactions($user_id,$company_id,$this->request->data['StaffUser']);
                        //         //$this->sendmail_aftersignup($this->request->data['StaffUser']);
                        //    }
                    }
                } else {
                    $errors = $this->StaffUser->validationErrors;
                    if (empty($errors)) {
                        $errors = $this->TempCompany->validationErrors;
                    }
                    $this->Session->setFlash(__('There is some error in below form. Please see red message.'), 'error');
                }
            } else {
                $this->request->data = $this->TempCompany->read(null, $companyId);

                $this->request->data['StaffUser'] = $data['StaffUser'] = (!empty($this->data["TempCompany"]['staff_user_data'])) ? unserialize($this->data["TempCompany"]['staff_user_data']) : [];
                $nr_docfiles = (!empty($this->data["TempCompany"]['extra_document'])) ? unserialize($this->data["TempCompany"]['extra_document']) : [];
                $nr_docfiles = (!empty($nr_docfiles['CompanyDocument'])) ? $nr_docfiles['CompanyDocument'] : [];

                $savedPresentation = (!empty($this->data["TempCompany"]['presentation'])) ? unserialize($this->data["TempCompany"]['presentation']) : [];
                $this->request->data['CompanyPresentation'] = (!empty($savedPresentation)) ? $savedPresentation['CompanyPresentation'] : [];

                $savedPodcast = (!empty($this->data["TempCompany"]['podcasts'])) ? unserialize($this->data["TempCompany"]['podcasts']) : [];
                $this->request->data['CompanyPodcast'] = (!empty($savedPodcast)) ? $savedPodcast['CompanyPodcast'] : [];

                $savedEbook = (!empty($this->data["TempCompany"]['ebooks'])) ? unserialize($this->data["TempCompany"]['ebooks']) : [];
                $this->request->data['CompanyEbook'] = (!empty($savedEbook)) ? $savedEbook['CompanyEbook'] : [];

                /*
                    $data=$this->TempCompany->find('first',['conditions'=>['TempCompany.company_id'=>$tempId],'order'=>'TempCompany.id DESC']); 
                    $this->request->data=$data;
                    
                    
                    // pr($nr_docfiles);die;
                    $data['CompanyDocument']=(!empty($nr_docfiles['CompanyDocument']))?$nr_docfiles['CompanyDocument']:[];
                */
            }
            $img =  $this->request->data['StaffUser'] ;
            $country_list = $this->Custom->getCountryList();
            $organization_list = $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1)));
            $this->set(compact('country_list', 'organization_list', 'nr_docfiles', 'companyId', 'data', 'img'));
        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'create_newsroom'));
            exit;
        }
    }

    public function preview($companyId = '', $returnType = "published", $newsroomFilter = 'prnews', $currentpage = '1')
    {
        $isFullwidth = "";
        $media_array = $prarray = [];
        $model = "TempCompany";
        if (!empty($companyId)) {
            $prarray = $conditions = array();
            $this->$model->bindModel(array('belongsTo' => array('OrganizationType', 'Country')));
            if (!empty($this->data) && $this->request->is("post")) {
                $this->Company->bindModel(array('hasMany' => array('CompanyDocument')));

                $data = $this->$model->find("first", array('conditions' => array("$model.id" => $companyId)));
                $svTempId = $data["TempCompany"]['id'];
                $tempUserData = unserialize($data["TempCompany"]['staff_user_data']);
                unset($data["TempCompany"]['id']);
                unset($data["TempCompany"]['staff_user_data']);
                unset($data["TempCompany"]['company_id']);
                $saveData['Company'] = $data["TempCompany"];
                // $saveData['Company']['id']=$companyId;  For new newsroom no company id
                if (!empty($data["TempCompany"]['extra_document'])) {
                    $extraDocument = unserialize($data["TempCompany"]['extra_document']);
                    if (!empty($extraDocument)) {
                        $saveData['CompanyDocument'] = $extraDocument['CompanyDocument'];
                    }
                }

                if (!empty($data["TempCompany"]['presentation'])) {
                    $presentation = unserialize($data["TempCompany"]['presentation']);
                    if (!empty($presentation)) {
                        $saveData['CompanyPresentation'] = $presentation['CompanyPresentation'];
                    }
                }

                if (!empty($data["TempCompany"]['presentation'])) {
                    $presentation = unserialize($data["TempCompany"]['presentation']);
                    if (!empty($presentation)) {
                        $saveData['CompanyPresentation'] = $presentation['CompanyPresentation'];
                    }
                }

                if (!empty($data["TempCompany"]['podcasts'])) {
                    $podcasts = unserialize($data["TempCompany"]['podcasts']);
                    if (!empty($podcasts)) {
                        $saveData['CompanyPodcast'] = $podcasts['CompanyPodcast'];
                    }
                }

                if (!empty($data["TempCompany"]['ebooks'])) {
                    $ebooks = unserialize($data["TempCompany"]['ebooks']);
                    if (!empty($ebooks)) {
                        $saveData['CompanyEbook'] = $ebooks['CompanyEbook'];
                    }
                }
unset($data["TempCompany"]['extra_document']);
unset($data["TempCompany"]['presentation']);
unset($data["TempCompany"]['ebooks']);
unset($data["TempCompany"]['podcasts']);

// Example of ensuring that no array data is passed
foreach ($tempUserData as $key => $value) {
    if (is_array($value)) {
        // Assuming you want to store arrays as JSON strings
        $tempUserData[$key] = json_encode($value);
    }
}

$saveUserData['StaffUser'] = $tempUserData;
$userSavedData = $this->StaffUser->saveAll($saveUserData, ["deep" => true]);


                if ($userSavedData) {
                    $userId = (!empty($saveUserData['StaffUser']['id'])) ? $saveUserData['StaffUser']['id'] : $this->StaffUser->getLastInsertID();
                    $saveData['Company']['staff_user_id'] = $userId;
                    if ($this->Company->saveAll($saveData, array("deep" => true))) {
                        $lastInsertedId = $this->Company->getLastInsertID();

                        // $saveUserData['Company']['Company']=$lastInsertedId;
                        // $saveUserData['StaffUser']['email']=strtolower($tempUserData['StaffUser']['email']);


                        $this->TempCompany->id = $svTempId;
                        $this->TempCompany->delete();
                    }
                }
                $this->Session->setFlash(__('Detail successfully updated'), 'success');
                $this->redirect(array('action' => $returnType));
                exit;
            }

            $data = $this->$model->find("first", array('conditions' => array("$model.id" => $companyId)));
            $data['Company'] = (!empty($data["TempCompany"])) ? $data["TempCompany"] : [];
            $doc_data = (!empty($data["TempCompany"]['extra_document'])) ? unserialize($data["TempCompany"]['extra_document']) : [];

            $presentationData = (!empty($data["TempCompany"]['presentation'])) ? unserialize($data["TempCompany"]['presentation']) : [];

            $ebookData = (!empty($data["TempCompany"]['ebooks'])) ? unserialize($data["TempCompany"]['ebooks']) : [];

            $podData = (!empty($data["TempCompany"]['podcasts'])) ? unserialize($data["TempCompany"]['podcasts']) : [];

            $this->set('title_for_layout', ucfirst($data[$model]['name']));


            if ($newsroomFilter == 'prnews') { // No PR for new newsroom

            } else if ($newsroomFilter == 'social') {
                require APP . 'Vendor' . DS . 'tumblr' . DS . 'tumblr.php';
                $obj = new tumblrFeed();
                if (!empty($data[$model]['tumblr'])) {
                    $blogName = str_replace(array("http:", "/"), array("", ""), $data[$model]['tumblr']);
                    $tumblrData = $obj->fetchfeeds($blogName, '10');
                    $this->set('tumblrData', $tumblrData);
                }
            }

            // if ($newsroomFilter == 'companyassets') {
            //     //$this->newsroom_assets($data["Company"]['id'],$data['StaffUser']['id'],$currentpage,$data["Company"]['slug']);
            //     // $doc_data = $this->CompanyDocument->find('all',array('conditions'=>array('company_id'=>$data[$model]['id'])));
            // }
            $doc_files = $doc_video = $doc_files = $doc_image = "";
            $this->set(compact('data', 'prarray', 'newsroomFilter', 'returnType', "doc_files", "doc_video", "doc_files", "doc_image", "isFullwidth", "companyId", 'doc_data', 'model', "media_array", "presentationData", "podData", "ebookData"));
        }
    }


    /*
    public function create_newsroom() {  
        $this->set('title_for_layout', 'Create a Newsroom');  
        $nr_company_id = $this->Cookie->read('company_id');
        if(!empty($nr_company_id)){
            $this->removeNewsroomCookie();
        }
        $nr_docfiles = $this->Cookie->read('nr_docfiles');
        if (($this->request->is("post") || $this->request->is('put')) && !empty($this->data)){
            $this->StaffUser->set($this->request->data); 
            if ($this->StaffUser->validates() && $this->Company->validates()) {
                    $date = date('Y').DS.date('m'); 
                    if(isset($this->data['Company']['docfile']) && !empty($this->data['Company']['docfile'])){
                        $docfiles = [];
                        $doc_file_array = $this->data['Company']['docfile'];
                        foreach ($doc_file_array as $doc_key => $doc_value) {
                            if(isset($nr_docfiles[$doc_key]) && !empty($nr_docfiles[$doc_key])){
                                if(!in_array($nr_docfiles[$doc_key]['name'], $doc_value['name'])){
                                    unset($nr_docfiles[$doc_key]);
                                    $nr_docfiles[$doc_key] = $doc_file_array[$doc_key];
                                }
                            }else{
                                    $nr_docfiles[] = $doc_file_array[$doc_key];
                            }
                        }
                        $this->Cookie->write('nr_docfiles',$nr_docfiles, false);
                        $this->data['Company']['docfile'] = $nr_docfiles;
                        $nr_docfiles = $nr_docfiles;
                    }else{
                        $count_files = count($this->data['Company']['docfilescount']);
                        $nr_docfiles = array_slice($nr_docfiles,0,$count_files);
                        $this->Cookie->write('nr_docfiles',$nr_docfiles, false);
                        $this->data['Company']['docfile'] = $nr_docfiles;
                        $nr_docfiles = $nr_docfiles;
                    }
                    foreach ($nr_docfiles as $nr_doc_key => $nr_doc_value) {
                        $dirFile = ROOT.DS."app".DS.'webroot'.DS.'files'.DS.'company' . DS . 'docfile' . DS . $date;
                        $dir = new Folder($dirFile, true, 0755); 
                        $this->Custom->document_upload($nr_doc_value['name'],$nr_doc_value['tmp_name'],$dirFile);
                     } 
                    unset($this->request->data['StaffUser']['profile_image']);  
                    unset($this->request->data['Company']['logo']); 
                    unset($this->request->data['Company']['banner_image']); 
                    $description = strip_tags($this->request->data['Company']['description'], '<p><a><ul><ol><li><b><strong><br>');
                    $this->Session->write('nr_description',$description);
                    $this->Session->write('nr_about_us',$this->request->data['Company']['hear_about_us']);
                    $this->Session->write('nr_address',$this->request->data['Company']['address']);
                    $this->redirect(array('controller' => 'newsrooms', 'action' => 'preview'));

            }else {

                    $errors = $this->StaffUser->validationErrors;

                if(empty($errors))

                    $errors =$this->Company->validationErrors;



                $this->Session->setFlash(__('There is some error in below form. Please see red message.'), 'error');

            }

        }

        $this->readNewsroomCookie();

        $this->set('nr_docfiles',$nr_docfiles);

        $this->set('organization_list', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1))));

        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));

    }
    
    public function preview($newsroomFilter = '') { 
        $this->set('title_for_layout', 'Create a Newsroom');
        $this->readNewsroomCookie();
        $nr_docfiles = $this->Cookie->read('nr_docfiles');
        if (($this->request->is("post") || $this->request->is('put'))&&!empty($this->data)){
            $this->loadModel('CompanyDocument');
            $this->readNewsroomCookie();
            $this->request->data['StaffUser']['status'] = 1;
            $this->request->data['StaffUser']['pr_plan_paid'] = 1;
            $this->request->data['Company']['newsroom_created_by'] ="admin";
            $this->request->data['Company']['payment_status'] ="1";
            $this->request->data['Company']['status'] ="1";
            $this->request->data['Company'] = array_filter($this->request->data['Company']);
            if($this->Company->save($this->request->data['Company'])){
                $company_id= $this->Company->getLastInsertID(); 
                $nr_docfiles = $this->Cookie->read('nr_docfiles');
                $CompanyDocument_data = [];
                foreach ($nr_docfiles as $doc_key => $doc_value) {
                    $doc_path = date('Y').DS.date('m');
                    $CompanyDocument_data[] = array('company_id'=>$company_id,'file_name'=>$doc_value['name'],'file_path'=>$doc_path);
                }
                $this->CompanyDocument->saveAll($CompanyDocument_data);
                unset($this->request->data['Company']);
                $this->request->data['Company']['Company']=$company_id; 
               if($this->StaffUser->saveAll($this->request->data,array("deep"=>true))){
                $user_id=$this->StaffUser->getLastInsertID();
                $this->request->data['Company']['staff_user_id']=$user_id; 
                $this->save_transactions($user_id,$company_id,$this->request->data['StaffUser']);
                $this->sendmail_aftersignup($this->request->data['StaffUser']);
               }
             }  
        }
        if(isset($newsroomFilter) && !empty($newsroomFilter)){
            $this->set('newsroomFilter', $newsroomFilter);
        }
        $this->readNewsroomCookie(); 
        $this->set('nr_docfiles', $nr_docfiles);
        $this->set('organization_list', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1))));
        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));
    }
    */  
}
