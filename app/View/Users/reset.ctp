 
<div class="row">
    <div class="col-md-12">
        <h4 class="page-head-line">Reset for your new password</h4>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo $this->Form->create('StaffUser', array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'), 'novalidate'));
        echo $this->Form->input('password', array('type' => 'password', 'label' => 'Password:', 'required' => false, 'minlength' => "8", 'maxlength' => "50",));
        echo $this->Form->input('verify_password', array('type' => 'password', 'label' => 'Confirm password:', 'required' => false, 'minlength' => "8", 'maxlength' => "50",));
        ?>
        <hr />
        <?php
        echo $this->Form->input('Reset Now', array('class' => "submit-btn btn btn-info", "type" => 'submit', 'label' => false));
        echo $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#StaffUserResetForm").validate({
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
                },
            },
            submitHandler: function(form) {
                ShowLoadingIndicator();
                form.submit();
            }
        });
    });
</script>