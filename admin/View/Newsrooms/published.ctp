<style>
.card-default .cake-error{
    display:none;   
 }
}
</style>
<?php echo $this->element('search'); ?>
<div class="row">
<div class="col-lg-12 col-12">
<?php   $totalCount=$this->Paginator->params(); echo "Total Records:".$totalCount["count"];?></div>
    <div class="col-lg-12 col-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                
                <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                 __('S/N'), 
                                __("logo"),
                                __("Company name"),
                                __("Contact name"),
                                __("Hear about us"),
                                __("Status"),
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
                                    $actions ="";
                                
                                    if($data[$model]['status']==0){
                                     $actions .= ' ' . $this->Html->link(__("Approve "), array(
                                            'controller' => $controller,
                                            'action' =>"active_company",
                                            $data[$model]['id'],  
                                           'published',
                                                ), array('class' => 'btn btn-xs btn-default'));
                                    }else{
                                        $actions .= ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit_newsroom', $data[$model]['id'],"published"), array('class' => 'btn btn-sm mr-2 btn-success'));
                                       $s_action=($data[$model]['status']==2)?"active_company":"suspend_company";
                                       $onclick='';
                                       if($s_action=='suspend_company'){
                                        $onclick='return reasonMessage(this.href);';
                                       }
                                        $actions .= ' ' . $this->Html->link(__(ucfirst(str_replace("_company","",$s_action))), array(
                                                'controller' => $controller,
                                                'action' => $s_action,
                                                $data[$model]['id'],
                                                'published',
                                            ), array('class' => 'btn btn-sm btn-warning','onclick'=>$onclick));
                                    }
                                    
                                    $logo="<img src='".FRONTURL.'files/company/logo/'.$data[$model]['logo_path'].'/'.$data[$model]['logo']."' width='50px' height='50px' />";

                                    $name = $this->Html->link(__($data[$model]['name']), array(
                                            'controller' => $controller,
                                            'action' =>"view",
                                            $data[$model]['slug'],'published'  ), array('class' => 'link'));

                                    if($role_id==1)
                                        $name .="<br/><br/> Approved By : ".$this->Custom->getUserNameById($data['Company']['approved_by']); 

                                    $rows[] = array(
                                        __($index+1),
                                        $logo,
                                         __($name),
                                        __($data[$model]['contact_name']), 
                                        __($data[$model]['hear_about_us']), 
                                        $this->Custom->getUserStatus($data[$model]['status']),
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
                    </table></div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>
