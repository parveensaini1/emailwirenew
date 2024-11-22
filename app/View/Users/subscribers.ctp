<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
             <?php echo $this->element('submenu'); ?>
                
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __("s/n"),
                                __("Name"),
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
                                $name = ' ' . $this->Html->link(__($data[$model]['name']), array('controller' => $controller, 'action' => 'update_list', $data[$model]['id']), array('class' => 'link'));

                                $actions = ' ' . $this->Html->link(__('Import subscriber csv'), array('controller' => $controller, 'action' => 'import_subscriber_csv', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));

                                $actions .= ' ' . $this->Html->link(__('Manually add subscriber'), array('controller' => $controller, 'action' => 'add_subscriber', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));
                                    $rows[] = array(
                                        __($data[$model]['id']),
                                        __($name), 
                                         $actions
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
