<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-body"> 
                <div class="dataTable_wrapper">
                    <div class="ew-title full">Email wire</div> 
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __("Site name"),
                                __("Impressions"),
                                __("Release date"),
                             ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows1 = array();
                           
                                  $rows1[] = array(
                                        "Email wire",
                                        $pr_data['PressRelease']['views'],
                                        date('d-m-Y', strtotime($pr_data['PressRelease']['release_date'])),
                                    ); 
                                echo $this->Html->tableCells($rows1, array('class' => 'gradeX'));
                            ?>
                        </tbody>
                   </table>

                   <div class="ew-title full row">
                        <div class="col-lg-4">
                            Email wire Network 
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                            <?php echo $this->Form->create('Search', array('url'=>SITEURL.'ClippingReports/viewreport/'.$prId.'/','type' => 'get', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control form-inline', 'required' => false))); ?>
                                <div class="col-sm-3">Filter with:-</div>
                                <div class="col-sm-4">
                                <?php 
                                $selected=(!empty($this->request->query('type')))?$this->request->query('type'):"";
                                $options=['rss_feed'=>'Rss feed','js_feed'=>'Js feed','network_feed'=>'Network feed'];
                                echo $this->Form->input('type', array('selected' => $selected, 'id' => 'type', 'options' => $options,'onchange'=>"redirectclip(this.value)", 'empty' => 'Select distribution type', 'class' => 'form-control',"label"=>false)); ?>
                               
                               </div> 
                               <div class="col-sm-5">
                                     <?php
                        $options=[1=>'Journalist',2=>'Bloggers',3=>'Individuals'];
                        echo $this->Form->input('st', array('empty' => '-Select subscriber type-', "options" => $options,'class'=>'form-control','label'=>false,'onchange'=>"redirect(this.value)","default"=>$st));
                        ?>
                               </div> 
                               <?php echo $this->Form->end();?>
                            </div>
                        </div>
                   </div> 
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __("S/N"),
                                __("Subscriber Name"),
                                __("Email"),
                                __("type"),
                                __("Is opened"),
                                __("Status"),
                                __("Date"),
                                ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array as $index =>$data){
                                    $subscriber_name=$data['StaffUser']['first_name'];
                                    $email=$data['StaffUser']['email'];
                                    $status=($data['NewsletterLog']['is_mail_sent']==0)?"NOT SENT":"SENT";
                                    $is_opened=($data['NewsletterLog']['is_opened']==1)?"Opened":"not opened";
                                    $date=date("d-m-Y", strtotime($data['NewsletterLog']['created']));
                                    switch ($data['StaffUser']['subscriber_type']) {
                                        case '1':
                                        $subscriber_type    =   'Journalist';
                                        break;
                                        case '2':
                                        $subscriber_type    =   'Bloggers';
                                        break;
                                        default:
                                        $subscriber_type    =   'Individuals';
                                        break;
                                    }

                                    $rows[] = array(
                                        __($index+1),
                                        $subscriber_name,
                                        $email,
                                        $subscriber_type,
                                        $is_opened,
                                        $status,
                                        $date

                                    );
                                }
                                unset($checkcart);
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="7">No result found!</td>
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
<script type="text/javascript">
    function redirect(selected){
        var prId='<?php echo $prId;?>';
        var url=SITEURL+'ClippingReports/subscriberlist/'+prId+'/'+selected;
        window.location.replace(url);
    }

     function redirectclip(selected){
        var prId='<?php echo $prId;?>';
        var url=SITEURL+'ClippingReports/viewreport/'+prId+'/?sname=&type='+selected;
        window.location.replace(url);
    }
</script>