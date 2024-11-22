<div class="row">
   <div class="col-lg-12">
      <!--<div class="col-lg-12"><div class="ew-title full"><h1>Thank You!<h1></div></div>-->
      <div class="row">
        <div class="col-sm-2"></div>  
          <div class="col-sm-8">
                <?php 
                if(isset($transactionData['Transaction']['newsroom_amount']) && $transactionData['Transaction']['newsroom_amount'] > '0.00'){
                    echo '<section class="pr-not-found-message" style="min-height:auto; margin:10px 0;"><div class="alert alert-success" style="margin:0px;">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <i class="icon fa fa-warning" aria-hidden="true"></i>
                        Newsroom created successfully, <a href="'.SITEURL.'users/add-press-release">click here to submit PR</a>.
                    </div></section>';
                }elseif(isset($newsroomcount) && $newsroomcount == 0){
                    echo '<section class="pr-not-found-message" style="min-height:auto; margin:10px 0;"><div class="alert alert-success" style="margin:0px;">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <i class="icon fa fa-warning" aria-hidden="true"></i>
                        You have purchased PR plan successfully. Now you need to create newsroom in order to submit PR. <a href="'.SITEURL.'users/create-newsroom">Click here to create newsroom</a>.
                    </div></section>';
                }elseif(isset($newsroomcount) && $newsroomcount > 0){
                    echo '<section class="pr-not-found-message" style="min-height:auto; margin:10px 0;"><div class="alert alert-success" style="margin:0px;">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <i class="icon fa fa-warning" aria-hidden="true"></i>
                        You have purchased PR plan successfully, <a href="'.SITEURL.'users/add-press-release">click here to submit PR</a>.
                    </div></section>';
                }
                ?>
                <div class="card">
                    <div class="card-body row mb-4" style="clear: both;">
                    <div class="col-sm-4"><h3>Transaction Summary</h3></div>
                    <div class="col-sm-8 text-right">
                      <?php
                      echo $this->Html->link(__('Download invoice'), array('controller' =>$controller, 'action' =>'downloadinvoice',$transactionData['Transaction']['id'],rand(0,1000)), array('class' => 'btn btn-xs btn-success'));
                      ?>
                    </div>
                    <?php
                        $html = $this->Custom->getPlanInvoiceHtml($transactionData);
                        echo $html;
                    ?>
                    </div>
                </div>
            </div>
        <div class="col-sm-2"></div>  
      </div>
   </div>
</div>