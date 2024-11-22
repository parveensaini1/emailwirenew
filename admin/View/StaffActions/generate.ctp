<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default panel-block"> 
            <div class="row table-header-row">
                <div class="col-sm-12">
                    <div class="panel-heading datatable-heading">
                        <a class="btn btn-primary" href="<?php echo SITEURL; ?>admin/actions"><i class="icon-list"></i> Generate Action</a> 
                        <a class="btn btn-primary" href="/PhpProject2/admin/users/add"><i class="icon-plus-sign-alt"></i> Add New User</a>
                    </div>
                </div>            
            </div>
            <div id="accordion" class="panel-group">
                <?php
                foreach ($function_arr as $controller => $controller_detail) {
                    ?>
                    <div class="panel panel-default" id="main_div_<?php echo $controller_detail['id']; ?>">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a href="#<?php echo $controller ?>" data-parent="#accordion" data-toggle="collapse" class="class-label accordion-toggle collapsed">
                                    <span id="controler_<?php echo $controller_detail['id']; ?>"><?php echo $controller_detail['label']; ?></span> 
                                </a>
                                <div style="float: left;padding-right: 5px;" >
                                    <a data-id="<?php echo $controller_detail['id']; ?>" data-class="<?php echo $controller; ?>" class="generate-action btn btn-xs btn-info" href="javascript:void(0);">Generate Action</a>
                                </div>
                                <div style="float: left;padding-right: 5px;" >
                                    <a data-id="<?php echo $controller_detail['id']; ?>" data-value="<?php echo $controller_detail['label']; ?>" class="class-label-btn btn btn-xs btn-success" href="javascript:void(0);">Change Label</a>
                                </div>
                                <div class="delete-div" ><a data-id="<?php echo $controller_detail['id']; ?>" class="class-deleet btn btn-xs btn-danger" href="javascript:void(0);">Delete</a>
                                    <span class="main_loader" id="main_loader_<?php echo $controller_detail['id']; ?>"><?php //echo $this->Html->image('spinners/282.gif');       ?></span>
                                </div>
                                <div style="display: none;" id="controller-text_<?php echo $controller_detail['id']; ?>" class="controller-text group-text"> 
                                    <input data-id="<?php echo $controller_detail['id']; ?>" class="change-controller form-control" value="<?php echo $controller_detail['label']; ?>">
                                </div> 
                            </h4>
                        </div>
                        <div class="panel-collapse collapse" id="<?php echo $controller; ?>" style="height: 0px;">
                            <div class="panel-body">
                                <div class="table-responsive" id="list_table_<?php echo $controller_detail['id']; ?>">
                                    <?php include 'edit_list.ctp'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?> 
            </div>
        </div> 
    </div>
</div>
<style>
    .delete-div{
        float: left;
    }
    .delete-div a{
        float: left;
    }
    .main_loader{
        float: left;
        margin-left: 5px;
    }
    .main_loader img{
        height: 20px;   
    }
    .class-label{    
        float: left;
        width: 75%;
    }

    .group-text {
        float: left;
        margin-right: 10px;
        width: 86%;
    }
    .label-td img{
        float: left;
    }
    .controller-text{
        float: right;
        margin-top: 10px;
        width: 14%;
    }
</style>
<script>
    $('body').on("click", '.class-label-btn', function() {
        $("#controller-text_" + $(this).attr('data-id')).toggle("slow");
    });

    $('body').on("blur", '.change-controller', function() {  
        var id = $(this).attr('data-id');
        var value = $(this).val();
        var datastring = "id=" + id + "&label=" + value;
        $("#main_loader_" + id).html('<img src="' + SITEURL + 'img/spinners/282.gif" style="float: right" />').fadeIn();
        $.ajax({
            type: 'POST',
            url: SITEURL + 'admin/actions/change_label',
            data: datastring,
            success: function(data) {
                $("#controler_" + id).html(value);
                $("#controller-text_" + id).fadeOut(300);
                $("#main_loader_" + id).html('<span style="float: right" class="btn btn-xs btn-success">Updated</span>').fadeOut(3000);
            }});
    });
    
    $('body').on("blur", '.action_label', function() { 
        var id = $(this).attr('data-id');
        var datastring = "id=" + id + "&label=" + $(this).val();
        $("#loader_" + id).html('<img src="' + SITEURL + 'img/spinners/282.gif" />').fadeIn();
        $.ajax({
            type: 'POST',
            url: SITEURL + 'admin/actions/change_label',
            data: datastring,
            success: function(data) { 
		    $("#loader_" + id).html('<span class="btn btn-xs btn-success">Updated</span>').fadeOut(3000); 
            }});
    });

    $('body').on("click", '.delete_action', function() {
        var id = $(this).attr('data-id');
        var datastring = "id=" + id;
        $("#loader2_" + id).html('<img src="' + SITEURL + 'img/spinners/282.gif" />');
        $.ajax({
            type: 'POST',
            url: SITEURL + 'admin/actions/delete_action',
            data: datastring,
            success: function(data) {
                $("#tr_" + id).remove();
                $.pnotify({
                    title: "Action successfully deleted",
                    type: "success",
                    history: false,
                });
            }});
    });

    $('body').on("click", '.class-deleet', function() {
        var id = $(this).attr('data-id');
        var datastring = "id=" + id;
        $("#main_loader_" + id).html('<img src="' + SITEURL + 'img/spinners/282.gif" />');
        $.ajax({
            type: 'POST',
            url: SITEURL + 'admin/actions/delete_class',
            data: datastring,
            success: function(data) {
                $("#main_div_" + id).remove();
                $.pnotify({
                    title: "Action successfully deleted",
                    type: "success",
                    history: false,
                });
            }});
    });


    $('body').on("click", '.generate-action', function() {
        var id = $(this).attr('data-id');
        var class_name = $(this).attr('data-class');
        var datastring = "class=" + class_name;
        $("#main_loader_" + id).html('<img src="' + SITEURL + 'img/spinners/282.gif" style="float: right" />').fadeIn();
        $.ajax({
            type: 'POST',
            url: SITEURL + 'admin/actions/generate_action',
            data: datastring,
            success: function(data) {
                $("#list_table_" + id).html(data);
                $('#' + class_name).collapse('show');
                $("#main_loader_" + id).html('<span style="float: right" class="btn btn-xs btn-success">Updated</span>').fadeOut(3000);
            }});
    });

</script>