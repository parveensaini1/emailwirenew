<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title_for_layout; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.4 -->
        <?php
        echo $this->Html->css(
                array(
                    '/plugins/bootstrap/css/bootstrap.min',
                    'admin/theme.min',
                    '/plugins/iCheck/square/blue',
                    '/plugins/toastr/toastr.min'
                )
        );
        ?>
        <script>
            var appUrl = '<?php echo SITEURL; ?>';
            var SITEFRONTURL = '<?php echo SITEFRONTURL; ?>';
            var CONTROLLER = '<?php echo $this->params->controller; ?>';
        </script> 

        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo SITEURL.'img/favicon';?>/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo SITEURL.'img/favicon';?>/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo SITEURL.'img/favicon';?>/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo SITEURL.'img/favicon';?>/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo SITEURL.'img/favicon';?>/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo SITEURL.'img/favicon';?>/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo SITEURL.'img/favicon';?>/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo SITEURL.'img/favicon';?>/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITEURL.'img/favicon';?>/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo SITEURL.'img/favicon';?>/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITEURL.'img/favicon';?>/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo SITEURL.'img/favicon';?>/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL.'img/favicon';?>/favicon-16x16.png">
<!-- <link rel="manifest" href="<?php echo SITEURL.'img/favicon';?>/manifest.json"> -->
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo SITEURL.'img/favicon';?>/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
        
<?php //echo $this->Html->meta('favicon.ico','img/favicon.ico',array('type' => 'icon'));?>
    </head>
    <body>
        <div class="login-box">
            <section class="content" style="min-height:auto;padding: 0px 0px 25px 0px;">
                <div class="row">
                    <div class="col-md-12">

                        <?php
                        echo $this->Session->flash();
                        ?>
                    </div><!-- /.col --> 
                </div>
            </section>
            <div class="login-logo">
                <div><img src="<?php echo SITEURL; ?>img/emailwire_logo_final.jpg" /></div>
            </div><!-- /.login-logo -->
            <div >                      
                <?php echo $this->fetch('content'); ?>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
        <?php
        echo $this->Html->script(
                array(
                    'jQuery-2.1.4.min',
                    'http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js',
                    '/plugins/bootstrap/js/bootstrap.min',
                    '/plugins/iCheck/icheck.min',
                    '/plugins/toastr/toastr.min',
                    'customloader.css',
                    'custom',
                )
        );
        ?>

        <script>

                    $(function () {

                        $('input').iCheck({
                            checkboxClass: 'icheckbox_square-blue',
                            radioClass: 'iradio_square-blue',
                            increaseArea: '20%' // optional
                        });
                    });
        </script>
        <?php //echo $this->element('message'); ?>
    </body>
</html>
