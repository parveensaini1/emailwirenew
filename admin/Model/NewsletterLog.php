<?php

class NewsletterLog extends AppModel {

    public $name = 'NewsletterLog';
    public $belongsTo = array('PressRelease','StaffUser');
    
}
