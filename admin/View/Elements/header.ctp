<?php
        echo $this->Html->css(
                array(
                    '/plugins/fontawesome/css/all.min.css',
                    '/plugins/select2/css/select2.min',
                    '/plugins/dist/css/adminlte.min.css',
                    '/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
                    '/plugins/jquery-ui/jquery-ui',
                    '/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min', 
                    '/plugins/icheck-bootstrap/icheck-bootstrap.min',
                    '/plugins/fancybox/jquery.fancybox.min',
                    '/plugins/sweetalert2/sweetalert2.min',
                    '/plugins/toastr/toastr.min',  
                    "custom", 
                    '/css/admin/custom.css',
                    '/css/admin/loader.css',
                    '/css/admin/theme.min.css',
                    '/css/admin/AdminLTE.css',
                )
            ); ?>
        <?php echo $this->Js->writeBuffer(array('cache'=>true));  ?>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link custom-main-item" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <h3><?php echo $siteName; ?></h3>
      </li>

    </ul>
    <ul class="navbar-nav ml-auto">
      
      <li class="nav-item">
      <?php echo $this->element('header_conditional_menu'); ?>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-user"></i>

        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <?php
              $profile = (!empty($this->Session->read('Auth.User.profile_image'))) ? $this->Session->read('Auth.User.profile_image') : '';
              if (!empty($this->Session->read('Auth.User.first_name'))) {
                $userName = $this->Session->read('Auth.User.first_name') . ' ' . $this->Session->read('Auth.User.last_name');
              }

              if (file_exists(WWW_ROOT . 'files/profile_image/' . $profile) && $profile != '') {
                echo $this->Html->image('/files/profile_image/' . $profile, array('class' => 'img-size-50 mr-3 img-circle'));
              } else {
                echo $this->Html->image('avatar5.png', array('class' => 'img-size-50 mr-3 img-circle'));
              }
              ?>
              <div class="media-body">
                <h3 class="dropdown-item-title text-center">
                  <?php echo ucfirst($userName);  ?>
                  <!-- <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span> -->
                </h3>
                <p class="text-sm text-center"><?php echo $this->Custom->getRoleById($this->Session->read('Auth.User.staff_role_id'), $this->Session->read('Auth.User.id'));  ?></p>
                <!-- <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p> -->
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <?php echo $this->Html->link("Sign out", array("controller" => 'staffUsers', 'action' => 'logout', 'admin' => false), array('class' => 'dropdown-item dropdown-footer')); ?>
        </div>
      </li>
    </ul>
    <?php 
            echo $this->Html->script(
                array(
                    '/plugins/toastr/toastr.min',
                    '/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min',
                    '/plugins/moment/moment.min',
                    '/plugins/bootstrap/js/popper.min',
                    "/plugins/bootstrap/js/bootstrap.min",
                    "/plugins/bootstrap/js/bootstrap.bundle.min",
                    '/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min',
                    '/plugins/dist/js/adminlte', 
                    "custom",
                    '/js/app.js',
                    '/js/custom.js',
                    '/js/ResizeSensor.js',
                    '/js/jquery-ui.min.js',
                    '/js/jquery-1.11.3.min.js',
                    '/js/demo.js',
                    '/js/dashboard.js',
                    '/js/dashboard2.js',
                    '/js/cropper.min.js',
                    '/js/bootstrap-dialog.js',
                    '/js/bootbox.min.js',
                )
            );
        echo $this->Js->writeBuffer();

        ?> 
  </nav>
  <!-- /.navbar -->