<?php 
$controller = trim($this->params['controller']);
$action=strtolower(trim($this->params['action']));
$action = (!empty($action))?$action:"index"; // ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="row table-header-row float-right">
                <div class="panel-heading datatable-heading">
                <?php if(isset($menutitle)&&!empty($menutitle)){ 
                $redirectAction = (!in_array($action,['edit','add','trashed']))?$action:"index";
                ?>
                <?php //$menutitle=ucfirst($menutitle); ?>
                    <?php echo $this->Html->link('<i class="fa fa-list"></i> All '.$menutitle.' ', array('controller' => $controller, 'action' =>$redirectAction), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                <?php }
                    if(isset($menutitle_add)&&$action=='index'){ ?>
                           <?php echo $this->Html->link('<i class="fa fa-plus"></i> Add New '.$menutitle_add,  array('controller' => $controller, 'action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                    <?php }?>
                    <?php 
                    if(isset($menutitle)&&!empty($menutitle)&&!empty($showTrashButton)){
                        echo $this->Html->link('<i class="fa fa-list"></i> Trashed', array('controller' => $controller, 'action' => 'trashed'), array('class' => 'btn btn-danger', 'escape' => false));
                    }
                    ?>    
                </div>    
            </div>    
        </div>
    </div>
</div>