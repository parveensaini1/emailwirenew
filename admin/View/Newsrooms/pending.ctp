 <?php echo $this->element('search'); ?>
<div class="row">
<div class="col-lg-12 col-12">
<?php   $totalCount=$this->Paginator->params(); echo "Total Records:".$totalCount["count"];?></div>
    <div class="col-lg-12 col-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                
                <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                 __('S/N'), 
                                __("logo"),
                                __("Company name"),
                                __("Contact name"), 
                                __("Payment status"),
                                __("Status"),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            
                            if (count($data_array) > 0) {
                                foreach ($data_array AS $index =>$data) { 
                                    $actions =""; 
                                    $paymentStatus="<span class='label label-danger'>Failed</span>";
                                    
                                    $actions .= ' ' . $this->Html->link(__("Edit"), array(
                                                'controller' => $controller,
                                                'action' =>"edit_newsroom",
                                                $data[$model]['id'],'pending'
                                                    ), array('class' => 'btn btn-xs btn-danger'));
                                                    
                                    if($data['Transaction']['status']=='Success' && $data['Transaction']['newsroom_amount']>'0'){
                                        $paymentStatus="<span class='label label-success'>Success</span>";   
                                        $actions .= ' ' . $this->Html->link(__("Approve"), array(
                                                'controller' => $controller,
                                                'action' =>"active_company",
                                                $data[$model]['id'],  
                                               'pending',
                                                    ), array('class' => 'btn btn-xs btn-default'));
                                                    
                                        $actions .= ' ' . $this->Html->link(__("Disapprove"), array(
                                                'controller' => $controller,
                                                'action' =>"inactive_company",
                                                $data[$model]['id'],  
                                               'pending',
                                                    ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return reasonMessage(this.href);'));
                                                    
                                        
                                                    
                                    }else{
                                        if(!empty($data['StaffUser'])){
                                        $count= $this->Custom->checkNewsroomIncart($data['StaffUser']['id']);
                                          if($count==1){
                                            $paymentStatus="<span class='label label-warning'>Pending</span>"; 
                                          }
                                      }
                                    } 

                                     

                                     
                                     
                                    $logo="<img src='".FRONTURL.'files/company/logo/'.$data[$model]['logo_path'].'/'.$data[$model]['logo']."' width='50px' height='50px' />";
                                    
                                    $name = $this->Html->link(__($data[$model]['name']), array(
                                        'controller' => $controller,
                                        'action' =>"view",
                                        $data[$model]['slug'],'pending'  ), array('class' => 'link'));

                                    $rows[] = array(
                                        __($index+1),
                                        $logo,
                                         __($name),
                                        __($data[$model]['contact_name']), 
                                        $paymentStatus,
                                        $this->Custom->getUserStatus($data[$model]['status']),
                                        $actions,
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                            <td align="center" colspan="6">
                                <div class="alert alert-dismissable label-default fade in">
                                    <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                    No record found.
                                </div> 
                            </td>
                            <?php
                        }
                        ?>  
                        </tbody>
                    </table>
                    <div class="row">
                    <?php echo $this->element('pagination'); ?>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>
