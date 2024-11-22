
<?php echo $this->element('submenu'); ?>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <div class="dataTable_wrapper">
                    <div class="col-md-8 col-sm-8">
                        
                    
                    <?php
                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false))); ?>
                    <?php echo "<div class='row mb-2'>";  
                    echo $this->Form->input('country_id',
                        array(
                            'options'=>$country_list, 
                            'label'  => 'Country',
                            'class'  => 'select2 form-control',
                            'data-actions-box' => 'true',
                            'placeholder'      => 'Enter Country name...',
                            'div'=>"col-sm-6",
                            'onchange'=>"getStates(this.value,'$model','MsaStateId')"
                        )
                    );
                    echo $this->Form->input('state_id',
                    array(
                        'options'=>$state_list, 
                        'label'  => 'State',
                        'class'  => 'select2 form-control',
                        'data-actions-box' => 'true',
                        'placeholder'      => 'Enter state name...',
                        'div'=>"col-sm-6"
                    )
                    );
                    echo "</div>"; ?>
                    <?php 
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
