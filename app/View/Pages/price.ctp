<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                <?php include 'menu.ctp';  
               // echo $this->element('search_with_box'); ?>
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                $this->Paginator->sort(__("id")),
                                $this->Paginator->sort(__("title")),
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

                                    $actions .= ' ' . $this->Html->link(__('Delete'), array(
                                                'controller' => $controller,
                                                'action' => 'delete',
                                                $data[$model]['id'],
                                                'token' => $this->params['_Token']['key'],
                                                    ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return confirmAction(this.href);'));
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
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
