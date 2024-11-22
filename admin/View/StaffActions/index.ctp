<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-body">
                <?php include 'menu.ctp'; ?>
                <div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <?php
                    foreach ($function_arr as $controller => $controller_detail) {
                        ?>
                        <div class="panel box box-primary" id="main_div_<?php echo $controller_detail['StaffAction']['id']; ?>">
                            <div class="box-header with-border">
                                <h4 class="box-title col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $controller_detail['StaffAction']['name']; ?>">
                                                <span id="controler_<?php echo $controller_detail['StaffAction']['id']; ?>"><?php echo $controller_detail['StaffAction']['label']; ?></span> 
                                            </a>
                                        </div> 
                                        <div class="col-md-3">

                                        </div>
                                        <div class="col-md-3">
                                            <div style="float: left;padding-right: 5px;" >
                                                <a data-id="<?php echo $controller_detail['StaffAction']['id']; ?>" data-class="<?php echo $controller_detail['StaffAction']['name']; ?>" class="generate-action btn btn-xs btn-info" href="javascript:void(0);">Generate Action</a>
                                            </div>
                                            <div style="float: left;padding-right: 5px;" >
                                                <a data-id="<?php echo $controller_detail['StaffAction']['id']; ?>" data-value="<?php echo $controller_detail['StaffAction']['label']; ?>" class="class-label-btn btn btn-xs btn-success" href="javascript:void(0);">Change Label</a>
                                            </div>
                                            <div class="delete-div" >
                                                <a data-id="<?php echo $controller_detail['StaffAction']['id']; ?>" class="update-status btn btn-xs <?php echo ($controller_detail['StaffAction']['status']) ? 'btn-danger' : 'btn-success'; ?>" href="javascript:void(0);">
                                                    <span class="span_label"><?php echo ($controller_detail['StaffAction']['status']) ? 'Disable' : 'Enable'; ?></span>
                                                    <span class="span_ldr" style="padding-left: 5px;display: none;"><?php echo $this->Html->image('spinners/loader2.gif'); ?></span>
                                                </a>
                                            </div>                                            
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="display: none;" id="controller-text_<?php echo $controller_detail['StaffAction']['id']; ?>" class="controller-text group-text"> 
                                                <input data-id="<?php echo $controller_detail['StaffAction']['id']; ?>" class="change-label form-control" value="<?php echo $controller_detail['StaffAction']['label']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </h4>
                            </div>
                            <div id="<?php echo $controller_detail['StaffAction']['name']; ?>" class="panel-collapse collapse">
                                <div class="box-body">
                                    <div class="table-responsive" id="list_table_<?php echo $controller_detail['StaffAction']['id']; ?>">
                                        <?php include 'edit_list.ctp'; ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <?php } ?>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col --> 
</div>

<?php
echo $this->Html->css('/acl/css/acl');
echo $this->Html->script(array('/acl/js/acl', '/acl/js/bootbox'));
?> 