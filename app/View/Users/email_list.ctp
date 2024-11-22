<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="ew-title full"><?php echo $title_for_layout;?></div>
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                   <?php  include 'sendy_submenu.ctp'; ?>
                
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
                                $name = ' ' . $this->Html->link(__($data[$model]['name']), array('controller' => $controller, 'action' => 'edit-list', $data[$model]['id']), array('class' => 'link'));

                                $actions = ' ' . $this->Html->link(__('Import media email list csv'), array('controller' => $controller, 'action' => 'import-media-email-list', $data[$model]['id']), array('class' => 'btn btn-sm btn-info'));

                                $actions .= ' ' . $this->Html->link(__('Manually add email'), array('controller' => $controller, 'action' => 'add-media-email', $data[$model]['id']), array('class' => 'btn btn-sm btn-success'));

                                $actions .= ' ' . $this->Html->link(__('View media list'), array('controller' => $controller, 'action' => 'client-media-list', $data[$model]['id']), array('class' => 'btn btn-sm btn-bg-orange'));
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
