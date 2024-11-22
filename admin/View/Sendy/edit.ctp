<?php  include 'submenu.ctp'; ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
               
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
                                echo $this->Form->input('list_for_client',array('type'=>'checkbox','class'=>'form-control status-checkbox',"label"=>"List for client"));

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
 