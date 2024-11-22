<div class="row"> 
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-body"> 
                <div class="dataTable_wrapper">
                  <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $disAppproveReason=""; 
                           // $actioncol=__('Actions');
                            if($status==3 || $status==0 || $status==4){
                                if($status==4){
                                  $disAppproveReason=__('Disapproved Reason');
                                }

                            }
                            $tableHeaders = $this->Html->tableHeaders(array(
                                $this->Paginator->sort("S/N"),
                                $this->Paginator->sort(__("title")),
                                $this->Paginator->sort(__("release_date")),
                                __('Status'),
                                // $disAppproveReason,
                                // $actioncol,
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array as $index => $data) {
                                //pr($data);die;    
                                $actions="";
                                    switch ($data['PressRelease']['status']) {
                                        case '0':
                                        $editbutton='Make Payment';
                                        break;
                                        case '3':
                                        $editbutton='Edit draft PR';
                                        break;
                                        case '4':
                                        $editbutton='Edit PR';
                                        break;
                                        
                                        default:
                                        $editbutton='Edit';
                                        break;
                                    }  

                                    if($data[$model]['status']==4){
                                       $actions = ' '.$this->Html->link(__($editbutton), array('controller' =>"PressReleases", 'action' => 'edit',$data[$model]['id']), array('class' => 'btn btn-xs btn-success')); 
                                    }

                                    if($data[$model]['status']==3){
                                    $status=''; 
                                    
                                    $actions = ' '.$this->Html->link(__($editbutton), array('controller' =>"PressReleases", 'action' => 'add-press-release',$data[$model]['plan_id'],$data[$model]['id']), array('class' => 'btn btn-xs btn-success')); 
                                    }
                                    $title=$this->Html->link($data[$model]['title'], array('controller' =>"PressReleases", 'action' => 'view',$data[$model]['plan_id'],$data[$model]['id']));

                                    $reason='';
                                    if($data[$model]['status']==4){
                                    $reason=(!empty($data[$model]['disapproval_reason']))?"<span class='text-center'>".ucfirst($data[$model]['disapproval_reason'])."</span>":"<span class='text-center'>-</span>";
                                    }
                                    $rows[] = array(
                                        __($index+1),
                                        $title,
                                        date('d-m-Y', strtotime($data[$model]['release_date'])),
                                        $reason,
                                         $this->Custom->getUserStatus($data[$model]['status']),
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
        </div> 
    </div> 
</div>
