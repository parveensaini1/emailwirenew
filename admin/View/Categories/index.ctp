<!-- /.row -->
<?php include 'menu.ctp'; ?>
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
                                $this->Paginator->sort(__("Sr. No.")),
                                $this->Paginator->sort(__("name")),                               
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                            	$i = 0;
                                foreach ($data_array AS $key=>$value) {
                            	$i++;
                                    $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit',$key), array('class' => 'btn btn-xs btn-success'));

                                   $actions .= ' ' . $this->Html->link(__('Delete'), array(
                                                'controller' => $controller,
                                                'action' => 'delete',
                                                $key,
                                                    ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return confirmAction(this.href);'));
                                    $actions .= ' ' . $this->Html->link(__('Move up'), array('controller' => $controller, 'action' => 'moveup', $key, 1), array('class' => 'btn btn-xs btn-primary'));
                                    $actions .= ' ' . $this->Html->link(__('Move down'), array('controller' => $controller, 'action' => 'movedown', $key, 1), array('class' => 'btn btn-xs btn-info'));


                                    $rows[] = array(
                                        __($i),
                                        __($value),
                                        $actions,
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="3">No result found!</td>
                                </tr>
                                <?php
                            }
                            // echo $tableHeaders;
                            ?> 
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <?php //echo $this->element('pagination'); ?>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>
