<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create($model, array('novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                    <div class="row">
                        <div class="col-sm-9">
                        <?php
                        echo $this->Form->input('template_name', array("class" => "form-control", "empty" => ""));
                        
                        // echo $this->Form->input('type',array('type'=>'radio','options'=>array('1'=>'Page Template','2'=>'Category Template'),'default'=>'1'));
                       ?>
                        <div class="row">

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <?php
                             
                            echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                            ?>
                            <?php
                            echo $this->Html->link('Cancel', array(
                                'controller' => $controller,
                                'action' => 'index'
                                    ), array('class' => 'btn btn-danger')
                            );
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
 