<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">

            <div class="panel-body">
                <?php
                echo $this->Form->create('User', array('novalidate', 'inputDefaults' => array('class' => 'form-control', 'div' => 'form-group'),'id'="passwordform"));
                ?> 
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        echo $this->Form->input('current_password', array('type' => 'password','minlength'=>"8",'maxlength'=>"20"));
                        ?>
                    </div>   
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $this->Form->input('password', array('type' => 'password','minlength'=>"8",'maxlength'=>"20")); ?>
                    </div>   
                </div> 
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $this->Form->input('verify_password', array('type' => 'password','minlength'=>"8",'maxlength'=>"20")); ?>
                    </div>  
                </div>  
                <button type="submit" class="btn btn-info">Update</button>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div> 

<script type="text/javascript">
     $(document).ready(function(){ 
        $("#passwordform").validate({
            debug: false,
            rules: { 
                "data[StaffUser][current_password]": {
                    required: true,
                    minlength: 8
                },
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
                "data[StaffUser][current_password]": {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long"
                },  
                "data[StaffUser][password]": {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long"
                }, 
                "data[StaffUser][verify_password]": {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long",
                    equalTo: "Please enter the same password."
                }, 
            }
        });
    });
 

</script>