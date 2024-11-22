<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?> - <?php echo $pr_data['PressRelease']['title']; ?></div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"> 
                <div class="dataTable_wrapper">
                    <div class="ew-title full"> 
                        <div class="row">
                            <div class="col-sm-4"> <?php echo $siteName;?></div>
                                <div class="col-lg-8 text-right">
                                
                                <?php 
                                echo " ".$this->Html->link(__('Download Report'), array('controller' => $controller, 'action' => 'download',$prId,rand(0,999)),array('class' => 'btn btn-xs btn-info'));

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
                            $userId=$pr_data['PressRelease']['staff_user_id'];
                            $rows1 = array();
                           
                                  $rows1[] = array(
                                        $siteName,
                                        $pr_data['PressRelease']['views'],
                                        date($dateformate, strtotime($pr_data['PressRelease']['release_date'])),
                                    ); 
                                echo $this->Html->tableCells($rows1, array('class' => 'gradeX'));
                            ?>
                        </tbody>
                   </table>

                   <div class="ew-title full row">
                        <div class="col-lg-6">
                            Email wire Network  
                        </div>

                        <div class="col-lg-6">
                            <div class="row">
                            <?php echo $this->Form->create('Search', array('url'=>SITEURL.'users/viewreport/'.$prId.'/','type' => 'get', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control form-inline', 'required' => false))); ?>

                                <div class="col-sm-2">Search:</div>
                                <?php if($this->request->query('type')!='mail_feed'){ ?>
                                <div class="col-sm-4">
                                     
                                    <?php
                                    $name=(!empty($this->request->query('sname')))?$this->request->query('sname'):"";
                                    echo $this->Form->input('sname',['placeholder'=>'Please enter site name..','label'=>false,'value'=>$name]);?>
                                </div>
                                <?php } ?>
                        
                                <div class="col-sm-4">
                                <?php 
                                $selected=(!empty($this->request->query('type')))?$this->request->query('type'):"";

                                $champ= $this->Sendy->checkSentMail($userId,$prId);
                               
                                $champId=(!empty($champ))?$champ['id']:"";

                                $options=['rss_feed'=>'Rss feed','js_feed'=>'Js feed',"mail_feed"=>'Mail feed','network_feed'=>'Network feed']; 
                                echo $this->Form->input('cid', ['type' => 'hidden','value'=>$champId]);
                                echo $this->Form->input('type', array('selected' => $selected, 'id' => 'type', 'options' => $options, 'empty' => 'Select distribution type', 'class' => 'form-control',"label"=>false)); ?>
                               
                               </div> 
                               <div class="col-sm-2">
                                <?php echo $this->Form->submit('Search', array('class' => 'btn btn-info', 'div' => false)); ?>
                               </div> 
                               <?php echo $this->Form->end();?>
                            </div>
                        </div>
                   </div> 
                   <?php if($selected=='mail_feed'){ 
                        if(!empty($data_array)){
                            $createdfrom=(!empty($champ))?$champ['createdfrom']:"";
                            echo '<div class="$createdfrom">';
                                include 'sentmailreportmenu.ctp';
                                if($createdfrom=='fronted'&&$msts=='graph'){
                                    $userId=$champ['userID'];
                                    include 'sentmailreport.ctp';
                                }else{ 
                                    include 'sendy_subscriber_report_list.ctp';
                                }
                            echo '</div>';
                        }else{
                            echo "<h3 class='text-center'>Record not found.</h3>";
                        }    
                        
                        
                      }else{?>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                __("S/N"),
                                __("Site name"),
                                __("Published url"),
                                __("Distribution type"),
                                __("Impressions"),
                                __("Email"),
                                __("Distribution date"),
                                
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
                                    if(in_array($data[$model]['distribution_type'],['network_feed','mail_feed'])&&!empty($data[$model]['email'])){
                                         $email= $this->Post->getEmailforClippingReport($data[$model]['email']);
                                     }
                                    $release_page_url="<a target='_blank' href=".$data[$model]['release_page_url'].">".$data[$model]['release_page_url']."</a>";
                                    $rows[] = array(
                                        __($index+1),
                                        $data[$model]['site_name'],
                                        $release_page_url,
                                        str_replace("_"," ",ucfirst($data[$model]['distribution_type'])),
                                        $data[$model]['views'],
                                        $email,
                                        date($dateformate, strtotime($data[$model]['created'])),
                                    );
                                }
                                unset($checkcart);
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="5">No result found!</td>
                                </tr>
                                <?php 
                        } ?>
                        </tbody>
                    </table>
                    <?php } ?>

                    <?php $reports= $this->Custom->getAdditionalClippingReport($pr_data['PressRelease']['id']);
                    if(!empty($reports)){
                    ?>
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
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
