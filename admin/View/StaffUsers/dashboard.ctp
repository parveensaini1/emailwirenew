<!-- Content Header (Page header) -->
<?php
$role = $this->Session->read('Auth.User.StaffRole');
//echo $flatsCount;die;
?>

 
<section class="content">
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fas fa-clock text-white"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><a href="<?php echo SITEURL . 'PressReleases/pending'; ?>">Pending PR</a></span>
          <span class="info-box-number"><?php echo $pending_pr_count; ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fas fa-newspaper"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><a href="<?php echo SITEURL . 'newsrooms/pending'; ?>">Pending Newsroom</a></span>
          <span class="info-box-number"><?php echo count($pending_newsrooms); ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-olive color-palette"><i class="fas fa-user"></i></span>
        <!-- <i class="ion ion-ios-cart-outline"></i> -->

        <div class="info-box-content">
          <span class="info-box-text"><a href="<?php echo SITEURL . 'subscribers'; ?>">Subscriber</a></span>
          <span class="info-box-number"><?php echo $subscriber_count; ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-teal disabled color-palette"><i class="fas fa-users"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><a href="<?php echo SITEURL . 'clients'; ?>">Cleints</a></span>
          <span class="info-box-number"><?php echo $client_count; ?></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-navy disabled color-palette"><i class="fas fa-cart-plus"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><a href="<?php echo SITEURL . 'carts/prcart'; ?>">PR in cart</a></span>
          <span class="info-box-number"><?php echo $cout_pr_cart_payment; ?></span>
          <span class="info-box-text"><a onclick="sendremaindermail('pr')" href="javascript:void(0)">Send remainder mail</a> </span>
        </div>
        <!-- /.info-box-content -->
      </div>
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-orange color-palette"><i class="fas fa-shopping-cart text-white"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><a href="<?php echo SITEURL . 'carts'; ?>">Plans in cart</a></span>
          <span class="info-box-number"><?php echo $count_cart_payment; ?></span>
          <?php if ($count_cart_payment > 0) { ?>
            <span class="info-box-text"><a onclick="sendremaindermail('plan')" href="javascript:void(0)">Send remainder mail</a> </span>
          <?php } ?>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>
  </div>
  <div class="row">
    <div class="col-sm-6">
      <div class="card card-teal direct-chat direct-chat-primary">
        <div class="card-header">
          <h3 class="card-title">Latest Approved PR</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="card-body">
          <div class="table-responsive">
          <table class="table no-margin table-striped table-bordered table-hover" id="dashtableid">
              <thead>
                <tr>
                  <th>S/N</th>
                  <th>title</th>
                </tr>
              </thead>
              <tbody>
                <?php
                //echo "<pre>";
                //print_r($approved_pr);die;
                $i = 0;
                foreach ($approved_pr  as $approved_pr_details) {
                  $i++;
                ?>
                  <tr>
                    <td>
                      <?php echo $i; ?>
                    </td>
                    <?php
                    $approved_url    =   SITEURL . 'PressReleases/view/' . $approved_pr_details['PressRelease']['id'] . '/approved';
                    ?>

                    <td>
                      <a href="<?php echo $approved_url; ?>">
                        <?php
                        echo $approved_pr_details['PressRelease']['title'];
                        ?>
                      </a>
                    </td>

                  </tr>
                <?php
                }
                ?>

              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          <a href="<?php echo  SITEURL . 'PressReleases'; ?>" class="btn btn-sm btn-info float-right">View All PR</a>
        </div>
        <!-- /.box-footer -->
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card card-teal direct-chat direct-chat-primary">
        <div class="card-header">
          <h3 class="card-title">Pending newsroom</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="card-body">
          <div class="table-responsive">
            <table class="table no-margin table-striped table-bordered table-hover" id="dataTables-example">
              <thead>
                <?php
                $tableHeaders = $this->Html->tableHeaders(array(
                  __('S/N'),
                  __('Company name'),
                  // __('Actions'),
                ), array(), array('class' => 'sorting'));
                echo $tableHeaders;
                ?>
              </thead>
              <tbody>
                <?php
                $rows = array();

                if (count($pending_newsrooms) > 0) {
                  foreach ($pending_newsrooms as $index => $data) {
                    $actions = "";
                    //$actions = ' ' . $this->Html->link(__('View'), array('controller' =>'newsrooms', 'action' => 'view', $data['Company']['id'],"pending"), array('class' => 'btn btn-xs btn-success'));

                    $title = $this->Html->link($data["Company"]['name'], array('controller' => 'newsrooms', 'action' => 'view', $data['Company']['id'], "pending"), array('class' => 'link'));
                    $rows[] = array(
                      __($index + 1),
                      __($title),
                      // $actions,
                    );
                  }
                  echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                } else {
                ?>
                  <td align="center" colspan="2">
                    <div class="alert alert-dismissable label-default fade in">
                      <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                      No record found.
                    </div>
                  </td>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          <a href="<?php echo SITEURL . 'newsrooms/pending'; ?>" class="btn btn-sm btn-info float-right">View All PR</a>
        </div>
        <!-- /.box-footer -->
      </div>
    </div>
  </div>
</section>