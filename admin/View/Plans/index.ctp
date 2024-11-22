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
                                __("S/N"),
                                __("Category name"),
                                __("Plan Type"),
                                __("Price"),
                                __("Discounted Amount"),
                                __("Plan XML Feed"),            
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
                                    if($data[$model]['status']==1){
                                        $actions .= ' '.$this->Html->link(__('Assign plan to user'), array('controller' =>'Plans', 'action' =>'assign_plans','?plan='.$data[$model]['id']), array('class' => 'btn btn-xs btn-primary'));
                                    }

                                    // $xml= $this->Html->link(__('Plan XML Feed'), SITEFRONTURL.'rss/byplan.rss?s='.$data['PlanCategory']['slug'], array('class' => 'btn btn-xs btn-info','target'=>"_blank"));
                                    $xml= $this->Html->link(__('Plan XML Feed'), SITEFRONTURL.'rss/pnsxml.rss?s='.$data['PlanCategory']['slug'], array('class' => 'btn btn-xs btn-info','target'=>"_blank"));

                                    $rows[] = array(
                                        __($counter),
                                        __($data['PlanCategory']['name']),
                                        __(ucfirst($data[$model]['plan_type'])),
                                        __($cSymbole.$data[$model]['price']),
                                        __($cSymbole.$data[$model]['bulk_discount_amount']),
                                        $xml,
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
