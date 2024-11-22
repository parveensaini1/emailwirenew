<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <!-- /.card-heading -->
            <div class="card-body">
               <?php // echo $this->element('subcard'); ?>
                <div class="dataTable_wrapper">
                    <div class="row">
                        <div class="col-sm-6">
                <?php 
                echo $this->Form->create("RemainingUserPlan", array('novalidate' => 'novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));

                echo $this->Form->input('staff_user_id', array('options'=>$client_list,'empty' => '-Select user-','class'=>'select2 form-control','default'=>$defaultuserid,'label'=>"Users"));

                echo $this->Form->input('plan_id', array('options'=>$plan_list,'empty' => '-Select plan-','class'=>'select2 form-control','default'=>$defaultPlanId,'label'=>"Plans"));
                
                echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                echo $this->Form->end();
                ?>
                </div>
                </div>
                    </div>
            </div>
        </div>
    </div>
</div>
