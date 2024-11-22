<?php
class Cart extends AppModel {
    public $name = 'Cart';
    public $belongsTo=array('StaffUser','Plan');
}
