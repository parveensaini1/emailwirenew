<style>
.card-default .cake-error{
    display:none;   
 }
}
</style>
<?php echo $this->element('search'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">  
                <div class="dataTable_wrapper table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php 
                            $tableHeaders = $this->Html->tableHeaders(array(
                                 __('S/N'), 
                                 // __('Id'),
                                __("Title"),
                                __("Release date"),
                                __("Posted date"), 
                                __('Read'),
                                __('Shared'),
                                __('Click Through'), 
                                __("Author/Client"),
                                __('Action'),
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
                                if ($role_id==1) {
                                $actions .= ' ' . $this->Html->link(__("Disapprove"), array(
                                            'controller' => $controller,
                                            'action' =>"inactive_pr",
                                            $data[$model]['id'],  
                                           'pending',
                                                ), array('class' => 'btn btn-xs btn-danger'));
                                }

                                $actions .= $this->Html->link(__('Edit'), array(
                                            'controller' => $controller,
                                            'action' =>"add",$data[$model]['language'],$data[$model]['plan_id'],
                                            $data[$model]['id'],
                                     ), array('class' => 'btn btn-xs btn-primary'));

                                // $actions .= ' ' . $this->Html->link(__('Trash'), array(
                                //                 'controller' => $controller,
                                //                 'action' => 'move_trash',
                                //                 $data[$model]['id'],
                                //                 'disapproved',
                                //                 'token' => $this->params['_Token']['key'],), array('class' => 'btn btn-xs btn-danger'));

                                $actions .= ' ' . $this->Html->link(__('Trash'), array(
                                                'controller' => $controller,
                                                'action' => 'move_trash',
                                                $data[$model]['id'],
                                                'disapproved'
                                                ), array('class' => 'btn btn-xs btn-danger'));

                                $actions .= ' ' . $this->Html->link(__("View Report"), array(
                                            'controller' => 'ClippingReports',
                                            'action' =>"viewreport",
                                            $data[$model]['id'],  
                                           'approved',
                                                ), array('class' => 'btn btn-xs btn-info'));

                                // $actions .= ' ' . $this->Html->link(__("Download Report"), array(
                                //             'controller' => $controller,
                                //             'action' =>"download",
                                //             $data[$model]['id'],  
                                //            'approved',
                                //                 ), array('class' => 'btn btn-xs btn-info'));
                                

                                // $actions .= ' ' . $this->Html->link(__("Send in mail"), array('controller' =>"sendy",'action' =>"sendinmail",$data[$model]['id']),array('class' => 'btn btn-xs btn-primary sendinmail'));
 
                                // $actions .= ' ' . $this->Html->link(__("Mail report"), array('controller' =>"sendy",'action' =>"emailreport",$data[$model]['id']),array('class' => 'btn btn-xs btn-default sendinmail'));

                                if(isset($data['Distribution']) && !empty($data['Distribution']) && $data['Distribution'][0]['id'] == '8'){
                                    
                                    $sent=$this->Sendy->checkSentMail($data[$model]['staff_user_id'],$data[$model]['id']);

                                    if(!empty($sent)){
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
                                              $actions .= '<br/> ' . $this->Html->link(__("Mail report"), array('controller' =>"Sendy",'action' =>"emailreport",$sent['userID'],$sent['id']),array('class' => 'btn btn-xs btn-default sendinmail'));
                                        }
                                        
                                    }else{
                                    $actions .= '<br/>'.$this->Html->link(__("Send in mail"), array('controller' =>"sendy",'action' =>"sendinmail",$data[$model]['id']),array('class' => 'btn btn-xs btn-primary sendinmail'));
                                    }

                                }
                                 $trackingHtml="<img src=".SITEFRONTURL."rss/gif?v=".$data[$model]['id'].">";
                                $actions .="<br/><br/><a class='btn btn-xs btn-primary' href='javascript:void(0)' onclick='clipboardprcode(".$data[$model]['id'].")' id='trackingcodebtn-".$data[$model]['id']."'>Copy Traking code</a> <input style='opacity: 0;' id='copytrackingcode-".$data[$model]['id']."' type='text' value='$trackingHtml'>";
                                    
                                $approved_url    =   SITEURL.'PressReleases/view/'.$data[$model]['id'].'/approved';

                                    $title='<a href="'.$approved_url.'">'.$data[$model]['title'].'</a>';
                                    if ($role_id==1) {
                                     $title .="<br/><br/> Approved By : ".$this->Custom->getUserNameById($data['PressRelease']['approved_by']); 
                                    }

                                    $viewscount=($data[$model]['views']>0)?$data[$model]['views']:"-";

                                    $socialShareCount=(!empty($data['0']['socialShareCount']))?$data['0']['socialShareCount']:"0";
                                    $networkFeedCount=(!empty($data['0']['networkFeedCount']))?$data['0']['networkFeedCount']:"0";
                                


                                    $rows[] = array(
                                        __($index+1),
                                        __($title),
                                        __(date('d F Y',strtotime($data[$model]['release_date']))), 
                                        __(date('d F Y',strtotime($data[$model]['created']))), 
                                         __($viewscount),
                                         $socialShareCount,
                                         $networkFeedCount,
                                         '<a href="'.SITEURL.'PressReleases/clientReleases/'.$data['StaffUser']['id'].'">'.$data['StaffUser']['first_name'].' '.$data['StaffUser']['last_name'].'</a>',
                                        $actions,
                                    );
                                }
                             echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            }else{ ?>
                            <td align="center" colspan="8">
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
                <!-- <div class="row">
                    <?php //echo $this->element('pagination'); ?>
                </div> -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<style type="text/css">
    .sendinmail{margin-top: 10px;}
</style>