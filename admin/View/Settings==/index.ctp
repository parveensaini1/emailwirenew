 

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
                                $this->Paginator->sort('id', NULL),
                                $this->Paginator->sort('key'),
                                $this->Paginator->sort('value'),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            foreach ($settings AS $setting) {
                                $actions = $this->Html->link(__('Move up'), array('controller' => 'settings', 'action' => 'moveup', $setting['Setting']['id'], 'admin' => false), array('class' => 'btn btn-xs btn-primary'));
                                $actions .= ' ' . $this->Html->link(__('Move down'), array('controller' => 'settings', 'action' => 'movedown', $setting['Setting']['id'], 'admin' => false), array('class' => 'btn btn-xs btn-default'));
                                $actions .= ' ' . $this->Html->link(__('Edit'), array('controller' => 'settings', 'action' => 'edit', $setting['Setting']['id'], 'admin' => false), array('class' => 'btn btn-xs btn-success'));

                                $actions .= ' ' . $this->Html->link(__('Delete'), array(
                                            'controller' => 'settings',
                                            'action' => 'delete',
                                            $setting['Setting']['id'],
                                                ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return confirmAction(this.href);'));

                                $key = $setting['Setting']['key'];
                                $keyE = explode('.', $key);
                                $keyPrefix = $keyE['0'];
                                if (isset($keyE['1'])) {
                                    $keyTitle = '.' . $keyE['1'];
                                } else {
                                    $keyTitle = '';
                                }

                                $rows[] = array(
                                    $setting['Setting']['id'],
                                    $this->Html->link($keyPrefix, array('controller' => 'settings', 'action' => 'index', 'p' => $keyPrefix)) . $keyTitle,
                                    $this->Text->truncate(strip_tags($setting['Setting']['value']), 20),
                                    $actions,
                                );
                            }

                            echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
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
