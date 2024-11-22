<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Inflector', 'Utility');
App::uses('Xml', 'Utility');

App::import('Vendor', 'Stripe', array('file' => 'stripe-php/init.php'));
use Stripe\Stripe;
use Stripe\Checkout\Session;


use Dompdf\Dompdf;
use Dompdf\Options;

class UsersController extends AppController
{
    public $name = 'Users';
    public $helpers = array('Cache');
    public $components = array('Cookie', 'AWSSES','Crm');
    //public $components = array('Cookie', 'AWSSES', 'Stripe');
    public $uses = array('StaffUser', 'EmailTemplate', 'Company','TempCompany','OrganizationType', 'Transaction', 'Country', 'PressRelease', "Category", "Msa", "TakeOverCompany", "CompaniesStaffUser", 'Cart', 'Coupon', 'Plan', "List", 'Campaign', 'ClippingReport', 'Subscriber', 'PressImage', 'RemainingUserPlan', 'Invoice', 'NewsletterLog', 'ClickThroughClient', 'NwRelationships', "CompanyPresentation",'CompanyPodcast','CompanyEbook');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('controller', 'users');
        $this->set('model', 'StaffUser');
        $this->Auth->allow('forgot', 'logout', 'check_login', 'forgot_password', 'reset', 'signup', 'activate', 'create_newsroom', 'create_newsroom_preview', 'edit_newsroom', 'edit_preview', 'become_subscriber', 'paymentsuccess', 'paymentprsuccess', 'paymentcancel', 'unsubscribe_newsletter', 'support', 'ticket_success');
        $this->set('currencySymbol', Configure::read('Site.currency'));
    }

    public function dashboard()
    {
        $this->layout = "dashboard";
        $this->set('title_for_layout', 'Dashboard');
        $email = $this->Auth->user('email');
        $user_id = $this->Auth->user('id');
        if ($this->Auth->user('staff_role_id') == 1 || $this->Auth->user('staff_role_id') == 2) {
            $this->redirect('/admin/staffUsers/dashboard');
        }
        if ($this->Auth->user('email_confirmed') == 0) {
            $this->set('user_email_status', $this->StaffUser->find('count', array('conditions' => array('StaffUser.id' => $user_id, 'StaffUser.email_confirmed' => 0))));
        }
        if ($this->Session->check('ClientUser.signup')) {
            $this->Session->delete('ClientUser.signup');
        }

         $checkStatus = $this->StaffUser->find('count', array('conditions' => array('StaffUser.id' => $user_id, 'status' => 2)));
        if ($checkStatus){
            $this->Session->setFlash("your account has been suspended. Please contact to admin.", 'error'); 
           
        }
        $companies_columns = $this->Company->find('first', array('fields'=>['organization_type_id','country_id'],
            'conditions' => array('Company.staff_user_id' => $user_id, "Company.status" => "0")));
        

            if ($companies_columns) {
                $check_incomplete_newsroom = $companies_columns['Company'];
            
                if ($check_incomplete_newsroom['organization_type_id'] == 0) {
                    $this->Session->setFlash("Your newsroom is incomplete, please proceed to ", "session_flash_link", array("link_text" => "complete your newsroom", "link_url" => array("controller" => "users", "action" => "newsrooms", "0", "admin" => false)));
                } elseif ($check_incomplete_newsroom['country_id'] == '0') {
                    $this->Session->setFlash("Your newsroom is incomplete, please proceed to ", "session_flash_link", array("link_text" => "complete your newsroom", "link_url" => array("controller" => "users", "action" => "newsrooms", "0", "admin" => false)));
                } elseif (in_array("", $check_incomplete_newsroom)) {
                    $this->Session->setFlash("Your newsroom is incomplete, please proceed to ", "session_flash_link", array("link_text" => "complete your newsroom", "link_url" => array("controller" => "users", "action" => "newsrooms", "0", "admin" => false)));
                }
            }

        $companyStatus = $this->Company->find('count', array('conditions' => array('Company.staff_user_id' => $user_id, "Company.status" => "0") ));
        if ($companyStatus > 0) {

            $this->Session->setFlash("Your newsroom currently under review by admin. Newsroom will not be public before approval, click here to check your ", "session_flash_link", array(
                "link_text" => "Pending Newsroom",

                "link_url" => array(
                    "controller" => "users",
                    "action" => "newsrooms", "pending",
                    "admin" => false
                )
            ));

            //$this->Session->setFlash('Your account currently under review. News room will not be public before approval.<a href="'.SITEURL.'users/companies/0">Pending companies</a> ', 'warning');
        }
 

        if ($this->Auth->user('staff_role_id') == 3) {
            $this->client_dashboard($user_id);
        } else if ($this->Auth->user('staff_role_id') == 4) {
            $this->subscriber_dashboard($user_id);
        } else {
            $this->logout();
        }
    }
    public function unsubscribe_newsletter($token = null)
    {

        try {
            if ($token) {
                $uid = $this->Custom->decryptSubscriberToken($token);
                $this->StaffUser->id = $uid;
                if (!$this->StaffUser->exists()) {
                    throw new NotFoundException(__('Invalid user'));
                }
                $this->StaffUser->saveField("newsletter_subscription", "0");
                $resubscribeUrl = $this->Custom->genrateSubscriberToken("resubscribe", $uid);
                $this->set("resubscribeUrl", $resubscribeUrl);
            } else {

                $this->redirect(SITEURL);
            }
        } catch (Exception $exc) {

            //echo $exc->getMessage();

        }
    }

    public function resubscribe($token = null)
    {
        try {

            if ($token) {

                $uid = $this->Custom->decryptSubscriberToken($token);

                $this->StaffUser->id = $uid;

                if (!$this->StaffUser->exists()) {

                    throw new NotFoundException(__('Invalid user'));
                }

                $saveData['StaffUser']['id'] = $uid;

                $saveData['StaffUser']['newsletter_send_mail_date'] = $this->Custom->get_newsletter_sendmail_date($uid);

                $saveData['StaffUser']['newsletter_subscription'] = 1;

                $this->StaffUser->saveField($saveData);

                // $resubscribeUrl=$this->Custom->genrateSubscriberToken("resubscribe",$uid);

            } else {

                $this->redirect(SITEURL);
            }
        } catch (Exception $exc) {

            echo $exc->getMessage();
        }
    }

    private function subscriber_dashboard($user_id)
    {

        $this->update_newsletter_details("yes");
    }

    public function update_newsletter_details($redriectTodash = "")
    {

        if ($redriectTodash == "") {

            $this->set('title_for_layout', "Update newsletter details");
        }

        $user_id = $this->Auth->user('id');

        if (!empty($this->data)) {

            if (empty($this->data['Msa']) && empty($this->data['Category'])) {

                $this->Session->setFlash(__('Please select at least one Region or Industry.'), 'error');

                if ($redriectTodash == "yes") {

                    $this->redirect(array('action' => 'dashboard'));
                }
            }

            $this->request->data['StaffUser']['newsletter_send_mail_date'] = $this->Custom->get_newsletter_sendmail_date($user_id);

            if ($this->StaffUser->saveAll($this->request->data, array('deep' => true))) {

                $this->Session->write('Auth.User.newsletter_subscription', '1');

                $this->Session->setFlash(__('Detail successfully updated.'), 'success');
            }
        }

        $pCategory_list = $this->Category->find('list', array('conditions' => array('is_deleted' => 0, 'status' => 1, 'parent_id' => 0)), array('order' => 'name'));

        $categories = [];

        if ($pCategory_list) {

            foreach ($pCategory_list as $cpid => $pCatname) {

                $categories[$pCatname] = [];

                $category_list = $this->Category->find('list', array('conditions' => array('is_deleted' => 0, 'status' => 1, 'parent_id' => $cpid)), array('order' => 'name'));

                foreach ($category_list as $cId => $category) {

                    $categories[$pCatname][$cId] = $category;
                }
            }
        }

        $this->Msa->recursive = -1;

        $msa_list = $this->Msa->find('list', array(
            'joins' => array(
                array(
                    'table' => 'msas_press_releases',
                    'alias' => 'MsaPressRelease',
                    'type' => 'INNER',
                    'conditions' => array(
                        'MsaPressRelease.msa_id = Msa.id'
                    )
                ),
                array(
                    'table' => 'press_releases',
                    'alias' => 'PressRelease',
                    'type' => 'INNER',
                    'conditions' => array(
                        'PressRelease.id = MsaPressRelease.press_release_id AND PressRelease.status=1'
                    )
                ),
            ),
            'conditions' => array('Msa.status' => 1), 'fields' => array('id', 'name'), 'order' => "name ASC"
        ));

        $newsletter_subscription = $this->Auth->user('newsletter_subscription');

        $email_confirmed = $this->Auth->user('email_confirmed');

        $this->request->data = $this->StaffUser->read(null, $user_id);



        $this->set(compact("categories", "msa_list", "user_id", 'newsletter_subscription', 'email_confirmed'));
    }

    private function client_dashboard($user_id)
    {

        // $transactions = $this->Invoice->find('count', array('conditions' => array("Invoice.staff_user_id" => $user_id, 'Invoice.tx_id !=' => null), 'limit' => "5", 'order' => 'Invoice.id DESC'));
        $invoiceCount = $this->Invoice->find('count', array('conditions' => array("Invoice.staff_user_id" => $user_id, 'Invoice.tx_id !=' => null), 'limit' => "5", 'order' => 'Invoice.id DESC'));
        $approveCount = $this->PressRelease->find('count', array("conditions" => array('PressRelease.status' => "1", "PressRelease.staff_user_id" => $user_id)));
        $pendingCount = $this->PressRelease->find('count', array("conditions" => array('PressRelease.status' => "0", "PressRelease.staff_user_id" => $user_id)));
        $draftCount = $this->PressRelease->find('count', array("conditions" => array('PressRelease.status' => "3", "PressRelease.staff_user_id" => $user_id)));
        $deninedCount = $this->PressRelease->find('count', array("conditions" => array('PressRelease.status' => "4", "PressRelease.staff_user_id" => $user_id)));
        $embargoCount = $this->PressRelease->find('count', array("conditions" => array('PressRelease.status' => "2", "PressRelease.staff_user_id" => $user_id)));
        //$this->PressRelease->recursive = '-1';
        // $approved_pr = $this->PressRelease->find("all", array('conditions' => array('PressRelease.status' => '1', 'PressRelease.staff_user_id' => $user_id), 'fields' => array('PressRelease.id', 'PressRelease.title', 'PressRelease.summary', 'PressRelease.plan_id'), 'order' => 'PressRelease.id DESC', 'limit' => '5'));

        $data_array = $this->press_release('approved', '5', 'yes');

        $this->set(compact('invoiceCount', 'approveCount', 'pendingCount', 'draftCount', 'deninedCount', 'embargoCount', 'data_array'));
    }

    public function activated_plans()
    {

        $this->set('title_for_layout', __('Activated PR plans'));

        $user_id = $this->Auth->user('id');

        $userDetail = $this->StaffUser->find('first', array('conditions' => array('StaffUser.id' => $user_id)));

        $currencySymbol = Configure::read('Site.currency');
        $plan_list = [];
        $plan_list = $this->RemainingUserPlan->find('all', array(
            'joins' => array(
                array(
                    'table' => 'plans',
                    'alias' => 'Plan',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Plan.id = RemainingUserPlan.plan_id'
                    )
                ),
                array(
                    'table' => 'plan_categories',
                    'alias' => 'PlanCategory',
                    'type' => 'INNER',
                    'conditions' => array(
                        'PlanCategory.id = Plan.plan_category_id'
                    )
                )
            ),
            'conditions' => array('RemainingUserPlan.staff_user_id' => $user_id, 'RemainingUserPlan.number_pr > ' => "0"),
            'fields' => array('PlanCategory.name', 'PlanCategory.slug', 'PlanCategory.word_limit', 'Plan.id', 'Plan.price', 'Plan.plan_type', 'Plan.bulk_discount_amount', 'Plan.number_pr', 'RemainingUserPlan.assign_from', 'RemainingUserPlan.number_pr')
        ));

        $this->set(compact('plan_list', 'userDetail', 'currencySymbol'));

        /* old code remove by hitesh

          // $this->PlanCategory->virtualFields['name'] = 'CASE WHEN word_limit >0 THEN CONCAT(PlanCategory.name, " - ",PlanCategory.word_limit," words") ELSE PlanCategory.name END';

        // $plancat_list = $this->PlanCategory->find('list', array('fields' => array('id', 'name'), 'conditions' => array('status' => 1)));

            if ($plancat_list) {

            $this->Plan->recursive = -1;

            foreach ($plancat_list as $pcId => $planCatname) {

                $plan_list[$planCatname] = [];

                $getplan = $this->Plan->find('all', array(

                    'joins' => array(

                        array(

                            'table' => 'remaining_user_plans',

                            'alias' => 'RemainingUserPlan',

                            'type' => 'INNER',

                            'conditions' => array(

                                'RemainingUserPlan.plan_id = Plan.id'

                            )

                        )

                    ),

                    'fields' => array('id', 'price', 'bulk_discount_amount', 'number_pr', 'RemainingUserPlan.assign_from', 'RemainingUserPlan.number_pr'), 'conditions' => array('Plan.plan_category_id' => $pcId, 'RemainingUserPlan.staff_user_id' => $user_id, 'RemainingUserPlan.number_pr !=' => "0")
                ));

                // $getplan=Set::extract('/Plan/.', $getplan);

                if (!empty($getplan)) {

                    foreach ($getplan as $index => $plan) {

                        $price = ($plan['Plan']['bulk_discount_amount'] > 0) ? $currencySymbol . $plan['Plan']['bulk_discount_amount'] : $currencySymbol . $plan['Plan']['price'];

                        $number_pr = ($plan['Plan']['number_pr'] > 0) ? $plan['Plan']['number_pr'] : $plan['number_pr'];

                        $categoryname = preg_replace('/\d+/', '', str_replace(array("-", "words"), array("", ""), $planCatname));

                        $assignedFrom = ($plan['RemainingUserPlan']['assign_from'] != "frontend") ? "Assigned By : " . ucFirst($plan['RemainingUserPlan']['assign_from']) : "Purchased By : Client";



                        $plan_list[$planCatname][$plan['Plan']['id']] = "<div class='col-sm-7'>" . trim($categoryname) . " - $price (" . ($number_pr) . ")</div><div class='col-sm-2'><span class='mr-2'>(" . $plan['RemainingUserPlan']['number_pr'] . ")</span></div> <div class='pull-right col-sm-3'>$assignedFrom</div>";
                    }
                } else {

                    unset($plan_list[$planCatname]);
                }
            }
        }
        */
    }

    public function newsrooms($slugType = "approved")
    {

        switch ($slugType) {
            case 'approved':
                $title = 'Approved Newsrooms';
                $status = 1;
                break;
            case 'suspended':
                $title = 'Suspended Newsrooms';
                $status = 2;
                break;
            case 'disapproved':
                $title = 'Disapproved Newsrooms';
                $status = 3;
                break;
            case 'trashed':
                $status = 4;
                $title = 'Trash Newsrooms';
                break;
            default:
                $status = 0;
                $title = 'Pending Newsrooms';
                break;
        }

        $this->set('title_for_layout', $title);
        $user_id = $this->Auth->user('id');

        /*
        $data_array = $this->CompaniesStaffUser->find('all', array(
            'joins' => array(
                array(
                    'table' => 'companies',
                    'alias' => 'Company',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Company.id = CompaniesStaffUser.company_id'
                    )
                )
            ),
            'conditions' => array('CompaniesStaffUser.staff_user_id' => $user_id, "Company.status" => $status), 'fields' => array('Company.id', 'Company.name', 'Company.status', 'Company.logo', 'Company.logo_path', 'Company.contact_name', 'Company.slug', 'Company.payment_status')
        ));*/

        $data_array = $this->Company->find('all', array(
         
            'conditions' => array('Company.staff_user_id' => $user_id, "Company.status" => $status), 'fields' => array('Company.id', 'Company.name', 'Company.status', 'Company.logo', 'Company.logo_path', 'Company.contact_name', 'Company.slug', 'Company.payment_status'),
            'order'=>"Company.id DESC"
        ));
        $this->removeNewsroomCookie();

        $this->set(compact('data_array','slugType','title'));
    }

    public function newsroom_view($slug = '', $newsroomFilter = 'prnews',$returnType="pending",$currentpage = '1')
    {   
        try {
        $isFullwidth = "";
        $model = "Company";
            if (!$slug) {
                throw new NotFoundException('Invalid Newsroom.');
            } 
            $prarray = $conditions = array();
            $this->$model->bindModel(array('belongsTo' => array('OrganizationType', 'Country'))); 

            $this->request->data=$data = $this->$model->find("first", array('conditions' => array("$model.slug" => $slug)));
            if (empty($data)) {
                throw new NotFoundException('Invalid Newsroom.');
            } 
            
            $companyId=$data[$model]['id'];
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
            $this->set(compact('data', 'prarray', 'newsroomFilter', 'returnType', "doc_files", "doc_video", "doc_files", "doc_image", "isFullwidth", "companyId","slug"));
        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' =>"newsrooms",$returnType));
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
        $controller = 'newsroom_view';

        $action = $slug . '/companyassets';

        // if (!empty($media_array)) {

        //     foreach ($media_array as $key => $mediadata) {

        //         if (isset($mediadata['PI']['image_path']) && !empty($mediadata['PI']['image_path'])) {

        //             $imageUrl = SITEURL . 'files/company/press_image/' . $mediadata['PI']['image_path'] . '/' . $mediadata['PI']['image_name'];

        //            // $this->Qimage->resize(array('height' => $this->thumbHeight, 'width' => $this->thumbWidth, 'file' => $imageUrl, 'output' => WWW_ROOT . 'files/company/press_image/thumb/'));
        //         }
        //     }
        // }

        $this->set(compact('media_array', 'totalCount', 'totalpages', 'currentpage', 'action', 'controller'));
    }

    public function profile()
    {

        App::uses('File', 'Utility');

        $this->set('title_for_layout', 'User profile');

        $this->set('organization_list', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1))));

        $this->loadModel('Country');

        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));

        if (!empty($this->request->data)) {

            if ($this->data['StaffUser']['new_profile_image']['name'] != '') {

                $date = date('Y') . DS . date('m');

                $file_path = ROOT . DS . 'admin' . DS . 'webroot' . DS . 'files' . DS . 'profile_image';

                if (!empty($this->request->data['StaffUser']['profile_image'])) {

                    $delfile = new File($file_path . DS . $this->request->data['StaffUser']['profile_image'], false, 0777);

                    $delfile->delete();
                }

                $profileImg = uniqid() . ".png";

                if (move_uploaded_file($this->data['StaffUser']['new_profile_image']['tmp_name'], $file_path . DS . $profileImg)) {

                    $this->Session->delete('Auth.User.profile_image');

                    $this->Session->write('Auth.User.profile_image', $profileImg);

                    unset($this->request->data['StaffUser']['profile_image']);

                    $this->request->data['StaffUser']['profile_image'] = $profileImg;
                }
            }


            if ($this->request->data['StaffUser']['staff_role_id'] == 4 || $this->request->data['StaffUser']['staff_role_id'] == 3) {

                if ($this->StaffUser->save($this->request->data)) {

                    $this->Session->setFlash(__('Detail successfully updated'), 'success');

                    $this->Session->delete('Auth.User.first_name');

                    $this->Session->delete('Auth.User.last_name');

                    $this->Session->write('Auth.User.first_name', $this->data['StaffUser']['first_name']);

                    $this->Session->write('Auth.User.last_name', $this->data['StaffUser']['last_name']);

                    $this->redirect(array('action' => 'profile'));
                } else {

                    $this->Session->setFlash(__('Detail could not be changed'), 'error');
                }
            } else {

                if ($this->data['Company']['newlogo'] != '' && !empty($this->data['Company']['newlogo']['name'])) {

                    $date = date('Y') . DS . date('m');

                    $file_path = WWW_ROOT . 'files' . DS . 'company' . DS . 'logo' . DS;

                    $delfile = new File($file_path . $this->request->data['Company']['logo_path'] . DS . $this->request->data['Company']['logo'], false, 0777);

                    $delfile->delete();

                    unset($this->request->data['Company']['logo']);


                    $dir = new Folder($file_path, true, 0755);

                    $logo_name = uniqid() . ".png";

                    if (move_uploaded_file($this->data['Company']['newlogo']['tmp_name'], $file_path . $date . DS . $logo_name)) {

                        unset($this->request->data['Company']['newlogo']);

                        $this->request->data['Company']['logo_path'] = date('Y') . "/" . date('m');

                        $this->request->data['Company']['logo'] = $logo_name;
                    }
                }

                if ($this->data['Company']['newbanner_image'] != '' && !empty($this->data['Company']['newbanner_image']['name'])) {

                    $date = date('Y') . DS . date('m');

                    $file_path1 = WWW_ROOT . 'files' . DS . 'company' . DS . 'banner' . DS;

                    $delfile = new File($file_path1 . $this->request->data['Company']['banner_path'] . DS . $this->request->data['Company']['banner_image'], false, 0777);

                    $delfile->delete();

                    $dir = new Folder($file_path1, true, 0755);

                    $banner_image = uniqid() . ".png";

                    if (move_uploaded_file($this->data['Company']['newbanner_image']['tmp_name'], $file_path1 . $date . DS . $banner_image)) {

                        unset($this->request->data['Company']['banner_image']);

                        $this->request->data['Company']['banner_path'] = date('Y') . "/" . date('m');

                        $this->request->data['Company']['banner_image'] = $banner_image;
                    }
                }

                unset($this->request->data['StaffUser']['new_profile_image']);

                unset($this->request->data['Company']['newlogo']);

                unset($this->request->data['Company']['newbanner_image']);


                if ($this->StaffUser->saveAssociated($this->request->data, array('deep' => true))) {

                    $this->Session->delete('Auth.User.first_name');

                    $this->Session->delete('Auth.User.last_name');

                    $this->Session->write('Auth.User.first_name', $this->data['StaffUser']['first_name']);

                    $this->Session->write('Auth.User.last_name', $this->data['StaffUser']['last_name']);

                    $this->Session->setFlash(__('Detail successfully updated'), 'success');

                    $this->redirect(array('action' => 'profile'));
                } else {

                    $this->Session->setFlash(__('Detail could not be changed'), 'error');
                }
            }
        }

        $this->request->data = $this->StaffUser->read(null, $this->Auth->user('id'));
    }
    public function login()
    {
        App::uses('CakeEmail', 'Network/Email');
        $redirect = (!empty($this->request->query('r'))) ? $this->request->query('r') : array('action' => 'dashboard');
        $this->layout = 'login';
        $this->set('title_for_layout', 'Login');

        if ($this->Auth->login()) {
            // if(!$this->request->data['StaffUser']['otp']){
            //     $otp = rand(100000, 999999);
                
            //     $Email = new CakeEmail('default');
            //     $Email->to($this->request->data['StaffUser']['email'])
            //           ->replyTo('emailwireweb@gmail.com')
            //           ->subject('Your OTP Code')
            //           ->send("Your OTP code is: $otp");
            //     $this->Session->write('OTP', $otp);
            //     $otpGeneratedTime = time();
            //     $this->Session->write('OTP_TIME', $otpGeneratedTime);
            //     $this->layout = 'otp';
            //     return;
            // }
            // $otpGeneratedTime = $this->Session->read('OTP_TIME');
            // $currentTime = time();
            // if ($currentTime - $otpGeneratedTime > 60) {
            //     $this->Session->delete('OTP');
            //     $this->Session->delete('OTP_TIME');
            //     $this->Session->setFlash('OTP has expired. Please request a new OTP.', 'error');
            //     return $this->redirect(array('action' => 'login'));
            // }
            // $sessionOtp = $this->Session->read('OTP');
            // if ($sessionOtp != $this->request->data['StaffUser']['otp']) {
            //     $this->Session->setFlash('Invalid OTP. Please try again.', 'error');
            //     $this->layout = 'otp';
            //     return;
            // }
            // $this->Session->delete('OTP');
            // $this->Session->delete('OTP_TIME');
            $this->removeNewsroomCookie();
            $is_plan_paid = $this->Auth->user('pr_plan_paid');

            if (isset($is_plan_paid) && $is_plan_paid == 1) {
                $this->redirect($redirect);
            } else {
                $this->redirect('/plans/online-distribution/');
            }
        }

        if ($this->request->is('post')) {
            if(!$this->request->data['StaffUser']['otp']){
                $otp = rand(100000, 999999);
                
                $Email = new CakeEmail('default');
                $Email->to($this->request->data['StaffUser']['email'])
                      ->replyTo('emailwireweb@gmail.com')
                      ->subject('Your OTP Code')
                      ->send("Your OTP code is: $otp");
                $this->Session->write('OTP', $otp);
                $otpGeneratedTime = time();
                $this->Session->write('OTP_TIME', $otpGeneratedTime);
                $this->layout = 'otp';
                return;
            }
            $otpGeneratedTime = $this->Session->read('OTP_TIME');
            $currentTime = time();
            if ($currentTime - $otpGeneratedTime > 60) {
                $this->Session->delete('OTP');
                $this->Session->delete('OTP_TIME');
                $this->Session->setFlash('OTP has expired. Please request a new OTP.', 'error');
                return $this->redirect(array('action' => 'login'));
            }
            $sessionOtp = $this->Session->read('OTP');
            if ($sessionOtp != $this->request->data['StaffUser']['otp']) {
                $this->Session->setFlash('Invalid OTP. Please try again.', 'error');
                $this->layout = 'otp';
                return;
            }
            $this->Session->delete('OTP');
            $this->Session->delete('OTP_TIME');
            $this->Auth->authenticate['Form'] = array('scope' => array('StaffUser.status' => '1'));

            // echo AuthComponent::password($this->request->data['StaffUser']['password']); die;

            //$checEmail=$this->StaffUser->find('count',array('conditions'=>array('email_confirmed'=>"1")));

            if ($this->Auth->login()) {

                if (empty($this->request->data['StaffUser']['remember_me'])) {

                    $this->Cookie->delete('StaffUser');
                } else {

                    $cookie = array();

                    $cookie['email'] = $this->request->data['StaffUser']['email'];

                    $cookie['password'] = $this->request->data['StaffUser']['password'];

                    $cookie['remember_me'] = $this->request->data['StaffUser']['remember_me'];

                    $this->Cookie->write('StaffUser', $cookie, true);
                }

                if (AuthComponent::user()) {

                    $this->Session->setFlash('User login successfully', 'success');

                    $is_plan_paid = $this->Auth->user('pr_plan_paid');

                    if (isset($is_plan_paid) && $is_plan_paid == 1) {

                        return $this->redirect($redirect);
                    } else {

                        return $this->redirect('/plans/online-distribution/');
                    }
                }
            } else {

                $this->Session->setFlash(__($this->Auth->authError), 'error');

                //$this->redirect($this->Auth->loginAction);

            }
        }

        if ($this->Cookie->check('StaffUser')) {

            $this->request->data['StaffUser']['email'] = $this->Cookie->read('StaffUser.email');

            $this->request->data['StaffUser']['password'] = $this->Cookie->read('StaffUser.password');

            $this->request->data['StaffUser']['remember_me'] = $this->Cookie->read('StaffUser.remember_me');
        }
    }



    public function login_form()
    {

        $this->layout = 'ajax';

        $this->set('title_for_layout', 'Login');
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





/*

    public function create_newsroom()
    {
        $this->layout = 'site_default';
        $this->set('title_for_layout', 'Create a Newsroom');

        $nr_company_id = $this->Cookie->read('company_id');
        if (!empty($nr_company_id)) {
            $this->removeNewsroomCookie();
        } 
        $nr_docfiles = $this->Cookie->read('nr_docfiles');

        if (($this->request->is("post") || $this->request->is('put')) && !empty($this->data)) {

            $this->StaffUser->set($this->request->data);

            if ($this->StaffUser->validates() && $this->Company->validates()) {

                $date = date('Y') . DS . date('m');



                if (isset($this->data['Company']['docfile']) && !empty($this->data['Company']['docfile'])) {

                    $docfiles = [];

                    $doc_file_array = $this->data['Company']['docfile'];

                    foreach ($doc_file_array as $doc_key => $doc_value) {

                        if (isset($nr_docfiles[$doc_key]) && !empty($nr_docfiles[$doc_key])) {

                            if (!in_array($nr_docfiles[$doc_key]['name'], $doc_value['name'])) {

                                unset($nr_docfiles[$doc_key]);

                                $nr_docfiles[$doc_key] = $doc_file_array[$doc_key];
                            }
                        } else {

                            $nr_docfiles[] = $doc_file_array[$doc_key];
                        }
                    }

                    $this->Cookie->write('nr_docfiles', $nr_docfiles, false);

                    $this->request->data['Company']['docfile'] = $nr_docfiles;

                    $nr_docfiles = $nr_docfiles;
                } else {

                    $count_files = count($this->request->data['Company']['docfilescount']);

                    $nr_docfiles = array_slice($nr_docfiles, 0, $count_files);

                    $this->Cookie->write('nr_docfiles', $nr_docfiles, false);

                    $this->request->data['Company']['docfile'] = $nr_docfiles;

                    $nr_docfiles = $nr_docfiles;
                }

                foreach ($nr_docfiles as $nr_doc_key => $nr_doc_value) {

                    $dirFile = WWW_ROOT . 'files' . DS . 'company' . DS . 'docfile' . DS . $date;

                    $dir = new Folder($dirFile, true, 0755);

                    $this->Custom->document_upload($nr_doc_value['name'], $nr_doc_value['tmp_name'], $dirFile);
                }

                unset($this->request->data['StaffUser']['profile_image']);

                unset($this->request->data['Company']['logo']);

                unset($this->request->data['Company']['banner_image']);



                $description = strip_tags($this->request->data['Company']['description'], '<p><a><ul><ol><li><b><strong><br>');

                $this->Session->write('nr_description', $description);

                $this->Session->write('nr_about_us', $this->request->data['Company']['hear_about_us']);

                $this->Session->write('nr_address', $this->request->data['Company']['address']);

                if ($this->Auth->loggedIn()) {

                    $this->Cookie->write('nr_user_id', $this->Auth->user('id'));

                    $this->Cookie->write('nr_email', $this->Auth->user('emal'));

                    $this->Cookie->write('nr_first_name', $this->Auth->user('first_name'));

                    $this->Cookie->write('nr_last_name', $this->Auth->user('last_name'));
                }



                $this->redirect(array('controller' => 'users', 'action' => 'newsroom-preview'));
            } else {

                $errors = $this->StaffUser->validationErrors;

                if (empty($errors))

                    $errors = $this->Company->validationErrors;



                $this->Session->setFlash(__('There is some error in below form. Please see red message.'), 'error');
            }
        }

        $this->readNewsroomCookie();

        $this->set('nr_docfiles', $nr_docfiles);

        $this->set('organization_list', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1))));

        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));
    }



    public function create_newsroom_preview($newsroomFilter = '')
    {

        $this->layout = 'site_default';

        $this->set('title_for_layout', 'Create a Newsroom');

        $this->readNewsroomCookie();

        $nr_docfiles = $this->Cookie->read('nr_docfiles');

        if ($this->Auth->loggedIn()) {

            $user_id = $this->Auth->user('id');

            $condition[] = array("name like '%" . $this->request->data['Company']['name'] . "%'");

            $old_company_list = $this->Company->find('first', array('conditions' => $condition));

            if (!empty($old_company_list)) {

                if (isset($old_company_list['StaffUser']["0"]['id']) && $old_company_list['StaffUser']["0"]['id'] == $user_id) {

                    $this->Session->setFlash("Your newsroom is already created by you and it`s payment is pending., click here to check your ", "session_flash_link", array(

                        "link_text" => "Pending Newsroom",

                        "link_url" => array(

                            "controller" => "users",

                            "action" => "newsrooms", "0",

                            "admin" => false

                        )

                    ));

                    $this->removeNewsroomCookie();
                } else {

                    $this->Session->setFlash("This company is already listed with us., Click here to take over his newsroom", "session_flash_link", array(

                        "link_text" => "Take over Newsroom",

                        "link_url" => array(

                            "controller" => "users",

                            "action" => "take-over-publishing",

                            "admin" => false

                        )

                    ));
                }

                $this->redirect('/users/create-newsroom');
            }
        }



        if (($this->request->is("post") || $this->request->is('put')) && !empty($this->data)) {
            $this->loadModel('CompanyDocument');
            $this->readNewsroomCookie();
            $this->request->data['StaffUser']['status'] = 1;
            if ($this->Auth->loggedIn()) {
                $user_id = $this->request->data['StaffUser']['id'] = $this->Auth->user('id');
                unset($this->request->data['StaffUser']['email']);
                unset($this->request->data['StaffUser']['confirm_email']);
                unset($this->request->data['StaffUser']['password']);
                unset($this->request->data['StaffUser']['verify_password']);
                unset($this->request->data['StaffUser']['profile_image']);
                // $companyList = $this->CompaniesStaffUser->find('list', array('conditions' => array('CompaniesStaffUser.staff_user_id' => $user_id), 'fields' => array('company_id', 'company_id')));
                $companyList = $this->Company->find('list', array('conditions' => array('Company.staff_user_id' => $user_id), 'fields' => array('Company.id', 'Company.id')));
            } else {

                $this->Session->write('ClientUser.signup', 'frontend');
            }



            $this->request->data['Company'] = array_filter($this->request->data['Company']); // to remove empty elements in the array

            if ($this->StaffUser->saveAll($this->request->data, array("deep" => true))) {
                $user_id = $this->StaffUser->getLastInsertID();
                $this->request->data['Company']['staff_user_id']=$user_id;
                if ($this->Company->save($this->request->data['Company'])) {
                    $lstInsertId = $this->Company->getLastInsertID();
                    $nr_docfiles = $this->Cookie->read('nr_docfiles');
                    $CompanyDocument_data = [];
                    foreach ($nr_docfiles as $doc_key => $doc_value) {
                        $doc_path = date('Y') . DS . date('m');
                        $CompanyDocument_data[] = array('company_id' => $lstInsertId, 'file_name' => $doc_value['name'], 'file_path' => $doc_path);
                    }
                    $this->CompanyDocument->saveAll($CompanyDocument_data);
                    $this->Session->write('ClientUserSaved.company_id', $lstInsertId);
                    unset($this->request->data['Company']);
                if (isset($companyList)) {
                    $companyList = array_values($companyList);
                    $companyList[count($companyList)] = $lstInsertId;
                }

                // $this->request->data['Company']['Company'] = (isset($companyList)) ? $companyList : $lstInsertId;
                    if (!$this->Auth->loggedIn()) { 
                        $this->request->data['StaffUser']['id'] = $user_id;
                        $this->Auth->login($this->request->data['StaffUser']);
                    }
                    // create new session id update all plan with new session id because latest newsroom will payment will charge
                    $cart_session_id = Security::hash(CakeText::uuid(), 'sha1', true);
                    $_SESSION['cart_session_id'] = $cart_session_id;
                    $cartData = $this->Custom->fetchCartData($user_id);
                    if (!empty($cartData)) {
                        $this->Cart->query("UPDATE `carts` SET `cart_session_id` = '" . $cart_session_id . "' WHERE `carts`.`staff_user_id` = '$user_id' AND `carts`.`cart_type` ='plan' AND `carts`.`is_newsroom_incart` ='0'");
                    }
                    $saveCart['Cart']['staff_user_id'] = $user_id;
                    $saveCart['Cart']['plan_id'] = '';
                    $saveCart['Cart']['company_id'] = $lstInsertId;
                    $saveCart['Cart']['is_newsroom_incart'] = 1;
                    $saveCart['Cart']['cart_session_id'] = $cart_session_id;
                    $this->Cart->save($saveCart);
                    $is_plan_paid = $this->Auth->user('pr_plan_paid');
                    if (isset($is_plan_paid) && $is_plan_paid != 0) {
                        $this->Session->setFlash('You already have purchased a PR plan, still you can purchase extra plans for future PR submission or can move ahead with newsroom checkout.', 'success');
                    }
                    $this->removeNewsroomCookie();
                    $this->redirect('/plans/online-distribution/');
                }
            }
        }

        // if(isset($newsroomFilter) && !empty($newsroomFilter)){}
        $this->set('newsroomFilter', $newsroomFilter);
        $this->set('nr_docfiles', $nr_docfiles);

        $this->set('organization_list', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1))));

        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));
    }




    
    public function edit_newsroom($id = null)
    {
        $this->loadModel('CompanyDocument');
        if (!$id) {
            $this->redirect(array('controller' => 'users', 'action' => 'newsrooms', '0'));
       }
        $this->layout = 'site_default';
        $this->set('title_for_layout', 'Edit Newsroom');
        $user_id = $this->Auth->user('id');
        $have_newsroom = $this->CompaniesStaffUser->find('count', array('conditions' => array('CompaniesStaffUser.staff_user_id' => $user_id, 'CompaniesStaffUser.company_id' => $id)));
        if ($have_newsroom == 0) {
            $this->redirect('/notfound');
        }
        $condition[] = array("Company.id" => $id);
        $old_company_list = $this->Company->find('all', array('conditions' => $condition));
        $company_documents = $this->CompanyDocument->find('all', array('conditions' => array('company_id' => $id)));
        $docfiles = [];
        foreach ($company_documents as $doc_key => $doc_value) {
            $name = $doc_value['CompanyDocument']['file_name'];
            unset($doc_value['CompanyDocument']['file_name']);
            $doc_value['CompanyDocument']['name'] = $name;
            $docfiles[] = $doc_value['CompanyDocument'];
       }
        if (!empty($company_documents) && empty($this->Cookie->read('nr_docfiles'))) {
            $this->Cookie->write('nr_docfiles', $docfiles, false);
        } else {
            $this->Cookie->write('nr_docfiles', $this->Cookie->read('nr_docfiles'), false);
            $docfiles = $this->Cookie->read('nr_docfiles');
        }
        if (empty($this->Cookie->read('company_id'))){
            $company_data = $old_company_list[0]['Company'];
            $this->Cookie->write('company_id', $id);
        }

        if (!empty($company_data['media_contact_name'])){
            $this->Cookie->write('nr_media_contact_name', $company_data['media_contact_name']);
        }
        if (!empty($company_data['media_job_title'])){
            $this->Cookie->write('nr_media_job_title', $company_data['media_job_title']);
        }
        if (!empty($company_data['media_email'])){
            $this->Cookie->write('nr_media_email', $company_data['media_email']);
        }
        if (!empty($company_data['media_phone_number'])){
            $this->Cookie->write('nr_media_phone_number', $company_data['media_phone_number']);
        }
        if (!empty($company_data['contact_name'])){
            $this->Cookie->write('nr_contact_name', $company_data['contact_name']);
        }
        if (!empty($company_data['job_title'])){
            $this->Cookie->write('nr_job_title', $company_data['job_title']);
        }
        if (!empty($company_data['organization_type_id']))
            $this->Cookie->write('nr_org_typ_id', $company_data['organization_type_id']);
        if (!empty($company_data['name'])){
            $this->Cookie->write('nr_company_name', $company_data['name']);
        }
        if (!empty($company_data['phone_number'])){
            $this->Cookie->write('nr_phone_number', $company_data['phone_number']);
        }
        if (!empty($company_data['fax_number'])){
            $this->Cookie->write('nr_fax_number', $company_data['fax_number']);
        }
        if (!empty($company_data['address'])){
            $this->Session->write('nr_address', $company_data['address']);
        }
        // $this->Cookie->write('nr_address',$get_cookie);
        if (!empty($company_data['city'])){
            $this->Cookie->write('nr_city', $company_data['city']);
        }
        if (!empty($company_data['state'])){
            $this->Cookie->write('nr_state', $company_data['state']);
        }
        if (!empty($company_data['country_id'])){
            $this->Cookie->write('nr_country', $company_data['country_id']);
        }
        if (!empty($company_data['zip_code'])){
            $this->Cookie->write('nr_zip_code', $company_data['zip_code']);
        }
        if (!empty($company_data['web_site'])){
            $this->Cookie->write('nr_web_site', $company_data['web_site']);
        }
        if (!empty($company_data['blog_url'])){
            $this->Cookie->write('nr_blog_url', $company_data['blog_url']);
        }
        if (!empty($company_data['linkedin'])){
            $this->Cookie->write('nr_linkedin', $company_data['linkedin']);
        }
        if (!empty($company_data['twitter_link'])){
            $this->Cookie->write('nr_twitter_link', $company_data['twitter_link']);
        }
        if (!empty($company_data['fb_link'])){
            $this->Cookie->write('nr_fb_link', $company_data['fb_link']);
        }
        if (!empty($company_data['pinterest'])){
            $this->Cookie->write('nr_pinterest', $company_data['pinterest']);
        }
        if (!empty($company_data['instagram'])){
            $this->Cookie->write('nr_instagram', $company_data['instagram']);
        }
        if (!empty($company_data['tumblr'])){
            $this->Cookie->write('nr_tumblr', $company_data['tumblr']);
        }
        // $this->Cookie->write('nr_description',$company_data['description']);
        // $this->Cookie->write('nr_hear_about_us',$company_data['hear_about_us']);
        if (!empty($company_data['description'])){
            $this->Session->write('nr_description', $company_data['description']);
        }
        if (!empty($company_data['hear_about_us'])){
            $this->Session->write('nr_about_us', $company_data['hear_about_us']);
        }
        if (!empty($company_data['logo'])){
            $this->Cookie->write('nr_logo', $company_data['logo']);
            $this->Cookie->write('encodedlogo', SITEURL . '/files/company/logo/' . $company_data['logo_path'] . '/' . $company_data['logo']);
        }
           
        if (!empty($company_data['logo_path'])){
            $this->Cookie->write('nr_logo_path', $company_data['logo_path']);
        }
        if (!empty($company_data['banner_path'])){
            $this->Cookie->write('nr_banner_path', $company_data['banner_path']);
        }
        if (!empty($company_data['banner_image'])){
            $this->Cookie->write('nr_banner_image', $company_data['banner_image']);
           $this->Cookie->write('encodedbanner', SITEURL . '/files/company/banner/' . $company_data['banner_path'] . '/' . $company_data['banner_image']);
        }
        if (($this->request->is("post") || $this->request->is('put')) && !empty($this->data)) {
            $this->StaffUser->set($this->request->data);
            if ($this->StaffUser->validates() && $this->Company->validates()) {
                if (isset($this->request->data['Company']['remove_doc']) && !empty($this->request->data['Company']['remove_doc'])) {
                    $this->Cookie->write('remove_doc', $this->request->data['Company']['remove_doc'], false);
                }
                $date = date('Y') . DS . date('m');
                $nr_docfiles = $this->Cookie->read('nr_docfiles');
                if (isset($this->request->data['Company']['docfile']) && !empty($this->request->data['Company']['docfile'])) {
                    $docfiles = [];
                    $doc_file_array = $this->request->data['Company']['docfile'];
                    foreach ($doc_file_array as $doc_key => $doc_value) {
                        if (isset($nr_docfiles[$doc_key]) && !empty($nr_docfiles[$doc_key])) {
                            if (!in_array($nr_docfiles[$doc_key]['name'], $doc_value['name'])) {
                                unset($nr_docfiles[$doc_key]);
                                $nr_docfiles[$doc_key] = $doc_file_array[$doc_key];
                           }
                        } else {
                            $nr_docfiles[] = $doc_file_array[$doc_key];
                        }
                    }
                    $this->Cookie->write('nr_docfiles', $nr_docfiles, false);
                    $this->request->data['Company']['docfile'] = $nr_docfiles;
                    $nr_docfiles = $nr_docfiles;
                    $docfiles = $nr_docfiles;
                } else {
                    $count_files = count($this->request->data['Company']['docfilescount']);
                    $nr_docfiles = array_slice($nr_docfiles, 0, $count_files);
                    $this->Cookie->write('nr_docfiles', $nr_docfiles, false);
                    $this->request->data['Company']['docfile'] = $nr_docfiles;
                    $nr_docfiles = $nr_docfiles;
                    $docfiles = $nr_docfiles;
                }
                foreach ($nr_docfiles as $nr_doc_key => $nr_doc_value) {
                    if (isset($nr_doc_value['tmp_name']) && !empty($nr_doc_value['tmp_name'])) {
                        $dirFile = WWW_ROOT . 'files' . DS . 'company' . DS . 'docfile' . DS . $date;
                        $dir = new Folder($dirFile, true, 0755);
                        $this->Custom->document_upload($nr_doc_value['name'], $nr_doc_value['tmp_name'], $dirFile);
                    }
                }
                unset($this->request->data['StaffUser']['profile_image']);
                unset($this->request->data['Company']['logo']);
                unset($this->request->data['Company']['banner_image']);

               $description = strip_tags($this->request->data['Company']['description'], '<p><a><ul><ol><li><b><strong><br>');
                $this->Session->write('nr_description', $description);
                $this->Session->write('nr_about_us', $this->request->data['Company']['hear_about_us']);
                $this->Session->write('nr_address', $this->request->data['Company']['address']);
                if ($this->Auth->loggedIn()) {
                    $this->Cookie->write('nr_user_id', $user_id);
                    $this->Cookie->write('nr_email', $this->Auth->user('emal'));
                    $this->Cookie->write('nr_first_name', $this->Auth->user('first_name'));
                    $this->Cookie->write('nr_last_name', $this->Auth->user('last_name'));
                }
                $this->redirect(array('controller' => 'users', 'action' => 'edited-newsroom-preview'));
            } else {
               $errors = $this->StaffUser->validationErrors;
                if (empty($errors)){
                    $errors = $this->Company->validationErrors;
                    $this->Session->setFlash(__('There is some error in below form. Please see red message.'), 'error');
                }
            }
        }
        $this->readNewsroomCookie();
        $nr_docfiles = $this->Cookie->read('nr_docfiles');
        $remove_doc = $this->Cookie->read('remove_doc');
        $this->set('remove_doc', $remove_doc);
        $this->set('nr_docfiles', $nr_docfiles);
        $this->set('company_id', $id);
        $this->set('organization_list', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1))));
        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));
    }



    public function edited_newsroom_preview($newsroomFilter = '')
    {

        $this->layout = 'site_default';

        $this->loadModel('CompanyDocument');

        $this->set('title_for_layout', 'Edit Newsroom');

        $this->readNewsroomCookie();

        $nr_docfiles = $this->Cookie->read('nr_docfiles');

        if (($this->request->is("post") || $this->request->is('put')) && !empty($this->data)) {



            $remove_doc = $this->Cookie->read('remove_doc');

            $CompanyDocument_data = [];

            foreach ($nr_docfiles as $doc_key => $doc_value) {

                if (isset($doc_value['tmp_name']) && !empty($doc_value['tmp_name'])) {

                    $doc_path = date('Y') . DS . date('m');

                    $CompanyDocument_data[] = array('company_id' => $this->request->data['Company']['id'], 'file_name' => $doc_value['name'], 'file_path' => $doc_path);
                }
            }

            if (!empty($CompanyDocument_data)) {

                $this->CompanyDocument->saveAll($CompanyDocument_data);
            }

            if (!empty($remove_doc)) {

                $CompanyDocument_condition = array('CompanyDocument.id IN' => $remove_doc);

                $this->CompanyDocument->deleteAll($CompanyDocument_condition, false);
            }





            $this->request->data['Company'] = array_filter($this->request->data['Company']);

            if ($this->Company->save($this->request->data['Company'])) {



                $this->removeNewsroomCookie();

                $this->Session->setFlash('Newsroom successfully edited.', 'success');

                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            }
        }

        if (isset($newsroomFilter) && !empty($newsroomFilter)) {

            $this->set('newsroomFilter', $newsroomFilter);
        }

        $this->set('nr_docfiles', $nr_docfiles);

        $this->set('organization_list', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1))));

        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));
    }*/

    
    public function create_newsroom($slug = null)
    {
        $this->layout="site_default";
        $this->set('title_for_layout', __('Create Newsroom'));
        $date = date('Y') . DS . date('m');
        $nr_docfiles = "";
        $model='TempCompany';
        try { 
            $this->$model->bindModel(array('belongsTo'=>array('OrganizationType','Country')));
            $nr_docfiles = $save = $data = [];
            if (!empty($this->data)) {
                
                $this->request->data[$model]['logo'] = $this->request->data[$model]['temp_logo'];
                $this->request->data[$model]['banner_image'] = $this->request->data[$model]['temp_banner_image'];

                if(!empty($this->request->data['StaffUser']['temp_profile_image'])){
                    $this->request->data['StaffUser']['profile_image'] = $this->request->data['StaffUser']['temp_profile_image'];
                }

                $save = $this->request->data;
                // $this->request->data['Company']['status']='4';
                
                if (!empty($this->request->data['StaffUser']['id'])) {
                    unset($save['StaffUser']['password']);
                    unset($save['StaffUser']['verify_password']);
                }
 
                $save['TempCompany']['staff_user_id'] = (!empty($this->data['StaffUser']['id'])) ? $this->data['StaffUser']['id'] : null;
 
                $save[$model]['staff_user_data'] = serialize($this->data['StaffUser']);
                $nr_docfiles = (!empty($save['CompanyDocument'])) ? $save['CompanyDocument'] : [];
                // pr($nr_docfiles);die;
                unset($save[$model]['temp_logo']);
                unset($save[$model]['temp_banner_image']);
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
                           
                            $companyDocument['CompanyDocument'][$docCount]['file_name'] = $nr_doc_value['file_name']['name'];
                            $companyDocument['CompanyDocument'][$docCount]['file_path'] =(!empty($nr_doc_value['file_path']))?$nr_doc_value['file_path']: $date;
                            $companyDocument['CompanyDocument'][$docCount]['doc_caption'] =(!empty($nr_doc_value['doc_caption']))?$nr_doc_value['doc_caption']:null;
                            // $companyDocument['CompanyDocument'][$docCount]['company_id'] = null;
                        } else if (!empty($nr_doc_value['file_name'])&&!empty($nr_doc_value['file_path'])) {
                            $companyDocument['CompanyDocument'][$docCount] = $nr_doc_value;
                        }else{
                            unset($podcast);
                        }
                        $docCount++;
                    }
                    
                    $save['TempCompany']['extra_document'] =(!empty($companyDocument))?serialize($companyDocument):null;
                    // $this->CompanyDocument->saveMany($companyDocument['CompanyDocument']);

                } else {
                    $save['TempCompany']['extra_document'] = null;
                }
                unset( $this->request->data['CompanyDocument']);
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
                            unset( $this->request->data['CompanyPresentation'][$preCount]);
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
                            unset( $this->request->data['CompanyEbook'][$eCount]);
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
                            unset( $this->request->data['CompanyPodcast'][$podCount]);
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
                    $save['TempCompany']['newsroom_created_by'] = "frontend";
                    $save['TempCompany']['approved_by'] = "1";
                    $save['TempCompany']['status'] = "0";
                    $save['TempCompany']['slug'] = strtolower(Inflector::slug($this->request->data[$model]['name'], '-'));
                    $media_contact_name = [];
                    $media_job_title = [];
                    $media_email = [];
                    $media_phone_number = [];
                    foreach($save['TempCompany']['media_contact'] as $media_contact){
                        array_push($media_contact_name, $media_contact['name']);
                        array_push($media_job_title, $media_contact['job_title']);
                        array_push($media_email, $media_contact['email']);
                        array_push($media_phone_number, $media_contact['phone_number']);
                    }


                    $save['TempCompany']['media_contact_name'] = implode(",", $media_contact_name);
                    $save['TempCompany']['media_job_title'] = implode(",", $media_job_title);
                    $save['TempCompany']['media_email'] = implode(",", $media_email);
                    $save['TempCompany']['media_phone_number'] = implode(",", $media_phone_number);

                    $tempData = $this->TempCompany->save($save['TempCompany']);
                    if (!empty($tempData)) {
                        $slug = (!empty($slug))?$slug:$tempData['TempCompany']['slug'];
                        // $this->redirect(array('controller' => 'newsrooms', 'action' => 'preview', $companyId));
                        $this->redirect(array('controller' => 'users', 'action' => 'newsroom-preview',$save['TempCompany']['slug']));

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
                        $errors = $this->Company->validationErrors;
                        $errors['Tempcompany']=$errors;
                    }
                  
                    $this->Session->setFlash(__('There is some error in below form. Please see red message.'), 'error');
                }
            } else {
                $this->request->data = $this->TempCompany->findBySlug($slug);

                $this->request->data['StaffUser'] = $data['StaffUser'] = (!empty($this->data["TempCompany"]['staff_user_data'])) ? unserialize($this->data["TempCompany"]['staff_user_data']) : [];
                $nr_docfiles = (!empty($this->data["TempCompany"]['extra_document'])) ? unserialize($this->data["TempCompany"]['extra_document']) : [];
                $this->request->data['CompanyDocument'] = $nr_docfiles = (!empty($nr_docfiles['CompanyDocument'])) ? $nr_docfiles['CompanyDocument'] : [];

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

            $country_list = $this->Custom->getCountryList();
            $organization_list = $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1)));
            $this->set(compact('country_list', 'organization_list', 'nr_docfiles', 'slug', 'data','model'));
        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'create-newsroom'));
            exit;
        }
    }

    
    public function create_newsroom_preview($slug='',$newsroomFilter='prnews',$returnType="",$currentpage='1'){ 
        $this->layout = 'newsroom_preview';
        $isFullwidth="yes";
        $previewPageAction="create-newsroom";
        $model="TempCompany";
        try{
            if(!empty($slug)){ 
                $prarray =$conditions = array(); 
                $this->$model->bindModel(array('belongsTo'=>array('OrganizationType','Country')));
                $data=$this->$model->find("first",array('conditions'=>array("$model.slug"=>$slug)));
            
                if(empty($data)){
                    throw new NotFoundException('Invalid Newsroom.');
                } 

                if(!empty($this->data) && $this->request->is("post")){
                    // $this->Company->bindModel(array('hasMany'=>array('CompanyDocument') ) );

                    $doc_data=(!empty($data["TempCompany"]['extra_document']))?unserialize($data["TempCompany"]['extra_document']):[];
                    $companyId=$data["TempCompany"]['company_id'];
                    $svTempId=$data["TempCompany"]['id'];
                    $tempUserData=unserialize($data["TempCompany"]['staff_user_data']);
                    $saveData['Company']=$data["TempCompany"];  
                    if(!empty($data["TempCompany"]['extra_document'])){
                        $extraDocument=unserialize($data["TempCompany"]['extra_document']);
                        if(!empty($extraDocument)){
                            $saveData['CompanyDocument']=$extraDocument['CompanyDocument'];
                        }
                    }

                    
                    // $this->CompanyPresentation->deleteAll(['company_id' => $companyId], false);
                    if (!empty($data["TempCompany"]['presentation'])) {
                        $companyPresentationData = unserialize($data["TempCompany"]['presentation']);
                        if (!empty($companyPresentationData)) {
                            $this->loadModel('CompanyPresentation');
                            $saveData['CompanyPresentation'] = $companyPresentationData['CompanyPresentation'];
                        }
                    }

                    // $this->CompanyPodcast->deleteAll(['company_id' => $companyId], false);
                    if (!empty($data["TempCompany"]['podcasts'])) {
                        $companyPodcastData = unserialize($data["TempCompany"]['podcasts']);
                        if (!empty($companyPodcastData)) {
                            $this->loadModel('CompanyPodcast');
                            $saveData['CompanyPodcast'] = $companyPodcastData['CompanyPodcast'];
                        }
                    }

                    
                    // $this->CompanyEbook->deleteAll(['company_id' => $companyId], false);
                    if (!empty($data["TempCompany"]['ebooks'])) {
                        $companyEbookData = unserialize($data["TempCompany"]['ebooks']);
                        if (!empty($companyEbookData)) {
                            $this->loadModel('CompanyEbook');
                            $saveData['CompanyEbook'] = $companyEbookData['CompanyEbook'];
                        }
                    }

                    
                    unset($data["TempCompany"]['id']);
                    unset($data["TempCompany"]['company_id']);
                    unset($data["TempCompany"]['staff_user_data']);
                
                    unset($data["TempCompany"]['extra_document']);
                    unset($data["TempCompany"]['podcasts']);
                    unset($data["TempCompany"]['ebooks']);
                    $saveUserData['StaffUser']=$tempUserData;
                    unset($saveUserData["StaffUser"]['encodedprofile']); 
                    
                    $createdUser= $this->StaffUser->save($saveUserData); 
                    $user_id=$saveData['Company']['staff_user_id']=$createdUser['StaffUser']['id'];
                    unset($saveData['Company']['id']);
                    unset($saveData['Company']['company_id']);
                    if($createdUser){
                        if (AuthComponent::user() !=true) { 
                            $saveUserData['StaffUser']['id'] = $user_id;
                            $this->Auth->login($saveUserData['StaffUser']);
                        }

                        if($this->Company->saveAll($saveData,array("deep"=>true))){  
                            $lstInsertId = $this->Company->getLastInsertID();
                            $this->Session->write('ClientUserSaved.company_id', $lstInsertId);

                            $this->TempCompany->id=$svTempId;
                            $this->TempCompany->delete();
                    
                        
                        // create new session id update all plan with new session id because latest newsroom will payment will charge
                            $cart_session_id = Security::hash(CakeText::uuid(), 'sha1', true);
                            $_SESSION['cart_session_id'] = $cart_session_id;
                            $cartData = $this->Custom->fetchCartData($user_id);
                            if (!empty($cartData)) {
                                $this->Cart->query("UPDATE `carts` SET `cart_session_id` = '" . $cart_session_id . "' WHERE `carts`.`staff_user_id` = '$user_id' AND `carts`.`cart_type` ='plan' AND `carts`.`is_newsroom_incart` ='0'");
                            }
                            
                            $saveCart['Cart']['staff_user_id'] = $user_id;
                            $saveCart['Cart']['plan_id'] = '';
                            $saveCart['Cart']['company_id'] = $lstInsertId;
                            $saveCart['Cart']['is_newsroom_incart'] = 1;
                            $saveCart['Cart']['cart_session_id'] = $cart_session_id;
                            $this->Cart->save($saveCart);
                            $is_plan_paid = $this->Auth->user('pr_plan_paid');
                            if (isset($is_plan_paid) && $is_plan_paid != 0) {
                                $this->Session->setFlash('You already have purchased a PR plan, still you can purchase extra plans for future PR submission or can move ahead with newsroom checkout.', 'success');
                            }
                            $this->redirect('/plans/online-distribution/');
                            
                            exit;
                        }

                    }
                    
                }
                $tempId=$data["TempCompany"]['id'];
                $data['StaffUser']=unserialize($data["TempCompany"]['staff_user_data']);
                $data['Company']=$data["TempCompany"]; 
                $doc_data=(!empty($data["TempCompany"]['extra_document']))?unserialize($data["TempCompany"]['extra_document']):[];
                
                $this->request->data['CompanyDocument'] = (!empty($doc_data['CompanyDocument']))?$doc_data['CompanyDocument']:[];

                $presentationData = (!empty($data["TempCompany"]['presentation'])) ? unserialize($data["TempCompany"]['presentation']) : [];
                $this->request->data['CompanyPresentation'] = (!empty($presentationData['CompanyPresentation']))?$presentationData['CompanyPresentation']:[];

                $podData = (!empty($data["TempCompany"]['podcasts'])) ? unserialize($data["TempCompany"]['podcasts']) : [];
                $this->request->data['CompanyPodcast'] = (!empty($podData['CompanyPodcast']))?$podData['CompanyPodcast']:[];
            

                $ebookData = (!empty($data["TempCompany"]['ebooks'])) ? unserialize($data["TempCompany"]['ebooks']) : [];
                
                $this->request->data['CompanyEbook'] = (!empty($ebookData['CompanyEbook']))?$ebookData['CompanyEbook']:[];
    
                
                $this->loadModel('CompanyDocument'); 
                $this->set('title_for_layout',ucfirst($data[$model]['name'])); 
                if($newsroomFilter=='prnews'){ 
                    $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));
                    $prconditions[] = array('PressRelease.status' =>'1','PressRelease.release_date <=' => date('Y-m-d'),"company_id"=>$data[$model]['company_id']);
                    $this->paginate = array('conditions' => $prconditions, 'limit' => Configure::read('Site.paging'), 'order' => 'PressRelease.release_date DESC');
                    $prarray = $this->paginate('PressRelease');  
                }else if($newsroomFilter=='social'){
                    require APP.'Vendor'.DS.'tumblr'.DS.'tumblr.php';
                    $obj=new tumblrFeed(); 
                    if(!empty($data[$model]['tumblr'])){
                        $blogName=str_replace(array("http:","/"),array("",""),$data[$model]['tumblr']);
                        $tumblrData=$obj->fetchfeeds($blogName,'10');
                        $this->set('tumblrData',$tumblrData);
                    }
                }

                if($newsroomFilter=='companyassets'){ 
                    $media_array=[];
                    $totalCount=$totalpages="0";

                    //  $this->newsroom_assets($tempId,$data['StaffUser']['id'],$currentpage,$data["TempCompany"]['slug']);
                    // $doc_data = $this->CompanyDocument->find('all',array('conditions'=>array('company_id'=>$data[$model]['id'])));

                    $this->set(compact('media_array', 'totalCount', 'totalpages', 'currentpage'));
                }
                $doc_files=$doc_video=$doc_image =""; 
                $this->set('model',$model);
                $this->set(compact('data','previewPageAction','slug','prarray','newsroomFilter','returnType',"doc_files","doc_video","doc_files","doc_image","isFullwidth","tempId",'doc_data', "presentationData",'ebookData','podData'));
            }

        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' => 'create-newsroom'));
            exit;
        }
    }


    public function edit_newsroom($slug=null,$returnType="approved"){
        $model="TempCompany";
        $this->set('title_for_layout', __('Edit Newsroom'));
        $this->set('model',$model);
        $date = date('Y').DS.date('m');
        $nr_docfiles="";
        try{
            if(!$slug){
                throw new NotFoundException('Invalid Newsroom.');
            } 
            $data=[];
            $company=$this->Company->findBySlug($slug); 
            if(empty($company)) {
                throw new NotFoundException('Invalid Newsroom.');
            }  
            $id=$company['Company']['id']; 
            $data=$this->TempCompany->find('first',['conditions'=>['TempCompany.slug'=>$slug],'order'=>'TempCompany.id DESC']); 
            if (!empty($this->data)) {   
              

                $this->request->data[$model]['logo']=$this->request->data[$model]['temp_logo'];
                $this->request->data[$model]['banner_image']=$this->request->data[$model]['temp_banner_image'];
                $this->request->data['StaffUser']['profile_image']=$this->request->data['StaffUser']['temp_profile_image'];
                
                $save=$this->request->data;
                $save[$model]['company_id']=$id;
                $save[$model]['staff_user_data']=serialize($this->data['StaffUser']); 
                $nr_docfiles=(!empty($save['CompanyDocument']))?$save['CompanyDocument']:[];

                unset($save[$model]['temp_logo']);
                unset($save[$model]['temp_banner_image']);
                unset($save['StaffUser']); 
                unset($save['CompanyDocument']);
                // if(!empty($nr_docfiles)){
                //     $dirFile = ROOT.DS."app".DS.'webroot'.DS.'files'.DS.'company' . DS . 'docfile' . DS .$date;
                //     $dir = new Folder($dirFile, true, 0755); 
                //     $docCount=0;
                //     $doc=[];
                //     foreach ($nr_docfiles as $nr_doc_key => $nr_doc_value) {
                //         if(!empty($nr_doc_value['tmp_name'])){
                //             $this->Custom->document_upload($nr_doc_value['name'],$nr_doc_value['tmp_name'],$dirFile);
                //             $doc['CompanyDocument'][$docCount]['file_name']=$nr_doc_value['name'];
                //             $doc['CompanyDocument'][$docCount]['file_path']=$date;
                //             $doc['CompanyDocument'][$docCount]['company_id']=$id;
                //         }else{
                //             $doc['CompanyDocument'][$docCount]=$nr_doc_value;
                //         }
                //         $docCount++;
                //     } 
                //     $save['TempCompany']['extra_document']=serialize($doc);
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
                           
                            $companyDocument['CompanyDocument'][$docCount]['file_name'] = $nr_doc_value['file_name']['name'];
                            $companyDocument['CompanyDocument'][$docCount]['file_path'] =(!empty($nr_doc_value['file_path']))?$nr_doc_value['file_path']: $date;
                            $companyDocument['CompanyDocument'][$docCount]['doc_caption'] =(!empty($nr_doc_value['doc_caption']))?$nr_doc_value['doc_caption']:null;
                            $companyDocument['CompanyDocument'][$docCount]['id'] = (!empty($nr_doc_value['id']))?$nr_doc_value['id']: null;
                        } else if (!empty($nr_doc_value['file_name'])&&!empty($nr_doc_value['file_path'])) {
                            $companyDocument['CompanyDocument'][$docCount] = $nr_doc_value;
                        }
                        $docCount++;
                    }
                    $save['TempCompany']['extra_document'] = serialize($companyDocument);
                    // $this->CompanyDocument->saveMany($companyDocument['CompanyDocument']);

                } else {
                    $save['TempCompany']['extra_document'] = null;
                }
                unset( $this->request->data['CompanyDocument']);
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
 


                $save['TempCompany']['staff_user_id']=(!empty($this->data['StaffUser']['id']))?$this->data['StaffUser']['id']:null;


               // unset($this->request->data['TempCompany']['docfile']);
                if ($this->TempCompany->validates()) { //$this->StaffUser->validates()&&
                    if(!empty($data['TempCompany']['id'])){
                      $insertId= $save['TempCompany']['id']=$data['TempCompany']['id'];
                    }

                    $media_contact_name = [];
                    $media_job_title = [];
                    $media_email = [];
                    $media_phone_number = [];
                    foreach($save['TempCompany']['media_contact'] as $media_contact){
                        array_push($media_contact_name, $media_contact['name']);
                        array_push($media_job_title, $media_contact['job_title']);
                        array_push($media_email, $media_contact['email']);
                        array_push($media_phone_number, $media_contact['phone_number']);
                    }


                    $save['TempCompany']['media_contact_name'] = implode(",", $media_contact_name);
                    $save['TempCompany']['media_job_title'] = implode(",", $media_job_title);
                    $save['TempCompany']['media_email'] = implode(",", $media_email);
                    $save['TempCompany']['media_phone_number'] = implode(",", $media_phone_number);

                    $this->TempCompany->save($save);
                    if(empty($insertId)){
                        $insertId= $this->TempCompany->getLastInsertID(); 
                    } 
                    $this->redirect(array('controller' => 'users', 'action' => 'edit_preview',$slug,'prnews',$returnType));
                }      
               
            }else{
               $this->request->data=$data;
                if(empty($data)){
                    $data=$this->Company->read(null, $id); 
                    $this->request->data=$data;
                    $this->request->data['TempCompany']=$data['Company'];
                }else{
                    $data['StaffUser']=(!empty($data["TempCompany"]['staff_user_data']))?unserialize($data["TempCompany"]['staff_user_data']):[];
                    $nr_docfiles=(!empty($data["TempCompany"]['extra_document']))?unserialize($data["TempCompany"]['extra_document']):[];
                    $data['CompanyDocument']=(!empty($nr_docfiles['CompanyDocument']))?$nr_docfiles['CompanyDocument']:[];

                    $savedPresentation = (!empty($data["TempCompany"]['presentation'])) ? unserialize($data["TempCompany"]['presentation']) : [];

                    $this->request->data['CompanyPresentation'] =(!empty($savedPresentation['CompanyPresentation']))?$savedPresentation['CompanyPresentation']:[];

                    $savedPodcast = (!empty($data["TempCompany"]['podcasts'])) ? unserialize($data["TempCompany"]['podcasts']) : [];
                    $this->request->data['CompanyPodcast'] = (!empty($savedPodcast['CompanyPodcast']))?$savedPodcast['CompanyPodcast']:[];

                    $savedEbook=(!empty($data["TempCompany"]['ebooks'])) ? unserialize($data["TempCompany"]['ebooks']) : [];
                    $this->request->data['CompanyEbook'] = (!empty($savedEbook['CompanyEbook']))?$savedEbook['CompanyEbook']:[];
                }  
            }
             
           
            $country_list=$this->Custom->getCountryList();
            $nr_docfiles=(!empty($data['CompanyDocument']))?$data['CompanyDocument']:[];
            $organization_list=$this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1)));
            $this->set(compact('country_list','organization_list','nr_docfiles','id','data'));

        }catch(Exception $exc){
            $this->Session->setFlash($exc->getMessage(), 'error');
            $this->redirect(array('action' =>"newsrooms",$returnType)); 
        } 
    
    }


    public function edit_preview($slug='',$newsroomFilter='prnews',$returnType="approved",$currentpage='1'){ 
        $this->layout = 'newsroom_preview';
        $isFullwidth="yes";
        $previewPageAction="edit-newsroom";
        $model="TempCompany";
    	if(!empty($slug)){ 
            if(!$slug){
                throw new NotFoundException('Invalid Newsroom.');
            } 

	        $prarray =$conditions = array(); 
	        $this->$model->bindModel(array('belongsTo'=>array('OrganizationType','Country')));
            $data=$this->$model->find("first",array('conditions'=>array("$model.slug"=>$slug)));
           
            if(empty($data)){
                throw new NotFoundException('Invalid Newsroom.');
            } 


            if(!empty($this->data) && $this->request->is("post")){
                $this->Company->bindModel(array('hasMany'=>array('CompanyDocument') ) );  
                $doc_data=(!empty($data["TempCompany"]['extra_document']))?unserialize($data["TempCompany"]['extra_document']):[];
                $companyId=$data["TempCompany"]['company_id'];
                $svTempId=$data["TempCompany"]['id'];
                $tempUserData=unserialize($data["TempCompany"]['staff_user_data']);
                $saveData['Company']=$data["TempCompany"]; 
                $saveData['Company']['id']=$companyId; 
                if(!empty($data["TempCompany"]['extra_document'])){
                    $extraDocument=unserialize($data["TempCompany"]['extra_document']);
                    if(!empty($extraDocument)){
                        $saveData['CompanyDocument']=$extraDocument['CompanyDocument'];
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
                if($this->Company->saveAll($saveData,array("deep"=>true))){ 
                    $saveUserData['StaffUser']=$tempUserData;
                    // $saveUserData['StaffUser']['email']=strtolower($tempUserData['StaffUser']['email']);
                    $this->StaffUser->save($saveUserData); 
                    $this->TempCompany->id=$svTempId;
                    $this->TempCompany->delete();
                }
                $this->Session->setFlash(__('Newsroom successfully edited.'), 'success');  

                $this->redirect(array('controller' => 'users', 'action' => 'newsrooms',$returnType));
                exit;
            }

	        
            $tempId=$data["TempCompany"]['id'];
            $data['StaffUser']=unserialize($data["TempCompany"]['staff_user_data']);
            $data['Company']=$data["TempCompany"]; 
            $doc_data=(!empty($data["TempCompany"]['extra_document']))?unserialize($data["TempCompany"]['extra_document']):[];
            
            $this->request->data['CompanyDocument'] = (!empty($doc_data['CompanyDocument']))?$doc_data['CompanyDocument']:[];

            $presentationData = (!empty($data["TempCompany"]['presentation'])) ? unserialize($data["TempCompany"]['presentation']) : [];
            $this->request->data['CompanyPresentation'] = (!empty($presentationData['CompanyPresentation']))?$presentationData['CompanyPresentation']:[];

            $podData = (!empty($data["TempCompany"]['podcasts'])) ? unserialize($data["TempCompany"]['podcasts']) : [];
            $this->request->data['CompanyPodcast'] = (!empty($podData['CompanyPodcast']))?$podData['CompanyPodcast']:[];

            $ebookData = (!empty($data["TempCompany"]['ebooks'])) ? unserialize($data["TempCompany"]['ebooks']) : [];
            
            $this->request->data['CompanyEbook'] = (!empty($ebookData['CompanyEbook']))?$ebookData['CompanyEbook']:[];
 
            
            $this->loadModel('CompanyDocument'); 
	        $this->set('title_for_layout',ucfirst($data[$model]['name'])); 
	        if($newsroomFilter=='prnews'){ 
	            $this->PressRelease->unbindModel(array('hasMany'=>array('PressSeo','PressYoutube','PressPoadcast'),'hasAndBelongsToMany'=>array('Category','Msa','State','Distribution'),'belongsTo'=>array('Plan')));
	            $prconditions[] = array('PressRelease.status' =>'1','PressRelease.release_date <=' => date('Y-m-d'),"company_id"=>$data[$model]['company_id']);
	            $this->paginate = array('conditions' => $prconditions, 'limit' => Configure::read('Site.paging'), 'order' => 'PressRelease.release_date DESC');
	            $prarray = $this->paginate('PressRelease');  
	        }else if($newsroomFilter=='social'){
	            require APP.'Vendor'.DS.'tumblr'.DS.'tumblr.php';
	            $obj=new tumblrFeed(); 
	            if(!empty($data[$model]['tumblr'])){
	                $blogName=str_replace(array("http:","/"),array("",""),$data[$model]['tumblr']);
	                $tumblrData=$obj->fetchfeeds($blogName,'10');
	                $this->set('tumblrData',$tumblrData);
	            }
	        }

            if($newsroomFilter=='companyassets'){ 
                $this->newsroom_assets($data["TempCompany"]['company_id'],$data['StaffUser']['id'],$currentpage,$data["TempCompany"]['slug']);
                // $doc_data = $this->CompanyDocument->find('all',array('conditions'=>array('company_id'=>$data[$model]['id'])));
	        }

            $doc_files=$doc_video=$doc_files=$doc_image ="";
            $this->set('model',$model);
	        $this->set(compact('data','prarray','newsroomFilter','returnType',"doc_files","doc_video","doc_files","doc_image","isFullwidth","tempId",'doc_data', "presentationData",'ebookData','podData',"previewPageAction"));
        }
    }



    public function prpayment($selectedplan, $prId = '',$method="")
    {

        $this->layout = 'default';
        try {
               $user_id = $this->Auth->user("id");
            $plan_id = $selectedplan;
            $firstName = $this->Auth->user('first_name');
            $lastName = $this->Auth->user('last_name');
            $cartdata = $this->Custom->getprcartdata($user_id, $selectedplan, $prId);
            $cartSessionId = $cartdata['cart_session_id'];
            if ($cartdata['totals']['subtotal'] == 0) {
                throw new NotFoundException(__('Your cart is empty. Please try again.'));
            }
            $total_amount = $cartdata['totals']["total"];
            if($method=='stripe'){
                Stripe::setApiKey(Configure::read('Stripe.secret'));
                $YOUR_DOMAIN = Router::url('/', true);
                $product_name = 'Press Release';
             $lineItems[] = [
                    'price_data' => [
                        'currency' => "USD",
                        'product_data' => [
                            'name' => $product_name,  // Product name
                        ],
                        'unit_amount' => $total_amount * 100,  // Price in cents
                    ],
                    'quantity' => 1,  // Default quantity
                ];
                
            $checkoutSession = Session::create([
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'metadata' => [
                        'user_id' => $user_id,
                        'cart_id' => $cartSessionId,
                        'plan_id' => $plan_id,
                        'total_amount' => $total_amount,
                    ],
                    'success_url' => $YOUR_DOMAIN . 'stripe/success?cart_id=' . $cartSessionId . '&session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => $YOUR_DOMAIN . 'plans',
                ]);
             return $this->redirect($checkoutSession->url);
            }else{
            $this->set(compact('total_amount', 'user_id', 'firstName', 'lastName', 'plan_id', "cartSessionId", 'prId'));
                
            }
            
            
            
                
                
            
            
        } catch (Exception $exc) {
            $message = $exc->getMessage();
            $this->Session->setFlash(__($message), 'error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
    }



    public function paymentprsuccess()
    {
        $this->set('title_for_layout', 'Payment Successfully done');
        $transactions = $this->request->query;
        if(!empty($transactions['crt'])){
            // Getting Transaction by cart 
            $transactionData = $this->Transaction->find('first', array('conditions' => array('Transaction.cart_session_id' => $transactions['crt'])));
        }

        if (!empty($transactions)) {
            $coupon_data = '';
            $cartSessionId = '';
            if (isset($this->request->query['cm']) && !empty($this->request->query['cm'])) {
                $userPlanCartIds = explode(",", $this->request->query['cm']); //0=>User id,1=>Plan id,2=> Cart ID
                $user_id = (!empty($userPlanCartIds[0])) ? $userPlanCartIds[0] : $this->Auth->user('id');
                $plan_id = (!empty($userPlanCartIds[1])) ? $userPlanCartIds[1] : $transactions['plan'];
                $prId = (!empty($userPlanCartIds[2])) ? $userPlanCartIds[2] : $transactions['pr'];
                $cartSessionId = (!empty($userPlanCartIds[3])) ? $userPlanCartIds[3] :$transactions['crt'];
            }
            $cartdata = $this->Custom->getprcartdata($user_id, $plan_id, $prId);
            if (isset($transactions['tx']) && !empty($transactions['tx'])) {
                if ($this->save_prtransactions($transactions, $user_id, $plan_id, $cartdata, $cartSessionId,$prId)) {
                    $this->removeNewsroomCookie();
                   return $this->redirect(array('controller' => 'users', 'action' => 'prsuccess', $prId));
                }
            }
        } else {
            $this->removeNewsroomCookie();
            $this->redirect(array('controller' => 'users', 'action' => 'prsuccess'));
        } 
    }

    public function save_prtransactions($transaction, $user_id, $plan_id, $sess_data, $cartSessionId = '',$prId="")
    {
            $this->loadModel('TransactionPressRelease');
            $checkTx = $this->Transaction->find('count', array('conditions' => array('tx_id' => $transaction['tx'])));
            if ($checkTx==0) {
            $errorString = "";
            $status = strtolower($transaction['st']);
            $data_array = array();
            $data_array['Transaction']['cart_session_id'] =(!empty($sess_data['cart_session_id']))?$sess_data['cart_session_id']:null;
            $data_array['Transaction']['transaction_type'] = 'pr';
            $data_array['Transaction']['staff_user_id'] = $user_id;
            $data_array['Transaction']['tx_id'] = $transaction['tx'];
            $data_array['Transaction']['paymant_date'] = date('Y-m-d H:i:s');
            $data_array['Transaction']['currency'] = $transaction['cc'];
            $data_array['Transaction']['discount_id'] = isset($sess_data['discount_id']) ? $sess_data['discount_id'] : "0";
            $data_array['Transaction']['newsroom_amount'] = "0.00";
            $data_array['Transaction']['subtotal'] = $sess_data['totals']['subtotal'];
            $data_array['Transaction']['discount'] = $sess_data['totals']['discount'];
            $data_array['Transaction']['tax'] = $sess_data['totals']['tax'];
            $data_array['Transaction']['total'] = $transaction['amt'];
            $data_array['Transaction']['status'] = $status;
            $data_array['Transaction']['cart_type'] = "";
            $data_array['Transaction']['error_message'] = $errorString;

            /* only for testing 
            $uName = $this->Auth->user('first_name');
            $emailTemplate = $this->EmailTemplate->selectTemplate('payment-invoice');
            $mailTo = $this->Auth->user('email');

            $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('press_release_id' => $prId, 'staff_user_id' => $user_id)));
            $html=$this->Custom->getPrInvoiceHtmlForMail($data_array,$transdata);
            $this->Custom->send_invoice_mail($html,$emailTemplate,$uName,$mailTo);
            die;
             */
            $this->loadModel('Transaction');
            if ($this->Transaction->save($data_array)) {
                $txId = $this->Transaction->getLastInsertID();
                $updatePrCreadits = $this->RemainingUserPlan->find('first', array('conditions' => array('RemainingUserPlan.plan_id' => $plan_id, 'RemainingUserPlan.staff_user_id' => $user_id, 'number_pr !=' => '0'), 'fields' => array('number_pr', 'id')));
                if (!empty($updatePrCreadits)) {
                    $number_pr = $updatePrCreadits['RemainingUserPlan']['number_pr'] - 1;
                    $this->RemainingUserPlan->id = $updatePrCreadits['RemainingUserPlan']['id'];
                    $this->RemainingUserPlan->saveField('number_pr', $number_pr);
                }
                if (empty($errorString)) {
                    $plan_id = $this->Session->read('pr_selectedplan');
                    $cart_plans = $this->saveprtrans($user_id, $plan_id, $txId);
                    if ($prId) {
                        $savedatapr['PressRelease']['id'] = $prId;
                        $savedatapr['PressRelease']['status'] = '0';
                        $savedatapr['PressRelease']['transaction_id']=$data_array['Transaction']['tx_id'];
                        $this->PressRelease->save($savedatapr);
                    }
                    $uName = $this->Auth->user('first_name');
                    $emailTemplate = $this->EmailTemplate->selectTemplate('payment-invoice');
                    $mailTo = $this->Auth->user('email');
                     
                    $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('press_release_id' => $prId, 'staff_user_id' => $user_id)));
                    $html=$this->Custom->getPrInvoiceHtmlForMail($data_array,$transdata);
                    $this->Custom->send_invoice_mail($html,$emailTemplate,$uName,$mailTo);
                    if (!empty($prId)) {
                        $this->Cart->deleteAll(['Cart.press_release_id' => $prId, 'Cart.staff_user_id' => $user_id, 'cart_type' => 'pr'], false);
                    } else if (!empty($cartSessionId)) {
                        $this->Cart->deleteAll(['Cart.cart_session_id' => $cartSessionId, 'Cart.staff_user_id' => $user_id, 'cart_type' => 'pr'], false);
                    }
                    $this->Session->delete("pr_selectedplan");
                    //$this->redirect(array('controller' => 'users', 'action' => 'prsuccess', $savedatapr['PressRelease']['id']));
                } else {
                     $this->redirect(array('controller' => 'users', 'action' => 'payment/?err=' . $errorString));
                }
            }
        }
        return true;
    }



    public function paymentprcancel($value = '')
    {
        echo "Payment Failed";
        die;
    }



    public function saveprtrans($user_id, $selectedplan, $transId = "0", $status = '')
    {

        $this->loadModel('TransactionPressRelease');

        $cart_plans['totals']['subtotal'] = $cart_plans['totals']['discount'] = $cart_plans['totals']['tax'] = $cart_plans['totals']['total'] = $famount = $tax = $discount = $plan_amount = '0.00';

        $this->Cart->recursive = '1';

        $this->Cart->bindModel(array('belongsTo' => array('Plan')));

        $cart_plans = $this->Cart->find('first', array('conditions' => array('staff_user_id' => $user_id, 'plan_id' => $selectedplan, 'cart_type' => 'pr')));

        $cart_plans['TransactionPressRelease'] = $cart_plans['Cart'];

        $cart_plans['TransactionPressRelease']['transaction_id'] = $transId;

        $cart_plans['TransactionPressRelease']['staff_user_id'] = $cart_plans['Cart']['staff_user_id'];

        $plan = $cart_plans['Plan'];

        $cart_plans['TransactionPressRelease']["word_amount"] = "0.00";

        if ($cart_plans['Cart']['extra_words'] > 0) {

            $amt = ceil(($cart_plans['Cart']['extra_words'] / 100)) * $plan['add_word_amount'];

            $amount = number_format($amt, 2);

            $cart_plans['TransactionPressRelease']["word_amount"] = $amount;

            $plan_amount += $amount;
        }

        $cart_plans['TransactionPressRelease']["category_amount"] = "0.00";

        if ($cart_plans['Cart']['extra_category'] > 0) {

            $amt = ($cart_plans['Cart']['extra_category'] * $plan['add_category_charges']);

            $amount = number_format($amt, 2);

            $cart_plans['TransactionPressRelease']["category_amount"] = $amount;

            $plan_amount += $amt;
        }

        $cart_plans['TransactionPressRelease']["msa_amount"] = "0.00";

        if ($cart_plans['Cart']['extra_msa'] > 0) {

            $amtmsa = ceil($cart_plans['Cart']['extra_msa'] / $plan['msa_limit']) * ($plan['add_msa_charges']);

            $amountmsa = number_format($amtmsa, 2);

            $cart_plans['TransactionPressRelease']["msa_amount"] = $amountmsa;

            $plan_amount += $amtmsa;
        }

        $cart_plans['TransactionPressRelease']["state_amount"] = "0.00";

        if ($cart_plans['Cart']['extra_state'] > 0) {

            $amtstate = ceil($cart_plans['Cart']['extra_state'] / $plan['state_limit']) * ($plan['add_state_charges']);

            $amountstate = number_format($amtstate, 2);

            $cart_plans['TransactionPressRelease']["state_amount"] = $amountstate;

            $plan_amount += $amtstate;
        }

        if ($cart_plans['Cart']['translate_charges'] > 0) {

            $amttx = $plan['translation_amount'];

            $amttx = number_format($amttx, 2);

            $cart_plans['TransactionPressRelease']["translation_amount"] = $amttx;

            $plan_amount += $amttx;
        }

        $cart_plans['feature'] = [];

        if (!empty($cart_plans['Cart']['distribution_ids'])) {

            $features = unserialize($cart_plans['Cart']['distribution_ids']);

            foreach ($features as $index => $value) {

                $featureData = $this->Custom->getprfeatureprice($value['distribution_id']);

                $cart_plans['feature'][$index]['distribution_id'] = $value['distribution_id'];

                $listId = $cart_plans['Cart']['list_id'];

                $featureAmount = $this->Custom->getAmountMailList($listId, $value['distribution_id'], $featureData['amount'], $featureData['number']);

                $cart_plans['feature'][$index]['name'] = $featureData['name'];

                $cart_plans['feature'][$index]['price'] = $featureAmount;

                $famount = $famount + $cart_plans['feature'][$index]['price'];
            }

            $cart_plans['Cart']['distribution_ids'] = serialize($cart_plans['feature']);

            unset($cart_plans['Cart']['distribution_ids']);

            $cart_plans['TransactionPressRelease']['distribution_ids'] = serialize($cart_plans['feature']);

            unset($cart_plans['feature']);
        }


        $plan_amount += $famount;

        $cart_plans['TransactionPressRelease']['subtotal'] = number_format($plan_amount, 2);

        $cart_plans['TransactionPressRelease']['discount'] = number_format($discount, 2);

        $cart_plans['TransactionPressRelease']['tax'] = $tax;

        $cart_plans['TransactionPressRelease']['total'] = $this->Custom->get_cart_total('0', $plan_amount, $discount);

        unset($cart_plans['Cart']);

        unset($cart_plans['Plan']);

        unset($cart_plans['TransactionPressRelease']['id']);

        unset($cart_plans['TransactionPressRelease']['cart_type']);

        unset($cart_plans['TransactionPressRelease']['is_newsroom_incart']);

        // unset($cart_plans['TransactionPressRelease']['staff_user_id']);

        if ($this->TransactionPressRelease->save($cart_plans)) {

            $lastId = $this->TransactionPressRelease->getLastInsertID();

            $distribution_ids = unserialize($cart_plans['TransactionPressRelease']['distribution_ids']);

            $distributionsArr = [];

            if (!empty($distribution_ids)) {

                foreach ($distribution_ids as $key => $distribution_id) {

                    $distributionsArr[$key]['transaction_press_release_id'] = $lastId;

                    $distributionsArr[$key]['distribution_id'] = $distribution_id['distribution_id'];
                }

                $this->loadModel('DistributionsTransactionPressRelease');

                $this->DistributionsTransactionPressRelease->saveMany($distributionsArr);
            }
        }

        return $cart_plans;
    }



    /*Plan payment*/

    public function payment()
    {
        $this->set('title_for_layout', 'Make Payment');
        $this->layout = 'site_default';

        $newsroom_signup = "";

        try {

            $params = $this->request->query;
            $payment_method = isset($params['payment_method']) ? $params['payment_method'] : '';

            // Check if payment method is valid
            if ($payment_method != "paypal" && $payment_method != "stripe") {
                throw new NotFoundException(__('Please Select Valid Payment Method'));
            }

            $flag = "true";
            $plan_id = "";
            $recurring      =   false;
            $cycle = "";
            $cycle_period = "";
            $user_id = $this->Auth->user("id");
            $first_name = $this->Auth->user("first_name");
            $last_name = $this->Auth->user("last_name");
            $newsroom_slug = "";
            if ($this->Auth->loggedIn() && !empty($this->params->pass) && isset($this->params->pass[0]) && !empty($this->params->pass[0])) {
                $newsroom_slug = $this->params->pass[0];
                $newsroom = $this->Company->find("first", ["conditions" => ["Company.slug" => $newsroom_slug], "fields" => ["Company.id"]]);
                $checkNewsroomInCart = $this->Cart->find("first", ["conditions" => ["Cart.company_id" => $newsroom["Company"]["id"]], "fields" => ["Cart.cart_session_id"]]);
                if (!empty($checkNewsroomInCart)) {
                    $this->Cart->query("UPDATE `carts` SET `cart_session_id` = '" . $checkNewsroomInCart['Cart']['cart_session_id'] . "' WHERE `carts`.`staff_user_id` = '$user_id' AND `carts`.`cart_type` ='plan' AND `carts`.`is_newsroom_incart` ='0'");
                    $checkCart = $this->Cart->find('first', array('fields' => ['cart_session_id'], 'conditions' => array('Cart.staff_user_id' => $user_id, 'Cart.cart_session_id' => $checkNewsroomInCart["Cart"]['cart_session_id'], 'cart_type' => "plan"), 'order' => "Cart.id desc"));
                } else {
                    $checkCart = $this->Cart->find('first', array('fields' => ['cart_session_id'], 'conditions' => array('Cart.staff_user_id' => $user_id, 'cart_type' => "plan"), 'order' => "Cart.id desc"));
                }
            } else {
                $checkCart = $this->Cart->find('first', array('fields' => ['cart_session_id'], 'conditions' => array('Cart.staff_user_id' => $user_id, 'cart_type' => "plan"), 'order' => "Cart.id desc"));
            }
            if (empty($checkCart))
                throw new NotFoundException(__('Please purchase a PR plan or create newsroom.'));
                $cartSessionId = $checkCart['Cart']['cart_session_id'];
            $coupon_data = '';
             if ($this->Session->check('FrontCart.coupon')) {
                $couponId = $this->Session->read('FrontCart.coupon.coupon_id');
                $coupon_data = $this->Coupon->find('first', array('conditions' => array('Coupon.id' => $couponId, 'release_date <=' => date('Y-m-d'), 'end_date >=' => date('Y-m-d')), 'fields' => array('value', 'type', 'code', 'id')));
                if (empty($coupon_data)){
                    throw new NotFoundException(__('Your coupon expired.'));
                }
            }

            $newsroom_signup = $this->Custom->get_save_transaction_formateddata($user_id, $cartSessionId, $coupon_data);
            if ($newsroom_signup['Transaction']["total"] <= 0)
                throw new NotFoundException(__('Please purchase a PR plan or create newsroom.'));
            // echo '<pre>';
            // print_r($newsroom_signup);die;
                $total_amount = str_replace(",", "", $newsroom_signup['Transaction']["total"]);
                $this->loadModel('Plan');

            if (isset($newsroom_signup['TransactionPlan']) && !empty($newsroom_signup['TransactionPlan'])) {

                $plan_id = $newsroom_signup['TransactionPlan'][0]['plan_id'];

                $plan_details = $this->Plan->find('first', array('conditions' => array("Plan.id" => $plan_id)));

                if (isset($plan_details) && !empty($plan_details)) {

                    if ($plan_details['Plan']['plan_type'] == 'subscription') {
                        // $plan_details['Plan']['cycle_period']='daily'; // only for testing
                        switch ($plan_details['Plan']['cycle_period']) {
                            case 'monthly':
                                $cycle             =   'M';
                                $cycle_period      =   '1';
                                $recurring         =   true;
                                break;
                            case 'quarterly':
                                $cycle              =   'M';
                                $cycle_period       =   '3';
                                $recurring          =   true;
                                break;
                            case 'halfyearly':
                                $cycle             =   'M';
                                $cycle_period      =   '6';
                                $recurring         =   true;
                                break;
                            case 'yearly':
                                $cycle             =   'Y';
                                $cycle_period      = '1';
                                $recurring         = true;
                                break;
                            default:
                                $cycle       =   'D';
                                $cycle_period =   '1';
                                $recurring   =   true;
                        }
                    }
                }
            }


            if($payment_method == "stripe"){
                $this->redirect(array('controller' => 'stripe', 'action' => 'checkout'));
            }

           

            $this->set(compact('newsroom_signup', 'total_amount', 'user_id', 'first_name', 'last_name', 'cycle', "cycle_period", "recurring", 'plan_id', 'cartSessionId'));
        } catch (Exception $exc) {

            $flag = "false";

            $message = $exc->getMessage();
        }

        if ($flag == "false") {

            $this->Session->setFlash(__($message), 'error');

            $this->redirect(array('controller' => 'plans', 'action' => 'online-distribution'));
        }
    }

    /*public function payment() {
        $amount = 5000; // Amount in cents ($50.00)
        $currency = 'usd';
        $successUrl = Router::url('/payments/success', true);
        $cancelUrl = Router::url('/payments/cancel', true);

        $session = $this->Stripe->createCheckoutSession($amount, $currency, $successUrl, $cancelUrl);

        if (is_string($session)) {
            $this->Session->setFlash(__('Error creating Stripe session: ' . $session));
        } else {
            $this->Session->setFlash(__($message), 'error');

            $this->redirect(array('controller' => 'plans', 'action' => 'online-distribution'));
        }
    } */

    public function paymentsuccess()
    {
        $cartSessionId = "";
        $transactionData=[];
        $this->set('title_for_layout', 'Payment Successfully Completed');
        $transactions = $this->request->query;

       

        $user_id = $this->Auth->user('id');
        
        // if(!empty($transactions['crt'])){
        //     // Getting Transaction by cart 
        //     $transactionData = $this->Transaction->find('first', array('conditions' => array('Transaction.cart_session_id' => $transactions['crt'])));
        // }
        
        
         
        $isFirstTxn = $this->Transaction->find('first', array('conditions' => array('Transaction.staff_user_id' => $user_id)));

      
        if (!empty($transactions) && empty($transactionData)) {
            
            if (isset($this->request->query['cm']) && !empty($this->request->query['cm'])) {
                $userPlanCartIds = explode(",", $this->request->query['cm']); //0=>User id,1=>Plan id,2=> Cart ID
                $userId = (isset($userPlanCartIds[0]) && !empty($userPlanCartIds[0])) ? $userPlanCartIds[0] : "";
                $planId = (isset($userPlanCartIds[1]) && !empty($userPlanCartIds[1])) ? $userPlanCartIds[1] : "";
                $cartSessionId = (isset($userPlanCartIds[2]) && !empty($userPlanCartIds[2])) ? $userPlanCartIds[2] : "";
            }
            $coupon_data = '';
            if ($this->Session->check('FrontCart.coupon')) {
                $couponId = $this->Session->read('FrontCart.coupon.coupon_id');
                $coupon_data = $this->Coupon->find('first', array('conditions' => array('Coupon.id' => $couponId), 'fields' => array('value', 'type', 'code', 'id')));
            }
            
            $newsroom_signup = $this->Custom->get_save_transaction_formateddata($user_id, $cartSessionId, $coupon_data);
            if (isset($this->request->query['tx']) && !empty($this->request->query['tx'])) {
                // echo 1;
                unset($_SESSION['cart_session_id']);
                $this->save_transactions($transactions, $user_id, $newsroom_signup, $cartSessionId);
                $transactionData = $this->Transaction->find('first', array('conditions' => array('tx_id' => $transactions['tx'])));
                $pr_plan_paid = $transactionData['StaffUser']['pr_plan_paid'];
              $transactionData["Transaction"]['subtotal'] =$transactionData["Transaction"]['total'];
              //  $newsroomcount = $this->Company->find('count', array('conditions' => array('Company.staff_user_id' => $user_id)));
                $this->Session->write('Auth.User.pr_plan_paid', $pr_plan_paid);
                $email = $this->Auth->user('email');
               $leadId = $this->Crm->searchRecord($email);
                if($leadId && empty($isFirstTxn)){
                    $response = $this->Crm->convertLeadToContact($leadId);
                    //die($response);
                }
                $this->set(compact('transactionData'));
            }

          
        } else {
            $this->set(compact('transactionData'));
           

            // mail testing 
        //     $txId='231';
        //     $uName="Hitesh";
        //     $mailTo="testdevlopertest123@gmail.com";
        //     $this->Transaction->recursive = 2;
        //     $transactionsDetail = $this->Transaction->read(null, $txId);
        //     $html = $this->Custom->getPlanInvoiceHtmlForMail($transactionsDetail);
        //  echo $html; 
        //      $emailTemplate = $this->EmailTemplate->findByAlias('payment-invoice');
            
        //     $this->Custom->send_invoice_mail($html, $emailTemplate['EmailTemplate'], $uName, $mailTo);
        //      die;
            // $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));

        } 
        
        
    }


    private function save_transactions($transaction, $user_id, $sess_data, $cartSessionId = "")
    {
       echo $pressreleaseid = $this->Session->read('pr_press_releaseid');
        $selectedplan = $this->Session->read('pr_selectedplan');
        
        
        $this->PressRelease->id = $pressreleaseid;
        $savedata['PressRelease']['status'] = '0';
        $this->PressRelease->save($savedata);
        
        
        $cartdata = $this->Custom->getprcartdata($user_id, $selectedplan, $pressreleaseid); 
        $metadata = [];
        if(isset($cartdata["feature"]) && !empty($cartdata["feature"])){
            $metadata["press_release_id"] = $pressreleaseid;
            $metadata["features"] = $cartdata["feature"];
            
        }
        
        
        $count = $this->Transaction->find('count', array('conditions' => array('tx_id' => $transaction['tx'])));
        if ($count == 0) {
            $pr_plan_paid = $this->Auth->user('pr_plan_paid');
            $errorString = "";
            $status = isset($transaction['st']) ? $transaction['st'] : "";
            $company_id = (isset($sess_data['Transaction']["company_id"]) && !empty($sess_data['Transaction']["company_id"])) ? $sess_data['Transaction']["company_id"] : $this->Session->read('ClientUserSaved.company_id');
            $data_array = array();
            $data_array['Transaction']['staff_user_id'] = $user_id;
            $data_array['Transaction']['tx_id'] = $transaction['tx'];
            $data_array['Transaction']['cart_session_id'] = (!empty($cartSessionId))?$cartSessionId:null;
            $data_array['Transaction']['paymant_date'] = date('Y-m-d H:i:s');
            $data_array['Transaction']['currency'] = $transaction['cc'];
            $data_array['Transaction']['discount_id'] = isset($sess_data['Transaction']['discount_id']) ? $sess_data['Transaction']['discount_id'] : null;
            $data_array['Transaction']['newsroom_amount'] = $sess_data['Transaction']['newsroom_amount'];
            $data_array['Transaction']['subtotal'] = $sess_data['Transaction']['subtotal'];
            $data_array['Transaction']['discount'] = $sess_data['Transaction']['discount'];
            $data_array['Transaction']['tax'] = $sess_data['Transaction']['tax'];
            $data_array['Transaction']['total'] = $transaction['amt'];
            $data_array['Transaction']['status'] = (strtolower($status) == 'completed') ? "Success" : $status;
            $data_array['Transaction']['cart_type'] = "";  // not found in paypal
            
            $data_array['Transaction']['error_message'] ="";
            $data_array['Transaction']['metadata'] =  json_encode($metadata);
            $data_array['Transaction']['company_id'] = $company_id;
            $data_array['Transaction']['is_plan_newsroom'] = (!empty($sess_data['TransactionPlan']) && $sess_data['Transaction']['newsroom_amount'] > 0) ? "1" : "0";
            $this->loadModel('Transaction');
            if ($this->Transaction->save($data_array)) {
                //pr($sess_data['TransactionPlan']);die;
                $txId = $this->Transaction->getLastInsertID();

                $this->Transaction->id = $txId;
                $this->Transaction->saveField('invoice_no', 10010 + $txId - 902);
                
                if (isset($sess_data['TransactionPlan']) && !empty($sess_data['TransactionPlan'])) {
                    $this->loadModel('TransactionPlan');
                    $this->loadModel('RemainingUserPlan');
                    $remaingPRArr = [];
                    foreach ($sess_data['TransactionPlan'] as $index => $sess_data) {
                        $newdataArr[$index]['transaction_id'] = $txId;
                        $newdataArr[$index]['plan_id'] = $sess_data['plan_id'];
                        
                        $newdataArr[$index]['plan_amount'] = $sess_data['plan_amount'];
                        $data_array['TransactionPlan'][$index]['title'] = $sess_data['title'];
                        $data_array['TransactionPlan'][$index]['plan_id'] = $sess_data['plan_id'];
                        $data_array['TransactionPlan'][$index]['plan_amount'] = $sess_data['plan_amount'];
                        $number_pr = $this->Custom->getprnumber($sess_data['plan_id']);
                        $remaingPRArr[$index]['transaction_id'] = $txId;
                        $remaingPRArr[$index]['staff_user_id'] = $user_id;
                        $remaingPRArr[$index]['number_pr'] = $number_pr;
                        $remaingPRArr[$index]['plan_id'] = $sess_data['plan_id'];
                        $previousplan = $this->RemainingUserPlan->find('first', array('fields' => ['RemainingUserPlan.id', 'RemainingUserPlan.number_pr'], 'conditions' => ['staff_user_id' => $user_id, 'plan_id' => $sess_data['plan_id']]));
                        if (!empty($previousplan)) {
                            $remaingPRArr[$index]['id'] = $previousplan['RemainingUserPlan']['id'];
                            $remaingPRArr[$index]['number_pr'] = ($previousplan['RemainingUserPlan']['number_pr'] + $number_pr);
                       }
                   }
                    $this->TransactionPlan->saveMany($newdataArr, array('deep' => true));
                    $this->RemainingUserPlan->saveMany($remaingPRArr, array('deep' => true));
                    $pr_plan_paid = (empty($errorString)) ? "1" : "0";
               }
                $this->StaffUser->id = $user_id;
                $this->StaffUser->saveField('pr_plan_paid', $pr_plan_paid);
                $this->Session->write('Auth.User.pr_plan_paid', $pr_plan_paid);
                $this->Session->delete('FrontCart.coupon');
                $this->Session->delete('ClientUser.signup');
                if (empty($errorString)) {
                    $this->Session->delete("ew_cartdata");
                    if (!empty($cartSessionId)) {
                        $this->Cart->deleteAll(array('Cart.staff_user_id' => $user_id, 'Cart.cart_session_id' => $cartSessionId, 'cart_type' => 'plan'), false);
                   }
                    $uName = $this->Auth->user('first_name');
                    $mailTo = $this->Auth->user('email');
                    $newsroomcount = $this->Company->find('count',array('conditions' => array('Company.staff_user_id' => $user_id)
                    ));
                    $is_plan_paid = $this->Auth->user('pr_plan_paid');
                    // $pressReleaseCount = $this->PressRelease->find('count', array('conditions' => array("PressRelease.staff_user_id" => $user_id)));
                    if ($company_id != 0 && $data_array['Transaction']['status'] == 'success') {
                        $this->removeNewsroomCookie();
                        $csavedate['Company']['id'] = $company_id;
                        $csavedate['Company']['payment_status'] = '1';
                        $csavedate['Company']['staff_user_id'] = $user_id;
                        $user_email = $this->Auth->user('email');
                        $this->Custom->send_mailOnNewsroomCreation($company_id, $user_email);
                        $this->Company->save($csavedate);
                    }
                    if (!empty($this->Session->read('ClientUserSaved.company_id'))) {
                        $this->Session->delete('ClientUserSaved.company_id');
                    }
                    $this->Transaction->recursive = 2;
                    $transactionsDetail = $this->Transaction->read(null, $txId);
                    $html = $this->Custom->getPlanInvoiceHtmlForMail($transactionsDetail);
                    $emailTemplate = $this->EmailTemplate->findByAlias('payment-invoice');
                    
                    $this->Custom->send_invoice_mail($html, $emailTemplate['EmailTemplate'], $uName, $mailTo);
                    if ($is_plan_paid == 1) {
                        if ($newsroomcount > 0) {
                            // $this->redirect(array('controller' => 'users', 'action' => 'add-press-release'));
                        }
                    } else {
                        //$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                    } 
                    if ($newsroomcount == 0) {
                        //$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));

                    }
                } else {
                    $this->Session->setFlash($errorString, 'error');
                    $this->redirect(array('controller' => 'users', 'action' => 'payment/?err=' . $errorString));
                }
            }
        } else {
            //  $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
    }



    public function paymentcancel($value = '')
    {

        $this->layout = 'site_default';

        $this->set('title_for_layout', 'Payment Failed');
    }

    public function removeNewsroomCookie()
    {



        $this->Cookie->delete('nr_first_name');

        $this->Cookie->delete('nr_last_name');

        $this->Cookie->delete('nr_email');

        $this->Cookie->delete('nr_confirm_email');

        $this->Cookie->delete('nr_contact_name');

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



    public function sendmail_aftersignup()
    {

        $data = $this->Auth->user();

        $email = $data['email'];

        $key = Security::hash(CakeText::uuid(), 'sha1', true);

        $hash = sha1($data['email'] . rand(0, 20));

        $url = Router::url(array('controller' => 'users', 'action' => 'activate'), true) . '/' . $key . '#' . $hash;

        $ms = $url;

        $ms = wordwrap($ms, 1000);



        $fu['StaffUser']['tokenhash'] = $key;

        $this->StaffUser->id = $data['id'];



        if ($this->StaffUser->saveField('tokenhash', $fu['StaffUser']['tokenhash'])) {

            $email = $this->EmailTemplate->selectTemplate('user-signup');

            $emailFindReplace = array(

                '##NAME##' => $data['first_name'],

                '##SITE_NAME##' => $this->siteName,

                '##SUPPORT_EMAIL##' => Configure::read('Site.support_email'),

                '##ACCOUNT_ACTIVATE_LINK##' => $url,

                '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),

                '##FROM_EMAIL##' => $this->StaffUser->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']),

                '##SITE_LOGO##' => Router::url(array(

                    'controller' => 'img',

                    'action' => '/',

                    'logo.png',

                    'admin' => false

                ), true)

            );





            $this->AWSSES->from = $email['title'] . " <" . $email['from'] . ">";

            $this->AWSSES->to =  $this->data['StaffUser']['email'];

            $this->AWSSES->subject = $email['subject'];

            $this->AWSSES->replayto = $email['reply_to_email'];

            $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

            if (!$this->AWSSES->_aws_ses()) {

                $this->Email->from = $email['title'] . " <" . $email['from'] . ">";

                $this->Email->replyTo = ($email['reply_to_email'] == '##REPLY_TO_EMAIL##') ? $this->data['StaffUser']['email'] : $email['reply_to_email'];

                $this->Email->to =  $data['email'];

                $this->Email->subject = strtr($email['subject'], $emailFindReplace);

                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';

                $description = strtr($email['description'], $emailFindReplace);

                $this->Email->send($description);
            }

            return true;
        }
    }

    public function sendmail_afterpayment()
    {

        $data = $this->Auth->user();

        $email = $data['email'];

        $key = Security::hash(CakeText::uuid(), 'sha1', true);

        $hash = sha1($data['email'] . rand(0, 20));

        $url = Router::url(array('controller' => 'users', 'action' => 'activate'), true) . '/' . $key . '#' . $hash;

        $ms = $url;

        $ms = wordwrap($ms, 1000);



        $fu['StaffUser']['tokenhash'] = $key;

        $this->StaffUser->id = $data['id'];



        if ($this->StaffUser->saveField('tokenhash', $fu['StaffUser']['tokenhash'])) {

            $email = $this->EmailTemplate->selectTemplate('user-signup');

            $emailFindReplace = array(

                '##NAME##' => $data['first_name'],

                '##SITE_NAME##' => $this->siteName,

                '##SUPPORT_EMAIL##' => Configure::read('Site.support_email'),

                '##ACCOUNT_ACTIVATE_LINK##' => $url,

                '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),

                '##FROM_EMAIL##' => $this->StaffUser->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']),

                '##SITE_LOGO##' => Router::url(array(

                    'controller' => 'img',

                    'action' => '/',

                    'logo.png',

                    'admin' => false

                ), true)

            );



            $this->AWSSES->from = $email['title'] . " <" . $email['from'] . ">";

            $this->AWSSES->to =  $this->data['StaffUser']['email'];

            $this->AWSSES->subject = $email['subject'];

            $this->AWSSES->replayto = $email['reply_to_email'];

            $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

            if (!$this->AWSSES->_aws_ses()) {

                $this->Email->from = $email['title'] . " <" . $email['from'] . ">";

                $this->Email->replyTo = $email['reply_to_email'];

                $this->Email->to = $data['email'];

                $this->Email->subject = strtr($email['subject'], $emailFindReplace);

                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';

                $description = strtr($email['description'], $emailFindReplace);

                $this->Email->send($description);
            }

            return true;
        }
    }
    public function signup()
    {

        $this->layout = 'site_default';

        $this->set('title_for_layout', 'Signup');

        if (!empty($this->data)) {

            $this->request->data['StaffUser']['status'] = 1;

            $this->request->data['StaffUser']['staff_role_id'] = 3;

            // unset($this->request->data['Company']['logo']);

            if ($this->StaffUser->save($this->request->data)) {

                $email = $this->data['StaffUser']['email'];

                $key = Security::hash(CakeText::uuid(), 'sha1', true);

                $hash = sha1($this->data['StaffUser']['email'] . rand(0, 20));

                $url = Router::url(array('controller' => 'users', 'action' => 'activate'), true) . '/' . $key . '#' . $hash;

                $ms = $url;

                $ms = wordwrap($ms, 1000);



                $fu['StaffUser']['tokenhash'] = $key;

                $this->StaffUser->id = $this->StaffUser->getLastInsertID();

                if ($this->StaffUser->saveField('tokenhash', $fu['StaffUser']['tokenhash'])) {

                    $userData = $this->StaffUser->find('first', array('conditions' => array("StaffUser.id" => $this->StaffUser->id)));

                    $this->Auth->login($userData['StaffUser']);



                    if ($this->Session->check("ew_cartdata")) {

                        $cartData = $this->Session->read("ew_cartdata");

                        $newsroomAssign = ($cartData['newsroom_amount'] > 0) ? '1' : "0";

                        $cart_plans = $this->Custom->addToCartWithDb($cartData, '', $userData['StaffUser']['id'], $newsroomAssign);

                        $this->Session->delete("ew_cartdata");
                    }

                    $this->Session->write('ClientUser.signup', 'frontend');

                    $this->userAssignSendyTable($userData['StaffUser']['id'], $email, $userData['StaffUser']['first_name']);
                    
                     $crmData = [
                    "data" => [
                        [
                            "First_Name" => $userData['StaffUser']['first_name'],
                            "Last_Name" => $userData['StaffUser']['last_name'],
                            "Email" => $userData['StaffUser']['email'],
                            "Phone" => $userData['StaffUser']['phone']
                            
                        ]
                    ]
                ];
                
                // die(json_encode($crmData));
    
                // Send data to CRM
                $response = $this->Crm->createRecord($crmData);

                    $this->sendmail_aftersignup();

                    if (isset($this->data['StaffUser']['redirect']) && !empty($this->data['StaffUser']['redirect'])) {

                        $this->redirect(SITEURL . $this->data['StaffUser']['redirect']);

                        // $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));

                    } else {

                        $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                    }

                    //============EndEmail=============//

                }
            } else {

                $this->Session->destroy();

                $this->Session->setFlash(__('There is some error in below form. Please see red message.'), 'error');
            }
        }

        $this->loadModel('Company');

        $this->loadModel('OrganizationType');

        $this->set('organization_list', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1))));

        $this->loadModel('Country');

        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));
    }

    public function user_password()
    {

        $this->set('title_for_layout', __('Change password'));

        $id = $this->Auth->user('id');



        if (!$id && empty($this->request->data)) {

            $this->Session->setFlash(__('Invalid User'), 'error');
        }

        if (!empty($this->request->data)) {

            $this->request->data['StaffUser']['id'] = $id;



            if ($this->StaffUser->save($this->request->data)) {

                $this->Session->destroy();

                $this->Session->setFlash(__('Your password has changed successfully.  Please log in again.'), 'success');



                $this->redirect($this->Auth->logout());

                //$this->redirect(array('action' => 'user_password'));

            } else {

                $this->Session->setFlash(__('Password could not be changed'), 'error');
            }

            // pr($this->request->data); die;

        }
    }

    /*

     * reset method

     * use to reset password using password reset link

     */

    public function activate($token = null)
    {

        $this->layout = 'login';

        $this->set('title_for_layout', $this->siteName  . ' :: ' . __('Activate account'));

        $this->StaffUser->recursive = -1;

        if (!empty($token)) {

            $u = $this->StaffUser->findBytokenhash($token);

            if ($u) {

                $new_hash = sha1($u['StaffUser']['email'] . rand(0, 20)); //created token

                $this->request->data['StaffUser']['id'] = $u['StaffUser']['id'];

                $this->request->data['StaffUser']['tokenhash'] = $new_hash;

                $this->request->data['StaffUser']['email_confirmed'] = 1;

                if ($u['StaffUser']['staff_role_id'] != 3) {

                    $this->request->data['StaffUser']['newsletter_send_mail_date'] = $this->Custom->get_newsletter_sendmail_date($u['StaffUser']['id']);
                } else {

                    $this->request->data['StaffUser']['newsletter_cycle'] = null;
                }

                if ($this->StaffUser->save($this->request->data)) {

                    $this->Session->setFlash('You have successfully activated your account.', 'success');

                    if ($this->Auth->loggedIn()) {

                        $this->Session->delete('Auth.User.email_confirmed');

                        $this->Session->write('Auth.User.email_confirmed', '1');

                        if ($this->Auth->user('pr_plan_paid') != 0) {

                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                        } else {

                            $this->redirect('/plans/online-distribution/');
                        }
                    } else {

                        $this->redirect(array('controller' => 'users', 'action' => 'login'));
                    }
                }
            } else {

                $this->Session->setFlash(__('The account actvation link has either expired or was used, please request a new link.'), 'error');

                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        } else {

            $this->redirect('/');
        }
    }

    public function resend_activation()
    {
        $user_email = $this->Auth->user('email');

        $first_name = $this->Auth->user('first_name');

        $key = Security::hash(CakeText::uuid(), 'sha1', true);

        $hash = sha1($user_email . rand(0, 20));

        $url = Router::url(array('controller' => 'users', 'action' => 'activate'), true) . '/' . $key . '#' . $hash;

        $ms = $url;

        $ms = wordwrap($ms, 1000);

        $fu['StaffUser']['tokenhash'] = $key;

        $this->StaffUser->id = $this->Auth->user('id');

        if ($this->StaffUser->saveField('tokenhash', $fu['StaffUser']['tokenhash'])) {

            $email = $this->EmailTemplate->selectTemplate('resend-activation');

            $emailFindReplace = array(

                '##NAME##' => $first_name,

                '##SITE_NAME##' => $this->siteName,

                '##ACCOUNT_ACTIVATE_LINK##' => $url,

                '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),

                '##FROM_EMAIL##' => $this->StaffUser->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']),

                '##SITE_LOGO##' => Router::url(array(

                    'controller' => 'img',

                    'action' => '/',

                    'logo.png',

                    'admin' => false

                ), true)

            );

            $this->AWSSES->from = $email['title'] . " <" . $email['from'] . ">";

            $this->AWSSES->to = trim($user_email);

            $this->AWSSES->subject = strtr($email['subject'], $emailFindReplace);

            $this->AWSSES->replayto = $email['reply_to_email'];

            $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

            if (!$this->AWSSES->_aws_ses()) {

                App::uses('CakeEmail', 'Network/Email'); 
                $Email = new CakeEmail('smtp');
                $Email->from(array($email['from'] => $email['title'] . " <" . $email['from'] . ">")); 
                $Email->to($user_email); 
                $Email->replyTo($email['reply_to_email']); 
                $Email->subject(strtr($email['subject'], $emailFindReplace)); 
                $Email->emailFormat('html'); 
                $description = strtr($email['description'], $emailFindReplace);
                $Email->send($description);


                // $this->Email->from = $email['title'] . " <" . $email['from'] . ">";

                // $this->Email->replyTo = $email['reply_to_email'];

                // $this->Email->to = $user_email;

                // $this->Email->subject = strtr($email['subject'], $emailFindReplace);

                // $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';

                // $description = strtr($email['description'], $emailFindReplace);

                // $this->Email->send($description);
            }

            $this->Session->setFlash(__('An email has been sent to your email id. Please activate your email account following the activation link'), 'success');

            $this->redirect($this->referer());
        }
    }

    public function forgot()
    {

        $this->layout = 'login';

        $this->set('title_for_layout', $this->siteName . ' :: ' . __('Forgot password'));

        $this->StaffUser->recursive = -1;

        $this->loadModel('EmailTemplate');

        $this->StaffUser->set($this->request->data);

        if (!empty($this->data)) {

            if (empty($this->data['StaffUser']['email'])) {

                $this->Session->setFlash(__('Please provide your email address that was used to register with us.'), 'error');
            } else {

                $email = $this->data['StaffUser']['email'];

                $fu = $this->StaffUser->find('first', array('conditions' => array('StaffUser.email' => $email)));

                if ($fu) {

                    if ($fu['StaffUser']['status']) {

                        App::uses('String', 'Utility');

                        $key = Security::hash(CakeText::uuid(), 'sha1', true);

                        $hash = sha1($fu['StaffUser']['email'] . rand(0, 20));

                        $url = Router::url(array('controller' => 'users', 'action' => 'reset'), true) . '/' . $key . '#' . $hash;

                        $ms = $url;

                        $ms = wordwrap($ms, 1000);



                        $fu['StaffUser']['tokenhash'] = $key;

                        $this->StaffUser->id = $fu['StaffUser']['id'];

                        $email = $this->EmailTemplate->selectTemplate('forgot_password');



                        if ($this->StaffUser->saveField('tokenhash', $fu['StaffUser']['tokenhash'])) {

                            $emailFindReplace = array(

                                '##NAME##' => ucfirst($fu['StaffUser']['first_name']) . " " . $fu['StaffUser']['last_name'],

                                '##SITE_NAME##' => $this->siteName,

                                '##PASSWORD_RESET_LINK##' => $url,

                                '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),

                            );



                            $this->AWSSES->from = $email['title'] . " <" . $email['from'] . ">";

                            $this->AWSSES->to =  $this->data['StaffUser']['email'];

                            $this->AWSSES->subject = $email['subject'];

                            $this->AWSSES->replayto = $email['reply_to_email'];

                            $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

                            if (!$this->AWSSES->_aws_ses()) {

                                App::uses('CakeEmail', 'Network/Email');
                                $Email = new CakeEmail('default');

                                $Email->from(array('emailwireweb@gmail.com' => $email['subject']));
                                $Email->to($this->data['StaffUser']['email']);
                                $Email->replyTo($email['reply_to_email']);
                                $Email->subject($email['subject']);
                                $Email->emailFormat('html');
                                $Email->send(strtr($email['description'], $emailFindReplace));
                                try {
                                    if (mail($this->data['StaffUser']['email'], 'Test Subject', 'Test Message'))
                                        echo 'Success';
                                    else echo 'Failure without Exception';
                                } catch (Exception $e) {
                                    echo 'Failure with Exception';
                                }
                            }
                            $this->Session->setFlash(__('Check your email to reset your password.'), 'success');

                            $this->redirect(array('controller' => 'users', 'action' => 'login'));
                            //============EndEmail=============//
                        } else {
                            $this->Session->setFlash(__('Error generating reset link. Please, try again.'), 'error');
                        }
                    } else {
                        $this->Session->setFlash(__('This account has not been activated yet. Please check your mail to follow the links.'), 'error');
                    }
                } else {

                    $this->Session->setFlash(__('The email you entered does not exist on our system.'), 'error');
                }
            }
        }
    }

    public function logout()
    {

        $this->response->disableCache();

        $this->Session->setFlash(__('Log out successfull'), 'success');

        $this->Session->destroy();

        clearCache();

        $this->removeNewsroomCookie();

        $this->redirect(array('controller' => 'users', 'action' => 'login'));

        //$this->redirect($this->Auth->logout());

    }

    private function __save_ip()
    {

        $this->loadModel('IpLog');

        $ip_log = array();

        $ip_log['IpLog']['ip'] = $_SERVER['REMOTE_ADDR'];

        $ip_log['IpLog']['staff_user_id'] = $this->Auth->user('id');

        $this->IpLog->save($ip_log['IpLog']);
    }

    public function reset($token = null)
    {

        $this->layout = 'login';

        $this->set('title_for_layout', $this->siteName . ' :: ' . __('Reset password'));

        $this->StaffUser->recursive = -1;

        if (!empty($token)) {

            $u = $this->StaffUser->findBytokenhash($token);


            if ($u) {

                if (!empty($this->data)) {

                    $this->request->data['StaffUser']['id'] = $u['StaffUser']['id'];

                    $this->request->data['StaffUser']['email'] = $u['StaffUser']['email'];

                    $new_hash = sha1($u['StaffUser']['email'] . rand(0, 20)); //created token

                    $this->request->data['StaffUser']['tokenhash'] = $new_hash;

                    if ($this->StaffUser->validates(array('fieldList' => array('password', 'verify_password')))) {
                        if ($this->StaffUser->save($this->request->data)) {
                            $this->Session->setFlash('The password has been updated.', 'success');
                            return $this->redirect(array('controller' => 'users', 'action' => 'login'));
                        }
                    } else {
                        // debug($this->StaffUser->invalidFields()); die;
                    }
                }
            } else {

                $this->Session->setFlash(__('The forgotten password link has either expired or was used, please request a new password.'), 'error');
            }
        } else {
            $this->Session->setFlash(__('The forgotten password link has either expired or was used, please request a new password.'), 'error');
            $this->redirect('/');
        }
    }

    public function change_password()
    {

        $this->set('title_for_layout', __('Change password'));

        $id = $this->Auth->user('id');

        if (!$id && empty($this->request->data)) {

            $this->Session->setFlash(__('Invalid StaffUser'), 'error');
        }

        if (!empty($this->request->data)) {

            $this->request->data['StaffUser']['id'] = $id;

            if ($this->StaffUser->save($this->request->data)) {

                $this->Session->setFlash(__('Password changed successfully'), 'success');

                $this->Session->destroy();

                $this->redirect($this->Auth->logout());

                //$this->redirect(array('action' => 'change_password'));

            } else {

                $this->Session->setFlash(__('Password could not be changed'), 'error');
            }
        }
    }

    public function deleteProfileImage($id)
    {

        $this->StaffUser->id = $id;

        if (!$this->StaffUser->exists()) {

            throw new NotFoundException(__('Invalid user'));
        }

        $this->StaffUser->recursive = -1;

        $data = $this->StaffUser->read(array('StaffUser.profile_image'), $id);

        App::uses('File', 'Utility');

        $file = new File(WWW_ROOT . 'files/profile_images/' . $data['StaffUser']['profile_image'], false, 0777);

        try {

            $file->delete();

            $this->StaffUser->saveField('profile_image', '');

            $this->viewPath = 'Elements';

            $this->render('no_image');
        } catch (Exception $exc) {

            echo $exc->getTraceAsString();
        }



        $this->autoRender = false;
    }

    public function create_new_company()
    {

        App::uses('File', 'Utility');

        App::uses('Folder', 'Utility');

        $this->set('organization_list', $this->OrganizationType->find('list', array('conditions' => array('OrganizationType.status' => 1))));

        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));

        if (!empty($this->data)) {

            $this->Company->set($this->request->data);

            $this->request->data['Company']['staff_user_id'] = $this->Auth->user('id');

            if ($this->data['Company']['logo'] != '' && !empty($this->data['Company']['logo']['name'])) {

                $date = date('Y') . DS . date('m');

                $file_path = WWW_ROOT . 'files' . DS . 'company' . DS . 'logo' . DS . $date;

                $dir = new Folder($file_path, true, 0755);

                $logo_name = uniqid() . ".png";

                if (move_uploaded_file($this->data['Company']['logo']['tmp_name'], $file_path . DS . $logo_name)) {

                    unset($this->request->data['Company']['logo']);

                    $this->request->data['Company']['logo_path'] = date('Y') . "/" . date('m');

                    $this->request->data['Company']['logo'] = $logo_name;
                }
            }

            if ($this->data['Company']['banner_image'] != '' && !empty($this->data['Company']['banner_image']['name'])) {

                $date = date('Y') . DS . date('m');

                $file_path = WWW_ROOT . 'files' . DS . 'company' . DS . 'banner' . DS . $date;

                $dir = new Folder($file_path, true, 0755);

                $banner_image = uniqid() . ".png";

                if (move_uploaded_file($this->data['Company']['banner_image']['tmp_name'], $file_path . DS . $banner_image)) {

                    unset($this->request->data['Company']['banner_image']);

                    $this->request->data['Company']['banner_path'] = date('Y') . "/" . date('m');

                    $this->request->data['Company']['banner_image'] = $banner_image;
                }
            }

            if ($this->Company->save($this->request->data)) {

                $this->set('csaved', '1');

                //$this->redirect(FRONTURL.'/add-new-company?s=1');

            }
        }
    }

    public function submit_release($selectedLang = 'en', $selectedplan = '', $id = null, $action = '3')
    {
        
        
        $languages = $allStates = $allCountries = $allMsas = $country_list = $msa_list = $state_list = [];

        $currencySymbol = Configure::read('Site.currency');
        $this->set('title_for_layout', __('Add a new Press Release'));
        $this->set('model', 'PressRelease');
        $this->loadModel('MsasPlanCategory');
        $this->loadModel('CountriesPlanCategory');
        $this->loadModel('PlanCategoriesState');
        $is_plan_paid = $this->Auth->user('pr_plan_paid');
        $user_id = $this->Auth->user('id');
        if ($id == null && $this->request->is('get') && !empty($selectedplan)) {
            
            $cart_plans = $this->Custom->checkCartbeforeSubmitPr($user_id, $selectedplan);
          
            if (!empty($cart_plans) && empty($id)) {
                $this->Cart->delete($cart_plans['Cart']['id']);
            }
        }
        if (!empty($this->data)) {
            $this->loadModel('PressSeo');
            $this->loadModel('PressYoutube');
            $this->loadModel('PressPoadcast');
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

            $cart_plans = $this->Custom->checkprcart($user_id, $selectedplan);

            $status = '3';

            $fmessage = "PR save successfully in draft";

            if ($this->data['PressRelease']['submittype'] == "submitwithoutpayment") {

                $status = '0';

                $fmessage = "PR successfully submitted for review. Payment is pending. Please make payment as soon as possible to get PR approved.";
            } else if ($this->data['PressRelease']['submittype'] == "paypal" || $this->data['PressRelease']['submittype'] == "stripe") {
                $status = '0';
                $fmessage = "PR successfully submitted for review.";
            } else if ($this->data['PressRelease']['submittype'] == "preview") {

                $status = '3';

                $fmessage = 'Please scroll and review your press release and submit it for approval, otherwise click on  "Go back edit PR" button to edit press release.';
            }

            $this->request->data['PressRelease']['status'] = $status;



            $press_image = array_values($this->request->data['PressImage']);

            foreach ($press_image as $index => $image) {
                if ($image['image_name'] == '') {
                    unset($this->request->data['PressImage'][$index]);
                }
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
            if (count($this->request->data['PressImage']) == 0) {
                unset($this->request->data['PressImage']);
            }
            $press_seo = array_values($this->request->data['PressSeo']);
            unset($this->request->data['PressSeo']);
            foreach ($press_seo as $key => $seo) {
                if (!empty($seo['keyword'])) {
                    if (isset($this->data['PressRelease']['id']) && !empty($this->data['PressRelease']['id'])) {
                        $this->request->data['PressSeo'][$key]['press_release_id'] = $this->data['PressRelease']['id'];
                    }
                    $this->request->data['PressSeo'][$key]['keyword'] = $seo['keyword'];
                    $this->request->data['PressSeo'][$key]['slug'] = strtolower(Inflector::slug($seo['keyword'], '-'));
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
            foreach ($press_youtubes as $key => $youtubes) {
                if (!empty($youtubes['url'])) {
                    if (isset($this->data['PressRelease']['id']) && !empty($this->data['PressRelease']['id'])) {
                        $this->request->data['PressYoutube'][$key]['press_release_id'] = $this->data['PressRelease']['id'];
                    }
                    $this->request->data['PressYoutube'][$key]['url'] = $youtubes['url'];
                    $this->request->data['PressYoutube'][$key]['description'] = $youtubes['description'];
                    if (isset($youtubes['id']))
                        $this->request->data['PressYoutube'][$key]['id'] = $youtubes['id'];
                } else {
                    if (isset($youtubes['id']) && empty($youtubes['url'])) {
                        $this->PressYoutube->id = $youtubes['id'];
                        if ($this->PressYoutube->exists()) {
                            $this->PressYoutube->delete();
                        }
                    }
                }
            }
            $press_podcasts = array_values($this->request->data['PressPoadcast']);
            unset($this->request->data['PressPoadcast']);
            foreach ($press_podcasts as $key => $podcast) {

                if (!empty($podcast['url'])) {

                    if (isset($this->data['PressRelease']['id']) && !empty($this->data['PressRelease']['id'])) {

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
            $newDate = DateTime::createFromFormat("m-d-Y",$this->request->data['PressRelease']['release_date'])->format("Y-m-d");
            $this->request->data['PressRelease']['release_date'] = $newDate;
            $this->request->data['PressRelease']['staff_user_id'] = $this->Auth->user('id');
            $this->request->data['PressRelease']['is_paid'] = "0";
            if (!empty($cart_plans)) {

                $featureD = unserialize($cart_plans['Cart']['distribution_ids']);

                if ((!empty($cart_plans['Cart']['is_newsroom_incart']) || $cart_plans['Cart']['extra_words'] > 0 || $cart_plans['Cart']['extra_category'] > 0 || $cart_plans['Cart']['extra_msa'] > 0 || $cart_plans['Cart']['extra_state'] > 0 || !empty($cart_plans['Cart']['translate_charges']) || !empty($featureD))) {
                    $this->request->data['PressRelease']['is_paid'] = "1";
                }
            }

            $this->request->data['PressRelease']['language_id'] = $this->Custom->getLanguageIdByCode($this->data['PressRelease']['language']);
            if ($this->PressRelease->saveAll($this->request->data, array('deep' => true))) {
                if (isset($this->request->data['PressRelease']['id']) && !empty($this->request->data['PressRelease']['id'])) {
                    $prId = $this->data['PressRelease']['id'];
                } else {
                    $prId = $this->PressRelease->getLastInsertID();
                }
               
                if (!empty($cart_plans) && $cart_plans['Cart']['press_release_id'] == 0) {
                    $this->Cart->id = $cart_plans['Cart']['id'];
                    $this->Cart->saveField('press_release_id', $prId);
                }
                if (isset($this->request->data['Distribution']) && !empty($this->request->data['Distribution']['Distribution'])) {
                    if (in_array(8, $this->request->data['Distribution']['Distribution'])) {
                        $list_id = $this->request->data['PressRelease']['list_id'];
                        $this->setCampSchedule($prId, $list_id);
                    }
                }
                // $this->save_press_image($prId, $press_image);
                $this->Session->setFlash($fmessage, 'success');
                $this->Session->write('pr_selectedplan', $selectedplan);
                if ($this->data['PressRelease']['submittype'] == "paypal" || $this->data['PressRelease']['submittype'] == "stripe") {
                    return $this->redirect(array('controller' => 'users', 'action' => 'prpayment', $selectedplan, $prId));
                }
                if ($this->data['PressRelease']['submittype'] == "preview") {
                    return $this->redirect(array('controller' => 'users', 'action' => 'makepayment', $selectedplan, $prId));
                }
                $redStatus = $this->Custom->getPRSUrlStatus($status);
                return $this->redirect(array('action' => 'press-releases', $redStatus));
            } else {
                $this->Session->setFlash(__('PR not submitted. Please, try again.'), 'error');
            }
        }


        $pCategory_list = $this->Custom->getParentCategories();

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

        $this->Plan->recursive = 2;
        $planDetail = $this->Plan->find('first', array('conditions' => array('Plan.id' => $selectedplan)));
        $remaingPR = $this->Custom->getRemaingPR($selectedplan, $user_id);
        if ($remaingPR == 0 && !empty($selectedplan)) {
            $this->Session->setFlash(__('This plan has been expired.'), 'warning');
        }
        // $this->List->bindModel(array('belongsTo'=>['StaffUser']));
        $user_email_list = $this->List->find('list', array('conditions' => array('staff_user_id' => $user_id), 'fields' => array('List.id', 'List.name')));
        $admin_email_list = $this->List->find('list', array('conditions' => array('List.staff_user_id' => '1', 'List.list_for_client' => '1'), 'fields' => array('List.id', 'List.name')));
        $email_list['Your Own Email Lists'] = $user_email_list;
        $email_list['Target Email lists'] = $admin_email_list;


        $checknewsroompayment = $this->Custom->checkUserCompaniesPayment($this->Auth->user('id'));
        $company_list = $this->Custom->getUserCompanies($this->Auth->user('id'));
    //   print_r($company_list);exit;
        
        if ($checknewsroompayment > 0) {

            $this->Session->setFlash("Your newsroom payment pending., click here to check your ", "session_flash_link", array(
                "link_text" => "Pending Newsroom",
                "link_url" => array(
                    "controller" => "users",
                    "action" => "newsrooms", "0",
                    "admin" => false
                )
            ));
        }
        if (empty($company_list)) {
            $message = "";
            $setNewsroomStatus = 3;
            $susspedcompanies = $this->Custom->checkSuspendedCompanies($this->Auth->user('id'));
            if ($susspedcompanies > 0) {
                $setNewsroomStatus = 2;
                $label = "Suspended";

                $message = "Your newsroom has been suspended";
            } else {

                $disApprovedcompanies = $this->Custom->checkDisapprovedCompanies($this->Auth->user('id'));

                if ($disApprovedcompanies > 0) {
                    $label = "Disapproved";
                    $message = "Your newsroom has been disapproved";
                }
            }

            if ($message != "") {
                $this->Session->setFlash("$message, click here to check your ", "session_flash_link", array(
                    "link_text" => "$label Newsroom",
                    "link_url" => array(
                        "controller" => "users",
                        "action" => "newsrooms", $setNewsroomStatus,
                        "admin" => false
                    )
                ));
            }
        }

        //$this->RemainingUserPlan->virtualFields['name'] = 'CONCAT(PlanCategory.name, " (' . $currencySymbol . '", CASE WHEN  bulk_discount_amount > 0 THEN bulk_discount_amount ELSE price END ,") (",RemainingUserPlan.number_pr,")")';
        $currencySymbol = Configure::read('Site.currency');
        $plan_list = [];
        $planArr = $this->RemainingUserPlan->find('all', array(
            'joins' => array(
                array(
                    'table' => 'plans',
                    'alias' => 'Plan',
                    'type' => 'INNER',
                    'conditions' => array('Plan.id = RemainingUserPlan.plan_id')
                ),
                array(
                    'table' => 'plan_categories',
                    'alias' => 'PlanCategory',
                    'type' => 'INNER',
                    'conditions' => array('PlanCategory.id = Plan.plan_category_id')
                )
            ),
            'conditions' => array('RemainingUserPlan.staff_user_id' => $user_id, 'RemainingUserPlan.number_pr > ' => "0"),
            'fields' => array('PlanCategory.name', 'PlanCategory.slug', 'PlanCategory.word_limit', 'Plan.id', 'Plan.price', 'Plan.plan_type', 'Plan.bulk_discount_amount', 'Plan.number_pr', 'RemainingUserPlan.assign_from', 'RemainingUserPlan.number_pr', 'CONCAT(PlanCategory.name,CASE WHEN  Plan.plan_type = "single" AND word_limit > 0 THEN  " - " ELSE "" END, CASE WHEN  Plan.plan_type = "single" AND word_limit > 0 THEN word_limit ELSE "" END ,CASE WHEN  Plan.plan_type = "single" AND word_limit > 0 THEN " words " ELSE "" END) as plan_nm', 'CONCAT(PlanCategory.name, " - ' . $currencySymbol . '", CASE WHEN  bulk_discount_amount > 0 THEN bulk_discount_amount ELSE price END ," (",RemainingUserPlan.number_pr,")") as remaning_plan')
        ));

        if (!empty($planArr)) {
            foreach ($planArr as $loop => $plan) {
                $plan_list[$plan[0]["plan_nm"]][$plan["Plan"]["id"]] = $plan[0]["remaning_plan"];
            }
        } 

     

        if (empty($plan_list)) {
            $this->Session->setFlash("You do not have active PR plan. Please ", "session_flash_link", array(
                "link_text" => "purchase a plan",
                "link_url" => array(
                    "controller" => "plans",
                    "action" => "online-distribution",
                    "admin" => false
                )
            ));
        }

        if (!empty($planDetail['PlanCategory'])) {
            if (!empty($planDetail['PlanCategory']['is_country_allowed']) && $planDetail['PlanCategory']['is_country_allowed'] == 1 && $planDetail['PlanCategory']['is_allowed_all_country'] == 1) {
                $country_list = $this->Custom->getCountryList();
            } else {
                $country_list = $this->CountriesPlanCategory->find('list', array(
                    'joins' => array(
                        array(
                            'table' => 'countries',
                            'alias' => 'Country',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Country.id = CountriesPlanCategory.country_id'
                            )
                        ),
                    ),
                    'conditions' => array('Country.status' => 1, 'plan_category_id' => $planDetail['PlanCategory']['id']),
                    'fields' => array('Country.id', 'Country.name')
                ));
            }
            /* If Country allowed and mas */

            /*
            $msa_list = $this->MsasPlanCategory->find('list', array(
                'joins' => array(
                    array(
                        'table' => 'msas',
                        'alias' => 'Msa',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Msa.id = MsasPlanCategory.msa_id'
                        )
                    ),
                ),
                'conditions' => array('Msa.status' => 1, 'plan_category_id' => $planDetail['PlanCategory']['id']),
                'fields' => array('Msa.id', 'Msa.name')
            ));
            */
        }

        $state_list = "";
        $transdata = [];
        $this->loadModel('State');
        if (!empty($id)) {
            $this->request->data = $this->PressRelease->read(null, $id);
            $this->request->data['PressRelease']['language'] = ($selectedLang == $this->request->data['PressRelease']['language']) ? $this->request->data['PressRelease']['language'] : $selectedLang;
            if ($this->request->data['PressRelease']['status'] == 0) {
                $this->Session->setFlash(__('You can not edit the press release after submitting a press release.'), 'error');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                exit;
            }
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
            $this->loadModel('TransactionPressRelease');
            $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('staff_user_id' => $user_id, 'press_release_id' => $id)));
        } 
    
        $this->loadModel('Distribution');
        $this->Distribution->virtualFields['name'] = 'CONCAT(name, " (' . $currencySymbol . '", CASE WHEN  number > 0 THEN CONCAT(amount," / ",number," Email") ELSE amount END ,")")';
        $distribution_list = $this->Distribution->find('list', array("fields" => ['id', 'name'], 'conditions' => array('status' => 1)));

        $allCountries = [];
        if (empty($allCountries)) {
            $allCountries = $this->Custom->getCountryList();
        }
        $languages = $this->Custom->getLanguages();
        $this->set(compact('planDetail', 'is_plan_paid', "selectedplan", 'plan_list', 'remaingPR', 'state_list', 'distribution_list', 'action', 'email_list', "company_list", "msa_list", "country_list", "categories", "allStates", "allMsas", "allCountries", 'id', 'transdata', 'user_id', 'currencySymbol', 'languages', 'selectedLang'));
    }

    private function save_press_image($press_release_id, $press_image)
    {

        App::uses('Folder', 'Utility');

        $date = date('Y') . DS . date('m') . DS . date('d');

        $file_path = WWW_ROOT . 'files' . DS . 'company' . DS . 'press_image' . DS . $date;

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

    public function press_release($st = 'pending', $limit = "", $isReturn = "")
    {

        $this->set('model', 'PressRelease');
        Controller::disableCache();
        $status = $this->Custom->getPRSstatus($st);
        $user_id = $this->Auth->user('id');
        $limit = (!empty($limit)) ? $limit : $this->limit;
        $conditions = array();

      

        $conditions[] = array('PressRelease.status' => $status, "staff_user_id" => $user_id);

        $this->PressRelease->virtualFields['count'] = "select count(*) FROM clipping_reports where press_release_id=PressRelease.id";

        $this->PressRelease->recursive = -1;
        // $clippingReportTbl=$this->ClippingReport->table;
        // $socialShareCountSql="select views FROM $clippingReportTbl where distribution_type='social_media_feed' AND press_release_id=PressRelease.id";
        // $networkFeedCountSql="select views FROM $clippingReportTbl where distribution_type='network_feed' AND press_release_id=PressRelease.id";

        $socialShareCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` NOT IN ('email','print') AND press_release_id=PressRelease.id";
        $networkFeedCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='2' AND press_release_id=PressRelease.id";

        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array("($socialShareCountSql) as socialShareCount", "($networkFeedCountSql) as networkFeedCount", 'PressRelease.id', 'PressRelease.slug', 'PressRelease.title', 'PressRelease.views', 'PressRelease.summary', 'PressRelease.status', 'PressRelease.release_date', 'PressRelease.plan_id', 'PressRelease.is_paid', 'PressRelease.old_status', 'PressRelease.disapproval_reason','PressRelease.language',),
            'limit' => $limit, 'order' => 'PressRelease.id DESC'
        );

        $data_array = $this->paginate('PressRelease');
        if (!empty($isReturn)) {
            return $data_array;
            exit;
        }

        $title = (count($data_array) > 1) ? "Drafts" : "Draft";
        if ($st != 'draft')
            $title = ucfirst($st) . ' press releases';

   
        
        $this->set('title_for_layout', $title);
        $this->set(compact('data_array', 'status', 'user_id', 'title'));
    }

    public function movetrash($id = '')
    {

        $this->PressRelease->id = $id;

        $savedata['PressRelease']['status'] = '5';

        if ($this->PressRelease->save($savedata)) {

            $this->Session->setFlash(__('Detail successfully trashed.'), 'success');

            $this->redirect(array('action' => 'press_release', "trashed", 'rmch-' . rand(1, 999)));
        }
    }

    public function restorepr($id = '', $oldStatus = '')
    {

        $this->PressRelease->id = $id;

        $savedata['PressRelease']['status'] = $oldStatus;

        if ($this->PressRelease->save($savedata)) {

            $this->Session->setFlash(__('Detail successfully trashed.'), 'success');

            $status = $this->Custom->getPRSUrlStatus($oldStatus);

            $this->redirect(array('action' => 'press_release', $status, 'rmch-' . rand(1, 999)));
        }
    }

    public function deletepr($id = null)
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

        $this->redirect(array('action' => 'press_release', "trashed", 'rmch-' . rand(1, 999)));
    }

    public function become_subscriber()
    {

        $this->layout = 'site_default';

        $this->set('title_for_layout', 'Become a subscriber');

        $this->set('country_list', $this->Country->find('list', array('conditions' => array('Country.status' => 1))));



        if (!empty($this->data)) {

            $this->request->data['StaffUser']['status'] = 1;

            $this->request->data['StaffUser']['staff_role_id'] = 4;

            $this->request->data['StaffUser']['newsletter_cycle'] = "d";

            $this->request->data['StaffUser']['newsletter_send_mail_date'] = date('Y-m-d', strtotime('+1 days'));

            $company_logo = $this->request->data;

            unset($this->request->data['Company']['logo']);

            if ($this->StaffUser->save($this->request->data)) {

                $email = $this->data['StaffUser']['email'];

                $key = Security::hash(CakeText::uuid(), 'sha1', true);

                $hash = sha1($this->data['StaffUser']['email'] . rand(0, 20));

                $url = Router::url(array('controller' => 'users', 'action' => 'activate'), true) . '/' . $key . '#' . $hash;

                $ms = $url;

                $ms = wordwrap($ms, 1000);



                $fu['StaffUser']['tokenhash'] = $key;

                $this->StaffUser->id = $this->StaffUser->getLastInsertID();

                if ($this->StaffUser->saveField('tokenhash', $fu['StaffUser']['tokenhash'])) {

                    $email = $this->EmailTemplate->selectTemplate('user-signup');



                    $emailFindReplace = array(

                        '##NAME##' => $this->data['StaffUser']['first_name'],

                        '##SITE_NAME##' => $this->siteName,

                        '##SUPPORT_EMAIL##' => Configure::read('Site.support_email'),

                        '##ACCOUNT_ACTIVATE_LINK##' => $url,

                        '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),

                        '##FROM_EMAIL##' => $this->StaffUser->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']),

                        '##SITE_LOGO##' => Router::url(array(

                            'controller' => 'img',

                            'action' => '/',

                            'logo.png',

                            'admin' => false

                        ), true)

                    );

                    $this->AWSSES->from = $email['title'] . " <" . $email['from'] . ">";

                    $this->AWSSES->to =  $this->data['StaffUser']['email'];

                    $this->AWSSES->subject = $email['subject'];

                    $this->AWSSES->replayto = $email['reply_to_email'];

                    $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);



                    if (!$this->AWSSES->_aws_ses()) {

                        $this->Email->from = $email['title'] . " <" . $email['from'] . ">";

                        $this->Email->replyTo = $email['reply_to_email'];

                        $this->Email->to = $this->request->data['StaffUser']['email'];

                        $this->Email->subject = strtr($email['subject'], $emailFindReplace);

                        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';

                        $description = strtr($email['description'], $emailFindReplace);

                        $this->Email->send($description);
                    }

                    $this->Session->setFlash(__('An invite email has been sent to your email id.Please activate your email account following the activation link'), 'success');

                    //$this->Session->setFlash(__('StaffUser registered succesfully!'), 'success');

                    //  exit();

                    $userData = $this->StaffUser->find('first', array('conditions' => array("StaffUser.id" => $this->StaffUser->id)));

                    $this->Auth->login($userData['StaffUser']);

                    $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));

                    //============EndEmail=============//

                }
            } else {

                $this->Session->destroy();

                $this->Session->setFlash(__('There is some error in below form. Please see red message.'), 'error');
            }
        }
    }

    public function take_over_publishing()
    {

        $this->set('title_for_layout', 'Take Over Publishing');

        $this->set('title', "Take Over Publishing");

        if ($this->request->is('post')) {

            $this->request->data['TakeOverCompany']['staff_user_id'] = $this->Auth->user('id');

            if ($this->TakeOverCompany->save($this->request->data)) {

                $this->Session->setFlash('Your request has been sent.', 'success');
            } else {

                $this->Session->setFlash(__('There is some error in below form. Please see red message.'), 'error');
            }
        }

        $data_array = $this->TakeOverCompany->find('all', array('conditions' => array('TakeOverCompany.staff_user_id' => $this->Auth->user('id'))));

        $this->set('data_array', $data_array);
    }

    public function becomeaclient()
    {

        if ($this->request->is('post')) {

            $user_id = $this->Auth->user('id');

            $count = $this->StaffUser->find('count', array('conditions' => array("StaffUser.id" => $user_id, 'staff_role_id' => 3)));

            if ($count == 0) {

                $this->StaffUser->id = $user_id;

                $savedata['StaffUser']['staff_role_id'] = '3'; //client

                if ($this->StaffUser->save($savedata)) {

                    $this->Session->destroy();

                    $this->Session->setFlash(__('You have successfully migrated to client.'), 'success');

                    $userData = $this->StaffUser->find('first', array('conditions' => array("StaffUser.id" => $user_id)));



                    $email = $this->EmailTemplate->selectTemplate('subscriber-become-client');

                    $emailFindReplace = array(

                        '##NAME##' => ucfirst($userData['StaffUser']['first_name']) . " " . $userData['StaffUser']['last_name'],

                        '##SITE_NAME##' => $this->siteName,

                    );


                    $this->AWSSES->from = $email['title'] . " <" . $email['from'] . ">";

                    $this->AWSSES->to =  $userData['StaffUser']['email'];

                    $this->AWSSES->subject = $email['subject'];

                    $this->AWSSES->replayto = $email['reply_to_email'];

                    $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);



                    if (!$this->AWSSES->_aws_ses()) {

                        $this->Email->from = $email['title'] . " <" . $email['from'] . ">";

                        $this->Email->replyTo = $email['reply_to_email'];

                        $this->Email->to = $userData['StaffUser']['email'];

                        $this->Email->subject = strtr($email['subject'], $emailFindReplace);

                        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';

                        $description = strtr($email['description'], $emailFindReplace);

                        $this->Email->send($description);
                    }

                    $this->Auth->login($userData['StaffUser']);
                }
            } else {

                $this->Session->setFlash(__('You are already client.'), 'error');
            }

            $this->redirect(array('action' => 'dashboard'));
        }
    }

    public function check_email()
    {

        if (!empty($this->data['email'])) {

            $condition[] = array("email" => $this->data['email']);

            $email_count = $this->StaffUser->find('count', array('conditions' => $condition));



            if ($email_count == 0) {

                $status = "true";

                $message = "<span class='text-success'>This email is avilable.</span>";
            } else {

                $status = 'false';

                $message = "<span class='text-danger'>This email is already exist.</span>";
            }
        } else {

            $status = "empty";

            $message = "<span class='text-danger'>Please enter email.</span>";
        }

        echo json_encode(array("status" => $status, "message" => $message));

        $this->autoRender = false;
    }

    public function makepayment($selectedplan, $id)
    {
        $user_id = $this->Auth->user("id");
        // $this->layout = "full_width_default";
        $this->set('title_for_layout', __('Make payment'));
        $this->set('model', 'PressRelease');
        $this->Session->delete('pr_press_releaseid');
        
        
       
        try {
            if ($this->request->is('post') && $this->data['PressRelease']['submittype'] == "submitwithoutpayment") {
                 
                $savedata['PressRelease']['id'] = $id;
                $savedata['PressRelease']['status'] = '0';
                $this->PressRelease->save($savedata);
                $this->Session->delete('pr_selectedplan');
                $this->Session->setFlash(
                    "Your PR has been successfully edited. click here to view ",
                    "session_flash_link",
                    array(
                        "link_text" => "Press release",
                        "link_url" => array(
                            "controller" => "users",
                            "action" => "view", $selectedplan, $id
                        )
                    )
                );
                /* Added Code by Hitesh
                    reducing the PR number from it`s plan. nothing in cart.
                */

                $user_id = $this->Auth->user('id');
                $updatePrCreadits = $this->RemainingUserPlan->find('first', array('conditions' => array('RemainingUserPlan.plan_id' => $selectedplan, 'RemainingUserPlan.staff_user_id' => $user_id, 'number_pr !=' => '0'), 'fields' => array('number_pr', 'id')));
                if (!empty($updatePrCreadits)) {
                    $number_pr = $updatePrCreadits['RemainingUserPlan']['number_pr'] - 1;
                    $this->RemainingUserPlan->id = $updatePrCreadits['RemainingUserPlan']['id'];
                    $this->RemainingUserPlan->saveField('number_pr', $number_pr);
                }
                if ($this->Session->read('pr_press_releaseid')) {
                    $this->Cart->deleteAll(['Cart.press_release_id' => $id, 'Cart.staff_user_id' => $user_id, 'cart_type' => 'pr'], false);
                }
                return $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            }

            if ($this->request->is('post')) { 
                $this->Session->write('pr_selectedplan', $selectedplan); 
                $this->Session->write('pr_press_releaseid', $id); 
                return $this->redirect(array('controller' => 'users', 'action' => 'prpayment', $selectedplan,$id,$this->data['PressRelease']['submittype']));
            } 

            $this->PressRelease->recursive = 2;

            $data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.staff_user_id' => $user_id,'PressRelease.transaction_id IS NULL','PressRelease.status' =>[0,3], 'PressRelease.id' => $id)));
            if(empty($data)){
                $this->Session->setFlash(__("Payment already completed for this press release."), 'error'); 
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));  
            }
             
            $cartdata = $this->Custom->getprcartdata($user_id, $selectedplan, $id); 
            
            $this->Session->write('Stripe.cartData', $cartdata);
            $this->set(compact('data', 'cartdata' ,'selectedplan','id'));
        } catch (Exception $exc) { 
            $message = $exc->getMessage(); 
            $this->Session->setFlash(__($message), 'error'); 
            $this->redirect(array('controller' => 'users', 'action' => 'makepayment'));
        }
    }
    /*PR View page*/

    public function view($plan_id, $id)
    {
        $currency = Configure::read('Site.currency');
        // $this->layout = "full_width_default";
        $this->set('model', 'PressRelease');

        try {
            $user_id = $this->Auth->user("id");
            $this->PressRelease->recursive = 2;
            $data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.staff_user_id' => $user_id, 'PressRelease.id' => $id)));

            /* pr($data);
        die; */


            if (empty($data)) {

                $this->redirect('/notfound');
            }

            $this->set('title_for_layout', __($data['PressRelease']['title']));

            $this->set('data', $data);

            if ($data['PressRelease']['status'] == 4) {

                $this->Session->setFlash("Your PR has been disapproved by admin.", 'error');
            }

            $pressId  = $data['PressRelease']['id'];

            $plan_id  = $data['PressRelease']['plan_id'];

            $user_id  = $data['PressRelease']['staff_user_id'];
            
            $cartdata  = $this->Custom->getprcartdata($user_id, $plan_id, $pressId);

            $transdata = [];

            if ($cartdata['totals']['total'] == 0) {

                $cartdata = [];

                $this->loadModel('TransactionPressRelease');

                $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('staff_user_id' => $user_id, 'press_release_id' => $id)));
            }

            $this->set(compact('data', 'cartdata', 'transdata', 'currency'));
        } catch (Exception $exc) {

            $message = $exc->getMessage();

            $this->Session->setFlash(__($message), 'error');

            $this->redirect(array('controller' => 'users', 'action' => 'make-payment'));
        }
    }

    /*PR Transaction View page*/

    public function transaction_view($id = null)
    {

        if ($id == null)

        $this->redirect('/notfound');

        $currencySymbol = Configure::read('Site.currency');

        $this->set('title_for_layout', __('Transaction View'));

        $this->set('model', 'Transaction');

        try {

            $transdata = [];

            $user_id = $this->Auth->user("id");

            $this->Transaction->recursive = 2;

            $data = $this->Transaction->read(null, $id);

            if ($data['Transaction']['transaction_type'] == 'pr') {

                $this->loadModel('TransactionPressRelease');

                $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('transaction_id' => $id, 'staff_user_id' => $user_id)));
            }

            $this->set(compact('data', 'transdata', 'currencySymbol'));
        } catch (Exception $exc) {

            $message = $exc->getMessage();

            $this->Session->setFlash(__($message), 'error');

            $this->redirect(array('controller' => 'users', 'action' => 'make-payment'));
        }
    }
    public function invoices($type = '')
    {

        $this->loadModel('Invoice');

        $this->set('model', 'Invoice');

        $this->set('placeholder', 'Please enter Transaction id,Name,Email..');

        $this->Invoice->bindModel(array('belongsTo' => ['StaffUser']));

        $selected = $type;

        if (empty($type)) {

            $type = ['plannewsroom', 'pr'];
        }


        $data_array = [];

        $this->Invoice->recursive = "2";

        $user_id = $this->Auth->user('id');

        $condition_arr = array(
            'Invoice.staff_user_id' => $user_id, 'Invoice.tx_id !=' => null
        );

        // if($type == "pr"){
         
        // }else{
            $condition_arr['Invoice.transaction_type'] = $type;
       // }

        $conditions[] = $condition_arr;

     

        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {

            $this->set('keyword', $this->params->query['s']);

            $conditions[] = array('OR' => array(

                'Invoice.tx_id like ' => '%' . trim($this->params->query['s']) . '%',

                'StaffUser.first_name like ' => '%' . trim($this->params->query['s']) . '%',

                'StaffUser.email like ' => '%' . trim($this->params->query['s']) . '%',

                'StaffUser.last_name like ' => '%' . trim($this->params->query['s']) . '%'

            ));
        }

        $this->Invoice->recursive = "-1";

        $fields = array('Invoice.id', 'Invoice.tx_id', 'StaffUser.first_name', 'StaffUser.last_name', 'subtotal', 'total', 'status', "payment_date");

        $this->paginate = array('conditions' => $conditions, 'order' => 'Invoice.id DESC', 'limit' => '15');

        $data_array = $this->paginate('Invoice');

        $type = ($type == "plannewsroom") ? "Plan" : "Press release";

        $title_for_layout = __('All ' . $type . ' Invoices');



        $this->set(compact("data_array", "selected", "title_for_layout"));
    }

    public function invoice_view($id = '')
    {

        $this->set('title_for_layout', __('View Invoice'));

        $transdata = [];

        $data = $this->Transaction->read(null, $id);

        if ($data['Transaction']['transaction_type'] == 'pr') {

            $this->loadModel('TransactionPressRelease');

            $this->TransactionPressRelease->bindModel(array('belongsTo' => array('PressRelease' => array('className' => 'PressRelease'))));

            $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('transaction_id' => $id)));
        }

        $this->set(compact('data', 'transdata'));
    }

    public function editpr($id = null, $redirect = 'pending')
    {

        $this->set('model', 'PressRelease');

        $this->loadModel('Distribution');

        $this->loadModel('State');

        $this->loadModel('Country');

        $this->loadModel('Category');

        $this->loadModel('Msa');

        $this->loadModel('Plan');

        $this->loadModel('PressSeo');

        $this->loadModel('PressYoutube');

        $this->loadModel('PressPoadcast');


        if ($id == null)

        $this->redirect('/notfound');

        $this->set('title_for_layout', __('Edit PR'));

        if (!empty($this->data)) {

            $press_image = array_values($this->request->data['PressImage']);

            $this->save_press_image($id, $press_image);

            unset($this->request->data['PressImage']);

            $press_seo = array_values($this->request->data['PressSeo']);

            unset($this->request->data['PressSeo']);

            foreach ($press_seo as $key => $seo) {

                if (!empty($seo['keyword'])) {

                    $this->request->data['PressSeo'][$key]['press_release_id'] = $this->data['PressRelease']['id'];

                    $this->request->data['PressSeo'][$key]['keyword'] = $seo['keyword'];

                    $this->request->data['PressSeo'][$key]['slug'] = strtolower(Inflector::slug($seo['keyword'], '-'));



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

            foreach ($press_youtubes as $key => $youtubes) {

                if (!empty($youtubes['url'])) {

                    if (isset($this->data['PressRelease']['id']) && !empty($this->data['PressRelease']['id'])) {

                        $this->request->data['PressYoutube'][$key]['press_release_id'] = $this->data['PressRelease']['id'];
                    }

                    $this->request->data['PressYoutube'][$key]['url'] = $youtubes['url'];

                    $this->request->data['PressYoutube'][$key]['description'] = $youtubes['description'];

                    if (isset($youtubes['id']))

                        $this->request->data['PressYoutube'][$key]['id'] = $youtubes['id'];
                } else {

                    if (isset($youtubes['id']) && empty($youtubes['url'])) {

                        $this->PressYoutube->id = $youtubes['id'];

                        if ($this->PressYoutube->exists()) {

                            $this->PressYoutube->delete();
                        }
                    }
                }
            }

            $press_podcasts = array_values($this->request->data['PressPoadcast']);

            unset($this->request->data['PressPoadcast']);

            foreach ($press_podcasts as $key => $podcast) {

                if (!empty($podcast['url'])) {

                    if (isset($this->data['PressRelease']['id']) && !empty($this->data['PressRelease']['id'])) {

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

            $this->request->data['PressRelease']['status'] = '0';

            if ($this->PressRelease->saveAll($this->request->data)) {

                $this->Session->setFlash(__('PR successfully updated for review.'), 'success');

                return $this->redirect(array('controller' => 'users', 'action' => "press-releases"));
            } else {

                $this->Session->setFlash(__('PR not updated. Please, try again.'), 'error');
            }
        }

        $this->request->data = $this->PressRelease->read(null, $id);

        if ($this->request->data['PressRelease']['status'] != '4') {

            $this->redirect('/users/press-releases');
        }

        $this->Distribution->virtualFields['name'] = 'CONCAT(name, " ($",amount,")")';

        $distribution_list = $this->Distribution->find('list', array('id', 'name'));

        $state_list = "";

        $countryId = $this->request->data['PressRelease']['country_id'];

        $state_list = $this->State->find('list', array('conditions' => array('country_id' => $countryId)));

        $country_list = $this->Country->find('list', array('conditions' => array('Country.status' => 1)));

        $planDetail = $this->Plan->find('first', array('conditions' => array('Plan.id' => $this->request->data['PressRelease']['plan_id'])));

        $pCategory_list = $this->Category->find('list', array('conditions' => array('status' => 1, 'parent_id' => 0)));

        $categories = [];

        if ($pCategory_list) {

            foreach ($pCategory_list as $cpid => $pCatname) {

                $categories[$pCatname] = [];

                $category_list = $this->Category->find('list', array('conditions' => array('status' => 1, 'parent_id' => $cpid)));

                foreach ($category_list as $cId => $category) {

                    $categories[$pCatname][$cId] = $category;
                }
            }
        }

        if ($planDetail['PlanCategory']['is_msa_allowed'])

            $mas_list = $this->Msa->find('list', array('conditions' => array('status' => 1)));

        $this->set(compact('state_list', 'country_list', 'distribution_list', 'categories', 'mas_list', 'planDetail'));
    }

    public function prsuccess($prId = '')
    {

        $this->set('title_for_layout', __('Thank you'));

        $user_id = $this->Auth->user('id');

        $this->loadModel('TransactionPressRelease');

        $this->PressRelease->recursive = 2;
        $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressImage', 'Company', 'PressYoutube', 'PressPoadcast'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution'), 'belongsTo' => array('Plan')));
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

        $data = $this->PressRelease->read(null, $prId);

        //$this->PressRelease->recursive = 2;

        // $data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.staff_user_id' => $user_id, 'PressRelease.id' => $prId), 'fields' => array('PressRelease.plan_id', 'PressRelease.staff_user_id')));

        if (empty($data)) {

            $this->redirect('/notfound');
        }

        //$staff_data = $this->StaffUser->read(null, $user_id);
        // $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('TransactionPressRelease.staff_user_id' => $user_id, 'TransactionPressRelease.press_release_id' => $prId)));
        // $data['TransactionPressRelease']=$data['TransactionPressRelease'];
        $transactionData  = $this->Transaction->find("first", array('conditions' => array('Transaction.id' => $data['TransactionPressRelease']['transaction_id'])));
        $data['Transaction'] = $transactionData['Transaction'];
        $this->set(compact('prId', 'data'));
    }

    public function subscribenews()
    {

        if ($this->Auth->user('staff_role_id') != 4) {
            $this->redirect('dashboard');
        }
        $model = "PressRelease";
        $this->set('title_for_layout', __('Subscribe news'));
        $user_id = $this->Auth->user('id');
        $userdetails = $this->StaffUser->find(
            'first',
            [
                'conditions' => ["StaffUser.id" => $user_id],
                'contain' => [
                    'Category' => ['fields' => ['id']],
                    'Msa' => ['fields' => ['id']]
                ]
            ],
        );
        $categoriesIds = [];
        $msaIds = [];
        if ($userdetails['Category']) {
            foreach ($userdetails['Category'] as $catIndex => $category) {
                $categoriesIds[$catIndex] = $category['id'];
            }
        }
        if ($userdetails['Msa']) {
            foreach ($userdetails['Msa'] as $msaIndex => $msa) {
                $msaIds[$msaIndex] = $msa['id'];
            }
        }
        $data_array = array();
        $prConditions = [];
        if (!empty($msaIds) && !empty($categoriesIds)) {
            $prConditions = ['PressRelease.status' => 1, 'OR' => ['Msa.msa_id' => $msaIds, 'CategoryPressRelease.category_id' => $categoriesIds]];
        } else if (!empty($msaIds)) {
            $prConditions = ['PressRelease.status' => 1, 'Msa.msa_id' => $msaIds, 'PressRelease.id !=' => $alreadySent, 'PressRelease.release_date BETWEEN ? and ?' => array($lastSentdate, $today)];
        } else if (!empty($categoriesIds)) {
            $prConditions = ['PressRelease.status' => 1, 'CategoryPressRelease.category_id' => $categoriesIds, 'PressRelease.id !=' => $alreadySent, 'PressRelease.release_date BETWEEN ? and ?' => array($lastSentdate, $today)];
        }
        $this->PressRelease->unbindModel(array('hasMany' => array('PressSeo', 'PressYoutube', 'PressPoadcast'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution'), 'belongsTo' => array('Plan')));
        $prfields = ['PressRelease.id', 'PressRelease.title', 'PressRelease.status', 'PressRelease.slug', 'PressRelease.body', 'PressRelease.summary', 'PressRelease.release_date', 'Company.name', 'Company.slug', 'Company.logo_path', 'Company.logo', 'StaffUser.first_name', 'StaffUser.last_name', 'StaffUser.profile_image'];
        $this->paginate = array(
            'joins' => array(
                array(
                    'table' => 'categories_press_releases',
                    'alias' => 'CategoryPressRelease',
                    'type' => 'INNER',
                    'conditions' => array(
                        'CategoryPressRelease.press_release_id = PressRelease.id'
                    )
                ),
                array(
                    'table' => 'msas_press_releases',
                    'alias' => 'Msa',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Msa.press_release_id = PressRelease.id'
                    )
                )
            ), 'conditions' => $prConditions, 'fields' => $prfields,
            'group' => 'PressRelease.id', 'order' => 'PressRelease.release_date DESC'
        );
        $data_array = $this->paginate('PressRelease');
        $this->set(compact('data_array', 'model'));
    }
    public function plans($currentpage = '1')
    {
        $this->set('title_for_layout', __('Purchesed PR plans'));
        $this->set('title', "PR plans");
        $this->loadModel('RemainingUserPlan');
        $user_id = $this->Auth->user('id');
        $limit = Configure::read('Site.paging');
        $totalCounts = $this->StaffUser->query("CALL userPlansTransactionCount(" . $user_id . ");");
        $totalCount = (isset($totalCounts[0][0]['totalplans']) && !empty($totalCounts[0][0]['totalplans'])) ? $totalCounts[0][0]['totalplans'] : "0";
        $totalpages = ($totalCount / $limit);
        $offset = (($currentpage - 1) * $limit);
        $userPlans = $this->StaffUser->query("CALL user_planstransaction(" . $user_id . "," . $limit . "," . $offset . ");");
        $this->set(compact('userPlans', 'totalCount', 'totalpages', 'currentpage'));
    }

    public function purchased_plan($currentpage = '1'){
        $this->set('title_for_layout', __('Invoices'));
        $this->set('title', "Invoices");
        $user_id = $this->Auth->user('id');
        $limit =  Configure::read('Site.paging');
        $totalCounts = $this->StaffUser->query("CALL userPurchasedPlanCount(" . $user_id . ");");
        $totalCount = (isset($totalCounts[0][0]['totalplans']) && !empty($totalCounts[0][0]['totalplans'])) ? $totalCounts[0][0]['totalplans'] : "0";
        $totalpages = ($totalCount / $limit);
        $offset = (($currentpage - 1) * $limit);
        $userPlans = $this->StaffUser->query("CALL userPurchasedPlan(" . $user_id . "," . $limit . "," . $offset . ");");

        $this->set(compact('userPlans', 'totalCount', 'totalpages', 'currentpage'));
    }

    public function clipping_report()
    {

        $user_id = $this->Auth->user('id');
        $this->set('title_for_layout', __('Clipping report'));
        $model = "PressRelease";
        $this->PressRelease->recursive = -1;
        // $clippingReportTbl=$this->ClippingReport->table;
        // $this->PressRelease->unbindModel(array("belongsTo"=>array('Plan','Company'),"hasAndBelongsToMany"=>array('Category','Msa','State','Distribution'),"hasMany"=>array('PressSeo', 'PressYoutube', 'PressImage','PressPoadcast')));
        // $socialShareCountSql="select views FROM $clippingReportTbl where distribution_type='social_media_feed' AND press_release_id=PressRelease.id";
        // $networkFeedCountSql="select views FROM $clippingReportTbl where distribution_type='network_feed' AND press_release_id=PressRelease.id";

        $socialShareCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` NOT IN ('email','print') AND press_release_id=PressRelease.id";
        $networkFeedCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='2' AND press_release_id=PressRelease.id";
        $prconditions[] = array('PressRelease.status' => '1', "PressRelease.staff_user_id" => $user_id);

        $this->paginate = array(
            'conditions' => $prconditions,
            'fields' => array("($socialShareCountSql) as socialShareCount", "($networkFeedCountSql) as networkFeedCount", 'PressRelease.id', 'PressRelease.slug', 'PressRelease.title', 'PressRelease.views', 'PressRelease.summary', 'PressRelease.status', 'PressRelease.release_date', 'PressRelease.plan_id', 'PressRelease.is_paid', 'PressRelease.old_status', 'PressRelease.disapproval_reason'),
            'limit' => $this->limit, 'order' => 'PressRelease.id DESC'
        );

        // $this->paginate = array(

        //     'conditions' => $prconditions, 'fields' => array('PressRelease.id', 'PressRelease.title', 'PressRelease.views', "PressRelease.release_date", "PressRelease.plan_id"), 'limit' => Configure::read('Site.paging'), 'order' => 'PressRelease.release_date DESC'
        // );

        $data_array = $this->paginate('PressRelease');

        $this->set(compact('data_array', "model"));
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

            $this->paginate = array('conditions' => $prconditions, 'limit' => Configure::read('Site.paging'), 'order' => 'ClippingReport.id DESC');

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

    public function userAssignSendyTable($id = "", $email = "", $first_name = "")
    {

        if ($this->Auth->login()) {

            $first_name = $this->Auth->user('first_name');

            $id = $this->Auth->user('first_name');

            $email = $this->Auth->user('email');
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, SITEURL . "sendy/includes/app/users.php");

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "login_email=$email&name=$first_name&id=$id");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
    }

    public function email_list()
    {

        $this->set('model', 'List');

        $this->set('title_for_layout', 'Email Lists');

        $user_id = $this->Auth->user('id');

        $limit = Configure::read('Admin.paging');

        $this->paginate = array("conditions" => array("staff_user_id" => $user_id), 'order' => 'List.id ASC', 'limit' => $limit);

        $pageList = $this->paginate('List');

        $this->set('data_array', $pageList);
    }

    public function add_email_list()
    {

        $this->set('model', 'List');

        $this->set('title_for_layout', 'Add Email List');

        $user_id = $this->Auth->user('id');

        if ($this->request->is('post')) {

            $name = trim($this->data['List']['name']);

            $count = $this->List->find('count', array("conditions" => array("staff_user_id" => $user_id, "name" => $name)));

            if ($count > 0) {

                $this->List->validationErrors['name'] = 'Name is already exist.';
            } else {

                $this->request->data['List']['name'] = $name;

                $this->request->data['List']['list_for_client'] = 1;

                $this->List->save($this->request->data);

                $lastId = $this->List->getLastInsertId();

                $this->redirect(array('controller' => 'users', 'action' => 'import-media-email-list', $lastId));
            }
        }

        $this->set(compact("user_id"));
    }

    public function edit_list()
    {

        $this->set('model', 'List');

        if (isset($this->params['lid']) && !empty($this->params['lid'])) {

            $lid = $this->params['lid'];

            $appId = '1';

            $this->set('title_for_layout', 'Edit Email List');

            $user_id = $this->Auth->user('id');

            $this->List->id = $lid;

            if (!$this->List->exists()) {

                throw new NotFoundException(__('Invalid email template'));
            }

            if ($this->request->is('put')) {

                $this->request->data['List']['list_for_client'] = 1;

                if ($this->List->save($this->request->data)) {

                    $this->Session->setFlash(__('List name successfully updated.'), 'success');

                    $this->redirect(array('controller' => 'users', 'action' => 'import-media-email-list', $lid));
                } else {

                    $this->Session->setFlash(__('List name not updated.. Please, try again.'), 'error');
                }
            }

            $this->request->data = $this->List->read(null, $lid);

            $this->set(compact("user_id", 'lid', 'appId'));
        } else {

            $this->redirect(array('controller' => 'users', 'action' => 'email-lists', $lid));
        }
    }

    public function sendy_add_subscriber($lid = null)
    {

        $this->set('model', 'List');

        $this->set('title_for_layout', 'Add manually media email');

        if (isset($lid) && !empty($lid)) {

            $user_id = $this->Auth->user('id');

            $appId = '1';

            $err = isset($_GET['e']) ? $_GET['e'] : '';

            $data = $this->List->read(null, $lid);

            $this->set(compact("lid", "data", "user_id", "appId"));
        } else {

            $this->redirect(array('controller' => 'users', 'action' => 'email-lists'));
        }
    }

    public function sendy_import_subscriber_csv($lid = null)
    {

        $this->set('model', 'List');

        if (isset($lid) && !empty($lid)) {

            $user_id = $this->Auth->user('id');

            $appId = '1';

            $this->set('title_for_layout', 'Import media email list csv');

            $err = isset($_GET['e']) ? $_GET['e'] : '';



            $data = $this->List->read(null, $lid);

            $this->set(compact("lid", "data", "err", "appId", "user_id"));
        } else {

            $this->redirect(array('controller' => 'users', 'action' => 'email-lists'));
        }
    }

    public function sendy_subscribers($lid = null)
    {
        //'sent' => null

        $this->set('title_for_layout', 'Client Media List');

        $this->loadModel('Subscriber');

        $this->set('model', 'Subscriber');

        $limit = Configure::read('Admin.paging');

        $user_id = $this->Auth->user('id');

        $this->paginate = array("conditions" => array("list" => $lid, "staff_user_id" => $user_id), 'order' => 'Subscriber.id DESC', 'limit' => $limit);

        $subscribers = $this->paginate('Subscriber');

        $this->set(compact("lid", "subscribers", "user_id"));
    }

    public function delete_sendy_subscriber($id = '', $listId = "")
    {

        try {

            $this->Subscriber->id = $id;



            if (!$this->Subscriber->exists()) {

                throw new NotFoundException(__('Invalid Detail.'));
            }

            if ($this->Subscriber->delete()) {

                $this->Session->setFlash(__('Email deleted successfully'), 'success');
            } else {

                throw new NotFoundException(__('Email name not deleted. Please, try again.'));
            }
        } catch (Exception $exc) {

            $message = $exc->getMessage();

            $this->Session->setFlash($message, 'error');
        }

        $this->redirect(array('controller' => "users", 'action' => 'client-media-list', $listId));
    }

    public function support()
    {

        //function for zoho ticket submit from our site

        $this->layout = 'site_default';

        if ($this->Auth->loggedIn()) {

            $this->layout = 'default';
        }

        $this->set('title_for_layout', 'Create ticket');
    }

    public function ticket_success()
    {

        $this->layout = 'site_default';

        if ($this->Auth->loggedIn()) {

            $this->layout = 'default';
        }

        $this->set('title_for_layout', 'Thank you!');
    }
    public function sentmailreport($userId = null, $champId = null)
    {

        $this->set('title_for_layout', 'Sent mail report');

        if ($champId != null) {

            $userId = ($userId == null) ? $this->Auth->user('id') : $userId;

            $this->set(compact("champId", "userId"));
        } else {

            $this->redirect('/notfound');
        }
    }
    public function sendinmail($prId = null)
    {

        if ($prId != null) {

            $this->loadModel("List");

            $user_id = $this->Auth->user('id');

            if ($this->request->is("post") && !empty($this->data)) {

                $listId = $this->data['Campaign']['list_id'];

                $this->setCampSchedule($prId, $listId);

                $this->Session->setFlash(__('PR will be send.'), 'success');

                return $this->redirect(array("controller" => "users", 'action' => 'press-releases/approved'));
            }

            $lists = $this->List->find("list", array("conditions" => array("staff_user_id" => $user_id), "fields" => array('id', "name")));

            $this->set(compact("lists", "prId", "user_id"));
        } else {

            return $this->redirect(array('action' => 'index'));
        }
    }

    public function setCampSchedule($prId = null, $list_id)
    {

        $this->loadModel('Campaign');

        $this->loadModel('PressRelease');

        $this->loadModel('Login');

        $sendyMainUser = $this->Login->find('first', array("fields" => array("timezone"), "conditions" => array("id" => "1")));

        // $timezone=$sendyMainUser['Login']['timezone'];

        // date_default_timezone_set($timezone!='0' && $timezone!='' ? $timezone : date_default_timezone_get());

        $campaign = $this->Campaign->find('first', array("fields" => array("id"), "conditions" => array('created LIKE' => '%' . date("Y-m-d") . '%', 'sent' => null)));

        $send_date = strtotime("+2 minutes");

        if (!empty($campaign)) {

            $seconds = strtotime("+10 minutes");

            $rounded_seconds = round($seconds / (15 * 60)) * (15 * 60);

            $send_date = strtotime(date('Y:m:d H:i:s', $rounded_seconds));
        }

        $mainUserId = $appId = "1";

        $user_id = $this->Auth->user('id');

        $from_email = $this->Auth->user('email');

        $from_name = $this->Auth->user('first_name') . " " . $this->Auth->user('last_name');

        $this->PressRelease->recursive = -1;

        $data = $this->PressRelease->find("first", array("fields" => array("title", "slug", "body"), "conditions" => array("PressRelease.id" => $prId)));

        $campaignData['Campaign']['title'] = $data['PressRelease']['title'];

        $campaignData['Campaign']['press_release_id'] = $prId;

        $campaignData['Campaign']['press_release_slug'] = $data['PressRelease']['slug'];

        $campaignData['Campaign']['html_text'] = "<html><head><title></title></head><body>" . $data['PressRelease']['body'] . " <p style='text-align:center;'><unsubscribe>Click here to unsubscribe</unsubscribe></p> </body></html>";

        $campaignData['Campaign']['userID'] = 1; //$user_id

        $campaignData['Campaign']['staff_user_id'] = $user_id;

        $campaignData['Campaign']['createdfrom'] = "frontend";

        $campaignData['Campaign']['app'] = $appId;

        $campaignData['Campaign']['from_name'] = $from_name;

        $campaignData['Campaign']['from_email'] = $from_email;

        $campaignData['Campaign']['reply_to'] = "no-reply@emailwire.com";

        $campaignData['Campaign']['recipients'] = "0";

        $campaignData['Campaign']['wysiwyg'] = "1";

        $campaignData['Campaign']['lists'] = $list_id;   // select list in popup

        $campaignData['Campaign']['opens_tracking'] = "1";

        $campaignData['Campaign']['links_tracking'] = "1";

        $campaignData['Campaign']['timezone'] = date_default_timezone_get(); //

        $campaignData['Campaign']['send_date'] = $send_date;

        if ($this->Campaign->save($campaignData)) {

            $cid = $this->Campaign->getInsertID();

            return true;
        }
    }

    public function download($prId = '',$orderBy="potential_audience")
    {
        try {
            if (!$prId) {
                throw new NotFoundException('Invalid request.');
            }

            $prconditions[] = array('ClippingReport.press_release_id' => $prId, 'ClippingReport.distribution_type !=' => 'mail_feed',);
            if (!empty($this->request->query('type'))) {
                $prconditions[] = array('ClippingReport.distribution_type' => $this->request->query('type'));
            }
            if (!empty($this->request->query('sname'))) {
                $prconditions[] = array('ClippingReport.site_name' => $this->request->query('sname'));
            }
            //$this->ClippingReport->recursive = -1;
            $clippingReportData = []; //$this->ClippingReport->find('all', array('conditions' => $prconditions, 'order' => 'ClippingReport.id DESC'));

            $nwrelationships = $this->NwRelationships->find('all', array('conditions' => array('NwRelationships.press_release_id' => $prId, 'NwRelationships.status' => '1'), 'order' => "NwRelationships.$orderBy DESC"));
            // $this->PressRelease->recursive = -1;
            // $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id")));

            $this->PressRelease->recursive = 2;
            $socialShareCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` NOT IN ('email','print') AND press_release_id=PressRelease.id";
            $emailCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` IN ('email') AND press_release_id=PressRelease.id";
            $printCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` IN ('print') AND press_release_id=PressRelease.id";
            $networkFeedCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='2' AND press_release_id=PressRelease.id";
            $potentialAudienceCountSql = "select SUM(potential_audience) FROM " . $this->NwRelationships->table . " where press_release_id=PressRelease.id AND `status`='1' ";
            $prData = $this->PressRelease->find("first", array(
                'conditions' => array('PressRelease.id' => $prId),

                "fields" => array(
                    "($socialShareCountSql) as socialShareCount", "($networkFeedCountSql) as networkFeedCount", "($potentialAudienceCountSql) as potentialAudienceCount",
                    "($emailCountSql) as emailCount", "($printCountSql) as printCount",
                    "id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id", "approved_by", "slug"
                )
            ));

            if (empty($prData)) {
                throw new NotFoundException('Invalid Press release.');
            }

            $this->generatePdfDownloadReceiptScreen($prData, $nwrelationships, $clippingReportData);
        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            if (!empty($prId)) {
                $this->redirect(array('action' => 'viewclippingreport', $prId));
            } else {
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    function generatePdfDownloadReceiptScreen($prData = [], $networkwebsites = [], $data_array = [])
    {

        include_once(APP . 'Vendor' . DS . 'dompdf/autoload.inc.php');
        $dompdf = new Dompdf();
        $dompdf->set_option('enable_remote', TRUE);
        $dompdf->set_option('isRemoteEnabled', TRUE);
        // $dompdf->set_option('isFontSubsettingEnabled', true);
        $options = new Options();
        // $options->set('isJavascriptEnabled', TRUE);
        $html = $this->Custom->getClippingReportViewHtml($prData, $networkwebsites, $data_array);
        $dompdf->load_html($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($prData['PressRelease']['slug'] . ".pdf", array("Attachment" => true));
        $filename = "Clipping Report - " . $pr_data['PressRelease']['title'];
        // $dompdf->stream($filename, array("Attachment" => 0));
        $file = $dompdf->output();
    }

    function getPdfReceipt()
    {
        include_once(APP . 'Vendor' . DS . 'dompdf/autoload.inc.php');
        $dompdf = new Dompdf();
        $dompdf->set_option('enable_remote', TRUE);
        $dompdf->set_option('isRemoteEnabled', TRUE);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $options = new Options();
        $options->set('isJavascriptEnabled', TRUE);
        $dompdf->load_html($this->request->data('html'));
        $dompdf->setPaper('A4');
        $dompdf->render();
        $filename = "Clipping Report - " . $this->request->query['title'];
        $dompdf->stream($filename, array("Attachment" => 1));
    }
    public function viewclippingreport($prId = '',$orderBy="potential_audience")
    {
        $this->set('title_for_layout', 'View Clipping Report');
        /*
        $prconditions[] = array('ClippingReport.press_release_id' => $prId, 'ClippingReport.distribution_type !=' => 'mail_feed',);
        if (!empty($this->request->query('type'))) {
            $prconditions[] = array('ClippingReport.distribution_type' => $this->request->query('type'));
        }
        if (!empty($this->request->query('sname'))) {
            $prconditions[] = array('ClippingReport.site_name' => $this->request->query('sname'));
        }
        $this->ClippingReport->recursive = -1;
        $data_array = $this->ClippingReport->find('all', array('conditions' => $prconditions, 'order' => 'ClippingReport.id DESC'));*/

        $this->loadModel('NwRelationships');
       // $nwrelationships = $this->NwRelationships->find('all', array('conditions' => array('NwRelationships.press_release_id' => $prId, 'NwRelationships.status' => '1'), 'order' =>"NwRelationships.$orderBy DESC")); // 
        
       $this->paginate = array('conditions' => array('NwRelationships.press_release_id' => $prId, 'NwRelationships.status' => '1'), 'order' => "NwRelationships.$orderBy DESC", 'limit' => $this->limit);
       $nwrelationships = $this->paginate('NwRelationships');
       
       $this->PressRelease->recursive = 2;
        $socialShareCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` NOT IN ('email','print') AND press_release_id=PressRelease.id";
        $emailCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` IN ('email') AND press_release_id=PressRelease.id";
        $printCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` IN ('print') AND press_release_id=PressRelease.id";
        $networkFeedCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='2' AND press_release_id=PressRelease.id";
        $potentialAudienceCountSql = "select SUM(potential_audience) FROM " . $this->NwRelationships->table . " where press_release_id=PressRelease.id AND `status`='1'";

        $data = $this->PressRelease->find("first", array(
            'conditions' => array('PressRelease.id' => $prId),
            "fields" => array(
                "($socialShareCountSql) as socialShareCount", "($networkFeedCountSql) as networkFeedCount", "($potentialAudienceCountSql) as potentialAudienceCount",
                "($emailCountSql) as emailCount", "($printCountSql) as printCount",
                "id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id"
            )
        ));

        $html = "";
        //$html = $this->generateClippingHtml($data_array, $data, $nwrelationships);

        $this->loadModel('PdfSetting');
        $pdf_data = $this->PdfSetting->find('first', array('conditions' => array('PdfSetting.id' => '1')));
        $pdfEmailDescription      =   $pdf_data['PdfSetting']['email_distribution_description'];
        $pdfNetworkDescription    =   $pdf_data['PdfSetting']['network_description'];

        $sumOfPotentialAudience = (!empty($data['0']['potentialAudienceCount'])) ?$this->Custom->numberFormatAsUs( $data['0']['potentialAudienceCount']): 0;
 
        $dateformat = strip_tags(Configure::read('Site.DateFromat'));
          /*
        $newsLetterMailReport='';
        $newsletterMailList = $this->Custom->getNewsletterOpenMailList($data['PressRelease']['id']);
        $newsletterSentMailList = $this->Custom->getNewsletterSentMailList($data['PressRelease']['id']);
        $newsletterReceivedMailList = $this->Custom->getNewsletterReceivedMailList($data['PressRelease']['id']);

        $newsLetterMailReport   .= $this->Custom->newsletterSentMailList($newsletterSentMailList,$newsletterReceivedMailList,$pdfEmailDescription);
        $newsLetterMailReport   .= $this->Custom->newsletterOpenMailList($newsletterMailList,$newsletterSentMailList,$newsletterReceivedMailList);
        $newsLetterMailReport   .= $this->Custom->newsletterReceivedMailList($newsletterReceivedMailList,$newsletterSentMailList);
        
        $sendyMailSentReportByCountry=$sendyMailSentReport="";
        $champ = $this->Custom->getCampaignDetails($data['PressRelease']['id']);
        if (!empty($champ)) {
            $unsubscribedMailList = $this->Custom->getSendyUnsubscribedMailList($champ['id']);
            $bouncedMailList = $this->Custom->getSendyBouncedMailList($champ['id']);
            $spamMailList = $this->Custom->getSendySpamMailList($champ['id']);
            $links = $this->Custom->getLinksMailList($champ['id']); 
            $sendyMailSentReport   = $this->Custom->mailSentReport($champ); 
            $sendyMailSentReportByCountry = $this->Custom->countryReportsByEmail($champ);
        }
        
        $rssMedia=$this->Custom->rssMediaReport($data_array); 
        $jsMedia=$this->Custom->jsMediaReport($data_array);

        'rssMedia','jsMedia','socialMedia','newsLetterMailReport','sendyMailSentReport',"sendyMailSentReportByCountry"
        */

        $this->set(compact('html', 'pdf_data', 'data', 'dateformat','pdfEmailDescription', 'pdfNetworkDescription','nwrelationships','sumOfPotentialAudience'));
    }
    function generateClippingHtml($data_array, $pr_data, $networkwebsites)
    {
        $this->loadModel('MaintenancePayment');
        $html = $this->Custom->getClippingReportViewHtml($pr_data, $networkwebsites, $data_array);
        return $html;
    }
    public function downloadinvoice($id = '')
    {
        $transdata = '';

        $user_id = $this->Auth->user('id');

        $this->Transaction->recursive = 2;

        $data_array = $this->Transaction->read(null, $id);

        if ($data_array['Transaction']['transaction_type'] == 'pr') {

            $this->loadModel('TransactionPressRelease');

            $transdata  = $this->TransactionPressRelease->find("first", array('conditions' => array('transaction_id' => $id, 'staff_user_id' => $user_id)));
        }

        $this->generatePdfDownloadInvoice($data_array, $transdata);

        exit();
    }

    function generatePdfDownloadInvoice($data_array, $transdata = '')
    {
        include_once(APP . 'Vendor' . DS . 'dompdf/autoload.inc.php');

        $dompdf = new Dompdf();

        // $dompdf->set_option('enable_remote', TRUE);

        if ($data_array['Transaction']['transaction_type'] == 'plannewsroom') {

            $invoicetype = "Plan";

            $html = $this->Custom->getPlanInvoiceHtml($data_array);
        } else {

            $invoicetype = "Press Release";

            $html = $this->Custom->getPrInvoiceHtml($data_array, $transdata);
        }


        $dompdf->load_html($html);

        // $dompdf->setPaper('A4', 'landscape');
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('enable_remote', TRUE);
        $dompdf->set_option('isRemoteEnabled', TRUE);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();


        $filename = "invoice" . rand(0, 1000);

        $dompdf->stream($filename, array("Attachment" => 1));

        return true;
    }

    public function send_subscription_canceled_mail($id, $subscr_id)
    {

        $flg = true;

        $this->loadModel('EmailTemplate');

        $emailtempname = 'subscription-canceled';

        $email = $this->EmailTemplate->selectTemplate($emailtempname);

        $firstName = $this->Auth->user('first_name');

        $lastName = $this->Auth->user('last_name');

        $url = SITEURL . 'users/transaction_view/' . $id;

        $emailFindReplace = array(

            '##NAME##' => $firstName . ' ' . $lastName,

            '##SITE_NAME##' => $this->siteName,

            '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),

            '##SUBSCRIPTION_ID##' => $subscr_id,

            '##TRANSACTION_LINK##' => $url,

            '##FROM_EMAIL##' => $email['from'],

            '##SITE_LOGO##' => Router::url(array(

                'controller' => 'img',

                'action' => '/',

                'logo.png',

                'admin' => false

            ), true)

        );

        $this->AWSSES->from = $email['title'] . " <" . $email['from'] . ">";

        $this->AWSSES->to = trim($this->Auth->user('email'));

        $this->AWSSES->subject = strtr($email['subject'], $emailFindReplace);

        $this->AWSSES->replayto = $email['reply_to_email'];

        $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

        if (!$this->AWSSES->_aws_ses()) {

            $this->Email->from = $email['title'] . " <" . $email['from'] . ">";

            $this->Email->replyTo = $email['reply_to_email'];

            $this->Email->to = $userData['StaffUser']['email'];

            $this->Email->subject = strtr($email['subject'], $emailFindReplace);

            $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';

            $description = strtr($email['description'], $emailFindReplace);

            if (!$this->Email->send($description))

                $flg = false;
        }

        return $flg;
    }

    public function cancel_subscription($id, $subscr_id, $redirect = 'dashboard', $reason = '')
    {
        try {

            if ($this->request->is("get")) {

                if (!empty($subscr_id)) {

                    $redirect = ($redirect == 'transaction_view') ? $redirect . '/' . $id : $redirect;

                    $subscr_id = 'I-T0VK1LDT1M17';

                    $api_url = (strip_tags(Configure::read('Site.payment.environment')) == 'live') ? 'https://api-3t.paypal.com/nvp' : 'https://api-3t.sandbox.paypal.com/nvp';

                    $action = 'Cancel';  //'I-4U125EKV9MR4';

                    $api_request = 'USER=' . urlencode('sb-vv4343t28037_api1.business.example.com')

                        .  '&PWD=' . urlencode('XL7LMF9QCGLYCEEW')

                        .  '&SIGNATURE=' . urlencode('AAt6oC33W1VsnoAwyub6beDvH8gmAKyZSQDcp-cwsDYOrNmT55XnUxRH')

                        .  '&VERSION=76.0'

                        .  '&METHOD=ManageRecurringPaymentsProfileStatus'

                        .  '&PROFILEID=' . urlencode($subscr_id)

                        .  '&ACTION=' . urlencode($action)

                        .  '&NOTE=' . urlencode($reason);

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $api_url); // For live transactions, change to 'https://api-3t.paypal.com/nvp'

                    curl_setopt($ch, CURLOPT_VERBOSE, 1);

                    // Uncomment these to turn off server and peer verification

                    // curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

                    // curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    curl_setopt($ch, CURLOPT_POST, 1);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_request);

                    $response = curl_exec($ch);

                    if (!$response)

                        throw new NotFoundException("Proccess failed due to technical error" . curl_error($ch) . '(' . curl_errno($ch) . ')');

                    curl_close($ch);

                    parse_str($response, $parsed_response);

                    if ($parsed_response['ACK'] != "Failure") {

                        if (!empty($reason)) {

                            $updateData['Transaction']['subscr_status'] = "0";

                            $updateData['Transaction']['reason_unsubscriber'] = $reason;

                            $updateData['Transaction']['id'] = $id;

                            if ($this->Transaction->save($updateData))

                                $this->send_subscription_canceled_mail($id, $subscr_id);
                        }

                        $message = "Your subscription has been Cancelled.";

                        $this->Session->setFlash($message, 'success');

                        $this->redirect(array('action' => $redirect));
                    } else if (isset($parsed_response['L_SEVERITYCODE0']) && $parsed_response['L_SEVERITYCODE0'] == 'Error') {

                        throw new NotFoundException("Proccess failed ! :" . $parsed_response['L_SHORTMESSAGE0']);
                    }
                } else {

                    throw new NotFoundException("Missing subscription id.");
                }
            } else {

                throw new NotFoundException(__('Invalid Method used.'));
            }
        } catch (Exception $exc) {

            $status = "failed";

            $message = $exc->getMessage();

            $this->Session->setFlash($message, 'error');

            $this->redirect(array('action' => $redirect));
        }
    }

    public function testing()
    {

        $this->AWSSES->to = "testdevlopertest123@gmail.com,testnetleon@gmail.com";

        $this->AWSSES->subject = "Testing with AWS subject.";

        $this->AWSSES->htmlMessage = "Testing with AWS email.";

        if ($this->AWSSES->_aws_ses()) {

            echo "success";
        } else {

            echo "failed";
        }

        die;
    }

    public function contact_us()
    {

        $this->set('title_for_layout', __('Contact us'));

        $this->set('title', "Contact us");

        $first_name = $this->Auth->user('first_name');

        $last_name = $this->Auth->user('last_name');

        $email = $this->Auth->user('email');

        $phone = $this->Auth->user('phone');

        $this->set(compact('first_name', 'last_name', 'email', 'phone'));
    }

    function testmail()
    {

        var_dump($this->AWSSES->_aws_ses());
        die;
    }
}
