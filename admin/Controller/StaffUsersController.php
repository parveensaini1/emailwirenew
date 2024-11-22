<?php

class StaffUsersController extends AppController
{

    public $name = 'StaffUsers';

    public $uses = array('StaffUser', 'EmailTemplate', 'Company', 'OrganizationType', 'Transaction', 'Country', 'PressRelease', "Category", "Msa", 'Cart', 'PlanCategory', 'Plan');

    public $components = array('Crm');

    public function beforeFilter()
    {

        parent::beforeFilter();

        $this->set('controller', 'staffUsers');

        $this->set('model', 'StaffUser');

        $this->Auth->allow('forgot');
    }



    public function dashboard()
    {   
        //echo "here";die();

        $this->layout = 'dashboard';

        $this->set('title_for_layout', 'Dashboard');

        $roleId = $this->Auth->user('staff_role_id');

        switch ($roleId) {

            case '1':

                $this->admin_dashboard();

                break;

            case '2':

                $this->editor_dashboard();

                $this->render('editor_dashboard');

                break;

            default:
                //added by ashish for testing
                $this->admin_dashboard();
                break;
        }
    }



    public function admin_dashboard()
    {

        $client_count = $this->StaffUser->find('count', array('conditions' => array('staff_role_id' => 3)));

        $subscriber_count = $this->StaffUser->find('count', array('conditions' => array('staff_role_id' => 4)));

        $newsroomConditions = array('Company.status' => 0);

        $this->Company->recursive = "-1";

        $fields = array('Company.id', 'Company.name', "Company.status");

        $pending_newsrooms = $this->Company->find("all", array('conditions' => $newsroomConditions, "fields" => $fields, 'order' => 'Company.id DESC', 'limit' => '5'));



        $approved_pr    =   $this->PressRelease->find("all", array('conditions' => array('PressRelease.status' => '1'), 'order' => 'PressRelease.id DESC', 'limit' => '5'));



        $pending_pr_count        = $this->PressRelease->find("count", array('conditions' => array('PressRelease.status' => '0')));

        $cout_pr_cart_payment    = $this->Cart->find("count", array('conditions' => array('Cart.cart_type' => 'pr')));

        $count_cart_payment      = $this->Cart->find("count", array('conditions' => array('Cart.cart_type' => 'plan')));



        $this->set(compact('client_count', 'subscriber_count', 'pending_newsrooms', 'approved_pr', 'pending_pr_count', 'cout_pr_cart_payment', 'count_cart_payment'));
    }



    public function editor_dashboard()
    {

        $user_id = $this->Auth->user('id');

        $checkStatus = $this->StaffUser->find('count', array('conditions' => array('StaffUser.id' => $user_id, 'status' => 2)));

        if ($checkStatus)

            $this->Session->setFlash("your account has been suspended. Please contact to admin.", 'error');
    }





    private function __save_ip()
    {

        $this->loadModel('IpLog');

        $ip_log = array();

        $ip_log['IpLog']['ip'] = $_SERVER['REMOTE_ADDR'];

        $ip_log['IpLog']['user_id'] = $this->Auth->user('id');

        $this->IpLog->save($ip_log['IpLog']);
    }



    public function check_user_status($email)
    {

        $conditions = array('StaffUser.email' => $email, 'StaffUser.status !=' => '1');

        $count = $this->StaffUser->find('count', array('conditions' => $conditions));
        if ($count == 1) {

            $this->Session->setFlash("your account suspended or delete. Please contact with admin.", 'error');

            $this->redirect($this->Auth->loginAction);
        }
    }

    public function login()
    {
        App::uses('CakeEmail', 'Network/Email');

        if (AuthComponent::user()) {
            return $this->redirect(array('action' => 'dashboard'));
			exit;
        }

        $this->layout = 'login';
        $this->set('title_for_layout', 'Login');

        if ($this->request->is('post')) {
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
            $this->sendyLogout();

            $this->check_user_status($this->request->data['StaffUser']['email']);

            $this->Auth->authenticate['Form'] = array('scope' => array('StaffUser.status' => '1'));
	
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

                if ($this->Auth->loggedIn()) {

                    $role = $this->Auth->user('StaffRole.title');

                    $this->Session->setFlash("$role login successfully.", 'success');

                    return $this->redirect(array('action' => 'dashboard'));
					
                }
            } else {

                $this->Session->setFlash(__($this->Auth->authError), 'error');

                $this->redirect($this->Auth->loginAction);
            }
        }

        if ($this->Cookie->check('StaffUser')) {

            $this->request->data['StaffUser']['email'] = $this->Cookie->read('StaffUser.email');

            $this->request->data['StaffUser']['password'] = $this->Cookie->read('StaffUser.password');

            $this->request->data['StaffUser']['remember_me'] = $this->Cookie->read('StaffUser.remember_me');
        }
    }



    public function forgot()
    {

        $this->layout = 'login';

        $this->set('title_for_layout', Configure::read('Site.name') . ' :: ' . __('Forgot password'));

        $this->StaffUser->recursive = -1;

        $this->loadModel('EmailTemplate');

        if (!empty($this->data)) {

            if (empty($this->data['StaffUser']['email'])) {

                $this->Session->setFlash(__('Please provide your email address that was used to register with us.'), 'error');
            } else {

                $email = $this->data['StaffUser']['email'];

                $fu = $this->StaffUser->find('first', array('conditions' => array('StaffUser.email' => $email)));

                if ($fu) {

                    if ($fu['StaffUser']['status']) {

                        $key = Security::hash(CakeText::uuid(), 'sha1', true);

                        $hash = sha1($fu['StaffUser']['email'] . rand(0, 20));

                        $url = SITEFRONTURL . '/users/reset/' . $key . '#' . $hash;

                        $ms = $url;

                        $ms = wordwrap($ms, 1000);



                        $fu['StaffUser']['tokenhash'] = $key;

                        $this->StaffUser->id = $fu['StaffUser']['id'];

                        if ($this->StaffUser->saveField('tokenhash', $fu['StaffUser']['tokenhash'])) {

                            $email = $this->EmailTemplate->selectTemplate('forgot_password');



                            $emailFindReplace = array(

                                '##NAME##' => ucfirst($fu['StaffUser']['first_name']) . " " . $fu['StaffUser']['last_name'],

                                '##SITE_NAME##' => Configure::read('Site.name'),

                                '##PASSWORD_RESET_LINK##' => $url,

                                '##SITE_LINK##' => FULL_BASE_URL . router::url('/', false),

                            );



                            $this->Email->from = $email['subject'] . "<" . $email['from'] . ">";

                            $this->Email->replyTo = $email['reply_to_email'];



                            $this->Email->to = $this->data['StaffUser']['email'];

                            $this->Email->subject = $email['subject'];

                            $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';



                            $this->Email->send(strtr($email['description'], $emailFindReplace));

                            $this->Session->setFlash(__('Check your email to reset your password.'), 'success');

                            $this->redirect(array('controller' => 'staffUsers', 'action' => 'login'));

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



    public function reset_password($token)
    {

        $this->set('title_for_layout', Configure::read('site.name') . ' :: ' . __('Reset password'));

        $this->StaffUser->recursive = -1;

        if (!empty($token)) {

            $u = $this->StaffUser->findBytokenhash($token);

            if ($u) {

                $this->StaffUser->id = $u['StaffUser']['id'];

                if (!empty($this->data)) {

                    $this->StaffUser->data = $this->data;

                    $this->StaffUser->data['StaffUser']['email'] = $u['StaffUser']['email'];

                    $new_hash = sha1($u['StaffUser']['email'] . rand(0, 20)); //created token

                    $this->StaffUser->data['StaffUser']['tokenhash'] = $new_hash;

                    print_r($new_hash);
                    die;

                    if ($this->StaffUser->validates(array('fieldList' => array('password', 'verify_password')))) {

                        if ($this->StaffUser->save($this->StaffUser->data)) {

                            $this->Session->setFlash(__('The password has been updated.'), 'success');

                            $this->redirect('/');
                        }
                    }
                }
            } else {

                $this->Session->setFlash(__('The forgotten password link has either expired or was used, please request a new password.'), 'error');
            }
        } else {

            $this->redirect('/');
        }
    }



    public function logout()
    {

        $this->Session->destroy();

        $this->sendyLogout();



        $this->Session->setFlash(__('Log out successfull.'), 'success');

        $this->redirect($this->Auth->logout());
    }



    public function sendyLogout($value = '')
    {
        /*Logout sendy dashboard*/

        $this->Session->delete('Staffuser.sendylogin');

        setcookie('logged_in', "", time() - 3600, '/');
    }



    public function change_password($id, $redirect = '')
    {

        $this->set('check_trash', $this->check_trash());

        $this->set('title_for_layout', __('Change password'));

        if (!$id && empty($this->request->data)) {

            $this->Session->setFlash(__('Invalid User'), 'default', array('class' => 'alert alert-danger'));
        }

        if (!empty($this->request->data)) {

            $this->request->data['StaffUser']['id'] = $id;

            if ($this->StaffUser->save($this->request->data)) {

                // $this->Session->destroy();

                $this->Session->setFlash(__('Password has changed successfully.'), 'success');

                $this->redirect("/$redirect");

                // $this->redirect(array('controller' => 'staffUsers', 'action' =>'change_password',$id,$redirect));

            } else {

                $this->Session->setFlash(__('Password could not be changed'), 'error');
            }
        }
    }



    public function user_password()
    {

        $this->set('title_for_layout', __('Change password'));

        $id = $this->Auth->user('id');

        $this->set('check_trash', $this->check_trash());



        if (!$id && empty($this->request->data)) {

            $this->Session->setFlash(__('Invalid User'), 'error');
        }

        if (!empty($this->request->data)) {

            $this->request->data['StaffUser']['id'] = $id;



            if ($this->StaffUser->save($this->request->data)) {

                $this->Session->destroy();

                $this->Session->setFlash(__('Password has changed successfully. Please login now.'), 'success');

                $this->redirect(array('action' => 'user_password'));
            } else {

                $this->Session->setFlash(__('Password could not be changed. Please try again.'), 'error');
            }

            // pr($this->request->data); die;

        }
    }



    public function profile()
    {

        $this->set('title_for_layout', 'User profile');


        if (!empty($this->request->data)) {

            if ($this->data['StaffUser']['new_profile_image']['name'] != '') {

                $date = date('Y') . DS . date('m');

                $file_path = WWW_ROOT . 'files' . DS . 'profile_image';

                if (!empty($this->request->data['StaffUser']['profile_image'])) {

                    $delfile = new File($file_path . DS . $this->request->data['StaffUser']['profile_image'], false, 0777);

                    $delfile->delete();
                }

                $profileImg = uniqid() . ".jpg";

                if (move_uploaded_file($this->data['StaffUser']['new_profile_image']['tmp_name'], $file_path . DS . $profileImg)) {

                    $this->Session->delete('Auth.User.profile_image');

                    $this->Session->write('Auth.User.profile_image', $profileImg);

                    unset($this->request->data['StaffUser']['profile_image']);

                    $this->request->data['StaffUser']['profile_image'] = $profileImg;
                }
            }

            $this->request->data['StaffUser']['id'] = $this->Auth->user('id');

            if ($this->StaffUser->save($this->request->data)) {

                //pr($this->data); die;

                $this->Session->setFlash(__('Detail successfully updated'), 'success');

                $this->redirect(array('action' => 'profile'));
            } else {

                $this->Session->setFlash(__('Detail could not be changed'), 'error');
            }
        } else {

            $this->request->data = $this->StaffUser->read(null, $this->Auth->user('id'));
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



    public function administrators($filter = "", $redirect = "")
    {

        $url = $this->request->url;

        $this->set('title_for_layout', __("Administrators list"));

        $keyword = '';

        $conditions[] = array('StaffUser.staff_role_id' => '1', 'StaffUser.status !=' => '4');

        if (!empty($this->params->query['keyword']) && $this->params->query['keyword'] != '') {

            $keyword = $this->params->query['keyword'];

            $conditions[] = array(

                'OR' => array(

                    'StaffUser.first_name like ' => '%' . $keyword . '%',

                    'StaffUser.last_name like ' => '%' . $keyword . '%',

                    'StaffUser.email like ' => '%' . $keyword . '%',

                )

            );
        }

        $this->set('keyword', $keyword);

        $this->StaffUser->recursive = "-1";

        $this->paginate = array('conditions' => $conditions, 'group' => 'StaffUser.id', 'order' => 'StaffUser.id desc', 'limit' => Configure::read('Admin.paging'));

        $data_array = $this->paginate('StaffUser');

        $check_trash = $this->check_trash();

        $menuadd = "Administrator";

        $this->set(compact('url', 'data_array', 'check_trash', 'menuadd'));
    }



    public function editors($filter = "", $redirect = "")
    {

        $url = $this->request->url;

        $this->set('title_for_layout', __("Editors list"));

        $keyword = '';



        $conditions[] = array('StaffUser.staff_role_id' => 2, 'StaffUser.status !=' => '4');

        if (!empty($this->params->query['keyword']) && $this->params->query['keyword'] != '') {

            $keyword = $this->params->query['keyword'];

            $conditions[] = array(

                'OR' => array(

                    'StaffUser.first_name like ' => '%' . $keyword . '%',

                    'StaffUser.last_name like ' => '%' . $keyword . '%',

                    'StaffUser.email like ' => '%' . $keyword . '%',

                )

            );
        }

        $this->set('keyword', $keyword);

        $this->StaffUser->recursive = "-1";

        $this->paginate = array('conditions' => $conditions, 'group' => 'StaffUser.id', 'order' => 'StaffUser.id desc', 'limit' => Configure::read('Admin.paging'));

        $data_array = $this->paginate('StaffUser');

        $check_trash = $this->check_trash();

        $menuadd = 'Editor';

        $userRoleId = "2";

        $userRoleName = "Editors";

        $this->set(compact('url', 'data_array', 'check_trash', 'menuadd', 'userRoleName', 'userRoleId'));
    }

    public function subscribers($filter = "")
    {

        $url = $this->request->url;

        $this->set('title_for_layout', __(ucfirst($url) . " list"));

        $keyword = '';

        $conditions[] = array('StaffUser.staff_role_id' => '4', 'StaffUser.status !=' => '4');

        if (!empty($this->params->query['keyword']) && $this->params->query['keyword'] != '') {

            $keyword = $this->params->query['keyword'];

            $conditions[] = array(

                'OR' => array(

                    'StaffUser.first_name like ' => '%' . $keyword . '%',

                    'StaffUser.last_name like ' => '%' . $keyword . '%',

                    'StaffUser.email like ' => '%' . $keyword . '%',

                )

            );
        }

        $this->set('keyword', $keyword);

        $this->StaffUser->recursive = "-1";

        $this->paginate = array('conditions' => $conditions, 'group' => 'StaffUser.id', 'order' => 'StaffUser.id desc', 'limit' => Configure::read('Admin.paging'));

        $data_array = $this->paginate('StaffUser');

        $check_trash = $this->check_trash();

        $menuadd = "Subscriber";

        $userRoleId = "4";

        $userRoleName = "Subscribers";

        $this->set(compact('url', 'data_array', 'check_trash', 'menuadd', 'userRoleName', 'userRoleId'));
    }





    public function clients($filter = "")
    {

        $this->loadModel('Transaction');

        $this->loadModel('CompaniesStaffUser');

        $url = $this->request->url;

        $this->set('title_for_layout', __("Clients list"));

        $keyword = '';

        $conditions[] = array('StaffUser.staff_role_id' => 3, 'StaffUser.status !=' => '4');

        if (!empty($this->params->query['keyword']) && $this->params->query['keyword'] != '') {

            $keyword = strip_tags($this->params->query['keyword']);



            $conditions[] = array(

                'OR' => array(

                    'StaffUser.first_name like ' => '%' . $keyword . '%',

                    'StaffUser.last_name like ' => '%' . $keyword . '%',

                    'StaffUser.email like ' => '%' . $keyword . '%',

                )

            );
        }

        if (!empty($this->params->query['filter']) && $this->params->query['filter'] != '') {

            $filter = strip_tags($this->params->query['filter']);



            if ('purchase' == $filter) {

                $conditions[] = array(

                    'OR' => array(

                        'StaffUser.pr_plan_paid' => '1',

                        'Transaction.status' => 'Success',

                    )

                );
            }

            if ('not_purchase' == $filter) {

                $conditions[] = array(

                    'AND' => array(

                        'StaffUser.pr_plan_paid' => '0',

                        'Transaction.status' => '',

                    )

                );
            }

            if ('confirmed_email' == $filter) {

                $conditions[] = array(

                    'StaffUser.email_confirmed' => '1',

                );
            }

            if ('not_confirmed_email' == $filter) {

                $conditions[] = array(

                    'StaffUser.email_confirmed' => '0',

                );
            }

            $this->set('selected_filter', $filter);
        }

        $joins = array(

            array(

                'table' => 'companies_staff_users',

                'alias' => 'CompaniesStaffUser',

                'type' => 'LEFT',

                'conditions' => 'StaffUser.id = CompaniesStaffUser.staff_user_id',

            ),

            array(

                'table' => 'transactions',

                'alias' => 'Transaction',

                'type' => 'LEFT',

                'conditions' => 'CompaniesStaffUser.company_id = Transaction.company_id',

            )

        );



        $this->set('keyword', $keyword);

        $this->paginate = array('joins' => $joins, 'conditions' => $conditions, 'group' => 'StaffUser.id', 'order' => 'StaffUser.id desc', 'limit' => Configure::read('Admin.paging'));

        $data_array = $this->paginate('StaffUser');



        $check_trash = $this->check_trash();

        $menuadd = "Client";

        $userRoleId = "3";

        $userRoleName = "Clients";

        $this->set(compact('url', 'data_array', 'check_trash', 'menuadd', 'userRoleId', 'userRoleName'));
    }





    public function trash($redirect = '')
    {

        $keyword = "";

        $this->set('title_for_layout', __('All Trash '));

        switch ($redirect) {

            case 'clients':

                $role_ids = 3;

                break;

            case 'subscribers':

                $role_ids = 4;

                break;

            case 'editors':

                $role_ids = 2;

                break;

            default:

                $role_ids = 1;

                break;
        }

        $conditions = array('StaffUser.status' => '4', 'StaffUser.staff_role_id ' => $role_ids);

        if (!empty($this->params->query['keyword']) && $this->params->query['keyword'] != '') {

            $keyword = $this->params->query['keyword'];

            $conditions = array(

                'or' => array(

                    'StaffUser.first_name like ' => '%' . $keyword . '%',

                    'StaffUser.email like ' => '%' . $keyword . '%'

                )

            );
        }

        $this->set('keyword', $keyword);

        $this->StaffUser->recursive = "-1";

        $this->paginate = array('conditions' => $conditions, 'group' => 'StaffUser.id', 'order' => 'StaffUser.id desc', 'limit' => '15');

        $data = $this->paginate('StaffUser');

        $this->set('check_trash', $this->check_trash());

        $this->set('data_array', $data);
    }

    public function check_trash()
    {

        switch ($this->request->url) {

            case 'clients':

                $role_ids = 3;

                break;

            case 'subscribers':

                $role_ids = 4;

                break;

            case 'editors':

                $role_ids = 2;

                break;

            default:

                $role_ids = 1;

                break;
        }



        $conditions = array('StaffUser.status' => '4', 'StaffUser.staff_role_id =' => $role_ids);

        return $this->StaffUser->find('count', array('conditions' => $conditions));
    }



    public function add($redirect = '')
    {

       

        $this->set('title_for_layout', __('Add a new ' . substr($redirect, 0, -1)));

        $this->set('check_trash', $this->check_trash());

        if (!empty($this->data)) {

           

            if ($this->StaffUser->save($this->request->data)) {

                $user_id = $this->StaffUser->getLastInsertID();



                $this->userAssignSendyTable($user_id, $this->data['StaffUser']['email'], $this->data['StaffUser']['first_name']);


                $crmData = [
                    "data" => [
                        [
                            "Last_Name" => $this->request->data['StaffUser']['last_name'],
                            "First_Name" => $this->request->data['StaffUser']['first_name'],
                            "Email" => $this->request->data['StaffUser']['email'],
                            "Phone" => $this->request->data['StaffUser']['phone'],
                        ]
                    ]
                ];
    
                // Send data to CRM
                $response = $this->Crm->createRecord($crmData);
              
              
                $this->Session->setFlash(__('Detail successfully added'), 'success');

                return $this->redirect("/$redirect");
              
            } else {

                $this->Session->setFlash(__('Detail not added. Please, try again.'), 'error');
            }
        }

        $this->loadModel('StaffRole');

        // $conditions=array('id'=>array("1","2"));   //array('id'=>2);

        // if($redirect=="subscribers"||$redirect=="clients"){

        //     $conditions=array('id'=>array("3","4"));

        // }

        switch ($redirect) {

            case 'clients':

                $conditions = array('id' => 3);

                break;

            case 'subscribers':

                $conditions = array('id' => 4);

                break;

            case 'editors':

                $conditions = array('id' => 2);

                break;

            default:

                $conditions = array('id' => 1);

                break;
        }

        $this->set('role', $this->StaffRole->find('list', array('conditions' => $conditions)));
    }



    public function userAssignSendyTable($id = "", $email = "", $first_name = "")
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://newsite.emailwire.com/sendy/includes/app/users.php");

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "login_email=$email&name=$first_name&id=$id");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
    }



    public function edit($id, $redirect = '')
    {

        App::uses('File', 'Utility');

        $this->set('check_trash', $this->check_trash());

        $this->set('title_for_layout', __('Add a new ' . substr($redirect, 0, -1)));

        if (!empty($this->data)) {

            if ($this->data['StaffUser']['staff_role_id'] == 2) {

                $saved = $this->StaffUser->saveAssociated($this->request->data, array('deep' => true));
            } else {

                $saved = $this->StaffUser->save($this->request->data);
            }

            if ($saved) {

                $this->Session->setFlash(__('Detail successfully updated'), 'success');

                return $this->redirect("/$redirect");
            } else {

                $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
            }
        } else {

            $this->request->data = $this->StaffUser->read(null, $id);
        }

        $this->loadModel('StaffRole');

        $this->loadModel('OrganizationType');

        $this->loadModel('Country');

        $country_list = $this->Country->find('list');



        // if($redirect=="subscribers"||$redirect=="clients"){

        //     $conditions=array('id'=>array("3","4"));

        // }else{

        //     $conditions=array('id'=>array("1","2"));    

        // }



        switch ($redirect) {

            case 'clients':

                $conditions = array('StaffRole.id' => 3);

                break;

            case 'subscribers':

                $conditions = array('StaffRole.id' => 4);

                break;

            case 'editors':

                $conditions = array('StaffRole.id' => 2);

                break;

            default:

                $conditions = array('StaffRole.id' => 1);

                break;
        }



        $role = $this->StaffRole->find('list', array('conditions' => $conditions));

        $organizationList = $this->OrganizationType->find('list');

        $this->set(compact('role', 'organizationList', "country_list"));
    }





    public function move_trash($id = null, $redirect)
    {

        $this->set('check_trash', $this->check_trash());

        $this->StaffUser->id = $id;

        if (!$this->StaffUser->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        if ($this->StaffUser->saveField('status', '4')) {

            $this->Session->setFlash(__('Detail successfully trashed'), 'success');

            return $this->redirect("/$redirect");
        }
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

            return $this->redirect("/$redirect");
        }
    }



    public function restore($id = null, $redirect = "")
    {

        $this->set('check_trash', $this->check_trash());

        $this->StaffUser->id = $id;

        if (!$this->StaffUser->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        if ($this->StaffUser->saveField('status', '1')) {

            $this->Session->setFlash(__('Detail successfully Restore.'), 'success');

            return $this->redirect("/$redirect");
        }
    }



    public function delete($id = null, $redirect)
    {

        $this->StaffUser->id = $id;

        if (!$this->StaffUser->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        if ($this->StaffUser->delete()) {

            $this->Session->setFlash(__('Detail successfully deleted'), 'success');

            return $this->redirect(array("controller" => "staffUsers", 'action' => "trash", $redirect));
        }
    }





    public function clientcompanies($id = null)
    {

        if ($id == null)

            $this->redirect(array('action' => 'users'));



        $this->StaffUser->recursive = "2";

        $data_array = $this->StaffUser->find('first', array('conditions' => array('StaffUser.id' => $id)));

        $this->set(compact("data_array"));
    }



    public function active_company($id = null, $staff_user_id = '')
    {

        $this->set('check_trash', $this->check_trash());

        $this->Company->id = $id;

        if (!$this->Company->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        $data['Company']['status'] = "1";

        if ($this->Company->save($data)) {

            $this->Session->setFlash(__('Company activated.'), 'success');

            $this->redirect(array("controller" => "staffUsers", 'action' => "clientcompanies", $staff_user_id));
        }
    }

    public function suspend_company($id = null, $staff_user_id = '')
    {

        $this->set('check_trash', $this->check_trash());

        $this->Company->id = $id;

        if (!$this->Company->exists()) {

            throw new NotFoundException('Invalid id', 'error');
        }

        $data['Company']['status'] = "2";

        if ($this->Company->save($data)) {

            $this->Session->setFlash(__('Company suspended.'), 'success');

            $this->redirect(array("controller" => "staffUsers", 'action' => "clientcompanies", $staff_user_id));
        }
    }





    public function edit_company($id = null)
    {

        App::uses('File', 'Utility');

        $this->set('title_for_layout', __('Edit Company'));

        if (!empty($this->data)) {



            if ($this->data['Company']['newlogo'] != '' && isset($this->data['Company']['newlogo']['name'])) {

                $date = date('Y') . DS . date('m');

                $file_path = ROOT . DS . 'app' . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'logo' . DS;

                $delfile = new File($file_path . $this->request->data['Company']['logo_path'] . DS . $this->request->data['Company']['logo'], false, 0777);

                unset($this->request->data['Company']['logo']);

                $dir = new Folder($file_path, true, 0755);

                $logo_name = uniqid() . ".jpg";

                if (move_uploaded_file($this->data['Company']['newlogo']['tmp_name'], $file_path . $date . DS . $logo_name)) {

                    unset($this->request->data['Company']['newlogo']);

                    $this->request->data['Company']['logo_path'] = date('Y') . "/" . date('m');

                    $this->request->data['Company']['logo'] = $logo_name;
                }
            }

            if ($this->data['Company']['newbanner_image'] != '' && isset($this->data['Company']['newbanner_image']['name'])) {

                $date = date('Y') . DS . date('m');

                $file_path = ROOT . DS . 'app' . DS . 'webroot' . DS . 'files' . DS . 'company' . DS . 'banner' . DS . $date;

                $dir = new Folder($file_path, true, 0755);

                $banner_image = uniqid() . ".jpg";

                if (move_uploaded_file($this->data['Company']['newbanner_image']['tmp_name'], $file_path . DS . $banner_image)) {

                    unset($this->request->data['Company']['banner_image']);

                    $this->request->data['Company']['banner_path'] = date('Y') . "/" . date('m');

                    $this->request->data['Company']['banner_image'] = $banner_image;
                }
            }

            unset($this->data['Company']['newlogo']);

            unset($this->data['Company']['newbanner_image']);

            if ($this->Company->save($this->request->data)) {

                $this->Session->setFlash(__('Detail successfully updated'), 'success');

                return $this->redirect(array('action' => "clientcompanies", $this->data['Company']['staff_user_id']));
            } else {
            }

            $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
        }

        $this->request->data = $this->Company->read(null, $id);



        $country_list = $this->Country->find('list');

        $organizationList = $this->OrganizationType->find('list');

        $this->set(compact('organizationList', "country_list"));
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



    public function press_release($user_id = null, $status = '')
    {

        //$status=(!empty($this->request->pass[0]))?$this->request->pass[0]:"0"; 'PressRelease.status' =>$status,

        $this->set('model', 'PressRelease');

        $conditions = array();

        $conditions[] = array("staff_user_id" => $user_id);



        $this->paginate = array('conditions' => $conditions, 'limit' => Configure::read('Site.paging'), 'order' => 'PressRelease.release_date DESC');

        $data_array = $this->paginate('PressRelease');

        $drafTitle = (count($data_array) > 1) ? "Drafts" : "Draft";

        switch ($status) {

            case '1':

                $title = 'Approved press release';

                break;

            case '3':

                $title = $drafTitle;

                break;

            case '4':

                $title = 'Disapproved press release';

                break;

            case '2':

                $title = 'Embargoed press release';

                break;

            default:

                $title = 'Press Releases';

                break;
        }

        $this->set('title_for_layout', $title);

        $this->set(compact('data_array', 'status', 'user_id', 'title'));
    }



    public function activated_plans($user_id)
    {

        $this->set('title_for_layout', __('Activated client plans'));

        $userDetail = $this->StaffUser->find('first', array('conditions' => array('StaffUser.id' => $user_id)));



        $currencySymbol = Configure::read('Site.currency');

        $this->PlanCategory->virtualFields['name'] = 'CASE WHEN word_limit >0 THEN CONCAT(PlanCategory.name, " - ",PlanCategory.word_limit," words") ELSE PlanCategory.name END';

        $plancat_list = $this->PlanCategory->find('list', array('fields' => array('id', 'name'), 'conditions' => array('status' => 1)));

        $plan_list = [];

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

                //   $getplan=Set::extract('/Plan/.', $getplan);

                if (!empty($getplan)) {

                    foreach ($getplan as $index => $plan) {



                        $price = ($plan['Plan']['bulk_discount_amount'] > 0) ? $currencySymbol . $plan['Plan']['bulk_discount_amount'] : $currencySymbol . $plan['Plan']['price'];

                        $number_pr = ($plan['Plan']['number_pr'] > 0) ? $plan['Plan']['number_pr'] : $plan['number_pr'];

                        $categoryname = preg_replace('/\d+/', '', str_replace(array("-", "words"), array("", ""), $planCatname));

                        $assignedFrom = ($plan['RemainingUserPlan']['assign_from'] != "fronted") ? "Assigned By : " . ucFirst($plan['RemainingUserPlan']['assign_from']) : "Purchased By : Client";



                        $plan_list[$planCatname][$plan['Plan']['id']] = "<div class='col-sm-7'>" . trim($categoryname) . " - $price (" . ($number_pr) . ")</div><div class='col-sm-2'><span class='mr-2'>(" . $plan['RemainingUserPlan']['number_pr'] . ")</span></div> <div class='pull-right col-sm-3'>$assignedFrom</div>";
                    }
                } else {

                    unset($plan_list[$planCatname]);
                }
            }
        }

        $this->set(compact('plan_list', 'userDetail'));
    }





    public function sendnotifications($role_id = '', $userRoleName = "")
    {

        $this->set('title_for_layout', __("Send notification to all $userRoleName"));

        $this->loadModel('EmailTemplate');

        $site_name = strip_tags(Configure::read('Site.name'));

        $data = $this->EmailTemplate->find('first', ['conditions' => ['id' => 30]]);



        if (!empty($this->data)) {



            $sendToString = "";

            $emails = $this->StaffUser->find("list", ["conditions" => ["StaffUser.staff_role_id" => $role_id, "StaffUser.status" => "1"], "fields" => ["id", "email"]]);

            if (!empty($emails)) {

                $sendToString = $comma = "";

                foreach ($emails as $key => $sendMail) {

                    $sendToString .= $comma . $sendMail;

                    $comma = ",";
                }
            }



            $newTitle = (!empty($this->data['StaffUser']['title'])) ? $this->data['StaffUser']['title'] : "";

            $subject = (!empty($this->data['StaffUser']['subject'])) ? $this->data['StaffUser']['subject'] : "";

            $this->AWSSES->from = $newTitle . " <" . $this->data['StaffUser']['from'] . ">";

            $this->AWSSES->to = $sendToString;

            $this->AWSSES->subject = $site_name . " : " . $subject;

            $this->AWSSES->replayto = $this->data['StaffUser']['reply_to_email'];

            $this->AWSSES->htmlMessage = $this->data['StaffUser']['description'];

            try {

                if (!$this->AWSSES->_aws_ses()) {

                    $sendTo = array_values($emails);

                    $this->Email->from = $newTitle . " <" . $this->data['StaffUser']['from'] . ">";

                    $this->Email->replyTo = $this->data['StaffUser']['reply_to_email'];

                    $this->Email->to = $sendTo;

                    $this->Email->subject = $subject;

                    $this->Email->sendAs = 'html';

                    $description = $this->data['StaffUser']['description'];

                    $this->Email->send($description);
                }

                $this->Session->setFlash(__("Notification mail successfully sent to all $userRoleName."), 'success');
            } catch (Exception $exc) {

                echo $exc->getTraceAsString();
            }
        }



        $this->set(compact('data', 'userRoleName', 'role_id', 'site_name'));
    }



    public function sendNotificationEmail($role_id = '')
    {

        $this->loadModel("StaffRole");

        //"StaffUser.id"=>["3","51"],

        $role = $this->StaffRole->find("first", ["conditions" => ["StaffRole.id" => $role_id], "fields" => ["title"]]);

        $emails = $this->StaffUser->find("list", ["conditions" => ["StaffUser.staff_role_id" => $role_id, "StaffUser.status" => "1"], "fields" => ["id", "email"]]);

        $site_name = strip_tags(Configure::read('Site.name'));

        $message = trim($this->request->query['reason']);



        $redirectRole = (substr($role["StaffRole"]['title'], -1) != "s") ? $role["StaffRole"]['title'] . "s" : $role["StaffRole"]['title'];

        if (!empty($emails)) {

            $sendToString = $comma = "";

            foreach ($emails as $key => $sendMail) {

                $sendToString .= $comma . $sendMail;

                $comma = ",";
            }

            $email = $this->Custom->get_email_template('emailwire-notification');

            $emailFindReplace = array(

                '##ROLE##' => $role["StaffRole"]['title'],

                "##MESSAGE##" => $message,

                '##SITE_NAME##' => $site_name,

            );

            $newTitle = (!empty($email['title'])) ? $email['title'] : $subject;

            $this->AWSSES->from = $newTitle . " <" . $email['from'] . ">";

            $this->AWSSES->to = $sendToString;

            $this->AWSSES->subject = $site_name . " : " . strtr($email['subject'], $emailFindReplace);

            $this->AWSSES->replayto = trim($email['reply_to_email']);

            $this->AWSSES->htmlMessage = strtr($email['description'], $emailFindReplace);

            try {

                if (!$this->AWSSES->_aws_ses()) {

                    $sendTo = array_values($emails);

                    $this->Email->from = $email['title'] . " <" . $email['from'] . ">";

                    $this->Email->replyTo = $email['reply_to_email'];

                    $this->Email->to = $sendTo;

                    $this->Email->subject = strtr($email['subject'], $emailFindReplace);

                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';

                    $description = strtr($email['description'], $emailFindReplace);

                    $this->Email->send($description);
                }
            } catch (Exception $exc) {

                echo $exc->getTraceAsString();
            }
        }

        $this->redirect(SITEURL . strtolower($redirectRole));
    }



    public function email_to_client($id = '')
    {

        $this->set('title_for_layout', __("Send Email"));

        $this->loadModel('EmailTemplate');

        $site_name = strip_tags(Configure::read('Site.name'));

        $data = $this->EmailTemplate->find('first', ['conditions' => ['id' => 30]]);



        if (!empty($this->data)) {



            $sendToString = "";

            $emails = $this->StaffUser->find("list", ["conditions" => ["StaffUser.id" => $id], "fields" => ["id", "email"]]);

            if (!empty($emails)) {

                $sendToString = $comma = "";

                foreach ($emails as $key => $sendMail) {

                    $sendToString .= $comma . $sendMail;

                    $comma = ",";
                }
            }



            $newTitle = (!empty($this->data['StaffUser']['title'])) ? $this->data['StaffUser']['title'] : "";

            $subject = (!empty($this->data['StaffUser']['subject'])) ? $this->data['StaffUser']['subject'] : "";

            $this->AWSSES->from = $newTitle . " <" . $this->data['StaffUser']['from'] . ">";

            $this->AWSSES->to = $sendToString;

            $this->AWSSES->subject = $site_name . " : " . $subject;

            $this->AWSSES->replayto = $this->data['StaffUser']['reply_to_email'];

            $this->AWSSES->htmlMessage = $this->data['StaffUser']['description'];

            try {

                if (!$this->AWSSES->_aws_ses()) {

                    $sendTo = array_values($emails);

                    $this->Email->from = $newTitle . " <" . $this->data['StaffUser']['from'] . ">";

                    $this->Email->replyTo = $this->data['StaffUser']['reply_to_email'];

                    $this->Email->to = $sendTo;

                    $this->Email->subject = $subject;

                    $this->Email->sendAs = 'html';

                    $description = $this->data['StaffUser']['description'];

                    $this->Email->send($description);
                }

                $this->Session->setFlash(__("Email successfully sent to $sendToString."), 'success');
            } catch (Exception $exc) {

                echo $exc->getTraceAsString();
            }
        }



        $this->set(compact('data', 'userRoleName', 'role_id', 'site_name'));
    }
}
