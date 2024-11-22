<style>
.card-default .cake-error{
    display:none;   
 }
}
</style>
<?php echo $this->element('search'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">  
                <div class="dataTable_wrapper">
				<div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                 __('S/N'), 
                                 // __('Id'),
                                __("Title"),
                                __("Release date"),
                                __("Post date"), 
                                __('Views'),
                                __('Status'),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array AS $index =>$data) { 
                                    $approved_url    =   SITEURL.'PressReleases/view/'.$data[$model]['id'];
                                    $viewscount=($data[$model]['views']>0)?$data[$model]['views']:"-";

                                    $status = '';
                                    if($data['PressRelease']['status'] == 0){
                                        $status = 'Pending';
                                    }else if($data['PressRelease']['status'] == 1){
                                        $status = 'Approve';

                                    }else if($data['PressRelease']['status'] == 2){
                                        $status = 'Embargoed';

                                    }else if($data['PressRelease']['status'] == 3){
                                        $status = 'Draft';

                                    }else if($data['PressRelease']['status'] == 4){
                                        $status = 'Tisapprove';

                                    }else if($data['PressRelease']['status'] == 5){
                                        $status = 'Trash';

                                    }

                                    $title='<a href="'.$approved_url.'">'.$data[$model]['title'].'</a>';
                                    if ($role_id==1) {
                                     $title .="<br/><br/> Approved By : ".$this->Custom->getUserNameById($data['PressRelease']['approved_by']); 
                                    }

                                    $rows[] = array(
                                        __($index+1),
                                        __($title),
                                        __(date('d F Y',strtotime($data[$model]['release_date']))), 
                                        __(date('d F Y',strtotime($data[$model]['created']))), 
                                        __($viewscount),
                                        __($status),
                                        
                                    );
                                }
                             echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            }else{ ?>
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
                </div>
                <!-- <div class="row">
                    <?php //echo $this->element('pagination'); ?>
                </div> -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<style type="text/css">
    .sendinmail{margin-top: 10px;}
</style>