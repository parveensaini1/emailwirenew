<?php
$controller = strtolower(trim($this->params['controller']));
$action = strtolower(trim($this->params['action']));
$role = $this->Session->read('Auth.User.StaffRole');
$role_id = $this->Session->read('Auth.User.staff_role_id'); 
?>
<header class="main-header">
    <nav class="navbar navbar-static-top" style="background-color: #fa7d07!important;">
        <div class="container" style="width: 100%;">
            <div class="navbar-header">
                <a style="padding: 4px 15px;margin-left:0PX;" href="<?php echo SITEURL; ?>users/dashboard" class="navbar-brand"><img src="<?php echo SITEURL; ?>website/img/emailwire-logo-inner.png"/></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <?php if($role_id!=4){?> 
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    <ul class="nav navbar-nav"> 
                        <li class="dropdown" >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Press Releases<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php if(isset($is_plan_paid)&&$is_plan_paid== 1){ ?>
                                 <li <?php if ($controller == 'pressreleases' && $action == 'submit_release') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Submit A Release</span>', array('controller' => 'users', 'action' => 'add-press-release'), array('escape' => false)) ?>
                                </li>
                            <?php } ?>
                            <?php
                            if(isset($pressReleaseCount)&&$pressReleaseCount>= 1){ ?>
                                <li <?php if ($controller == 'pressreleases' && $action == 'press-releases') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Pending</span>', array('controller' => 'users', 'action' => 'press-releases'), array('escape' => false)) ?>
                                </li> 
                                <li <?php if ($controller == 'pressreleases' && $action == 'press-releases') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Approved</span>', array('controller' => 'users', 'action' => 'press-releases','1'), array('escape' => false)) ?>
                                </li> 
                                <li <?php if ($controller == 'pressreleases' && $action == 'press-releases') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Denied</span>', array('controller' => 'users', 'action' => 'press-releases','2'), array('escape' => false)) ?>
                                </li>  
                            <?php } ?>
                            </ul>
                        </li>
                        <li class="dropdown" >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Newsroom<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'users'&&$action=='create-newsroom') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Create newsroom</span>', "create-newsroom", array('escape' => false)) ?>
                                </li> 
                                <li <?php if ($controller == 'users' && $action == 'take-over-company') { ?> class="active" <?php } ?>>
                                <?php echo $this->Html->link('<span>Company Take Over</span>','take-over-company', array('escape' => false)) ?>
                                </li> 
                                <?php if($newsroomcount>=1){?>
                                <li <?php if ($controller == 'users'&&$action=='newsrooms') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>My Newsrooms</span>', "javascript:void(0);", array('escape' => false)) ?>
                                </li>
                            <?php } ?>
                            </ul>
                        </li>
                        <!-- <li class="dropdown" >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">PR Plans<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Purchased Plans & Credits</span>', "javascript:void(0);", array('escape' => false)) ?>
                                </li> 
                            </ul>
                        </li>
                        <li class="dropdown" >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">User Info<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Modify Credit Card</span>', "javascript:void(0);", array('escape' => false)) ?>
                                </li>                   
                                <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Change Method of Payment</span>', "javascript:void(0);", array('escape' => false)) ?>
                                </li> 
                            </ul>
                        </li> -->
                       <!--  <li <?php if ($controller == 'invoices' && $action == 'index') { ?> class="active" <?php } ?>>
                            <?php echo $this->Html->link('<span>Invoices</span>', array('controller' => 'invoices', 'action' => 'index'), array('escape' => false)) ?>
                        </li>
                       
                        <li class="dropdown" >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">PR Guidelines & Policies<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Content Policy</span>', "javascript:void(0);", array('escape' => false)) ?>
                                </li>                   
                                <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Press Release Format</span>', "javascript:void(0);", array('escape' => false)) ?>
                                </li>  
                            </ul>
                        </li> -->
                    </ul> 
                </div><!-- /.navbar-collapse -->
            <?php } else{ ?>
    <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    <ul class="nav navbar-nav"> 
                        <li>
                            <a href="#">Subscribe News</a>                        
                        </li>
                        <li>
                            <a href="<?php echo SITEURL.'users/becomeaclient'; ?>">Become A PR Firm or Client</a>                        
                        </li>
                    </ul> 
                </div><!-- /.navbar-collapse -->
    
<?php } ?>            
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?php 
                            if ($this->Session->read('Auth.User.profile_image') != '') {
                                echo $this->Html->image(SITEADMIN . '/files/profile_image/' . $this->Session->read('Auth.User.profile_image'), array('class' => 'user-image'));
                            } else {
                                echo $this->Html->image('no_image.jpeg', array('class' => 'user-image'));
                            }
                            ?> 
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?php echo strtoupper($this->Session->read('Auth.User.first_name')); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <?php
                                if ($this->Session->read('Auth.User.profile_image') != '') {
                                    echo $this->Html->image(SITEADMIN . '/files/profile_image/' . $this->Session->read('Auth.User.profile_image'), array('class' => 'img-circle'));
                                } else {
                                    echo $this->Html->image(SITEURL.'img/no_image.jpeg', array('class' => 'img-circle'));
                                }
                                ?>                                 
                                <p>
                                    <?php echo strtoupper($this->Session->read('Auth.User.first_name')); ?>
                                    <small>Member since <?php echo date('d-m-Y', strtotime($this->Session->read('Auth.User.created'))); ?></small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="col-xs-12">
                                    <?php echo $this->Html->link("Change Password", array("controller" => 'users', 'action' => 'user_password')); ?>
                                </div>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <?php echo $this->Html->link("Profile", array("controller" => 'users', 'action' => 'profile'), array('class' => 'btn btn-default btn-flat')); ?>                                    
                                </div>
                                <div class="pull-right">
                                    <?php echo $this->Html->link("Sign out", array("controller" => 'users', 'action' => 'logout', 'admin' => false), array('class' => 'btn btn-default btn-flat')); ?>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-custom-menu -->
        </div><!-- /.container-fluid -->
    </nav>
</header>
