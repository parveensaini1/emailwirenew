<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-body"> 
                <div class="dataTable_wrapper">
                    <div class="ew-title full"> 
                        <div class="row">
                            <div class="col-sm-5"> Email wire</div>
                                <div class="col-lg-7 text-right">
                            <?php 
                                $userId=$pr_data['PressRelease']['staff_user_id'];
                                $champ= $this->Sendy->checkSentMail($userId,$prId);
                                $champId=(!empty($champ))?$champ['id']:"";
                                $count_user=$this->Custom->subscrber_count($prId);
                                if(!empty($count_user)){
                                    echo $this->Html->link(__("Subscriber clipping report ($count_user)"), array('controller' =>'ClippingReports', 'action' => 'subscriberlist',$prId), array('class' => 'btn btn-xs btn-primary'));
                                }else{
                                    echo "<label class='btn btn-xs btn-primary'>Subscriber clipping report (0)</label>";
                                }
                                echo " ".$this->Html->link(__("Add manually"), array('controller' =>'ClippingReports', 'action' => 'add_manually',$prId), array('class' => 'btn btn-xs btn-default'));
                                echo " ".$this->Html->link(__('Upload Report'), array('controller' => $controller, 'action' => 'uploadreport',$prId), array('class' => 'btn btn-xs btn-default'));
                                echo " ".$this->Html->link(__('Send Report'), array('controller' => $controller, 'action' => 'sendreport',$prId,$champId), array('class' => 'btn btn-xs btn-info','title'=>"Send to ".$pr_data['StaffUser']['email']));
                                
                               ?>
                            </div>
                        </div>
                    </div>
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
                        <div class="col-sm-6">
                            Email wire Network 
                        
                        </div>
                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                
                            <?php 
                            $selected=(!empty($this->request->query('type')))?$this->request->query('type'):"";
                            echo $this->Form->create('Search', array('url'=>SITEURL.'ClippingReports/viewreport/'.$prId.'/','type' => 'get', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control form-inline', 'required' => false))); ?>
                            <div class="row">
                                <!-- <div class="col-sm-2">Search:-</div> -->
                                <?php if(!empty($selected)&&$selected!='mail_feed'){ ?>
                                <div class="col-sm-4">
                                    <?php
                                    $name=(!empty($this->request->query('sname')))?$this->request->query('sname'):"";
                                    echo $this->Form->input('sname',['placeholder'=>'Please enter site name..','label'=>false,'value'=>$name]);?>
                                </div>
                             <?php }?>
                                <div class="col-sm-4">
                                <?php 
                                echo $this->Form->input('cid', ['type' => 'hidden','value'=>$champId]);
                                $options=['rss_feed'=>'Rss feed','js_feed'=>'Js feed','mail_feed'=>'Mail feed','network_feed'=>'Network feed'];
                                echo $this->Form->input('type', array('selected' =>$selected, 'id' => 'type', 'options' => $options, 'empty' => 'Select distribution type', 'class' => 'form-control',"label"=>false)); ?>
                               
                               </div> 
                               <div class="col-sm-2">
                                <?php echo $this->Form->submit('Search', array('class' => 'btn btn-info', 'div' => false)); ?>
                               </div> 
                               </div>
                               <?php echo $this->Form->end();?>
                            </div>
                        </div>
                   </div> 
                  <?php   
                    if($selected=='mail_feed'){ 


                            $createdfrom=(!empty($champ))?$champ['createdfrom']:"";
                            echo "<div class='$createdfrom'>";
                                include 'sentmailreportmenu.ctp';
                                if($createdfrom=='fronted'&&$msts=='graph'){
                                    $userId=$champ['userID'];
                                    include 'sentmailreport.ctp';
                                }else{ 
                                    include 'sendy_subscriber_report_list.ctp';
                                }
                            echo '</div>';
                        
                        
                      }else{?>

                   <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                $this->Paginator->sort("S/N"),
                                __("Site name"),
                                __("Published url"),
                                __("Distribution type"),
                                __("Impressions"),
                                __("Email"),
                                __("Distribution date"),
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
                                    $email="-";
                                    if($data[$model]['distribution_type']=='mail_feed'&&!empty($data[$model]['email'])){
                                         $email= $this->Custom->getEmailforClippingReport($data[$model]['email']);
                                     }

                                      $actions = ' ' . $this->Html->link(__("Edit"), array(
                                            'controller' =>'ClippingReports',
                                            'action' =>"edit",
                                            $data[$model]['id'],  
                                                ), array('class' => 'btn btn-xs btn-info'));

                                    $editTitle="<a href='".SITEURL."ClippingReports/edit/".$data[$model]['id']."' >".$data[$model]['site_name']."</a>";
                                    $rows[] = array(
                                        __($index+1),
                                        $editTitle,
                                        $data[$model]['release_page_url'],
                                        str_replace("_"," ",ucfirst($data[$model]['distribution_type'])),
                                        $data[$model]['views'],
                                        $email,
                                        date('d-m-Y', strtotime($data[$model]['created'])),
                                        $actions,
                                    );
                                }
                                unset($checkcart);
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="8">No result found!</td>
                                </tr>
                                <?php 
                        } ?>
                        </tbody>
                   </table>
               <?php }  ?>
                <?php $reports= $this->Custom->getAdditionalClippingReportDetails($prId);
                    if(!empty($reports)){ ?>
                    <div class="ew-title full"> 
                        <div class="row">
                            <div class="col-sm-8"> <?php echo $siteName;?> Additional clipping reports</div>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __("Report name"),
                                __("Action"),
                                __("uploaded date"),
                             ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;

                            ?>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($reports as $key => $report){
                            $url=SITEURL.$report['ClippingAdditionalReport']['path'].'/'.$report['ClippingAdditionalReport']['file_name']."?nocache".rand(0,1000);
                            $actionaddi= '<a href="'.$url.'" class="btn btn-xs btn-info" download="download">Download Report</a>';
                            $rows1 = array();
                                  $rows1[] = array(
                                        ucfirst($report['ClippingAdditionalReport']['file_name']),
                                        $actionaddi,
                                        date($dateformate, strtotime($report['ClippingAdditionalReport']['created'])),
                                    ); 
                            }
                            echo $this->Html->tableCells($rows1, array('class' => 'gradeX'));

                            ?>
                        </tbody>
                   </table>
               <?php } ?>
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
