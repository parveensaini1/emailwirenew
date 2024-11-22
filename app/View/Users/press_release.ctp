<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title;?></div></div>
    <?php  if (count($data_array) > 0) {?>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"> 
                <div class="dataTable_wrapper">
                  <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $disAppproveReason=""; 
                            $actioncol=__('Actions');
                            $tableHeading=array("Title","Release Date",$actioncol,);
                            if($status==1){
                                $tableHeading=array("Title","Read","Shared","Click Through","Release Date",$actioncol,);
                            }else if($status==4){
                                $disAppproveReason=__('Disapproved Reason');
                                $tableHeading=array("Title","Release Date",$disAppproveReason,$actioncol,);
                            }
                            $tableHeaders = $this->Html->tableHeaders($tableHeading, array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                           
                                foreach ($data_array as $index => $data) {
                                    
                                $actions="";
                                    switch ($data['PressRelease']['status']) {
                                        case '3':
                                        $editbutton='Edit draft PR';
                                        case '4':
                                        $editbutton='Edit PR';
                                        break;
                                        default:
                                        $editbutton='';
                                    } 
                                    $countcart=$this->Custom->countprcart($user_id,$data[$model]['plan_id'],$data[$model]['id']); 
                             
                                    if($data[$model]['status']==0 &&$data[$model]['is_paid']==1&&$countcart==1){
                                         $actions = ' '.$this->Html->link(__("Make Payment"), array('controller' => $controller, 'action' => 'makepayment',$data[$model]['plan_id'],$data[$model]['id']), array('class' => 'btn btn-sm btn-success'));
                                    }

                                    if($data[$model]['status']!=5){
                                        if($data[$model]['status']!=1){
                                           $actions = (!empty($editbutton))?' '.$this->Html->link(__($editbutton), array('controller' => $controller, 'action' => 'add-press-release',$data[$model]['language'],$data[$model]['plan_id'],$data[$model]['id']), array('class' => 'btn btn-sm btn-success')):""; 

                                            // if($data[$model]['status']!=3){ 
                                           		// $actions.=' '.$this->Html->link(__('Trash'), array('controller' => $controller,'action' => 'movetrash',$data[$model]['id']),array('class' => 'btn btn-sm btn-danger'));
                                            // }
                                        }
                                        $actions.=' '.$this->Html->link(__('Trash'), array('controller' => $controller,'action' => 'movetrash',$data[$model]['id']),array('class' => 'btn btn-sm btn-danger'));
                                    }else{
                                    $actions = ' '.$this->Html->link(__("Restore PR"), array('controller' => $controller, 'action' => 'restorepr',$data[$model]['id'],$data[$model]['old_status']), array('class' => 'btn btn-sm btn-success')); 

                                    $actions.=' '.$this->Html->link(__('Permanent delete'), array('controller' => $controller,'action' =>'deletepr',$data[$model]['id']),array('class' => 'btn btn-sm btn-danger'));
                                    }



                                /*if($status==1&&isset($data['Distribution']) && !empty($data['Distribution']) && $data['Distribution'][0]['id'] == '8'){
                                    $sent=$this->Sendy->checkSentMail($user_id,$data[$model]['id']);

                                    if(!empty($sent)){
                                        if($sent['createdfrom']!='admin'){
                                             $timezone = stripslashes($sent['timezone']);
                                                $send_date=$sent['send_date'];
                                                $recipients=$sent['recipients'];
                                                $to_send=$sent['to_send'];
                                            if($sent['sent']!=''){

                                                if($recipients >=$to_send){
                                                    $sent_to_all = true;
                                                }else{
                                                    if($to_send==NULL)
                                                        $sent_to_all = true;
                                                    else
                                                        $sent_to_all = false;
                                                }
                                            }else{
                                                $sent_to_all = false;
                                                 $actions .='<span class="label label-warning">'._('Sending').'</span>';
                                            }


                                            $label='<span class="label label-warning">'._('Sent').'</span>';
                                            if(!$sent_to_all){
                                                    //if sending incomplete
                                                    if($recipients<$to_send){
                                                        if($send_date!='0' &&$timezone!='0'){
                                                            $label='<span class="label label-warning">'._('Sending').'</span>';
                                                        }else{
                                                            $label='<span class="label label-warning">'._('Preparing').'</span>';
                                                        }
                                                    }
                                            }

                                            if($sent_to_all){
                                                $actions .= ' ' . $this->Html->link(__("View sent report"), array('controller' =>"users",'action' =>"sentmailreport",$sent['userID'],$sent['id']),array('class' => 'btn btn-sm btn-primary sendinmail'));
                                            }

                                       } 
                                        
                                    }else{
                                      $actions .= ' ' . $this->Html->link(__("Send in mail"), array('controller' =>"users",'action' =>"sendinmail",$data[$model]['id']),array('class' => 'btn btn-sm btn-primary sendinmail'));
                                    }
                                }*/
                                    

                                    $title=$this->Html->link($data[$model]['title'], array('controller' => $controller, 'action' => 'view',$data[$model]['plan_id'],$data[$model]['id']));

                                    $reason='';
                                    if($data[$model]['status']==4){
                                     $reason=(!empty($data[$model]['disapproval_reason']))?"<span class='text-center'>".ucfirst($data[$model]['disapproval_reason'])."</span>":"<span class='text-center'>-</span>";
                                    }
                                    if($status==4){
                                        $rows[] = array(
                                            $title,
                                            date($dateformate, strtotime($data[$model]['release_date'])),
                                            $reason,
                                            $actions,
                                        );
                                    }else if($status==1){
                                        $socialShareCount=(!empty($data['0']['socialShareCount']))?$data['0']['socialShareCount']:"0";
                                        $networkFeedCount=(!empty($data['0']['networkFeedCount']))?$data['0']['networkFeedCount']:"0";
                                        $rows[] = array(
                                            $title,
                                            $data[$model]['views'],
                                            $socialShareCount,
                                            $networkFeedCount,
                                            date($dateformate, strtotime($data[$model]['release_date'])),
                                            $actions,
                                        );
                                    }else{
                                        $rows[] = array(
                                            $title,
                                            date($dateformate, strtotime($data[$model]['release_date'])),
                                            $actions,
                                        );
                                    }
                                }
                                unset($checkcart);
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                           ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <?php echo $this->element('pagination'); ?>
                </div>
            </div> 
        </div> 
    </div> 
<?php }else{  echo $this->Custom->getRecordNotFoundMsg();} ?>
</div>
