<?php echo $this->element('search'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">  
                <div class="dataTable_wrapper">
				<div class="table-responsive">	
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                 __('S/N'), 
                                 __('Transaction id'),
                                __("Name"), 
                                __("Subtotal"), 
                                __("Total"),
                                __("Status"),
                                __("Paymant date"),
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

                                    $actions = ' ' . $this->Html->link(__('View'), array('controller' => $controller, 'action' => 'view', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));

                                    $name=$data['StaffUser']['first_name'].' '.$data['StaffUser']['last_name'];
                                    $rows[] = array(
                                        __($index+1),
                                        __($data[$model]['tx_id']),
                                        __($name),
                                        __($data[$model]['subtotal']),
                                        __($data[$model]['total']),
                                        __($data[$model]['status']),
                                        __(date('d F Y',strtotime($data[$model]['paymant_date']))),  
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
                </div>
                </div>

                <div class="row">
                    <?php  echo $this->element('pagination'); ?>
                </div> 

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>
