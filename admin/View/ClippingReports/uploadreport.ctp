<div id="main-content" class="row card">
    <div id="content" class="col-sm-12 card-body content">
        <?php 
        echo "<b>Press Release Id-</b>".$pId;
        echo "<br>";
        echo "<b>Created By-</b>".$userData['StaffUser']['first_name']." ".$userData['StaffUser']['last_name'];
        echo "<br>";

        echo "<b>Email- </b>".$userData['StaffUser']['email'];
        echo "<br>";

        echo "<b>Approved By-</b> ".$approvedUser['StaffUser']['first_name']." ".$approvedUser['StaffUser']['last_name'];
        echo "<br>";
        echo "<div class='viewreport'>".$this->Html->link(__('View Clipping Report'), array('controller' => $controller, 'action' => 'viewclippingreport',$pId), array('class' => 'btn btn-xs btn-info')) ."</div>";
        echo "<br>";
        echo "<h3>PR Title- ".$prTitle."</h3>";?>
      
    </div> 
</div> 
  <div class="card card-default"> 
            <div class="card-body">
                <div class="dataTable_wrapper">
                <?php
                echo $this->Form->create($model, array('id' => 'clipreport_form', 'type' => 'file', 'novalidate' => 'novalidate','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                // echo $this->Form->input('xmlurl', array('class' => 'form-control ', 'id' => 'xmlname', 'type' => 'text',"required"=>"required","label"=>"Report url",'onchange'=>'disable_csv(this.value)'));
                // echo '<strong class="text-center"> --OR-- </strong>';
                echo $this->Form->input('csvurl', array('class' => 'form-control ', 'id' => 'csvname', 'type' => 'file',"required"=>"required","label"=>"CSV file",'onchange'=>'disable_xml(this.value)'));

                ?>
                    <!-- <label class="control-label" for="csv_file"><em><?php // echo _('CSV format');?>:</em></label>    -->                 
                    <table class="table table-bordered table-striped table-condensed" style="width: 500px;">
                        <tbody>
                        <tr>
                            <th><?php echo _('Released Page url');?></th>
                            <th><?php echo _('Website name');?></th>
                            <th><?php echo _('Views');?> (Optional)</th> 

                        </tr>
                            <tr>
                              <td>http://www.example.com/news</td>
                              <td>Email wire</td>
                              <td>10</td>
                            </tr>
                            <tr>
                              <td>http://www.example1.com/news</td>
                              <td>Google</td>
                              <td>50</td>
                            </tr>
                          </tbody>
                    </table>
                    <p>Note: Click here to <a href="<?php echo SITEURL.'ClippingReports/downloads/clipping_report_sample.csv/clipping_reports'; ?>" download><b>Download Dummy CSV file</b></a>.</p>
                   <?php  echo $this->Form->submit('Submit', array('class' => 'submitbtn btn btn-info', 'div' => false)); ?>
              	<?php $this->Form->end();?>
                </div>
            </div>
        </div>
<script type="text/javascript">
    
    $(document).ready(function(){
       $("#clipreport_form").submit(function(){
        $(".submitbtn").attr("disabled","disabled");
       }); 
    });

function disable_csv(value=null){
    if(value!=''){
        $('#xmlname').prop("disabled", false);
        $('#csvname').prop("disabled", true);
    }else{
        $('#csvname').prop("disabled", false);
        $('#xmlname').prop("disabled", false);
    }
}
function disable_xml(value){
    if(value!=''){
        $('#csvname').prop("disabled", false);
        $('#xmlname').prop("disabled", true);
    }else{
        $('#csvname').prop("disabled", false);
        $('#xmlname').prop("disabled", false);
    }
}
</script>