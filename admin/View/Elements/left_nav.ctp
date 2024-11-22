<?php  
$controller = strtolower(trim($this->params['controller']));
$action = strtolower(trim($this->params['action'])); 
?>
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo SITEFRONTURL;?>" target="_blank" class="brand-link">
      <img src="<?php echo SITEURL; ?>img/emailwire_logo_final.png" alt="<?php echo $siteName;?> Logo" class="brand-image img-"
           style="opacity: .8">
      <span class="brand-text font-weight-light" style="color:white">EW</span>
    </a>

    <!-- Sidebar -->
    <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
    
    <div class="sidebar"> 
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
             <?php
              $activeClass=($controller == 'staffUsers' && $action == 'dashboard')?"active":"";
              echo $this->Html->link('<i class="nav-icon fas fa-tachometer-alt"></i> <p>Dashboard</p>', array('controller' => 'staffUsers','action'=>'dashboard'), array('class'=>"nav-link $activeClass",'escape' => false)); 
              unset($activeClass);
              ?> 
          </li>  
          <?php if(isset($role_id)&&in_array($role_id,$accessAllow)){ ?>
           <li class="nav-item has-treeview <?php if (in_array($controller, array('staffusers')) && $action != 'dashboard'){ echo "menu-open"; } ?>">
              <a href="#" class="nav-link pushmenu-custom">
                <i class="nav-icon fas fa-user"></i>
                <p>User Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <?php
                      $activeClass=($controller == 'staffusers' &&$action=='administrators'&&$this->request->url=="administrators")?"active":"";
                    echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Administrators</p>', '/administrators', array('class'=>"nav-link $activeClass" ,'escape' => false));
                    unset($activeClass);
                    ?> 
                </li>  

                <li class="nav-item">
                  <?php
                      $activeClass=($controller == 'staffusers' && $action == 'editors'&&$this->request->url=="editors")?"active":"";
                    echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Editors</p>', '/editors', array('class'=>"nav-link $activeClass" ,'escape' => false));
                    unset($activeClass);
                    ?> 
                </li> 

                
                <li class="nav-item">
                  <?php
                      $activeClass=($controller == 'staffusers' && $action == 'clients'&&$this->request->url=="clients")?"active":"";
                    echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Clients</p>', '/clients', array('class'=>"nav-link $activeClass" ,'escape' => false));
                    unset($activeClass);
                    ?> 
                </li> 
                <li class="nav-item">
                  <?php
                      $activeClass=($controller == 'staffusers' && $action == 'subscribers'&&$this->request->url=="subscribers")?"active":"";
                    echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Subscribers</p>', '/subscribers', array('class'=>"nav-link $activeClass" ,'escape' => false));
                    unset($activeClass);
                    ?> 
                </li> 
              </ul>
            </li>  
            <li class="nav-item has-treeview <?php if (in_array($controller, array('newsrooms','takeovercompanies')) && $action != 'dashboard'){ echo "menu-open"; } ?>">
              <a href="#" class="nav-link pushmenu-custom">
              <i class="nav-icon fab fa-delicious"></i>
                <p> Newsroom Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'newsrooms' && $action == 'create_newsroom')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Create newsroom</p>', array('controller' => 'newsrooms', 'action' => 'create_newsroom'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'newsrooms' && $action == 'pending')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Pending</p>', array('controller' => 'newsrooms', 'action' => 'pending'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'newsrooms' && $action == 'published')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Published</p>', array('controller' => 'newsrooms', 'action' => 'published'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'newsrooms' && $action == 'suspended')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Suspended</p>', array('controller' => 'newsrooms', 'action' => 'suspended'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'newsrooms' && $action == 'disapproved')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Disapproved</p>', array('controller' => 'newsrooms', 'action' => 'disapproved'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'newsrooms' && $action == 'takeovercompanies')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Takeovercompanies</p>', array('controller' => 'newsrooms', 'action' => 'suspended'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
              </ul>
            </li>
            
            <li class="nav-item has-treeview <?php if (in_array($controller, array('pressreleases')) && $action != 'dashboard') { ?> active menu-open <?php } ?>">
              <a href="#" class="nav-link pushmenu-custom">
              <i class="nav-icon fab fa-usps"></i>
                <p> PR Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'pressreleases' && $action == 'add')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Add New PR</p>', array('controller' => 'PressReleases', 'action' => 'add'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'pressreleases' && $action == 'pending')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Pending PR</p>', array('controller' => 'PressReleases', 'action' => 'pending'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 

                  <?php 
                    if(isset($draftcount)&&($draftcount>0)){?>
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'pressreleases' && $action == 'draft')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Draft</p>', array('controller' => 'PressReleases', 'action' => 'draft'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> <?php } ?>

                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'pressreleases' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Approved PR</p>', array('controller' => 'PressReleases', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 

                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'pressreleases' && $action == 'embargoed')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Embargoed PR</p>', array('controller' => 'PressReleases', 'action' => 'embargoed'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'pressreleases' && $action == 'disapproved')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Disapproved PR</p>', array('controller' => 'PressReleases', 'action' => 'disapproved'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'pressreleases' && $action == 'trashed')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Trashed PR</p>', array('controller' => 'PressReleases', 'action' => 'trashed'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li>  
              </ul>
            </li>


            <li class="nav-item has-treeview <?php if (in_array($controller, array('sendy')) && $action != 'dashboard') { ?> active menu-open <?php } ?>">
              <a href="#" class="nav-link pushmenu-custom">
              <i class="nav-icon fas fa-server"></i>
                <p> Sendy Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'sendy' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Lists</p>', array('controller' => 'sendy', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'sendy' && $action == 'add')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Add List</p>', array('controller' => 'sendy', 'action' => 'add'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li>

              </ul>
            </li>
             <!-- Post manager--> 
             
            <li class="nav-item has-treeview <?php if (in_array($controller, array('plans', 'PlanCategories','distributions')) && $action != 'dashboard') { ?> active menu-open<?php } ?>">
              <a href="#" class="nav-link pushmenu-custom">
              <i class= "nav-icon fas fa-folder-plus"></i>
                <p> Pr Plans <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'plans' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Plans</p>', array('controller' => 'Plans', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'plans' && $action == 'add')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Create Plan</p>', array('controller' => 'Plans', 'action' => 'add'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  
                  <li class="nav-item">
                  <?php
                      $activeClass=($controller == 'plancategories' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Plan name manager</p>', array('controller' => 'PlanCategories', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'distributions' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Distribution Option Manager</p>', array('controller' => 'Distributions', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 

                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'plans' && $action == 'assign_plans')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Assign Plans to Clients</p>', array('controller' => 'Plans', 'action' => 'assign_plans'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li>      
              </ul>
            </li>

                  <li class="nav-item has-treeview <?php if (in_array($controller, array('coupons')) && $action != 'dashboard') { ?> active menu-open<?php } ?>">
              <a href="#" class="nav-link pushmenu-custom">
              <i class="nav-icon fas fa-gift"></i>
                <p> Coupon Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'coupons' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Coupons</p>', array('controller' => 'Coupons', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'coupons' && $action == 'add')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Add New Coupons</p>', array('controller' => 'Coupons', 'action' => 'add'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li>
              </ul>
            </li>

                  <li class="nav-item has-treeview <?php if (in_array($controller, array('pages', 'pagetemplates')) && $action != 'dashboard') { ?> active menu-open<?php } ?>">
              <a href="#" class="nav-link pushmenu-custom">
              <i class="nav-icon fas fa-book"></i>
                <p> Page Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'pages' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>All pages</p>', array('controller' => 'pages', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'pages' && $action == 'add')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Add New Page</p>', array('controller' => 'pages', 'action' => 'add'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li>
              </ul>
            </li>

                  <li class="nav-item has-treeview <?php if (in_array($controller, array('staffuserspages')) && $action != 'dashboard') { ?> active menu-open <?php } ?>">
              <a href="#" class="nav-link pushmenu-custom">
              <i class="nav-icon fas fa-file"></i>
                <p> Users Pages <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'staffuserspages' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>All Pages</p>', array('controller' => 'StaffUsersPages', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'staffuserspages' && $action == 'add')?"active":"";
                      echo $this->Html->link('<i class=" text-info nav-icon far fa-circle nav-icon"></i> <p>Add New Page</p>', array('controller' => 'StaffUsersPages', 'action' => 'add'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li>
              </ul>
            </li>
            

            <li class="nav-item has-treeview <?php if (in_array($controller, array('categories','msas','companies','organizationtypes','countries','states','languages')) && $action != 'dashboard'){ echo "menu-open"; } ?>">
              <a href="#" class="nav-link pushmenu-custom">
                <i class="nav-icon fas fa-fw fa-database"></i>
                <p> Master Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
              <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'languages' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Languages</p>', array('controller' => 'languages', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'categories' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Category</p>', array('controller' => 'Categories', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'msas' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>MSA</p>', array('controller' => 'msas', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'organizationtypes' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Organization Type</p>', array('controller' => 'organizationTypes', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li>
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'countries' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Countries</p>', array('controller' => 'countries', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'states' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>States</p>', array('controller' => 'states', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
              </ul>
            </li> 



            <li class="nav-item has-treeview <?php if (in_array($controller, array('invoices')) && $action != 'dashboard'){ echo "menu-open"; } ?>">
              <a href="#" class="nav-link pushmenu-custom">
                <i class="nav-icon fas fa-th"></i>
                <p> Invoice Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'invoices' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Plan Invoices</p>', array('controller' => 'invoices', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'invoices' && $action == 'prinvoice')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>PR Invoices</p>', array('controller' => 'invoices', 'action' => 'prinvoice'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'invoices' && $action == 'settings')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Invoice PDF settings</p>', array('controller' => 'invoices', 'action' => 'settings','2'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  
              </ul>
            </li> 

            <li class="nav-item has-treeview <?php if (in_array($controller, array('carts')) && $action != 'dashboard' ){ echo "menu-open"; } ?>">
              <a href="#" class="nav-link pushmenu-custom">
                <i class="nav-icon fas fa-cart-arrow-down"></i>
                <p> Cart Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'carts' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Plan Cart</p>', array('controller' => 'carts', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'carts' && $action == 'prcart')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>PR Cart</p>', array('controller' => 'carts', 'action' => 'prcart'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li>                   
              </ul>
            </li> 
            
            <li class="nav-item has-treeview <?php if (in_array($controller, array('clippingreports')) && $action != 'dashboard' ){ echo "menu-open"; } ?>">
              <a href="#" class="nav-link pushmenu-custom">
                <i class="nav-icon fas fa-clipboard"></i>
                <p> Clipping Report<i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'clippingreports' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Clipping Reports</p>', array('controller' => 'ClippingReports', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'clippingreports' && $action == 'subscriber_logs')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Subscriber Mail List</p>', array('controller' => 'ClippingReports', 'action' => 'subscriber_logs'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'clippingreports' && $action == 'settings')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Reports PDF settings</p>', array('controller' => 'ClippingReports', 'action' => 'settings','1'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 
                                
              </ul>
            </li> 
            
            <li class="nav-item has-treeview <?php if (in_array($controller, array('networkwebsites')) && $action != 'dashboard'){ echo "menu-open"; } ?>">
              <a href="#" class="nav-link pushmenu-custom">
                <i class="nav-icon fas fa-project-diagram">
                </i>
                <p> Network Websites  <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview"> 
                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'networkwebsites' && $action == 'index')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>All Network Websites</p>', array('controller' => 'NetworkWebsites', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li> 

                  <li class="nav-item">
                    <?php
                        $activeClass=($controller == 'networkwebsites' && $action == 'add')?"active":"";
                      echo $this->Html->link('<i class="text-info far fa-circle nav-icon"></i> <p>Add Network Website</p>', array('controller' => 'NetworkWebsites', 'action' => 'add'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                      unset($activeClass);
                      ?> 
                  </li>            
              </ul>
            </li>

            <li class="nav-item">
              <?php
                $activeClass=($controller == 'emailtemplates' && $action == 'dashboard')?"active":"";
                echo $this->Html->link('<i class="nav-icon fas fa-envelope"></i> <p>Email Templates</p>', array('controller' => 'emailTemplates','action'=>'index'), array('class'=>"nav-link $activeClass",'escape' => false)); 
                unset($activeClass);
                ?> 
            </li> 

            <li class="nav-item">
              <?php
                $activeClass=($controller == 'socialshares' && $action == 'dashboard')?"active":"";
                echo $this->Html->link('<i class="nav-icon fas fa-share-alt"></i> <p>Social Shares</p>', array('controller' => 'socialShares','action'=>'index'), array('class'=>"nav-link $activeClass",'escape' => false)); 
                unset($activeClass);
                ?> 
            </li> 
            <li class="nav-item">
              <?php
                $activeClass=($controller == 'advertisements' && $action == 'dashboard')?"active":"";
                echo $this->Html->link('<i class="nav-icon fas fa-bullhorn"></i> <p>Advertisements</p>', array('controller' => 'advertisements','action'=>'index'), array('class'=>"nav-link $activeClass",'escape' => false)); 
                unset($activeClass);
                ?> 
            </li> 
            <li class="nav-item">
              <?php
                $activeClass=($controller == 'queries' && $action == 'dashboard')?"active":"";
                echo $this->Html->link('<i class="nav-icon fas fa-question-circle"></i> <p>Queries</p>', array('controller' => 'queries','action'=>'index'), array('class'=>"nav-link $activeClass",'escape' => false)); 
                unset($activeClass);
                ?> 
            </li>   
            <li class="nav-item">
              <?php
                $activeClass=($controller == 'settings' && $action == 'dashboard')?"active":"";
                echo $this->Html->link('<i class="nav-icon fas fa-wrench"></i> <p>Settings</p>', array('controller' => 'settings','action'=>'index'), array('class'=>"nav-link $activeClass",'escape' => false)); 
                unset($activeClass);
                ?> 
            </li>   
            
           


            
            <?php if($role_id==1&&$this->Session->read('Auth.User.sso_id')=="hiteshvermadoit"){?>
            <li class="nav-item has-treeview <?php if (in_array($controller, array('settings','useractions')) && $action != 'dashboard'){ echo "menu-open"; } ?>">
              <a href="#" class="nav-link pushmenu-custom">
                <i class="nav-icon fas fa-cog"></i>
                <p> Developer Manager <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <?php
                      $activeClass=($controller == 'settings' && $action == 'index')?"active":"";
                    echo $this->Html->link('<i class="far fa-circle nav-icon"></i> <p>Settings</p>', array('controller' => 'settings', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                    unset($activeClass);
                    ?> 
                </li>

                 <li class="nav-item">
                  <?php
                      $activeClass=($controller == 'useractions' && $action == 'index')?"active":"";
                    echo $this->Html->link('<i class="far fa-circle nav-icon"></i> <p>Permissions</p>', array('controller' => 'useractions', 'action' => 'index'), array('class'=>"nav-link $activeClass" ,'escape' => false));
                    unset($activeClass);
                    ?> 
                </li> 
              </ul>
            </li> 
            <?php } ?>
            
          <?php }elseif(isset($role_id)&&$role_id==4){ // College Menus ?>
            <li class="nav-item has-treeview <?php if (in_array($controller, array('collegerequirements','donors')) && $action != 'dashboard'){ echo "menu-open"; } ?>">
              <a href="#" class="nav-link pushmenu-custom">
                <i class=" fas fa-hand-holding-usd"></i>
                <p> Donations Requirements <i class="fas fa-angle-left right"></i> </p>
              </a>
              <ul class="nav nav-treeview">
              <li class="nav-item">
                    <?php
                      $activeClass=($controller == 'collegerequirements' && $action == 'dashboard')?"active":"";
                      echo $this->Html->link('<i class="text-info nav-icon far fa-circle"></i> <p>All Requirements</p>', array('controller' =>'collegerequirements','action'=>'index'), array('class'=>"nav-link $activeClass",'escape' => false)); 
                      unset($activeClass);
                      ?> 
                  </li> 
                <li class="nav-item">
                  <?php
                    $activeClass=($controller == 'collegerequirements' && $action == 'add')?"active":"";
                    echo $this->Html->link('<i class="text-info nav-icon far fa-circle"></i> <p>Add College Requirements</p>', array('controller' =>'collegerequirements','action'=>'add'), array('class'=>"nav-link $activeClass",'escape' => false)); 
                    unset($activeClass);
                    ?> 
                  </li> 
                  <li class="nav-item">
                  <?php
                    $activeClass=($controller == 'donors' && $action == 'index')?"active":"";
                    echo $this->Html->link('<i class="text-info nav-icon far fa-circle"></i> <p>All Donors</p>', array('controller' =>'donors','action'=>'index'), array('class'=>"nav-link $activeClass",'escape' => false)); 
                    unset($activeClass);
                    ?> 
                  </li>
                  <li class="nav-item">
                  <?php
                    $activeClass=($controller == 'donors' && $action == 'requesteddonor')?"active":"";
                    echo $this->Html->link('<i class="text-info nav-icon far fa-circle"></i> <p>All Requested Donors</p>', array('controller' =>'donors','action'=>'index'), array('class'=>"nav-link $activeClass",'escape' => false)); 
                    unset($activeClass);
                    ?> 
                  </li> 
              </ul>
            </li>    
          <?php } ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
          </div>
    <!-- /.sidebar -->
  </aside>
<style>
@media only screen and (max-width: 768px) {
    .brand-image.img-{
        margin-left: 70px;
    }
}

    .brand-image.img-{
        margin-top:-20px;
    }
    .brand-image.img- {
    transition: width 0.5s ease, max-height 0.5s ease;
}

</style>
<script>
$(document).ready(function() {
    //var expandedImageSrc = "<?php echo SITEURL; ?>img/expanded_logo.jpeg";
    //var collapsedImageSrc = "<?php echo SITEURL; ?>img/emailwire_logo_final.png";

    // $('.brand-text.font-weight-light').text('');
    $('.custom-main-item').on('click', function(e) {
            // Check each .pushmenu-custom element to see if it has the 'prevent-click' class
            $('.pushmenu-custom').each(function() {
                if ($(this).hasClass('prevent-click')) {
                    // If 'prevent-click' class is present, remove it
                    $(this).removeClass('prevent-click');
                    $('.brand-image.img-').css('margin-left', '.8rem');
                    $('.brand-image.img-').css('margin-top', '-20px');
                    $('.brand-image.img-').css('width', '');
                    $('.brand-image.img-').css('max-height', '');
                     //$('.brand-image').attr('src', collapsedImageSrc);
                    console.log('prevent-click class removed from', this); // Logging the element from which the class was removed
                } else {
                    $(this).addClass('prevent-click');
                    $('.brand-image.img-').css('margin-left', '70px');
                    $('.brand-image.img-').css('margin-top', '-20px');
                    $('.brand-image.img-').css('width', '25%');
                    $('.brand-image.img-').css('max-height', '55px');
                     //$('.brand-image').attr('src', expandedImageSrc);
                }
            });
        });
    $('.pushmenu-custom').on('click', function(e) {
        // Check if the clicked element has the class 'prevent-click'
        if ($(this).hasClass('prevent-click')) {
            // Code for what should happen when 'prevent-click' is present
            console.log('Click on element prevented');
        } else {
            // Check if the 'body' does not have the class 'prevent-click'
            if (!$('.nav-link').hasClass('prevent-click')) {
                // This block is executed if 'body' does not have 'prevent-click' class
                console.log('No prevent-click class on body, performing actions');
                $('.custom-main-item').click(); // Trigger click on '.custom-main-item'
                $(this).addClass('prevent-click'); // Add 'prevent-click' to prevent future clicks
                
            } else {
                // Optionally handle the case where 'body' has the 'prevent-click' class
                console.log('Body has prevent-click class, action blocked');
            }
        }
    });
});
</script>






