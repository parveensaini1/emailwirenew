<div class="row">
    <div class="col-lg-12">
       <div class="ew-title full"><?php echo $title;?></div>
        <?php  if (count($data_array) > 0) {?>
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __('S/N'), 
                                __("Logo"),
                                __("Company name"),
                                __("Contact name"), 
                                __("Status"),
                                __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array(); 
                                foreach ($data_array AS $index =>$data) { 
                                    $actions="";
                                    $logo="<img src='".SITEURL.'files/company/logo/'.$data['Company']['logo_path'].'/'.$data['Company']['logo']."' width='50px' height='50px' />";
                                    // if($data['Company']['payment_status']==0){
                                    //     $check=$this->Custom->checkTransactionForNewsroom($data['Company']['id']);
                                    //     if($check==0){
                                    //         $actions .= ' ' . $this->Html->link(__("Make payment"), array(
                                    //             'controller' => 'plans',
                                    //             'action' =>"online-distribution",$data['Company']['slug']
                                    //         ), array('class' => 'btn btn-sm btn-bg-orange'));
                                    //     }
                                    // }
                                        $actions .= ' ' . $this->Html->link(__("Edit"), array(
                                            'controller' => 'users',
                                            'action' =>"edit-newsroom",$data['Company']['slug'],$slugType
                                        ), array('class' => 'btn btn-sm btn-bg-orange'));
                                    
                                    // $actions .= ' ' . $this->Html->link(__("Edit"), array('controller' => 'plans','action' =>"online-distribution",$data['Company']['slug']),
                                    //     array('class' => 'btn btn-sm btn-primary'));
                                    $title=$this->Html->link(ucfirst($data['Company']['name']), array('controller' => $controller, 'action' => 'newsroom_view',$data['Company']['slug'],'prnews',$slugType));

                                    $rows[] = array(
                                        __($index+1),
                                        $logo,
                                         __($title),
                                        __($data['Company']['contact_name']), 
                                        $this->Custom->getStatus($data['Company']['status']),
                                        $actions,
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            ?>  
                        </tbody>
                    </table>
                </div>
                <!-- <div class="row">
                    <?php //echo $this->element('pagination'); ?>
                </div> -->
            </div>
        </div>
        <?php }else{  echo $this->Custom->getRecordNotFoundMsg();} ?>
    </div>
</div>
