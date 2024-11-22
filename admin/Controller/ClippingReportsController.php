<?php
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Inflector', 'Utility');
use Dompdf\Dompdf;
class ClippingReportsController extends AppController{

    public $name = 'ClippingReports';

    public $uses = array('ClippingReport', "PressRelease", 'Campaign', 'Subscriber', 'Link', 'List', 'ClickThroughClient', 'NwRelationships', 'NwRelationship', 'NetworkWebsite');

    public function beforeFilter()
    {
        parent::beforeFilter();
        //pr($this->params); die;
        $this->set('menutitle', 'Clipping reports');
        $this->set('menutitle_add', 'Clipping report');
        $this->set('controller', 'ClippingReports');
        $this->set('model', 'ClippingReport');
    }

    public function index()
    {
        $this->loadModel('PressRelease');
        $model = "PressRelease";
        $this->set('title_for_layout', __('EmailWireClip™ Clipping Reports'));
        $data_array = [];
        $this->set('placeholder', 'Please enter title..');
        if (isset($this->request->params['named']['page']) && !empty($this->request->params['named']['page']) && isset($this->params->query['s']) && !empty($this->params->query['s'])) {
            $url = str_replace('/page:' . $this->request->params['named']['page'], '', $_SERVER['REQUEST_URI']);
            $url = str_replace('admin/', '', $url);
            $this->redirect($url);
        }
        if (isset($this->params->query['s']) && !empty($this->params->query['s'])) {
            $this->set('keyword', $this->params->query['s']);
            $conditions[] = array('PressRelease.title like ' => '%' . $this->params->query['s'] . '%');
        }
        $this->PressRelease->recursive = 1;
        $conditions[] = array('PressRelease.status' => '1');

        $this->PressRelease->unbindModel(

            array('belongsTo' => array('Plan', 'Company'), 'hasMany' => array('PressSeo', 'PressYoutube', 'PressImage', 'PressPoadcast'), 'hasAndBelongsToMany' => array('Category', 'Msa', 'State', 'Distribution'))

        );

        $this->paginate = array(
            'conditions' => $conditions, 'fields' => array('PressRelease.id', 'PressRelease.title', 'PressRelease.views', "PressRelease.release_date", "PressRelease.plan_id", "staff_user_id", 'StaffUser.email'),
            'limit' => 10, 'order' => 'PressRelease.release_date DESC'
        );

        $data_array = $this->paginate('PressRelease');

        $this->set(compact('data_array', "model"));

        $page_number = $this->request->paging['PressRelease']['page'];

        $this->set('page_number', $page_number);
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

    public function viewreport($prId = "")
    {
        $this->loadModel('PressRelease');
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



        // echo "<pre>"; print_r($data_array);       die;



        $title_for_layout = (!empty($pr_data)) ? 'Clipping report detail :-' . $pr_data['PressRelease']['title'] : 'Clipping report detail';



        $this->set(compact('data_array', "model", 'pr_data', 'prId', 'type', "msts", "title_for_layout"));
    }







    public function uploadreport($pId = "")
    {
        $this->PressRelease->recursive = -1;
        $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $pId), "fields" => array("id", "slug", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id", "approved_by")));
        if (empty($pr_data)) {
            $this->Session->setFlash(__('Invalid Request.'), 'error');
            return $this->redirect(array('action' => 'index'));
            exit;
        }
        $prTitle = $pr_data['PressRelease']['title'];
        $prSlug = Inflector::slug(strtolower($prTitle), '-');
        // $slug=Inflector::slug(strtolower($data[0]), '-');
        $this->set('title_for_layout', __("Upload Report for Clipping Report"));
        if ($this->request->is('post') && !empty($this->data) && !empty($pId)) {
            $totalSavedCount = $this->NwRelationship->find('count', array("conditions" => array('NwRelationship.press_release_id' => $pId)));

            if (!empty($this->data['ClippingReport']['xmlurl'])) {
                $getContent = @file_get_contents($this->data['ClippingReport']['xmlurl']);
                if ($getContent) {
                    $content = simplexml_load_file($this->data['ClippingReport']['xmlurl']);
                    if (isset($content->status_code) && $content->status_code == 100) {
                        $data_array = $content->media_outlets->item;
                        $count = count($content->media_outlets->item);
                        foreach ($data_array as $key => $data) {
                            $pressUrl = $data->url;
                            $domain = $this->Custom->get_domain($pressUrl);
                            $this->updateClippingReportByRss($pId, $domain, $pressUrl, 'network_feed');
                        }
                        $this->Session->setFlash(__('Report uploaded successfully.'), 'success');
                        return $this->redirect(array('action' => 'viewclippingreport', $pId));
                    } else {
                        $this->Session->setFlash(__('Please enter report url . Please, try again.'), 'error');
                    }
                } else {

                    $this->Session->setFlash(__('Please enter report url not valid. Please, try again.'), 'error');
                }
            } else {
                $filename = $this->data['ClippingReport']['csvurl']['name'];
                $file_size = $this->data['ClippingReport']['csvurl']['size'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $allowed = array('csv');
                if (!in_array($ext, $allowed)) {
                    $this->Session->setFlash(__('Please upload csv file only.'), 'error');
                } else if (($file_size > 10097152)) {
                    $this->Session->setFlash(__('uploaded file is greater than 10MB.'), 'error');
                } else {
                    $fname = $this->data['ClippingReport']['csvurl']['name'];
                    $chk_ext = explode(".", $fname);
                    if (strtolower(end($chk_ext)) == "csv") {
                        $filename = $this->data['ClippingReport']['csvurl']['tmp_name'];
                        $handle = fopen($filename, "r");
                        $update_data = array();
                        $row = 1;
                        if (($handle = fopen($filename, "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $savedata = [];
                                if (isset($data[0]) && !empty($data[0])) {
                                    $networkRelationshipsExist = $this->NwRelationship->find('first', array("conditions" => array('NwRelationship.press_release_id' => $pId, 'NwRelationship.press_release_link' => $data[0], 'NwRelationship.site_name' => $data[1])));
                                    if (!empty($networkRelationshipsExist)) {
                                        $savedata['NwRelationship']['id'] = $networkRelationshipsExist['NwRelationship']['id'];
                                    } else {
                                        $this->NwRelationship->create();
                                    }
                                    $match_keyword = parse_url($data[0], PHP_URL_HOST);
                                    $networkWebiste = $this->NetworkWebsite->find('first', array("conditions" => array('NetworkWebsite.website_name ' => $data[2], 'NetworkWebsite.status' => "1")));
                                    if (isset($networkWebsite) && !empty($networkWebsite)) {
                                        $savedata['NwRelationship']['network_website_id'] = $networkWebsite['NetworkWebsite']['id'];
                                        $savedata['NwRelationship']['press_release_id'] = $pId;
                                        $savedata['NwRelationship']['press_release_link'] = $data[0];
                                        $savedata['NwRelationship']['site_name'] = $data[1];
                                        if (isset($networkWebsite['NetworkWebsite']['website_logo']) && !empty($networkWebsite['NetworkWebsite']['website_logo'])) {
                                            $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/" . $networkWebsite['NetworkWebsite']['website_logo'];
                                        } else {
                                            $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/blank.jpg";
                                        }
                                        $savedata['NwRelationship']['type'] = $networkWebsite['NetworkWebsite']['website_media_type'];
                                        $savedata['NwRelationship']['location'] = $networkWebsite['NetworkWebsite']['website_location'];
                                        $savedata['NwRelationship']['potential_audience'] = $networkWebsite['NetworkWebsite']['potential_audience'];
                                    } else {
                                        $savedata['NwRelationship']['press_release_id'] = $pId;
                                        $savedata['NwRelationship']['press_release_link'] = $data[0];
                                        $savedata['NwRelationship']['site_name'] = $data[1];
                                        $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/blank.jpg";
                                        $savedata['NwRelationship']['type'] = '';
                                        $savedata['NwRelationship']['location'] = '';
                                        $savedata['NwRelationship']['order_num'] = ++$totalSavedCount; // only new insert
                                    }
                                    $this->NwRelationship->save($savedata);
                                }
                              
                            }
                            fclose($handle);
                            $this->Session->setFlash(__('Data imported successfully.'), 'success');
                            return $this->redirect(array('action' => 'viewclippingreport', $pId));
                        }
                    }
                }
            }
        }
        $staff_user_id = $pr_data['PressRelease']['staff_user_id'];
        $approved_user_id = $pr_data['PressRelease']['approved_by'];
        $this->loadModel('StaffUser');
        $this->StaffUser->recursive = -1;
        $userData = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $staff_user_id)));
        $approvedUser = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $approved_user_id)));
        $prTitle = $pr_data['PressRelease']['title'];
        $this->set(compact('approvedUser', 'pId', 'prTitle', 'userData'));
    }
    public function updateClippingReportByRss($prId = '', $hostname = '', $releasePageUrl = '', $distributionType = '')
    {
        $this->loadModel('ClippingReport');
        if (!empty($hostname)) {
            $site_name = $hostname;
            $extractHostname = explode(".", $hostname);
            if (is_array($extractHostname)) {
                $elementCount = count($extractHostname);
                $site_name = $extractHostname[$elementCount - 2];
            }
            $conditions = array(
                'ClippingReport.press_release_id' => $prId,
                'ClippingReport.site_name' => $site_name,
                'ClippingReport.distribution_type' => $distributionType
            );
            $check = $this->ClippingReport->find('first', array('conditions' => $conditions, "fields" => array("ClippingReport.id", "ClippingReport.views")));
            if (!empty($check['ClippingReport'])) {
                $data['ClippingReport']['id'] = $check['ClippingReport']['id'];
                $data['ClippingReport']['release_page_url'] = $releasePageUrl;
            } else {
                $data['ClippingReport']['distribution_type'] = $distributionType;
                $data['ClippingReport']['press_release_id'] = $prId;
                $data['ClippingReport']['domain'] = 'http://' . rtrim($hostname, "/");
                $data['ClippingReport']['site_name'] = ucfirst($site_name);
                $data['ClippingReport']['release_page_url'] = $releasePageUrl;
                $this->ClippingReport->create();
            }
            $this->ClippingReport->save($data);
        }
    }
    public function sendreport($prId = '', $orderBy = "potential_audience")
    {
        $this->ClippingReport->recursive = -1;
        $prconditions[] = array('ClippingReport.press_release_id' => $prId);
        if (!empty($this->request->query('type'))) {
            $prconditions[] = array('ClippingReport.distribution_type' => $this->request->query('type'));
        }
        if (!empty($this->request->query('sname'))) {
            $prconditions[] = array('ClippingReport.site_name' => $this->request->query('sname'));
        }
        $data_array = $this->ClippingReport->find('all', array('conditions' => $prconditions, 'order' => 'ClippingReport.id DESC'));
        $this->PressRelease->recursive = -1;
        $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id")));
        $this->loadModel('NwRelationships');
        $nwrelationships = $this->NwRelationships->find('all', array('conditions' => array('NwRelationships.press_release_id' => $prId, 'NwRelationships.status' => '1'), 'order' => 'NwRelationships.$orderBy DESC'));
        if ($this->generatePdfDownloadReceipt($data_array, $pr_data, $nwrelationships)) {
            $this->Session->setFlash(__('Your report successfully sent to the client.'), 'success');
            return $this->redirect(array('action' => 'index'));
        }
    }
    function generatePdfDownloadReceipt($data_array, $pr_data, $networkwebsites)
    {
        $staff_user_id = $pr_data['PressRelease']['staff_user_id'];
        $user_data = $this->getUserDetails($staff_user_id);
        $user_email = $user_data['email'];
        include_once(APP . 'Vendor' . DS . 'dompdf/autoload.inc.php');
        $dompdf = new Dompdf();
        $dompdf->set_option('enable_remote', TRUE);
        $this->loadModel('MaintenancePayment');
        $html = $this->Custom->getClippingReportViewHtmlSend($data_array, $pr_data['PressRelease'], $networkwebsites);
        // echo $html ;
        // die;
        $dompdf->load_html($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        //$dompdf->stream();    
        // $dompdf->Output(WWW_ROOT.$pr_data['PressRelease']['title'].".pdf", 'F');        
        // $dompdf->stream($pr_data['PressRelease']['title'].".pdf", array("Attachment" => true));        
        $file = $dompdf->output();
        file_put_contents($pr_data['PressRelease']['title'] . ".pdf", $file);
        App::uses('CakeEmail', 'Network/Email');
        $currency = Configure::read('Site.currency');
        $Email = new CakeEmail('default');
        $Email->from(array('emailwireweb@gmail.com' => "Email wire Clipping Report"));
        $Email->to($user_email);
        $Email->replyTo('emailwireweb@gmail.com');
        $Email->subject($pr_data['PressRelease']['title']);
        $Email->emailFormat('html');
        $Email->attachments($pr_data['PressRelease']['title'] . ".pdf");
        try {
            $mail_send = $Email->send();
            return true;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
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
    
    public function getUserDetails($id = ''){
        $this->loadModel('StaffUser');
        $data = $this->StaffUser->find('first', array('conditions' => array('StaffUser.id' => $id), "fields" => array("first_name", "last_name", "email")));
        return $data['StaffUser'];
    }

    public function subscriber_logs()
    {
        $this->loadModel('NewsletterLog');
        $this->set('title_for_layout', __('Press Release subscriber log'));
        $this->paginate = array('limit' => '15', 'group' => 'NewsletterLog.press_release_id', 'order' => 'NewsletterLog.id DESC');
        $data_array = $this->paginate('NewsletterLog');
        $this->set('data_array', $data_array);
    }

    public function subscriberlist($prId = '', $st = '')
    {
        $this->loadModel('NewsletterLog');
        $this->set('title_for_layout', __('Subscriber list'));
        $conditions['NewsletterLog.press_release_id'] = $prId;
        if (!empty($st)) {

            $conditions['StaffUser.subscriber_type'] = $st;
        }
        $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "title", "views", "release_date", "staff_user_id")));


        $this->paginate = array('conditions' => $conditions, 'limit' => '15', 'group' => "NewsletterLog.staff_user_id", 'order' => 'NewsletterLog.id DESC');

        $data_array = $this->paginate('NewsletterLog');
        $this->set(compact('data_array', 'st', 'prId', 'pr_data'));
    }  

    public function downloads($filename, $folder_name)
    {
        $folder_url = WWW_ROOT . 'files' . DS . $folder_name . DS . $filename;
        $this->response->file($folder_url, array('download' => true, 'name' => $filename));
        return $this->response;
    }

    public function settings($id = null)
    {
        $this->loadModel('PdfSetting');
        $this->set('title_for_layout', __('Pdf Setting'));
        if ($this->PdfSetting->validates($this->request->data)) {
            if (isset($this->request->data) && !empty($this->request->data)) {
                if (isset($this->request->data['PdfSetting']['logo']) && !empty($this->request->data['PdfSetting']['logo']) && !empty($this->request->data['PdfSetting']['logo']['name'])) {
                    
                    $url = Router::url(null, true);
                    $filename = $this->request->data['PdfSetting']['logo']['name'];
                    $file_size = $this->request->data['PdfSetting']['logo']['size'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $allowed = array('jpeg', 'jpg', 'png', 'gif', 'bmp');
                    if (!in_array($ext, $allowed)) {
                        $this->Session->setFlash(__("Please upload 'jpeg', 'jpg', 'png', 'gif', 'bmp' file only."), 'error');
                        return $this->redirect(array('action' => 'settings/' . $id));
                    } else if (($file_size > 2097152)) {
                        $this->Session->setFlash(__('uploaded file is greater than 2MB.'), 'error');
                        return $this->redirect(array('action' => 'settings/' . $id));
                    }
                    $imgInfo = pathinfo($this->request->data['PdfSetting']['logo']['name']);
                    $file_path = ROOT . DS . 'app' . DS . 'webroot' . DS . 'files' . DS . 'pdf_settings';
                    $this->request->data['PdfSetting']['logo']['name'] = $imgInfo['filename'] . '_users.' . $imgInfo['extension'];
                    $logoImageValue = $imgInfo['filename'] . '_' . time() . '.' . $imgInfo['extension'];
                    move_uploaded_file($this->request->data['PdfSetting']['logo']['tmp_name'], $file_path . '/' . $logoImageValue);
                    $this->request->data['PdfSetting']['logo'] = $logoImageValue;
                } else {
                    
                    if ($this->request->data['PdfSetting']['delete_logo'] == 1) {
                        $this->request->data['PdfSetting']['logo'] = '';
                    }else{
                        
                        $pdf_data = $this->PdfSetting->find('first', array("conditions" => array('PdfSetting.id' => $id)));
                        $this->request->data['PdfSetting']['logo'] = $pdf_data['PdfSetting']['logo'];
                    }
                }
                if ($this->PdfSetting->save($this->request->data)) {
                    $this->Session->setFlash(__('Detail successfully updated'), 'success');
                    //return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('Detail not updated. Please, try again.'), 'error');
                }
            } else {
                $this->request->data = $this->PdfSetting->read(null, $id);
            }
        }
    }

    public function uploadpdf($prId = null)
    {

        $this->set('title_for_layout', __('Upload Additional reports'));
        $this->loadModel("ClippingAdditionalReport");
        $this->set(compact('prId'));
    }

 
    public function uploadcsv($prId = null)
    {
        if ($this->request->is('post') && !empty($this->data)) {
            try {
                $this->set('title_for_layout', __("Upload CSV for Clipping Report"));
                $this->loadModel("NwRelationship");
                $this->loadModel("NetworkWebsite");
                $filename = $this->data['ClippingReport']['csvurl']['name'];
                $file_size = $this->data['ClippingReport']['csvurl']['size'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $allowed = array('csv');
                if (!in_array($ext, $allowed)) {
                    $this->Session->setFlash(__('Please upload csv file only.'), 'error');
                } else if (($file_size > 10097152)) {
                    $this->Session->setFlash(__('uploaded file is greater than 10MB.'), 'error');
                } else {
                    $totalSavedCount = $this->NwRelationship->find('count', array("conditions" => array('NwRelationship.press_release_id' => $prId)));

                    $fname = $this->data['ClippingReport']['csvurl']['name'];
                    $chk_ext = explode(".", $fname);
                    if (strtolower(end($chk_ext)) == "csv") {
                        $row = 0;
                        $filename = $this->data['ClippingReport']['csvurl']['tmp_name'];
                        // $handle = fopen($filename, "r");
                        // $dataArr = fgetcsv($handle, 0, "\r");
                        if (($handle = fopen($filename, "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                if ($row != 0) {
                                    if (isset($data[0])) {
                                        $savedata = [];
                                        $isNetworkRelationshipsExist = $this->NwRelationship->find('count', array("conditions" => array('NwRelationship.press_release_id' => $prId, 'NwRelationship.press_release_link' => $data[5], 'NwRelationship.site_name' => $data[0])));
                                        if ($isNetworkRelationshipsExist == 0) {
                                            // $match_keyword = parse_url($data[5], PHP_URL_HOST);
                                            $slug = Inflector::slug(strtolower(trim($data[0])), '-');
                                            $networkWebiste = $this->NetworkWebsite->find('first', array("conditions" => array('NetworkWebsite.slug ' => $slug, 'NetworkWebsite.status' => "1")));

                                            $country=(!empty($data[1]))?$data[1]:'';
                                            $state=(!empty($data[2]))?$data[2]:'';
                                            $city=(!empty($data[3]))?$data[3].', ':''; 

                                            $country=(!empty($country))?', '.$country:"";
                                            $location =$city.$state.$country;  
                                            if (isset($networkWebiste) && !empty($networkWebiste)) {
                                                $savedata['NwRelationship']['network_website_id'] = $networkWebiste['NetworkWebsite']['id'];
                                                $savedata['NwRelationship']['press_release_id'] = $prId;
                                                $savedata['NwRelationship']['press_release_link'] = $data[5];
                                                $savedata['NwRelationship']['site_name'] = $data[0];
                                                if (isset($networkWebiste['NetworkWebsite']['website_logo']) && !empty($networkWebiste['NetworkWebsite']['website_logo'])) {
                                                    $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/" . $networkWebiste['NetworkWebsite']['website_logo'];
                                                } else {
                                                    $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/blank.jpg";
                                                }
                                                $savedata['NwRelationship']['type'] = $data[4];
                                                $savedata['NwRelationship']['location'] = $location;
                                                $savedata['NwRelationship']['potential_audience'] = $networkWebiste['NetworkWebsite']['potential_audience'];
                                            } else {
                                                $savedata['NwRelationship']['press_release_id'] = $prId;
                                                $savedata['NwRelationship']['press_release_link'] = $data[5];
                                                $savedata['NwRelationship']['site_name'] = $data[0];
                                                $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/blank.jpg";
                                                $savedata['NwRelationship']['type'] = $data[4];
                                                $savedata['NwRelationship']['location'] = $location;
                                                $savedata['NwRelationship']['order_num'] = ++$totalSavedCount;
                                            } 
                                            $this->NwRelationship->create();
                                            $this->NwRelationship->save($savedata);
                                            unset($savedata);
                                        }
                                    }
                                }
                                $row++;
                            }
                            fclose($handle);
                            $this->Session->setFlash(__('Data imported successfully.'), 'success');
                            return $this->redirect(array('action' => 'viewclippingreport', $prId));
                          
                        } else {

                            $this->Session->setFlash(__('CSV file not valid!'), 'error');
                        }
                    }
                }
            } catch (Exception $error) {

                echo $error->getMessage();

                die;
            }
        }
        $this->PressRelease->recursive = -1;
        $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id", "approved_by")));
        $staff_user_id = $pr_data['PressRelease']['staff_user_id'];
        $approved_user_id = $pr_data['PressRelease']['approved_by'];
        $this->loadModel('StaffUser');
        $this->StaffUser->recursive = -1;
        $userData = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $staff_user_id)));
        $approvedUser = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $approved_user_id)));
        $prTitle = $pr_data['PressRelease']['title'];
        $this->set(compact('approvedUser', 'prId', 'userData', 'prTitle'));
    }
    

    public function uploadadditionalxml($prId = null)
    {
        $pr_id = $prId;
        $this->PressRelease->recursive = -1;
        $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "slug", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id", "approved_by")));
        if (empty($pr_data)) {
            $this->Session->setFlash(__('Invalid Request.'), 'error');
            return $this->redirect(array('action' => 'index'));
            exit;
        }
        $prTitle = $pr_data['PressRelease']['title'];
        $prSlug = Inflector::slug(strtolower($prTitle), '-');
        $this->set('title_for_layout', __("Upload XML for Clipping Report"));
        $this->loadModel("ClippingAdditionalXml");
        if (!empty($this->data)) {
            $this->loadModel("NwRelationship");
            $this->loadModel("NetworkWebsite");
            $rss_feed = simplexml_load_file($this->data['ClippingReport']['xml_url']);
            if (!empty($rss_feed->channel)) {
                $getPrSlug = Inflector::slug(strtolower($rss_feed->channel->title), '-');
                if ($prSlug != $getPrSlug) {
                    $this->Session->setFlash("This xml URL is not for $prTitle", 'error');
                    return $this->redirect(array('action' => 'viewclippingreport', $prId));
                    exit;
                }
                $totalSavedCount = $this->NwRelationship->find('count', array("conditions" => array('NwRelationship.press_release_id' => $prId)));
                foreach ($rss_feed->channel->item as $feed_item) {
                    $match_keyword = parse_url($feed_item->link, PHP_URL_HOST);
                    $networkWebiste = $this->NetworkWebsite->find('first', array("conditions" => array('NetworkWebsite.website_domain LIKE' => '%' . $match_keyword . '%')));
                    $isNetworkRelationshipsExist = $this->NwRelationship->find('first', array("conditions" => array('NwRelationship.press_release_id' => $prId, 'NwRelationship.press_release_link' => (string) $feed_item->link, 'NwRelationship.site_name LIKE ' => '%' . (string) $feed_item->title . '%')));
                    if (!empty($isNetworkRelationshipsExist)) {
                        $savedata['NwRelationship']['id'] = $isNetworkRelationshipsExist['NwRelationship']['id'];
                    } else {
                        $this->NwRelationship->create();
                    }
                    if (isset($networkWebiste) && !empty($networkWebiste)) {
                        $savedata['NwRelationship']['network_website_id'] = $networkWebiste['NetworkWebsite']['id'];
                        $savedata['NwRelationship']['press_release_id'] = $prId;
                        $savedata['NwRelationship']['press_release_link'] = (string)$feed_item->link;
                        $savedata['NwRelationship']['site_name'] = (string) $feed_item->title;
                        if (!empty($networkWebiste['NetworkWebsite']['website_logo'])) {
                            $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/" . $networkWebiste['NetworkWebsite']['website_logo'];
                        } else {
                            $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/blank.jpg";
                        }
                        $savedata['NwRelationship']['type'] = $networkWebiste['NetworkWebsite']['website_media_type'];
                        $savedata['NwRelationship']['location'] = $networkWebiste['NetworkWebsite']['website_location'];
                        $savedata['NwRelationship']['potential_audience'] = $networkWebiste['NetworkWebsite']['potential_audience'];
                    } else {
                        $savedata['NwRelationship']['id'] = '';
                        $savedata['NwRelationship']['network_website_id'] = 0;
                        $savedata['NwRelationship']['press_release_id'] = $prId;
                        $savedata['NwRelationship']['press_release_link'] = (string) $feed_item->link;
                        $savedata['NwRelationship']['site_name'] = (string)$feed_item->title;
                        $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/blank.jpg";
                        $savedata['NwRelationship']['type'] = '';
                        $savedata['NwRelationship']['location'] = '';
                        $savedata['NwRelationship']['potential_audience'] = 0;
                        $savedata['NwRelationship']['order_num'] = ++$totalSavedCount;
                    }
                    // pr($savedata);die;
                    $this->NwRelationship->save($savedata);
                }
                $clipping_additional_xml['ClippingAdditionalXml']['id'] = '';
                $clipping_additional_xml['ClippingAdditionalXml']['pr_id'] = $prId;
                $clipping_additional_xml['ClippingAdditionalXml']['xml_url'] = $this->data['ClippingReport']['xml_url'];
                $this->ClippingAdditionalXml->save($clipping_additional_xml);
            }
            return $this->redirect(array('action' => 'viewclippingreport', $pr_id));
        }
        $clipping_additional_xml_data = $this->ClippingAdditionalXml->find('all', array("conditions" => array('pr_id' => $prId), array('order' => 'ClippingAdditionalXml.created DESC')));
        $this->set(compact('clipping_additional_xml_data'));
        $staff_user_id = $pr_data['PressRelease']['staff_user_id'];
        $approved_user_id = $pr_data['PressRelease']['approved_by'];
        $this->loadModel('StaffUser');
        $this->StaffUser->recursive = -1;
        $user_data = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $staff_user_id)));
        $approved_data = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $approved_user_id)));
        $user_datas = $user_data;
        $approved_datas = $approved_data;
        $this->set(compact('user_datas'));
        $this->set(compact('approved_datas'));
        $this->set(compact('pr_id'));
        $this->set('pr_title', $pr_data['PressRelease']['title']);
    }
    public function viewclippingreport($prId = '', $orderBy = 'potential_audience')
    {
        $deleteId = $this->request->query('delete');
        if($deleteId){
            $this->loadModel('NwRelationship');
            $this->NwRelationships->id = $deleteId;
            $entity['press_release_link'] = null;
    
            if ($this->NwRelationships->save($entity)) {
                // Success: Redirect to the previous URL
                $referer = $this->request->referer();
                return $this->redirect($referer);
            } else {
                // Handle the error (optional)
                $this->Flash->error(__('The URL could not be deleted. Please, try again.'));
            }
            
            $referer = $this->request->referer();
            return $this->redirect($referer);
        }
        $orderBy = 'order_num';
        $this->PressRelease->recursive = 2;
        $socialShareCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` NOT IN ('email','print') AND press_release_id=PressRelease.id";
        $emailCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` IN ('email') AND press_release_id=PressRelease.id";
        $printCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='1' AND `domain` IN ('print') AND press_release_id=PressRelease.id";
        $networkFeedCountSql = "select SUM(clicked) FROM " . $this->ClickThroughClient->table . " where type='2' AND press_release_id=PressRelease.id";
        $potentialAudienceCountSql = "select SUM(potential_audience) FROM " . $this->NwRelationships->table . " where press_release_id=PressRelease.id AND `status`='1' ";

        $data = $this->PressRelease->find("first", array(
            'conditions' => array('PressRelease.id' => $prId),
            "fields" => array(
                "($socialShareCountSql) as socialShareCount", "($networkFeedCountSql) as networkFeedCount", "($potentialAudienceCountSql) as potentialAudienceCount",
                "($emailCountSql) as emailCount", "($printCountSql) as printCount",
                "id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id", "approved_by"
            )

        ));
        $staff_user_id = $data['PressRelease']['staff_user_id'];
        $approved_user_id = $data['PressRelease']['approved_by'];
        $this->loadModel('StaffUser');
        $this->StaffUser->recursive = -1;
        $userData = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $staff_user_id)));
        $approvedUser = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $approved_user_id)));
        /* $html = $this->generateClippingHtml($data_array, $pr_data, $nwrelationships, $user_data, $approved_data);*/
        $this->set(compact('prId', 'approvedUser', 'userData'));
        // $nwrelationships = $this->NwRelationships->find('all', array('conditions' => array('NwRelationships.press_release_id' => $prId, 'NwRelationships.status' => '1'), 'order' => "order_num DESC"));

        // order_num,
        $this->paginate = array('conditions' => array('NwRelationships.press_release_id' => $prId, 'NwRelationships.status' => '1'), 'order' => "order_num ASC", 'limit' => $this->limit);
        $nwrelationships = $this->paginate('NwRelationships');
        $html = "";
        //$html = $this->generateClippingHtml($data_array, $data, $nwrelationships);

        $this->loadModel('PdfSetting');

        $pdf_data = $this->PdfSetting->find('first', array('conditions' => array('PdfSetting.id' => '1')));

        $pdfEmailDescription      =   $pdf_data['PdfSetting']['email_distribution_description'];

        $pdfNetworkDescription    =   $pdf_data['PdfSetting']['network_description'];

        $sumOfPotentialAudience = (!empty($data['0']['potentialAudienceCount']) && $data['0']['potentialAudienceCount'] > 0) ? $this->Custom->numberFormatAsUs($data['0']['potentialAudienceCount']) : 0;

        $rowCountPotentialAudience = count($nwrelationships);
 
     
        $dateformat = strip_tags(Configure::read('Site.DateFromat'));

        $this->set(compact('prId','orderBy','pdf_data', 'data', 'dateformat', 'nwrelationships', 'pdfEmailDescription', 'pdfNetworkDescription','sumOfPotentialAudience'));
    }

    function generateClippingHtml($data_array, $pr_data, $networkwebsites, $user_data, $approved_data)
    {
        $this->loadModel('MaintenancePayment');
        $html = $this->Custom->getClippingReportViewHtml($data_array, $pr_data['PressRelease'], $networkwebsites, $user_data, $approved_data);
        return $html;
    }

    public function download($prId = '')
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
            $nwrelationships = $this->NwRelationships->find('all', array('conditions' => array('NwRelationships.press_release_id' => $prId, 'NwRelationships.status' => '1'), 'order' => 'NwRelationships.potential_audience DESC'));
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



    /*

   * @params: 
   * @Function use: generatePdfDownloadReceiptScreen: Agenerate pdf
   * @created by: Hitesh verma
   * @Created: 16-10-2022
   */
    function generatePdfDownloadReceiptScreen($prData = [], $networkwebsites = [], $data_array = [])
    {
        $staff_user_id = $prData['PressRelease']['staff_user_id'];
        $user_data = $this->getUserDetails($staff_user_id);
        $user_email = $user_data['email'];
        include_once(APP . 'Vendor' . DS . 'dompdf/autoload.inc.php');
        $dompdf = new Dompdf();
        $dompdf->set_option('enable_remote', TRUE);
        $html = $this->Custom->getClippingReportViewHtmlSend($prData, $networkwebsites, $data_array);
        $dompdf->load_html($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        //$dompdf->stream();    
        // $dompdf->Output(WWW_ROOT.$prData['PressRelease']['title'].".pdf", 'F');        
        $dompdf->stream($prData['PressRelease']['slug'] . ".pdf", array("Attachment" => true));
        //$file = $dompdf->output();
        // file_put_contents($prData['PressRelease']['title'] . ".pdf", $file);
    }

    public function trash($model = "ClippingReport", $id = null, $redirectAction = null, $prId = null)
    {
        try {

            $this->loadModel($model);

            if (!$id) {

                throw new NotFoundException('Invalid request.');
            }

            $id = base64_decode($id);

            $this->{$model}->id = $id;

            if (!$this->{$model}->exists()) {

                throw new NotFoundException('Invalid request.');
            }
            

            if ($this->movetotrash($model, $id)) {

                $this->clearcache('bhamashah_donation_list');

                $this->Session->setFlash(__('Detail successfully deleted'), 'success');

                if (!empty($redirectAction)) {

                    $prId = base64_decode($prId);

                    $this->redirect(array("controller" => "clippingReports", 'action' => $redirectAction, $prId));
                } else {

                    $this->redirect(array('action' => 'index'));
                }
            }
        } catch (Exception $exc) {

            $this->Session->setFlash($exc->getMessage(), 'error');

            $this->redirect(array('action' => 'index'));
        }

        exit;
    }

    public function trashed($model = "ClippingReport")

    {

        $this->loadModel($model);

        $this->set('title_for_layout', __("All $model"));

        $keyword = '';

        $conditions[] = array("$model.status" => $this->trashStatus);



        $this->Prg->commonProcess();

        $searchValues = $this->{$this->modelClass}->data[$this->modelClass] = $this->passedArgs;

        $keyword = (!empty($searchValues) && !empty($searchValues["name"])) ? $searchValues["name"] : "";

        $conditions[] = $this->{$this->modelClass}->parseCriteria($this->passedArgs);

        $this->set('keyword', $keyword);

        $this->loadModel('District');

        $district_list = $this->District->find('list', array('conditions' => array('status' => 1)));

        $this->set('district_list', $district_list);

        $this->paginate = array('conditions' => $conditions, 'order' => '$model.id DESC', 'limit' => $this->limit);

        $data = $this->paginate($model);

        $this->set('data_array', $data);

        $this->render("index");
    }



    public function restore($model = "ClippingReport", $id = null)

    {

        $this->loadModel($model);

        try {

            if (!$id) {

                throw new NotFoundException('Invalid request.');
            }

            $id = base64_decode($id);

            $this->{$model}->id = $id;

            if (!$this->{$model}->exists()) {

                throw new NotFoundException('Invalid request.');
            }

            if ($this->restorefromtrash($model, $id)) {

                $this->clearcache('$model_list');

                $this->Session->setFlash(__('Detail successfully restore.'), 'success');

                $this->redirect(array('action' => 'index'));
            }
        } catch (Exception $exc) {

            $this->Session->setFlash($exc->getMessage(), 'error');

            $this->redirect(array('action' => 'index'));
        }
    }

    /*

   * @params: 

   * @Function use: addNewRowClippingReport: Add new row clipping

   * @created by: Hitesh verma

   * @Created: 16-10-2022

   */

    function addNewRowClippingReport($prId = "")
    {
        try {
            if (!$prId) {
                throw new NotFoundException('Invalid request.');
            }
            $prId = base64_decode($prId);
            $savedata['NetworkWebsite']['press_release_id'] = $prId;
            $savedata['NetworkWebsite']['website_logo'] = "blank.jpg";
            $savedata['NetworkWebsite']['website_name'] = "Add Media Here" . rand(1, 100);
            $savedata['NetworkWebsite']['potential_audience'] = 9999999;
            $savedata['NetworkWebsite']['website_location'] = null;
            $savedata['NetworkWebsite']['website_media_type'] = 'Newspaper';
            $networkWebsiteData = $this->NetworkWebsite->save($savedata);
            if ($networkWebsiteData) {
                $totalSavedCount = $this->NwRelationship->find('count', array("conditions" => array('NwRelationship.press_release_id' => $prId)));
                $sv['press_release_id'] = $prId;
                $sv['network_website_id'] = $networkWebsiteData['NetworkWebsite']['id'];
                $sv['site_name'] = $savedata['NetworkWebsite']['website_name'];
                $sv['type'] = " Newspaper";
                $sv['potential_audience'] = 9999999;
                $sv['type'] = " website";
                $sv['site_logo'] = "files/networkwebsite/blank.jpg";
                $sv['order_num'] = $totalSavedCount + 1;
                $this->NwRelationships->save($sv);
            }
            $this->redirect(array('action' => 'viewclippingreport', $prId));
        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            if (!empty($prId)) {
                $this->redirect(array('action' => 'viewclippingreport', $prId));
            } else {

                $this->redirect(array('action' => 'index'));
            }
        }

        exit;
    }
    

    public function uploadClippingReportByJson($prId = "")

    {

        try {

            if (!$prId) {

                throw new NotFoundException('Invalid request.');
            }



            $this->PressRelease->recursive = -1;

            $prData = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "slug", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id", "approved_by")));



            if (empty($prData)) {

                throw new NotFoundException('Invalid request.');
            }

            $prTitle = $prData['PressRelease']['title'];

            $prSlug = Inflector::slug(strtolower($prTitle), '-');



            $this->set('title_for_layout', __("Upload report for $prTitle"));



            $curlErrorMsg = "";

            $this->loadModel("NetworkWebsite");

            $this->loadModel("NwRelationship");

            // $this->set('title_for_layout', __('Upload Clipping report by API')); 

            if ($this->request->is('post') && !empty($this->data) && !empty($prId)) {

                if (!empty($this->data['ClippingReport']['api_url'])) {

                    $api = $this->data['ClippingReport']['api_url'];

                    $ch    = curl_init();

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:  application/json"));

                    curl_setopt($ch, CURLOPT_URL, $api);

                    $output = curl_exec($ch);

                    if (curl_errno($ch)) {

                        $curlErrorMsg = curl_error($ch);
                    }

                    curl_close($ch);

                    if (!empty($curlErrorMsg)) {

                        throw new NotFoundException('Error!' . $curlErrorMsg);
                    }



                    $results = json_decode($output);



                    if (!empty($results)) {

                        if (is_array($results)) {

                            foreach ($results as $result) {

                                $this->saveJsonFormattedData($result->items, $prId, $prSlug);
                            }
                        } else {

                            $this->saveJsonFormattedData($results->items, $prId, $prSlug);
                        }



                        $this->loadModel('ClippingAdditionalXml');

                        $clippingAdditionalApi = [];

                        $clippingAdditionalApi['ClippingAdditionalXml']['id'] = '';

                        $clippingAdditionalApi['ClippingAdditionalXml']['pr_id'] = $prId;

                        $clippingAdditionalApi['ClippingAdditionalXml']['api_url'] = $this->data['ClippingReport']['api_url'];

                        $this->ClippingAdditionalXml->save($clippingAdditionalApi);

                        $this->Session->setFlash(__('Data imported successfully.'), 'success');

                        return $this->redirect(array('action' => 'viewclippingreport', $prId));
                    }
                }
            }

            $staff_user_id = $prData['PressRelease']['staff_user_id'];

            $approved_user_id = $prData['PressRelease']['approved_by'];

            $this->loadModel('StaffUser');

            // $this->StaffUser->recursive = -1;

            // $userData = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $staff_user_id)));

            $approvedUser = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $approved_user_id)));

            $this->set(compact('approvedUser', 'prId', 'approvedUser', 'prData'));
        } catch (Exception $exc) {

            $this->Session->setFlash($exc->getMessage(), 'error');

            if (!empty($prId)) {

                $this->redirect(array('action' => 'viewclippingreport', $prId));
            } else {

                $this->redirect(array('action' => 'index'));
            }
        }
    }



    private function saveJsonFormattedData($items = [], $prId, $prSlug)
    {
        if (!empty($items)) {
            $totalSavedCount = $this->NwRelationship->find('count', array("conditions" => array('NwRelationship.press_release_id' => $prId)));
            foreach ($items as $data) {
                $getPrSlug = Inflector::slug(strtolower($data->headline), '-');
                if ($prSlug != $getPrSlug) {
                    continue;
                } else {
                    $networkRelationshipsExist = $this->NwRelationship->find('first', array("conditions" => array('NwRelationship.press_release_id' => $prId, 'NwRelationship.press_release_link' => $data->link, 'NwRelationship.site_name' => $data->name)));
                    if (!empty($networkRelationshipsExist)) {
                        $savedata['NwRelationship']['id'] = $networkRelationshipsExist['NetworkWebsite']['id'];
                    } else {
                        $this->NwRelationship->create();
                    }
                    $networkWebsite = $this->NetworkWebsite->find('first', array("conditions" => array('NetworkWebsite.website_name ' => $data->name, 'NetworkWebsite.status' => "1")));
                    if (isset($networkWebsite) && !empty($networkWebsite)) {
                        $savedata['NwRelationship']['network_website_id'] = $networkWebsite['NetworkWebsite']['id'];
                        if (isset($networkWebsite['NetworkWebsite']['website_logo']) && !empty($networkWebsite['NetworkWebsite']['website_logo'])) {
                            $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/" . $networkWebsite['NetworkWebsite']['website_logo'];
                        } else {
                            $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/blank.jpg";
                        }
                        $savedata['NwRelationship']['type'] = $networkWebsite['NetworkWebsite']['website_media_type'];
                        $savedata['NwRelationship']['location'] = $networkWebsite['NetworkWebsite']['website_location'];
                        $savedata['NwRelationship']['potential_audience'] = $networkWebsite['NetworkWebsite']['potential_audience'];
                    } else {
                        $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/blank.jpg";
                    }
                    $savedata['NwRelationship']['press_release_id'] = $prId;
                    $savedata['NwRelationship']['press_release_link'] = $data->link;
                    $savedata['NwRelationship']['site_name'] = $data->name;
                    $savedata['NwRelationship']['order_num'] = ++$totalSavedCount;
                    $this->NwRelationship->save($savedata);
                }
            }
        }
        return true;
    }

    public function upload_site_logo($prId = "")
    {
        try {
            if (!$prId) {
                throw new NotFoundException('Invalid request.');
            }
            $this->loadModel("NwRelationships");
            // $this->set('title_for_layout', __('Upload Clipping report by API'))
            if ($this->request->is('post') && !empty($this->data) && !empty($prId)) {
                $dirFile = ROOT . DS . 'app' . DS . 'webroot' . DS . 'files' . DS . 'networkwebsite';
                $dir = new Folder($dirFile, true, 0755);
                if (!empty($this->request->data['NwRelationships']['site_logo']['tmp_name'])) {
                    $ext = pathinfo($this->request->data['NwRelationships']['site_logo']['name'], PATHINFO_EXTENSION);
                    $fileName = Inflector::slug(strtolower($this->request->data['NwRelationships']['site_logo']['name']), '-') . ".$ext";
                    $moved = move_uploaded_file($this->request->data['NwRelationships']['site_logo']['tmp_name'], $dirFile . DS . $fileName);
                    $filename = $this->request->data['NwRelationships']['site_logo']['name'];
                    unset($this->request->data['NwRelationships']['site_logo']);
                    $this->request->data['NwRelationships']['site_logo'] = "files/networkwebsite/" . $fileName;
                } else {
                    $this->request->data['NwRelationships']['site_logo'] = null;
                    unset($this->request->data['NwRelationships']['site_logo']);
                }

                if ($this->NwRelationships->save($this->request->data['NwRelationships'])) {
                    if (!empty($this->request->data['NetworkWebsite']['id'])) {
                        $saveData['NetworkWebsite']['website_logo'] = $fileName;
                        $saveData['NetworkWebsite']['id'] = $this->request->data['NetworkWebsite']['id'];
                        $this->NetworkWebsite->save($saveData);
                    } else {
                        $this->request->data['NetworkWebsite']['id'] = (!empty($this->request->data['NetworkWebsite']['id'])) ? $this->request->data['NetworkWebsite']['id'] : null;
                        $this->request->data['NetworkWebsite']['website_logo'] = $fileName;
                        $this->NetworkWebsite->save($this->request->data['NetworkWebsite']);
                    }
                }
                $this->Session->setFlash(__('Data imported successfully.'), 'success');
                return $this->redirect(array('action' => 'viewclippingreport', $prId));
            }
        } catch (Exception $exc) {

            $this->Session->setFlash($exc->getMessage(), 'error');

            if (!empty($prId)) {

                $this->redirect(array('action' => 'viewclippingreport', $prId));
            } else {

                $this->redirect(array('action' => 'index'));
            }
        }
    }


    /*
   * @params: 
   * @Function use: moverecord: Add new row clipping
   * @created by: Hitesh verma
   * @Created: 12-11-2022
   */

    function moverecord($moveType = "", $id = "", $prId = "",$redirectAction="")
    {
        try {
            if (empty($id) || empty($moveType) || empty($prId)) {
                throw new NotFoundException('Invalid request.');
            }
            $id = base64_decode($id);
            $prId = base64_decode($prId);
            $data = $this->NwRelationship->find('first', array("field" => ['id', 'order_num'], "conditions" => array('NwRelationship.id' => $id)));
            $dataArr = $this->NwRelationship->find('neighbors', ['field' => 'id', 'value' => $id]);
            if (!empty($dataArr)) {
                if (!empty($dataArr["prev"]) && $moveType == "down") {
                
                    $svOlder['NwRelationship']["id"] = $dataArr["prev"]['NwRelationship']["id"];
                    $svOlder['NwRelationship']["order_num"] = (!empty($data['NwRelationship']["order_num"])) ? $data['NwRelationship']["order_num"] : 1;
                
                    $svCurrent['NwRelationship']["id"] = $id;
                    $svCurrent['NwRelationship']["order_num"] = (!empty($dataArr["prev"]['NwRelationship']["order_num"])) ? $dataArr["prev"]['NwRelationship']["order_num"] : 1;
                    
                } else if (!empty($dataArr["next"]) && $moveType == "up") {
                
                    $svOlder['NwRelationship']["id"] = $dataArr["next"]['NwRelationship']["id"];
                    $svOlder['NwRelationship']["order_num"] = (!empty($data['NwRelationship']["order_num"])) ? $data['NwRelationship']["order_num"] : 1;
                
                    $svCurrent['NwRelationship']["id"] = $id;
                    $svCurrent['NwRelationship']["order_num"] = (!empty($dataArr["next"]['NwRelationship']["order_num"])) ? $dataArr["next"]['NwRelationship']["order_num"] : 1;
                }

                // pr($svOlder);

                $this->NwRelationship->save($svOlder);
                $this->NwRelationship->save($svCurrent);
                $this->Session->setFlash(__('successfully Moved.'), 'success');
            }
        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            if (!empty($prId)) {
                $this->redirect(array('action' => 'viewclippingreport', $prId));
            } else {

                $this->redirect(array('action' => 'index'));
            }
        }
        $this->redirect(array('action' => 'viewclippingreport', $prId,$redirectAction,'clrcache'.rand(1,100)));
        exit;
    }


    /*
     * @params: 
     * @Function use: updateClippingByGroupMediaNetwork: Clipping report by Group media network xml
     * @created by: Hitesh verma
     * @Created: 14-11-2022
     */
    public function updateClippingByGroupMediaNetwork($prId = "")
    {
        try {
            if (empty($prId)) {
                throw new NotFoundException('Invalid request.');
            }
            $prId = json_decode($prId);
            $log_file = WWW_ROOT . DS . 'tmp' . DS . 'logs' . DS . "gmn_xml.log";
            $this->loadModel('NetworkWebsite');
            $this->loadModel('NwRelationship');
            $NetworkWebsite = $this->NetworkWebsite->find('all', ["conditions" => ['xml_link IS NOT NULL']]); //,'last_ran_cron <='=>date('Y-m-d H:i:s')
            if (isset($NetworkWebsite) && !empty($NetworkWebsite)) {
                foreach ($NetworkWebsite as $key => $nwdata) {
                    $nw_id = $nwdata['NetworkWebsite']['id'];
                    $webXmlLink = $nwdata['NetworkWebsite']['xml_link'];
                    if (isset($webXmlLink) && !empty($webXmlLink)) {
                        $rss_feed = simplexml_load_file($webXmlLink);
                        if (!empty($rss_feed && !empty($rss_feed->channel->item))) {
                            $totalSavedCount = $this->NwRelationship->find('count', array("conditions" => array('NwRelationship.press_release_id' => $prId)));
                            foreach ($rss_feed->channel->item as $item) {
                                // echo "<pre>";
                                // print_r($nwdata);
                                $title = (strtok($item->title, "|") === true) ? explode("|", $item->title, 2) : $item->title;
                                $title = (is_array($title)) ? $title[0] : $title;
                                $this->PressRelease->recursive = -1;
                                $pressRelease = $this->PressRelease->find('first', array('conditions' => array('PressRelease.title' => $title, 'PressRelease.status' => 1, 'PressRelease.release_date <=' => date('Y-m-d')), 'fields' => array('id', 'title')));

                                if (isset($pressRelease) && !empty($pressRelease)) {
                                    $press_release_id = $pressRelease['PressRelease']['id'];
                                    $press_release_title = $pressRelease['PressRelease']['title'];
                                    $NwRelationship = $this->NwRelationship->find('count', array('conditions' => array('NwRelationship.press_release_id =' => $press_release_id, 'NwRelationship.network_website_id =' => $nw_id)));
                                    if (empty($NwRelationship)) {

                                        $savedata['NwRelationship']['id'] = '';
                                        $savedata['NwRelationship']['network_website_id'] = $nw_id;
                                        $savedata['NwRelationship']['press_release_id'] = $press_release_id;
                                        $savedata['NwRelationship']['press_release_link'] = (string)$item->link;
                                        $savedata['NwRelationship']['site_logo'] = "files/networkwebsite/" . $nwdata['NetworkWebsite']['website_logo'];
                                        $savedata['NwRelationship']['potential_audience'] = $nwdata['NetworkWebsite']['potential_audience'];
                                        $savedata['NwRelationship']['site_name'] = $nwdata['NetworkWebsite']['website_name'];
                                        $savedata['NwRelationship']['location'] = $nwdata['NetworkWebsite']['website_location'];
                                        $savedata['NwRelationship']['type'] = $nwdata['NetworkWebsite']['website_media_type'];
                                        $savedata['NwRelationship']['order_num'] = ++$totalSavedCount; // only new insert
                                        $this->NwRelationship->create();
                                        $this->NwRelationship->save($savedata);
                                    }
                                }
                            }
                        } else {
                            //    error_log(date('[Y-m-d H:i e] '). "item not found : $webXmlLink" . PHP_EOL, 3, $log_file);
                        }
                    } else {
                        //  error_log(date('[Y-m-d H:i e] '). "webXmlLink not found" . PHP_EOL, 3, $log_file);
                    }
                    $sv['NetworkWebsite']['id'] = $nw_id;
                    $sv['NetworkWebsite']['last_ran_cron'] = date("Y-m-d") . " 23:59:59";
                    $this->NetworkWebsite->save($sv);
                    sleep(2);  // Time interval to read on each xml

                }
            }
        } catch (Exception $exc) {
            $this->Session->setFlash($exc->getMessage(), 'error');
            if (!empty($prId)) {
                $this->redirect(array('action' => 'viewclippingreport', $prId));
            } else {

                $this->redirect(array('action' => 'index'));
            }
        }
        $this->redirect(array('action' => 'viewclippingreport', $prId));
        exit;
    }
    /* */

    function export_clipping_report_in_csv($prId,$orderBy="potential_audience"){
        // $this->PressRelease->recursive=-1;
        $prData = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $prId), "fields" => array("id", "title", "slug")));
        
        // $this->NwRelationships->bindModel(array('belongsTo' => array('PressRelease')));//"PressRelease.title as Press_Release",
        $fields=["NwRelationships.site_name as Media_Name","NwRelationships.site_url as Website","NwRelationships.location as Location","NwRelationships.type as Media_Type","NwRelationships.potential_audience as Potential_Audience"];
        $nwrelationships = $this->NwRelationships->find('all', array("fields"=>$fields,'conditions' => array('NwRelationships.press_release_id' => $prId, 'NwRelationships.status' => '1'), 'order' =>"NwRelationships.$orderBy DESC"));
        $dataArr = Set::extract('/NwRelationships/.', $nwrelationships);
        $prSlug = Inflector::slug(strtolower($prData['PressRelease']['title']), '-');
        $this->Export->exportCsv($dataArr,$prSlug.".csv");
    }
     public function add_manually($pId = "")
    {

        $pr_id = $pId;

        $this->set('title_for_layout', __('Add in report '));

        if ($this->request->is('post') && !empty($this->data)) {


            $this->request->data['ClippingReport']['distribution_type'] = "network_feed";

            $this->request->data['ClippingReport']['domain'] = $this->Custom->get_domain($this->data['ClippingReport']['release_page_url']);

            $this->ClippingReport->save($this->request->data);

            $this->loadModel('NwRelationship');

            $this->loadModel("NetworkWebsite");

            $match_keyword = parse_url($this->data['ClippingReport']['release_page_url'], PHP_URL_HOST);

            // echo $match_keyword ;

            // echo "<br>";

            // echo strlen($match_keyword );


            $network_webiste = $this->NetworkWebsite->find('all', array("conditions" => array('NetworkWebsite.website_domain LIKE' => '%' . $match_keyword . '%')));

            $network_relationships_exist = $this->NwRelationship->find('all', array("conditions" => array('NwRelationship.press_release_id' => $pId, 'NwRelationship.press_release_link' => $this->data['ClippingReport']['release_page_url'], 'NwRelationship.site_name' => $this->data['ClippingReport']['site_name'])));


            if (empty($network_relationships_exist)) {

                if (isset($network_webiste) && !empty($network_webiste)) {

                    // echo "<pre>";

                    // print_r($network_webiste);

                    // die;

                    // echo "above";

                    // die;

                    $savedata['NwRelationship']['id'] = '';

                    $savedata['NwRelationship']['network_website_id'] = $network_webiste[0]['NetworkWebsite']['id'];

                    $savedata['NwRelationship']['press_release_id'] = $pId;

                    $savedata['NwRelationship']['press_release_link'] = $this->data['ClippingReport']['release_page_url'];

                    $savedata['NwRelationship']['site_name'] = $this->data['ClippingReport']['site_name'];

                    if (isset($network_webiste[0]['NetworkWebsite']['website_logo']) && !empty($network_webiste[0]['NetworkWebsite']['website_logo'])) {

                        $savedata['NwRelationship']['site_logo'] = SITEFRONTURL . "website/img/" . $network_webiste[0]['NetworkWebsite']['website_logo'];
                    } else {
                        $savedata['NwRelationship']['site_logo'] = SITEFRONTURL . "website/img/blank.jpg";
                    }

                    $savedata['NwRelationship']['type'] = $network_webiste[0]['NetworkWebsite']['website_media_type'];

                    $savedata['NwRelationship']['location'] = $network_webiste[0]['NetworkWebsite']['website_location'];

                    $savedata['NwRelationship']['potential_audience'] = $network_webiste[0]['NetworkWebsite']['potential_audience'];

                    $this->NwRelationship->save($savedata);

                    return $this->redirect(array('action' => 'viewclippingreport', $pr_id));
                } else {

                    $savedata['NwRelationship']['id'] = '';

                    $savedata['NwRelationship']['network_website_id'] = 0;

                    $savedata['NwRelationship']['press_release_id'] = $pId;

                    $savedata['NwRelationship']['press_release_link'] = $this->data['ClippingReport']['release_page_url'];

                    $savedata['NwRelationship']['site_name'] = $this->data['ClippingReport']['site_name'];

                    $savedata['NwRelationship']['site_logo'] = SITEFRONTURL . "website/img/blank.jpg";

                    $savedata['NwRelationship']['type'] = 'Newswire';

                    $savedata['NwRelationship']['location'] = '';

                    $savedata['NwRelationship']['potential_audience'] = 0;

                    $this->NwRelationship->save($savedata);

                    return $this->redirect(array('action' => 'viewclippingreport', $pr_id));
                }
            }

            return $this->redirect(array('action' => 'viewclippingreport', $pId));
        }

        $this->set(compact('pId'));

        $this->PressRelease->recursive = -1;

        $pr_data = $this->PressRelease->find("first", array('conditions' => array('PressRelease.id' => $pr_id), "fields" => array("id", "plan_id", "company_id", "title", "views", "release_date", "staff_user_id", "approved_by")));

        $staff_user_id = $pr_data['PressRelease']['staff_user_id'];

        $approved_user_id = $pr_data['PressRelease']['approved_by'];


        $this->loadModel('StaffUser');

        $this->StaffUser->recursive = -1;

        $user_data = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $staff_user_id)));

        $approved_data = $this->StaffUser->find("first", array('conditions' => array('StaffUser.id' => $approved_user_id)));

        $user_datas = $user_data;

        $approved_datas = $approved_data;

        $this->set(compact('user_datas'));

        $this->set(compact('approved_datas'));

        $this->set(compact('pr_id'));

        $this->set('pr_title', $pr_data['PressRelease']['title']);
    }
}
