 
<div class="row">
    <div class="col-lg-12">
        <div class="row table-header-row">
            <div class="panel-heading datatable-heading">
            <?php  
            

                if ($controller == 'users' && $action == 'email_list') {
                    echo $this->Html->link(' <i class="fa fa-plus"></i> Add new email list',  array('controller' => $controller, 'action' =>"add-email-list"), array('class' => 'btn btn-primary', 'escape' => false))."\t"; 
                }

                if ($controller == 'users' && $action == 'sendy_add_subscriber') {
                    echo $this->Html->link(' <i class="fa fa-plus"></i> Import media email list csv',  array('controller' => $controller, 'action' =>"import-media-email-list",$lid), array('class' => 'btn btn-primary', 'escape' => false))."\t"; 
                } 

                 if ($controller == 'users' && $action == 'sendy_import_subscriber_csv') { 
                    echo $this->Html->link(' <i class="fa fa-plus"></i> Add media email manually',  array('controller' => $controller, 'action' =>"add-media-email",$lid), array('class' => 'btn btn-primary', 'escape' => false));
                } 

                ?>
            </div>    
        </div>    
    </div>
</div> 