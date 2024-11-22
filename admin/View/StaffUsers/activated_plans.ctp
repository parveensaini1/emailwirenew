 <section class="content-section">
    <div class="card">
      <div class="card-header with-border"> 
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
<?php  
  if(!empty($plan_list)){?>
   <div class="row">
      <div class="col-sm-12">
         <div class="card">
           <!-- /.card-header -->
            <div class="card-body">
              <?php
              echo "<div class='row'><div class='col-sm-7'><span class='card-title'>Plans</span></div><div class='col-sm-2'><span class='card-title'>Remaining</span></div><div class='col-sm-3'> </div></div><hr/>";
              foreach ($plan_list as $planHeader => $plans){
                echo "<h3 class='card-title'>$planHeader</h3>";
                echo "<ul class='product-list-in-card'>";
                  foreach ($plans as $key => $plan) {
                    echo "<li class='plan-title item mt-2'><div class='product-title row'>$plan</div></li>";
                  }
                echo "</ul>";
              } ?>
            </div>
          </div>
        </div>
    </div>



<?php }else{?>
     <div class="alert alert-dismissable label-default fade in">
          <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
          No record found.
      </div> 
<?php } ?>
<style type="text/css">
  #category_page article h4 a, .card-title {
    font-size: 22px;
    font-weight: bold;
    line-height: 35px;
    color: #000;
    clear: both;
}
</style>

