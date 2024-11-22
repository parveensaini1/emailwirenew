<?php  $paginatorInformation = $this->Paginator->params();   ?>
<?php echo $this->element('submenu'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body"> 
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
                            if(!empty($settings)){
                             $count=(($paginatorInformation['page']-1)*$paginatorInformation['limit'])+1;
                            
                                foreach ($settings AS $setting) {
                                    //$actions = $this->Html->link(__('Move up'), array('controller' => 'settings', 'action' => 'moveup',base64_encode($setting['Setting']['id']), 'admin' => false), array('class' => 'btn btn-xs btn-primary'));

                                    // $actions .= ' ' . $this->Html->link(__('Move down'), array('controller' => 'settings', 'action' => 'movedown',base64_encode($setting['Setting']['id']), 'admin' => false), array('class' => 'btn btn-xs btn-default'));
                                    // $actions .= ' ' . $this->Html->link(__('Edit'), array('controller' => 'settings', 'action' => 'edit',base64_encode($setting['Setting']['id']), 'admin' => false), array('class' => 'btn btn-xs btn-success'));

                                   $actions = ' ' . $this->Html->link(__('<i class="fas fa-edit"></i>'), array('controller' => $controller, 'action' => 'edit', base64_encode($setting[$model]['id'])), array('class' => 'btn btn-sm btn-info','escape'=>false,'title'=>'Edit'));
                                    $actions .=$this->Html->link(__('<i class="fas fa-trash-alt"></i>'), array('controller' => $controller, 'action' => 'delete',base64_encode($setting[$model]['id'])), array("id"=>"homelink",'class' =>'ml-2 btn btn-sm btn-danger', 'onclick' =>"return confirmAction(this.href,'Are you sure want to delete?.','DELETE','question','true');",'escape'=>false,'title'=>'Delete'));


                                    $key = $setting['Setting']['key'];
                                    $keyE = explode('.', $key);
                                    $keyPrefix = $keyE['0'];
                                    if (isset($keyE['1'])) {
                                        $keyTitle = '.' . $keyE['1'];
                                    } else {
                                        $keyTitle = '';
                                    }

                                    $rows[] = array(
                                        $count,
                                        $this->Html->link($keyPrefix, array('controller' => 'settings', 'action' => 'index', 'p' => $keyPrefix)) . $keyTitle,
                                        $this->Text->truncate(strip_tags($setting['Setting']['value']), 20),
                                        $actions,
                                    );

                                    $count++;
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            }
                            ?> 
                        </tbody>
                    </table>
                </div>
                <?php  
                if($paginatorInformation['pageCount']>1){ ?>
                    <div class="row">
                        <?php echo $this->element('pagination'); ?>
                    </div>
                <?php } ?>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
