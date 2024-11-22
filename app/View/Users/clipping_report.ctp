<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
    <?php if (count($data_array) >0){?>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"> 
                <div class="dataTable_wrapper">
				<div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover clipping-report-table" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                "S/N",
                                "Title",
                                "Read",
                                "Shared",
                                "Click Through",
                                "Release Date",
                                 _('Action'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                        
                                foreach ($data_array as $index => $data) {
                                    $title=$this->Html->link($data[$model]['title'], array('controller' => $controller, 'action' => 'viewclippingreport',$data[$model]['id']));

                                    $action = " ".$this->Html->link(__("View report"), array('controller' => $controller, 'action' => 'viewclippingreport',$data[$model]['id']), array('class' => 'btn btn-sm btn-bg-orange margin-right-2'));

                                    $action .= " ".$this->Html->link(__("Download report"), array('controller' => $controller, 'action' => 'download',$data[$model]['id'],'potential_audience',rand(0,1000)), array('class' => 'btn btn-sm btn-bg-orange'));
                                    $socialShareCount=(!empty($data['0']['socialShareCount']))?$data['0']['socialShareCount']:"0";
                                    $networkFeedCount=(!empty($data['0']['networkFeedCount']))?$data['0']['networkFeedCount']:"0";
                             
                                    $rows[] = array(
                                        __($index+1),
                                        $title,
                                        $data[$model]['views'],
                                        $socialShareCount,
                                        $networkFeedCount,
                                        date($dateformate, strtotime($data[$model]['release_date'])),
                                        $action
                                        
                                    );
                                }
                                unset($checkcart);
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                             ?>
                        </tbody>
                    </table>
					</div>
                </div>
                <div class="row">
                    <?php echo $this->element('pagination'); ?>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <?php }else{   echo $this->Custom->getRecordNotFoundMsg(); } ?>
</div>

