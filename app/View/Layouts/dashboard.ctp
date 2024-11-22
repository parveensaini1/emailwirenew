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
            'admin/custom',
            'customloader.css',
            '/website/css/custom',
            '/website/css/custom2',
        )
    );
    ?> 
    <?php

    echo $this->Html->script(
        array(
            '/plugins/jquery/jquery.min',
            '/plugins/jqueryui/jquery-ui.min',
            '/website/js/popper.min',
            '/plugins/bootstrap/js/bootstrap.bundle.min', 
            '/plugins/dist/js/adminlte',
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
 
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body  class="hold-transition skin-blue layout-top-nav <?php echo $controller . ' ' . $action; ?> <?php echo $bodyClass . " " . $signupClass; ?> <?php echo ($uId != 0) ? "logged-in" : "logged-out"; ?>">
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
            echo $this->element('footer');
            
            ?>
    </div><!-- ./wrapper -->
 
</body>

</html>