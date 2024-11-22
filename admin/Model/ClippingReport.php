<?php

App::uses('AppModel', 'Model');

class ClippingReport extends AppModel { 
 public $belongsTo = array('PressRelease');
 
}
