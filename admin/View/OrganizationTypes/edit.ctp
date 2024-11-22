<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <!-- /.card-heading -->
            <div class="card-body">
                <?php include 'menu.ctp'; ?>
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create($model, array('novalidate' => 'novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                    echo $this->Form->input('id');
                    ?>
                    <div class="row">                        
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('name', array('type' => 'text')); ?>
                        </div> 
                    </div>  
                    <?php
                   echo $this->Form->input('status', array('div' => 'form-group div-status', 'class' => 'custom_check','type'=>'checkbox'));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                    ?>
                    <?php
                    echo $this->Html->link('Cancel', array(
                        'controller' => $controller,
                        'action' => 'index'
                            ), array('class' => 'btn btn-danger')
                    );
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>