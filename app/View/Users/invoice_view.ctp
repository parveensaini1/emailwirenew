<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?>   <?php if(isset($transdata['PressRelease'])&&!empty($transdata['PressRelease'])){ echo " - ".$transdata['PressRelease']['title']; } ?> </div></div>
</div>
<!--
 <section class="content-section">
    <div class="box">
      <div class="box-header with-border"> 
        <div class="row">
            <div class="col-sm-4">
              <?php //echo $data['StaffUser']['email']; ?>
            </div>
            <div class="col-sm-8 text-right">
              <?php //echo ucfirst($data['StaffUser']['first_name']).' '.$data['StaffUser']['last_name']; ?>     
            </div>
          </div>
        </div>
    </div>
  </section> -->

<?php 
$pdf_data=$this->Custom->getInvociePdfSetting(); 
$pdf_title  =   $pdf_data['PdfSetting']['title'];
$pdf_logo   =   $pdf_data['PdfSetting']['logo'];
$tandc      =   $pdf_data['PdfSetting']['footer_text'];
if(isset($data['Company']) && !empty($data['Company']['name']) ){
 $company =  $data['Company']['name'];
}elseif(isset($data['StaffUser']['Company']) && !empty($data['StaffUser']['Company'][0]['name'])){
 $company =  $data['StaffUser']['Company'][0]['name'];
}else{
 $company =  '';
}
if($data['Transaction']['transaction_type']=='plannewsroom'){ ?>
  <style type="text/css">
  .item-list li{  list-style: none; clear: both; }
  </style>
  <div  class="row">
  <div class="col-sm-offset-2 col-sm-8">
        <div class="card ls-card">
            <div class="card-header with-border">
                <div class="row">
                    <div class="col-sm-5">
                        <h4>Transaction Summary</h4>
                    </div>
                    <div class="col-sm-7 text-right">
                        <?php echo $this->Html->link(__('Download invoice'), array('controller' => $controller, 'action' => 'downloadinvoice', $data['Transaction']['id'], rand(0, 1000)), array('class' => 'btn btn-xs btn-success'));
                        $actions = '';
                        if ($data['Transaction']['txn_type'] == 'subscr_payment') {
                            $actions = " " . $this->Custom->cancelSubscriptionBtn($data['Transaction']['subscr_id'], $controller, 'transaction_view', '1');
                        }
                        echo $actions;
                        ?>

                    </div>
                </div>
            </div>
                <?php
                //echo "<pre>";print_r($data);die;
                // transactiuon id : echo $transdata['id']; die;

                $promo_code = '';
                $transdata = $data['Transaction'];
                $total_amount = (isset($transdata['total']) && $transdata['total'] > 0) ? $transdata['total'] : "0.00";
                $subtotal = (isset($transdata['subtotal']) && $transdata['subtotal'] > 0) ? $transdata['subtotal'] : "0.00";
                $discount = (isset($transdata['discount']) && $transdata['discount'] > 0) ? $transdata['discount'] : "0.00";
                $tax = (isset($transdata['tax']) && $transdata['tax'] > 0) ? $transdata['tax'] : "0.00";
                ?>


                <style>
                    body,
                    html {
                        background: #ffffff;
                        color: #555555;
                        font-family: sans-serif;
                        line-height: 20px;
                        font-size: 14px;
                        margin: 0;
                        card-sizing: border-card;
                    }

                    * {
                        card-sizing: border-card;
                    }

                    table {
                        margin: 0;
                        padding: 0;
                    }
                </style>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                    <tr>
                        <td width='300'><img style='width: 160px;margin: 30px;' src='<?php echo SITEURL . 'files/pdf_settings/' . $pdf_logo; ?>' alt='logo'></td>
                        <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>
                    </tr>
                    <tr>
                        <td valign='top' rowspan='3' colspan='2' style='padding-left: 20px; width: 300px; text-align: left;'><?php echo Configure::read('Site.address'); ?></td>
                        <td width="150" align="right" style="padding-right: 20px; width: 300px; text-align: right;"><strong>Invoice #: </strong><?php echo $data['Transaction']['invoice_no']; ?></td>
                    </tr>
                    <tr>
                        <td width="150" align="right" style="padding-right: 20px;"><strong>Invoice Date: </strong><?php echo date($dateformate, strtotime($data['Transaction']['paymant_date'])); ?></td>
                    </tr>
                    <tr>
                        <td width="150" align="right" style="padding-right: 20px;"><strong>Payment Date: </strong><?php echo date($dateformate, strtotime($data['Transaction']['paymant_date'])); ?></td>
                    </tr>

                    <?php /* if($data['Transaction']['tx_id']!= NULL){?>
                <tr>
                            <td width='120' align='right' style='padding-right: 8px;'>Payment Type:</td>
                            <td width='150' align='left' style='padding-right: 20px;'><?php echo __("Recurring");?></td>
                        </tr>
                <tr>
                            <td width='120' align='right' style='padding-right: 8px;'>Recurring ID:</td>
                            <td width='150' align='left' style='padding-right: 20px;'><?php echo $data['Transaction']['subscr_id'];?></td>
                        </tr>         
                <?php } */ ?>
                    <tr>
                        <td valign='top' align='left' style='padding-left: 20px;'>Phone: <?php echo strip_tags(Configure::read('Site.phone')); ?> <br><?php echo SITEURL; ?></td>
                        <td align='right' colspan='2'>
                            <table align='center' style='margin-top: 30px; margin-right: 20px; width: 40%; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>
                                <tr>
                                    <td align='center' style='text-align: center; padding: 5px;'>
                                        <span style='width:100%; text-align: center; display: block;'>Amount Due:</span>
                                        <strong style='width:100%; text-align: center; margin-top: 10px; font-size: 24px; display: inline-block;'><?php echo $currencySymbol . $total_amount; ?></strong>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td align='left' style='padding-left: 20px; padding-bottom: 10px;'>
                            <h4>Bill To:</h4>
                            <?php echo (!empty($company)) ? $company . "<br>" : '' . $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'] . "<br>" . $data['StaffUser']['email'] . "<br>"; ?>
                        </td>
                        <td colspan='2'></td>
                    </tr>
                </table>
                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; margin: auto; border-right: 1px solid #e6e6e6; padding: 20px 0; background: #ffffff;border-collapse:collapse'>
                    <thead>
                        <tr>
                            <th width='80' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-right: 0;padding: 8px;'>Date</th>
                            <th style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Description</th>
                            <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Quantity</th>
                            <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Price</th>
                            <th align='center' width='100' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 8px;'>Amount</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $rows = array();
                        $i = 0;
                        if (!empty($data['TransactionPlan'])) {
                            foreach ($data['TransactionPlan'] as $index => $plan) {

                                $plan_details = $this->Custom->fetchPlanData($plan['plan_id']);
                                $plan_url = $this->Custom->getPlanCategorySlug($plan_details['PlanCategory']['parent_id']);
                                $name  =  $plan_details['PlanCategory']['name'];
                                $type  =  $plan_details['Plan']['plan_type'];
                                $notesFindReplace = array('##PLAN##' => $name, '##PAGEURL##' => "<br/>" . SITEURL . 'plans/');
                                $notes = strtr($pdf_data['PdfSetting']['email_distribution_description'], $notesFindReplace);
                        ?>
                                <tr>
                                    <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'><?php echo date('F d, Y', strtotime($data['Transaction']['paymant_date'])); ?></td>
                                    <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'><?php echo $name; ?></td>
                                    <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                                    <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'><?php echo $currencySymbol . $plan['plan_amount']; ?></td>
                                    <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'><?php echo $currencySymbol . $plan['plan_amount']; ?></td>
                                </tr>
                        <?php  }
                        }
                        if ($data['Transaction']['newsroom_amount'] > 0) {
                            echo "<tr>
                                <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('M d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Newsroom</td>
                                <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                                <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['Transaction']['newsroom_amount'] . "</td>
                                <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['Transaction']['newsroom_amount'] . "</td>
                            </tr>";
                        }
                        ?>

                        <tr>
                            <td align='center' colspan='2'></td>
                            <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Subtotal</td>
                            <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'><?php echo $currencySymbol . $subtotal; ?></td>
                        </tr>
                        <?php if ($discount) { ?>
                            <tr>
                                <td align='center' colspan='2'></td>
                                <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Discount</td>
                                <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'><?php echo $currencySymbol . $discount; ?></td>
                            </tr>
                        <?php } ?>
                        <?php /* if($tax){ ?>
                <tr>
                    <td align='center' colspan='2'></td>
                        <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Tax</td>
                        <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'><?php echo $currencySymbol.$tax;?></td>
                </tr>
            <?php } */ ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td align='center' colspan='2'></td>
                            <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>
                            <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'><?php echo $currencySymbol . $total_amount; ?></td>
                        </tr>
                    </tfoot>
                </table>
                <table class='email-wrapper' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                    <tbody>
                        <tr>
                            <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Notes: </strong><?php if (isset($notes)) {
                                                                                                                            echo $notes;
                                                                                                                        } ?></td>
                            <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Terms and Conditions: </strong><?php echo $tandc; ?></td>
                        </tr>
                    </tbody>
                </table>



            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card ls-card">
                        <!-- /.card-header -->
                        <div class="card-body">


                            <?php if ($data['Transaction']['subscr_id'] != NULL) {
                                $all_data = $this->Custom->all_transaction($data['Transaction']['subscr_id'], $data['Transaction']['id']);
                                if (!empty($all_data)) {
                            ?>
                                    <h2>Recurring Transaction Summary</h2>
                                    <table class="table table-bordered">
                                        <thead>
                                            <?php
                                            $tableHeaders = $this->Html->tableHeaders(array(
                                                __('S.No.'),
                                                __("Transaction id"),
                                                __("Transaction type"),
                                                __("Amount"),
                                                __("Date"),
                                            ));
                                            echo $tableHeaders;
                                            ?>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $rows = array();
                                            $i = 0;
                                            foreach ($all_data as $d) {
                                                $i++;
                                                $tx_id  = (!empty($d['Transaction']['tx_id'])) ? $d['Transaction']['tx_id'] : "-";
                                                $total  = $d['Transaction']['currency'] . '' . (!empty($d['Transaction']['total'])) ? $d['Transaction']['total'] : "0.00";

                                                $date  = date('F d, Y', strtotime($d['Transaction']['paymant_date']));
                                                $txnType = $this->Custom->getTransactionType($d['Transaction']['txn_type']);
                                                $rows[] = array(
                                                    __($i),
                                                    __($tx_id),
                                                    __($txnType),
                                                    __($currencySymbol . $total),
                                                    __($date),
                                                );
                                            }
                                            echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                                            if (empty($rows)) {
                                                echo '<tr><td align="center" colspan="5">
                                        <div class="alert alert-dismissable label-default fade in">
                                            <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                            No record found.
                                        </div> 
                                    </td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                            <?php }
                            } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>          
    </div>
  </div>
  
<?php }else if(isset($transdata['TransactionPressRelease'])&&!empty($transdata['TransactionPressRelease'])){ 
      $promo_code=''; 
      $transdata=$transdata['TransactionPressRelease'];
      $total_amount=(isset($transdata['total'])&&$transdata['total']>0)?$transdata['total']:"0.00"; 
      $subtotal=(isset($transdata['subtotal'])&&$transdata['subtotal']>0)?$transdata['subtotal']:"0.00";
      $discount=(isset($transdata['discount'])&&$transdata['discount']>0)?$transdata['discount']:"0.00";
      $tax=(isset($transdata['tax'])&&$transdata['tax']>0)?$transdata['tax']:"0.00"; ?> 
      <div class="row">
      <div class="col-sm-1"> </div> 
        <div class="col-sm-offset-1 col-sm-10"> 
          <div class="card-default card ls-card">
              <div class="card-header with-border">
                  <div class="row">
                      <div class="col-sm-5">
                          <h4>Transaction Summary</h4>
                      </div>
                      <div class="col-sm-7 text-right">
                          <?php echo $this->Html->link(__('Download invoice'), array('controller' => $controller, 'action' => 'downloadinvoice', $data['Transaction']['id'], rand(0, 1000)), array('class' => 'btn btn-xs btn-success'));
                          $actions = '';
                          ?>

                      </div>
                  </div>
              </div> 
              <div class="card-body">
                  <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>
                      <tr>
                          <td width='300'><img style='width: 160px;margin: 30px;' src='<?php echo SITEURL . 'files/pdf_settings/' . $pdf_logo; ?>' alt='logo'></td>
                          <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>
                      </tr>
                      <tr>
                          <td valign='top' rowspan='3' colspan='2' style='padding-left: 20px; width: 300px; text-align: left;'><?php echo Configure::read('Site.address'); ?></td>
                          <td width="150" align="right" style="padding-right: 20px; width: 300px; text-align: right;"><strong>Invoice #: </strong><?php echo $data['Transaction']['tx_id']; ?></td>
                      </tr>
                      <tr>
                          <td width="150" align="right" style="padding-right: 20px;"><strong>Invoice Date: </strong><?php echo date($dateformate, strtotime($data['Transaction']['paymant_date'])); ?></td>
                      </tr>
                      <tr>
                          <td width="150" align="right" style="padding-right: 20px;"><strong>Payment Date: </strong><?php echo date($dateformate, strtotime($data['Transaction']['paymant_date'])); ?></td>
                      </tr>

                      <tr>
                          <td valign='top' align='left' style='padding-left: 20px;'>Phone: <?php echo strip_tags(Configure::read('Site.phone')); ?> <br><?php echo SITEURL; ?></td>
                          <td align='right' colspan='2'>
                              <table align='center' style='margin-top: 30px; margin-right: 20px; width: 40%; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>
                                  <tr>
                                      <td align='center' style='text-align: center; padding: 5px;'>
                                          <span style='width:100%; text-align: center; display: block;'>Amount Due:</span>
                                          <strong style='width:100%; text-align: center; margin-top: 10px; font-size: 24px; display: inline-block;'><?php echo $currencySymbol . $total_amount; ?></strong>
                                      </td>
                                  </tr>
                              </table>

                          </td>
                      </tr>

                      <tr>
                          <td align='left' style='padding-left: 20px; padding-bottom: 10px;'>
                              <h4>Bill To:</h4>
                              <?php echo $company . "<br>" . $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'] . "<br>" . $data['StaffUser']['email']; ?>
                          </td>
                          <td colspan='2'></td>
                      </tr>
                  </table>
                  <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; margin: auto; border-right: 1px solid #e6e6e6; padding: 20px 0; background: #ffffff;border-collapse:collapse'>
                      <thead>
                          <tr>
                              <th width='100' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-right: 0;padding: 8px;'>Date</th>
                              <th style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Description</th>
                              <th align='center' width='200' style='font-weight: 700;background: #f9f9f9;text-align:center;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Quantity</th>
                              <th align='center' width='60' style='font-weight: 700;background: #f9f9f9;text-align:center;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Price</th>
                              <th align='center' width='100' style='font-weight: 700;background: #f9f9f9;text-align:right;border: 1px solid #dddddd;border-left: 0;padding: 8px;'>Amount</th>
                          </tr>
                      </thead>
                      <tbody>

                          <?php
                          $plan_details = $this->Custom->fetchPlanData(isset($plan['plan_id']));
                          $plan_url = $this->Custom->getPlanCategorySlug(isset($plan_details['PlanCategory']['parent_id']));
                          $name  =  isset($plan_details['PlanCategory']['name']);
                          $type  =  isset($plan_details['Plan']['plan_type']);
                          ?>
                          <tr>
                              <?php
                              if ($transdata['word_amount'] > 0) {
                                  $lbl = ($transdata['extra_words'] > 1) ? "words" : "word";
                                  echo "<tr><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date($dateformate, strtotime($data['Transaction']['paymant_date'])) . "</td><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>Extra Word charges</td><td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_words'] . " Extra $lbl</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['word_amount'] . "</td></tr>";
                              }
                              if ($transdata['extra_category'] > 0) {
                                  $lbl = ($transdata['extra_category'] > 1) ? "categories" : "category";
                                  echo "<tr><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date($dateformate, strtotime($data['Transaction']['paymant_date'])) . "</td><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>Extra Category charges</td><td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_category'] . "Extra $lbl</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['category_amount'] . "</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['category_amount'] . "</td></tr>";
                              }

                              if ($transdata['extra_msa'] > 0)
                                  echo "<tr><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date($dateformate, strtotime($data['Transaction']['paymant_date'])) . "</td><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>Extra MSA charges</td><td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_msa'] . " Extra msa</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['msa_amount'] . "</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['msa_amount'] . "</td></tr>";

                              if ($transdata['extra_state'] > 0) {
                                  $lbl = ($transdata['extra_state'] > 1) ? "states" : "state";
                                  echo "<tr><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date($dateformate, strtotime($data['Transaction']['paymant_date'])) . "</td><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>Extra State charges</td><td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $transdata['extra_state'] . " Extra $lbl</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['state_amount'] . "</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['state_amount'] . "</td></tr>";
                              }

                              if (!empty($transdata['translate_charges']))
                                  echo "<tr><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date($dateformate, strtotime($data['Transaction']['paymant_date'])) . "</td><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>Content translate charges</td><td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $transdata['translation_amount'] . "</td></tr>";

                              $features = unserialize($transdata['distribution_ids']);
                              if (!empty($features)) {
                                  foreach ($features as $index => $feature) {
                                      echo "<tr><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date($dateformate, strtotime($data['Transaction']['paymant_date'])) . "</td><td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . $feature['name'] . "</td><td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td><td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $feature['price'] . "</td></tr>";
                                  }
                              }
                              ?>
                          </tr>

                          <tr>
                              <td align='center' colspan='2'></td>
                              <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Subtotal</td>
                              <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'><?php echo $currencySymbol . $subtotal; ?></td>
                          </tr>

                          <?php /* if($tax){ ?>
                              <tr>
                              <td align='center' colspan='2'></td>
                              <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Tax</td>
                              <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'><?php echo $currencySymbol.$tax;?></td>
                              </tr>
                          <?php } */ ?>
                      </tbody>
                      <tfoot>
                          <tr>
                              <td align='center' colspan='2'></td>
                              <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>
                              <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'><?php echo $currencySymbol . $total_amount; ?></td>
                          </tr>
                      </tfoot>
                  </table>
              </div>
          </div>
        </div>
      </div> 
<?php }else{?>
     <div class="alert alert-dismissable label-default fade in">
          <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>  No record found.  </div> 
<?php } ?>
 

