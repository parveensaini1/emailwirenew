<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?php echo "<b>Press Release id-</b> " . $prId; ?> <?php echo "<b class='ml-4'>Approved By-</b> " . $approvedUser['StaffUser']['first_name'] . " " . $approvedUser['StaffUser']['last_name']; ?></h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm text-right">
                        <?php echo ucfirst($prData['PressRelease']['title']); ?>
                        <?php // echo  $this->Html->link(__('Edit Clipping Report'), array('controller' => $controller, 'action' => 'edit',$prId), array('class' => 'btn btn-xs btn-info')); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="main-content" class="col-12">



    <div class="card card-default">
        <div class="card-body">
            <div class="dataTable_wrapper">
                <?php
                echo $this->Form->create('', array('id' => 'clipreport_form', 'type' => 'file', 'novalidate' => 'novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                echo $this->Form->input('api_url', array('class' => 'form-control ', 'id' => 'xml_url', 'type' => 'url', "required" => "required", "label" => "Api url"));
                echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                $this->Form->end();
                ?>
            </div>
            <div>
                <?php
                if (isset($clipping_additional_xml_data) && !empty($clipping_additional_xml_data)) {
                    $i = 0;
                    foreach ($clipping_additional_xml_data as $key => $xml_data) {
                        $i++;
                ?>
                        <div class="card-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th class="sorting">S/N</th>
                                            <th class="sorting">XML URL</th>
                                            <th class="sorting">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="gradeX">
                                            <td><?php echo $i; ?></td>
                                            <td>
                                                <a href="<?php echo $xml_data['ClippingAdditionalXml']['api_url']; ?>"><?php echo $xml_data['ClippingAdditionalXml']['xml_url']; ?></a>
                                            </td>
                                            <td><?php echo date('F d, Y', strtotime($xml_data['ClippingAdditionalXml']['created'])); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#clipreport_form").validate();
    $(document).ready(function(){
       $("#clipreport_form").submit(function(){
        $(".submitbtn").attr("disabled","disabled");
       }); 
    });

</script>