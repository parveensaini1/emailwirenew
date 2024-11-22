<div class="full ew-account-page margin-bottom20">
    <div class="row">    
        <!-- title -->  
        <div class="col-lg-12"><div class="ew-title full">New Account & Newsroom Sign Up. </div></div>
        <!-- End title --> 
        <!-- text and form fields -->
        <div class="col-sm-8 ew-account-form-fields">
            <p>Create an account to submit press releases. The information required here should be legitimate business or personal information, contact persons and media assets necessary to distribute your news and to create your company or individual newsroom.</p>  
            <p>Note: If you want to subscribe to news, or if you are a  journalist, blogger or other media professional please register to receive the news you want here:</p> 
            <p>Each press release you submit will be  published under a newsroom that belongs to a company, organization, an individual entity or a domain name.  There is a one-time cost $49 cost approve to verify and approve each application. </p> 
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo $this->Session->flash();
                    ?>
                </div>
            </div>
            <?php
            echo $this->Form->create('StaffUser', array('type' => 'file', 'inputDefaults' => array('class' => 'form-control', 'label' => false, 'div' => false), 'novalidate'));
            ?>
            <div class="row">
                <div class="col-lg-12 ew-account-sub-head"><h4>Personal Information</h4></div>
                <div class="col-sm-6 form-group">
                    <label>First Name *</label>  
                    <?php
                    echo $this->Form->input('first_name', array("type" => 'text'));
                    ?>                        
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Last Name *</label>  
                    <?php
                    echo $this->Form->input('last_name', array("type" => 'text'));
                    ?>                        
                </div> 
                <div class="col-sm-6 form-group">
                    <label>E-mail *</label>  
                    <?php
                    echo $this->Form->input('email', array("type" => 'text'));
                    ?>                        
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Confirm E-mail *</label>  
                    <?php
                    echo $this->Form->input('confirm_email', array("type" => 'text'));
                    ?>                        
                </div> 
                <div class="col-lg-12 ew-account-sub-head"><h4>Password</h4></div>
                <div class="col-sm-6 form-group">
                    <label>Password *</label>  
                    <?php
                    echo $this->Form->input('password', array("type" => 'password'));
                    ?>                        
                </div>
                <div class="col-sm-6 form-group">
                    <label>Confirm Password *</label>                          
                    <?php
                    echo $this->Form->input('verify_password', array("type" => 'password'));
                    ?>
                </div> 
                <div class="col-lg-12 ew-account-sub-head"><h4>Company Information</h4></div>  
                <div class="col-sm-6 form-group">
                    <label>Job Title *</label>                           
                    <?php
                    echo $this->Form->input('Company.job_title', array("type" => 'text'));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Organization Type  *</label>                          
                    <?php
                    echo $this->Form->input('Company.organization_type_id', array('empty' => '-Select-', "options" => $organization_list, 'class' => 'form-control'));
                    ?>
                </div>  
                <div class="col-sm-6 form-group">
                    <label>Company Name *</label>  
                    <?php
                    echo $this->Form->input('Company.name', array("type" => 'text'));
                    ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Country *</label>  
                    <?php
                    echo $this->Form->input('Company.country_id', array('empty' => '-Select-', "options" => $country_list));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Street Address *</label>  
                    <?php
                    echo $this->Form->input('Company.address', array("type" => 'text'));
                    ?>                        
                </div>     
                <div class="col-sm-6 form-group">
                    <label>Telephone *</label>  
                    <?php
                    echo $this->Form->input('Company.phone_number', array("type" => 'text'));
                    ?>                        
                </div>
                <div class="col-sm-6 form-group">
                    <label>City *</label>                          
                    <?php
                    echo $this->Form->input('Company.city', array("type" => 'text'));
                    ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Fax Number </label>                          
                    <?php
                    echo $this->Form->input('Company.fax_number', array("type" => 'text'));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>State / Province *</label>  
                    <?php
                    echo $this->Form->input('Company.state', array("type" => 'text'));
                    ?>
                </div> 
                <div class="col-sm-12 form-group">
                    <label>Website URL *</label>  
                    <?php
                    echo $this->Form->input('Company.web_site', array("type" => 'text'));
                    ?>                        
                </div> 
                <div class="col-lg-12 form-group">
                    <label>Blog URL </label>  
                    <?php
                    echo $this->Form->input('Company.blog_url', array("type" => 'text'));
                    ?>                        
                </div>    
                <div class="col-lg-12 ew-account-sub-head"><h4>Company Social Media links</h4></div>      
                <div class="col-sm-6 form-group">
                    <label>LinkedIn</label>  
                    <?php
                    echo $this->Form->input('Company.linkedin', array("type" => 'text'));
                    ?>                        
                </div>    
                <div class="col-sm-6 form-group">
                    <label>Twitter</label>  
                    <?php
                    echo $this->Form->input('Company.twitter_link', array("type" => 'text'));
                    ?>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Facebook</label>  
                    <?php
                    echo $this->Form->input('Company.fb_link', array("type" => 'text'));
                    ?>
                </div>  
                <div class="col-sm-6 form-group">
                    <label>Pinterest</label>  
                    <?php
                    echo $this->Form->input('Company.pinterest', array("type" => 'text'));
                    ?>
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Instagram </label>  
                    <?php
                    echo $this->Form->input('Company.instagram', array("type" => 'text'));
                    ?> 
                </div> 
                <div class="col-sm-6 form-group">
                    <label>Tumblr </label>  
                    <?php
                    echo $this->Form->input('Company.tumblr', array("type" => 'text'));
                    ?>
                </div> 
                <div class="col-lg-12 form-group">
                    <label>Company description* </label>
                    <?php
                    echo $this->Form->input('Company.description', array("type" => 'textarea'));
                    ?>
                </div>
                <div class="col-lg-12 form-group">
                    <label>How Did You Hear About Us? * </label>
                    <?php
                    echo $this->Form->input('Company.hear_about_us', array("type" => 'textarea'));
                    ?>
                </div> 
                <div class="col-sm-6 form-group ew-company-logo">
                    <label>Company Logo  </label> 
                    <p>* Company logo should in 80 X 80</p>    
                    <label class="custom-file-upload">
                        <?php
                        echo $this->Form->input('Company.logo', array("type" => 'file'));
                        ?>                            
                        Browse Logo
                    </label>  
                </div> 
                <div class="col-sm-6 form-group ew-personal-picture">
                    <label>Personal Picture </label> 
                    <p>* Personal Photo should in 80 X 80</p>    
                    <label class="custom-file-upload">
                        <?php
                        echo $this->Form->input('StaffUser.profile_image', array("type" => 'file'));
                        ?>                            
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
            </form>                
        </div>        
        <!-- End text and form fields -->
        <!-- Sidebar -->
        <div class="col-sm-4 ew-sidebar">
            <div class="full ew-sidebar-inner orange-border">
                <!-- Your cart -->    
                <div class="full ew-side-cart margin-bottom15">
                    <div class="ew-title-price full">    
                        <h2>Now on your cart</h2>
                        <span class="float-left">Newsroom Feed :</span>
                        <span class="float-right">$49</span>     
                    </div>
                    <div class="ew-cart-dis-block full">
                        <div class="ew-promocode full">
                            <h2><span>Enter a Promo Code</span></h2> 
                            <form>
                                <input type="text" placeholder="Type Code Here" />
                                <input type="submit" value="Apply" />    
                            </form>    
                        </div>
                        <div class="full ew-cart-row">
                            <span class="float-left">Discount : </span>
                            <span class="float-right text-right">$30</span>     
                        </div> 
                        <div class="full ew-cart-row">
                            <span class="float-left">Total : </span>
                            <span class="float-right text-right">$550</span>     
                        </div>     
                    </div>    
                </div> 
                <!-- End Your cart --> 
                <!-- Have a question? -->
                <div class="ew-side-gray-box full margin-bottom15">
                    <h2>Have Question?</h2>
                    <p>Feel free to contact us if you have any question or concerns.</p> 
                    <div class="ew-phone ew-gray-b-text full">Call 832-7162363</div>
                    <div class="ew-ticket ew-gray-b-text full"><a href="<?php echo SITEURL.'users/support'; ?>">Open Ticket</a></div>     
                </div>    
                <!-- End Have a question? --> 
                <!-- Already have an account -->
                <div class="ew-side-gray-box full margin-bottom15">
                    <h2>Already have an account?</h2>
                    <p>You should <a href="#">login to your account</a> to continue the order process.</p> 
                </div>         
                <!-- End Already have an account --> 
                <!-- side address -->
                <div class="ew-side-address full margin-bottom15">
                    <img src="img/emailwire-logo.jpg" alt=""/>
                    <h3>Contact information</h3>
                    <p>GroupWeb Media LLC<br> 440 Benmar #2026 Houston,<br> TX 77060</p> 
                </div>         
                <!-- End side address -->     
            </div>    
        </div>    
        <!-- End sidebar -->        
    </div>
</div> 