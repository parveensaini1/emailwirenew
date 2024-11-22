 
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                <?php include 'menu.ctp'; ?>
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                    echo $this->Form->input('title');
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                    ?>
                    <?php
                    echo $this->Html->link('Cancel', array(
                                'controller' => 'roles',
                                'action' => 'index'
                            ),array('class'=>'btn btn-danger')
                    );
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
