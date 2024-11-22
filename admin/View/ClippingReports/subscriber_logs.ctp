<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-body"> 
                <div class="dataTable_wrapper">
                  <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __("S/N"),
                                __("Press Release"),
                                __("Total"),
                                __("Status"),
                               __("Action"),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array as $index => $data) {
                                    $press_release=$data['PressRelease']['title'];
                                    $is_mail_sent   =   $data['NewsletterLog']['is_mail_sent'];
                                    if($is_mail_sent==0)
                                    {
                                        $status="NOT SENT";
                                    }
                                    else
                                    {   $status="SENT";

                                    }
                                    $actions = ' ' . $this->Html->link(__('View'), array('controller' =>'ClippingReports', 'action' => 'subscriberlist',$data['NewsletterLog']['press_release_id']), array('class' => 'btn btn-xs btn-primary'));
                                    
                                    $count_user=$this->Custom->subscrber_count($data['NewsletterLog']['press_release_id']);
                                    $rows[] = array(
                                        __($index+1),
                                        $press_release,
                                        $count_user,
                                        $status,
                                        $actions
                                    
                                       
                                    );
                                }
                                unset($checkcart);
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="3">No result found!</td>
                                </tr>
                                <?php 
                        } ?>
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
