<?php if(isset($menutitle)){?>
<div class="row">
    <div class="col-lg-12">
        <div class="row table-header-row">
            <div class="card-heading datatable-heading">
            <?php $menutitle=ucfirst($menutitle);
                echo $this->Html->link('<i class="fa fa-list"></i> All '.$menutitle.' ', array('controller' => $controller, 'action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false))."\t"; 
                
                if(isset($menutitle_add)){
                    $menuadd=ucfirst($menutitle_add);
                    echo $this->Html->link(' <i class="fa fa-plus"></i> Add New '.$menuadd,  array('controller' => $controller, 'action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false))."\t";
                }

                if(isset($action)&&$action=="edit"){
                    echo $this->Html->link(' <i class="fa fa-plus"></i> Import subscribers',  array('controller' => $controller, 'action' => 'import_subscriber_csv',$lid), array('class' => 'btn btn-primary', 'escape' => false))."\t";
                    echo $this->Html->link(' <i class="fa fa-plus"></i> Add subscriber manually',  array('controller' => $controller, 'action' => 'import_subscriber_csv',$lid), array('class' => 'btn btn-primary', 'escape' => false));
                } 

                ?>
            </div>    
        </div>    
    </div>
</div>
<?php }?>