<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                <?php // include 'custom_submenu.php'; ?>
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create("List", array('novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                    <div class="row">
                        <div class="col-sm-9">
                        <?php
                            echo $this->Form->input('id');
                            echo $this->Form->input('name', array("class" =>"form-control","empty" =>""));
                       ?>
                        <div class="row">
                            <div class="col-md-6"> 
                                <?php
                                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                                ?>
                            </div>  
                        </div>
                    </div>
                    </div> 
                    <?php echo $this->Form->end(); ?> 
                </div> 
            </div>
        </div>
    </div>
</div>
 