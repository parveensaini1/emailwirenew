<?php
  echo $this->Html->css(array('cropper.min'));
  echo $this->Html->script(array('jquery.validate.min','additional-methods.min','cropper.min'));
  //custom_newsroom
  echo $this->element('popup_company_alert');
  echo $this->element('image_crop_banner');
  echo $this->element('image_crop_logo');
  echo $this->element('image_crop_profile');  
?>
<div class="full ew-account-page margin-bottom20">
    <div class="row">    
        <!-- title -->  
        <?php if(!$this->Session->read('Auth.User.id')){ ?>   
            <div class="col-lg-12"><div class="ew-title full">New Account & Newsroom Sign Up</div></div>
        <?php }else{?>
            <div class="col-lg-12"><div class="ew-title full">Create newsroom</div></div>
        <?php } ?>
        <div class="col-sm-8 ew-account-form-fields">
            
        <?php if(!$this->Session->read('Auth.User.id')){ ?>
            <p>Create an account to submit press releases. The information required here should be legitimate business or personal information, contact persons and media assets necessary to distribute your news and to create your company or individual newsroom.</p>  
            <p>Note: If you want to subscribe to news, or if you are a  journalist, blogger or other media professional please register to receive the news you want here:</p> 
            <p>Each press release you submit will be  published under a newsroom that belongs to a company, organization, an individual entity or a domain name.  There is a one-time cost $49 cost approve to verify and approve each application. </p> 
            <?php } ?>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo $this->Session->flash();
                    ?>
                </div>
            </div>
            <div id="form-sec" class="">
            <?php
            echo $this->Form->create('StaffUser', array('type' => 'file', 'inputDefaults' => array('class' => 'form-control', 'label' => false, 'div' => false,),"id"=>"register_from",'validate'));
            echo $this->Form->input("pr_amount",array("type"=>"hidden","value"=>Configure::read('Site.newsroom.amount')));
            echo $this->Form->input("StaffUser.staff_role_id",array("type"=>"hidden","value"=>3));
            echo $this->Form->input("pr_currency",array("type"=>"hidden","value"=>Configure::read('PR.currency')));
            echo $this->Form->input("total_amount",array("type"=>"hidden","value"=>Configure::read('Site.newsroom.amount')));
             ?>
            <div class="row">
                <div class="col-lg-12 ew-account-sub-head"><h4>Media Contact</h4></div>
                <?php  
                if(!empty($this->Session->read('Auth.User.staff_role_id'))){
                     echo $this->Form->input("StaffUser.id",array("type"=>"hidden","value"=>$this->Session->read('Auth.User.id'))); 
                     echo $this->Form->input("StaffUser.first_name",array("type"=>"hidden","value"=>$this->Session->read('Auth.User.first_name'),'id'=>'first_name'));
                     echo $this->Form->input("StaffUser.last_name",array("type"=>"hidden","value"=>$this->Session->read('Auth.User.last_name'),'id'=>'last_name' ));
                     echo $this->Form->input("StaffUser.email",array("type"=>"hidden","value"=>$this->Session->read('Auth.User.email'),'id'=>'uemail'));
                    ?>
                    <div class="col-sm-12 form-group"> 
                    <div class="already_user">Your information is already filled. Your are login with <?php echo ucfirst($this->Session->read('Auth.User.email'));?></div>
                    </div>  
                <?php }else{?>
                    <div class="col-sm-6 form-group">
                        <label>First Name *</label> 
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Fill first name here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>  
                        <?php
                        echo $this->Form->input('first_name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_first_name]',this.value)",'data-msg'=>"Please enter first name."));
                        ?>                        
                        
                    </div> 
                    <div class="col-sm-6 form-group">
                        <label>Last Name *</label>  
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Fill last name here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        <?php 

                        echo $this->Form->input('last_name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_last_name]',this.value)",'data-msg'=>"Please enter last name."));
                        ?>                     
                    </div> 
                    <div class="col-sm-6 form-group">
                        <label>E-mail *</label>  
                         <a href="javascript:void(0)" data-toggle="tooltip" title="Fill email here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        <?php
                        echo $this->Form->input('email', array("type" => 'email','maxlength'=>"50",'class'=>'form-control',"required"=>"required",'onchange'=>"check_user_email(); setCookie('CakeCookie[nr_email]',this.value)",'data-msg'=>"Please enter email.", "autocomplete"=>"off"));
                        ?>                        
                    </div> 
                    <div class="col-sm-6 form-group">
                        <label>Confirm E-mail *</label>  
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Fill confirm email here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        <?php
                        echo $this->Form->input('confirm_email', array("type" => 'email','maxlength'=>"50",'class'=>'form-control',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_confirm_email]',this.value)","equalTo"=>"#StaffUserEmail",'data-msg'=>"Please enter same email."));
                        ?>                        
                    </div> 
                    <div class="col-lg-12 ew-account-sub-head"><h4>Password</h4></div>
                    <div class="col-sm-6 form-group">
                        <label>Password *</label>  
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Fill password here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        <?php
                        echo $this->Form->input('password', array("type" => 'password','minlength'=>"8",'maxlength'=>"50",'class'=>'form-control ',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_password]',this.value)",'id'=>"password",'data-msg'=>"Please enter password.", "autocomplete"=>"off"));
                        ?>                        
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>Confirm Password *</label>  
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Fill confirm password here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>                        
                        <?php
                        echo $this->Form->input('verify_password', array("type" => 'password','minlength'=>"8",'maxlength'=>"50",'class'=>'form-control ',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_verify_password]',this.value)","equalTo"=>"#password",'id'=>"verify_password",'data-msg'=>"Please enter same passowrd."));
                        ?>
                    </div> 
                    <div class="col-sm-6 form-group ew-personal-picture">
                    <label>Personal Picture </label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Choose Image"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <br/>
                    <!-- <p>* Personal Photo should be greate than 200 X 200</p>     -->
                    <p></p>
                    <label class="custom-file-upload">
                        <?php
                        $requiredprofile=(!empty($this->data['StaffUser']['profile_image']))?"false":"required";
                        
                        echo $this->Form->input('StaffUser.profile_image', array("type" => 'file','class'=>'form-control ',"required"=>$requiredprofile,'id'=>'profile_image','accept'=>'image/*','onchange'=>"imagevalidation('profile_image',1,1,'greater')"));
                        echo $this->Form->input("StaffUser.encodedprofile",array("type"=>"hidden","id"=>"encodedprofile"));
                        ?>                            
                        <label style="display: none;" id="profile_image-error"></label>
                        Browse Picture
                    </label>  
                    <div class="row dynamic_proimage"> 

                          <?php 
                            if ($this->data['StaffUser']['profile_image']!= '') {
                                echo $this->Html->image(SITEADMIN . '/files/profile_image/'.$this->data['StaffUser']['profile_image'], array('class' => 'user-image',"id"=>"croped_profile_image"));
                            } else {
                                echo '<img style="display: none;" id="croped_profile_image" src="">';
                            }
                            ?> 

                        

                    </div>
                </div> 
                <?php } ?>
                 
                <div class="col-lg-12 ew-account-sub-head"><h4>Company Information</h4></div>  
                <div class="col-sm-6 form-group">
                    <label>Contact Name *</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill contact name here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>                          
                    <?php
                    echo $this->Form->input('Company.contact_name', array("type" => 'text','maxlength'=>"50",'class'=>'form-control ',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_contact_name]',this.value)",'data-msg'=>"Please enter contact name."));
                    ?>
                  
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Job Title *</label> 
                     <a href="javascript:void(0)" data-toggle="tooltip" title="Fill job title here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>                           
                    <?php
                    echo $this->Form->input('Company.job_title', array("type" => 'text','maxlength'=>"100",'class'=>'form-control ',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_job_title]',this.value)",'data-msg'=>"Please enter job title."));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Organization Type *</label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill organization type here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>                           
                    <?php
                    echo $this->Form->input('Company.organization_type_id', array('empty' => '-Select-', "options" => $organization_list, 'class' => 'form-control ',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_org_typ_id]',this.value)"));
                    ?>
                </div>  
                <div class="col-sm-6 form-group">
                    <label>Company Name *</label>
                     <a href="javascript:void(0)" data-toggle="tooltip" title="Fill company name here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>  
                    <?php
                    echo $this->Form->input('Company.name', array("type" => 'text','maxlength'=>"100",'class'=>'form-control ',"required"=>"required","id"=>"company_name",'onchange'=>"search_company(); setCookie('CakeCookie[nr_company_name]',this.value)",'data-msg'=>"Please enter company name.")); //"onkeypress"=>"search_company();",
                    ?>
                     <div style="display: none;" id="check_company_message"></div>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Telephone *</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill telephone number here "><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('Company.phone_number', array("type" => 'text','minlength'=>"10",'maxlength'=>"15",'class'=>'form-control','onkeypress'=>"return isNumber(event)",'onchange'=>"setCookie('CakeCookie[nr_phone_number]',this.value)",));
                    ?>                        
                </div>
                <div class="col-sm-6 form-group">
                    <label>Fax Number </label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill fax number here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>                         
                    <?php
                    echo $this->Form->input('Company.fax_number', array("type" => 'text','maxlength'=>"15",'onkeypress'=>"return isNumber(event)",'onchange'=>"setCookie('CakeCookie[nr_fax_number]',this.value)",));
                    ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Street Address *</label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill street address here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('Company.address', array("type" => 'text','maxlength'=>"255",'class'=>'form-control ',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_address]',this.value)",));
                    ?>                        
                </div>

                <div class="col-sm-6 form-group">
                    <label>City *</label>  
                     <a href="javascript:void(0)" data-toggle="tooltip" title="Fill city here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>                        
                    <?php
                    echo $this->Form->input('Company.city', array("type" => 'text','maxlength'=>"100",'class'=>'form-control ',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_city]',this.value)",));
                    ?>
                </div>
                 <div class="col-sm-6 form-group">
                    <label>State / Province *</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill state/province here "><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('Company.state', array("type" => 'text','maxlength'=>"100",'class'=>'form-control ',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_state]',this.value)",));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Country *</label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill country here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('Company.country_id', array('empty' => '-Select-', "options" => $country_list,'class'=>'form-control ',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_country]',this.value)",));
                    ?>
                </div> 
                  <div class="col-sm-6 form-group">
                    <label>Zip Code *</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill zipcode here "><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('Company.zip_code', array("type" => 'text','maxlength'=>"6",'minlength'=>"5",'class'=>'form-control ','minlength'=>"5",'maxlength'=>"6",'onkeypress'=>"return isNumber(event)",'onchange'=>"setCookie('CakeCookie[nr_zip_code]',this.value)",));
                    ?>
                </div> 
                <div class="col-sm-12 form-group">
                    <label>Website URL *</label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill website url here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('Company.web_site', array("type" => 'text','class'=>'form-control',"required"=>"required",'onchange'=>"setCookie('CakeCookie[nr_web_site]',this.value)",));
                    ?>                        
                </div> 
                <div class="col-lg-12 form-group">
                    <label>Blog URL </label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill blog url here "><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('Company.blog_url',array("type" => 'text','class'=>'form-control','onchange'=>"setCookie('CakeCookie[nr_blog_url]',this.value)",));
                    ?>                        
                </div>    
                <div class="col-lg-12 ew-account-sub-head"><h4>Company Social Media links</h4></div>      
                <div class="col-sm-6 form-group">
                    <label>LinkedIn</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill linked url here "><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('Company.linkedin', array("type" => 'text','class'=>'form-control','onchange'=>"setCookie('CakeCookie[nr_linkedin]',this.value)",));
                    ?>                        
                </div>    
                <div class="col-sm-6 form-group">
                    <label>Twitter</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill twitter url here "><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('Company.twitter_link', array("type" => 'text','class'=>'form-control','onchange'=>"setCookie('CakeCookie[nr_twitter_link]',this.value)",));
                    ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Facebook</label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill facbook url here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('Company.fb_link', array("type" => 'text','class'=>'form-control','onchange'=>"setCookie('CakeCookie[nr_fb_link]',this.value)",));
                    ?>
                </div>  
                <div class="col-sm-6 form-group">
                    <label>Pinterest</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill pinterest url here "><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                    <?php
                    echo $this->Form->input('Company.pinterest', array("type" => 'text','class'=>'form-control','onchange'=>"setCookie('CakeCookie[nr_pinterest]',this.value)",));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Instagram </label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill instagram url here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>  
                    <?php
                    echo $this->Form->input('Company.instagram', array("type" => 'text','class'=>'form-control','onchange'=>"setCookie('CakeCookie[nr_instagram]',this.value)",));
                    ?> 
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Tumblr </label>  
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill tumbler url here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('Company.tumblr', array("type" => 'text','class'=>'form-control ','onchange'=>"setCookie('CakeCookie[nr_tumblr]',this.value)",));
                    ?>
                </div> 
                <div class="col-lg-12 form-group">
                    <label>Company description* </label>
                     <a href="javascript:void(0)" data-toggle="tooltip" title="Fill company description here "><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('Company.description', array("type" => 'textarea','class'=>'form-control ',"required"=>"required","col"=>"30","row"=>"30"));
                    ?>
                </div>
                <div class="col-lg-12 form-group">
                    <label>How Did You Hear About Us? * </label>
                     <a href="javascript:void(0)" data-toggle="tooltip" title="Fill detail  how did you hear about us"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('Company.hear_about_us', array("type" => 'textarea','class'=>'form-control ',"required"=>"required","col"=>"30","row"=>"30"));
                    ?>
                </div> 
                <div class="col-sm-6 form-group ew-company-logo">
                    <label>Company Logo  </label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="choose image"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <!-- <p>* Company logo should be greater than 255 X 255</p>     -->
                    <p></p>
                    <label class="custom-file-upload">
                        <?php
                        $requiredlogo=(!empty($this->data['Company']['logo']))?"false":"required";

                        echo $this->Form->input('Company.logo', array("type" => 'file','class'=>'form-control ',"required"=>$requiredlogo,'id'=>'logo_image','accept'=>'image/*','onchange'=>"imagevalidation('logo_image',1,1,'greater')"));
                        
                         echo $this->Form->input("Company.encodedlogo",array("type"=>"hidden","id"=>"encodedlogo"));
                        ?>            
                        <label style="display: none;" id="logo_image-error"></label>                
                        Browse Logo
                    </label>  
                    <div id="image_err"></div>

                    
                    <?php 
                        if (isset($this->data['Company']['logo'])&&$this->data['Company']['logo']!= '') {
                      
                        echo "<div id='croped-logo-img-bx' class='row croped-logo-img-section'>".$this->Html->image(SITEURL.'files/company/logo/'.$this->data['Company']['logo_path'].'/'.$this->data['Company']['logo'], array('width'=>"100%",'id'=>'croped_logo_image'))."</div>";
                        }else{
                            echo "<div id='croped-logo-img-bx' style='display: none;' class='row croped-logo-img-section'><div class='ewlogobx'>".'<img id="croped_logo_image" src=""></div></div>';
                        }
                    ?>
                        
                    
                </div> 
                <div class="col-sm-6 form-group ew-personal-picture">
                    <label>Banner Image</label> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Choose image"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <!-- <p>* Banner Photo should be greater than 1280 X 320</p>     -->
                    <p></p>
                    <label class="custom-file-upload">
                        <?php
                        $requiredb=(!empty($this->data['Company']['banner_image']))?"false":"required";
                        echo $this->Form->input('Company.banner_image', array("type" => 'file','class'=>'form-control ','id'=>'banner_image',"required"=>$requiredb,'accept'=>'image/*','onchange'=>"imagevalidation('banner_image',1,1,'greater')"));
                        
                        echo $this->Form->input("Company.encodedbanner",array("type"=>"hidden","id"=>"encodedbanner"));

                        ?>   
                        <label style="display: none;" id="banner_image-error"></label>Browse Picture</label>
                   <div class="row croped-banner-img-section">
                    <?php 
                        if (isset($this->data['Company']['banner_image'])&&$this->data['Company']['banner_image']!= '') {
                         echo $this->Html->image(SITEURL.'files/company/banner/'.$this->data['Company']['banner_path'].'/'.$this->data['Company']['banner_image'], array('id'=>"croped_banner_image"));
                         
                        } else {
                            echo '<img style="display: none;" id="croped_banner_image" src="">';
                        }
                        ?> 
                   </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group ew-company-file">
                        <label>Extra media files  </label>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Include extra media files to your newsroom such as white papers, infographics, product shots, etc."><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                        <!-- <p>* Company logo should be greater than 255 X 255</p>     -->
                        <p></p>
                        <label class="custom-file-upload">
                            <?php
                            echo $this->Form->input('Company.docfile', array("type" => 'file','class'=>'form-control ','id'=>'docfile','accept'=>'application/pdf,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document'));
                            ?>            
                            Browse pdf/doc
                        </label>  
                    </div> 
                </div> 
                <div class="col-lg-12 form-group ew-captch-div">
                     <script src='https://www.google.com/recaptcha/api.js'></script>
                     <div class="g-recaptcha" data-sitekey="6LeKmngUAAAAAPrD8F-12YikzO5TsC0U9M58EYuP"></div>
                     <div id="g-recaptcha-error" style="display: none;" ></div>          
                </div>  
                <div class="col-lg-12 form-group">
                    <input id="submit-btn" type="button" value="Newsroom Preview" class="create-newsroom-btn">
                    <p style="display: none;" id="create-preview">It will take short while we are creating newsroom preview. </p>
                   <!-- <?php echo $this->Form->input('Preview', array("type" => 'submit','id'=>'submit-btn',)); ?> -->
                </div>  

            </div>
            <?php echo $this->Form->end(); ?>      
            </div>    
        </div>    

        <div class="col-sm-4 ew-sidebar">
            <?php echo $this->element('signup_sidebar'); ?>
        </div>    

        <!-- End sidebar -->        
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){ 
    search_company();
    check_user_email();
    $('#submit-btn').on('click', function() {
        var checkvalid=$("#register_from").valid({
            debug: false,
            rules: { 
                "data[Company][phone_number]": {
                    required: true,
                    Number: true,
                    minlength: 10,
                    maxlength: 15
                }, 
                "data[StaffUser][logo]": {
                    required: true, 
                    accept: "jpg|jpeg|png", 
                },
                "data[StaffUser][banner_image]": {
                    required: true, 
                    accept: "jpg|jpeg|png", 
                },
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
        if(checkvalid){
         var checkCaptchavalid= validationrecaptcha();
         if(checkCaptchavalid!="true"){
            $("#g-recaptcha-error").html('<label style="color:red;">This field is required.</label>').show();
          }else{
            $('#AjaxLoading').show();
            $("#register_from").submit();
            $("#submit-btn").attr('disabled','disabled');
            $("#create-preview").show();
          }
        }else{
             $('html, body').animate({scrollTop:520}, 'slow');
              $("#submit-btn").removeAttr('disabled');
              $("#create-preview").hide();
        } 
    }); 
}); 

$('#CompanyOrganizationTypeId').on('change', function () { 
     var selectedText  = this.selectedOptions[0].text;
      $("#prev_org").text(selectedText);
      setCookie('CakeCookie[nr_org_name]',selectedText);
});

$('#CompanyCountryId').on('change', function () { 
     var selectedText  = this.selectedOptions[0].text;
      $("#prev_country").text(selectedText);
      setCookie('CakeCookie[nr_country_name]',selectedText);
});    


window.URL = window.URL || window.webkitURL;
function imagevalidation(imageSelector,getImgwidth='',getImgheight='',condtion='equal',maxthenImgwidth='',
maxthenImghight='') { 
    var getImgwidth=(getImgwidth!='')?getImgwidth:200;
    var getImgheight=(getImgheight!='')?getImgheight:200;
    var form=$("#register_from");
    var image_err=$("#"+imageSelector+"-error");
    var selector="#"+imageSelector;
    var flag=0;
        var fileInput = $("#register_from").find(selector)[0],
        file = fileInput.files && fileInput.files[0];
        if(file){
            if(file.size<=(1048576*2)){
                var img = new Image();
                img.src = window.URL.createObjectURL(file);
                img.onload = function() {
                    var width = img.naturalWidth,height = img.naturalHeight;
                    window.URL.revokeObjectURL(img.src);
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
                        image_preview(fileInput,imageSelector);
                        return true;
                    }
                };  
            }else{
                $("#submit-btn").attr('disabled','disabled');
                image_err.replaceWith("<label id='"+imageSelector+"-error' class='error' for='StaffUserProfileImage'>The Image Size should be less than 2Mb.</label>");
            }
        }
    }


function image_preview(fileInput,imageSelector) {
    var $modal = $('#modal_'+imageSelector);
    if(imageSelector=="logo_image"){
        var cropimage = document.getElementById('cropimagelogo'); 
    }else if(imageSelector=="banner_image"){
        var cropimage = document.getElementById('cropimagebanner'); 
    }else{
       var cropimage = document.getElementById('cropimageprofile');  
    }
    var files = fileInput.files;

    for (var i = 0; i < files.length; i++) {           
        var file = files[i];
        var imageType = /image.*/;     
        if (!file.type.match(imageType)) {
            continue;
        }                       
        cropimage.value = '';           
        cropimage.file = file;    
        var reader = new FileReader();
        reader.onload = (function(aImg) { 
            return function(e) { 
                aImg.src = e.target.result; 
            }; 
        })(cropimage);
        reader.readAsDataURL(file);
    }
    $modal.modal({backdrop: 'static',keyboard: false });
}  



window.addEventListener('DOMContentLoaded', function () {
        var bannerimage_selector = document.getElementById('croped_banner_image');
        var bannercropimage = document.getElementById('cropimagebanner');
        var minCroppedWidth = 1280;
        var minCroppedHeight = 320;
        var maxCroppedWidth = 1900;
        var maxCroppedHeight = 320;
        var $alert = $('.alert');
        var $modal = $('#modal_banner_image');
        var cropbtn=document.getElementById('cropbanner');
        var skipcropbtn=document.getElementById('skipcropbanner');
        var $prevImageSelector = $('#prev_banner_image');

        var cropper; 
        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(bannercropimage, {
            viewMode: 3,
            data: {
              width: (minCroppedWidth + maxCroppedWidth) / 2,
              height: (minCroppedHeight + maxCroppedHeight) / 2,
            },
            crop: function (event) {
              var width = event.detail.width;
              var height = event.detail.height;
              if (
                width < minCroppedWidth
                || height < minCroppedHeight
                || width > maxCroppedWidth
                || height > maxCroppedHeight
              ) {
                cropper.setData({
                  width: Math.max(minCroppedWidth, Math.min(maxCroppedWidth, width)),
                  height: Math.max(minCroppedHeight, Math.min(maxCroppedHeight, height)),
                });
              }
            },
            });
          }).on('hidden.bs.modal', function () {
              cropper.destroy();
              cropper = null;
          });

        cropbtn.addEventListener('click', function () {
            var initialAvatarURL;
            var canvas;
            $modal.modal('hide');
            if (cropper) {
              canvas = cropper.getCroppedCanvas({
                width: 1900,
                height: 320,
              });
     

               var oldimage=$("#encodedbanner").val();
               uploadImage(oldimage,canvas.toDataURL(),"banner","encodedbanner","croped_banner_image","nr_banner_image","nr_banner_path","banner_image-error");
               /*
              var resultData =uploadImage(oldimage,canvas.toDataURL(),"banner","encodedbanner");
              var obj = JSON.parse(resultData);
              var img_url=obj.img_url;
              $("#encodedbanner").val(img_url);
              setCookie('CakeCookie[encodedbanner]',img_url); 
              setCookie('CakeCookie[nr_banner_path]',obj.image_path);
              setCookie('CakeCookie[nr_banner_image]',obj.image_name);
              initialAvatarURL = bannerimage_selector.src;
              bannerimage_selector.src = img_url; 
              $prevImageSelector.attr("src",img_url.src);  
              bannerimage_selector.style.display = "block";
              $alert.removeClass('alert-success alert-warning');*/
              /*canvas.toBlob(function (blob) {
                console.log(blob);
              // var formData = new FormData();
              // formData.append('avatar', blob, 'avatar.jpg');

              });*/
            }
        });

        skipcropbtn.addEventListener('click', function () {
          $("#banner_image").val('');
        });
});



window.addEventListener('DOMContentLoaded', function () {
      var pimage_selector = document.getElementById('croped_profile_image');
      var pcropimage = document.getElementById('cropimageprofile');
      var minCroppedWidth = 10;
      var minCroppedHeight = 10;
      var maxCroppedWidth = 1900;
      var maxCroppedHeight = 1900;
      var $alert = $('.alert');
      var $modal = $('#modal_profile_image');
      var cropbtn=document.getElementById('cropprofile');
      var skipcropbtn=document.getElementById('skipcropprofile');
      var $prevImageSelector = $('#prev_profile_image');
      var cropper; 
      $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(pcropimage, {
            viewMode: 2,
            data: {
              width: (minCroppedWidth + maxCroppedWidth) / 2,
              height: (minCroppedHeight + maxCroppedHeight) / 2,
            },
            crop: function (event) {
              var width = event.detail.width;
              var height = event.detail.height;
              if (
                width < minCroppedWidth
                || height < minCroppedHeight
                || width > maxCroppedWidth
                || height > maxCroppedHeight
              ) {
                cropper.setData({
                  width: Math.max(minCroppedWidth, Math.min(maxCroppedWidth, width)),
                  height: Math.max(minCroppedHeight, Math.min(maxCroppedHeight, height)),
                });
              }
            },
            });
        }).on('hidden.bs.modal', function () {
          cropper.destroy();
          cropper = null;
        });

        cropbtn.addEventListener('click', function () {
            var initialAvatarURL;
            var canvas;
            $modal.modal('hide');
            if (cropper) {
              canvas = cropper.getCroppedCanvas({
                width: 200,
                height: 200,
              });


              var oldimage=$("#encodedprofile").val();
              uploadImage(oldimage,canvas.toDataURL(),"profile","encodedprofile","croped_profile_image","nr_profile_image","","profile_image-error");

              /*
              var resultData =uploadImage(oldimage,canvas.toDataURL(),"profile","encodedprofile");

              var obj = JSON.parse(resultData);
              var img_url=obj.img_url;
              $("#encodedprofile").val(img_url);
              setCookie('CakeCookie[encodedprofile]',img_url); 
              setCookie('CakeCookie[nr_profile_image]',obj.image_name); 
              initialAvatarURL = pimage_selector.src;
              pimage_selector.src = img_url; 
              $prevImageSelector.attr("src",img_url.src);  
         
              pimage_selector.style.display = "block";
              $alert.removeClass('alert-success alert-warning');*/
              /*canvas.toBlob(function (blob) {
                console.log(blob);
              // var formData = new FormData();
              // formData.append('avatar', blob, 'avatar.jpg');

              });*/
            }
         });

          skipcropbtn.addEventListener('click', function () {
             $("#profile_image").val('');
             // $pimage_selector
          pimage_selector.src = ''; 
          $("#encodedprofile").val(''); 
          $prevImageSelector.attr("src",SITEURL+'img/no_image.jpeg');
              // imagevalidation('profile_image',200,200,'equal');
          });
});



window.addEventListener('DOMContentLoaded', function () {
      var logo_image_selector = document.getElementById('croped_logo_image');
      var logocropimage = document.getElementById('cropimagelogo');
      var minCroppedWidth = 10;
      var minCroppedHeight = 10;
      var maxCroppedWidth = 1900;
      var maxCroppedHeight = 1900;
      var $alert = $('.alert');
      var $modal = $('#modal_logo_image');
      var $prevImageSelector = $('#prev_logo_image');
      var cropbtn=document.getElementById('croplogo');
      var skipcropbtn=document.getElementById('skipcroplogo');
      var cropper; 
      $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(logocropimage, {
            viewMode: 1,
            data: {
              width: (minCroppedWidth + maxCroppedWidth) / 2,
              height: (minCroppedHeight + maxCroppedHeight) / 2,
            },
            crop: function (event) {
              var width = event.detail.width;
              var height = event.detail.height;
              if (
                width < minCroppedWidth
                || height < minCroppedHeight
                || width > maxCroppedWidth
                || height > maxCroppedHeight
              ) {
                cropper.setData({
                  width: Math.max(minCroppedWidth, Math.min(maxCroppedWidth, width)),
                  height: Math.max(minCroppedHeight, Math.min(maxCroppedHeight, height)),
                });
              }
            },
            });
      }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
      });

        cropbtn.addEventListener('click', function () {
            var initialAvatarURL;
            var canvas;
            $modal.modal('hide');
            if (cropper) {
              canvas = cropper.getCroppedCanvas({
                width: 255,
                height: 255,
              });

              var oldimage=$("#encodedlogo").val();
               uploadImage(oldimage,canvas.toDataURL(),"logo","encodedlogo","croped_logo_image","nr_logo","nr_logo_path","logo_image-error");
               $("#croped-logo-img-bx").show();
               /*
              var resultData =uploadImage(oldimage,canvas.toDataURL(),"logo","encodedlogo");
              var obj = JSON.parse(resultData);
              var img_url=obj.img_url;
              $("#encodedlogo").val(img_url);
              setCookie('CakeCookie[encodedlogo]',img_url); 
              setCookie('CakeCookie[nr_logo_path]',obj.image_path);
              setCookie('CakeCookie[nr_logo]',obj.image_name);
              

              initialAvatarURL = logo_image_selector.src;
              logo_image_selector.src = img_url; 

               $prevImageSelector.attr("src",img_url.src);  
              document.getElementById('croped-logo-img-bx').style.display = "block";
              $alert.removeClass('alert-success alert-warning');
              /*canvas.toBlob(function (blob) {
              //console.log(blob);
              // var formData = new FormData();
              // formData.append('avatar', blob, 'avatar.jpg');

              });*/
            }
        });


        skipcropbtn.addEventListener('click', function () {
            $("#logo_image").val('');
        });
});

if(getCookie('CakeCookie[nr_address]')!=''){
    $("#CompanyAddress").val(getCookie('CakeCookie[nr_address]'));
}



function uploadImage(oldimage="",image,image_type,inputId,image_selector,cookie_f_name,cookie_path_name,errId){ 
    $("#submit-btn").attr('disabled','disabled');
    $.ajax({
        type: 'POST',
        url: SITEURL+'ajax/uploadimage',
        data:{oldimage:oldimage,base64: image,image_type:image_type},
        // async: false,
        success: function (data) {
             $("#submit-btn").removeAttr('disabled');
              var obj = JSON.parse(data);
                if(obj.status=='success'){
                  var img_url=obj.img_url;
                  $("#"+inputId).val(img_url);
                  setCookie('CakeCookie['+inputId+']',img_url); 
                  setCookie('CakeCookie['+cookie_path_name+']',obj.image_path);
                  setCookie('CakeCookie['+cookie_f_name+']',obj.image_name);
                  $("#"+image_selector).attr("src",img_url).show();  
                }else{
                    $("#"+errId).before('<p>'+obj.message+'</p>').show();
                }
        }}
    );   
}
</script>
