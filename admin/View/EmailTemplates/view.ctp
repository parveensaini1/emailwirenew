<div class="page-content">
         <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         
         <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN STYLE CUSTOMIZER -->
                  
                  <!-- END BEGIN STYLE CUSTOMIZER -->  
                  <h3 class="page-title">
                     View Email Template
                     <small>Here you can review your Email Template informations</small>
                  </h3>
                  <ul class="breadcrumb">
                     <li>
                        <i class="icon-bookmark-empty"></i>
                        <?php echo $this->Html->link('Email Templates', array('controller' => 'emailTemplates', 'action' => 'index',"admin"=>true),array("escape"=>false)); ?>
                        <span class="icon-angle-right"></span>
                     </li>
                   
                     <li><a href="#">View Email Template</a></li>
                  </ul>
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
                  <div class="tabbable tabbable-custom boxless">
                     
                     <div class="tab-content">
                      <div class="portlet box blue">
                              <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i>View Email Template details</h4>
                                 
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <div class="form-horizontal form-view">
                                    
                                   
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="firstName"><?php echo __('Title'); ?>:</label>
                                             <div class="controls">
                                                <span class="text"><?php echo h($emailTemplate['EmailTemplate']['title']); ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName"><?php echo __('Subject');?></label>
                                             <div class="controls">
                                                <span class="text"><?php echo $emailTemplate['EmailTemplate']['subject']; ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
									
									
                                    <div class="row-fluid">
                                      
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName"><?php echo __('From');?></label>
                                             <div class="controls">
                                                <span class="text"><?php echo $emailTemplate['EmailTemplate']['from']; ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
									   <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="firstName"><?php echo __('Reply To Email'); ?></label>
                                             <div class="controls">
                                                <span class="text"><?php echo $emailTemplate['EmailTemplate']['reply_to_email']; ?></span>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
									
                                    <div class="row-fluid">
                                       
                                       <!--/span-->
                                      <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName"><?php echo __('Status'); ?></label>
                                             <div class="controls">
												<?php
												if( $emailTemplate['EmailTemplate']['status']=="1"){
												$status	=	'Active';
												}else{
												$status	=	'Inactive';
												}
												?>
                                                <span class="text"><?php echo h($status); ?></span>
                                             </div>
                                          </div>
                                       </div>  
                                       <!--/span-->
									   
									    <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName"><?php echo __('Html'); ?></label>
                                             <div class="controls">
												<?php
												if( $emailTemplate['EmailTemplate']['is_html']=="1"){
												$html	=	'Yes';
												}else{
												$html	=	'No';
												}
												?>
                                                <span class="text"><?php echo h($html); ?></span>
                                             </div>
                                          </div>
                                       </div>  
                                    </div>
									
									<div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="firstName"><?php echo __('Description'); ?>:</label>
                                             <div class="controls">
                                                <span class="text">
												
												<?php 
												if($emailTemplate['EmailTemplate']['is_html']==1){
												echo $emailTemplate['EmailTemplate']['description']; 
												}else{
												echo strip_tags($emailTemplate['EmailTemplate']['description']);
												}
												?>
												</span>
                                             </div>
                                          </div>
                                       </div>
                                      
                                    </div>
									
									
                                    
                                    <div class="form-actions">
                                    <!--   <button type="submit" class="btn blue"><i class="icon-pencil"></i> Edit</button>
                                       <button type="button" class="btn">Back</button>-->
                                    </div>
                                 </div>
                                 <!-- END FORM-->  
                              </div>
                           </div>
                        
                        
                     </div>
                  </div>
               </div>
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>