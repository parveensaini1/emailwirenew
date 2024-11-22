<?php if (($controller == 'ClippingReports' && $action == 'viewclippingreport')) { ?>
    <div class="btn-group">
        <button type="button" class="btn btn-success">Upload Report By</button>
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu" style="">
            <?php 
            $uploadreport=$this->Html->link(__('Report(2-col)'), array('controller' => $controller, 'action' => 'uploadreport',$prId), array('class' => 'dropdown-item'));
            $uploadpdf=$this->Html->link(__('Additional XML'), array('controller' => $controller, 'action' =>'uploadadditionalxml',$prId), array('class' => 'dropdown-item'));
            $addmanually=$this->Html->link(__('Add Manuallys'), array('controller' => $controller, 'action' => 'add_manually',$prId), array('class' => 'dropdown-item'));
            // $editmanually=$this->Html->link(__('Edit Report'), array('controller' => $controller, 'action' => 'edit',$prId), array('class' => 'btn btn-xs btn-info'));
            $uploadcsv=$this->Html->link(__('Upload CSV(5-col)'), array('controller' => $controller, 'action' => 'uploadcsv',$prId), array('class' => 'dropdown-item'));
            $uploadreportByApi=$this->Html->link(__('Upload Report By API'), array('controller' => $controller, 'action' => 'uploadClippingReportByJson',$prId), array('class' => 'dropdown-item'));
            $GMNReport=$this->Html->link(__('Generate GNM Report'), array('controller' => $controller, 'action' => 'updateClippingByGroupMediaNetwork',$prId), array('class' => 'dropdown-item'));

          //$addmanually.' '. .$csvClippingReport
            echo $uploadreport.' '.$uploadcsv." ".$uploadpdf." ".$uploadreportByApi." ".$GMNReport." ";
            
            ?>
             
        </div>
    </div>
<?php } ?>