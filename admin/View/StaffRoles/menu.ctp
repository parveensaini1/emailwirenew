 <div class="row">
    <div class="col-lg-12">
        <div class="row table-header-row">
            <div class="panel-heading datatable-heading">
                <?php echo $this->Html->link('<i class="fa icon-list"></i> All Role', array('controller' => $controller, 'action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                <?php echo $this->Html->link('<i class="fa icon-plus-sign-alt"></i> Add New Role', array('controller' => $controller, 'action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>    
        </div>    
    </div>
</div>
