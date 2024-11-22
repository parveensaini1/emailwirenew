<?php

App::uses('AppModel', 'Model');

class ClickThroughClient extends AppModel { 
 public $belongsTo = array('PressRelease');
 
}
