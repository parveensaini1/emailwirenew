<?php
App::uses('AppModel', 'Model');
class Page extends AppModel {
    public $belongsTo=array('PageTemplate');
    public $actsAs = array('Containable');
    public $name = 'Page';
}

