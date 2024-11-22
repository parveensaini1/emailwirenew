<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                     echo $this->Form->input('id'); 
                    ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <?php
                            echo $this->Form->input('staff_role_id', array('options' => $role, 'empty'=>false)); //'empty' => 'Select Role'
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?php
                            echo $this->Form->input('first_name');
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?php
                            echo $this->Form->input('last_name');
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?php
                            echo $this->Form->input('email');
                            ?>
                        </div>
                    </div>
                    <div class="row"> 
                        
                        <div class="col-sm-3">
                            <?php
                           echo $this->Form->input('phone', array("type" => 'text', 'class' => 'form-control','maxlength'=>"15",'minlength'=>"10",'onkeypress'=>"return isNumber(event)"));
                            ?>
                        </div>
                    </div>
                    <?php                                                             
                   echo $this->Form->input($model.'.status',array('type'=>'checkbox','class'=>'form-control status-checkbox',"label"=>"Active"));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                    
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
