
<?php
/*
echo "<b>Press Release Id-</b>".$prId; 
echo "<b>Created By-</b>".$userData['StaffUser']['first_name']." ".$userData['StaffUser']['last_name'];  
echo "<b>Email- </b>".$userData['StaffUser']['email']; */
?>

<?php //echo $html; 
$plan = $data['Plan'];
$planName = $plan['PlanCategory']['name'];
$pdfFindReplace = array('##planName##' => $planName);
$pdf_title                  =   $pdf_data['PdfSetting']['title'];
$pdf_logo                   =   $pdf_data['PdfSetting']['logo'];
$pdf_welcome_text           =   strtr($pdf_data['PdfSetting']['welcome_text'], $pdfFindReplace);
$pdf_footer      =   $pdf_data['PdfSetting']['footer_text'];
$company_name = $data['Company']['name'];

//$company_logo = WWW_ROOT . "files/company/logo/" . $data['Company']['logo_path'] . "/" . $data['Company']['logo']; 

$company_logo = SITEFRONTURL . "files/company/logo/" . $data['Company']['logo_path'] . "/" . $data['Company']['logo'];
 
$replaceCompanyName = ["##COMPANYNAME##" => $company_name];
$replaceFooterText = ["##PHONE##" => strip_tags(Configure::read('Site.phone')), "##YEAR##" => date('Y')];

echo $this->Html->css(array('/plugins/bootstrap-table-editable/bootstrap-editable'));
echo $this->Html->script(
    array(
        '/plugins/bootstrap-table-editable/bootstrap-editable', 'updatetablecontent',
        '/plugins/lazyload/jquery.lazy.min',
        // '/plugins/lazyload/jquery.lazy.plugins.min', no need this file
    )
);
echo $this->Js->writeBuffer();
?>
<div class="row">
    <div class="col-sm-1">
    </div>
    <div class="col-sm-11">
        <?php 

        $sortBy=($orderBy=="potential_audience")?'order_num':"potential_audience";
        $sortByLable=($orderBy=="potential_audience")?'Custom Order':"Potential Audience";
        $actions =' ' . "<button class='sendcls btn btn-xs btn-info  float-right' id='".$data['PressRelease']['id']."' useremail='".$data['StaffUser']['email']."'" ."' sendtitle='".$data['PressRelease']['title']."'>Send Report</button>";
        $actions .= ' ' . $this->Html->link(__('Download Report'), array('controller' => $controller, 'action' => 'download',$data['PressRelease']['id'],$orderBy), array('class' => 'btn btn-xs btn-info  float-right mr-2'));
        $actions .=$this->Html->link(__('Export Clipping Report CSV'), array('controller' => $controller, 'action' => 'export_clipping_report_in_csv',$prId), array('class' => 'btn btn-xs btn-info  float-right mr-2'));
        $actions .= ' ' . $this->Html->link(__("Short By $sortByLable"), array('controller' => $controller, 'action' => 'viewclippingreport',$data['PressRelease']['id'],$sortBy), array('class' => 'btn btn-xs btn-info  float-right mr-2'));
        
        echo $actions;
        ?>

    </div>
</div>   
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
    <?php 
        echo "<b>Press Release id-</b> " . (isset($prId) ? $prId : "N/A");

        // Check if $approvedUser and the necessary keys exist before trying to access them
        if (isset($approvedUser['StaffUser']['first_name'], $approvedUser['StaffUser']['last_name'])) {
            echo "<b class='ml-4'>Approved By-</b> " . 
                 $approvedUser['StaffUser']['first_name'] . " " . 
                 $approvedUser['StaffUser']['last_name'];
        } else {
            echo "<b class='ml-4'>Approved By-</b> N/A";
        }
    ?>
</h3>

                <div class="card-tools">
                    <div class="input-group input-group-sm text-right">
                        <?php echo ucfirst($data['PressRelease']['title']); ?>
                        <?php // echo  $this->Html->link(__('Edit Clipping Report'), array('controller' => $controller, 'action' => 'edit',$prId), array('class' => 'btn btn-xs btn-info')); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-tools">
        <div class='row mt-4'>
            <div class="col-sm-1 col-md-1"></div>
            <div class="col-sm-10 col-md-10">
                <div class="row">
                    <div class="col-sm-6 col-md-6  text-md-left text-center"><img style="width: 120px;" class="lazyload" src='<?php echo $company_logo; ?>'></div>
                    <div class="col-sm-6 col-md-6  text-md-right text-center "><img src='<?php echo  SITEFRONTURL . "files/pdf_settings/" . $pdf_logo; ?>'></div>
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
                    <table class="table " style="border-top: 7px solid #f39c12;">
                        <tbody>
                            <tr>
                                <td style="width:25%; background-color: #ccc;"><strong class="text-center p-2">Press Release Title</strong></td>
                                <td style="width:75%">
                                    <h6><?php echo $data['PressRelease']['title']; ?></h6>
                                </td>
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
                    <div style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>Statistics</div>

                    <table class="table ">
                        <tbody>
                            <tr>
                                <td><strong class="text-center p-2">Total Potential Audience</strong></td>
                                <td align="right" style="width:75%"><strong><?php echo (!empty($data['0']['potentialAudienceCount'])) ? $this->Custom->numberFormatAsUs($data['0']['potentialAudienceCount']) : 'NA'; ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong class="text-center p-2">Release Views/Reads:</strong></td>
                                <td align="right" style="width:75%"><strong><?php echo (!empty($data['PressRelease']['views'])) ? $this->Custom->numberFormatAsUs($data['PressRelease']['views']) : 'NA'; ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong class="text-center p-2">Click-Throughs</strong></td>
                                <td align="right" style="width:75%"><strong><?php echo (!empty($data['0']['networkFeedCount'])) ? $this->Custom->numberFormatAsUs($data['0']['networkFeedCount']) : 'NA'; ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong class="text-center p-2">Social Shares</strong></td>
                                <td align="right" style="width:75%"><strong><?php echo (!empty($data['0']['networkFeedCount'])) ? $this->Custom->numberFormatAsUs($data['0']['networkFeedCount']) : 'NA'; ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong class="text-center p-2">Prints</strong></td>
                                <td align="right" style="width:75%"><strong><?php echo (!empty($data['0']['networkFeedCount'])) ? $this->Custom->numberFormatAsUs($data['0']['networkFeedCount']) : 'NA'; ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong class="text-center p-2 a">Emailed</strong></td>
                                <td align="right" style="width:75%"><strong><?php echo (!empty($data['0']['networkFeedCount'])) ? $this->Custom->numberFormatAsUs($data['0']['networkFeedCount']) : 'NA'; ?></strong></td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <?php if (!empty($nwrelationships)) { 
                     $paginatorInformation = $this->Paginator->params();  
                     $totalRecord=(!empty($paginatorInformation['count']))?$paginatorInformation['count']:1;
                    ?>
                    <div class="row mt-4 table-responsive">
                        <div style='color: #4d4d4d; font-size: 25px; font-weight: 600; border-bottom: 5px solid #cac8c8;  margin-bottom: 15px; padding-bottom: 5px; padding-top: 25px; width: 100%;'>Media Pickup</div>
                        <div style='margin:0 10px 10px 10px;color: #4d4d4d;'>
                            <span><?php 
                               $pdfNetworkDescription = str_replace(["##PR-PICKUPS##","##PR-AUDIENCE##"], [$totalRecord,$sumOfPotentialAudience], $pdf_data['PdfSetting']['network_description']);

                            echo $pdfNetworkDescription; ?></span>
                            <!-- <a href='javascript:void(0)' class ='float-right btn btn-sm btn-primary' onclick='addMediaPicupNewRowInTable("mediaPicup")'><i class="fas fa-plus"></i></a> -->
                            <?php echo $this->Html->link('<i class="fas fa-plus"></i>', array('controller' => $controller, 'action' => 'addNewRowClippingReport', base64_encode($prId)), array('class' => 'float-right btn btn-sm btn-primary', 'escape' => false)); ?>
                        </div>
                        <table style="width: 100%;border:none;" id='mediaPicup' class='table table-striped'>
                            <thead>
                                <th>S/N</th>
                                <th>Site Identity</th>
                                <th>Media Name</th>
                                <th>URL</th>
                                <th>Location</th>
                                <th class='white-space-nowrap' align='center'>Media Type</th>
                                <th>Potential<br>Audience</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <!--  contenteditable="true" -->
                                <?php
                                $counter = $total_potential_audience = 0;
                                foreach ($nwrelationships as $index => $nwrelationship) {
                                    $action = '
                                <div class="btn-group-vertical"> 
                                <div class="btn-group">
                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                </button>
                                <ul class="dropdown-menu" style="">
                                <li>'.$this->Html->link(__('Move up'), array('controller' => $controller, 'action' => 'moverecord','up',base64_encode($nwrelationship['NwRelationships']['id']),base64_encode($prId),$orderBy ), array('class' => 'btn d-block btn-outline-primary btn-sm m-2','escape'=>false,)).'</li>
                                <li>'.$this->Html->link(__('Move Down'), array('controller' => $controller, 'action' => 'moverecord','down',base64_encode($nwrelationship['NwRelationships']['id']),base64_encode($prId),$orderBy ), array('class' => 'btn d-block btn-outline-primary btn-sm m-2 ','escape'=>false,)).'</li>
                                <li>'.$this->Html->link(__('<i class="fas fa-trash-alt"></i>'), array('controller' => $controller, 'action' => 'trash','NwRelationships',base64_encode($nwrelationship['NwRelationships']['id']),"viewclippingreport",base64_encode($prId)), array("id"=>"homelink",'class' =>'btn d-block btn-outline-danger btn-sm m-2', 'onclick' =>"return confirmAction(this.href,'Are you sure want to delete?.','DELETE','question','true');",'escape'=>false,'title'=>'Delete')).'</li>    

                                </ul>
                                </div>
                                </div> ';
                                    $counter++;
                                    // print_r($nwrelationship);exit;
                                    $id = $nwrelationship['NwRelationships']['id'];
                                    $siteLogo =  SITEFRONTURL . $nwrelationship['NwRelationships']['site_logo'];

                                    $pa = (!empty($nwrelationship['NwRelationships']['potential_audience'])) ? $nwrelationship['NwRelationships']['potential_audience'] : "-";

                                    $ajxUrl = SITEURL . "ajax/updateClippingReportTableData/NwRelationships/".$nwrelationship['NwRelationships']['network_website_id'];
                                    $siteLogoUploadForm = '<form action="' . SITEURL . 'ClippingReports/upload_site_logo/' . $prId . '" id="nwRelationship_form_' . $nwrelationship['NwRelationships']['id'] . '" class="hide sitelogoform" novalidate="novalidate" enctype="multipart/form-data" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
                                        <div class="form-group">
                                            <input type="hidden" name="data[NetworkWebsite][id]" value="' . $nwrelationship['NwRelationships']['network_website_id']. '">
                                            <input type="hidden" name="data[NetworkWebsite][website_name]" value="' .$nwrelationship['NwRelationships']['site_name']. '">
                                            <input type="hidden" name="data[NetworkWebsite][website_domain]"" value="' .$nwrelationship['NwRelationships']['site_url']. '">
                                            <input type="hidden" name="data[NetworkWebsite][website_location]" value="' .$nwrelationship['NwRelationships']['location']. '">
                                            <input type="hidden" name="data[NetworkWebsite][website_media_type]" value="' .$nwrelationship['NwRelationships']['type'] .'">

                                            <input type="hidden" name="data[NwRelationships][id]" value="' . $nwrelationship['NwRelationships']['id'] . '">
                                            <input type="file" name="data[NwRelationships][site_logo]" class="form-control sitelogoinput"  id="sitelogo">
                                        </div>
                                    </form>
                                    <img class="sitelogo lazyload" id="sitelogo_' . $nwrelationship['NwRelationships']['id'] . '" alt="' . ucwords($nwrelationship['NwRelationships']['site_name']) . '" width="120px" data-src="' . $siteLogo . '">
                                    <a href="javascript:void(0)" style="display: inline-block;" class="mt-2 btn-xs btn-info" onclick="showlogoform(' . $nwrelationship['NwRelationships']['id'] . ')">Change Logo</a>';
                                    echo "
                                 <tr style='padding-top:3px'>
                                     <td>" . $index + 1 . "</td>
                                     <td>$siteLogoUploadForm</td>
                                     <td class='editable  border-top-0' id='site_name' data-type='text' data-url='$ajxUrl' data-pk='" . $nwrelationship['NwRelationships']['id'] . "' data-value='" . ucwords($nwrelationship['NwRelationships']['site_name']) . "'>" . ucwords($nwrelationship['NwRelationships']['site_name']) . "</td>
                                     
                                     <td>
                                     <a href=" . $nwrelationship['NwRelationships']['press_release_link'] . ">View Release</a>
                                     <a id='press_release_link' class='editable' data-url='$ajxUrl' data-pk='" . $nwrelationship['NwRelationships']['id'] . "'   data-type='text' data-value='" . ucwords($nwrelationship['NwRelationships']['press_release_link']) . "' target='_blank' href=" . $nwrelationship['NwRelationships']['press_release_link'] . ">Edit <i class='fas fa-edit'></i></a>
                                     </td>
                                     <td class='editable  border-top-0' id='location' data-url='$ajxUrl' data-pk='" . $nwrelationship['NwRelationships']['id'] . "' >" . $nwrelationship['NwRelationships']['location'] . "</td>
                                     <td class='white-space-nowrap editable  border-top-0' id='type' data-url='$ajxUrl' data-pk='" . $nwrelationship['NwRelationships']['id'] . "' data-type='text' data-value='" . ucwords($nwrelationship['NwRelationships']['type']) . "'>" . $nwrelationship['NwRelationships']['type'] . "</td>
                                     <td class='editable  border-top-0' id='potential_audience'data-url='$ajxUrl' data-pk='" . $nwrelationship['NwRelationships']['id'] . "' data-type='text' data-value='$pa'>" . $this->Custom->numberFormatAsUs($pa) . "</td>
                                     <td>$action</td>
                                 </tr>";
                                    //  $total_potential_audience = $total_potential_audience + $nwrelationship['NwRelationships']['potential_audience'];
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <?php echo $this->element('pagination'); ?>
                    </div>
                <?php } ?>

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
    </div>
</div>
<?php echo $this->element('send_report_by_mail'); ?>
<script> 
    function showlogoform(id) {
        if (id !== undefined) {
            $(".sitelogo").removeClass("hide");
            $(".sitelogoform").addClass("hide");
            $("#nwRelationship_form_" + id).removeClass("hide");
            $("#sitelogo_" + id).addClass("hide");

        }
    }

     $('#redirectMe').click(function(e){
          var hrefValue = $(this).attr('href');
          window.location.href = hrefValue;
     });
    $('.sitelogoinput').on('change', function() {
        var ext = $(this).val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            message_box('Invalid extension!! Please upload file with extension pdf, gif, png, jpg, jpeg!', "Alert!", "warning", "mid-center");
            $(this).parents('form')[0].reset();
            return false;
        }
        var fileKbSize = (this.files[0].size / 1024);
        if (fileKbSize > 4056) { //greater than 1.5 MB
            $(this).val('');
            message_box('Size exceeded !! Please upload file with less than 4MB!', "Alert!", "warning", "mid-center");
            $(this).parents('form')[0].reset();
            return false;
        }
        if (fileKbSize <= 0) { //greater than 1.5 MB
            $(this).val('');
            message_box('content: Please upload file with size greater than 10kb!', "Alert!", "warning", "mid-center");
            $(this).parents('form')[0].reset();
            return false;
        }
        if ($(this).val() != '') {
            $('#AjaxLoading').show();
            $(this).parents('form').submit();
        }
    });

    $(document).ready(function(){
        $('.lazyload').lazy();
    });
</script>