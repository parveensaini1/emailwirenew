<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php  echo "<div class='client_name'> Client : <strong>".$data_array['StaffUser']['first_name']." ".$data_array['StaffUser']['last_name']."</strong></div>";  ?>
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                $this->Paginator->sort('S/N'), 
                                $this->Paginator->sort("logo"),
                                $this->Paginator->sort("name","Company name"),
                                $this->Paginator->sort(__("contact_name")),
                                $this->Paginator->sort(__("status")),
                                $this->Paginator->sort(__("payment status")),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            
                            if (count($data_array) > 0) {
                                foreach ($data_array['Company'] AS $index =>$data) {
                                    $staff_user_id=$data_array["StaffUser"]['id'];
                                    $flag=0;
                                    $paymentStatus="<span class='label label-danger'>Failed</span>";
                                    if($data['Transaction']['status']=='Success'&&$data['Transaction']['newsroom_amount']>'0'){
                                    $paymentStatus="<span class='label label-success'>Success</span>";
                                    $flag=1;
                                    }else{
                                     $count= $this->Custom->checkNewsroomIncart($data['StaffUser'][0]['id']);
                                      if($count==1){
                                        $paymentStatus="<span class='label label-warning'>Pending</span>"; 
                                      }
                                    }    
                                $actions ="";
                                if($flag=='1'){  
                                if($data['status']==0){ 
                                        $actions .= ' ' . $this->Html->link(__("Approve"), array(
                                                    'controller' => $controller,
                                                    'action' =>"active_company",
                                                    $data['id'],  
                                                    $staff_user_id,
                                                        ), array('class' => 'btn btn-xs btn-default'));
                                        }elseif($data['status']==1){
                                            $actions .= ' ' . $this->Html->link(__("Disapprove"), array(
                                                    'controller' => $controller,
                                                    'action' =>"active_company",
                                                    $data['id'],  
                                                    $staff_user_id,
                                                        ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return reasonMessage(this.href);'));
                                        }else{
                                          //  $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit_company', $data['id']), array('class' => 'btn btn-xs btn-success'));

                                           $s_action=($data['status']==2||$data['status']==3)?"active_company":"suspend_company";
                                            $actions .= ' ' . $this->Html->link(__(ucfirst(str_replace("_company"," now",$s_action))), array(
                                                    'controller' => $controller,
                                                    'action' => $s_action,
                                                    $data['id'],
                                                    $staff_user_id,
                                                ), array('class' => 'btn btn-xs btn-default'));
                                        }
                                    }
								    $logo="<img src='".FRONTURL.'files/company/logo/'.$data['logo_path'].'/'.$data['logo']."' width='50px' height='50px' />";
                                    $rows[] = array(
                                        __($index+1),
                                        $logo,
                                        __($data['name']),
                                        __($data['contact_name']),
                                        $this->Custom->getUserStatus($data['status']),
                                        $paymentStatus,
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
