<!DOCTYPE html>
<!-- <html dir="rtl" lang="ar"> -->
<!-- <html dir="ltr" lang="en"> -->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title_for_layout; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php
    echo $this->Html->css(
        array(
            '/plugins/dist/css/adminlte.min.css',
            '/plugins/bootstrap/css/bootstrap.min', 
            '/plugins/jqueryui/jquery-ui.min',
            '/plugins/iCheck/square/blue',
            '/plugins/toastr/toastr.min',
            'admin/bootstrap-dialog',
            // 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css',
            '/plugins/select2/css/select2.min',
            '/plugins/iCheck/square/blue',
            '/plugins/fancybox/jquery.fancybox',
            '/plugins/timepicker/jquery.timepicker',
            'admin/custom',
            '/plugins/sweetalert2/sweetalert2.min',
            'customloader.css',
            '/website/css/custom',
            '/website/css/custom2',
        )
    );
    ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <?php

    echo $this->Html->script(
        array(
            '/plugins/jquery/jquery.min',
            '/plugins/jqueryui/jquery-ui.min',
            '/website/js/popper.min',
            '/plugins/bootstrap/js/bootstrap.bundle.min',
            'https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js', 
            '/plugins/dist/js/adminlte',
            'bootstrap-dialog',
            '/plugins/select2/js/select2.min',
            '/plugins/iCheck/icheck.min',
            '/plugins/fancybox/jquery.fancybox',
            '/plugins/timepicker/jquery.timepicker',
            '/plugins/sweetalert2/sweetalert2.min',
            '/plugins/sheepIt/sheepIt',
            '/plugins/jquery-validation/jquery.validate.min',
            '/plugins/jquery-validation/additional-methods.min',  
            '/plugins/toastr/toastr.min',
            '/plugins/lazyload/jquery.lazy.min',
            '/plugins/lazyload/jquery.lazy.plugins.min',
            'custom',


        )
    );
    echo $this->Js->writeBuffer(array('cache' => true));

    $bodyClass = $this->Custom->bodyclass($this->here);
    $uId = ($this->session->read('Auth.User.id')) ? $this->session->read('Auth.User.id') : "0";
    $staff_role_id = ($this->session->read('Auth.User.staff_role_id')) ? $this->session->read('Auth.User.staff_role_id') : "0";
    $registerfrom = ($this->session->read('ClientUser.signup')) ? $this->session->read('ClientUser.signup') : "";
    $signupClass = ($this->session->read('ClientUser.signup')) ? "signup-from-" . $this->session->read('ClientUser.signup') : "";
    ?>
    <script>
        var SITEURL = '<?php echo SITEURL; ?>';
        var CONTROLLER = '<?php echo $this->params->controller; ?>';
        var currency = <?php echo Configure::read('Site.currency'); ?>;
        var CURRENT_URL = '<?php echo SITEURL . $this->params->url; ?>';
        var uId = '<?php echo $uId; ?>';
    </script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-180x180.png">
    <!-- <link rel="icon" type="image/png" sizes="192x192" href="<?php echo SITEURL . 'img/favicon'; ?>/android-icon-192x192.png"> -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITEURL . 'img/favicon'; ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo SITEURL . 'img/favicon'; ?>/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL . 'img/favicon'; ?>/favicon-16x16.png">
    <!-- <link rel="manifest" href="<?php echo SITEURL . 'img/favicon'; ?>/manifest.json"> -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo SITEURL . 'img/favicon'; ?>/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <?php //echo $this->Html->meta('favicon.ico','img/favicon.ico',array('type' => 'icon'));
    ?>
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body  class="hold-transition skin-blue layout-top-nav <?php echo $controller . ' ' . $action; ?> <?php echo $bodyClass . " " . $signupClass; ?> <?php echo ($uId != 0) ? "logged-in" : "logged-out"; ?>">
    <div id="AjaxLoading" style="display:none;" class="spinner-container">
        <div class="loader-main">
            <div class="cssload-loader">
                <div class="cssload-inner cssload-one"></div>
                <div class="cssload-inner cssload-two"></div>
                <div class="cssload-inner cssload-three"></div>
            </div>
            <p>Please wait ...</p>
        </div>
    </div>
    <div class="wrapper">
        <?php
        echo $this->element('top_nav');
        ?>
        <!-- Full Width Column -->
        <div class="content-wrapper">
            <!-- $isFullwidth managing from controller -->
            <div class="<?php echo (!empty($isFullwidth))?"container-fluid":"container"; ?>" style="max-width: 100%; ">
                    <?php
                    echo $this->Session->flash();
                    // &&$action=='dashboard'
                    if (isset($user_email_status) && $user_email_status == 1) {
                        echo '<section class="" style="min-height:auto; margin:10px 0;"><div class="row"><div class="col-sm-12"><div class="alert alert-success alert-dismissable" style="margin:0px;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <i class="icon fa fa-warning"></i>
                            You must confirm your email address. Check your inbox, spam/junk folder and click on the confirmation link.  Otherwise, click here to  <a href="' . SITEURL . 'users/resend_activation">resend confirmation</a>.
                        </div></div></div></section>';
                    }
                    // if($this->Session->read('Auth.User.staff_role_id')==3&&isset($is_plan_paid)&&$is_plan_paid==0){
                    if ($this->Session->read('Auth.User.staff_role_id') == 3 && isset($is_plan_paid) && $is_plan_paid == 0 && ($action == 'index' || $action == 'dashboard')) {
                        // echo '<section class="pr-not-found-message content-header" style="min-height:auto;"><div class="alert alert-warning alert-dismissable" style="margin:0px;">
                        //     <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        //     <i class="icon fa fa-warning"></i>
                        //     At this time you does not have any PR plan to submit a PR you must have a valid PR plan click here to <a href="'.SITEURL.'plans/online-distribution">purchase a PR plan</a>.
                        // </div></section>';
                        echo '<section class="pr-not-found-message" style="min-height:auto; margin:10px 0;"><div class="alert alert-warning alert-dismissable" style="margin:0px;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <i class="icon fa fa-warning"></i>
                            You currently do not have a PR plan. To submit a press release, select distribution below and purchase plan.';
                        if (isset($_SESSION['cart_session_id']) && $_SESSION['cart_session_id'] != '' && $action != 'dashboard') {
                            echo '<br><p style="margin-left: 30px;">If you don\'t want to purchase any pr plan at this time, you can checkout.</p>';
                        }
                        echo '</div></section>';
                    }

                    if ($this->Session->read('Auth.User.staff_role_id') == 3 && isset($newsroomcount) && $newsroomcount == 0 && $is_plan_paid == 1 && ($action == 'index' || $action == 'dashboard')) {
                        echo '<section class="pr-not-found-message" style="min-height:auto; margin:10px 0;"><div class="alert alert-warning alert-dismissable" style="margin:0px;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <i class="icon fa fa-warning"></i>
                            Currently you do not have any newsroom, click here to <a href="' . SITEURL . 'users/create-newsroom">create newsroom</a>. If you think that your company is already registered with us then search here to <a href="' . SITEURL . 'users/take-over-publishing">take over publishing</a>
                        </div></section>';
                    }
                    if ($this->Session->read('Auth.User.staff_role_id') == 3 && isset($newsroomcount) && $newsroomcount == 0 && $action == 'create_newsroom') {
                        echo '<section class="pr-not-found-message" style="min-height:auto; margin:10px 0;"><div class="alert alert-warning alert-dismissable" style="margin:0px;">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <i class="icon fa fa-warning"></i>
                            You currently do not have a newsroom. Create your newsroom below.</div></section>';
                    }

                    ?>
                    <?php if ($action != 'dashboard') {
                    ?>
                
                <?php  }  ?>
                <section class="content-header">
                    <h1>
                        <?php if ($this->Session->read('Auth.User.staff_role_id') == 3)
                            //echo $title_for_layout;
                        ?>
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">
                <?php
                 echo '<div class="container-fluid">'.$this->fetch('content').'</div>';
                //    echo $this->fetch('content');
                ?>
                </section><!-- /.content -->
            </div><!-- /.container -->
        </div><!-- /.content-wrapper -->
        <?php
            if(in_array($action,['edit_preview'])){
                
                echo '<section class="content">'.$this->element('footer_newsroom').'</section>';
            }else{
                echo $this->element('footer');
            }
            
            ?>
    </div><!-- ./wrapper -->

    <script type="text/javascript">
    
        function toast_message(message, type) {
            $.toast({
                text: message, // Text that is to be shown in the toast
                heading: 'Information', // Optional heading to be shown on the toast
                icon: type, // Type of toast icon
                showHideTransition: 'fade', // fade, slide or plain
                allowToastClose: true, // Boolean value true or false
                hideAfter: 3000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
                stack: false, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
                position: 'top-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
                textAlign: 'left', // Text alignment i.e. left, right or center
                loader: true, // Whether to show loader or not. True by default
                loaderBg: '#ffc082', // Background color of the toast loader
                beforeShow: function() {}, // will be triggered before the toast is shown
                afterShown: function() {}, // will be triggered after the toat has been shown
                beforeHide: function() {}, // will be triggered before the toast gets hidden
                afterHidden: function() {} // will be triggered after the toast has been hidden
            });
        }
        $(function() {
            // image layz load
            $('.lazyload').lazy();

            $(".custom_select").select2();
            
            $(".datepicker").datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true,
            });
            //   $('.timepicker').timepicker();
        });
    </script>
    <script>
        
        $(function() {
            $('.custom_check').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>

</html>
