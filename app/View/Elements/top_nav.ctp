<?php
$controller = strtolower(trim($this->params['controller']));
$action = strtolower(trim($this->params['action']));
$role = $this->Session->read('Auth.User.StaffRole');
$role_id = $this->Session->read('Auth.User.staff_role_id'); 
?>

<header class="main-header">
<nav class="navbar navbar-expand-lg ew-navbar-custom">
   <a style="padding: 4px 15px;margin-left:0PX;" href="<?php echo SITEURL; ?>users/dashboard" class="navbar-brand"><img src="<?php echo SITEURL; ?>website/img/emailwire-logo-inner.png"/></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse " id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <?php if($role_id!=4){?>  
        <li class="nav-item dropdown <?php if($controller == 'users'&&in_array($action,array('submit_release','press_release'))){echo 'active';}?>">
            <a class="nav-link dropdown-toggle <?php if($controller == 'users'&&in_array($action,array('submit_release','press_release'))){echo 'active';}?>" href="#" role="button" data-toggle="dropdown" aria-expanded="false">Press Releases</a>
            <div class="dropdown-menu">
                <?php
                    $menuActiveClass=($controller == 'users'&&$action=='submit_release')?"active":'';
                    echo $this->Html->link('<span>Submit a PR</span>', array('controller' => 'users', 'action' => 'add-press-release'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php 
                    $menuActiveClass=($controller == 'users'&&$action=='press_releases')?"active":'';
                    echo $this->Html->link('<span>Drafts</span>', array('controller' => 'users', 'action' => 'press-releases','draft'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php
                    $menuActiveClass=($controller == 'users'&&$action=='press_releases')?"active":'';
                    echo $this->Html->link('<span>Pending</span>', array('controller' => 'users', 'action' => 'press-releases','pending'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php
                    $menuActiveClass=($controller == 'users'&&$action=='press_releases')?"active":'';
                    echo $this->Html->link('<span>Approved</span>', array('controller' => 'users', 'action' => 'press-releases','approved'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php 
                    $menuActiveClass=($controller == 'users'&&$action=='press_releases')?"active":'';
                    echo $this->Html->link('<span>Embargoed</span>', array('controller' => 'users', 'action' => 'press-releases','embargoed'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php 
                    $menuActiveClass=($controller == 'users'&&$action=='press_releases')?"active":'';
                    echo $this->Html->link('<span>Disapproved</span>', array('controller' => 'users', 'action' => 'press-releases','disapproved'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php
                    $menuActiveClass=($controller == 'users'&&$action=='press_releases')?"active":'';
                    echo $this->Html->link('<span>Trashed</span>', array('controller' => 'users', 'action' => 'press-releases','trashed'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
            </div>
        </li>
        <li class="nav-item dropdown <?php if($controller == 'users'&&in_array($action,array('create_newsroom','take_over_publishing','newsrooms'))){echo 'active';}?>">
            <a class="nav-link dropdown-toggle <?php if($controller == 'users'&&in_array($action,array('create_newsroom','take_over_publishing','newsrooms'))){echo 'active';}?>" href="#" role="button" data-toggle="dropdown" aria-expanded="false">Newsroom</a>
            <div class="dropdown-menu">
                <?php
                    $menuActiveClass=($controller == 'users'&&$action=='create_newsroom')?"active":'';
                    echo $this->Html->link('<span>Create newsroom</span>', array('controller' => 'users', 'action' => 'create-newsroom'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php $menuActiveClass=($controller == 'users'&&$action=='take_over_publishing')?"active":'';
                    echo $this->Html->link('<span>Take Over Publishing</span>', array('controller' => 'users', 'action' => 'take-over-publishing'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php $menuActiveClass=($controller == 'users'&&$action=='newsrooms'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='pending')?"active":'';
                    echo $this->Html->link('<span>Pending Newsrooms</span>', array('controller' => 'users', 'action' => 'newsrooms','pending'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php $menuActiveClass=($controller == 'users'&&$action=='newsrooms'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='approved')?"active":'';
                    echo $this->Html->link('<span>Approved Newsrooms</span>', array('controller' => 'users', 'action' => 'newsrooms','approved'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php $menuActiveClass=($controller == 'users'&&$action=='newsrooms'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='suspended')?"active":'';
                    echo $this->Html->link('<span>Suspended Newsrooms</span>', array('controller' => 'users', 'action' => 'newsrooms','suspended'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php $menuActiveClass=($controller == 'users'&&$action=='newsrooms'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='disapproved')?"active":'';
                    echo $this->Html->link('<span>Disapproved Newsrooms</span>', array('controller' => 'users', 'action' => 'newsrooms','disapproved'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php $menuActiveClass=($controller == 'users'&&$action=='newsrooms'&&(isset($this->request->pass[0]))&&$this->request->pass[0]=='trashed')?"active":'';
                    echo $this->Html->link('<span>Trashed Newsrooms</span>', array('controller' => 'users', 'action' => 'newsrooms','trashed'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
            </div>
        </li>

        <li class="nav-item dropdown <?php if(($controller == 'users'||$controller == 'pages')&&in_array($action,array('index','plans','activated_plans'))){echo 'active';}?>">
            <a class="nav-link dropdown-toggle <?php if(($controller == 'users'||$controller == 'pages')&&in_array($action,array('index','plans','activated_plans'))){echo 'active';}?>" href="#" role="button" data-toggle="dropdown" aria-expanded="false">PR Plans</a>
            <div class="dropdown-menu">
                <?php
                    $menuActiveClass=($controller == 'plans'&&$action=="index")?"active":'';
                    echo $this->Html->link('<span>Purchase new plan</span>', array('controller' => 'plans', 'action' => ''), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php $menuActiveClass=($controller == 'users'&&$action=='plans')?"active":'';
                    echo $this->Html->link('<span>Purchased PR plans</span>', array('controller' => 'users', 'action' => 'plans'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php $menuActiveClass=($controller == 'users'&&$action=='activated_plans')?"active":'';
                    echo $this->Html->link('<span>Activated PR plans</span>', array('controller' => 'users', 'action' => 'activated_plans'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
            </div>
        </li>

        <li class="nav-item dropdown <?php if(($controller == 'users' && $action == 'invoices')){echo 'active';}?>">
            <a class="nav-link dropdown-toggle <?php if(($controller == 'users' && $action == 'invoices')){echo 'active';}?>" href="#" role="button" data-toggle="dropdown" aria-expanded="false">Invoices</a>
            <div class="dropdown-menu">
                <?php
                   // $menuActiveClass=($controller == 'users'&&isset($this->request->pass[1])&&$this->request->pass[1]=='plannewsroom')?"active":'';
                   // echo $this->Html->link('<span>Purchase new plan</span>', array('controller' => 'users', 'action' => 'invoices','plannewsroom'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php $menuActiveClass=($controller == 'users'&&isset($this->request->pass[1])&&$this->request->pass[1]=='pr')?"active":'';
                    echo $this->Html->link('<span>PR invoices</span>', array('controller' => 'users', 'action' => 'invoices','pr'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
            </div>
        </li>
 
      

        <li class="nav-item dropdown <?php if($controller == 'users' && $action == 'clipping_report'){echo 'active';}?>">
            <?php 
            $menuActiveClass=($controller == 'users' && $action == 'clipping_report')?"active":"";
            echo $this->Html->link('<span>Clipping Reports</span>', array('controller' => 'users', 'action' =>'clipping-report'), array('escape' => false,'class'=>"nav-link $menuActiveClass")) ?>
        </li>

        <li class="nav-item dropdown <?php if($controller == 'users'&&in_array($action,array('add_email_list','email_list'))){echo 'active';}?>">
            <a class="nav-link dropdown-toggle <?php if($controller == 'users'&&in_array($action,array('add_email_list','email_list'))){echo 'active';}?>" href="#" role="button" data-toggle="dropdown" aria-expanded="false">Email lists</a>
            <div class="dropdown-menu">
                <?php
                    $menuActiveClass=($controller == 'users'&&$action=='email_list')?"active":'';
                    echo $this->Html->link('<span>Email lists</span>', array('controller' => 'users', 'action' => 'email_list'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php
                    $menuActiveClass=($controller == 'users'&&$action=='add_email_list')?"active":'';
                    echo $this->Html->link('<span>Add list</span>', array('controller' => 'users', 'action' => 'add-email-list'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
            </div>
        </li>

        <li class="nav-item dropdown <?php if($controller == 'users'&&in_array($action,array('support','contact_us'))){echo 'active';}?>">
            <a class="nav-link dropdown-toggle <?php if($controller == 'users'&&in_array($action,array('support','contact_us'))){echo 'active';}?>" href="#" role="button" data-toggle="dropdown" aria-expanded="false">Contact us</a>
            <div class="dropdown-menu">
                <?php
                    $menuActiveClass=($controller == 'users'&&$action=='support')?"active":'';
                    echo $this->Html->link('<span>Support</span>', array('controller' => 'users', 'action' => 'support'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
                <?php
                    $menuActiveClass=($controller == 'users'&&$action=='contact_us')?"active":'';
                    echo $this->Html->link('<span>Contact Us</span>', array('controller' => 'users', 'action' => 'contact-us'), array('escape' => false,'class'=>"dropdown-item $menuActiveClass")) ?>
            </div>
        </li>

      <?php }else{?>
        <li class="nav-item dropdown <?php if($controller == 'users' && $action == 'subscribenews'){echo 'active';}?>">
            <?php 
            $menuActiveClass=($controller == 'users' && $action == 'subscribenews')?"active":"";
            echo $this->Html->link('<span>Subscriber News</span>', array('controller' => 'users', 'action' =>'subscribenews'), array('escape' => false,'class'=>"nav-link $menuActiveClass")) ?>
        </li>
        <li class="nav-item dropdown <?php if($controller == 'users' && $action == 'becomeaclient'){echo 'active';}?>">
            <?php 
            $menuActiveClass=($controller == 'users' && $action == 'becomeaclient')?"active":"";
            echo $this->Html->link('<span>Submit A News Release</span>', array('controller' => 'users', 'action' =>'becomeaclient'), array('escape' => false,'class'=>"nav-link $menuActiveClass")) ?>
        </li>
        <li class="nav-item dropdown <?php if($controller == 'users' && $action == 'update_newsletter_detail'){echo 'active';}?>">
            <?php 
            $menuActiveClass=($controller == 'users' && $action == 'update_newsletter_detail')?"active":"";
            echo $this->Html->link('<span>Update Newsletter details</span>', array('controller' => 'users', 'action' =>'update-newsletter-detail'), array('escape' => false,'class'=>"nav-link $menuActiveClass")) ?>
        </li>
        <?php } ?>
        
        <?php if((isset($is_plan_paid)&&$is_plan_paid==1&&$newsroomcount>=1)){
            $prActiveClass="";
            if ($controller == 'users' && $action == 'submit_release') {
                $prActiveClass="active";
            }
            ?>
            <li class="nav-item">
                <div class="submitprbtn">
                    <?php echo $this->Html->link('<span>Submit a PR</span>', array('controller' => 'users', 'action' => 'add-press-release'), array('escape' => false,'class'=>"float-left btn orange-btn $prActiveClass")) ?>
                </div>
            </li>
        <?php } ?>
        
    </ul>
   
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
            </div>
  </div>
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