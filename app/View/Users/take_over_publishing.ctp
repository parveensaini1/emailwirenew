<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));?> 
 
<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title;?></div></div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p>Please enter company name and submit your request to take over. Emailwire admin will review you request.</p>
                        <?php  //echo $this->Session->flash(); ?>
                    </div>
                </div>
            <?php
            echo $this->Form->create('TakeOverCompany', array('inputDefaults' => array('class' => 'form-control', 'label' => false, 'div' => "form-group"),"id"=>"register_from",'novalidate'));

            echo $this->Form->input("company_id",array("type"=>"hidden","value"=>"","id"=>"company_id"));
            echo $this->Form->input('TakeOverCompany.company_name', array("type" => 'text','maxlength'=>"100",'class'=>'form-control ',"required"=>"required","id"=>"company_name","label"=>"Company name",));

            echo $this->Form->input("comment",array("type"=>"textarea","label"=>"Comment","id"=>"note",'class'=>'form-control ',"div"=>"form-group"));

            echo $this->Form->input('Submit', array("type" => 'submit','id'=>"submit-btn","class"=>"btn btn-primary"));
            echo $this->Form->end();
            ?>
            </div>
            <?php if (!empty($data_array)) {?>
            <div class="panel-body"> 
                <div class="row"><div class="col-sm-12">Your previous request</div> </div>
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <?php
                            $tableHeaders = $this->Html->tableHeaders(array(
                                $this->Paginator->sort("S/N"),
                                $this->Paginator->sort("company_name"),  
                                    ), array(), array('class' => 'sorting'));
                            echo $tableHeaders;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $rows = array();
                            if (count($data_array) > 0) {
                                foreach ($data_array as $index => $data) {
                                    $rows[] = array(
                                        __($index+1),
                                        __($data['TakeOverCompany']['company_name']),  
                                    );
                                }
                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                            } else {
                                ?>
                                <tr>
                                    <td align="center" colspan="3">No result found!</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
             <!--    <div class="row">
                    <?php // echo $this->element('pagination'); ?>
                </div> -->
            </div>
        <?php } ?>
        </div>
    </div>
</div>

 <script type="text/javascript">
    // validate signup form on keyup and submit
    $(document).ready(function(){ 
        $("#register_from").validate({
            debug: false,
            rules: {
                "data[Company][name]": "required",
                "data[StaffUser][last_name]": "required",  
            },
            messages: {
                "data[Company][name]": "Please enter your company name.",
            }
        });
    });

$("#company_name").autocomplete({
    source:'<?php echo SITEURL; ?>ajax/get_compnies',
    select: function( event, ui ) {
        event.preventDefault();
        $("#company_name").val(ui.item.label);
        $("#company_id").val(ui.item.id);
        
    }
});
</script>