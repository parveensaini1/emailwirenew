<!-- /.row -->




<?php echo $this->element('submenu'); ?>
<!-- /.row -->
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
                                __("S/N"),
                                __("Name"),
                                __("Amount"),
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
                                    $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));
                                    $statusClass = $data[$model]['status']==0 ? 'btn btn-xs btn-danger' : 'btn btn-xs btn-success';
                                    $actions .= ' '.$this->Html->link(__($data[$model]['status']==0 ? 'InActive' : 'Active'), array('controller' => $controller, 'action' => 'changeStatus', $data[$model]['id'],$data[$model]['status']), array('class' => $statusClass));
                                   // $actions .= ' ' . $this->Custom->get_status($data[$model]['status']);
                                    $rows[] = array(
                                        __($index+1),
                                        __($data[$model]['name']),
                                        __($data[$model]['amount']), 
                                        $actions,
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="6">No result found!</td>
                                </tr>
                                <?php
                            }
                           
                            ?> 
                        </tbody>
                    </table>
                        
                        

                           
                    </table>
                </div>
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

                    
                    
                    
                    <div class="content-header pagetitle">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Newsroom Fee/Duration</h1>
                      </div><!-- /.col --> 
                    </div><!-- /.row -->
                  </div><!-- /.container-fluid -->
                </div>


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
                                __("S/N"),
                                __("Name"),
                                __("Value"),
                                __('Actions'),
                                    ));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            $cSymbole=Configure::read('Site.currency');
                                
                                    $actions = ' ' . $this->Html->link(__('Edit'), array('controller' =>"MasterPlans", 'action' => 'editPrice', $master_plans_array["id"]), array('class' => 'btn btn-xs btn-success'));
                                    $rows[] = array(
                                        __(1),
                                        __("Newsroom Fee"),
                                        __($cSymbole.$master_plans_array['price']),
                                        $actions,
                                    );
                                    
                                    $actions = ' ' . $this->Html->link(__('Edit'), array('controller' =>"MasterPlans", 'action' => 'editDuration', $master_plans_array["id"]), array('class' => 'btn btn-xs btn-success'));
                                    $rows[] = array(
                                        __(2),
                                        __("Newsroom Duration"),
                                        __(ucfirst($master_plans_array['duration'])),
                                        $actions,
                                    );
                                    
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                           ?>
                        </tbody>
                    </table>
                </div>
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