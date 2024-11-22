<?php echo $this->element('search'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php  echo "<div class='client_name'> Client : <strong>".$data_array['StaffUser']['first_name']." ".$data_array['StaffUser']['last_name']."</strong></div>";  ?>
                <div class="dataTable_wrapper">
				  <div class="table-responsive">	
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __('S/N'), 
                                __("logo"),
                                __("Company name"),
                                __("contact_name"),
                                __("phone_number"), 
                                __("job_title"), 
                                __("status"),
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
                                    $actions ="";
                                    if($data['status']==0){
                                     $actions = ' ' . $this->Html->link(__("Approve now"), array(
                                            'controller' => $controller,
                                            'action' =>"active_company",
                                            $data['id'],  
                                            $staff_user_id,
                                                ), array('class' => 'btn btn-xs btn-default'));
                                    }else{
                                        $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit_company', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));
                                       $s_action=($data[$model]['status']==2)?"active_company":"embargo_company";
                                        $actions .= ' ' . $this->Html->link(__(ucfirst(str_replace("_company"," now",$s_action))), array(
                                                'controller' => $controller,
                                                'action' => $s_action,
                                                $data[$model]['id'],
                                                $staff_user_id,
                                            ), array('class' => 'btn btn-xs btn-default'));
                                    }


 
                                    $logo="<img src='".FRONTURL.'files/company/logo/'.$data['logo_path'].'/'.$data['logo']."' width='50px' height='50px' />";
                                    $rows[] = array(
                                        __($index+1),
                                        $logo,
                                         __($data['name']),
                                        __($data['contact_name']),
                                        __($data['phone_number']),
                                        __($data['job_title']), 
                                       
                                        $this->Custom->getUserStatus($data['status']),
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
