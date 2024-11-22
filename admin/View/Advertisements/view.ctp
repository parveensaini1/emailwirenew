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
                     View Page
                     <small>Here you can review your page informations</small>
                  </h3>
                  <ul class="breadcrumb">
                     <li>
                        <i class="icon-bookmark-empty"></i>
                        <?php echo $this->Html->link('Pages', array('controller' => 'pages', 'action' => 'index'),array("escape"=>false)); ?>
                        <span class="icon-angle-right"></span>
                     </li>
                   
                     <li><a href="#">View Page</a></li>
                  </ul>
                  <?php echo $this->Html->link('Back', array('action' => 'index'), array('escape' => false, 'class' => 'btn green','style'=>array('float:right'))); ?>
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
                                 <h4><i class="icon-reorder"></i>View Page details</h4>
                                 
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <div class="form-horizontal form-view">
                                    
                                   
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="firstName"><?php echo __('Title'); ?>:</label>
                                             <div class="controls">
                                                <span class="text"><?php echo h($page['Page']['title']); ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Description</label>
                                             <div class="controls">
                                                <span class="text"><?php echo $page['Page']['description']; ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
									
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="firstName"><?php echo __('Meta Title'); ?>:</label>
                                             <div class="controls">
                                                <span class="text"><?php echo $page['Page']['meta_title']; ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Meta Keyword</label>
                                             <div class="controls">
                                                <span class="text"><?php echo $page['Page']['meta_keyword']; ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
									
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="firstName"><?php echo __('Meta Description'); ?>:</label>
                                             <div class="controls">
                                                <span class="text"><?php echo $page['Page']['meta_description']; ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Section</label>
                                             <div class="controls">
                                                <span class="text"><?php echo h($page['Page']['section']); ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                    </div>
									
									
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="firstName"><?php echo __('Order'); ?>:</label>
                                             <div class="controls">
                                                <span class="text"><?php echo h($page['Page']['page_order']); ?></span>
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label" for="lastName">Status</label>
                                             <div class="controls">
												<?php
												if($page['Page']['status']=="1"){
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