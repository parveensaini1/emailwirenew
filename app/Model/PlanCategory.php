<?php
App::uses('AppModel', 'Model');
/**
 * PlanCategory Model
 *
 * @property User $User
 * @property PlanCategory $ParentPlanCategory
 * @property Section $Section
 * @property PlanCategory $ChildPlanCategory
 */
class PlanCategory extends AppModel {
    public $hasMany=array('Plan');
    public $actsAs = array('Tree');
}
