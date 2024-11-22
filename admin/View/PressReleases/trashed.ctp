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
                                 __('S/N'),  
                                __("Title"),
                                __("Release date"),
                                __("Disapproval Date"),  
                                __("Author/Client"),
                                __("Action"),
                                    ), array(), array('class' => 'sorting'));
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            
                            if (count($data_array) > 0) {
                                       foreach ($data_array AS $index =>$data) { 
                                   
                                          $actions = $this->Html->link(__('Restore'), array(
                                                    'controller' => $controller,
                                                    'action' => 'restore',
                                                    $data[$model]['id'],
                                                    $data[$model]['old_status'], 
                                                        ), array('class' => 'btn btn-xs btn-primary'));
                                        $actions .= ' ' . $this->Html->link(__('Delete permanently '), array(
                                                    'controller' => $controller,
                                                    'action' => 'delete',
                                                    $data[$model]['id'],
                                                    'trashed', 
                                                        ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return confirmAction(this.href);'));
                                

                                    
                                    $title = $this->Html->link(__($data[$model]['title']), array(
                                            'controller' => $controller,
                                            'action' =>"view",
                                            $data[$model]['id'],  
                                           'disapproved',
                                     ), array('class' => 'link')); 
                                    
                                     

                                    if($role_id==1)
                                        $title .="<br/><br/> Disapproved By : ".$this->Custom->getUserNameById($data[$model]['approved_by']); 

                                   if($role_id==1){
                                     $rows[] = array(
                                        __($index+1),
                                        __($title),
                                        __(date('d F Y',strtotime($data[$model]['release_date']))), 
                                        __(date('d F Y',strtotime($data[$model]['disapprove_date']))),  
                                        '<a href="'.SITEURL.'PressReleases/clientReleases/'.$data['StaffUser']['id'].'">'.$data['StaffUser']['first_name'].' '.$data['StaffUser']['last_name'].'</a>',
                                        $actions,
                                    );
                                   }else{
                                     $rows[] = array(
                                        __($index+1),
                                        __($title),
                                        __(date('d F Y',strtotime($data[$model]['release_date']))), 
                                        __(date('d F Y',strtotime($data[$model]['disapprove_date']))),  
                                        '<a href="'.SITEURL.'PressReleases/clientReleases/'.$data['StaffUser']['id'].'">'.$data['StaffUser']['first_name'].' '.$data['StaffUser']['last_name'].'</a>',
                                    );
                                   }
                                    
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
