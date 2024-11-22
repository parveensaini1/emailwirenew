<style>
.card-default .cake-error{
    display:none;   
 }
}
</style>
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
                                __("Author/Client"),
                                __("Action"), 
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array AS $index =>$data) { 
                                    
                                    $plan_id  = $data['PressRelease']['plan_id'];
                                    $user_id  = $data['PressRelease']['staff_user_id'];
                                    
                                    $title = $this->Html->link(__($data[$model]['title']), array(
                                            'controller' => $controller,
                                            'action' =>"view",
                                            $data[$model]['id'],  
                                           'draft',
                                     ), array('class' => 'link')); 

                                    $actions = $this->Html->link(__('Edit'), array(
                                            'controller' => $controller,
                                            'action' =>"add",$data[$model]['language'],$data[$model]['plan_id'],
                                            $data[$model]['id'],
                                     ), array('class' => 'btn btn-xs btn-primary'));

                                     $actions .= ' ' . $this->Html->link(__('Trash'), array(
                                                'controller' => $controller,
                                                'action' => 'move_trash',
                                                $data[$model]['id'],
                                                'disapproved',), array('class' => 'btn btn-xs btn-danger'));

                                    $rows[] = array(
                                        __($index+1),
                                        __($title),
                                        __(date('d F Y',strtotime($data[$model]['release_date']))),
                                        '<a href="'.SITEURL.'PressReleases/clientReleases/'.$data['StaffUser']['id'].'">'.$data['StaffUser']['first_name'].' '.$data['StaffUser']['last_name'].'</a>',
                                        $actions
                                        
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                            <td align="center" colspan="8">
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
