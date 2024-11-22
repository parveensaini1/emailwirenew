<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title;?></div></div>
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-body">  
                <?php if (count($userPlans) > 0) {?>
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __("S/N"),
                                __("Transaction id"),
                                __("Name"),
                                __("Price"), 
                                __("Status"),                                
                                __('Payment Date'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            $cSymbole=Configure::read('Site.currency');
                            if (count($userPlans) > 0) {
                                $counter=1;
                                foreach ($userPlans AS $data) {
                                    $tx_id = ' ' . $this->Html->link($data['transactions']['tx_id'], array(
                                            'controller' => 'users',
                                            'action' =>"transaction_view",
                                            $data['tp']['transaction_id'],  
                                                ), array('class' => 'link'));

                                    $status='<span class="badge bg-success">'.$data['transactions']['payment_status'].'</span>';
                                    if($data['transactions']['payment_status']!='Success'){
                                        $status='<span class="badge bg-danger">'.$data['transactions']['payment_status'].'</span>';
                                    } 

                                     $rows[] = array(
                                        __($counter),
                                        __($tx_id),
                                        __(ucfirst($data['plancat']['cat_name'])),
                                        __($cSymbole.$data['tp']['price']),
                                        $status,
                                        __(date('F d, Y',strtotime($data['transactions']['paymant_date']))),
                                    );

                                $counter++;
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="3">No result found!</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                   <?php echo $this->element('custom_pagination');?>
               </div>
           <?php }else{
                echo $this->Custom->getRecordNotFoundMsg();
           } ?>
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
 