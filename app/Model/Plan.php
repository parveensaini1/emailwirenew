<?php
class Plan extends AppModel {
    public $name = 'Plan';     
    public $belongsTo=array('PlanCategory');
}