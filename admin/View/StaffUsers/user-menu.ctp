<?php $action = (!empty($action))?$action:"index"; //strtolower(trim($this->params['action'])) ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="row table-header-row float-right">
                <div class="card-heading datatable-heading">
                <?php 
            $url=(isset($url))?$url: $this->request->pass[0];
            echo $this->Html->link(' <i class="icon-list"></i>Add new '.$menuadd, array('controller' => $controller, 'action' => 'add',$url), array('class' => 'btn btn-primary', 'escape' => false));?>
            
             <?php  
            if($check_trash>0){
                echo $this->Html->link("<i class='icon-plus-sign-alt'></i> Trash ( ".$check_trash." )", array('controller' => $controller, 'action' => 'trash',$url), array('class' => 'btn btn-danger', 'escape' => false));
            }

            if(isset($userRoleId)){
                // echo $this->Html->link(__("Send Email Notification to all $userRoleName"), array('controller' => $controller,'action' =>"sendNotificationEmail",$userRoleId),array('class' => 'btn  btn-primary pull-right','onclick' => 'return reasonMessage(this.href,"Send Notification Email to '.$userRoleName.'");'));

                // echo $this->Form->button(__("Export All"),array('id'=>'export_client_list', 'class' => 'btn btn-primary export_client_list'));
                echo $this->Html->link(__("Export All"), array('controller' => 'Ajax','action' =>"export_client_list",$userRoleId),array('class' => 'btn btn-primary'));
                echo $this->Html->link(__("Send Email Notification to all $userRoleName"), array('controller' => $controller,'action' =>"sendnotifications",$userRoleId,$userRoleName),array('class' => 'btn  btn-primary ml-2 float-right'));
            }
            /*if($action=="users"){
                echo " Filter by :";
                echo $this->Html->link("<i class='icon-plus-sign-alt'></i> Client ", array('controller' => $controller, 'action' => 'users','3'), array('class' => '', 'escape' => false));
                echo " | ".$this->Html->link("<i class='icon-plus-sign-alt'></i> Subscriber", array('controller' => $controller, 'action' => 'users','4'), array('class' => '', 'escape' => false));
            }*/
            ?>
                </div>    
            </div>    
        </div>
    </div>
</div>