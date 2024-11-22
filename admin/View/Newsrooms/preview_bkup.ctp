<div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Newsroom Preview</h4>
                 <p>This is how your company newsroom will be visible to the public.  Please scroll to preview newsroom.<br>Check your entries carefully before submission.<br>If all details are fine, then click on the button, "Proceed with this newsroom" else click on "Go back and edit" button below.</p>
            </div>
             <div class="header-newsroom ew-banner-newsroom full"><?php
             if($this->data['Company']['banner_image'] != ''){
                echo $this->Html->image(SITEFRONTURL.'files/company/banner/'.$this->data['Company']['banner_path'].'/'.$this->data['Company']['banner_image'], array('id'=>"prev_banner_image"));
             }else{
                echo $this->Html->image('no-banner.png', array('class' => 'user-image',"id"=>"prev_logo_image", "width"=>"100%"));
             }
                ?>
            </div>
            <div class="modal-body full ew-sub-page ew-newsroom-block">
                <!-- newsroom mid -->
                <div class="full ew-newsroom-block">
                    <div class="container">
                        <div class="row">    
                            <div class="col-sm-3 ew-newsroom-left-user">
                                <div class="full ew-newsromm-user margin-bottom15">
                                    <div style="display: table-cell;height: 100%;vertical-align: middle;" >
                                    <?php 
                                  /*  if ($this->data['StaffUser']['profile_image']!= '') {
                                        echo $this->Html->image(SITEADMIN . '/files/profile_image/'.$this->data['StaffUser']['profile_image'], array('class' => 'user-image',"id"=>"prev_profile_image", "width"=>"200","height"=>"200"));

                                    } else {
                                        if($this->Session->read('Auth.User.profile_image')){
                                            echo $this->Html->image(SITEADMIN . '/files/profile_image/'.$this->Session->read('Auth.User.profile_image'), array('class' => 'user-image',"id"=>"prev_profile_image", "width"=>"200","height"=>"200")); 
                                        }else{
                                            echo $this->Html->image('no_image.jpeg', array('class' => 'user-image',"id"=>"prev_profile_image", "width"=>"200","height"=>"200"));
                                        }
                                    }*/
                                    ?> 
                                    <?php 
                                    if ($this->data['Company']['logo']!= '') {
                                     echo $this->Html->image(SITEFRONTURL.'files/company/logo/'.$this->data['Company']['logo_path'].'/'.$this->data['Company']['logo'], array('width'=>"100%",'id'=>'prev_logo_image'));

                                    } else {
                                       echo $this->Html->image('no_image.jpeg', array('class' => 'user-image',"id"=>"prev_logo_image", "width"=>"100%"));
                                    }
                                    ?>
                                        </div>
                                </div> 
                                
                                <div class="cus-ew-newsromm-user-box">
                                    <h4>Contact info</h4>
                                <div class="cus-ew-newsromm-user text-left">
                                    <div class="full ew-newsromm-address-info margin-bottom30">
                                    <!--<div class="avtar-user-box">-->
                                        <?php  
                                        // if ($this->Session->read('Auth.User.profile_image') != '') {
                                        // echo $this->Html->image(SITEADMIN . '/files/profile_image/' . $this->Session->read('Auth.User.profile_image'), array('class' => 'user-avtar'));
                                        // }else if ($this->data['StaffUser']['profile_image']!= '') {
                                        //       echo $this->Html->image(SITEADMIN . '/files/profile_image/'.$this->data['StaffUser']['profile_image'], array('class' => 'user-avtar'));
                                        // }else {
                                        //     echo $this->Html->image('no_image.jpeg', array('class' => 'user-avtar'));
                                        // }
                                        ?>
                                    <!--</div>-->
                                    <div class="avtar-user-box-right">
                                    <p id="prev_contact_info"><?php echo $this->data['Company']['contact_name']; ?></p>
                                    <?php if(!empty($this->data['Company']['job_title'])){ ?>
                                        <p id="prev_contact_info"><?php echo $this->data['Company']['job_title']; ?></p>
                                    <?php } ?>
                                    <p><span class="telphone"><?php echo $this->data['Company']['phone_number']; ?></span></p>                                    
                                    </div>
                                    <!--<p><span class="email"><?php echo $this->data['StaffUser']['email']; ?></span></p>-->
                                </div>
                                <?php if(!empty($this->data['Company']['address']) || !empty($this->data['Company']['city']) || !empty($this->data['Company']['state']) || !empty($this->data['Company']['nr_country_name']) || !empty($this->data['Company']['zip_code'])) { ?>
                                <div class="full ew-newsromm-address-info margin-bottom30">
                                    <h4 class="contact-icons"><i class="far fa-address-card"></i> Address</h4>  
                                    <p id="prev_address"><?php echo $this->data['Company']['address']; ?>,<br><?php echo $this->data['Company']['city']; ?>, <?php echo $this->data['Company']['state']; ?>, <?php echo $this->data['Company']['nr_country_name']; ?> - <?php echo $this->data['Company']['zip_code']; ?></p>
                                </div>
                                <?php } ?>
                                
                                    <?php /* ?>
                                    <div class="full ew-newsromm-contact-info margin-bottom30"><h4>About us</h4>
                                        <p id="pre_about" class="ew-bio"><?php echo $this->data['Company']['hear_about_us']; ?></p>
                                    </div>
                                    <?php */ ?>
                                    <?php if(!empty($this->data['Company']['nr_org_name'])){ ?>
                                    <div class="full ew-newsromm-contact-info margin-bottom30">
                                        <h4 class="contact-icons"><i class="far fa-building"></i> Organization Type</h4>
                                        <p id="prev_org" class="ew-organization"><?php echo $this->data['Company']['nr_org_name']; ?></p>  
                                        <!-- <p id="prev_contact_info"><?php //echo $this->data['Company']['job_title']; ?></p>   -->
                                    </div> 
                                    <?php } ?>
<!--                                     <?php if(!empty($this->data['Company']['job_title'])){ ?>
                                    <div class="full ew-newsromm-contact-info margin-bottom30">
                                        <h4 class="contact-icons"><i class="far fa-building"></i> Job title</h4>
                                        <p id="prev_contact_info"><?php echo $this->data['Company']['job_title']; ?></p>  
                                    </div> 
                                    <?php } ?> -->

                                 
                                <div class="full social-media-icon-newsroom margin-bottom20">
                                    <ul>
                                        <?php if(!empty($this->data['Company']['blog_url'])){ ?>
                                        <li class="ew-blog"><a id="prev-blog" target="_blank" href="<?php echo $this->data['Company']['blog_url']; ?>"></a></li>
                                        <?php } ?>
                                        <?php if(!empty($this->data['Company']['fb_link'])){ ?>
                                        <li class="ew-facebook"><a id="prev-facebook" target="_blank" href="<?php echo $this->data['Company']['fb_link']; ?>"></a></li>
                                        <?php } ?>
                                        <?php if(!empty($this->data['Company']['twitter_link'])){ ?>
                                        <li class="ew-twitter"><a id="prev-twitter" target="_blank" href="<?php echo $this->data['Company']['twitter_link']; ?>"></a></li>
                                        <?php } ?>
                                        <?php if(!empty($this->data['Company']['linkedin'])){ ?>
                                        <li class="ew-linkedin"><a id="prev-linkedin" target="_blank" href="<?php echo $this->data['Company']['linkedin']; ?>"></a></li>
                                        <?php } ?>
                                        <?php if(!empty($this->data['Company']['instagram'])){ ?>
                                        <li class="ew-instagram"><a id="prev-instagram" target="_blank" href="<?php echo $this->data['Company']['instagram']; ?>"></a></li>
                                        <?php } ?>
                                        <?php if(!empty($this->data['Company']['pinterest'])){ ?>
                                        <li class="ew-pintrest"><a id="prev-pintrest" target="_blank" href="<?php echo $this->data['Company']['pinterest']; ?>"></a></li>
                                        <?php } ?>
                                        <?php if(!empty($this->data['Company']['tumblr'])){ ?>
                                        <li class="ew-tumbler"><a id="prev-tumbler" target="_blank" href="<?php echo $this->data['Company']['tumblr']; ?>"></a></li>
                                        <?php } ?>   
                                    </ul>
                                </div>     
                            </div>
                            </div>    
                            </div>    
                            <div class="col-sm-9 ew-newsroom-right-section">
                                <div class="full ew-newsroom-user-bio">
                                    <h3 id="prev_contact_name" class="ew-user-name"><?php echo ucfirst($this->data['Company']['name']); ?></h3> 
                                    <p id="pre_des" class="ew-bio"><?php echo $this->data['Company']['description']; ?></p>    
                                </div>
                                <div class="ew-newsroom-tabing full">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                        <a class="nav-link <?php if($newsroomFilter=='prnews'){echo "active"; } ?>" href="<?php echo SITEURL.'newsrooms/preview'; ?>">Company News</a>
                                        </li>
                                        <li class="nav-item">
                                        <a class="nav-link <?php if($newsroomFilter=='social'){echo "active"; } ?>" href="<?php echo SITEURL.'newsrooms/preview/social'; ?>" role="tab">Social</a>
                                        </li>
                                        <li class="nav-item">
                                        <a class="nav-link <?php if($newsroomFilter=='companyassets'){echo "active"; } ?> " href="<?php echo SITEURL.'newsrooms/preview/companyassets'; ?>">Company assets</a>
                                        </li>
                                        </ul>
                                    <div class="tab-content" id="myTabContent" >
                                        <div class="tab-pane show active" id="compnaynews" role="tabcard" aria-labelledby="home-tab">
                                            

                                            <?php 
                                            if($newsroomFilter=='social'){
                                                echo $this->element("newsroom_social");
                                            }else if($newsroomFilter=='companyassets'){
                                                echo $this->element("newsroom_assets");
                                            }else{ ?>
                                                <div class="ew-newsroom-tabing full">
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane show active" id="compnaynews" role="tabcard" aria-labelledby="home-tab">
                                                            <div class="row">
                                                                <div class="col-sm-4 ew-latest-news-post margin-bottom20">
                                                                    <div class="full ew-latest-news-inner"> 
                                                                        <div class="orange-border ew-lastest-news-img-single full relative">
                                                                            <?php 
                                                                            echo $this->Html->image(SITEFRONTURL.'website/img/news-8.jpg', array('class' => 'user-image',"id"=>"prev_profile_image", "width"=>"100%"));
                                                                            ?> 
                 
                                                                            <div class="ew-date-sm ew-absolute-date"><?php echo date('F d, Y'); ?></div>
                                                                        </div>
                                                                        <div class="full ew-lastest-news-single-content">
                                                                            <a class="ew-link-title">This is dummy PR title.</a> 
                                                                            <div class="ew-company-news-bl float-left">
                                                                                <?php if($this->data['Company']['logo']){?>
                                                                                    <div class="ew-comany-logo float-left">
                                                                                        <div style="display: table-cell;height: 100%;vertical-align: middle;">
                                                                                            <?php 
                                                                                                if ($this->data['Company']['logo']!= '') {
                                                                                                 echo $this->Html->image(SITEFRONTURL.'files/company/logo/'.$this->data['Company']['logo_path'].'/'.$this->data['Company']['logo'], array('width'=>"100%",'id'=>'prev_logo_image'));

                                                                                                } else {
                                                                                                    echo $this->Html->image(SITEFRONTURL.'website/img/news-8.jpg', array('class' => 'user-image',"id"=>"prev_logo_image", "width"=>"100%"));
                                                                                                }
                                                                                                ?> 
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } ?>
                                                                                <div id="prev_company_name" class="ew-compnay-name float-left"><?php echo $this->data['Company']['name']; ?></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>  
                                                                </div>   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            
                                        </div>
                                    <?php 
                                    $paginatorInformation = $this->Paginator->params();
                                    if($paginatorInformation['pageCount']>1){ ?>
                                        <div class="row">
                                            <?php echo $this->element('pagination'); ?>
                                        </div>
                                    <?php }elseif(isset($totalCount)){?>
                                            <?php echo $this->element('custom_pagination');?>
                                    <?php }  ?>
                                    </div>
                                </div>
                            </div>
                        </div>            
                    </div>    
                </div>
                <a class="btn btn-default" href="<?php echo SITEURL.'users/create-newsroom';?>" >Go back and Edit</a> 
                  <?php
                echo $this->Form->create('StaffUser', array('type' => 'post'));
                echo $this->Form->input("pr_amount",array("type"=>"hidden","value"=>Configure::read('Site.newsroom.amount')));
                echo $this->Form->input("StaffUser.staff_role_id",array("type"=>"hidden","value"=>3));
                echo $this->Form->input("pr_currency",array("type"=>"hidden","value"=>Configure::read('Site.currency')));
                echo $this->Form->input("total_amount",array("type"=>"hidden","value"=>Configure::read('Site.newsroom.amount')));
                echo $this->Form->input('Create newsroom', array("type" => 'submit', 'class'=>"btn btn-primary",'id'=>'continuebtn','label'=>false));
                //"onclick"=>"ShowLoadingIndicator(); this.disabled=true"
                $this->Form->end();     
                ?> 
            </div> 
        </div>
    </div>