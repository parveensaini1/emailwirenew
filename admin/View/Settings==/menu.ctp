<div class="row">
    <div class="col-lg-12">
        <div class="row table-header-row">
            <div class="card-heading datatable-heading">
                <?php echo $this->Html->link('<i class="icon-list"></i> All Settings', array('controller' => 'settings', 'action' => 'index'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
                <?php echo $this->Html->link('<i class="icon-plus-sign-alt"></i> Add New Setting', array('controller' => 'settings', 'action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>
            </div>    
        </div>    
    </div>
</div>
