<p class="login-box-msg">Sign in to start your session</p>
<?php echo $this->Form->create('StaffUser', array('inputDefaults' => array('div' => false, 'class' => 'form-control', 'label' => false, 'required' => false))); ?>
<div class="form-group has-feedback">
    <?php echo $this->Form->input('email', array('placeholder' => "Email")); ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?php echo $this->Form->input('password', array('placeholder' => "Password")); ?>
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>
<div class="form-group has-feedback" id="otp-container" style="display: none;">
    <?php echo $this->Form->input('otp', ['placeholder' => "Enter OTP"]); ?>
    <span class="glyphicon glyphicon-send form-control-feedback"></span>
</div>
<div class="row">
    <div class="col-sm-8">
        <input type="checkbox"> Remember Me
    </div><!-- /.col -->


    <div class="col-sm-4">
        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
    </div><!-- /.col -->
</div>
<?php echo $this->Form->end(); ?>

<div class="social-auth-links text-center">
    <p>- OR -</p>

</div><!-- /.social-auth-links -->
<?php echo $this->Html->link('I forgot my password', array('controller' => 'users', 'action' => 'forgot', 'admin' => false)); ?>

<?php echo " | ".$this->Html->link('Create new account', array('controller' => 'users', 'action' => 'signup', 'admin' => false)); ?>

<script type="text/javascript">
    function validationrecaptcha(event) {
        event.preventDefault();
        var response = grecaptcha.getResponse();
        console.log('response',response)
        document.getElementById('g-recaptcha-error').innerHTML='';
        if(response.length == 0) {
            document.getElementById('g-recaptcha-error').innerHTML = '<label style="color:red;">This field is required.</label>';
            return false;
        }
        $("#StaffUserLoginForm").submit();
        ShowLoadingIndicator();
    }

</script>
