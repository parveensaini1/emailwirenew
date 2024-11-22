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
                    'admin/theme.min',
                    '/plugins/iCheck/square/blue',
                    '/plugins/toastr/toastr.min', 
                    '/plugins/bootstrap/css/bootstrap.min',
                    'customloader.css',
                )
        );
       
        echo $this->Html->script(array('/plugins/jquery/jquery.min','/plugins/jquery-ui/jquery-ui.min','/plugins/ckeditor/ckeditor','/plugins/jquery-validation/jquery.validate.min','/plugins/jquery-validation/additional-methods.min','/plugins/sweetalert2/sweetalert2.min','/plugins/fancybox/jquery.fancybox.min' )); 
        ?>
     <script>
            var SITEURL = '<?php echo SITEURL; ?>'; 
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
<!-- <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo SITEURL.'img/favicon';?>/android-icon-192x192.png"> -->
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITEURL.'img/favicon';?>/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo SITEURL.'img/favicon';?>/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL.'img/favicon';?>/favicon-16x16.png">
<!-- <link rel="manifest" href="<?php echo SITEURL.'img/favicon';?>/manifest.json"> -->
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo SITEURL.'img/favicon';?>/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
        
<?php //echo $this->Html->meta('favicon.ico','img/favicon.ico',array('type' => 'icon'));?>
    </head>
    <body class="login-page" ng-controller="AppCtrl">
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
                <div><a href="<?php echo SITEURL; ?>"><img src="<?php echo SITEURL; ?>img/emailwire_logo_final.jpg" /></a></div>
<!--                <b><a href="<?php echo SITEURL; ?>">Emailwire CMS</a></b>-->
            </div><!-- /.login-logo -->
            <div class="login-box-body">                      
<p class="login-box-msg">Sign in to start your session</p>
<?php echo $this->Form->create('StaffUser', array('inputDefaults' => array('div' => false, 'class' => 'form-control', 'label' => false, 'required' => false))); ?>
<div class="form-group has-feedback">
    <?php echo $this->Form->input('email', array('placeholder' => "Email")); ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?php echo $this->Form->input('password', array('placeholder' => "Password")); ?>
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>
<div class="form-group has-feedback" id="otp-container">
    <?php echo $this->Form->input('otp', ['placeholder' => "Enter OTP"]); ?>
    <span class="glyphicon glyphicon-send form-control-feedback"></span>
</div>
<div class="row">
    <div class="col-sm-8">
        <input type="checkbox"> Remember Me
    </div><!-- /.col -->


    <div class="col-sm-4">
        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
    </div><!-- /.col -->
</div>
<?php echo $this->Form->end(); ?>

<div class="social-auth-links text-center">
    <p>- OR -</p>

</div><!-- /.social-auth-links -->
<?php echo $this->Html->link('I forgot my password', array('controller' => 'users', 'action' => 'forgot', 'admin' => false)); ?>

<?php echo " | ".$this->Html->link('Create new account', array('controller' => 'users', 'action' => 'signup', 'admin' => false)); ?>

<script type="text/javascript">
    function validationrecaptcha(event) {
        event.preventDefault();
        var response = grecaptcha.getResponse();
        console.log('response',response)
        document.getElementById('g-recaptcha-error').innerHTML='';
        if(response.length == 0) {
            document.getElementById('g-recaptcha-error').innerHTML = '<label style="color:red;">This field is required.</label>';
            return false;
        }
        $("#StaffUserLoginForm").submit();
        ShowLoadingIndicator();
    }

</script>

            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
        <?php
        echo $this->Html->script(
                array(
                    //  'http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js',
                    '/plugins/bootstrap/js/bootstrap.min',
                    '/plugins/iCheck/icheck.min',
                    '/plugins/toastr/toastr.min', 
                )
        );
        ?>

        <script>

                
                    /*ajax start stop loading*/

function ShowLoadingIndicator() {
    if (typeof (disableLoadingIndicator) != 'undefined' && disableLoadingIndicator) {
        return;
    }
    var windowWidth = $(window).width();
    var scrollTop;
    if (self.pageYOffset) {
        scrollTop = self.pageYOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {
        scrollTop = document.documentElement.scrollTop;
    } else if (document.body) {
        scrollTop = document.body.scrollTop;
    }
    // $('#AjaxLoading').css('position', 'absolute');
    // $('#AjaxLoading').css('top', scrollTop + 'px');
    $('#AjaxLoading').show();
}

        </script>
        <?php //echo $this->element('message'); ?>
    </body>
</html>
