<?php
$controller = strtolower(trim($this->params['controller']));
$action = strtolower(trim($this->params['action']));
$role = $this->Session->read('Auth.User.StaffRole');
$role_id = $this->Session->read('Auth.User.staff_role_id'); 
?>

<header class="main-header">
    <!-- JavaScript Bundle with Popper -->

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
                        <li class="dropdown <?php if($controller == 'users'&&in_array($action,array('submit_release','press_release'))){echo 'active';}?>">
                            <a href="#" class="dropdown-toggle <?php if($controller == 'users'&&$action=='submit_release'){echo 'active';}?>" data-toggle="dropdown">Press Releases<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'users' && $action == 'submit_release') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Submit a PR</span>', array('controller' => 'users', 'action' => 'add-press-release'), array('escape' => false)) ?>
                                </li>
                                <li <?php if ($controller == 'users' && $action == 'press_release'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='draft') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Drafts</span>', array('controller' => 'users', 'action' => 'press-releases','draft'), array('escape' => false)) ?>
                                </li> 
                                <li <?php if ($controller == 'users' && $action == 'press_release'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='pending') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Pending</span>', array('controller' => 'users', 'action' => 'press-releases','pending'), array('escape' => false)) ?>
                                </li> 
                                 
                                <li <?php if ($controller == 'users' && $action == 'press_release'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='approved') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Approved</span>', array('controller' => 'users', 'action' => 'press-releases','approved'), array('escape' => false)) ?>
                                </li> 
                                <li <?php if ($controller == 'users' && $action == 'press_release'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='embargoed') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Embargoed</span>', array('controller' => 'users', 'action' => 'press-releases','embargoed'), array('escape' => false)) ?>
                                </li>
                                <li <?php if ($controller == 'users' && $action == 'press_release'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='disapproved') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Disapproved</span>', array('controller' => 'users', 'action' => 'press-releases','disapproved'), array('escape' => false)) ?>
                                </li>

                                <li <?php if ($controller == 'users' && $action == 'press_release'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='trashed') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Trashed</span>', array('controller' => 'users', 'action' => 'press-releases','trashed'), array('escape' => false)) ?>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown <?php if($controller == 'users'&&in_array($action,array('create_newsroom','take_over_publishing','newsrooms'))){echo 'active';}?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Newsroom<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'users'&&$action=='create_newsroom') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Create newsroom</span>',SITEURL."users/create-newsroom", array('escape' => false)) ?>
                                </li> 
                                <li <?php if ($controller == 'users' && $action == 'take_over_publishing') { ?> class="active" <?php } ?>>
                                <?php echo $this->Html->link('<span>Take Over Publishing</span>',SITEURL."users/take-over-publishing", array('escape' => false)) ?>
                                </li> 
                                <li <?php if ($controller == 'users'&&$action=='newsrooms'&&(isset($this->request->pass[0]))&&$this->request->pass[0]==0) { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Pending Newsrooms</span>',SITEURL."users/newsrooms/0", array('escape' => false)) ?>
                                </li>
                                <li <?php if ($controller == 'users'&&$action=='newsrooms'&&(isset($this->request->pass[0]))&&$this->request->pass[0]==3) { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Disapproved Newsrooms</span>',SITEURL."users/newsrooms/3", array('escape' => false)) ?>
                                </li>
                                <li <?php if ($controller == 'users'&&$action=='newsrooms'&&(isset($this->request->pass[0]))&&$this->request->pass[0]==1) { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Approved Newsrooms</span>',SITEURL."users/newsrooms/1", array('escape' => false)) ?>
                                </li>
                                <li <?php if ($controller == 'users'&&$action=='newsrooms'&&(isset($this->request->pass[0]))&&$this->request->pass[0]==2) { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Suspended Newsrooms</span>',SITEURL."users/newsrooms/2", array('escape' => false)) ?>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown <?php if(($controller == 'users'||$controller == 'pages')&&in_array($action,array('index','plans','activated_plans'))){echo 'active';}?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">PR Plans<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'pages'&&isset($this->request->pass[1])&&$this->request->pass[1]=='online-distribution') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Purchase new plan</span>',SITEURL."plans/online-distribution", array('escape' => false)) ?>
                                </li> 
                                <li <?php if ($controller == 'users'&&$action=='plans') { ?> class="active" <?php } ?>>
                                <?php echo $this->Html->link('<span>Purchased PR plans</span>',SITEURL."users/plans", array('escape' => false)) ?>
                                </li> 
                                <li <?php if ($controller == 'users'&&$action=='activated_plans') { ?> class="active" <?php } ?>>
                                <?php echo $this->Html->link('<span>Activated PR plans</span>',SITEURL."users/activated_plans", array('escape' => false)) ?>
                                </li> 
                            </ul>
                        </li>
                        
                        <li class="dropdown <?php if ($controller == 'users' && $action == 'invoices'){echo 'active';}?>">
                            <a href="<?php echo SITEURL."users/invoices/plannewsroom";?>" class="dropdown-toggle" data-toggle="dropdown">Invoices<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'users'&&isset($this->request->pass[1])&&$this->request->pass[1]=='plannewsroom') { ?> class="active" <?php } ?>>
                                    <?php  echo $this->Html->link('<span>Plan invoices</span>', array('controller' => 'users', 'action' => 'invoices','plannewsroom'), array('escape' => false))?>
                                </li> 
                                <li <?php if ($controller == 'users'&&isset($this->request->pass[1])&&$this->request->pass[1]=='pr') { ?> class="active" <?php } ?>>
                                <?php  echo $this->Html->link('<span>PR invoices</span>', array('controller' => 'users', 'action' => 'invoices','pr'), array('escape' => false))?>
                                </li> 
                            </ul>
                        </li> 
                        <?php // var_dump($controller); var_dump($action); die; ?>
                        <li <?php if($controller == 'users' && $action == 'clipping_report') { ?> class="active" <?php } ?> >
                            <?php echo $this->Html->link('<span>Clipping Reports</span>', array('controller' => 'users', 'action' =>'clipping-report'), array('escape' => false)) ?>
                        </li>
                        
                        <li class="dropdown <?php if($controller == 'users'&&in_array($action,array('add_email_list','email_list'))){echo 'active';}?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Email lists<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'users'&&$action=='add_email_list') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Email lists</span>',SITEURL."users/email-lists", array('escape' => false)) ?>
                                </li> 
                                  <li <?php if ($controller == 'users'&&$action=='add_email_list') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Add list</span>',SITEURL."users/add-email-list", array('escape' => false)) ?>
                                </li> 
                            </ul>
                        </li>
                        <li class="dropdown <?php if($controller == 'users'&&in_array($action,array('support','contact_us'))){echo 'active';}?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Contact us<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li <?php if ($controller == 'users'&&$action=='support') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Support</span>',SITEURL."users/support", array('escape' => false)) ?>
                                </li>  
                                <li <?php if ($controller == 'users'&&$action=='contact_us') { ?> class="active" <?php } ?>>
                                    <?php echo $this->Html->link('<span>Contact</span>',SITEURL."users/contact-us", array('escape' => false)) ?>
                                </li> 
                                  
                            </ul>
                        </li> 
 
                    </ul>  
                    <?php if((isset($is_plan_paid)&&$is_plan_paid==1&&$newsroomcount>=1)){
                        $prActiveClass="";
                        if ($controller == 'users' && $action == 'submit_release') {
                            $prActiveClass="active";
                        }
                     ?>
                        <div class="submitprbtn">
                            <?php echo $this->Html->link('<span>Submit a PR</span>', array('controller' => 'users', 'action' => 'add-press-release'), array('escape' => false,'class'=>" btn orange-btn $prActiveClass")) ?>
                        </div>
                    <?php } ?>
                </div><!-- /.navbar-collapse -->
            <?php } else{ ?>
    <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    <ul class="nav navbar-nav"> 
                        <li><a href="<?php echo SITEURL.'users/subscribenews'; ?>">Subscriber News</a></li>
                        <li><a href="<?php echo SITEURL.'users/becomeaclient'; ?>">Submit A News Release</a></li>
                        <li><a href="<?php echo SITEURL.'users/update-newsletter-details'; ?>">Update Newsletter details</a></li>
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

<?php    if($controller=='users'&&$action=='newsroom_view'&&isset($data['Company']['banner_path'])&&!empty($data['Company']['banner_path'])){ 
    $banner=SITEURL.'files/company/banner/'.$data['Company']['banner_path'].'/'.$data['Company']['banner_image'];     ?>
<div style="background-image:url('<?php echo $banner;?>');    background-size: cover !important; height: 330px;" class="header-newsroom ew-banner-newsroom full"> 
</div>
<?php } ?>

<?php    /*
if($controller=='users'&&$action=='newsroom_view'&&isset($data['Company']['banner_path'])&&!empty($data['Company']['banner_path'])){ 

    $banner=SITEURL.'files/company/banner/'.$data['Company']['banner_path'].'/'.$data['Company']['banner_image'];
    ?>
    <div style="background-image:url('<?php echo $banner;?>');    background-size: cover !important; height: 330px;" class="header-newsroom ew-banner-newsroom full"> 
    </div>
<?php }elseif(isset($banner)&&!empty($banner)){?>
    <div style="background-image:url('<?php echo $banner;?>');    background-size: cover !important; height: 330px;" class="header-newsroom ew-banner-newsroom full"> 
    </div>
<?php } */ ?> 