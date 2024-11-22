<?php
echo $this->Html->css('/acl/css/acl');
echo $this->Html->script('/acl/js/acl');
?> 
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-body">
                <?php include 'menu.ctp'; ?>
                <div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <?php foreach ($function_arr as $controller_detail) { ?>
                        <div class="panel box box-primary" id="main_div_<?php echo $controller_detail['StaffAction']['id']; ?>">
                            <div class="box-header with-border">
                                <h4 class="box-title col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $controller_detail['StaffAction']['name']; ?>">
                                                <span id="controler_<?php echo $controller_detail['StaffAction']['id']; ?>"><?php echo $controller_detail['StaffAction']['label']; ?></span> 
                                            </a>
                                        </div>  
                                    </div> 
                                </h4>
                            </div>
                            <div id="<?php echo $controller_detail['StaffAction']['name']; ?>" class="panel-collapse collapse">
                                <div class="box-body">
                                    <div class="table-responsive" id="list_table_<?php echo $controller_detail['StaffAction']['id']; ?>">
                                        <?php include 'permission_list.ctp'; ?>
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