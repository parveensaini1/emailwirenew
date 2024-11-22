<?php $action = (!empty($action))?$action:"index"; //strtolower(trim($this->params['action'])) ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="row table-header-row float-right">
                <div class="card-heading datatable-heading">
                <?php 
            
            echo $this->Html->link('<i class="icon-plus-sign-alt"></i> Add New '. isset($menuadd), array('controller' => $controller, 'action' => 'add',!empty($url)), array('class' => 'btn btn-primary', 'escape' => false)); 
            ?>

            <?php  
            if($check_trash>0){
            	echo $this->Html->link("<i class='icon-plus-sign-alt'></i> Trash ( ".$check_trash." )", array('controller' => $controller, 'action' => 'trash',$url), array('class' => 'btn btn-danger', 'escape' => false));
            }
            if(isset($userRoleId)){
                echo $this->Html->link(__("Send Email Notification to all $userRoleName"), array('controller' => $controller,'action' =>"sendnotifications",$userRoleId,$userRoleName),array('class' => 'btn  btn-primary pull-right'));
            }
           /* if($action=="index"){
            echo " Filter by :";
            echo $this->Html->link("<i class='icon-plus-sign-alt'></i> Admin ", array('controller' => $controller, 'action' => 'index','1'), array('class' => '', 'escape' => false));
            echo " | ".$this->Html->link("<i class='icon-plus-sign-alt'></i> Editor", array('controller' => $controller, 'action' => 'index','2'), array('class' => '', 'escape' => false));
            }*/
           	?>
                </div>    
            </div>    
        </div>
    </div>
</div>