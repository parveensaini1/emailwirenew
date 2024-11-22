<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body"> 
                <div class="dataTable_wrapper"> 
                        <div class="head">
                            <?php 
                               echo "<div class='row'>
                                    <div class='col-sm-1 tab'>".$this->Paginator->sort("S/N")."</div>
                                    <div class='col-sm-7 tab'>".$this->Paginator->sort(__("title"))."</div>
                                    <div class='col-sm-2 tab'>".$this->Paginator->sort(__("release_date"))."</div>
                                    <div class='col-sm-2 tab'>".$this->Paginator->sort(__("views"))."</div>
                               </div>"; 

                            ?>    
                        </div>
                        <div class="body-content">
                            <div class="row">
                                <?php 
                                $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array as $index => $data) {

                                    $moredetail="";
                                     if (count($data['ClippingReport']) > 0) {
                                    $moredetail=' <a class="btn btn-info" data-toggle="collapse" href="#collapse-'.$data[$model]['id'].'" role="button" aria-expanded="false" aria-controls="collapse-'.$data[$model]['id'].'">More details</a>';
                                     }
                                $title=$this->Html->link($data[$model]['title'], array('controller' => $controller, 'action' => 'view',$data[$model]['plan_id'],$data[$model]['id']));

                                    $views=($data[$model]['views']>0)?$data[$model]['views']:"-";
                                ?>
                                <div class="col-lg-12">
                                     <div class="row">
                                    <div class="col-sm-1"><?php echo __($index+1);?></div>
                                    <div class="col-sm-7"><?php echo __($title)."<br/>".$moredetail;?></div>
                                    <div class="col-sm-2"><?php echo date('d-m-Y', strtotime($data[$model]['release_date']));?></div>
                                    <div class="col-sm-2"><?php echo __("<span class='text-center'>".$views."</span>");?></div>
                                   
                                        
                                    </div>
                                </div>
                                <div id="<?php echo "collapse-".$data[$model]['id']; ?>" class="col-sm-12 collapse">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <?php
                                        $tableHeaders = $this->Html->tableHeaders(array(
                                            __("S/N"),
                                            __("Site name"),
                                            __("distribution_type"),
                                            __("Distribut date"),
                                                ), array(), array('class' => 'sorting'));
                                        echo $tableHeaders;
                                        ?>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rows = array();
                                        if (count($data['ClippingReport']) > 0) {
                                            foreach ($data['ClippingReport'] as $index => $clipreport) {
                                                $sitename="<a target='_blank' href='".$clipreport['domain']."'>".$clipreport['site_name']."</a>";
                                                 $rows[] = array(
                                                    __($index+1),
                                                    $sitename,
                                                    ucfirst(str_replace("_"," ",$clipreport['distribution_type'])),
                                                    date('d-m-Y', strtotime($clipreport['created'])),
                                                );
                                            }
                                            unset($checkcart);
                                            echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                                        } else {
                                            ?>
                                    <tr>
                                        <td align="center" colspan="4">No result found!</td>
                                    </tr>
                                    <?php 
                                    } ?>
                                    </tbody>
                                </table>  

                                </div>


                            <?php   }
                            } ?>        
                            </div>
                                
                            </div>
 
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

<style type="text/css">
    li{list-style: none;}

</style>