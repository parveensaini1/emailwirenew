  <div class="row">
    <div class="col-lg-12">
      <div class="ew-title full"><?php echo $title_for_layout; ?></div>
    </div>
  </div>
  <?php if (!empty($plan_list)) { ?>
    <section class="content-section">
      <div class="box">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-sm-4">
              <?php echo $userDetail['StaffUser']['email']; ?>
            </div>
            <div class="col-sm-8 text-right">
              <?php echo ucfirst($userDetail['StaffUser']['first_name']) . ' ' . $userDetail['StaffUser']['last_name']; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="row">
      <div class="col-sm-12">
        <div class="box">
          <!-- /.box-header -->
          <div class="box-body">
            <div class="dataTable_wrapper table-responsive">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                  <thead>
                      <?php
                          $tableHeaders = $this->Html->tableHeaders(array(
                          __("S/N"),
                          __("Plans"),
                          __("Remaining"),
                          __("Info"), 
                              ), array(), array('class' => 'sorting'));
                      ?>
                  </thead>
                  <tbody>
                      <?php
                      $rows = array(); 
                      $counter=1;
                      foreach ($plan_list as $loop => $data){
                        $price = ($data['Plan']['bulk_discount_amount'] > 0) ? $currencySymbol . $data['Plan']['bulk_discount_amount'] : $currencySymbol . $data['Plan']['price'];

                        $prnumber = ($data['Plan']['plan_type'] =='single' && !empty($data['PlanCategory']['word_limit']) && $data['PlanCategory']['word_limit'] >0 )? " - ".$data['PlanCategory']['word_limit']. " words":"";
                        $name="<h4>".$data['PlanCategory']['name']. $prnumber ."</h4>";
                        $name .="<br>".$data['PlanCategory']['name']." - ".$price. " (".$data['Plan']['number_pr']. ")";
                        $assignedFrom = ($data['RemainingUserPlan']['assign_from'] != "fronted") ? "Assigned By : Admin": "Purchased By : Client";
                        
                          $rows[] = array(
                            __($counter),
                            __($name), 
                            _($data['RemainingUserPlan']['number_pr']),
                            __($assignedFrom),
                        );
                    $counter++;
                  }
                  echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                      ?>
                  </tbody>
              </table>
            </div>
        </div>
      </div>
    </div>
  <?php } else {
    echo $this->Custom->getRecordNotFoundMsg();
  } ?>