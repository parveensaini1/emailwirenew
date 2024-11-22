<style type="text/css">.main-header,.main-footer,.ew-sidebar{display: none;}</style>
<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));?>
<?php echo $this->element('popup_company_alert'); ?>
<div class="full ew-account-page margin-bottom20">
    <div class="row">      
        <div class="col-sm-8 ew-account-form-fields">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo $this->Session->flash();
                    ?>
                </div>
            </div>
            <?php
            echo $this->Form->create('Company', array('type' => 'file', 'inputDefaults' => array('class' => 'form-control', 'label' => false, 'div' => false),"id"=>"new_company_from",'validate'));
             ?>
            <div class="row">
                <div class="col-lg-12 ew-account-sub-head"><h4>Company Information</h4></div>  
                <div class="col-sm-6 form-group">
                    <label>Contact Name *</label>                           
                    <?php
                    echo $this->Form->input('Company.contact_name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control ',"required"=>"required"));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Job Title *</label>                           
                    <?php
                    echo $this->Form->input('Company.job_title', array("type" => 'text','maxlength'=>"100",'class'=>'form-control ',"required"=>"required"));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Organization Type *</label>                          
                    <?php
                    echo $this->Form->input('Company.organization_type_id', array('empty' => '-Select-', "options" => $organization_list, 'class' => 'form-control ',"required"=>"required"));
                    ?>
                </div>  
                <div class="col-sm-6 form-group">
                    <label>Company Name *</label>  
                    <?php
                    echo $this->Form->input('Company.name', array("type" => 'text','maxlength'=>"100",'class'=>'form-control ',"required"=>"required","onchange"=>"search_company();","id"=>"company_name")); //,"onkeypress"=>"search_company();",
                    ?>
                     <div style="display: none;" id="check_company_message"></div>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Telephone *</label>  
                    <?php
                    echo $this->Form->input('Company.phone_number', array("type" => 'text','maxlength'=>"15",'class'=>'form-control validate[required, custom[phone],maxSize[15],minSize[10]]','onkeypress'=>"return isNumber(event)"));
                    ?>                        
                </div>
                <div class="col-sm-6 form-group">
                    <label>Fax Number </label>                          
                    <?php
                    echo $this->Form->input('Company.fax_number', array("type" => 'text','maxlength'=>"15",'onkeypress'=>"return isNumber(event)"));
                    ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Street Address *</label>  
                    <?php
                    echo $this->Form->input('Company.address', array("type" => 'text','maxlength'=>"255",'class'=>'form-control ',"required"=>"required"));
                    ?>                        
                </div>
                <div class="col-sm-6 form-group">
                    <label>City *</label>                          
                    <?php
                    echo $this->Form->input('Company.city', array("type" => 'text','maxlength'=>"100",'class'=>'form-control ',"required"=>"required"));
                    ?>
                </div>
                 <div class="col-sm-6 form-group">
                    <label>State / Province *</label>  
                    <?php
                    echo $this->Form->input('Company.state', array("type" => 'text','maxlength'=>"100",'class'=>'form-control ',"required"=>"required"));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Country *</label>  
                    <?php
                    echo $this->Form->input('Company.country_id', array('empty' => '-Select-', "options" => $country_list,'class'=>'form-control ',"required"=>"required"));
                    ?>
                </div> 
                  <div class="col-sm-6 form-group">
                    <label>Zip Code *</label>  
                    <?php
                    echo $this->Form->input('Company.zip_code', array("type" => 'text','maxlength'=>"6",'minlength'=>"5",'class'=>'form-control ',"required"=>"required",'onkeypress'=>"return isNumber(event)"));
                    ?>
                </div> 
                <div class="col-sm-12 form-group">
                    <label>Website URL *</label>  
                    <?php
                    echo $this->Form->input('Company.web_site', array("type" => 'text','class'=>'form-control',"required"=>"required",));
                    ?>                        
                </div> 
                <div class="col-lg-12 form-group">
                    <label>Blog URL </label>  
                    <?php
                    echo $this->Form->input('Company.blog_url',array("type" => 'text','class'=>'form-control'));
                    ?>                        
                </div>    
                <div class="col-lg-12 ew-account-sub-head"><h4>Company Social Media links</h4></div>      
                <div class="col-sm-6 form-group">
                    <label>LinkedIn</label>  
                    <?php
                    echo $this->Form->input('Company.linkedin', array("type" => 'text','class'=>'form-control'));
                    ?>                        
                </div>    
                <div class="col-sm-6 form-group">
                    <label>Twitter</label>  
                    <?php
                    echo $this->Form->input('Company.twitter_link', array("type" => 'text','class'=>'form-control'));
                    ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Facebook</label>  
                    <?php
                    echo $this->Form->input('Company.fb_link', array("type" => 'text','class'=>'form-control'));
                    ?>
                </div>  
                <div class="col-sm-6 form-group">
                    <label>Pinterest</label>  
                    <?php
                    echo $this->Form->input('Company.pinterest', array("type" => 'text','class'=>'form-control'));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Instagram </label>  
                    <?php
                    echo $this->Form->input('Company.instagram', array("type" => 'text','class'=>'form-control'));
                    ?> 
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Tumblr </label>  
                    <?php
                    echo $this->Form->input('Company.tumblr', array("type" => 'text','class'=>'form-control '));
                    ?>
                </div> 
                <div class="col-lg-12 form-group">
                    <label>Company description* </label>
                    <?php
                    echo $this->Form->input('Company.description', array("type" => 'textarea','class'=>'form-control ',"required"=>"required"));
                    ?>
                </div>
                <div class="col-lg-12 form-group">
                    <label>How Did You Hear About Us? * </label>
                    <?php
                    echo $this->Form->input('Company.hear_about_us', array("type" => 'textarea','class'=>'form-control ',"required"=>"required"));
                    ?>
                </div> 
                <div class="col-sm-6 form-group ew-company-logo">
                    <label>Company Logo  </label> 
                    <p>* Company logo should in 80 X 80</p>    
                    <label class="custom-file-upload">
                        <?php
                        echo $this->Form->input('Company.logo', array("type" => 'file','class'=>'form-control ',"required"=>"required",'id'=>'logo_image','accept'=>'image/*','onchange'=>"imagevalidation('logo_image',80,80,'both_less_greater',100,100)"));
                        ?>            
                        <label style="display: none;" id="logo_image-error"></label>                
                        Browse Logo
                    </label>  
                    <div id="image_err"></div>
                </div> 
                <div class="col-sm-6 form-group ew-personal-picture">
                    <label>Banner Image</label> 
                    <p>* Banner Photo should in 300 X 300</p>    
                    <label class="custom-file-upload">
                        <?php
                        echo $this->Form->input('Company.banner_image', array("type" => 'file','class'=>'form-control ','id'=>'banner_image',"required"=>"required",'accept'=>'image/*','onchange'=>"imagevalidation('banner_image',300,300,'both_less_greater',500,500)"));
                        ?>   
                        <label style="display: none;" id="banner_image-error"></label>                         
                        Browse Picture
                    </label>  
                </div> 
                <div class="col-lg-12 form-group ew-captch-div">
                     <script src='https://www.google.com/recaptcha/api.js'></script>
                     <div class="g-recaptcha" data-sitekey="6LeKmngUAAAAAPrD8F-12YikzO5TsC0U9M58EYuP"></div>                     
                </div> 
                <div class="col-lg-12 form-group">
                    <?php
                    echo $this->Form->input('Signup Now', array("type" => 'submit'));
                    ?> 

                </div>     
            </div>                
            <?php echo $this->Form->end(); ?>               
        </div>        
        <!-- End text and form fields -->
        <!-- Sidebar -->
        <div class="col-sm-4 ew-sidebar">
            <?php echo $this->element('signup_sidebar'); ?>
        </div>    
        <!-- End sidebar -->        
    </div>
</div>
<?php if(isset($csaved)&&!empty($csaved)){ ?>
<script type="text/javascript">
    $(document).ready(function(){
         window.onunload = refreshParent;
        window.close();
    });

    function refreshParent() {
        window.opener.location.reload();
    }

</script>
<?php } ?>

 
 <script type="text/javascript">
    // validate signup form on keyup and submit
    $(document).ready(function(){ 
        $("#new_company_from").validate({
            debug: false,
            rules: {
                "data[Company][phone_number]": {
                    required: true,
                    Number: true,
                    minlength: 10,
                    maxlength: 15
                },
                "data[Company][zip_code]": {
                    required: true,
                    Number: true,
                    minlength: 5,
                    maxlength: 6
                },
                rules: {
                    "data[StaffUser][logo]": {
                        required: true, 
                        accept: "jpg|jpeg|png", 
                    },
                },
                rules: {
                "data[StaffUser][banner_image]": {
                    required: true, 
                    accept: "jpg|jpeg|png", 
                },
                }
            },
            messages: { 
                "data[Company][logo]": {
                    required: "Please upload file.",
                    filesize:"File size must be less than 2000 KB.",
                    accept:"Please upload .jpg or .png or .jpeg file.",
                },
                "data[Company][banner_image]": {
                    required: "Please upload file.",
                    filesize:"File size must be less than 2000 KB.",
                    accept:"Please upload .jpg or .png or .jpeg file.",
                },
                "data[StaffUser][phone_number]": {
                    required: "Please Enter phone Number.",
                    Number: "Please Enter valid phone Number.",
                    minlength:"Phone Number Enter valid 10 digit phone Number.",
                    maxlength:"Phone Number Enter valid 10 digit phone Number.",
                },
                 "data[Company][zip_code]": {
                    required: "Please Enter zipcode.",
                    Number: "Please Enter valid zipcode.",
                    minlength:"Phone Number Enter valid 5 zipcode.",
                    maxlength:"Phone Number Enter valid 6 zipcode.",
                },
            }
        });
    });



window.URL = window.URL || window.webkitURL;
function imagevalidation(imageSelector,getImgwidth='',getImgheight='',condtion='equal',maxthenImgwidth='',
maxthenImghight='') {
      //e.preventDefault(); 
    var getImgwidth=(getImgwidth!='')?getImgwidth:80;
    var getImgheight=(getImgheight!='')?getImgheight:80;
    var form=$("#new_company_from");
    var image_err=$("#"+imageSelector+"-error");
    var selector="#"+imageSelector;
    var flag=0;
        var fileInput = $("#new_company_from").find(selector)[0],
        file = fileInput.files && fileInput.files[0];
        if(file){
            if(file.size<=1048576){
                var img = new Image();
                img.src = window.URL.createObjectURL(file);
                img.onload = function() {
                    var width = img.naturalWidth,height = img.naturalHeight;
                    window.URL.revokeObjectURL( img.src );
                    if(condtion=="equal"&& width == getImgwidth && height == getImgheight) {
                        flag=1;
                    }else if(condtion=="less"&& width <= getImgwidth && height <= getImgheight) {
                        flag=1;
                    }else if(condtion=="greater"&& width >= getImgwidth && height >= getImgheight) {
                        flag=1;
                    }else if(condtion=="both_less_greater"&& (width >= getImgwidth && height >= getImgheight)&&(width <= maxthenImgwidth && height <= maxthenImghight)) {
                        flag=1;
                    } 
                    if(flag==0){
                        $("#submit-btn").attr('disabled','disabled');
                        $con_text=(condtion!="equal")?condtion+' Or equal':condtion;
                        if(condtion!="both_less_greater"){
                            image_err.replaceWith("<label id='"+imageSelector+"-error' class='error' for='StaffUserProfileImage'>The Image Size should be "+$con_text+" "+getImgwidth+" X "+getImgheight+" (width X height).</label>");
                        }else{
                            image_err.replaceWith("<label id='"+imageSelector+"-error' class='error' for='StaffUserProfileImage'>The Image width should be "+getImgwidth+"px to "+maxthenImgwidth+" and height should be "+getImgheight+" to "+maxthenImghight+"px.</label>");  
                        }
                        return false;
                    }else{
                        image_err.hide();
                        $("#submit-btn").removeAttr('disabled');
                        return true;
                    }
                }; 
            }else{
                image_err.replaceWith("<label id='"+imageSelector+"-error' class='error' for='StaffUserProfileImage'>The Image Size should be less than 1Mb.</label>");
            }
        }
    }
    
function search_company() {
     $("#AjaxLoading").show();
    var company_name = $("#company_name").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo SITEURL; ?>ajax/search_company',
        data: {
            company_name: company_name,redirect: '<?php echo SITEURL; ?>users/take-over-company'
        },
        // async: false,
        success: function (response) {
            $("#AjaxLoading").hide();
            var obj=JSON.parse(response);
            if(obj.status=="false"){
                $("#submit-btn").attr('disabled','disabled');
                 $("#popuptitle").text("Take over Company");
                 $("#popalert-message").html(obj.message);
                 $("#popalert").modal("show");
            }else{
                $("#submit-btn").removeAttr('disabled');
            }
        } 
    }
    );
}        
</script>