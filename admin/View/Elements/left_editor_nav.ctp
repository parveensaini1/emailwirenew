<?php
    $controller = strtolower(trim($this->params['controller']));
    $action = strtolower(trim($this->params['action']));
    $role = $this->Session->read('Auth.User.StaffRole'); 
    $role_id = $this->Session->read('Auth.User.staff_role_id');
?>
<style>
.os-content-glue{
    height: 20px !important;
    
}
</style>
<aside class="main-sidebar"> 
    <section class="sidebar"> 
        <ul class="sidebar-menu">
           <li <?php if ($controller == 'staffusers' && $action == 'dashboard') { ?> class="active" <?php } ?>><a href="<?php echo SITEFRONTURL; ?>" target="_blank">Back to Emailwire</a></li>
        
            <li <?php if ($controller == 'staffusers' && $action == 'dashboard') { ?> class="active" <?php } ?> >
            <?php echo $this->Html->link('<i class="fa fa-dashboard"></i> <span>Dashboard</span>', array('controller' => 'staffUsers', 'action' => 'dashboard'), array('escape' => false)) ?>
            </li>
            <!-- Staff User manager  -->     
            <li class="treeview <?php if (in_array($controller, array('staffusers','actions', 'staffactions')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-fw fa-user"></i>
                    <span>User manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'staffusers'&&$action=='administrators'&&$this->request->url=="administrators") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Administrators</span>','/administrators', array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'staffusers'&&$action=='editors'&&$this->request->url=="editors") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Editors</span>','/editors', array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'staffusers'&&$action=='clients'&&$this->request->url=="clients") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Clients</span>','/clients', array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'staffusers'&&$action=='subscribers'){ ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Subscribers</span>','/subscribers', array('escape' => false)) ?>
                    </li>  
<!--                    <li <?php if ($controller == 'staffactions') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Permissions</span>', array('controller' => 'staffActions', 'action' => 'index'), array('escape' => false)) ?>
                    </li> -->
                    
                </ul>
            </li>  
            <li class="treeview <?php if (in_array($controller, array('newsrooms','takeovercompanies')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#"> 
                    <i class="fa fa-delicious"></i>
                    <span>Newsroom manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'newsrooms'&&$action=="create_newsroom") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Create newsroom</span>', array('controller' => 'newsrooms', 'action' => 'create_newsroom'), array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'newsrooms'&&$action=="pending") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Pending</span>', array('controller' => 'newsrooms', 'action' => 'pending'), array('escape' => false)) ?>
                    </li> 

                    <li <?php if ($controller == 'newsrooms'&&$action=="published") { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Published</span>', array('controller' => 'Newsrooms', 'action' =>'published'), array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'newsrooms'&&$action=="suspended") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Suspended</span>', array('controller' => 'Newsrooms', 'action' => 'suspended'), array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'newsrooms'&&$action=="disapproved") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Disapproved</span>', array('controller' => 'Newsrooms', 'action' => 'disapproved'), array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'takeovercompanies'&&$action=="index") { ?> class="active" <?php } ?>>
                        <?php  
                        echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Company Take Over Request</span>','/company-takeover-request', array('escape' => false)) ?>
                    </li> 
                </ul>
            </li> 

            <li class="treeview <?php if (in_array($controller, array('pressreleases')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-delicious"></i>
                    <span>PR manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                   <li <?php if ($controller == 'pressreleases'&&$action=="add") { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Add New PR</span>', array('controller' => 'PressReleases', 'action' => 'add'), array('escape' => false)) ?>
                    </li> 

                    <li <?php if ($controller == 'pressreleases'&&$action=="pending") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Pending PR</span>', array('controller' => 'PressReleases', 'action' => 'pending'), array('escape' => false)) ?>
                    </li>

                    <?php 
                    if(isset($draftcount)&&($draftcount>0)){?>
                    <li <?php if ($controller == 'pressreleases'&&$action=="draft") { ?> class="active" <?php } ?>><?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Draft PR</span>', array('controller' => 'PressReleases', 'action' => 'draft'), array('escape' => false)) ?>
                    </li> 
                    <?php } ?>

                    <li <?php if ($controller == 'pressreleases'&&$action=="index") { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Approved PR</span>', array('controller' => 'PressReleases', 'action' => 'index'), array('escape' => false)) ?>
                    </li>

                    <li <?php if ($controller == 'pressreleases'&&$action=="embargoed") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Embargoed PR</span>', array('controller' => 'PressReleases', 'action' => 'embargoed'), array('escape' => false)) ?>
                    </li>
                    
                    <li <?php if ($controller == 'pressreleases'&&$action=="disapproved") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Disapproved PR</span>', array('controller' => 'PressReleases', 'action' => 'disapproved'), array('escape' => false)) ?>
                    </li>
                     <li <?php if ($controller == 'pressreleases'&&$action=="trashed") { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Trashed PR</span>', array('controller' => 'PressReleases', 'action' => 'trashed'), array('escape' => false)) ?>
                    </li>
                </ul>
            </li> 
            
            <li class="treeview <?php if (in_array($controller, array('sendy')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-delicious"></i>
                    <span>Sendy manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                <li <?php if ($controller == 'sendy'&&$action=='index') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Lists</span>', array('controller' => 'sendy', 'action' => 'index'), array('escape' => false)) ?>
                </li> 
                <li <?php if ($controller == 'sendy'&&$action=='add') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Add list</span>', array('controller' => 'sendy', 'action' => 'add'), array('escape' => false)) ?>
                </li>   
               <!--  <li>
                    <a href="<?php echo SITEFRONTURL."sendy/app?i=1" ?>"><i class="fa fa-circle-o"></i> <span>Sendy Dashboard</span></a>
                </li>  -->
                   
                </ul>
            </li>

             <!-- Post manager--> 
            <li class="treeview <?php if (in_array($controller, array('plans', 'plancategories','distributions')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-delicious"></i>
                    <span>PR Plans manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'plans'&&$action=='index') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Plans</span>', array('controller' => 'Plans', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'plans'&&$action=='add') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Create plan</span>', array('controller' => 'Plans', 'action' => 'add'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'plancategories') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Plan name manager</span>', array('controller' =>'PlanCategories', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'distributions'&&$action='index') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Distribution Option manager</span>', array('controller' =>'Distributions', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'plans'&&$action=='assign_plans') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Assign plan to client</span>', array('controller' =>'Plans', 'action' => 'assign_plans'), array('escape' => false)) ?>
                    </li>
                </ul>
            </li> 


            <li class="treeview <?php if (in_array($controller, array('coupons')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-delicious"></i>
                    <span>Coupon manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'coupons'&&$action=='index') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Coupons</span>', array('controller' =>'Coupons', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'coupons'&&$action=='add') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Add new coupon</span>', array('controller' =>'Coupons', 'action' => 'add'), array('escape' => false)) ?>
                    </li>
                </ul>
            </li> 


            <li class="treeview <?php if (in_array($controller, array('pages', 'pagetemplates')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Page manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'pages'&& $action == 'index') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o text-aqua"></i> <span>All pages</span>', array('controller' => 'pages', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                       <li <?php if ($controller == 'pages' && $action == 'add') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Add New Page</span>', array('controller' => 'pages', 'action' => 'add'), array('escape' => false)) ?>
                    </li>
                   <!--  <li <?php if ($controller == 'pagetemplates') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Page template</span>', array('controller' => 'pagetemplates', 'action' => 'index'), array('escape' => false)) ?>
                    </li> -->
                </ul>
            </li>
            <li class="treeview <?php if (in_array($controller, array('staffuserspages')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Users Pages manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'staffuserspages'&& $action == 'index') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o text-aqua"></i> <span>All pages</span>', array('controller' => 'StaffUsersPages', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                       <li <?php if ($controller == 'staffuserspages' && $action == 'add') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Add New Page</span>', array('controller' => 'StaffUsersPages', 'action' => 'add'), array('escape' => false)) ?>
                    </li>
                </ul>
            </li>

            <li class="treeview <?php if (in_array($controller, array('categories','msas','companies','organizationtypes')) && $action != 'dashboard' ) { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-fw fa-database"></i>
                    <span>Master manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'categories') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Category</span>', array('controller' => 'categories', 'action' => 'index'), array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'msas') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>MSA</span>', array('controller' => 'msas', 'action' => 'index'), array('escape' => false)) ?>
                    </li> 
                    <li <?php if ($controller == 'organizationtypes') { ?> class="active" <?php } ?>>
                        <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Organization type</span>', array('controller' => 'organizationTypes', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                </ul>
            </li> 

            <li class="treeview <?php if (in_array($controller, array('invoices')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-bank"></i>
                    <span>Invoice manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'invoices'&& $action == 'index') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o text-aqua"></i> <span>Plan Invoices</span>', array('controller' => 'invoices', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'invoices'&& $action == 'prinvoice') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o text-aqua"></i> <span>PR Invoices</span>', array('controller' => 'invoices', 'action' => 'prinvoice'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'invoices'&&$action=='settings') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Invoice PDF settings</span>', array('controller' => 'invoices', 'action' => 'settings',2), array('escape' => false)) ?>
                    </li>
                </ul>
            </li>
            
             <li class="treeview <?php if (in_array($controller, array('carts')) && $action != 'dashboard' ) { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Cart Manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                     <li <?php if ($controller == 'carts'&& $action == 'index') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o text-aqua"></i> <span>Plan Cart</span>', array('controller' => 'carts', 'action' => 'index'), array('escape' => false)) ?>
                    </li>

                    <li <?php if ($controller == 'carts'&& $action == 'prcart') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o text-aqua"></i> <span>PR Cart</span>', array('controller' => 'carts', 'action' => 'prcart'), array('escape' => false)) ?>
                    </li>

                </ul>
            </li> 
            <li class="treeview <?php if (in_array($controller, array('clippingreports')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-delicious"></i>
                    <span>Clipping report manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'clippingreports'&&$action=='index') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Clipping Reports</span>', array('controller' => 'ClippingReports', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'clippingreports'&&$action=='subscriber_logs') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Subscriber Mail List</span>', array('controller' => 'ClippingReports', 'action' => 'subscriber_logs'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'clippingreports'&&$action=='settings') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Reports PDF settings</span>', array('controller' => 'ClippingReports', 'action' => 'settings',1), array('escape' => false)) ?>
                    </li>
                </ul>
            </li>

            <li class="treeview <?php if (in_array($controller, array('networkwebsites')) && $action != 'dashboard') { ?> active <?php } ?>">
                <a href="#">
                    <i class="fa fa-delicious"></i>
                    <span>Network Websites manager</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li <?php if ($controller == 'networkwebsites'&&$action=='index') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>All Network Websites</span>', array('controller' => 'NetworkWebsites', 'action' => 'index'), array('escape' => false)) ?>
                    </li>
                    <li <?php if ($controller == 'networkwebsites'&&$action=='add') { ?> class="active" <?php } ?>>
                    <?php echo $this->Html->link('<i class="fa fa-circle-o"></i> <span>Add Network Website</span>', array('controller' => 'NetworkWebsites', 'action' => 'add'), array('escape' => false)) ?>
                    </li>
                </ul>
            </li>

            <li <?php if ($controller == 'emailtemplates') { ?> class="active" <?php } ?> >
                <a href="<?php echo SITEURL; ?>emailTemplates"> 
                    <i class="fa fa-envelope-o"></i>
                    <span>Email Templates</span> 
                </a>               
            </li>


            <li <?php if ($controller == 'socialshares') { ?> class="active" <?php } ?> >
                <a href="<?php echo SITEURL; ?>socialShares"> 
                    <i class="fa fa-share-alt"></i>
                    <span>Social Shares</span> 
                </a>               
            </li>

            <li <?php if ($controller == 'advertisements') { ?> class="active" <?php } ?> >
                <a href="<?php echo SITEURL; ?>advertisements"> 
                    <i class="fa fa-bullhorn"></i>
                    <span>Advertisements</span> 
                </a>               
            </li>

            <li <?php if ($controller =='queries') { ?> class="active" <?php } ?> >
                <a href="<?php echo SITEURL; ?>queries"> 
                    <i class="fa fa-question-circle"></i> 
                      <span>Queries</span> 
                </a>               
            </li>


            <li <?php if ($controller == 'settings') { ?> class="active" <?php } ?> >
                <a href="<?php echo SITEURL; ?>settings"> 
                    <i class="fa fa-wrench"></i>
                    <span>Settings</span> 
                </a>               
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
