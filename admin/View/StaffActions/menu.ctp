 <div class="row">
    <div class="col-lg-12">
        <div class="row table-header-row">
            <div class="panel-heading datatable-heading">
                <?php echo $this->Html->link('<i class="icon-list"></i> All Action', array('controller' => $controller, 'action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                <?php echo $this->Html->link('<i class="icon-plus-sign-alt"></i> Generate Action', array('controller' => $controller, 'action' => 'generate'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                <?php echo $this->Html->link('<i class="icon-plus-sign-alt"></i> User Permission', array('controller' => $controller, 'action' => 'permission'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>    
        </div>    
    </div>
</div>
