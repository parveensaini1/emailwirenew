  <div class="row">
        <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
  </div>
 <?php  if(!empty($plan_list)){?>
 <section class="content-section">
    <div class="box">
      <div class="box-header with-border"> 
        <div class="row">
            <div class="col-sm-4">
              <?php echo $userDetail['StaffUser']['email']; ?>
            </div>
            <div class="col-sm-8 text-right">
              <?php echo ucfirst($userDetail['StaffUser']['first_name']).' '.$userDetail['StaffUser']['last_name']; ?>     
            </div>
          </div>
        </div>
    </div>
  </section>
  <div class="row">
      <div class="col-sm-12">
         <div class="box">
           <!-- /.box-header -->
            <div class="box-body">
              <?php 
              echo "<div class='row'><div class='col-sm-7'><span class='box-title'>Plans</span></div><div class='col-sm-2'><span class='box-title'>Remaining</span></div><div class='col-sm-3'> </div></div><hr/>";
              foreach ($plan_list as $planHeader => $plans){
                echo "<h3 class='box-title'>$planHeader</h3>";

                echo "<div class='product-list-in-box'>";
                  foreach ($plans as $key => $plan) {
                    echo "<div class='product-title row'>$plan</div>";
                  }
                echo "</div>";
              } ?>
            </div>
          </div>
        </div>
  </div>
<?php }else{   echo $this->Custom->getRecordNotFoundMsg(); } ?>

