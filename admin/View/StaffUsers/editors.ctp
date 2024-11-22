<?php include 'menu.ctp'; 
  ?>

  
  <div class="row">
<div class="col-sm-12">
    <div class="card card-default"> 
            <!-- /.card-heading -->

                <div class="card-header with-border">
                    <h3 class="card-title">Search Area</h3>
                </div>
                <?php echo $this->Form->create('StaffUser', array('type' => 'get')); ?>    
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-7 col-sm-7 col-md-7">
                            <?php echo $this->Form->input('keyword', array('value' => $keyword, 'label' => false, 'class' => 'form-control', 'placeholder' => 'First name,Last name, Email..')); ?>                        
                        </div>                    
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </div>
                </div>
                 <?php echo $this->Form->end();
              
                 ?>
                 </div>
</div>

</div> 

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
                                // __("User Role"),
                                __("First name"),
                                __("last name"),
                                __("Email"),
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
                                foreach ($data_array AS $index=> $data) {
                                    $redirect=$this->Custom->getredirect_action($data[$model]['staff_role_id']);

                                    $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit', $data[$model]['id'],$redirect), array('class' => 'btn btn-xs btn-success'));

                                    if ($data[$model]['staff_role_id'] != 1) {
                                        $actions .= ' ' . $this->Html->link(__('Trash'), array(
                                                    'controller' => $controller,
                                                    'action' => 'move_trash',
                                                    $data[$model]['id'],$redirect,
                                                        ), array('class' => 'btn btn-xs btn-danger'));
                                    }

                                    if ($data[$model]['staff_role_id'] != 1) {
                                        if($data[$model]['status']==0){
                                         $actions .= ' ' . $this->Html->link(__("Active now"), array(
                                                'controller' => $controller,
                                                'action' =>"restore",
                                                $data[$model]['id'], 
                                                $redirect,
                                                    ), array('class' => 'btn btn-xs btn-default'));
                                        }else{
                                           $s_action=($data[$model]['status']==2)?"restore":"suspend";
                                            $actions .= ' ' . $this->Html->link(__(ucfirst($s_action)), array(
                                                    'controller' => $controller,
                                                    'action' => $s_action,
                                                    $data[$model]['id'], 
                                                    $redirect,
                                                ), array('class' => 'btn btn-xs btn-default'));
                                        }
                                    }


                                    $actions .= ' ' . $this->Html->link(__('Change Password'), array('controller' => $controller, 'action' => 'change_password', $data[$model]['id'],$redirect), array('class' => 'btn btn-xs btn-warning'));
									
									
                                    $rows[] = array(
                                        __($index+1),
                                        // __($data['StaffRole']['title']),
                                        __($data[$model]['first_name']),
                                        __($data[$model]['last_name']),
                                        __($data[$model]['email']), 
                                       
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
