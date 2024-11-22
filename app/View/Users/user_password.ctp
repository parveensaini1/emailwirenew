<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));?>  
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <?php
 
                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                    echo $this->Form->input('current_password',array('type'=>'password',"required"=>"required"));
                    echo $this->Form->input('password',array('type'=>'password',"required"=>"required"));
                    echo $this->Form->input('verify_password',array('type'=>'password',"required"=>"required"));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                    ?>
                    <?php
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
      $(document).ready(function(){ 
        $("#StaffUserUserPasswordForm").validate({
            debug: false,
            rules: { 
                "data[StaffUser][password]": {
                    required: true,
                    minlength: 8
                },
                "data[StaffUser][verify_password]": {
                    required: true,
                    minlength: 8,
                    equalTo: "#StaffUserPassword"
                },
            },
            messages: { 
                "data[StaffUser][password]": {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long"
                }, 
                "data[StaffUser][verify_password]": {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long",
                    equalTo: "Please enter the same password."
                }
            }
        });
    });
</script>