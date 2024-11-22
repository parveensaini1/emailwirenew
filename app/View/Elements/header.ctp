<!-- JavaScript Bundle with Popper -->
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo SITEURL; ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>E</b>W</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img src="<?php echo SITEURL; ?>website/img/emailwire-logo.jpg" width="122px" />
        </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div id="sitter-notification">
        </div>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php
                        if (file_exists(SITEADMIN . 'files/profile_image/' . $this->Session->read('Auth.User.profile_image')) && $this->Session->read('Auth.User.profile_image') != '') {
                            echo $this->Html->image(SITEADMIN . '/files/profile_image/' . $this->Session->read('Auth.User.profile_image'), array('class' => 'user-image'));
                        } else {
                            echo $this->Html->image('no_image.jpeg', array('class' => 'user-image'));
                        }
                        ?>
                        <span class="hidden-xs"><?php echo strtoupper($this->Session->read('Auth.User.first_name')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?php //echo $this->Html->image(SITEADMIN . '/files/profile_image/' . $this->Session->read('Auth.User.profile_image'), array('class' => 'img-circle')); ?>
                            <?php
                            if (file_exists(SITEADMIN . 'files/profile_image/' . $this->Session->read('Auth.User.profile_image')) && $this->Session->read('Auth.User.profile_image') != '') {
                                echo $this->Html->image(SITEADMIN . '/files/profile_image/' . $this->Session->read('Auth.User.profile_image'), array('class' => 'img-circle'));
                            } else {
                                echo $this->Html->image('no_image.jpeg', array('class' => 'user-image'));
                            }
                            ?>
                            <p>
                                <?php echo strtoupper($this->Session->read('Auth.User.first_name')); ?>
                            </p>
                        </li>
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <?php echo $this->Html->link("Profile", array("controller" => 'users', 'action' => 'profile', 'admin' => false), array('class' => 'btn bg-olive btn-flat margin', 'style' => 'color:#FFF!important;')); ?>
                            </div>
                            <div class="col-xs-4 text-center">
                                <?php echo $this->Html->link("Change Password", array("controller" => 'users', 'action' => 'user_password', 'admin' => false), array('class' => 'btn bg-olive btn-flat margin', 'style' => 'color:#FFF!important;')); ?>
                            </div>
                        </li>
                        <li class="user-footer">
                            <div class="pull-right">
                                <?php echo $this->Html->link("Sign out", array("controller" => 'users', 'action' => 'logout', 'admin' => false), array('class' => 'btn btn-default btn-flat')); ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
    </nav>
</header>
 