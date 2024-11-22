<?php
include_once(ROOT.DS . 'vendors' . DS . 'aws'.DS.'vendor'.DS.'autoload.php');

use Aws\Ses\SesClient;  
use Aws\Exception\AwsException;

// include_once(APP . 'Vendor' . DS . 'aws/services/ses.class.php');
class AWSSESComponent extends Component {
    protected $controller = null;
    public $ses;
    public $emailViewPath = '/Emails';
    public $emailLayouts = 'Emails';
    public $htmlMessage;
    public $from = 'Email wire <emailwireweb@gmail.com>';
    public $to;
    public $replayto="noreplay@emailwire.com";
    public $subject;
    public $textMessage;
    public function beforeFilter() {
        parent::beforeFilter();
    }
    public function initialize(Controller $controller) {
        $this->controller = & $controller;
    }

    public function _aws_ses(){ 
            try {
        // $this->ses = new AmazonSES(array('certificate_authority' => false,
            //    'key' => "AKIAIDSNRF24WBI2OZIA",
            //    'secret' => "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"));
            $this->ses = new Aws\Ses\SesClient(Configure::read('aws_ses'));
            $sourceEmail ="Email wire <".Configure::read('awsSesSourceEmail').">";
            // $sourceEmail ="Email wire <hiteshvermadoit@gmail.com>";
        // $recipient_emails = ['hiteshvermadoit@gmail.com','testdevlopertest123@gmail.com'];
            $char_set = 'UTF-8'; 
            $recipientEmails=explode(',', $this->to);
            $requestMail=[
                'Destination' => [
                    'ToAddresses' => $recipientEmails,
                ],
                'ReplyToAddresses' => [$this->replayto],
                'Source' => $sourceEmail,
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => $char_set,
                            'Data' => $this->htmlMessage,
                        ],
                        // 'Text' => [
                        //     'Charset' => $char_set,
                        //     'Data' => $plaintext_body,
                        // ],
                    ],
                    'Subject' => [
                        'Charset' => $char_set,
                        'Data' => $this->subject,
                    ],
                ], 
            ]; 
            // pr($requestMail);die;
            $response = $this->ses->sendEmail($requestMail);
            $messageId = $response->get('MessageId');
            if (empty($messageId)){
                echo 'Message not sent.';
                return false;
            }
        return true;
        } catch (Exception $e) {
            // output error message if fails
            // echo $e->getMessage();
            $this->saveSesErrorLogs($e->getMessage());
            return false;
        }
        return true;
    } 
    public function _getHTMLBodyFromEmailViews($view){
       $currentLayout = $this->controller->layout;
       $currentAction = $this->controller->action;
       $currentView = $this->controller->view;
       $currentOutput = $this->controller->output;

       ob_start();
       $this->controller->output = null;

       $viewPath = $this->emailViewPath . DS . 'html' . DS . $view;
       $layoutPath = $this->emailLayouts . DS . 'html' . DS . 'default';

       $bodyHtml = $this->controller->render($viewPath, $layoutPath);

       ob_end_clean();

       $this->controller->layout = $currentLayout;
       $this->controller->action = $currentAction;
       $this->controller->view = $currentView;
       $this->controller->output = $currentOutput;

       return $bodyHtml;
    }

    Private function saveSesErrorLogs($errorResponse=""){
        $sv=[];
        if(!empty($errorResponse)){
            $obj = ClassRegistry::init('SesMailLog');
            $sv['response']=$errorResponse;
            $obj->save($sv);
        }
    }
}