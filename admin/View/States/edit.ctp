
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        
    <?php echo $this->element('submenu'); ?>
        <div class="card card-default">

            <!-- /.card-heading -->
            <div class="card-body">
                <div class="dataTable_wrapper">
                    <div class="col-md-8 col-sm-8">
                        
                    
                    <?php
                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                    echo $this->Form->input('id');
                    echo $this->Form->input('country_id',
                        array(
                            'options'=>$country_list, 
                            'label'  => 'Country',
                            'class'  => 'selectpicker form-control',
                            'data-actions-box' => 'true',
                            'placeholder'      => 'Enter country name...'
                        )
                    );
                    echo $this->Form->input('name'); 
                    echo $this->Form->input('status',array('type'=>'checkbox','class'=>'status_checkbox','div'=>'div-status'));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                    ?>
                    <?php
                    echo $this->Html->link('Cancel', array(
                        'controller' => 'states',
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
</div>
