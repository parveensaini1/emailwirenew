<?php
echo $this->Html->script(
    array( 
        '/plugins/lazyload/jquery.lazy.min',
        
    )
);
echo $this->Js->writeBuffer();


//echo $html; 
$plan = $data['Plan'];
$planName = $plan['PlanCategory']['name'];
$pdfFindReplace = array('##planName##' => $planName);
$pdf_title                  =   $pdf_data['PdfSetting']['title'];
$pdf_logo                   =   $pdf_data['PdfSetting']['logo'];
$pdf_welcome_text           =   strtr($pdf_data['PdfSetting']['welcome_text'], $pdfFindReplace);
$pdf_footer      =   $pdf_data['PdfSetting']['footer_text'];
$company_name = $data['Company']['name'];

//$company_logo = WWW_ROOT . "files/company/logo/" . $data['Company']['logo_path'] . "/" . $data['Company']['logo']; 

$company_logo = SITEURL . "files/company/logo/" . $data['Company']['logo_path'] . "/" . $data['Company']['logo'];


$replaceCompanyName = ["##COMPANYNAME##" => $company_name];
$replaceFooterText = ["##PHONE##" => strip_tags(Configure::read('Site.phone')), "##YEAR##" => date('Y')];


?>

<div class='row'>
    <div class="col-sm-1 col-md-1"></div>
    <div class="col-sm-10 col-md-10">
        <div class="row">
            <div class="col-sm-6 col-md-6  text-md-left text-center"><img style="width: 120px;" src='<?php echo $company_logo; ?>'></div>
            <div class="col-sm-6 col-md-6  text-md-right text-center "><img src='<?php echo  SITEURL . "files/pdf_settings/" . $pdf_logo; ?>'></div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="text-center" style="color: #6a6a6a; font-size: 30px; font-weight: 600;font-family:' Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif'; padding-bottom: 5px; padding-top: 5px;">
                    <h3 class="m-4"><strong> <?php echo strtr($pdf_title, $replaceCompanyName); ?></strong></h3>
                </div>
                <div class="text-center">Press Release Distribution by <?php echo $siteName; ?></div>
            </div>
        </div>
        <div class="row mt-4 table-responsive">
            <table class="table table-borderless" style="border-top: 7px solid #f39c12;"> 
                <tbody>
                <tr>
                    <td style="width:25%; background-color: #ccc;"><strong class="text-center p-2">Press Release Title</strong></td>
                    <td style="width:75%"><h6><?php echo $data['PressRelease']['title']; ?></h6></td> 
                </tr>
                <tr>
                    <td style="width:25%; background-color: #ccc;"><strong class="text-center p-2">Press Release ID:</strong></td>
                    <td style="width:75%"><strong><?php echo $data['PressRelease']['id']; ?></strong></td> 
                </tr>
                <tr>
                    <td style="width:25%; background-color: #ccc;"><strong class="text-center p-2">Press Release Plan</strong></td>
                    <td style="width:75%"><strong><?php echo $planName; ?></strong></td> 
                </tr>
                <tr>
                    <td style="width:25%; background-color: #ccc;"><strong class="text-center p-2">Distribution Date</strong></td>
                    <td style="width:75%"><strong><?php echo date($dateformat, strtotime($data["PressRelease"]['release_date'])); ?></strong></td> 
                </tr> 
                <tr>
                    <td style="width:25%; background-color: #ccc;"><strong class="text-center p-2">Reporting Date</strong></td>
                    <td style="width:75%"><strong><?php echo date($dateformat); ?></strong></td> 
                </tr>
            
                </tbody>
            </table> 
        </div>

        <div class="row mt-4 table-responsive">
            <div  style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>Statistics</div>

            <table class="table table-borderless"> 
                <tbody>
                <tr>
                    <td><strong class="text-center p-2">Total Potential Audience</strong></td>
                    <td align="right" style="width:75%"><strong><?php echo (!empty($data['0']['potentialAudienceCount']))?$this->Custom->numberFormatAsUs($data['0']['potentialAudienceCount']):0; ?></strong></td> 
                </tr>
                <tr>
                    <td><strong class="text-center p-2">Release Views/Reads:</strong></td>
                    <td align="right" style="width:75%"><strong><?php echo (!empty($data['PressRelease']['views']))?$this->Custom->numberFormatAsUs($data['PressRelease']['views']):0; ?></strong></td> 
                </tr>
                <tr>
                    <td><strong class="text-center p-2">Click-Throughs</strong></td>
                    <td align="right" style="width:75%"><strong><?php echo (!empty($data['0']['networkFeedCount']))?$this->Custom->numberFormatAsUs($data['0']['networkFeedCount']):0; ?></strong></td> 
                </tr>
                <tr>
                    <td><strong class="text-center p-2">Social Shares</strong></td>
                    <td align="right" style="width:75%"><strong><?php echo (!empty($data['0']['socialShareCount']))?$this->Custom->numberFormatAsUs($data['0']['socialShareCount']):0; ?></strong></td> 
                </tr> 
                <tr>
                    <td><strong class="text-center p-2">Prints</strong></td>
                    <td  align="right"style="width:75%"><strong><?php echo (!empty($data['0']['printCount']))?$this->Custom->numberFormatAsUs($data['0']['printCount']):0; ?></strong></td> 
                </tr>
                <tr>
                    <td><strong class="text-center p-2">Emailed</strong></td>
                    <td  align="right"style="width:75%"><strong><?php echo (!empty($data['0']['emailCount']))?$this->Custom->numberFormatAsUs($data['0']['emailCount']):0; ?></strong></td> 
                </tr>
            
                </tbody>
            </table> 
        </div>

        <?php if(!empty($nwrelationships)){ ?>
            <div class="row mt-4 table-responsive">
                <?php 
                 $paginatorInformation = $this->Paginator->params();
                 $clippingRow = '';
                 $counter=(($paginatorInformation['page']-1)*$paginatorInformation['limit'])+1;
                 $totalRecord=(!empty($paginatorInformation['count']))?$paginatorInformation['count']:1;
                            
                 if (isset($nwrelationships) && !empty($nwrelationships)) {
                    $pdfNetworkDescription = str_replace(["##PR-PICKUPS##","##PR-AUDIENCE##"], [$totalRecord,$sumOfPotentialAudience], $pdf_data['PdfSetting']['network_description']);

                     $clippingRow   .= "
                     <div  style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>Media Pickup</div>
                         <div style='margin:0 10px 10px 10px;color: #4d4d4d;'>$pdfNetworkDescription
                           
                         </div>
                         <table style='margin:5px 0 5px 0;width: 100%;border:none' cellspacing='5' cellpadding='5' id='dataTables-example' class='table table-striped'>
                             <thead>    
                                 <th width='20' style='text-align:left;max-width: 20px;'>S/N</th>
                                 <th>Site Identity</th>
                                 <th style='text-align:left;'>Media Name</th>
                                 <th width='50' style='text-align:left;max-width: 50px;'>Website</th>
                                 <th style='text-align:left;'>Location</th>
                                 <th width='70' style='text-align:left;max-width: 70px;' class='white-space-nowrap' align='center'>Media Type</th>
                                 <th style='text-align:left;'>Potential<br>Audience</th>
                             </thead></tbody>"; 
                     foreach ($nwrelationships as $index => $nwrelationship) {
                         $networkwebsite = $nwrelationship;
                         $siteLogo = SITEURL . $networkwebsite['NwRelationships']['site_logo'];
         
                         $pa = (!empty($nwrelationship['NwRelationships']['potential_audience'])) ? $nwrelationship['NwRelationships']['potential_audience'] : 0;
         
                         $clippingRow .= "
                             <tr style='padding-top:3px'>
                                 <td style='text-align:left;'>" . $counter . "</td>
                                 <td style='text-align:left;'><img class='lazyload' alt=" . ucwords($networkwebsite['NwRelationships']['site_name']) . " width='120px' data-src='" . $siteLogo . "'></td>
                                 <td style='text-align:left;'>" . ucwords($networkwebsite['NwRelationships']['site_name']) . "</td>
                                 <td style='text-align:left;'><a class='white-space-nowrap' target='_blank' href=" . $nwrelationship['NwRelationships']['press_release_link'] . ">View Release</a></td>
                                 <td style='text-align:left;'>" . $nwrelationship['NwRelationships']['location'] . "</td>
                                 <td style='text-align:left;' class='white-space-nowrap' >" . $nwrelationship['NwRelationships']['type'] . "</td>
                                 <td style='text-align:left;'>" .$this->Custom->numberFormatAsUs($pa) . "</td>
                             </tr>";
                         $counter++;
                     }
                     $clippingRow   .= "</tbody></table> ";
                 }
                 echo $clippingRow;
                ?>
            </div>
            <?php  if($paginatorInformation['pageCount']>1){ ?>
                    <div class="row">
                        <?php echo $this->element('pagination'); ?>
                    </div>
                <?php }
            
            } ?>
        <?php /* if(!empty($rssMedia)){ ?>
            <div class="row mt-4 table-responsive">
                <?php echo $rssMedia; ?>
            </div>
        <?php } */ ?>
        <?php /* if(!empty($jsMedia)){ ?>
            <div class="row mt-4 table-responsive">
                <?php echo $jsMedia; ?>
            </div>
        <?php } */ ?>
        <?php /* if(!empty($socialMedia)){ ?>
            <div class="row mt-4 table-responsive">
                <?php echo $socialMedia; ?>
            </div>
        <?php } */ ?>

        <?php /* if(!empty($newsLetterMailReport)){ ?>
        <div class="row mt-4 table-responsive">
            <?php echo $newsLetterMailReport; ?>
        </div>
        <?php } ?>

        <?php if(!empty($sendyMailSentReport)){ ?>
        <div class="row mt-4 table-responsive">
            <?php echo $sendyMailSentReport; ?>
        </div>
        <?php } ?>

        <?php if(!empty($sendyMailSentReportByCountry)){ ?>
        <div class="row mt-4 table-responsive">
            <?php echo $sendyMailSentReportByCountry; ?>
        </div>
        <?php } */ ?>


        <div class="row mt-4 table-responsive"> 
            <div style='font-size:14px; color: #4d4d4d; text-align: center; margin-top:50px;'><?php echo strtr($pdf_footer, $replaceFooterText); ?></div> 
        </div>
        
    </div>
    <div class="col-sm-1 col-md-1"></div>
</div>


<script>
    $(document).ready(function(){
        $('.lazyload').lazy();
    });
</script>