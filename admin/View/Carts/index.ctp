<?php echo $this->element('search'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">  
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                 __('S/N'), 
                                 _('Name'),
                                 _('Email'),
                                __("Ammount"),
                                __("Status"),
                                __("Action"),
                            ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array AS $index =>$data) {
                                    $newsroomAmount="0.00";
                                    if(!empty($data['Cart']['is_newsroom_incart'])&&$data['Cart']['is_newsroom_incart']==1)
                                        $newsroomAmount = Configure::read('Site.newsroom.amount');
                                    $plan['Plan']=(isset($data['Plan'])&&!empty($data['Plan']))?$data['Plan']:"";
                                    $coupon_data=$this->Custom->getCoupons($data['Cart']['coupon_id'],$data['Cart']['cart_session_id']);
                                    $cart=$this->Custom->getUserCartData($data['Cart']['staff_user_id'],$coupon_data,$newsroomAmount,$plan);
                                    $actions ="";
                                    $actions .= ' ' . $this->Html->link(__('Send mail'), array('controller' => $controller, 'action' => 'sendmail', $data[$model]['id'],'index'), array('class' => 'btn btn-xs btn-success'));
                                    $rows[] = array(
                                        __($index+1),
                                        __($data["StaffUser"]['first_name'].' '.$data["StaffUser"]['first_name']),
                                        __($data["StaffUser"]['email']),
                                        __($currency.$cart['totals']['total']),
                                        __('Pending'),
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
                <!-- <div class="row">
                    <?php //echo $this->element('pagination'); ?>
                </div> -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>
