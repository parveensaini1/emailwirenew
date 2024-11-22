<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
    
       <div class="col-sm-12">
            <div class="col-sm-6"> 
                <?php  $totalCount=$this->Paginator->params();
                echo "Total Transactions: ".$totalCount["count"]; ?>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-lg-offset-6 col-md-offset-4 col-sm-offset-2 col-lg-6 col-md-8 col-sm-10 inputbox-with-span">
					<span>Filter with: </span>
                    <?php 
                      $options=['plannewsroom'=>'Plan invoices','pr'=>'Pr invoices'];
					  echo $this->Form->input('type', array('selected' =>$selected, 'id' => 'type', 'options' => $options, 'empty' => 'All Invoices', 'class' => 'form-control',"label"=>false,'onchange' =>"redirect(this.value);"));
                    ?>                   
					</div>
                   <?php echo $this->Form->end();?>
                </div>
            </div>
       </div> 
       <?php if (count($data_array) > 0) { ?>
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-body">  
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                       <thead>
                            <?php
                            if($selected!="pr"){
                                $tableHeaders = $this->Html->tableHeaders(array(
                                __("S/N"),
                                    __("Transaction id"),
                                __("Payment type"),
                                __("Price"), 
                                __("Status"),                                
                                __('Payment date'),
                                __('Action'),
                                    ), array(), array('class' => 'sorting'));

                            }else{
                                $tableHeaders = $this->Html->tableHeaders(array(
                                    __("S/N"),
                                    __("Transaction id"),
                                    __("PR title"),
                                    __("Price"), 
                                    __("Status"),                                
                                    __('Payment date'),
                                    __('Action'),
                                    ), array(), array('class' => 'sorting'));
                            }
                            echo $tableHeaders;
                            ?>
                        </thead> 
                         <tbody>
                            <?php
                            $rows = array(); 
                            
                                $counter=1;
                                foreach ($data_array AS $loop => $data){
                                $tx_id =' '.$this->Html->link($data['Invoice']['invoice_no']??$data['Invoice']['tx_id'], array('controller' => 'users','action' =>"invoice_view",$data['Invoice']['id'],), array('class' => 'link'));
                                $status='<span class="label label-success">'.$data['Invoice']['status'].'</span>';
                                if($data['Invoice']['status']!='Success'){
                                     $status='<span class="label label-danger">'.$data['Invoice']['status'].'</span>';
                                } 
                                    //$actions='';
                                     $actions = ' ' . $this->Html->link(__("View"), array(
                                            'controller' => 'users',
                                            'action' =>"invoice_view",$data['Invoice']['id'],
                                        ), array('class' => 'btn btn-xs btn-primary'));
                                        
                                    $payment_type="One time";
                                    if($data['Invoice']['txn_type']=='subscr_payment'){
                                        $actions=$this->Custom->cancelSubscriptionBtn($data['Invoice']['subscr_id'],$controller,'invoices');
                                        $payment_type="Recurring";
                                    }
                                    $rows[] = array(
                                        __($counter),
                                        __($tx_id), 
                                        _($payment_type),
                                        __($currencySymbol.$data['Invoice']['total']),
                                        $status,
                                        __(date($dateformate,strtotime($data['Invoice']['paymant_date']))),
                                        $actions
                                    );
                                 if($selected=="pr"){
                                    $rows[$loop]['2']=$this->Custom->getPRTitleForTransactionPressRelease($data['Invoice']['id']);
                                }
                                $counter++;
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            ?>
                        </tbody>

                        
                    </table>
                </div>
                <div class="row">
                    <?php echo $this->element('pagination'); ?>
                </div>
                <!-- /.table-responsive -->
            </div> 
        </div>
    </div>
    
<?php }else{   echo $this->Custom->getRecordNotFoundMsg(); } ?>
</div> 

<script type="text/javascript">
    function redirect(selected) {
      window.location.replace(SITEURL+"users/invoices/"+selected);
    }
</script>