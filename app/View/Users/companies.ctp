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
                                $this->Paginator->sort('S/N'), 
                                $this->Paginator->sort("logo"),
                                $this->Paginator->sort("name","Company name"),
                                $this->Paginator->sort(__("contact_name")), 
                                $this->Paginator->sort(__("status")),
                                 __('Actions'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            
                            if (count($data_array) > 0) {
                                foreach ($data_array AS $index =>$data) { 
                                    $actions="";
                                    $logo="<img src='".SITEURL.'files/company/logo/'.$data['Company']['logo_path'].'/'.$data['Company']['logo']."' width='50px' height='50px' />";
                                    if($data['Company']['payment_status']==0){
                                         $actions .= ' ' . $this->Html->link(__("Make payment"), array(
                                            'controller' => 'plans',
                                            'action' =>"online-distribution",
                                             ), array('class' => 'btn btn-xs btn-primary'));
                                    }

                                    $rows[] = array(
                                        __($index+1),
                                        $logo,
                                         __($data['Company']['name']),
                                        __($data['Company']['contact_name']), 
                                        $this->Custom->getStatus($data['Company']['status']),
                                        $actions,
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                            <td align="center" colspan="6">
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
                <!-- <div class="row">
                    <?php //echo $this->element('pagination'); ?>
                </div> -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
