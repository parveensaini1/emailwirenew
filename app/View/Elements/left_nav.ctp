<?php
$controller = strtolower(trim($this->params['controller']));
$action = strtolower(trim($this->params['action']));
$role = $this->Session->read('Auth.User.StaffRole');
?>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li <?php if ($controller == 'users' && $action == 'dashboard') { ?> class="active" <?php } ?> >
                <?php echo $this->Html->link('<i class="fa fa-dashboard"></i> <span>Dashboard</span>', array('controller' => 'users', 'action' => 'dashboard'), array('escape' => false)) ?>
            </li> 
            <li class="treeview <?php if (in_array($controller, array('pressreleases')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-fw fa-newspaper-o"></i>
                    <span>Press Releases</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'pressreleases' && $action == 'submit_release') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Submit A Release</span>', array('controller' => 'pressReleases', 'action' => 'submit_release'), array('escape' => false)) ?>
                    </li>    
                    <li <?php if ($controller == 'pressreleases' && $action == 'pending') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Pending</span>', array('controller' => 'pressReleases', 'action' => 'pending'), array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'pressreleases' && $action == 'approved') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Approved</span>', array('controller' => 'pressReleases', 'action' => 'approved'), array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'pressreleases' && $action == 'denied') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Denied</span>', array('controller' => 'pressReleases', 'action' => 'denied'), array('escape' => false)) ?>
                    </li>            
                </ul>
            </li> 
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-fw fa-file-powerpoint-o"></i>
                    <span>Newsroom</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Add Newsroom</span>', "javascript:void(0);", array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'categories') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Edit Newsroom</span>', "javascript:void(0);", array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'msas') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Add News to Site</span>', "javascript:void(0);", array('escape' => false)) ?>
                    </li>                    
                </ul>
            </li> 

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-fw fa-institution"></i>
                    <span>Buy a Plan</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Purchased Plans & Credits</span>', "javascript:void(0);", array('escape' => false)) ?>
                    </li>                   
                </ul>
            </li> 
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-fw fa-credit-card"></i>
                    <span>User Info</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Modify Credit Card</span>', "javascript:void(0);", array('escape' => false)) ?>
                    </li>                   
                    <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Change Method of Paymen</span>', "javascript:void(0);", array('escape' => false)) ?>
                    </li>                   
                </ul>
            </li>  
            <li <?php if ($controller == 'invoices' && $action == 'index') { ?> class="active" <?php } ?>>
                <?php echo $this->Html->link('<i class="fa fa-fw fa-fax"></i> <span>Invoices</span>', array('controller' => 'invoices', 'action' => 'index'), array('escape' => false)) ?>
            </li> 
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-fw fa-tags"></i>
                    <span>PR Guidelines & Policies</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Content Policy</span>', "javascript:void(0);", array('escape' => false)) ?>
                    </li>                   
                    <li <?php if ($controller == 'plans') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Press Release Format</span>', "javascript:void(0);", array('escape' => false)) ?>
                    </li>                   
                </ul>
            </li> 
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
