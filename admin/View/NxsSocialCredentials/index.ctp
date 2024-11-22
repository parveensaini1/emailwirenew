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
                                __("id"),
                                __("social_media"),
                                __("social_site"),
                                __("social_media_short"),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        
                         <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array as $count => $data) {
                                     $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));

                                    $rows[] = array(
                                        __($count+1),
                                        __($data[$model]['social_media']),
                                        __($data[$model]['social_site']),
                                        __($data[$model]['social_media_short']),
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
