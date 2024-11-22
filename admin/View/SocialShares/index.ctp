<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <div class="card-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __('S/N'),
                                __('Title'),
                                __('Status'),
                                __('Order'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            foreach ($data_array AS $count=> $data) {
                                $actions = $this->Html->link(__('Move up'), array('controller' => $controller, 'action' => 'moveup', $data[$model]['id'], 'admin' => false), array('class' => 'btn btn-xs btn-primary'));
                                $actions .= ' ' . $this->Html->link(__('Move down'), array('controller' => $controller, 'action' => 'movedown', $data[$model]['id'], 'admin' => false), array('class' => 'btn btn-xs btn-default'));
                                $actions .= ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit', $data[$model]['id'], 'admin' => false), array('class' => 'btn btn-xs btn-success'));
                              //  $actions .= ' ' . $this->Html->link(__('Edit'), array('controller' =>$controller, 'action' => 'edit', $data[$model]['id'], 'admin' => false), array('class' => 'btn btn-xs btn-success'));
                                
                               $status=$this->Custom->getCheckBoxStatus($data[$model]['id'],$data[$model]['status'],$model);
                               $rows[] = array(
                                    ($count+1),
                                    $data[$model]['title'],
                                    $status,
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

<script type="text/javascript">
function changeStatusSocial(id,status,model) {
     $.ajax({
        url: SITEURL+'ajax/change_status/',
        type: 'POST',
        data:{id:id,model:model,status:status},
        success: function (response) {
        if(response!=0){   
         $("#change_status_"+id).replaceWith(response);
        }else{
           alert("This is special news.please remove from special news than Inactive.");
          }
      }  
    });
}
</script>