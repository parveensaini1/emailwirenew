<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php include 'menu.ctp'; ?>
                <div class="box-header with-border">
                    <h3 class="box-title">Search Area</h3>
                </div>
                <?php echo $this->Form->create('StaffUser', array('type' => 'get')); ?>    
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-7">
                            <?php echo $this->Form->input('keyword', array('value' => $keyword, 'label' => false, 'class' => 'form-control', 'placeholder' => 'Name, Email..')); ?>                        
                        </div>                    
                        <div class="col-xs-3">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </div>
                </div>
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                $this->Paginator->sort(__("id")),
                                $this->Paginator->sort(__("staff_role_id")),
                                $this->Paginator->sort(__("name")),
                                $this->Paginator->sort(__("email")),
                                $this->Paginator->sort(__("phone")),
                               
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
                                foreach ($data_array AS $data) {
                                    $actions = ' ' . $this->Html->link(__('Edit'), array('controller' => $controller, 'action' => 'edit', $data[$model]['id']), array('class' => 'btn btn-xs btn-success'));

                                    if ($data[$model]['staff_role_id'] != 1) {
                                        $actions .= ' ' . $this->Html->link(__('Delete'), array(
                                                    'controller' => $controller,
                                                    'action' => 'delete',
                                                    $data[$model]['id'],
                                                    'token' => $this->params['_Token']['key'],
                                                        ), array('class' => 'btn btn-xs btn-danger', 'onclick' => 'return confirmAction(this.href);'));
                                    }
                                    $actions .= ' ' . $this->Html->link(__('Change Password'), array('controller' => $controller, 'action' => 'change_password', $data[$model]['id']), array('class' => 'btn btn-xs btn-warning'));
									
									
                                    $rows[] = array(
                                        __($data[$model]['id']),
                                        __($data['StaffRole']['title']),
                                        __($data[$model]['name']),
                                        __($data[$model]['email']),
                                        __($data[$model]['phone']),
                                       
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
