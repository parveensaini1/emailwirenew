<div class="full ew-contact-form">
    <?php  
        echo $this->Form->create('Query', array('id' => 'queryform','novalidate' =>'novalidate','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
        <div class="row">
            <div class="col-lg-12 ew-contact-label margin-bottom15"><label>Contact us:</label></div> 
            <div class="col-sm-6 col-md-6">
            <?php 
                echo $this->Form->input('contact_name', array('class' => 'form-control ', 'id' => 'contact_name', 'type' => 'text',"required"=>"required","maxLength"=>"50",'label'=>false,'placeholder'=>"Contact name"));

            ?> 
            </div>
            <div class="col-sm-6 col-md-6">
                <?php echo $this->Form->input('email', array('type'=>"email",'class' => 'form-control ',"required"=>"required","maxLength"=>"150",'label'=>false,'placeholder'=>"Contact email address"));?>
            </div> 
            <div class="col-sm-6 col-md-6">
                <?php 
                echo $this->Form->input('phone', array('class' => 'form-control', 'id' => 'phone', 'type' => 'text',"required"=>"required",'minLength'=>"10",'maxLength'=>"15",'onkeypress'=>"return isNumber(event)",'placeholder'=>"Phone Number",'label'=>false));
                ?>
            </div>
            <div class="col-sm-6">
                <?php 
                echo $this->Form->input('subject', array('type'=>"text",'class' => 'form-control ',"required"=>"required","maxLength"=>"150",'label'=>false,'placeholder'=>"Subject"));
                ?>
            </div> 
            <div class="col-lg-12">
                <?php 
                    echo $this->Form->input('message', array('class' => 'form-control ', 'type' => 'textarea','label'=>false,'maxlength'=>"500",'autocomplete'=>"off",'label'=>false,"placeholder"=>"Query / Comment *","required"=>"required"));
                    ?> 
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
            <div class="col-lg-12">
                <input type="submit" name="submitbtn" id="submitbtn" value="SUBMIT NOW">
            </div>    
        </div> 
    <?php  echo $this->Form->end();?>
    <div id="querymsg" class="error" style="display: none;"></div>
</div>


<script type="text/javascript">
   $('#queryform').validate({
    submitHandler: function(form) {
        var checkCaptchavalid= validationrecaptcha();
        if(checkCaptchavalid!="true"){
            $("#g-recaptcha-error").html('<label style="color:red;">This field is required.</label>').show();
          }else{
            $("#querymsg").hide(); 
                $.ajax({
                type: "POST",
                url: SITEURL+'ajax/sendquery',
                data: $("#queryform").serialize(),
                success: function(data){
                    $("#AjaxLoading").hide();
                    var obj = JSON.parse(data);
                    var textclass="alert-success";
                    var iconclass='icon fa fa-check';
                    if(obj.status=='failed'){
                        textclass="alert-error";
                        iconclass='icon fa fa-ban';
                    }
                    $("#querymsg").html('<div class="alert '+textclass+' alert-dismissable" style="margin:0px;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><i class="'+iconclass+'"></i> '+obj.message+'</div>').show();
                    $('#queryform')[0].reset();
                }
                });
        }
    }
});   
</script>