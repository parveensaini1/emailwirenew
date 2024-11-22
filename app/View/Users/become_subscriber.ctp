<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));
  $page_content = $this->Post->get_users_page_content($action);
  if(!empty($page_content)){
    $page_title = $page_content['title'];
    $page_description = $page_content['description'];
  }

?>
<div class="full ew-account-page margin-bottom20">
    <div class="row">    
        <!-- title -->  
        <div class="col-lg-12"><div class="ew-title full"><?php echo $page_title; ?></div></div>
        <!-- End title --> 
        <!-- text and form fields -->
        <div class="col-sm-12 ew-account-form-fields">
            <?php echo $page_description; ?>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo $this->Session->flash();
                    ?>
                </div>
            </div>
            <?php
            echo $this->Form->create('StaffUser', array('type' => 'file', 'inputDefaults' => array('class' => 'form-control', 'label' => false,'div' => false),'id' => 'register_from','novalidate'));
            echo $this->Form->input("StaffUser.staff_role_id",array("type"=>"hidden","value"=>4)); ?>
            <div class="row">
                <div class="col-lg-12 ew-account-sub-head"><h4>Personal Information</h4></div>
                <div class="col-sm-6 form-group">
                    <label>Subscriber Type <em>*</em></label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Subscriber Type"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php 
                    $subscriber_options   =   array('1'=>'Journalist','2'=>'Bloggers','3'=>'Individuals');
                    echo $this->Form->input('subscriber_type', array("type" => 'select','empty'=>"--Select--",'options'=>$subscriber_options,'class'=>'form-control',"required"=>"required"));
                     ?>                        
                </div> 
            </div>    
            <div class="row">
                
                <div class="col-sm-6 form-group">
                    <label>First Name <em>*</em></label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Enter first name"><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php echo $this->Form->input('first_name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required")); ?>                        
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Last Name <em>*</em></label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Enter last name"><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php echo $this->Form->input('last_name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required")); ?>                        
                </div>                    
            </div> 
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>E-mail <em>*</em></label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Enter E-mail"><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('email', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required"));
                    ?>                        
                </div>
                <div class="col-sm-6 form-group">
                    <label>Confirm E-mail <em>*</em></label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Enter confirm E-mail"><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('confirm_email', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required"));
                    ?>                        
                </div> 
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Password <em>*</em></label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Enter password"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('password', array("type" => 'password','minlength'=>"8",'maxlength'=>"50",'class'=>'form-control',"required"=>"required"));
                    ?>                        
                </div>
                <div class="col-sm-6 form-group">
                    <label>Confirm Password <em>*</em></label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Enter confirm password"><i class="fa fa-question-circle" aria-hidden="true"></i></a>                   
                    <?php
                    echo $this->Form->input('verify_password', array("type" => 'password','minlength'=>"8",'maxlength'=>"50",'class'=>'form-control',"required"=>"required"));
                    ?>
                </div>                  
            </div>
            <div class="row">
                <div class="col-lg-12 form-group">
                    <div class="ew-captch-div has-feedback">
                         <script src='https://www.google.com/recaptcha/api.js'></script>
                         <div class="g-recaptcha" data-sitekey="<?php echo Configure::read('recaptcha_key');?>"></div>
                         <div id="g-recaptcha-error"></div>                     
                    </div> 
                 </div>
            </div>
            <div class="row">
                <div class="col-lg-12 form-group">
                    <?php echo $this->Form->input('Register', array("type" => 'submit')); ?>  
                </div>     
            </div>  
        </div>        
        <!-- End text and form fields -->
        <!-- Sidebar -->
        <div class="col-sm-4 ew-sidebar">
         <?php //  echo $this->element('signup_sidebar'); ?>
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