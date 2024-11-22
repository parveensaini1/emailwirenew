<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                $this->Paginator->sort(__("S/N")),
                                $this->Paginator->sort(__("title")),
                                $this->Paginator->sort(__("times")),
                                $this->Paginator->sort(__("viewed")),
                                $this->Paginator->sort(__("release_date")),
                                $this->Paginator->sort(__("status")),
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
                                    $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));
                                    
                                    if(isset($data['User']['id'])){
                                        $actions .= ' ' . $this->Html->link(__('View'), array('controller' => 'users', 'action' => 'view', $data['User']['id']), array('class' => 'btn btn-xs btn-info fancybox'));
                                    }
                                    
                                    $status = '';
                                    if ($data[$model]['status'])
                                        $status = '<i class="fa fa-check"></i>';
                                    else
                                        $status = '<i class="fa fa-close"></i>';
                                    $rows[] = array(
                                        __($data[$model]['id']),
                                        __($data[$model]['title']),
                                        $status,
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
