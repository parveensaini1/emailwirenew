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
                                __("Title"),
                                __("Release date"), 
                                __("Newsroom payment status"),  
                                __("Newsroom status"),
                                __("PR payment status"), 
                                __("Author/Client"),
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
                                    $redirect="pending";
                                    $plan_id  = $data['PressRelease']['plan_id'];
                                    $user_id  = $data['PressRelease']['staff_user_id']; 
                                    $getNewsRoomPaymentStatus=$this->Custom->getNewsRoomPaymentStatus($data['Company']['id']);
                                    $actions ="";
                                    $flag=0;
                                    $PressReleasePaymentStatus="<span class='label label-default'>No payment</span>";
                                   if($data[$model]['is_paid']==1){
                                      //  if(!empty($data['TransactionPressRelease'])&&$data['TransactionPressRelease']['subtotal']>0){
                                            $flag=1;
                                            $PressReleasePaymentStatus="<span class='label label-success'>Success</span>";
                                        // }else{
                                        //     $PressReleasePaymentStatus="<span class='label label-danger'>Failed</span>";
                                        // }

                                        
                                        if($flag=='0'){
                                            $checkInCart=$this->Custom->checkPrIncart($user_id,$data[$model]['id']); 
                                            if($checkInCart>0){
                                                $PressReleasePaymentStatus="<span class='label label-warning'>Pending</span>";
                                            } 
                                        }
                                        unset($data['TransactionPressRelease']);
                                    }else{
                                           $flag=1; // For non payment PR
                                    }

                                    if($flag==1){
                                        // if($getNewsRoomPaymentStatus==1&&$data['Company']['status']==1){
                                             $actions .= ' ' . $this->Html->link(__("Approve"), array(
                                                    'controller' => $controller,
                                                    'action' =>"active_pr",
                                                    $data[$model]['id'],  
                                                   'pending',
                                                        ), array('class' => 'btn btn-xs btn-default'));
                                        // }

                                        $actions .= ' ' . $this->Html->link(__("Disapprove"), array(
                                            'controller' => $controller,
                                            'action' =>"inactive_pr",
                                            $data[$model]['id'],  
                                           'pending',
                                                ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return reasonMessage(this.href);'));

                                    }
                                    $actions .= ' ' . $this->Html->link(__("Edit"), array(
                                            'controller' => $controller,
                                            'action' =>"add",$data[$model]['language'],
                                            $data[$model]['plan_id'],
                                            $data[$model]['id'],  
                                           'pending',
                                                ), array('class' => 'btn btn-xs btn-primary'));
                                                
                                   if ($data['StaffUser']['staff_role_id'] != 1) {
                                     $actions .= ' ' . $this->Html->link(__('Trash'), array(
                                                'controller' => $controller,
                                                'action' => 'move_trash',
                                                $data[$model]['id'],
                                                "pending", 
                                                    ), array('class' => 'btn btn-xs btn-danger'));
                                   }

                                    $frontview =$this->Html->link(__("Front view"), array(
                                        'controller' => $controller,
                                        'action' =>"frontview",
                                        $data[$model]['id'],)
                                    , array('class' => 'btn btn-xs btn-primary'));

                                    $title = $this->Html->link(__($data[$model]['title']), array(
                                            'controller' => $controller,
                                            'action' =>"view",
                                            $data[$model]['id'],  
                                           'pending',
                                     ), array('class' => 'link')); 
                                    

                                    $newsroomlink = $this->Html->link(__('View newsroom'), array(
                                            'controller' =>'Newsrooms',
                                            'action' =>"view",
                                            $data['Company']['id'],  
                                           'pending',
                                     ), array('class' => 'link'));

                                    $getNewsRoomPaymentStatus=$this->Custom->getNewsRoomPaymentStatus($data['Company']['id']);
                                    $newsroomPaymentStatus=($getNewsRoomPaymentStatus==1)?"<span class='label label-success'>Success</span><br/><br/>$newsroomlink":"<span class='label label-success'>Failed</span> <br/><br/>$newsroomlink";
                                    $newsroomStatus=$this->Custom->getUserStatus($data['Company']['status']);

                                    $rows[] = array(
                                        __($index+1),
                                        $title."<br/>".$frontview,
                                        __(date('d F Y',strtotime($data[$model]['release_date']))), 
                                        $newsroomPaymentStatus,
                                        $newsroomStatus,
                                        $PressReleasePaymentStatus,
                                        '<a href="'.SITEURL.'PressReleases/clientReleases/'.$data['StaffUser']['id'].'">'.$data['StaffUser']['first_name'].' '.$data['StaffUser']['last_name'].'</a>',
                                        $actions,
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                            <td align="center" colspan="8">
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
