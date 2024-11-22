
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
                                $this->Paginator->sort(__("code")),
                                __("Coupon type"),
                                __("value"),
                                __("Coupon release date"),
                                __("Coupon end date"),
                                __("Status"),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            $cSymbole=Configure::read('Site.currency');
                            if (count($data_array) > 0) {
                                $counter=1;
                                foreach ($data_array AS $data) {
                                    $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));
                                    $actions .= ' ' . $this->Html->link(__('Delete'), array(
                                                'controller' => $controller,
                                                'action' => 'delete',
                                                $data[$model]['id'],
                                                    ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return confirmAction(this.href);'));
                                    $symbole=($data[$model]['type']=='percentage')?"%":'';
                                     $rows[] = array(
                                        __($counter),
                                        __($data[$model]['code']),
                                        __(ucfirst($data[$model]['type'])),
                                        __($symbole.$data[$model]['value']),
                                        __(date("d F, Y",strtotime($data[$model]['release_date']))),
                                        __(date("d F, Y",strtotime($data[$model]['end_date']))),
                                        $this->Custom->get_status($data[$model]['status']),
                                        $actions,
                                    );

                                $counter++;
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="3">No result found!</td>
                                </tr>
                                <?php
                            }
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
