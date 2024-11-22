<div id="main-content" class="card row">
    <div id="content" class="card-body col-sm-12 content">
        <?php 
        echo "<b>Press Release Id-</b>".$pr_id;
        echo "<br>";
        echo "<b>Created By-</b>".$user_datas['StaffUser']['first_name']." ".$user_datas['StaffUser']['last_name'];
        echo "<br>";

        echo "<b>Email- </b>".$user_datas['StaffUser']['email'];
        echo "<br>";

        echo "<b>Approved By-</b> ".$approved_datas['StaffUser']['first_name']." ".$approved_datas['StaffUser']['last_name'];
        echo "<br>";
        echo "<div class='viewreport'>".$this->Html->link(__('View Clipping Report'), array('controller' => $controller, 'action' => 'viewclippingreport',$pr_id), array('class' => 'btn btn-xs btn-info')) ."</div>";
        echo "<br>";
        echo "<h3>PR Title- ".$pr_title."</h3>";?>
        
    </div>
</div>
<div class="card card-default"> 
            <div class="card-body">
                <div class="dataTable_wrapper">
                <?php
                    echo $this->Form->create('', array('id' => 'clipreport_form', 'type' => 'file', 'novalidate' => 'novalidate','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                    echo $this->Form->input('xml_url', array('class' => 'form-control ','id' =>'xml_url','type' => 'url',"required"=>"required","label"=>"Xml url"));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                  	$this->Form->end();
                ?>
                </div>
                <div>
                    <?php
                    if(isset($clipping_additional_xml_data) && !empty($clipping_additional_xml_data)){
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
                                            <tr class="gradeX"><td><?php echo $i; ?></td>
                                                <td>
                                                    <a href="<?php echo $xml_data['ClippingAdditionalXml']['xml_url']; ?>"><?php echo $xml_data['ClippingAdditionalXml']['xml_url']; ?></a>
                                                </td>
                                                <td><?php echo date('F d, Y',strtotime($xml_data['ClippingAdditionalXml']['created'])); ?></td>
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
<script type="text/javascript">
    $("#clipreport_form").validate();
</script>