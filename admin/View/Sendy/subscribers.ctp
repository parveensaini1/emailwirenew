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
                                __("Email"),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        
                         <tbody>
                            <?php
                            $rows = array();
                            if (count($subscribers) > 0) {
                                $count=0;
                                foreach ($subscribers AS $data) { 
                                
                                $actions ="";
                               
                                $actions = ' ' . $this->Html->link(__('Delete'), array(
                                        'controller' => $controller,
                                        'action' => 'delete_subscriber',
                                        $data[$model]['id'],
                                        $lid,
                                        ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return confirmAction(this.href);'));
                               
                                

                                    $rows[] = array(
                                        __($count+1),
                                        __(trim($data[$model]['name'])),
                                        __($data[$model]['email']),
                                         $actions
                                    );
                                $count++;
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
