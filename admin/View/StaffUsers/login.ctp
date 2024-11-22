<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<p class="login-box-msg">Enter your username and password</p>
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
    <div class="col-xs-8 col-sm-8">
        <input type="checkbox"> Remember Me 
    </div><!-- /.col -->
    <?php if($env == 'production'){ ?>
    <div style="padding-top: 15px;" class="col-lg-12 form-group ew-captch-div has-feedback">
         <script src='https://www.google.com/recaptcha/api.js'></script>
         <div class="g-recaptcha" data-sitekey="<?php echo Configure::read('recaptcha_key');?>"></div>
         <div id="g-recaptcha-error"></div>                       
    </div> 
    <?php } ?>
    <div class="col-xs-4 col-sm-4">
        <button type="submit" <?php if($env == 'production'){ ?> onclick="validationrecaptcha(event);" <?php } ?> class="btn btn-info  float-right">Sign In</button>
    </div><!-- /.col -->
</div>
<?php echo $this->Form->end(); ?>

<div class="social-auth-links text-center">
    <p>- OR -</p>

</div><!-- /.social-auth-links -->
<?php echo $this->Html->link('I forgot my password', array('controller' => 'staffUsers', 'action' => 'forgot', 'admin' => false)); ?>

<script type="text/javascript">
   function validationrecaptcha(event) { 
        event.preventDefault();
        var response = grecaptcha.getResponse();
        console.log(response);
        document.getElementById('g-recaptcha-error').innerHTML='';
        if(response.length == 0) {
            document.getElementById('g-recaptcha-error').innerHTML = '<label style="color:red;">This field is required.</label>';
            return false;
        }
        $("#StaffUserLoginForm").submit();
        ShowLoadingIndicator();

    }
    
</script>