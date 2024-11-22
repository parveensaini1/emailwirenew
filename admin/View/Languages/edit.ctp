<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                    echo $this->Form->input('id');
                    echo $this->Form->input('name');
                    echo $this->Form->input('slug');
                    echo $this->Form->input('code');
                    echo $this->Form->input('status',array('type'=>'checkbox','class'=>'status_checkbox','div'=>'div-status'));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
               
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>