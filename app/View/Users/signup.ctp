<?php echo $this->Html->script(array('/plugins/jquery-validation/jquery.validate.min',
                    '/plugins/jquery-validation/additional-methods.min'));?>
<div class="full ew-account-page margin-bottom20">
    <div class="row">    
        <!-- title -->  
        <div class="col-lg-12"><div class="ew-title full">Create PR Firm or Client Account</div></div>
        <!-- End title --> 
        <!-- text and form fields -->
        <div class="col-sm-8 ew-account-form-fields">
            <p>If you are a PR Firm or Company Owner and want to publish company press release then you need to signup on Emailwire.com</p>
            <p>From this signup your will become a client only. To submit a press release you need a company. You can take over an existing company from your client dashboard or you can follow this link to register a new company news room.<br/> <a href="<?php echo SITEURL;?>users/create-newsroom">Create company newsroom</a></p>           
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo $this->Session->flash();
                    ?>
                </div>
            </div>
            <?php
            echo $this->Form->create('StaffUser', array('type' => 'file', 'inputDefaults' => array('class' => 'form-control', 'label' => false,'div' => false),'id' => 'register_from','novalidate'));
            echo $this->Form->input("StaffUser.staff_role_id",array("type"=>"hidden","value"=>3));
            echo $this->Form->input("StaffUser.redirect",array("type"=>"hidden","value"=>'plans/online-distribution'));
            ?>
            <div class="row">
                <div class="col-lg-12 ew-account-sub-head"><h4>Personal Information</h4></div>
                <div class="col-sm-6 form-group">
                    <label>First Name *</label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill first name here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>  
                    <?php echo $this->Form->input('first_name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required")); ?>                        
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Last Name *</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill last name here"><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php echo $this->Form->input('last_name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required")); ?>                        
                </div>                    
            </div> 
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>E-mail *</label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill email here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('email', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required",'onchange'=>"check_user_email();",'autocomplete' => 'off'));
                    ?>                        
                </div>
                <div class="col-sm-6 form-group">
                    <label>Confirm E-mail *</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill confirm email here"><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('confirm_email', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required"));
                    ?>                        
                </div> 
            </div>
            
             <div class="row">
                <div class="col-sm-12 form-group">
                    <label>Phone *</label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill phone here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('phone', array("type" => 'text','maxlength'=>"15",'minlength'=>"10",'class'=>'form-control',"required"=>"required","onkeypress"=>"return isNumber(event)",'autocomplete' => 'off'));
                    ?>                        
                </div>
               
            </div>
            
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Password *</label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill password here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>  
                    <?php
                    echo $this->Form->input('password', array("type" => 'password','minlength'=>"8",'maxlength'=>"50",'class'=>'form-control',"required"=>"required"));
                    ?>                        
                </div>
                <div class="col-sm-6 form-group">
                    <label>Confirm Password *</label>     
                     <a href="javascript:void(0)" data-toggle="tooltip" title="Fill confirm password here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>                        
                    <?php
                    echo $this->Form->input('verify_password', array("type" => 'password','minlength'=>"8",'maxlength'=>"50",'class'=>'form-control',"required"=>"required"));
                    ?>
                </div>  
            </div>
            <?php if($env =='production'){ ?> 
            <div class="row">
                <div class="col-lg-12">
                    <div style="padding-top: 15px;" class="col-lg-12 form-group ew-captch-div has-feedback">
                         <script src='https://www.google.com/recaptcha/api.js'></script>
                         <div class="g-recaptcha" data-sitekey="<?php echo Configure::read('recaptcha_key');?>"></div>
                         <div id="g-recaptcha-error"></div>                     
                    </div> 
                 </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-lg-12 form-group">
                    
                    <?php  if($env =='production'){
                        echo $this->Form->input('Signup Now', array('class'=>"submit-btn","type" => 'submit','onclick'=>"validationrecaptcha();")); 
                    }else{
                        echo $this->Form->input('Signup Now', array('class'=>"submit-btn","type" => 'submit')); 
                    }?>  
                </div>     
            </div>  
        </div>        
        <!-- End text and form fields -->
        <!-- Sidebar -->
        <div class="col-sm-4 ew-sidebar">
         <?php   echo $this->element('signup_sidebar'); ?>
        </div>    
        <!-- End sidebar -->        
    </div>
</div> 
<script type="text/javascript">
     $(document).ready(function(){ 
        $("#register_from").validate({
            debug: false,
            rules: {
                "data[StaffUser][first_name]": "required",
                "data[StaffUser][last_name]": "required", 
                "data[StaffUser][phone]": "required", 
                "data[StaffUser][password]": {
                    required: true,
                    minlength: 8
                },
                "data[StaffUser][verify_password]": {
                    required: true,
                    minlength: 8,
                    equalTo: "#StaffUserPassword"
                },
                "data[StaffUser][email]": {
                    required: true,
                    email: true
                },
                "data[StaffUser][confirm_email]": {
                    required: true,
                    email: true,
                    equalTo: "#StaffUserEmail"
                }, 
                "data[StaffUser][confirm_email]": {
                    required: true,
                    email: true,
                    equalTo: "#StaffUserEmail"
                }, 
            },
            messages: {
                "data[StaffUser][first_name]": "Please enter your first name",
                "data[StaffUser][last_name]": "Please enter your last name", 
                "data[StaffUser][password]": {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long"
                }, 
                "data[StaffUser][verify_password]": {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long",
                    equalTo: "Please enter the same password."
                },
                "data[StaffUser][email]": "Please enter a valid email address.",
                "data[StaffUser][confirm_email]":{
                    required: "Please enter email address",
                    equalTo: "Please enter the same email address"
                }
            },
            submitHandler: function(form) {
                var checkCaptchavalid = validationrecaptcha(); 
            if(checkCaptchavalid!="true"){
                $("#g-recaptcha-error").html('<label style="color:red;">This field is required.</label>').show();
           }else{
                ShowLoadingIndicator();
               form.submit();
           }
            }
        });
    });
 

</script>