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
                                __("logo"),
                                __("Company name"),
                                __("Contact name"), 
                                // __("Status"),
                                __("Reason"), 
                                __("Disapproval Date"), 
                                // __('Actions'),
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
                                   
                                   /* $actions .= ' ' . $this->Html->link(__("Approve "), array(
                                            'controller' => $controller,
                                            'action' =>"active_company",
                                            $data[$model]['id'],  
                                           'disapproved',
                                    ), array('class' => 'btn btn-xs btn-default'));*/

                                    $logo="<img src='".SITEFRONTURL.'files/company/logo/'.$data[$model]['logo_path'].'/'.$data[$model]['logo']."' width='50px' height='50px' />";
                                    $name = $this->Html->link(__($data[$model]['name']), array(
                                        'controller' => $controller,
                                        'action' =>"view",
                                        $data[$model]['slug'],'disapproved'  ), array('class' => 'link'));
                                    
                                     if($role_id==1)
                                        $name .="<br/><br/> Disapproved By : ".$this->Custom->getUserNameById($data[$model]['approved_by']); 
                                    
                                    $rows[] = array(
                                        __($index+1),
                                        $logo,
                                         __($name),
                                        __($data[$model]['contact_name']), 
                                        __($data[$model]['disapproval_reason']),
                                        __($data[$model]['disapproval_date']),
                                        // $this->Custom->getUserStatus($data[$model]['status']),
                                        // $actions,
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
