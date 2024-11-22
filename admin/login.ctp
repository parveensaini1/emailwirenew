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
<div class="row">
    <div class="col-xs-8">
        <input type="checkbox"> Remember Me 
    </div><!-- /.col -->

    <div style="padding-top: 15px;" class="col-lg-12 form-group ew-captch-div has-feedback">
         <script src='https://www.google.com/recaptcha/api.js'></script>
         <div class="g-recaptcha" data-sitekey="<?php echo Configure::read('recaptcha_key');?>"></div>
         <div id="g-recaptcha-error"></div>                       
    </div> 
    <div class="col-xs-4">
        <button type="submit"  class="btn btn-primary btn-block btn-flat">Sign In</button>
    </div><!-- /.col -->
</div>
<?php echo $this->Form->end(); ?>

<div class="social-auth-links text-center">
    <p>- OR -</p>

</div><!-- /.social-auth-links -->
<?php echo $this->Html->link('I forgot my password', array('controller' => 'staffUsers', 'action' => 'forgot', 'admin' => false)); ?>

<script type="text/javascript">
   function validationrecaptcha(event) { 
       /* event.preventDefault();
        var response = grecaptcha.getResponse();
        document.getElementById('g-recaptcha-error').innerHTML='';
        if(response.length == 0) {
            document.getElementById('g-recaptcha-error').innerHTML = '<label style="color:red;">This field is required.</label>';
            return false;
        }*/
        $("#StaffUserLoginForm").submit();
    }
    
</script>