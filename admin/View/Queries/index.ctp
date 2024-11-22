<!-- /.row -->
<?php echo $this->element('submenu'); ?>
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
                                $this->Paginator->sort(__("id")),
                                $this->Paginator->sort(__("Name")),
                                $this->Paginator->sort(__("phone")),
                                $this->Paginator->sort(__("email")),
                                $this->Paginator->sort(__("subject")),
                                $this->Paginator->sort(__("message")),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        
                         <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array AS $data) {
                                     $actions = ' ' . $this->Html->link(__("Reply"), array('controller' => $controller,'action' =>"replyto",$data[$model]['id']),array('class' => 'btn btn-xs btn-info', 'onclick' => 'return reasonMessage(this.href,"Reply to user.");'));
                                    // $status = '';
                                    // if ($data[$model]['status'])
                                    //     $status = '<i class="fa fa-check"></i>';
                                    // else
                                    //     $status = '<i class="fa fa-close"></i>';
                                    $rows[] = array(
                                        __($data[$model]['id']),
                                        __(ucfirst($data[$model]['contact_name'])),
                                        __($data[$model]['phone']),
                                        __($data[$model]['email']),
                                        __($data[$model]['subject']),
                                        __($data[$model]['message']),
                                        $actions,
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else { ?>
                                <tr><td align="center" colspan="7">No result found!</td></tr>
                            <?php } ?> 
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
