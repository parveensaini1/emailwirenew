<?php
class Transaction extends AppModel {
    public $name = 'Transaction';   
    public $hasMany = array('TransactionPlan');
    public $belongsTo = array('StaffUser');   
 
}