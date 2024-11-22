<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Helper', 'View/Helper');
class CustomHelper extends AppHelper
{
    public $helpers = array('Html', 'Form');
    public function get_sub_category($cat_id)
    {

        $obj = ClassRegistry::init('Category');

        $obj->recursive = -1;

        $conditions = array();

        $conditions[] = array('Category.parent_id' => $cat_id, 'status' => 1);

        return $obj->find('list', array('conditions' => $conditions));
    }



    public function bodyclass($actual_link = '')
    {

        $page = '';

        $actual_link = rtrim($this->request->url, "/");

        if (!empty($actual_link)) {

            $url = explode('/', strtok($actual_link, "?"));

            $page = (!empty(end($url))) ? end($url) : end($url);
        }

        return (!empty($page)) ? $page : 'home';
    }



    public function getPrInvoiceHtml($data, $tx_id = "", $staff_data = [])
    {

        $currencySymbol = Configure::read('Site.currency');

        $site_name = strip_tags(Configure::read('Site.name'));

        $recurringHtml = "";

        $name = $plan_url = $rows = '';

        if (!empty($data)) {

            $total_amount = (isset($data['total']) && $data['total'] > 0) ? $data['total'] : "0.00";

            $subtotal = (isset($data['subtotal']) && $data['subtotal'] > 0) ? $data['subtotal'] : "0.00";

            $discount = (isset($data['discount']) && $data['discount'] > 0) ? $data['discount'] : "0.00";

            $tax = (isset($data['tax']) && $data['tax'] > 0) ? $data['tax'] : "0.00";

            if ($data['extra_words'] > 0)

                $rows .= "<tr>

                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['created'])) . "</td>

                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Word charges</td>

                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $data['extra_words'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['word_amount'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['word_amount'] . "</td>

                    </tr>";



            if ($data['extra_category'] > 0)

                $rows .= "<tr>

                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['created'])) . "</td>

                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra Category charges</td>

                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $data['extra_category'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['category_amount'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['category_amount'] . "</td>

                    </tr>";



            if ($data['extra_msa'] > 0)

                $rows .= "<tr>

                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['created'])) . "</td>

                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra MSA charges</td>

                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $data['extra_msa'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['msa_amount'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['msa_amount'] . "</td>

                    </tr>";



            if ($data['extra_state'] > 0)

                $rows .= "<tr>

                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['created'])) . "</td>

                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Extra State charges</td>

                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $data['extra_state'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['state_amount'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['state_amount'] . "</td>

                    </tr>";



            if (!empty($data['translate_charges']))

                $rows .= "<tr>

                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['created'])) . "</td>

                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Content translate charges</td>

                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['translation_amount'] . "</td>

                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['translation_amount'] . "</td>

                    </tr>";

            if (!empty($data['distribution_ids'])) {
                $distribution_data = array_values(unserialize($data['distribution_ids']));

                $i = 0;

                foreach ($distribution_data as $index => $plan) {

                    $i++;

                    $rows .= "<tr>

                                <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['created'])) . "</td>

                                <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $plan['name'] . "</td>

                                <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>

                                <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['price'] . "</td>

                                <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['price'] . "</td>

                                </tr>";
                }
            }
        }


        // $plan_url = SITEURL.'plans/';

        $descip = "The benefits for ##PLAN## are explained here: ##PAGEURL## ";
        $notesFindReplace = array('##PLAN##' => $name, '##PAGEURL##' => "<br/><a href='" . SITEURL . 'plans/' . $plan_url . "'>" . SITEURL . 'plans/' . $plan_url . "</a>");

        $notes = strtr($descip, $notesFindReplace);



        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>

               <style>body, html { background: #ffffff; color: #555555; font-family: sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }

               table {margin:0; padding: 0;}

               </style>            

               </head>

                <body>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>

                  <tr>

                    <td><img style='width: 160px;margin: 30px;' src='" . SITEURL . "website/img/emailwire-logo-inner.png' alt='logo'></td>

                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>

                  </tr>

                  <tr>

                    <td valign='top' rowspan='3' colspan='2' style='padding-left: 20px; width: 300px; text-align: left;'>" . Configure::read('Site.address') . "</td>

                    <td width='150' align='right' style='padding-right: 20px; width: 300px; text-align: right;'><strong>Invoice #: </strong>" . $data['txn_id'] . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Invoice Date: </strong>" . date('F d, Y', strtotime($data['created'])) . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px;'><strong>Payment Date: </strong>" . date('F d, Y', strtotime($data['created'])) . "</td>

                  </tr>

                  <tr>

                  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>

                            " . strip_tags(Configure::read('Site.email')) . "

                            " . SITEURL . "</td>

                    <td align='right' colspan='2'>

                        <table align='center' style='margin-top: 30px; margin-right: 20px; width: 60%; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>

                            <tr>

                                <td align='center' style='text-align: center; padding: 5px;'>

                                    <span style='width:100%; text-align: center; display: block;'>Amount Due:</span>

                                    <strong style='width:100%; text-align: center; margin-top: 10px; font-size: 24px; display: inline-block;'>$total_amount</strong>

                                </td>

                            </tr>

                        </table>

                    </td>

                  </tr>

                   

                    <tr>

                        <td align='left' style='padding-left: 20px;'><h4>Bill To:</h4>

                        " . $data['Company'][0]['name'] . "<br>

                        " . $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'] . "<br>

                        " . $data['StaffUser']['email'] . "</td>

                        <td colspan='2'></td>

                    </tr>

                </table>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; margin: auto; border-right: 1px solid #e6e6e6; padding: 20px 0; background: #ffffff;border-collapse:collapse'>

                    <thead>

                        <tr>

                          <th width='110' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-right: 0;padding: 8px;'>Date</th>

                          <th style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Description</th>  

                          <th align='center' width='60' style='font-weight: 700;text-align: center;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Quantity</th>  

                          <th align='center' width='60' style='font-weight: 700;text-align: center;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Price</th> 

                          <th align='center' width='100' style='font-weight: 700;text-align: center;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 8px;'>Amount</th>

                        </tr>

                    </thead>

                    <tbody>

                        $rows

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Subtotal</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$subtotal</td>

                        </tr>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Discount</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$discount</td>

                        </tr>

                        <!--<tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Discount</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$subtotal</td>

                        </tr>-->

                    </tbody>

                    <tfoot>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$total_amount</td>

                        </tr>

                    </tfoot>

                </table>

                    <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>

                        <tbody>

                        <tr>

                            <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Notes: </strong>$notes</td>   

                            <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Terms and Conditions: </strong>By purchasing this plan, you agree to terms of services outlined here: <br/> <a href='" . SITEURL . "plans/online-distribution' target='_blank'>" . SITEURL . "plans/online-distribution</a></td>

                        </tr>

                        </tbody>

                    </table>

                </body>

                </html>";

        return $html;
    }



    public function getPlanInvoiceHtml($data)
    {
        
        $currencySymbol = Configure::read('Site.currency');
        $site_name = strip_tags(Configure::read('Site.name'));
        $recurringHtml = "";
        $name = $plan_url = $rows = '';
        $metadata = json_decode($data["Transaction"]["metadata"],true);
        if(!empty($metadata) && isset($metadata["features"])){
           // echo 1;
            $i = 0;
            foreach ($metadata["features"] as $feature) {
              //  echo 2;
                $i++;
               $name = $feature["name"];
               $price = $feature["price"];
                $rows .= "<tr>
                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>$name</td>
                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $price . "</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $price . "</td>
                    </tr>";
            }
        }
        
        if (!empty($data['TransactionPlan'])) {
            $i = 0;
            foreach ($data['TransactionPlan'] as $index => $plan) {
                $i++;
                $plan_details = $this->fetchPlanData($plan['plan_id']);
                $plan_url = $this->getPlanCategorySlug($plan_details['PlanCategory']['parent_id']);
                $name  =  $plan_details['PlanCategory']['name'];
                //$type  =  $plan_details['Plan']['plan_type'];
                // $rows .="<tr style='padding-top:3px'><td>$i</td><td>$name </td> <td>$type</td> <td>".$currencySymbol.$plan['plan_amount']."</td></tr>";
                $rows .= "<tr>
                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>$name</td>
                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $plan['plan_amount'] . "</td>
                    </tr>";
            }
        }
        if ($data['Transaction']['newsroom_amount'] > 0) {
            $rows .= "<tr>
                      <td align='left' style='padding: 7px;border-left: 1px solid #dddddd;border-bottom: 1px solid #dddddd'>" . date('F d, Y', strtotime($data['Transaction']['paymant_date'])) . "</td>
                      <td align='left' style='padding: 7px;border-bottom: 1px solid #dddddd'>Newsroom</td>
                      <td align='center' style='padding: 7px;border-bottom: 1px solid #dddddd'>1</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['Transaction']['newsroom_amount'] . "</td>
                      <td align='right' style='padding: 7px;border-bottom: 1px solid #dddddd'>" . $currencySymbol . $data['Transaction']['newsroom_amount'] . "</td>
                    </tr>";
        }
        if ($data['Transaction']['subscr_id'] && $data['Transaction']['subscr_id'] != NULL) {
            $recurringData = $this->getRecurringTransaction($data['Transaction']['subscr_id'], $data['Transaction']['id']);
            if (!empty($recurringData)) {
                $recRows = '';
                $recurringHtml .= "
            <div style='text-align:left'><h3>Recurring Transaction Summary</h3></div>

            <table id='detail_table' style='margin:5px 0 5px 0;border-collapse: collapse;width: 100%;' border='1px solid #cac8c8' cellspacing='5' cellpadding='5' id='dataTables-example'>

            <thead>

                <tr>

                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>S.No.</th>

                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Transaction id</th>

                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Transaction Type</th>

                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Amount</th>

                    <th style='text-align:left;padding-right: 20px;padding-bottom: 2px;'>Date</th>

                </tr>

            </thead>";

                foreach ($recurringData as $loop => $recurringPayment) {

                    $total = (!empty($recurringPayment['Transaction']['total'])) ? $recurringPayment['Transaction']['total'] : "0.00";

                    $tx_id = (!empty($recurringPayment['Transaction']['tx_id'])) ? $recurringPayment['Transaction']['tx_id'] : "-";

                    $txnType = $this->getTransactionType($recurringPayment['Transaction']['txn_type']);

                    $date  = date('F d, Y', strtotime($recurringPayment['Transaction']['paymant_date']));

                    $recRows .= "<tr style='padding-top:3px'><td>" . ($loop + 1) . "</td><td>$tx_id</td> <td>$txnType</td> <td>" . $currencySymbol . $total . "</td><td>$date</td></tr>";
                }

                $recurringHtml .= "<tbody>$recRows</tbody></table>";
            }
        }



        $promo_code = '';

        $total_amount = (isset($data['Transaction']['total']) && $data['Transaction']['total'] > 0) ? $currencySymbol . $data['Transaction']['total'] : $currencySymbol . "0.00";

        $subtotal = (isset($data['Transaction']['subtotal']) && $data['Transaction']['subtotal'] > 0) ? $currencySymbol . $data['Transaction']['subtotal'] : $currencySymbol . "0.00";

        $discount = (isset($data['Transaction']['discount']) && $data['Transaction']['discount'] > 0) ? $currencySymbol . $data['Transaction']['discount'] : $currencySymbol . "0.00";

        $tax = (isset($data['Transaction']['tax']) && $data['Transaction']['tax'] > 0) ? $currencySymbol . $data['Transaction']['tax'] : $currencySymbol . "0.00";



        $pdf_obj        =   ClassRegistry::init('PdfSetting');

        $pdf_data       =   $pdf_obj->find('first', array('conditions' => array('PdfSetting.id' => '2')));



        $pdf_title      =   $pdf_data['PdfSetting']['title'];

        $pdf_logo       =   $pdf_data['PdfSetting']['logo'];

        $tandc          =   $pdf_data['PdfSetting']['footer_text'];



        $notesFindReplace = array('##PLAN##' => $name, '##PAGEURL##' => "<br/><a href='" . SITEURL . 'plans/' . $plan_url . "'>" . SITEURL . 'plans/' . $plan_url . "</a>");

        $notes = strtr($pdf_data['PdfSetting']['email_distribution_description'], $notesFindReplace);



        if (isset($data['Company']) && !empty($data['Company']['name'])) {

            $company =  $data['Company']['name'];
        } elseif (isset($data['StaffUser']['Company']) && !empty($data['StaffUser']['Company'][0]['name'])) {

            $company =  $data['StaffUser']['Company'][0]['name'];
        } else {

            $company =  '';
        }

        
        $company_logo_path = ROOT . '/app/webroot/' . "files/pdf_settings/" . $pdf_logo;
        $companyLogo=$this->convertImageIntoBase64($company_logo_path); 
     

        $html = "<!doctype html><html><head><meta charset='utf-8'><title>$site_name invoice</title>

               <style>body, html { background: #ffffff; color: #555555; font-family: sans-serif; line-height: 20px; font-size: 14px; margin: 0; box-sizing: border-box; } *{ box-sizing: border-box; }

               table {margin:0; padding: 0;}

               </style>            

               </head>

                <body>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>

                  <tr>

                    <td><img style='width: 160px;margin: 30px;' src='" .$companyLogo."' alt='logo'></td>

                    <td width='150' colspan='2' style='color: #9da3a6;text-align: right;padding-right: 20px;font-size: 26px;font-weight: 900;'>INVOICE</td>

                  </tr>

                  <tr>

                    <td valign='top' rowspan='3' colspan='2' style='padding-left: 20px;'>" . Configure::read('Site.address') . "</td>

                    <td width='150' align='right' style='padding-right: 20px; width: 300px; text-align: right;'><strong>Invoice #: </strong>" . $data['Transaction']['invoice_no'] . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px; text-align: right;'><strong>Invoice Date: </strong>" . date('F d, Y', strtotime($data['Transaction']['created'])) . "</td>

                  </tr>

                  <tr>

                    <td width='150' align='right' style='padding-right: 20px; text-align: right;'><strong>Payment Date: </strong>" . date("F d, Y", strtotime($data['Transaction']['paymant_date'])) . "</td>

                  </tr>

                  <tr>

                  <td valign='top' align='left' style='padding-left: 20px;'>Phone: " . strip_tags(Configure::read('Site.phone')) . "<br>

                            " . strip_tags(Configure::read('Site.email')) . "

                            " . SITEURL . "</td>

                    <td align='right' colspan='2'>

                        

                        <table align='center' style='margin-top: 30px; margin-right: 20px; width: 60%; border: 1px solid #e6e6e6; border-radius: 4px; padding: 10px;'>

                            <tr>

                                <td align='center' style='text-align: center; padding: 5px;'>

                                    <span style='width:100%; text-align: center; display: block;'>Amount Due:</span>

                                    <strong style='width:100%; text-align: center; margin-top: 10px; font-size: 24px; display: inline-block;'>$total_amount</strong>

                                </td>

                            </tr>

                        </table>

                        

                    </td>

                  </tr>

                   

                    <tr>

                        <td align='left' style='padding-left: 20px;'><h4>Bill To:</h4>

                        $company<br>

                        " . $data['StaffUser']['first_name'] . " " . $data['StaffUser']['last_name'] . "<br>

                        " . $data['StaffUser']['email'] . "</td>

                        <td colspan='2'></td>

                    </tr>

                </table>

                <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 98%; margin: 30px auto; border-right: 1px solid #e6e6e6; padding: 20px 0; background: #ffffff;border-collapse:collapse'>

                    <thead>

                        <tr>

                          <th width='110' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-right: 0;padding: 8px;'>Date</th>

                          <th style='font-weight: 700;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Description</th>  

                          <th align='center' width='60' style='font-weight: 700;text-align: center;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Quantity</th>  

                          <th align='center' width='60' style='font-weight: 700;text-align: center;background: #f9f9f9;border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;padding: 8px;'>Price</th> 

                          <th align='center' width='100' style='font-weight: 700;text-align: center;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 8px;'>Amount</th>

                        </tr>

                    </thead>

                    <tbody>

                        $rows

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Subtotal</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$subtotal</td>

                        </tr>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Discount</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$discount</td>

                        </tr>

                        <!--<tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='border-left: 1px solid #dddddd;padding: 7px;border-right: 1px solid #dddddd;'>Discount</td>

                           <td align='right' style='padding: 7px;border-right: 1px solid #dddddd;'>$subtotal</td>

                        </tr>-->

                    </tbody>

                    <tfoot>

                        <tr>

                           <td align='center' colspan='2'></td>

                           <td align='right' colspan='2' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;padding: 7px;'>Total</td>

                           <td align='right' style='font-weight: 700;background: #f9f9f9;border: 1px solid #dddddd;border-left: 0;padding: 7px;'>$total_amount</td>

                        </tr>

                    </tfoot>

                </table>

                    <table class='email-wrapper' width='' cellpadding='0' cellspacing='0' style='max-width: 100%; width: 100%; margin: auto; border-top: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; background: #ffffff;border-collapse:collapse'>

                        <tbody>

                        <tr>

                            <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Notes: </strong>$notes</td>   

                            <td style='vertical-align: top; padding: 20px; line-height: 25px;'><strong>Terms and Conditions: </strong>$tandc</td>

                        </tr>

                        </tbody>

                    </table>

                </body>

                </html>";
        return $html;
    }



    public function getRecurringTransaction($subscriber_id = '', $excludeId)
    {

        $obj = ClassRegistry::init('Transaction');

        $result = $obj->find("all", array('fields' => ['total', 'tx_id', 'txn_type', 'paymant_date'], 'conditions' => array('subscr_id' => $subscriber_id, 'Transaction.id !=' => $excludeId), 'order' => 'Transaction.id DESC'));

        return (!empty($result)) ? $result : [];
    }

    public function getStatus($status)
    {

        switch ($status) {

            case '1':

                $status = '<span class="badge bg-success">Approved</span>';

                break;

            case '2':

                $status = '<span class="label light-green">Suspended</span>';

                break;

            case '3':

                $status = '<span class="badge bg-danger">Disapproved</span>';

                break;

            default:

                $status = '<span class="badge bg-warning">Pending</span>';

                break;
        }

        return $status;
    }





    public function pricePlanHeader($word_limit = "0", $is_transation = "0")
    {

        $discountedCol = ($is_transation == 1) ? 'Translation per page' : 'Discounted Bulk';



        $html = '<div class="ew-pricing-plan-th-head full">

            <div class="ew-pricing-plan-head"># of Press Release</div>

            <div class="ew-pricing-plan-head">Each Addt’l 100 words</div>

            <div class="ew-pricing-plan-head">' . $discountedCol . '</div>

            <div class="ew-pricing-plan-head none-row"></div>

        </div>';

        if ($word_limit > 0) {

            $html = '<div class="ew-pricing-plan-th-head full">

            <div class="ew-pricing-plan-head">' . $word_limit . ' word Release</div>

            <div class="ew-pricing-plan-head">Each Addt’l 100 words</div>

            <div class="ew-pricing-plan-head">' . $discountedCol . '</div>

            <div class="ew-pricing-plan-head none-row"></div>

        </div>';
        }

        return $html;
    }





    public function cyclePeriodAbrivation($cycle_period)
    {

        switch ($cycle_period) {

            case 'monthly':

                $word = "Month";

                break;

            case 'halfyearly':

                $word = "6 Month";

                break;

            case 'quarterly':

                $word = "3 Month";

                break;

            case 'monthly':

                $word = "Month";

                break;



            default:

                $word = "Yearly";

                break;
        }

        return $word;
    }

    public function pricePlanRows($plan, $word_limit = "0")
    {
            if(!$plan['status']){
                $html = '';
                unset($plan);
                return $html;
            }
        
        $firstColumn = "";

        $plan_id = $plan['id'];

        $thirdColumn = 'n/a';

        if ($plan['translation_amount'] > 0) {

            $thirdColumn = Configure::read('Site.currency') . $plan['translation_amount'];
        }

        $add_word_amount = ($plan['add_word_amount'] > 0) ? $plan['add_word_amount'] : "n/a";

        if ($plan['plan_type'] == 'subscription') {

            $firstColumn = $plan['number_pr'] . '/day';



            if ($plan['bulk_discount_amount'] > 0) {

                $amunt = Configure::read('Site.currency') . $plan['bulk_discount_amount'];
            } else if ($plan['translation_amount'] > 0) {

                $amunt = Configure::read('Site.currency') . $plan['translation_amount'];
            }

            $thirdColumn = $amunt . '/' . $this->cyclePeriodAbrivation($plan['cycle_period']);
        } elseif ($plan['plan_type'] == 'bulk') {

            $firstColumn = $plan['number_pr'];

            if ($plan['bulk_discount_amount'] > 0) {

                $thirdColumn = Configure::read('Site.currency') . $plan['bulk_discount_amount'];
            } else if ($plan['translation_amount'] > 0) {

                $thirdColumn = Configure::read('Site.currency') . $plan['translation_amount'];
            }
        } elseif ($plan['plan_type'] == 'single' && $word_limit > 0) {

            $firstColumn = Configure::read('Site.currency') . $plan['price'];
        }

        if ($plan['plan_type'] == 'single' && $plan['number_pr'] > 1) {

            $plan_name = (!empty($plan['name'])) ? '<div class="ew-psp-sup-text full">' . $plan['name'] . '</div>' : "";

            $html = '<div class="ew-shadwo-dive-table-full-wrap full margin-bottom50 ew-single-wh-head">' . $plan_name . '<div class="ew-pricing-plan-th-text full">

                        <div class="ew-pricing-plan-text 1">' . $firstColumn . '</div>

                        <div class="ew-pricing-plan-text">' . $add_word_amount . '</div>

                        <div class="ew-pricing-plan-text">' . $thirdColumn . '</div>

                <div class="ew-pricing-plan-text ew-pricing-plan-btn btn-row-none"><a class="orange-btn" href="javascript:void(0)" onclick="addtocart(' . $plan_id . ');">Buy Plan</a></div>

            </div>



            </div>';
        } else {


            $plan_name = (!empty($plan['name'])) ? '<div class="ew-psp-sup-text full">' . $plan['name'] . '</div>' : "";

            $html = $plan_name . '<div class="ew-pricing-plan-th-text full">

                        <div class="ew-pricing-plan-text 2">' . $firstColumn . '</div>

                        <div class="ew-pricing-plan-text">' . $add_word_amount . '</div>

                        <div class="ew-pricing-plan-text">' . $thirdColumn . '</div>

                <div class="ew-pricing-plan-text ew-pricing-plan-btn btn-row-none"><a class="orange-btn" href="javascript:void(0)" onclick="addtocart(' . $plan_id . ');">Buy Plan</a></div>

            </div>';
        }

        return $html;
    }







    public function checkprcart($user_id = '', $plan_id = '')
    {

        $Cart = ClassRegistry::init('Cart');

        $checkcart = $Cart->find('first', array('conditions' => array('staff_user_id' => $user_id, 'plan_id' => $plan_id, 'cart_type' => 'pr')));

        return $checkcart;
    }



    public function countprcart($user_id = '', $plan_id = '', $prId = '')
    {

        $Cart = ClassRegistry::init('Cart');

        $count = $Cart->find('count', array('conditions' => array('staff_user_id' => $user_id, 'plan_id' => $plan_id, 'cart_type' => 'pr', 'Cart.press_release_id' => $prId)));



        return $count;
    }



    public function fetchPlanData($plan_id)
    {

        $obj = ClassRegistry::init('Plan');

        $data = $obj->find('first', array('conditions' => array('Plan.id' => $plan_id)));

        return $data;
    }

    public function getPlanCategorySlug($id = '')
    {

        if ($id) {

            $obj = ClassRegistry::init('PlanCategory');

            $result = $obj->find("first", array('fields' => ['PlanCategory.slug'], 'conditions' => array('PlanCategory.id' => $id)));

            return (!empty($result)) ? $result['PlanCategory']['slug'] : "online-distribution";
        }
    }



    public function getNewsroomLogo($logo_path = '', $logo = '', $slug = "")
    {

        $imageUrl = SITEURL . 'files/company/logo/' . $logo_path . '/' . $logo;



        $fileUrl = WWW_ROOT . 'files' . DS . 'company' . DS . 'logo' . DS . $logo_path . DS . $logo;



        if (!file_exists($fileUrl)) {

            $imageUrl = SITEURL . "img/no-logo-provided.png";
        }

        $logo = "<img src='" . $imageUrl . "' width='50px' height='50px' />";

        if (!empty($slug)) {

            $logo = "<a href='" . SITEURL . 'newsroom/' . $slug . "'><img src='" . $imageUrl . "' width='50px' height='50px' /></a>";
        }





        return $logo;
    }



    public function getPrSingleImage($imageArr = '', $class = 'press-image', $is_desc = '')
    {

        $imageUrl = SITEURL . "img/no_image.jpeg";

        $image_alt = '';

        if (!empty($imageArr)) {

            $image_path = $imageArr[0]['image_path'];

            $image_name = $imageArr[0]['image_name'];

            $image_alt = $imageArr[0]['image_text'];

            $describe_image = $imageArr[0]['image_text'];



            $imageUrl = SITEURL . 'files/company/press_image/' . $image_path . '/' . $image_name;

            $fileUrl = WWW_ROOT . 'files' . DS . 'company' . DS . 'press_image' . DS . $image_path . DS . $image_name;

            if (!file_exists($fileUrl)) {

                $imageUrl = SITEURL . "img/no_image.jpeg";
            }
        }

        return $this->Html->image($imageUrl, array('class' => $class, "width" => "100%", "alt" => $image_alt));
    }



    public function getEmbedCode($url = '')
    {

        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';

        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';

        if (preg_match($longUrlRegex, $url, $matches)) {

            $youtube_id = $matches[count($matches) - 1];
        }

        if (preg_match($shortUrlRegex, $url, $matches)) {

            $youtube_id = $matches[count($matches) - 1];
        }

        $videoIfram = "<iframe width='100%' height='300' src='https://www.youtube.com/embed/$youtube_id' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";

        return $videoIfram;
    }



    public function createprslug($slug = '')
    {

        return SITEURL . 'release/' . $slug;
    }



    public function getSubCategoryWithSlug($cat_id)
    {

        $obj = ClassRegistry::init('Category');

        $obj->recursive = -1;

        $conditions = array();

        $conditions[] = array('Category.parent_id' => $cat_id, 'Category.status' => 1);

        return $obj->find('list', array('fields' => array('Category.slug', 'Category.name'), 'conditions' => $conditions, 'order' => "name ASC"));
    }





    public function documentIcon($file)
    {

        if (empty($file)) {

            $document = "<img style='width: 60%;' src='" . SITEURL . 'img/no-document-icon.png' . "'>";
        } else {

            $fileTypeArr = explode("/", $this->mime_content_type($file));

            $file_type = $fileTypeArr['0'];

            if ($file_type == 'application') {

                $file_type = $fileTypeArr['1'];
            }

            switch ($file_type) {

                case 'image':

                    $document = "<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa fa-file-image-o'></i>";

                    break;

                case 'pdf':

                    $document = "<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa fa fa-file-pdf-o'></i>";

                    break;

                case 'msword':

                    $document = "<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa fa-file-word-o'></i>";

                    break;

                case 'docx':

                    $document = "<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa fa-file-word-o'></i>";

                    break;

                default:
            }
        }

        return $document;
    }



    public function docIcon($file)
    {

        if (empty($file)) {

            $document = "<img style='width: 60%;' src='" . SITEURL . 'img/no-document-icon.png' . "'>";
        } else {

            $fileTypeArr = explode("/", $this->mime_content_type($file));

            $file_type = $fileTypeArr['0'];

            if ($file_type == 'application') {

                $file_type = $fileTypeArr['1'];
            }

            switch ($file_type) {

                case 'image':

                    $document = "<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa fa-file-image-o'></i>";

                    break;

                case 'pdf':

                    $document = "<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa fa fa-file-pdf-o'></i>";

                    break;

                case 'msword':

                    $document = "<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa fa-file-word-o'></i>";

                    break;

                case 'docx':

                    $document = "<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa fa-file-word-o'></i>";

                    break;

                default:

                    $document = "<i style='font-size: 40px; color: rgb(255, 77, 77);' class='fa fa-file'></i>";
            }
        }

        return $document;
    }



    public function docType($file)
    {

        if (empty($file)) {

            $document = "<img style='width: 60%;' src='" . SITEURL . 'img/no-document-icon.png' . "'>";
        } else {

            $fileTypeArr = explode("/", $this->mime_content_type($file));

            $document = $fileTypeArr['0'];

            if ($document == 'application') {

                $document = $fileTypeArr['1'];
            }
        }

        return $document;
    }



    function mime_content_type($filename)
    {

        $mime_types = array(

            'txt' => 'text/plain',

            'htm' => 'text/html',

            'html' => 'text/html',

            'php' => 'text/html',

            'css' => 'text/css',

            'js' => 'application/javascript',

            'json' => 'application/json',

            'xml' => 'application/xml',

            'swf' => 'application/x-shockwave-flash',

            'flv' => 'video/x-flv',

            // images

            'png' => 'image/png',

            'jpe' => 'image/jpeg',

            'jpeg' => 'image/jpeg',

            'jpg' => 'image/jpeg',

            'gif' => 'image/gif',

            'bmp' => 'image/bmp',

            'ico' => 'image/vnd.microsoft.icon',

            'tiff' => 'image/tiff',

            'tif' => 'image/tiff',

            'svg' => 'image/svg+xml',

            'svgz' => 'image/svg+xml',

            // archives

            'zip' => 'application/zip',

            'rar' => 'application/x-rar-compressed',

            'exe' => 'application/x-msdownload',

            'msi' => 'application/x-msdownload',

            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video

            'mp3' => 'audio/mpeg',

            'qt' => 'video/quicktime',

            'mov' => 'video/quicktime',

            // adobe

            'pdf' => 'application/pdf',

            'psd' => 'image/vnd.adobe.photoshop',

            'ai' => 'application/postscript',

            'eps' => 'application/postscript',

            'ps' => 'application/postscript',

            // ms office

            'doc' => 'application/msword',

            'docx' => 'application/msword',

            'rtf' => 'application/rtf',

            'xls' => 'application/vnd.ms-excel',

            'ppt' => 'application/vnd.ms-powerpoint',

            // open office

            'odt' => 'application/vnd.oasis.opendocument.text',

            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

        );

        //$ext = strtolower(array_pop(explode('.',$filename)));

        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (array_key_exists($ext, $mime_types)) {

            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {

            $finfo = finfo_open(FILEINFO_MIME);

            $mimetype = finfo_file($finfo, $filename);

            finfo_close($finfo);

            return $mimetype;
        } else {

            return 'application/octet-stream';
        }
    }



    public function emailUserClippingReport($prId = '', $hostname = '', $email = '', $distributionType = '')
    {

        $clippingReportObj = ClassRegistry::init('ClippingReport');

        $subscriberExists = 0;

        $extractHostname = explode(".", $hostname);

        if (is_array($extractHostname)) {

            $elementCount = count($extractHostname);

            $site_name = $extractHostname[$elementCount - 2];
        }

        $conditions = array('ClippingReport.press_release_id' => $prId, 'ClippingReport.email' => $email, 'ClippingReport.distribution_type' => $distributionType);

        $check = $clippingReportObj->find('first', array('conditions' => $conditions, "fields" => array("ClippingReport.id", "ClippingReport.views")));

        $NewsletterLog = ClassRegistry::init('NewsletterLog');

        $subscriberExists = $NewsletterLog->find('first', array(

            /*

                'joins' => array(

                    array(

                        'table' => 'staff_users',

                        'alias' => 'StaffUser',

                        'type' => 'INNER',

                        'conditions' => array( 

                           'NewsletterLog.staff_user_id = StaffUser.id'

                        )

                    ),

                ), */

            'conditions' => ['StaffUser.email' => $email, 'NewsletterLog.press_release_id' => $prId],



        ));





        $stffUserObj       =   ClassRegistry::init('StaffUser');

        $user_detail       =   $stffUserObj->find('first', array('conditions' => array('StaffUser.email' => $email), "fields" => array("StaffUser.id")));

        $staff_user_id     =   $user_detail['StaffUser']['id'];

        // $NewsletterLog->updateAll(

        //     array('is_opened' => "1"),

        //     array('NewsletterLog.staff_user_id' => $staff_user_id,'NewsletterLog.press_release_id'=>$prId)

        // );





        if (!empty($subscriberExists)) {



            $updateStatus['NewsletterLog']['is_opened'] = '1';

            $updateStatus['NewsletterLog']['id'] = $subscriberExists['NewsletterLog']['id'];

            $NewsletterLog->save($updateStatus);



            if (!empty($check['ClippingReport'])) {

                $data['ClippingReport']['views'] = $check['ClippingReport']['views'] + 1;

                $data['ClippingReport']['id'] = $check['ClippingReport']['id'];

                $data['ClippingReport']['is_subscriber'] = 1;
            } else {

                $data['ClippingReport']['distribution_type'] = $distributionType;

                $data['ClippingReport']['press_release_id'] = $prId;

                $data['ClippingReport']['domain'] = null;

                $data['ClippingReport']['site_name'] = $hostname;

                $data['ClippingReport']['email'] = $email;

                $data['ClippingReport']['release_page_url'] = null;

                $data['ClippingReport']['views'] = 1;

                $data['ClippingReport']['is_subscriber'] = 1;
            }

            $clippingReportObj->save($data);
        }
    }





    public function getSocialMediaDetails($id)
    {

        $socialMediaCredential = ClassRegistry::init('SocialMediaCredential');

        $data = $socialMediaCredential->find('first', array('conditions' => array('SocialMediaCredential.id' => $id), "fields" => array("SocialMediaCredential.social_media", "SocialMediaCredential.social_site")));



        return (!empty($data['socialMediaCredential'])) ? $data['socialMediaCredential'] : [];
    }

    public function socialMediaUpdateClippingReport($prId = '', $smId = '', $distributionType = '')
    {

        $socialMediaDetail = $this->getSocialMediaDetails($smId);

        $clippingReportObj = ClassRegistry::init('ClippingReport');

        $site_name = (!empty($socialMediaDetail)) ? $socialMediaDetail['social_media'] : "";

        $hostname = (!empty($socialMediaDetail)) ? $socialMediaDetail['social_site'] : "";

        $conditions = array(

            'ClippingReport.press_release_id' => $prId,

            'ClippingReport.social_media_credential_id' => $smId,

            'ClippingReport.distribution_type' => $distributionType

        );

        $check = $clippingReportObj->find('first', array('conditions' => $conditions, "fields" => array("ClippingReport.id", "ClippingReport.views")));



        if (!empty($check['ClippingReport'])) {

            $data['ClippingReport']['views'] = $check['ClippingReport']['views'] + 1;

            $data['ClippingReport']['id'] = $check['ClippingReport']['id'];
        } else {

            $data['ClippingReport']['distribution_type'] = $distributionType;

            $data['ClippingReport']['press_release_id'] = $prId;

            $data['ClippingReport']['domain'] = null;

            $data['ClippingReport']['site_name'] = $hostname;

            $data['ClippingReport']['email'] = null;

            $data['ClippingReport']['release_page_url'] = null;

            $data['ClippingReport']['views'] = 1;

            $data['ClippingReport']['social_media_credential_id'] = $smId;
        }

        $clippingReportObj->save($data);
    }



    public function savePaymentData($status = '', $transaction_id = '', $records)
    {

        $paymentObj = ClassRegistry::init('Paypal');

        $data       =   array();

        $data['Payment']['transaction_id']      =      $transaction_id;

        $data['Payment']['status']              =      $status;

        $data['Payment']['record_json']             =      json_encode($records);

        if ($paymentObj->save($data)) {

            file_put_contents('./filename.txt', print_r($records, true));
        }

        return true;
    }





    private function get_domain($url)
    {

        $pieces = parse_url($url);

        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {

            return $regs['domain'];
        }

        return false;
    }



    public function updateClippingReport($prId = '', $hostname = '', $distributionType = '')
    {
        $socialMediaId = "";
        $clippingReportObj = ClassRegistry::init('ClippingReport');
        $extractHostname = explode(".", $hostname);
        if (is_array($extractHostname)) {
            $elementCount = count($extractHostname);
            $site_name = $extractHostname[$elementCount - 2];
        }
        $isSocialMedial = $this->checkSocialDomain($site_name);

        $conditions = array(
            'ClippingReport.press_release_id' => $prId,
            'ClippingReport.distribution_type' => $distributionType,
            'ClippingReport.site_name' => $site_name
        );

        if (!empty($isSocialMedial) && is_array($isSocialMedial)) {
            $distributionType = 'social_media_feed';
            $conditions = array(
                'ClippingReport.press_release_id' => $prId,
                'ClippingReport.distribution_type' => $distributionType,
                'ClippingReport.social_share_id' => $isSocialMedial['id']
            );
            $socialMediaId = $isSocialMedial['id'];
        }

        $check = $clippingReportObj->find('first', array('conditions' => $conditions, "fields" => array("ClippingReport.id", "ClippingReport.views")));
        if (!empty($check['ClippingReport'])) {
            $data['ClippingReport']['views'] = $check['ClippingReport']['views'] + 1;
            $data['ClippingReport']['id'] = $check['ClippingReport']['id'];
            $data['ClippingReport']['social_share_id'] = (!empty($socialMediaId)) ? $socialMediaId : NULL;
        } else {
            $data['ClippingReport']['distribution_type'] = $distributionType;
            $data['ClippingReport']['press_release_id'] = $prId;
            $data['ClippingReport']['domain'] = $hostname;
            $data['ClippingReport']['site_name'] = ucfirst($site_name);
            $data['ClippingReport']['release_page_url'] = null;
            $data['ClippingReport']['views'] = 1;
            $data['ClippingReport']['social_share_id'] = (!empty($socialMediaId)) ? $socialMediaId : NULL;
        }
        $clippingReportObj->save($data);
    }





    public function checkSocialDomain($domain = "")
    {
        $data = [];
        if (!empty($domain)) {
            $this->SocialShare = ClassRegistry::init('SocialShare');
            $data = $this->SocialShare->find('first', array('conditions' => array('OR' => array(
                'SocialShare.title like' => '%' . $domain . '%',
                'SocialShare.domain like' => '%' . $domain . '%',
            )), 'group' => "id"));
        }
        return (!empty($data)) ? $data['SocialShare'] : $domain;
    }
    public function checkLastTransactionStatusOfSubscrPayment($subscr_id)
    {
        $trxn = ClassRegistry::init('Transaction');
        $fields = ['status', 'subscr_status', 'reason_unsubscriber', 'id'];
        $data = $trxn->find('first', array('conditions' => array('Transaction.subscr_id' => $subscr_id), "fields" => $fields, 'order' => "Transaction.paymant_date DESC"));
        return (!empty($data['Transaction'])) ? $data['Transaction'] : [];
    }



    public function cancelSubscriptionBtn($subscr_id = '', $controller = '', $redirect, $isshowreason = '')
    {

        $actions = '';

        $check = $this->checkLastTransactionStatusOfSubscrPayment($subscr_id);

        if (!empty($check)) {

            if ($check['status'] == 'Success' && $check['subscr_status'] == '1') {

                $actions = ' ' . $this->Html->link(__("Cancel subscription"), array('controller' => $controller, 'action' => "cancel-subscription", $check['id'], $subscr_id, $redirect), array('class' => 'btn btn-xs btn-warning', 'onclick' => 'return reasonMessage(this.href);'));
            }



            if ($isshowreason && ($check['subscr_status'] == '0' || $check['status'] != 'Success')) {

                $actions = "<label class='badge bg-danger'>Subscription canceled.</label> <p class='text-danger'>" . $check['reason_unsubscriber'] . "</p>";
            }
        }



        return $actions;
    }





    public function all_transaction($subscriber_id = '', $excludeId)
    {

        $obj = ClassRegistry::init('Transaction');

        return $obj->find("all", array('conditions' => array('subscr_id' => $subscriber_id, 'Transaction.id !=' => $excludeId), 'order' => 'Transaction.id DESC'));
    }



    public function getTransactionType($type = '')
    {

        switch ($type) {

            case 'subscr_eot':

                $t = "Cancel";

                break;

            case 'subscr_eot':

                $t = "Cancel";

                break;

            case 'subscr_payment':

                $t = "Reccuring payment";

                break;

            default:

                $t = str_replace("_", " ", $type);

                break;
        }



        return $t;
    }



    public function summaryPrefix($date = '')
    {

        return date('F d, Y', strtotime($date)) . "/<a style='text-decoration:none;color:black' target='_blank' rel='nofollow' href='" . SITEFRONTURL . "' title='EMAILWIRE.COM'>EMAILWIRE.COM</a>/-- ";
    }





    public function getOrganizationList()
    {

        $obj = ClassRegistry::init('OrganizationType');

        return $obj->find("list", array('conditions' => array('OrganizationType.status' => '1'), 'fields' => ['id', 'name'], 'order' => 'OrganizationType.name ASC'));
    }



    public function getPRTitleForTransactionPressRelease($txId = '')
    {

        $obj = ClassRegistry::init('TransactionPressRelease');

        $obj->bindModel(array('belongsTo' => array('PressRelease' => array('className' => 'PressRelease'))));

        $data = $obj->find('first', array('conditions' => array('TransactionPressRelease.transaction_id' => $txId), 'fields' => ['TransactionPressRelease.transaction_id', 'TransactionPressRelease.id', 'PressRelease.title']));



        return (isset($data['PressRelease']['title']) && !empty($data['PressRelease']['title'])) ? ucfirst($data['PressRelease']['title']) : "-";
    }





    public function checkTransactionForNewsroom($newsroomId)
    {

        $trxn = ClassRegistry::init('Transaction');

        $count = $trxn->find('count', array('conditions' => array('Transaction.company_id' => $newsroomId, 'Transaction.status' => "Success")));

        return $count;
    }





    public function getAdditionalClippingReport($press_release_id)
    {

        $obj = ClassRegistry::init("ClippingAdditionalReport");

        return $obj->find("all", ["conditions" => ["ClippingAdditionalReport.press_release_id" => $press_release_id]]);
    }





    public function countMediaEmails($listId)
    {

        $subscriber = ClassRegistry::init('Subscriber');

        $count = $subscriber->find("count", array('conditions' => array("Subscriber.list" => $listId)));

        return $count;
    }





    public function getRecordNotFoundMsg()
    {

        return "<div class='row noresultfound'><div class='text-center'><h2><i class='icon fa fa-warning' aria-hidden='true'></i> No result found!</h2></div></div>";
    }





    public function getInvociePdfSetting($value = '')
    {
        $pdf_obj = ClassRegistry::init('PdfSetting');
        $pdf_data = $pdf_obj->find('first', array('conditions' => array('PdfSetting.id' => '2')));
        return $pdf_data;
    }


    /*
     * @params:  
     * @Function use: getCategoryFormat: This function use to get the category with child and parent list
     * @created by: Hitesh verma
     * @Created: 30-07-2022
     */
    function getCategoryFormat($categories, $filterby)
    {
        $html = '';
        if (!empty($categories)) {
            foreach ($categories as $pIndex => $pCategory) {
                if (!empty($pCategory)) {
                    $pslug = $pCategory['Category']['slug'];
                    $html .= "<ul>";
                    $html .= "<li class='cat_list'>";
                    $html .= "<h5>" . $pCategory['Category']['name'] . "</h5>";
                    if (!empty($pCategory['children'])) {
                        $html .= $this->childCategoryFormat($pCategory['children'], $pslug, $filterby);
                    }
                    $html .= "</li>";
                    $html .= "</ul>";
                }
            }
        }
        return $html;
    }
    /*
     * @params:  
     * @Function use: childCategoryFormat: This function use to get the category with child and parent list
     * @created by: Hitesh verma
     * @Created: 30-07-2022
     */
    private function childCategoryFormat($pCategory, $pslug, $filterby)
    {
        $html = '';
        $html = '<ul class="list msa-list">';
        foreach ($pCategory as $slug => $category) {
            $slug = $category['Category']['slug'];
            $html .= '<li class="list-item"><a href="' . SITEURL . $filterby . "/" . $pslug . "/" . $slug . '">' . $category['Category']['name'] . '</a>';
            if (!empty($category['children'])) {
                $html .= $this->childCategoryFormat($category['children'], $pslug, $filterby);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }



    function pngToJpg($company_logo_path, $company_logo_path1, $quality)
    {

        $image = imagecreatefrompng($company_logo_path);

        list($width, $height) = getimagesize($company_logo_path);

        $output = imagecreatetruecolor($width, $height);

        $white = imagecolorallocate($output,  255, 255, 255);

        imagefilledrectangle($output, 0, 0, $width, $height, $white);

        imagecopy($output, $image, 0, 0, 0, 0, $width, $height);

        imagejpeg($output, $company_logo_path1, $quality);

        imagedestroy($image);
    }

     /*
     * @params:  
     * @Function use: convertImageIntoBase64: Using for clipping report
     * @created by: Hitesh verma
     * @Created: 16-10-2022
     */
 
     function convertImageIntoBase64($imagePath="",$imageName=""){
        if(!empty($imagePath)){
            $type = pathinfo($imagePath, PATHINFO_EXTENSION);
            $fileData = file_get_contents($imagePath);  // https://image.com/img_name.png
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($fileData);
            return $base64;
        }
        return false;
    }

    public function numberFormatAsUs($number = "")
    {

        if (!empty($number)) {
            setlocale(LC_MONETARY, 'en_US');
            $number = $this->money_format('%!i', $number);
            return $number;
        }
        return (!empty($number))?$number:"-";
    }

    
    public function money_format($format, $number)
    {
        $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?' .
            '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
        if (setlocale(LC_MONETARY, 0) == 'C') {
            setlocale(LC_MONETARY, '');
        }
        $locale = localeconv();
        preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
        foreach ($matches as $fmatch) {
            $value = floatval($number);
            $flags = array(
                'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
                    $match[1] : ' ',
                'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
                'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                    $match[0] : '+',
                'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
                'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
            );
            $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
            $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
            $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
            $conversion = $fmatch[5];

            $positive = true;
            if ($value < 0) {
                $positive = false;
                $value  *= -1;
            }
            $letter = $positive ? 'p' : 'n';

            $prefix = $suffix = $cprefix = $csuffix = $signal = '';

            $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
            switch (true) {
                case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                    $prefix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                    $suffix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                    $cprefix = $signal;
                    break;
                case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                    $csuffix = $signal;
                    break;
                case $flags['usesignal'] == '(':
                case $locale["{$letter}_sign_posn"] == 0:
                    $prefix = '(';
                    $suffix = ')';
                    break;
            }
            if (!$flags['nosimbol']) {
                $currency = $cprefix .
                    ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                    $csuffix;
            } else {
                $currency = '';
            }
            $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

            $value = number_format(
                $value,
                $right,
                $locale['mon_decimal_point'],
                $flags['nogroup'] ? '' : $locale['mon_thousands_sep']
            );
            $value = @explode($locale['mon_decimal_point'], $value);

            $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
            if ($left > 0 && $left > $n) {
                $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
            }
            $value = implode($locale['mon_decimal_point'], $value);
            if ($locale["{$letter}_cs_precedes"]) {
                $value = $prefix . $currency . $space . $value . $suffix;
            } else {
                $value = $prefix . $value . $space . $currency . $suffix;
            }
            if ($width > 0) {
                $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                    STR_PAD_RIGHT : STR_PAD_LEFT);
            }

            $format = str_replace($fmatch[0], $value, $format);
            
        }
        return  ($format > 0)?str_replace(".00","",$format):"-"; //N/A
    }
    
    
}
