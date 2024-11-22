<div class="row">
  <div class="col-sm-12">
    <?php
    if(!empty($data_array)){
    foreach ($data_array as $key => $data) {?>
     <div class="card">
       <section class="content-section">
        <div class="card">
          <div class="card-header with-border"> 
            <div class="row">
                <div class="col-sm-4">
                  Transaction id: <?php echo $data['Transaction']['tx_id']; ?>
                </div>
                <div class="col-sm-8 text-right">
                 Total Amount:  <?php echo $currencySymbol. $data['Transaction']['total']; ?>
                </div>
              </div>
            </div>
        </div>
      </section>

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
    <?php } // end for loop
        }else{?>
      <div class="alert alert-dismissable label-default fade in">
          <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
          No record found.
      </div>

     <?php } ?>
  </div> 
  

</div>