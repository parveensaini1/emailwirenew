<?php echo $this->element('search'); ?>
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
                                __("title"),
                                __("Impressions"),
                                __("Release Date"),
                                __("Action"),
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                $sn=1+(($page_number-1)*10);
                                foreach ($data_array as $index => $data) {
                                
                                $userId=$data['PressRelease']['staff_user_id'];
                                $useremail=$data['StaffUser']['email'];

                                $champ= $this->Sendy->checkSentMail($userId,$data['PressRelease']['id']);
                                $champId=(!empty($champ))?$champ['id']:"";
                                    $uploadreport=$this->Html->link(__('Upload Report(2-col)'), array('controller' => $controller, 'action' => 'uploadreport',$data[$model]['id']), array('class' => 'btn btn-xs btn-primary'));
                                    $uploadpdf=$this->Html->link(__('Upload Additional XML'), array('controller' => $controller, 'action' =>'uploadadditionalxml',$data[$model]['id']), array('class' => 'btn btn-xs btn-primary'));
                                    $addmanually=$this->Html->link(__('Add Manuallys'), array('controller' => $controller, 'action' => 'add_manually',$data[$model]['id']), array('class' => 'btn btn-xs btn-default'));
                                    // $editmanually=$this->Html->link(__('Edit Report'), array('controller' => $controller, 'action' => 'edit',$data[$model]['id']), array('class' => 'btn btn-xs btn-info'));
                                    $uploadcsv=$this->Html->link(__('Upload CSV(5-col)'), array('controller' => $controller, 'action' => 'uploadcsv',$data[$model]['id']), array('class' => 'btn btn-xs btn-primary'));
                                    $uploadreportByApi=$this->Html->link(__('Upload Report By API'), array('controller' => $controller, 'action' => 'uploadClippingReportByJson',$data[$model]['id']), array('class' => 'btn btn-xs btn-primary'));
                                    // $GMNReport=$this->Html->link(__('Generate GNM Report'), array('controller' => $controller, 'action' => 'updateClippingByGroupMediaNetwork',$data[$model]['id']), array('class' => 'dropdown-item')); //." ".$GMNReport

                                    $title=$this->Html->link($data[$model]['title'], array('controller' => $controller, 'action' => 'viewclippingreport',$data[$model]['id']))."<br/><br/>".$addmanually.' '.$uploadreport.' '.$uploadcsv." ".$uploadpdf." ".$uploadreportByApi;
                                    $actions = ' ' . "<button class='sendcls btn btn-xs btn-info' id='".$data[$model]['id']."' useremail='".$useremail."'" ."' sendtitle='".$data[$model]['title']."'>Send Report</button>";
                                    $actions .= '<br />' . $this->Html->link(__('View Report'), array('controller' => $controller, 'action' => 'viewclippingreport',$data[$model]['id']), array('class' => 'btn btn-xs btn-info'));
                                    $actions .= '<br />' . $this->Html->link(__('Download Report'), array('controller' => $controller, 'action' => 'download',$data[$model]['id'],'potential_audience'), array('target'=>"_blank",'class' => 'btn btn-xs btn-info'));
 
                                   $rows[] = array(
                                        __($sn++),
                                        $title,
                                        $data[$model]['views'],
                                        date('d-m-Y', strtotime($data[$model]['release_date'])),
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



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">    
            <div class="modal-header">
                <h5 class="modal-title" name="send_title" id="send_title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body">
                <form id="sendmail" action="#" method="POST">
                    <div class="form-input">
                            <label>Client Mail</label> <br>
                            <input class="form-control" type="text" disabled value="" name="registered_email" id="registered_email">
                        </div>
                        <div class="email_add_msg">Enter the email adresses using comma "," separator. For example demo1@gmail.com,demo2@gmail.com </div>
                        <div class="form-input hide_form"><input type="hidden" value="" name="pr_id" id="pr_id"></div>
                        <div class="form-input add_email_new ">
                            <label>Add Email ID</label><br>
                            <input class="form-control" type="text" value="" name="other_email"  id="other_email" autocomplete="off" placeholder="Enter the email address."></div>
                        </form>   
                </div>
                <div class="modal-footer"> 
                    <span class="mail_processing"></span>
                    <input class="btn btn-md btn-info" type="submit" value="Send Clipping Report" name="sendmailbtn" id="sendmailbtn">
                </div>
        </div>
    </div>
</div> 
<?php echo $this->element('send_report_by_mail'); ?>