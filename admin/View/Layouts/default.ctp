<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title_for_layout; ?></title>
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo SITEFRONTURL.'img/favicon';?>/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo SITEFRONTURL.'img/favicon';?>/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo SITEFRONTURL.'img/favicon';?>/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo SITEFRONTURL.'img/favicon';?>/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo SITEFRONTURL.'img/favicon';?>/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo SITEFRONTURL.'img/favicon';?>/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo SITEFRONTURL.'img/favicon';?>/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo SITEFRONTURL.'img/favicon';?>/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITEFRONTURL.'img/favicon';?>/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo SITEFRONTURL.'img/favicon';?>/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITEFRONTURL.'img/favicon';?>/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo SITEFRONTURL.'img/favicon';?>/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEFRONTURL.'img/favicon';?>/favicon-16x16.png">
        <link rel="manifest" href="<?php echo SITEFRONTURL.'img/favicon';?>/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?php echo SITEFRONTURL.'img/favicon';?>/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
       
        <script>

            var SITEURL = '<?php echo SITEURL; ?>';
            var SITEFRONTURL = '<?php echo FRONTURL; ?>';
			var CONTROLLER = '<?php echo $this->params->controller; ?>';
            var cryptoSalt='<?php echo Configure::read('Security.salt');?>';
        </script>

        <?php
        echo $this->Html->css(
                array(
                    '/plugins/fontawesome/css/all.min.css',
                    '/plugins/select2/css/select2.min',
                    '/plugins/dist/css/adminlte.min.css',
                    '/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
                    '/plugins/jquery-ui/jquery-ui',
                    '/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min', 
                    '/plugins/icheck-bootstrap/icheck-bootstrap.min',
                    '/plugins/fancybox/jquery.fancybox.min',
                    '/plugins/sweetalert2/sweetalert2.min',
                    '/plugins/toastr/toastr.min',  
                    "custom", 
                    '/css/admin/custom.css',
                    '/css/admin/loader.css',
                    '/css/admin/theme.min.css',
                    '/css/admin/AdminLTE.css',
                )
            );
                    
        echo $this->Html->script(array('/plugins/jquery/jquery.min','/plugins/jquery-ui/jquery-ui.min','/plugins/ckeditor/ckeditor','/plugins/jquery-validation/jquery.validate.min','/plugins/jquery-validation/additional-methods.min','/plugins/sweetalert2/sweetalert2.min','/plugins/fancybox/jquery.fancybox.min',
        '/plugins/select2/js/select2.full.min')); ?> 
        <?php echo $this->Js->writeBuffer(array('cache'=>true));  ?>
        
        <!-- <link rel="stylesheet" href="https://use.fontawesome.com/db1066c662.css">-->
    </head>
    <body class="hold-transition register sidebar-mini layout-fixed sidebar-collapse">
        <div id="AjaxLoading" style="display:none;">
        <i class="fas fa-sync-alt fa-spin"></i></i><br/>Loading... Please wait...</div>
        <div class="wrapper">
             <?php echo $this->element('header'); ?>
             <?php 
            switch ($this->Session->read('Auth.User.staff_role_id')) {
                case 1:
                    echo $this->element('left_nav');
                break; 
                default:
                echo $this->element('left_editor_nav');
                break;
            }
            ?>

            
            <div class="content-wrapper"> 
                <?php if(!empty($title_for_layout)){ ?>
                <div class="content-header pagetitle">
                  <div class="container-fluid">
                    <div class="row mb-2">
                      <div class="col-sm-6">
                        <h1 class="m-0 text-dark">   <?php echo $title_for_layout; ?></h1>
                      </div><!-- /.col --> 
                    </div><!-- /.row -->
                  </div><!-- /.container-fluid -->
                </div>
                <?php } ?>
                <!-- /.content-header -->

                <section class="content "> 
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <?php
                                echo $this->Session->flash();
                                ?>
                            </div><!-- /.col -->
                        </div>
                    </div>
                </section>
                
                <section class="content">
                    <div class="container-fluid">
                    <?php echo $this->fetch('content'); ?>
                </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php
            if(in_array($action,['edit_preview'])){
                
                echo '<section class="content">'.$this->element('footer_newsroom').'</section>';
            }else{
                echo $this->element('footer');
            }
            
            ?>
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
 
        <?php //echo $this->element('sql_dump'); ?>
        <?php 
            echo $this->Html->script(
                array(
                    '/plugins/toastr/toastr.min',
                    '/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min',
                    '/plugins/moment/moment.min',
                    '/plugins/bootstrap/js/popper.min',
                    "/plugins/bootstrap/js/bootstrap.min",
                    "/plugins/bootstrap/js/bootstrap.bundle.min",
                    '/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min',
                    '/plugins/dist/js/adminlte', 
                    "custom",
                    '/js/app.js',
                    '/js/custom.js',
                    '/js/ResizeSensor.js',
                    '/js/jquery-ui.min.js',
                    '/js/jquery-1.11.3.min.js',
                    '/js/demo.js',
                    '/js/dashboard.js',
                    '/js/dashboard2.js',
                    '/js/cropper.min.js',
                    '/js/bootstrap-dialog.js',
                    '/js/bootbox.min.js',
                )
            );
        echo $this->Js->writeBuffer();

        ?> 
            <script>
            $('.custom_select').select2();
            $('.select2').select2();
            $(".datepicker").datepicker({
                dateFormat: "dd-mm-yy", 
                changeMonth: true,
                changeYear: true,
            });
            </script>
        <?php //echo $this->element('message');    ?>
    </body>
</html>
