<?php 
$actions=$this->Html->link(__("Front view"), array(
                        'controller' => $controller,
                        'action' =>"frontview",
                        $data[$model]['id'],   
                            ), array('class' => 'btn btn-xs btn-info'));

?>
<section class="content-section">
  <div class="box">
    <div class="box-header with-border"> 
      <div class="row">
          <div class="col-sm-4">
           <button onclick="goBack()" class="btn btn-xs btn-primary">Go Back</button>
          </div>
          <div class="col-sm-8 text-right">
            <?php echo $actions;?>       
          </div>
        </div>
      </div>
    </div>
  </section>
<div class="row">
   <div class="col-lg-12">
      <div class="card card-default">  
         <div class="card-body"> 
             <div class="dataTable_wrapper">
                 <div class="col-sm-3"> 
                     <h2>Client Profile</h2>
                     <div class="widget-user-image">
                       <?php 
                        if (file_exists(WWW_ROOT . 'files/profile_image/' . $data['StaffUser']['0']['profile_image']) && $data['StaffUser']['0']['profile_image']!='') {
                              echo $this->Html->image('/files/profile_image/' .$data['StaffUser']['0']['profile_image'], array('class' => 'img-circle','height'=>"128px",'width'=>"128px"));
                        }else{
                              echo $this->Html->image('no_image.jpeg', array('class' => 'img-circle','height'=>"128px",'width'=>"128px"));
                        }
                        ?> 
                     </div>
                     <h3 class="widget-user-username"><?php echo $data['StaffUser']['0']['first_name'].' '.$data['StaffUser']['0']['last_name']; ?></h3>
                     <h5 class="widget-user-desc"><?php echo $data['Company']['job_title'];?></h5>
                     <h5 class="widget-user-desc"><?php echo $data['StaffUser']['0']['email'];?></h5>
                     <p>PR Plan: <strong><?php echo ($data['StaffUser']['0']['pr_plan_paid']==1)?"Purchased":"Not Purchased"; ?></strong> </p>
                     <!-- <p><?php echo $this->Custom->getUserStatus($data['StaffUser']['0']['status'])?></p> -->
                 </div>
                 <div class="col-sm-6">
                     <h2>Newsroom details</h2> 
                     <div class="row">
                        <div class="col-sm-12">
                           <div class="control-group">
                              <label class="control-label" for="firstName"><?php echo __('Name'); ?>:</label>
                                 <span class="text"><?php echo h($data['Company']['name']); ?></span>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="control-group">
                              <label class="control-label" for="lastName">Organization</label>
                                 <span class="text"><?php echo $this->Custom->organizationTypeById($data['Company']['organization_type_id']); ?></span>
                           </div>
                        </div> 
                        <!--/span-->
                        
                     </div> 
                     <div class="row">
                        <div class="col-sm-12">
                           <div class="control-group">
                              <label class="control-label" for="firstName"><?php echo __('Contact Name'); ?>:</label>
                                 <span class="text"><?php echo h($data['Company']['contact_name']); ?></span>
                           </div>
                        </div> 
                        <div class="col-sm-12">
                           <div class="control-group">
                              <label class="control-label" for="firstName"><?php echo __('About'); ?>:</label>
                                 <span class="text"><?php echo h($data['Company']['hear_about_us']); ?></span>
                           </div>
                        </div>
                     </div> 

                      <div class="row">
                        <div class="col-sm-12">
                           <div class="control-group">
                              <label class="control-label" for="lastName">Phone number</label>
                                 <span class="text"><?php echo $data['Company']['phone_number']; ?></span>
                           </div>
                        </div> 
                         <div class="col-sm-12">
                           <div class="control-group">
                              <label class="control-label" for="lastName">Fax number</label>
                                 <span class="text"><?php echo $data['Company']['fax_number']; ?></span>
                           </div>
                        </div> 
                     </div> 
                     <div class="row">                        
                        <div class="col-sm-12">
                           <div class="control-group">
                              <label class="control-label" for="lastName">Address</label>
                                 <span class="text"><?php echo $data['Company']['address'].', '.$data['Company']['city'].', '.$data['Company']['state'].', '.$this->Custom->CountryNameById($data['Company']['id']).'-'.$data['Company']['zip_code']; ?></span>
                           </div>
                        </div> 
                        <!--/span-->
                        <div class="col-sm-12">
                           <div class="control-group">
                              <label class="control-label" for="lastName">Description</label>
                                 <span class="text"><?php echo $data['Company']['description']; ?></span>
                           </div>
                        </div> 
                     </div> 

                     <div class="row">
                        <div class="col-sm-12">
                           <div class="control-group">
                              <label class="control-label" for="firstName"><?php echo __('Social links'); ?>:</label>
                                 <ul>
                                   <?php 
                                 if(!empty($data['Company']['fb_link']))
                                    echo "<li class='social-links'><a target='_blank' href='".$data['Company']['fb_link']."'/>".$data['Company']['fb_link']."</a></li>";
                                 if(!empty($data['Company']['twitter_link']))
                                    echo "<li class='social-links'><a target='_blank' href='".$data['Company']['twitter_link']."'/>".$data['Company']['twitter_link']."</a></li>";
                                 if(!empty($data['Company']['youtube_link']))
                                    echo "<li class='social-links'><a target='_blank' href='".$data['Company']['youtube_link']."'/>".$data['Company']['youtube_link']."</a></li>";
                                 if(!empty($data['Company']['instagram']))
                                    echo "<li class='social-links'><a target='_blank' href='".$data['Company']['instagram']."'/>".$data['Company']['instagram']."</a></li>";
                                 if(!empty($data['Company']['pinterest']))
                                    echo "<li class='social-links'><a target='_blank' href='".$data['Company']['pinterest']."'/>".$data['Company']['pinterest']."</a></li>";
                                 if(!empty($data['Company']['tumblr']))
                                    echo "<li class='social-links'><a target='_blank' href='".$data['Company']['tumblr']."'/>".$data['Company']['tumblr']."</a></li>";
                                 if(!empty($data['Company']['linkedin']))
                                    echo "<li class='social-links'><a target='_blank' href='".$data['Company']['linkedin']."'/>".$data['Company']['linkedin']."</a></li>";
                               
                                 ?>
                                 </ul>
                           </div>
                        </div>
                        <!--/span-->
                        <div class="col-sm-12">
                              <label class="control-label" for="firstName"><?php echo __('Website/Blog'); ?>:</label>
                           <ul>
                              <?php 
                                   if(!empty($data['Company']['web_site']))
                                    echo "<li class='social-links'><a target='_blank' href='".$data['Company']['web_site']."'/>".$data['Company']['web_site']."</a></li>";
                                 if(!empty($data['Company']['blog_url']))
                                    echo "<li class='social-links'><a target='_blank' href='".$data['Company']['blog_url']."'/>".$data['Company']['blog_url']."</a></li>";

                              ?>
                           </ul>
                        </div> 
                     </div> 

                     <div class="row">
                        <div class="col-sm-6">
                           <div class="control-group">
                              <label class="control-label" for="firstName"><?php echo __('Logo'); ?>:</label>
                                 <div class="control"><?php 
 

                                 echo $this->Html->image(FRONTURL.'files/company/logo/'.$data['Company']['logo_path'].'/'.$data['Company']['logo'], array('class' => 'img-logo','height'=>"100px",'width'=>"100px"));?></div>
                           </div>
                        </div> 
                        <div class="col-sm-6">
                           <div class="control-group">
                              <label class="control-label" for="lastName">Banner image</label>
                              <div  class="control">
                               <?php  
                                 echo $this->Html->image(FRONTURL.'files/company/banner/'.$data['Company']['banner_path'].'/'.$data['Company']['banner_image'], array('class' => 'img-logo','height'=>"1280px",'width'=>"350px"));?>
                                 </div>
                           </div>
                        </div> 
                     </div> 
                   <div class="newsroom_approval col-sm-12">
                    <?php 

                     if($data[$model]['status']==0){
                         $actions = ' ' . $this->Html->link(__("Approve"), array(
                                'controller' => $controller,
                                'action' =>"active_company",
                                $data[$model]['id'],  
                               'view',
                                    ), array('class' => 'btn btn-xs btn-default'));

                         $actions .= ' ' . $this->Html->link(__("Disapprove"), array(
                                'controller' => $controller,
                                'action' =>"inactive_company",
                                $data[$model]['id'],  
                               'view',
                                    ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return reasonMessage(this.href);'));
                        }else{
                           $s_action=($data[$model]['status']==2)?"active_company":"suspend_company";
                            $onclick='';
                             if($s_action=='suspend_company'){
                              $onclick='return reasonMessage(this.href);';
                             }
                           $actions = ' ' . $this->Html->link(__(ucfirst(str_replace("_company","",$s_action))), array(
                           'controller' => $controller,
                           'action' => $s_action,
                           $data[$model]['id'],
                           'view',
                                   ), array('class' => 'btn btn-xs btn-default','onclick'=>$onclick));
                           }

                    if($data['Transaction']['status']=='Success'&&$data['Transaction']['newsroom_amount']>'0')       
                     echo $actions;
                    ?>
                 </div>
                </div>
                <div class="col-sm-3">
                    <div class="box box-primary">
                      <div class="box-header with-border">
                        <h3 class="box-title">Transaction</h3> 
                        <div class="">
                          <?php 
                            $paymentStatus="<span class='label label-danger'>Failed</span>";
                              if($data['Transaction']['status']=='Success'&&$data['Transaction']['newsroom_amount']>'0'){
                              $paymentStatus="<span class='label label-success'>Success</span>";   
                               
                              }else{
                                $count= $this->Custom->checkNewsroomIncart($data['StaffUser'][0]['id']);
                                if($count==1){
                                  $paymentStatus="<span class='label label-warning'>Pending</span>"; 
                                }
                              } 
                            ?>
                        </div>
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body">
                        <ul id="newsroomdetails" class="products-list product-list-in-box">
                        <li class="totals item"><div class="product-info"><div class="product-title">Payment status : <span class="pull-right"><?php echo $paymentStatus;?></span></div></div></li>
                        <?php  if($data['Transaction']['status']=='Success'&&$data['Transaction']['newsroom_amount']>'0'){?>
                        <li class="totals item"><div class="product-info"><div class="product-title">Transaction id : <span class="pull-right"><?php echo $data['Transaction']['tx_id'];?></span></div></div></li>
                          <li class="totals item"><div class="product-info"><div class="product-title">Newsroom amount : <span class="pull-right">$<?php echo $data['Transaction']['newsroom_amount'];?></span></div></div></li>
                         <?php } ?> 
                        </ul>
                      </div> 
                  </div>
                </div>
             </div>
         </div>
      </div>
   </div>
</div>

<style type="text/css">
#newsroomdetails .product-info {
  margin-left: 0px;
}
</style>