<?php 
echo $this->Html->script(
    array( 
        '/plugins/lazyload/jquery.lazy.min',
        
    )
);
echo $this->Js->writeBuffer();

echo $this->element('submenu'); ?>
<?php echo $this->element('advance_search2'); $paginatorInformation = $this->Paginator->params();   ?>
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
                                __("Logo"),
                                __("Media Name"),
                                __("website"),
                                __("Location"),
                                __("Media Type"),
                                __("Potential Audience"),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                $count=(($paginatorInformation['page']-1)*$paginatorInformation['limit'])+1;
                                foreach ($data_array AS $data) {
                                     
                                    if($data[$model]['status']==$trashStatusCode){ // deleted
                                        $actions =$this->Html->link(__('<i class="fas fa-undo"></i>'), array('controller' => $controller, 'action' => 'restore',base64_encode($data[$model]['id'])), array("id"=>"homelink",'class' =>'ml-2 btn btn-sm btn-info', 'onclick' =>"return confirmAction(this.href,'Are you sure want to Restore?.','Restore','question','true');",'escape'=>false,'title'=>'Restore'));
                                        $actions .=' ' .$this->Html->link(__('<i class="fas fa-trash-alt"></i>'), array('controller' => $controller, 'action' => 'delete',base64_encode($data[$model]['id'])), array("id"=>"homelink",'class' =>'ml-2 btn btn-sm btn-danger', 'onclick' =>"return confirmAction(this.href,'Are you sure want to permanently delete?','Delete','question','true');",'escape'=>false,'title'=>'Delete'));
                                    }else{
                                        $actions = ' ' . $this->Html->link(__('<i class="fas fa-edit"></i>'), array('controller' => $controller, 'action' => 'edit',base64_encode($data[$model]['id'])), array('class' => 'btn btn-sm btn-info','escape'=>false,'title'=>'Edit'));
                                        $actions .=' ' .$this->Html->link(__('<i class="fas fa-trash-alt"></i>'), array('controller' => $controller, 'action' => 'trash',base64_encode($data[$model]['id'])), array("id"=>"homelink",'class' =>'ml-2 btn btn-sm btn-danger', 'onclick' =>"return confirmAction(this.href,'Are you sure want to trash it?','Trash','question','true');",'escape'=>false,'title'=>'Delete'));
                                    } 
                                
                                    $pa=$data[$model]['potential_audience'];
                                    if($pa=='0' || $pa=='')
                                    {
                                     $pa="NA";
                                    }
                                    $rows[] = array(
                                        __($count),
                                        __('<img class="lazyload" width="100px" data-src="'.SITEFRONTURL.'files/networkwebsite/'.$data[$model]['website_logo'].'"'),
                                        __($data[$model]['website_name']),
                                        __($data[$model]['website_domain']),
                                        __($data[$model]['website_location']),
                                        __($data[$model]['website_media_type']),
                                        __($pa),
                                        $actions,
                                    );
                                     $count++;
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                           
                            } else {
                                ?>
                                    <tr>
                                     <td align="center" colspan="12">
                                <div class="alert alert-dismissable label-default fade in">
                                    <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                    No record found.
                                </div> 
                            </td>
                                <?php
                            }
                            ?> 
                        </tbody>
                    </table>
                </div>
                <?php  if($paginatorInformation['pageCount']>1){ ?>
                    <div class="row">
                        <?php echo $this->element('pagination'); ?>
                    </div>
                <?php } ?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<script>
    $(document).ready(function(){
        $('.lazyload').lazy();
    });
</script>