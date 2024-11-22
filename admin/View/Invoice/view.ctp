
 <section class="content-section">
    <div class="box">
      <div class="box-header with-border"> 
        <div class="row">
            <div class="col-sm-4">
              <?php echo $data['StaffUser']['email']; ?>
            </div>
            <div class="col-sm-8 text-right">
              <?php echo ucfirst($data['StaffUser']['first_name']).' '.$data['StaffUser']['last_name']; ?>     
            </div>
          </div>
        </div>
    </div>
  </section>

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
                    <div class="row">
                      <div class="col-sm-4"><h3>Transaction Summary</h3></div>
                      <div class="col-sm-8 text-right"><?php echo $this->Html->link(__('Send invoice'), array('controller' => $controller, 'action' =>'sendinvoice',$data[$model]['id']), array('class' => 'btn btn-xs btn-success')); ?></div>
                    </div>
                  <div>
                  <?php
                    $promo_code='';
                    $transdata=$data['Transaction'];
                    $total_amount=(isset($transdata['total'])&&$transdata['total']>0)?$transdata['total']:"0.00"; 
                    $subtotal=(isset($transdata['subtotal'])&&$transdata['subtotal']>0)?$transdata['subtotal']:"0.00";
                    $discount=(isset($transdata['discount'])&&$transdata['discount']>0)?$transdata['discount']:"0.00";
                    $tax=(isset($transdata['tax'])&&$transdata['tax']>0)?$transdata['tax']:"0.00";
                    $paymant_date=(isset($data['Transaction']['paymant_date']))?$data['Transaction']['paymant_date']:"00/00/00";
                  ?>
                <div class="box-body" style="">
                  <div class="row">
                    <div class="col-sm-8"></div>
                    <div class="col-sm-2 text-right">Transaction ID : </div>
                    <div class="col-sm-2 text-left"> <strong><?php echo $data['Transaction']['tx_id'];?></strong>
                    </div>
                   </div>
                <?php  if($paymant_date != '00/00/00'){?>
                   <div class="row" style="">
                      <div class="col-sm-8"></div>
                      <div class="col-sm-2 text-right">Payment Date : </div>
                      <div class="col-sm-2 text-left"> <strong><?php echo date('F d, Y',strtotime($paymant_date));?></strong>
                      </div>
                 </div> 
                <?php } ?>
                </div>
                
                <div class="box-body" style="">
                     <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <ul class="item-list">
                                    <li><strong>Bill To:</strong></li>
                                    <li>Name: <?php echo isset($data['StaffUser']['first_name']) && isset($data['StaffUser']['last_name']) ? ucfirst($data['StaffUser']['first_name']).' '.$data['StaffUser']['last_name'] : 'N/A'; ?></li>
                                    <li>Email: <?php echo isset($data['StaffUser']['email']) ? $data['StaffUser']['email'] : 'N/A'; ?></li>
                                    <li>Company: <?php echo isset($data['StaffUser']['Company'][0]['name']) ? $data['StaffUser']['Company'][0]['name'] : 'N/A'; ?></li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="item-list">    
                                    <li class="totals item"><div class="product-info"><div class="product-title pull-right">Subtotal : <span class="pull-right"><?php echo $currencySymbol.$subtotal;?></span></div></div></li>
                                    <li class="totals item"><div class="product-info"><div class="product-title pull-right">Discount : <span class="pull-right"><?php echo $currencySymbol.$discount;?></span></div></div></li>
                                    <li class="totals item"><div class="product-info"><div class="product-title pull-right">Total : <span class="pull-right"><?php echo $currencySymbol.$total_amount;?></span></div></div></li>
                              </ul>
                          </div>
                        </div>
                     </div>
                </div>
                 
               </div>
               <div class="row">
                  <div class="col-sm-12">
                     <div class="box">
                       <!-- /.box-header -->
                        <div class="box-body">
						  <div class="table-responsive">	
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
                                    $name  ="";
                                    $type  ="";
                                    $plan_details=$this->Custom->fetchPlanData($plan['plan_id']);
                                    if($plan_details){
                                    $name  =  $plan_details['PlanCategory']['name'];
                                    $type  =  $plan_details['Plan']['plan_type'];
                                    }
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
                          
                          <?php  if($data['Transaction']['subscr_id']!= NULL){
                             $all_data= $this->Custom->all_transaction($data['Transaction']['subscr_id'],$data['Transaction']['id']);?>
                             <h2>Recurring Transaction Summary</h2>
							 <div class="table-responsive">	
                           <table class="table table-bordered">
                              <thead>
                               <?php 
                                 $tableHeaders = $this->Html->tableHeaders(array(
                                    __('S.No.'), 
                                   __("Transaction id"),
                                   __("Transaction type"),
                                   __("Amount"),
                                   __("Date"),
                                 ));
                               echo $tableHeaders;
                               ?>
                              </thead>
                              <tbody>
                              <?php 
                                $rows = array();
                                $i = 0; 
                                foreach ($all_data as $d) { 
                                    $i++;
                                    $tx_id  =(!empty($d['Transaction']['tx_id']))?$d['Transaction']['tx_id']:"-";
                                    $total  =$d['Transaction']['currency'].''.(!empty($d['Transaction']['total']))?$d['Transaction']['total']:"0.00";

                                    $date  =date('d F Y',strtotime($d['Transaction']['paymant_date']));
                                    $txnType=$this->Custom->getTransactionType($d['Transaction']['txn_type']);
                                    $rows[] = array(
                                              __($i),
                                              __($tx_id),
                                              __($txnType),
                                              __($currencySymbol.$total),
                                              __($date),
                                          );
                                     }
                                  echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                                 if(empty($rows)){
                                    echo '<tr><td align="center" colspan="5">
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
                        <?php } ?>
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
$paymant_date=(isset($data['Transaction']['paymant_date']))?$data['Transaction']['paymant_date']:"00/00/00";
?>


<style type="text/css">
.item-list li{  list-style: none; clear: both; }
</style>
<div id="main-content" class="row">
    <div id="content" class="col-lg-12 content">
        <div class="card card-default"> 
            <div class="card-body">
               <div class="box">
                 <div class="box-header with-border">
                    <div class="row">
                      <div class="col-sm-4"><h3>Transaction Summary</h3></div>
                      <div class="col-sm-8 text-right"><?php echo $this->Html->link(__('Send invoice'), array('controller' => $controller, 'action' =>'sendinvoice',$data[$model]['id']), array('class' => 'btn btn-xs btn-success')); ?></div>
                    </div>
                  <div>
                 <div class="box-body" style="">
                  <div class="row">
                    <div class="col-sm-8"></div>
                    <div class="col-sm-2 text-right">Transaction ID : </div>
                    <div class="col-sm-2 text-left"> <strong><?php echo $data['Transaction']['tx_id'];?></strong>
                    </div>
                   </div>
                <?php  if($paymant_date != '00/00/00'){?>
                   <div class="row" style="">
                      <div class="col-sm-8"></div>
                      <div class="col-sm-2 text-right">Payment Date : </div>
                      <div class="col-sm-2 text-left"> <strong><?php echo date('F d, Y',strtotime($paymant_date));?></strong>
                      </div>
                 </div> 
                <?php } ?>
                </div>

                <div class="box-body" style="">
                     <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <ul class="item-list">
                                    <li><strong>Bill To:</strong></li>
                                    <li>Name: <?php echo ucfirst($data['StaffUser']['first_name']).' '.$data['StaffUser']['last_name']; ?></li>
                                    <li>Email: <?php echo $data['StaffUser']['email']; ?></li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="item-list">    
                                    <li class="totals item"><div class="product-info"><div class="product-title pull-right">Subtotal : <span class="pull-right"><?php echo $currencySymbol.$subtotal;?></span></div></div></li>
                                    <li class="totals item"><div class="product-info"><div class="product-title pull-right">Discount : <span class="pull-right"><?php echo $currencySymbol.$discount;?></span></div></div></li>
                                    <li class="totals item"><div class="product-info"><div class="product-title pull-right">Total : <span class="pull-right"><?php echo $currencySymbol.$total_amount;?></span></div></div></li>
                              </ul>
                          </div>
                        </div>
                     </div>
                </div>
                 
               </div>
               <div class="row">
                  <div class="col-sm-12">
                     <div class="box">
                        <div class="box-body">
                          <div class="table-responsive">    
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
                                $i = 0;
                                $i++;
                                     if($transdata['extra_words']>0){
                                         echo "<tr><td>".$i."</td><td>Extra Word charges</td><td>".$transdata['extra_words']."</td><td>".$currencySymbol.$transdata['word_amount']."</td></tr>";
                                        $i++;
                                     }
                                      if($transdata['extra_category']>0){
                                         echo "<tr><td>".$i."</td><td>Extra Category charges</td><td>".$transdata['extra_category']."</td><td>".$currencySymbol.$transdata['category_amount']."</td></tr>";
                                        $i++;
                                      }
                                      if($transdata['extra_msa']>0){
                                         echo "<tr><td>".$i."</td><td>Extra MSA charges</td><td>".$transdata['extra_msa']."</td><td>".$currencySymbol.$transdata['msa_amount']."</td></tr>";
                                        $i++;
                                      }
                                      if($transdata['extra_state']>0){
                                         echo "<tr><td>".$i."</td><td>Extra State charges</td><td>".$transdata['extra_state']."</td><td>".$currencySymbol.$transdata['state_amount']."</td></tr>";
                                        $i++;
                                      }
                                      if(!empty($transdata['translate_charges'])){
                                         echo "<tr><td>".$i."</td><td>Content translate charges</td><td>".$transdata['translate_charges']."</td><td>".$currencySymbol.$transdata['translation_amount']."</td></tr>";
                                        $i++;
                                      }
                                ?>
                              <?php 
                                $rows = array();
                                if(!empty(unserialize($transdata['distribution_ids']))) {
                                    foreach(unserialize($transdata['distribution_ids']) AS $index =>$value) {
                                    $name  ="";
                                    $type  ="";
                                    $name  =  $value['name'];
                                    $type  =  '1';
                                    $rows[] = array(
                                              __($i),
                                              __($name),
                                              __($type),
                                              __($value['price']),
                                              
                                          );
                                    $i++;
                                    }
                                  }  
                                    // if($data['Transaction']['newsroom_amount']>0) {
                                    //   $rows[count($rows)]=array($i+1,'Newsroom amount','-',$currencySymbol.$data['Transaction']['newsroom_amount']);
                                    // } 
                                    
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
</div>



<?php }else{?>
     <div class="alert alert-dismissable label-default fade in">
          <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
          No record found.
      </div> 
<?php } ?>

