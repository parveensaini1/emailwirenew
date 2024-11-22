<?php 
echo $this->Html->css(array('/plugins/owlcarousel/owl.carousel.min'));
echo $this->Html->script(array('/plugins/owlcarousel/owl.carousel.min')); 
?> 

<?php 
    $actions ="";
     $flag=0; 
      $user_id  = $data['PressRelease']['staff_user_id']; 
    $PressReleasePaymentStatus="<span class='badge bg-default'>No payment</span>";
    if($data[$model]['is_paid']==1){
        if(!empty($data['TransactionPressRelease'])&&$data['TransactionPressRelease']['subtotal']>0){
            $flag=1;
            $PressReleasePaymentStatus="<span class='badge bg-success'>Success</span>";
        }else{
            $PressReleasePaymentStatus="<span class='badge bg-danger'>Failed</span>";
        }

        
        if($flag=='0'){
            $checkInCart=$this->Custom->checkPrIncart($user_id,$data[$model]['id']); 
            if($checkInCart>0){
                $PressReleasePaymentStatus="<span class='badge bg-warning'>Pending</span>";
            } 
        }
    }else{
           $flag=1; // For non payment PR
    } 
    if($flag==1){
    $actions=' ' . $this->Html->link(__("Front view"), array(
                'controller' => $controller,
                'action' =>"frontview",
                $data[$model]['id'],   
                    ), array('class' => 'btn btn-xs btn-info'));
      ;

      $disApplable="Deactive";
     
      if($role_id==1&&($data[$model]['status']==0||$data[$model]['status']==4)){
            if($data['Company']['payment_status']==1&&$data['Company']['status']==1){
                 $actions .= ' ' . $this->Html->link(__("Approve"), array(
                        'controller' => $controller,
                        'action' =>"active_pr",
                        $data[$model]['id'],  
                       'pending',
                            ), array('class' => 'btn btn-xs btn-default'));
            }
         $disApplable="Disapprove";    
        }

        if($role_id==1&&$data[$model]['status']!=1&&$data[$model]['status']!=4){
          $actions .= ' ' . $this->Html->link(__($disApplable), array(
              'controller' => $controller,
              'action' =>"inactive_pr",
              $data[$model]['id'],  
             'disapproved',
                  ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return reasonMessage(this.href);'));
          }elseif($data[$model]['status']!=1&&$data[$model]['status']!=4){
            $actions .= ' ' . $this->Html->link(__($disApplable), array(
              'controller' => $controller,
              'action' =>"inactive_pr",
              $data[$model]['id'],  
             'disapproved',
                  ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return reasonMessage(this.href);'));
          }

      }?>
      <section class="<?php echo $lang=$this->Post->classAccordingToLanguage($data[$model]['language']);  ?> content-section">
        <div class="card">
          <div class="card-header with-border"> 
            <div class="row">
                <div class="col-sm-3">
                  PressRelease payment status: <?php echo $PressReleasePaymentStatus; ?>
                </div>
                <div class="col-sm-3">
                  <?php $newsroomStatus=$this->Custom->getUserStatus($data['Company']['status']); ?>
                  PressRelease Newsroom status: <?php echo $newsroomStatus; ?>
                </div>
                
                <div class="col-sm-6 text-right">
                  <?php echo $actions;?>       
                </div>
              </div>
            </div>
          </div>
        </section>

  <?php
     if(isset($cartdata) && $cartdata['totals']['total'] != "0"&&empty($transdata)){
        echo $this->element('pr_cart_details');
        
     }elseif(!empty($transdata)){
        echo $this->element('prtransaction');
     }
  ?>
  <section class="content-section">
   <div class="card">
     <div class="card-header with-border"> 
       <div class="row">
            <div class="<?php echo ($lang !="arabicrtl")?'col-sm-8':"col-sm-4"; ?>"> 
               <?php $url=SITEURL.'Invoices/user_plan_invoice/'.$data['Plan']['id']."/".$data[$model]['staff_user_id']; ?>
            <?php echo ($lang !="arabicrtl")?'<h3 class="card-title">'.$data[$model]['title']."</h3>":"Selected Plan:<strong><a href='$url' target='_blank' >".$data['Plan']['PlanCategory']['name']."</a></strong>"; ?>
                
            </div>
            <div class="<?php echo ($lang !="arabicrtl")?'col-sm-4':"col-sm-8"; ?> text-right">
               <?php echo ($lang=="arabicrtl")?'<h3 class="float-right card-title">'.$data[$model]['title']."</h3>":"Selected Plan:<strong><a href='$url' target='_blank' >".$data['Plan']['PlanCategory']['name']."</a></strong>"; ?>
            </div>
       </div> 
     </div>
     <div class="card-body <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']);  ?>" style="">
      <h4>Summery </h4>
       <?php echo $data[$model]['summary']; ?>
     </div>
     <div class="row primgslider">
                   <div class="col-sm-6">
                     <?php 
                  if(!empty($data['PressImage'])){
                    echo "<div id='primgslider' class='owl-carousel owl-theme'>";
                      foreach ($data['PressImage'] AS $index =>$image) { 
                      $imgurl=SITEFRONTURL.'files/company/press_image/'.$image['image_path'].'/'.$image['image_name'];   
                      $dsc=(!empty($image['describe_image']))?$image['describe_image']:"";
                      $img='<img  alt="'.$image['image_text'].'"  src="'.$imgurl.'">';
                      echo "<div class='item'>$img <p>".$dsc."</p></div>";
                    
                      }
                      echo "</div>";
                       
                   }
                 ?>
                   </div>
                  
                 </div>
     <div class="card-body <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']);  ?>" style=""> 
      <?php   $sourceName=$this->Post->summaryPrefix($data[$model]['source_msa'],$data[$model]['source_state'],$data[$model]['source_country'],$data[$model]['is_source_manually']);
              echo $sourceName.$data[$model]['body']; ?>
     </div>

     <div class="card-body <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']);  ?>" style="">
         <div class="row">
            <!-- <div class="col-sm-2 text-center">
               <h4>Stock ticker </h4>
             <?php // echo (!empty($data[$model]['stock_ticker']))?$data[$model]['stock_ticker']:"<strong class='text-center blank'>-</strong>"; ?>
            </div> -->
            <div class="col-sm-2 text-center">
               <h4>Company</h4>
               <?php echo (!empty($data['Company']['name']))?ucfirst($data['Company']['name']):"<strong class='text-center blank'>-</strong>"; ?>
            </div>
            <div class="col-sm-3 text-center">
               <h4>Release date</h4>
                <?php echo date("d F, Y",strtotime($data[$model]['release_date'])); ?>
            </div>
             <div class="col-sm-3 text-center">
               <h4>Is translate the content</h4>
                <?php echo ($data[$model]['translated_page']=="1")?"<span class='text-success'>Yes</span>":"<span class='text-danger'>No</span>"; ?>
            </div>
            <!-- <div class="col-sm-4 text-center">
               <h4>Iframe url</h4>
               <?php // echo (!empty($data[$model]['iframe_url']))? "<a target='_blank' href='".$data[$model]['iframe_url']."'>".$data[$model]['iframe_url']."</a>" :"<strong class='text-center blank'>-</strong>"; ?> 
            </div> -->
         </div>
     </div>
     <div class="card-body <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']);  ?>">
          <div class="row">
            <div class="col-sm-2 text-center">
               <h4>Contact name</h4>
               <?php echo (!empty($data[$model]['contact_name']))?ucfirst($data[$model]['contact_name']):"<strong class='text-center blank'>-</strong>"; ?>
            </div>

            <div class="col-sm-3 text-center">
               <h4>Contact email</h4>
             <?php echo (!empty($data[$model]['email']))?$data[$model]['email']:"<strong class='text-center blank'>-</strong>"; ?>
            </div>
            <div class="col-sm-3 text-center">
               <h4>Contact phone</h4>
                <?php echo (!empty($data[$model]['phone']))?$data[$model]['phone']:"<strong class='text-center blank'>-</strong>"; ?>
            </div>
            <div class="col-sm-4 text-center">
               <h4>Zip code</h4>
                <?php echo (!empty($data[$model]['zip_code']))?$data[$model]['zip_code']:"<strong class='text-center blank'>-</strong>"; ?>
            </div> 
         </div>
     </div>  
   </div>
   <div class="row">
      <div class="col-sm-6">
         <div class="card">
            <div class="card-header with-border">
              <h3 class="card-title">Press seo list</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               <table class="table table-bordered">
                  <thead>
                   <?php 
                     $tableHeaders = $this->Html->tableHeaders(array(
                        __('#'), 
                       __("Name"),
                     ));
                   echo $tableHeaders;
                   ?>
                  </thead>
                  <tbody>
                  <?php 
                    $rows = array();
                     if(!empty($data['PressSeo'])){
                        foreach ($data['PressSeo'] AS $index =>$seo) { 
                        // $link="<a title='Click' href='".$seo['urls']."' target='_blank'> <i class='fa fa-link'></i></a>";
                        $rows[] = array(
                                  __($index+1),
                                  __($seo['keyword']),
                           
                              );
                        }
                        echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                     }else{
                        echo '<tr><td align="center" colspan="2">
                                <div class="alert alert-dismissable label-default fade in">
                                    <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                    No record found.
                                </div> 
                            </td></tr>';
                     }
                   ?>
                 </tbody>
               </table>
            </div> 
         </div>  
      </div> 
      <div class="col-sm-6">
         <div class="card">
            <div class="card-header">
              <h3 class="card-title">Press youtube list</h3>
            </div>
               <div class="card-body no-padding">
                 <table class="table table-bordered">
                  <thead>
                   <?php 
                     $tableHeaders = $this->Html->tableHeaders(array(
                        __('#'), 
                        __("Description"),
                        __("Action"),
                     ));
                   echo $tableHeaders;
                   ?>
                  </thead>
                  <tbody>
                  <?php 
                    $rows = array();
                     if(!empty($data['PressYoutube'])){
                        foreach ($data['PressYoutube'] AS $index =>$video) {
                        $link="<a title='Click' href='".$video['url']."' target='_blank'> <i class='fa fa-link'></i></a>"; 
                        $rows[] = array(
                                  __($index+1),
                                  __($video['description']),
                                  $link,
                              );
                        }
                        echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                     }else{
                        echo '<tr><td align="center" colspan="3">
                                <div class="alert alert-dismissable label-default fade in">
                                    <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                    No record found.
                                </div> 
                            </td></tr>';
                     } ?>
                    </tbody>
                  </table>
               </div> 
            </div>
         </div>
   </div>
   <div class="row">
        <div class="col-sm-6">
           <div class="card">
              <div class="card-header">
                <h3 class="card-title">Press images</h3>
               </div> 
                  <div class="card-body no-padding">
                   <table class="table table-bordered">
                    <thead>
                     <?php 
                       $tableHeaders = $this->Html->tableHeaders(array(
                          __('#'), 
                          __("Image"),
                          __("Description"),
                          __("Text"),
                       ));
                     echo $tableHeaders;
                     ?>
                    </thead>
                    <tbody>
                    <?php 
                      $rows = array();
                       if(!empty($data['PressImage'])){
                          foreach ($data['PressImage'] AS $index =>$image) { 
                          $imgurl=SITEFRONTURL.'files/company/press_image/'.$image['image_path'].'/'.$image['image_name'];   
                          $img='<a data-fancycard="gallery" href="'.$imgurl.'"><img style="height: 50px;" width="50px"  src="'.$imgurl.'"></a>';
                          $rows[] = array(
                                    __($index+1),
                                    $img,
                                    __($image['describe_image']),
                                    __($image['image_text']),
                                );
                          }
                          echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                       }else{
                          echo '<tr><td align="center" colspan="4">
                                  <div class="alert alert-dismissable label-default fade in">
                                      <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                      No record found.
                                  </div> 
                              </td></tr>';
                       }
                     ?>
                      </tbody>
                     </table>
                  </div> 
               
           </div>
        </div>
      <div class="col-sm-6">
         <div class="card">
            <div class="card-header with-border">
              <h3 class="card-title">Msa list</h3>
            </div> 
            <div class="card-body">
               <table class="table table-bordered">
                  <thead>
                   <?php 
                     $tableHeaders = $this->Html->tableHeaders(array(
                        __('#'), 
                       __("Name"),
                     ));
                   echo $tableHeaders;
                   ?>
                  </thead>
                  <tbody>
                  <?php 
                    $rows = array();
                     if(!empty($data['Msa'])){
                        foreach ($data['Msa'] AS $index =>$msa) { 
                        $rows[] = array(
                                  __($index+1),
                                  __($msa['name']),
                              );
                        }
                        echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                     }else{
                        echo '<tr><td align="center" colspan="2">
                                <div class="alert alert-dismissable label-default fade in">
                                    <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                    No record found.
                                </div> 
                            </td></tr>';
                     }
                   ?>
                 </tbody>
               </table>
            </div> 
         </div>  
      </div> 
     
   </div>
   <div class="row">
      <div class="col-sm-6">
         <div class="card">
            <div class="card-header">
              <h3 class="card-title">Category list</h3>
              </div>
               <div class="card-body no-padding">
               <div class="table-responsive">
                  <table class="table table-bordered">
                     <thead>
                     <?php 
                        $tableHeaders = $this->Html->tableHeaders(array(
                           __('#'), 
                           __("Name"),
                        ));
                     echo $tableHeaders;
                     ?>
                     </thead>
                     <tbody>
                     <?php 
                     $rows = array();
                        if(!empty($data['Category'])){
                           foreach ($data['Category'] AS $index =>$category) { 
                           $rows[] = array(
                                    __($index+1),
                                    __($category['name']),
                                 );
                           }
                           echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                        }else{
                           echo '<tr><td align="center" colspan="2">
                                 <div class="alert alert-dismissable label-default fade in">
                                       <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                       No record found.
                                 </div> 
                              </td></tr>';
                        }
                     ?>
                     </tbody>
                     </table>
                  </div> 
               </div> 
               
            </div>
      </div>
      <div class="col-sm-6">
         <div class="card">
            <div class="card-header">
              <h3 class="card-title">State list</h3>
              </div> 
               <div class="card-body no-padding">
			      <div class="table-responsive">
                 <table class="table table-bordered">
                  <thead>
                   <?php 
                     $tableHeaders = $this->Html->tableHeaders(array(
                        __('#'), 
                        __("Name"),
                     ));
                   echo $tableHeaders;
                   ?>
                  </thead>
                  <tbody>
                  <?php 
                    $rows = array();
                     if(!empty($data['State'])){
                        foreach ($data['State'] AS $index =>$state) { 
                        $rows[] = array(
                                  __($index+1),
                                  __($state['name']),
                              );
                        }
                        echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                     }else{
                        echo '<tr><td align="center" colspan="2">
                                <div class="alert alert-dismissable label-default fade in">
                                    <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                    No record found.
                                </div> 
                            </td></tr>';
                     }
                   ?>
                 </tbody>
               </table>
            
            </div> 
          </div> 
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-sm-6">
            <div class="card">
               <div class="card-header">
                 <h3 class="card-title">Additional Features</h3>
                 </div>
                  <div class="card-body no-padding">
				  <div class="table-responsive">
                     <table class="table table-bordered">
                        <thead>
                           <?php 
                           $tableHeaders = $this->Html->tableHeaders(
                              array(
                                 __('#'), 
                                 __("Name"),
                              ));
                           echo $tableHeaders;
                         ?>
                        </thead>
                        <tbody>
                        <?php 
                          $rows = array();
                           if(!empty($data['Distribution'])){
                              foreach ($data['Distribution'] AS $index =>$distribution) { 
                              $rows[] = array(
                                        __($index+1),
                                        __($distribution['name']),
                                    );
                              }
                              echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                           }else{
                              echo '<tr><td align="center" colspan="2">
                                      <div class="alert alert-dismissable label-default fade in">
                                          <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                          No record found.
                                      </div> 
                                  </td></tr>';
                           }
                         ?>
                       </tbody>
                     </table>
                  </div>
                 
            </div>
         </div>
     </div> 
   </div>
  </section>
<script type="text/javascript">
   $('[data-fancycard="gallery"]').fancycard({
        animationEffect: "zoom",
        protect: true,
});
</script>


<script type="text/javascript">
   $('#primgslider').owlCarousel({
        loop:false,
        margin:0,
        nav:true,
        items:1,
         autoplay:true,
      autoplayTimeout:2000,
       autoHeight:true,
      autoplayHoverPause:true
    });
 </script>

  <script type="text/javascript">


   $('#yvideoslider, #poadcastslider').owlCarousel({
        loop:false,
        margin:0,
        nav:true,
        items:1,
        autoplay:false,
        autoplayHoverPause:true
    });
 </script>