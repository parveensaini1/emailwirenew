<?php  
if($data['Transaction']['transaction_type']=='plannewsroom'){?>

<style type="text/css">
.item-list li{  list-style: none; clear: both; }
</style>
<div id="main-content" class="row">
    <div id="content" class="col-lg-12 content">
        <div class="card card-default"> 
            <div class="card-body">
               <div class="box">
                 <div class="box-header with-border">
                  <div class="col-sm-5"><h3>Transaction Summary</h3></div>
                  <div class="col-sm-7">
                   <div class="box-tools pull-right">
                    <?php 
                    $actions='';
                    if($data['Transaction']['txn_type']=='subscr_payment'){
                        $actions=$this->Custom->cancelSubscriptionBtn($data['Transaction']['subscr_id'],$controller,'transaction_view','1');
                    }
                    echo $actions;
                    ?></div>
                   </div>
                 </div>
                 <?php 
                  //echo "<pre>";print_r($data);die;

                  $promo_code=''; 
                  $transdata=$data['Transaction'];
                  $total_amount=(isset($transdata['total'])&&$transdata['total']>0)?$transdata['total']:"0.00"; 
                  $subtotal=(isset($transdata['subtotal'])&&$transdata['subtotal']>0)?$transdata['subtotal']:"0.00";
                  $discount=(isset($transdata['discount'])&&$transdata['discount']>0)?$transdata['discount']:"0.00";
                  $tax=(isset($transdata['tax'])&&$transdata['tax']>0)?$transdata['tax']:"0.00";
                  ?>
                 <div class="box-body" style="">
                  <div class="col-sm-12 p-0 text-right">Transaction ID : <strong><?php echo $data['Transaction']['tx_id'];?></strong></div>
                 </div>
                 <div class="box-body" style="">
                     <div class="row">
                        <div class="col-sm-12">
                        <ul class="item-list">    
                          <li class="totals item"><div class="product-info"><div class="product-title pull-right">Subtotal : <span class="pull-right">$<?php echo $subtotal;?></span></div></div></li>
                        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Discount : <span class="pull-right">$<?php echo $discount;?></span></div></div></li>
                        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Tax : <span class="pull-right">$<?php echo $tax;?></span></div></div></li>
                        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Total : <span class="pull-right">$<?php echo $total_amount;?></span></div></div></li>
                      </ul>
                        </div>
                     </div>
                 </div>
                 
               </div>
               <div class="row">
                  <div class="col-sm-12">
                     <div class="card">
                       <!-- /.box-header -->
                        <div class="card-body">
                           <table class="table table-bordered">
                              <thead>
                               <?php 
                                 $tableHeaders = $this->Html->tableHeaders(array(
                                    __('S.No.'), 
                                   __("Plan"),
                                   __("Plan Type"),
                                   __("Amount"),
                                 ));
                               echo $tableHeaders;
                               ?>
                              </thead>
                              <tbody>
                              <?php 
                                $rows = array();
                                $i = 0;
                                if(!empty($data['TransactionPlan'])){
                                    foreach($data['TransactionPlan'] AS $index =>$plan) { 
                                    $i++;
                                    $plan_details   =   $this->Custom->fetchPlanData($plan['plan_id']);
                                    
                                    $name  =  $plan_details['PlanCategory']['name'];
                                    $type  =  $plan_details['Plan']['plan_type'];
                                    $rows[] = array(
                                              __($i),
                                              __($name),
                                              __($type),
                                              __($currencySymbol.$plan['plan_amount']),
                                              
                                          );
                                    }
                                  }  
                                    if($data['Transaction']['newsroom_amount']>0) {
                                      $rows[count($rows)]=array($i+1,'Newsroom amount','-',$currencySymbol.$data['Transaction']['newsroom_amount']);
                                    } 
                                    
                                    echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                                 if(empty($rows)){
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
                  
                
            </div>
        </div>
    </div> 
</div>
<?php }else if(isset($transdata['TransactionPressRelease'])&&!empty($transdata['TransactionPressRelease'])){ 
$promo_code=''; 
$transdata=$transdata['TransactionPressRelease'];
$total_amount=(isset($transdata['total'])&&$transdata['total']>0)?$transdata['total']:"0.00"; 
$subtotal=(isset($transdata['subtotal'])&&$transdata['subtotal']>0)?$transdata['subtotal']:"0.00";
$discount=(isset($transdata['discount'])&&$transdata['discount']>0)?$transdata['discount']:"0.00";
$tax=(isset($transdata['tax'])&&$transdata['tax']>0)?$transdata['tax']:"0.00";

?>
<!-- <div style="display: none;"id="buy-plan-error" class="text-danger col-lg-12 buy-plan-error-msg">Your cart is empty.</div> -->
<div class="card card-primary">
    <div class="card-header with-border">
      <h3 class="card-title">Transaction</h3>

      <!-- <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div> -->
    </div>
    <!-- /.box-header -->
    <div class="card-body">
      <ul id="cartplanlist" class="products-list product-list-in-box">
        <?php 
             if($transdata['extra_words']>0)
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Extra Word charges<span class='pull-right'>".$currencySymbol.$transdata['word_amount']."</span></div></div></li>";

              if($transdata['extra_category']>0)
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Extra Category charges<span class='pull-right'>".$currencySymbol.$transdata['category_amount']."</span></div></div></li>";

              if($transdata['extra_msa']>0)
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Extra MSA charges<span class='pull-right'>".$currencySymbol.$transdata['msa_amount']."</span></div></div></li>";

              if($transdata['extra_state']>0)
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Extra State charges<span class='pull-right'>".$currencySymbol.$transdata['state_amount']."</span></div></div></li>";
                 
              if(!empty($transdata['translate_charges']))
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Content translate charges<span class='pull-right'>".$currencySymbol.$transdata['translation_amount']."</span></div></div></li>";

            $features=unserialize($transdata['distribution_ids']);
            if(!empty($features)){
                foreach ($features as $index => $feature) { 
                 echo "<li  class='item'><div class='product-info'><div class='product-title'>".$feature['name']."<span class=' pull-right'>".$currencySymbol.$feature['price']."</span></div></div></li>";
                }
            }

        ?>

        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Subtotal : <span class="pull-right"><?php echo $currencySymbol.$subtotal;?></span></div></div></li>
        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Discount : <span class="pull-right"><?php echo $currencySymbol.$discount;?></span></div></div></li>
        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Tax : <span class="pull-right"><?php echo $currencySymbol.$tax;?></span></div></div></li>
        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Total : <span class="pull-right"><?php echo $currencySymbol.$total_amount;?></span></div></div></li>
      </ul>
    </div> 
</div>

<?php }else{?>
     <div class="alert alert-dismissable label-default fade in">
          <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
          No record found.
      </div> 
<?php } ?>