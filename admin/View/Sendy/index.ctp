<!-- /.row -->
<?php  include 'submenu.ctp'; ?>
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
                                __("s/n"),
                                __("Name"),
                                _("Is list for client"),
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
                                $name = ' ' . $this->Html->link(__($data[$model]['name']), array('controller' => $controller, 'action' => 'edit', $data[$model]['id']), array('class' => 'link'));

                                $actions = ' ' . $this->Html->link(__('Import subscribers'), array('controller' => $controller, 'action' => 'import_subscriber_csv', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));

                                $actions .= ' ' . $this->Html->link(__('Add subscriber manually'), array('controller' => $controller, 'action' => 'add_subscriber', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));

                                $actions .= ' ' . $this->Html->link(__('View subscribers'), array('controller' => $controller, 'action' => 'subscribers', $data[$model]['id']), array('class' => 'btn btn-xs btn-primary'));

                                if ($data[$model]['list_for_client']) {
                                    $list_for_client = '<a class="text-center" href="'.SITEURL.'sendy/assigntoclient/'.$data[$model]['id'].'/1"><i class="text-success fa fa-check-square-o"></i></a>';
                                } else {
                                    $list_for_client ='<a class="text-center" href="'.SITEURL.'sendy/assigntoclient/'.$data[$model]['id'].'/0" ><i class="fa fa-square"></i></a>';
                                }

                                    $rows[] = array(
                                        __($data[$model]['id']),
                                        __($name),
                                        $list_for_client,
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
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>
