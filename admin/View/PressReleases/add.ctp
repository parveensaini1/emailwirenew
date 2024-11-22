 <div class="card card-default <?php echo $this->Post->classAccordingToLanguage($selectedLang);  ?> ">
    <div class="card-body">
        <?php
        echo $this->Form->create('PressRelease', array('id' => 'release_form', 'type' => 'file', 'novalidate' => 'novalidate', 'id' => "PressReleaseform", 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
        echo $this->Form->input('id');
        ?>
        <?php
        if ($selectedplan == '') { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <label>Language</label>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Choose Language here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        <?php
                        echo $this->Form->input("$model.language", array('class' => 'form-control ','options' => $languages, "default" =>$selectedLang,'onchange' => "redirectByLanguage(this.value);", 'label' => false,"id"=>"languageId"));
            
                        //echo $this->Form->input('plan_id', array("type" => 'hidden', "value" => $selectedLang));
                        // $language_list = array("1" => "English", "2" => "Arabic");
                        // echo $this->Form->input('language_id', array('class' => 'form-control ', 'options' => $languages,"default"=>$defaultLang,'label' => false,"id"=>"languageId"));
                        ?>
                    </div>
                </div>
            
            <label>
                Select Press Release plan
            </label>
            <a href="#" data-toggle="tooltip" title="Select any plan"><i class="fa fa-question-circle" aria-hidden="true"></i></a>

            <?php  echo $this->Form->input('plan_id', array('class' => 'form-control ', 'onchange' => "redirect_selectedplan(this.value);", 'empty' => '-Select-', 'options' => $plan_list, "default" => $selectedplan, 'label' => false)); ?>

        <?php } else { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <label>Language</label>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Choose Language here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        <?php
                        echo $this->Form->input("$model.language", array('class' => 'form-control ', 'empty' => '-Select-', 'options' => $languages, "default" =>$selectedLang,'onchange' => "redirectByLanguage(this.value);", 'label' => false,"id"=>"languageId"));
            
                        //echo $this->Form->input('plan_id', array("type" => 'hidden', "value" => $selectedLang));
                        // $language_list = array("1" => "English", "2" => "Arabic");
                        // echo $this->Form->input('language_id', array('class' => 'form-control ', 'options' => $languages,"default"=>$defaultLang,'label' => false,"id"=>"languageId"));
                        ?>
                    </div>
                </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Company Detail</span></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo $this->Form->input('plan_id', array("type" => 'hidden', "value" => $selectedplan));
                    ?>
                    <label>
                        Select Newsroom
                    </label>
                    <a href="#" data-toggle="tooltip" title="Select any newsroom"><i class="fa fa-question-circle" aria-hidden="true"></i> </a>
                    <?php
                    echo $this->Form->input('company_id', array('class' => 'company-select form-control ', 'empty' => '-Select-', 'onchange' => "load_company_detail(this.value);", 'options' => $company_list, 'label' => false, 'id' => "company_list"));

                    echo $this->Form->input('submittype', array("type" => "hidden", 'value' => '', 'id' => "submittype"));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    echo $this->Form->input('staff_user_id', array("type" => "hidden", 'id' => "userid"));
                    ?>
                    <label>
                        Contact Name
                    </label>
                    <a href="#" data-toggle="tooltip" title="Fill contact name here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('contact_name', array('class' => 'form-control ', 'id' => 'contact_name', 'type' => 'text', "required" => "required", "maxLength" => "50", 'label' => false)); ?>
                </div>
                <div class="col-sm-6">
                    <label>
                        Job Title
                    </label>
                    <a href="#" data-toggle="tooltip" title="Fill job title here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php echo $this->Form->input('job_title', array('class' => 'form-control', 'id' => 'job_title', 'type' => 'text', "required" => "required", 'label' => false)); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label>
                        Email
                    </label>
                    <a href="#" data-toggle="tooltip" title="Fill email here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php echo $this->Form->input('email', array('class' => 'form-control ', 'id' => 'email', 'type' => 'text', "required" => "required", 'label' => false)); ?>
                </div>
                <div class="col-sm-6">
                    <label>
                        Phone
                    </label>
                    <a href="#" data-toggle="tooltip" title="Fill phone number here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php echo $this->Form->input('phone', array('class' => 'form-control', 'id' => 'phone', 'type' => 'text', "required" => "required", 'minLength' => "10", 'maxLength' => "15", 'onkeypress' => "return isNumber(event)", 'label' => false)); ?>
                </div>
            </div>



            <div class="row">
                <div class="col-sm-12">
                    <h5 class="block-title" style="border-bottom: 2px solid  #00a65a;"><span style="background-color: #00a65a;">Source of Press Release</span></h5>
                </div>
            </div>

            <div id="sourcebox" class="row <?php echo (!empty($this->data[$model]['is_source_manually'])) ? "hide" : "show"; ?>">
                <div class="col-sm-3">

                    <label>
                        Country
                    </label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Select country here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>

                    <?php echo $this->Form->input('media_country_id', array('class' => 'form-control select2', 'onchange' => "getSourceStates(this.value,'media_state_div','$model')", 'options' => $allCountries, 'empty' => '-Select State-', 'label' => false, "required" => "required")); ?>
                                            
                </div>
                <div class="col-sm-3" id="media_state_div">
                    <label>
                        State
                    </label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Select state here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php echo $this->Form->input('media_state_id', array('class' => 'form-control select2', 'options' => $allStates,  'empty' => '-Select-', 'label' => false, "required" => "required","onchange"=>"getSourceCities(this.value,'media_msa_id_box','$model')")); ?>
                </div>

                <div class="col-sm-3" id="media_msa_id_box">
                    <label>
                        City
                    </label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Select city here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php  echo $this->Form->input('media_msa_id', array('class' => 'form-control select2', 'options' => $allMsas, 'empty' => '-Select-', 'label' => false, "required" => "required"));  ?>
                </div>
            </div>
            <div id="manullalsourcebox" class="row <?php echo (!empty($this->data[$model]['is_source_manually'])) ? "show" : "hide"; ?>">
                <div class="col-sm-3 col-md-3">
                    <label>
                        Country
                    </label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Select enter country here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php echo $this->Form->input('source_country', array('class' => 'form-control ', 'type' => 'text', 'maxlength' => "255", 'autocomplete' => "off", 'label' => false)); ?>
                </div>
                <div class="col-sm-3 col-md-3" id="">

                    <label>State</label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Select enter state here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php echo $this->Form->input('source_state', array('class' => 'form-control ', 'type' => 'text', 'maxlength' => "255", 'autocomplete' => "off", 'label' => false)); ?>

                </div>

                <div class="col-sm-3 col-md-3" id=''>
                    <label>City</label>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Select city here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php echo $this->Form->input('source_msa', array('class' => 'form-control ', 'type' => 'text', 'maxlength' => "255", 'autocomplete' => "off", 'label' => false)); ?>
                </div>
            </div>
            <div class="row">

                <div class="col-sm-12">
                    <?php echo $this->Form->input('is_source_manually', array('label' => "Do you want add source manully ?", 'div' => 'form-group div-status', 'class' => 'custom_check', 'type' => 'checkbox', 'id' => "is_source_manually")); ?>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <h5 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Press Release Detail</span></h5>
                </div>
            </div>
           
            <div class="row">
                <div class="col-sm-12">
                    <label>
                        Title
                    </label>
                    <a href="#" data-toggle="tooltip" title="Fill title here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('title', array('class' => 'form-control ', 'type' => 'text', 'label' => false, 'maxlength' => "255", 'onkeydown' => 'countword(this.value,"title_count");', 'onchange' => "countword(this.value,'prsummary'); checktotalwords();", 'autocomplete' => "off"));

                    if ($planDetail['Plan']['plan_type'] == 'single' && $planDetail['PlanCategory']['word_limit'] > 0)
                        echo "Total word <span wrd='0' id='title_count'></span>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label>
                        Subtitle
                    </label>
                    <a href="#" data-toggle="tooltip" title="Fill sub-title here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('summary', array('class' => 'form-control ', 'type' => 'textarea', 'label' => false, 'maxlength' => "350", 'onchange' => "countword(this.value,'prsummary'); checktotalwords();", 'onkeydown' => 'countword(this.value,"prsummary");', 'autocomplete' => "off"));

                    if ($planDetail['Plan']['plan_type'] == 'single' && $planDetail['PlanCategory']['word_limit'] > 0)
                        echo "Total word <span wrd='0' id='prsummary'></span>";



                    $bodycontent = '';
                    if (!empty($this->request->data['PressRelease']['body'])) {
                        $content = str_replace(array("<li>", "</li>"), array(" ", " "), $this->request->data['PressRelease']['body']);
                        $bodycontent = strip_tags($content);
                    }
                    ?>
                    <br />
                    <label>
                        Body
                    </label>
                    <a href="#" data-toggle="tooltip" title="Fill release details here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    <?php
                    echo $this->Form->input('body', array("class" => "form-control editor", "id" => 'PressReleaseBody', 'label' => false, 'maxLength' => strlen($bodycontent)));
                    ?>
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-sm-6">
                    <label>SEO Keywords</label>
                    <br />
                </div>
            </div>
            <?php
            $countk = (isset($this->data['PressSeo']) && count($this->data['PressSeo']) > 0) ? count($this->data['PressSeo']) : 1;
            if ($countk > 5)
                $countk = 5;

            for ($kloop = 0; $kloop < 5; $kloop++) {
                $label = $kloop + 1;
                if (isset($this->data['PressSeo'][$kloop]['id']) || $kloop == '0') {
                    $stylekey = "display:block;";
                } else {
                    $stylekey = "display:none;";
                }
            ?>

                <div id="<?php echo "skeyword-" . $kloop; ?>" style="<?php echo $stylekey; ?>" class="row">
                    <div class="col-sm-3">
                        <label>SEO Keywords <?php echo $label; ?></label>
                        <a href="#" data-toggle="tooltip" title="Fill seo keywords here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                        <?php
                        if (isset($this->data['PressSeo'][$kloop]['id']) && !empty($this->data['PressSeo'][$kloop]['id'])) {
                            echo $this->Form->input("PressSeo.$kloop.id", array("type" => 'hidden', "value" => $this->data['PressSeo'][$kloop]['id']));
                        }
                        echo $this->Form->input("PressSeo." . $kloop . ".keyword", array('class' => 'form-control ', 'label' => false, 'type' => 'text', 'id' => "PressSeoKeyword$kloop"));
                        ?>
                    </div>
                </div>
            <?php }  ?>

            <div class="row">
                <div class="col-sm-12 keyword-btns">
                    <a class='btn btn-info mb-4' id='keybtn' href="javascript:void(0)" fnum='<?php echo $countk; ?>' onclick="addmoref('skeyword-','keybtn','rkeybtn','PressSeoKeyword');">Add More Keywords</a>

                    <a class='btn btn-info btn-danger' id='rkeybtn' href="javascript:void(0)" style="<?php if ($countk <= 1) {
                                                                                                            echo 'display: none;';
                                                                                                        } ?>" onclick="removefield('skeyword-','keybtn','rkeybtn','PressSeoKeyword');">Remove</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="block-title" style="border-bottom: 2px solid  #00a65a;"><span style="background-color: #00a65a;">Select target Distribution </span></h4>
                </div>
                <div class="col-sm-12">
                    <h4 class="block-title" style="border-bottom: 2px solid #f39c12;"><span style="background-color: #f39c12;">Category</span></h4>
                </div>
            


                <div class="col-sm-12">
            <div class="category_section">
                <?php
                echo $this->Form->input('Category.Category', array(
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'options' => $categories,
                    'label' => false,
                    'class' => 'form-group category_checkbox'
                ));
                ?>
            </div>
            </div>
        </div> 
        <?php if(!empty($country_list)){ ?>
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="block-title" style="border-bottom: 2px solid  #f39c12;"><span style="background-color: #f39c12;">Region </span></h5>
                </div>
                <?php if ($planDetail['PlanCategory']['is_country_allowed']) {  ?>
                    <div class="col-sm-4">
                        <label>Country</label>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Please select country">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                        </a>
                        <?php echo $this->Form->input('country_id', array('class' => 'form-control ', 'onchange' => "get_state(this.value,'state_div','yes','State.State','Msa.Msa','yes','msadropdn')", 'options' => $country_list, 'empty' => '-Select-', 'label' => false, "required" => "required")); ?>
                    </div>
                <?php } ?>
                <?php if ($planDetail['PlanCategory']['is_state_allowed']) {  ?>
                    <div class="col-sm-4" id="state_div">
                        <label>State</label>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Please select state">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                        </a>
                        <?php echo $this->Form->input('State.State', array('class' => 'form-control ', 'type' => 'select', 'options' => $state_list, 'div' => 'form-group', 'class' => 'form-control state-select', 'multiple' => true,'onchange'=>"getMsaOnChange(this.value,'Msa.Msa','yes','msadropdn');",'id'=>"cstfld", 'label' => false)); ?>
                    </div>
                <?php } ?>

                <?php if ($planDetail['PlanCategory']['is_msa_allowed']) {  ?>
                    <div id="msadropdn" class="col-sm-4">
                        <label>City</label>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Select city here">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                        </a>
                        <?php echo $this->Form->input('Msa.Msa', array('class' => 'form-control ', 'label' => false, 'options' => $msa_list, 'multiple' => true, 'class' => "bootselect form-control", 'id' => "prmsaid", "required" => "required", 'onchange' => "msachanges();")); ?>
                    </div>
                <?php } ?>

            </div>
    <?php } ?>

    <div class="row">
        <div class="col-sm-12">
            <h5 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Other Information</span></h5>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-6">
            <label>Release Date *</label>
            <a href="#" data-toggle="tooltip" title="Please choose date and time"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
            <br />
            <?php
            $currentdate = (isset($this->data[$model]['release_date']) && !empty($this->data[$model]['release_date'])) ? $this->data[$model]['release_date'] : '';
            echo $this->Form->input('release_date', array('class' => 'release_date form-control ', 'type' => 'text', 'label' => false, "required" => "required", "value" => $currentdate)); ?>
        
            Select tomorrow's date. Allow one day.
            or select today's date for $75 for same day distribution"
        </div>
    </div>

    
    <div class="row">
        <div class="col-sm-12">
            <h5 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Image Detail</span></h5>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            Each file cannot be larger than 1MB. Thumbnail images will be created automatically.

        </div>
    </div>

    <?php
            $countk = (isset($this->data['PressImage']) && count($this->data['PressImage']) > 0) ? count($this->data['PressImage']) : 1;
            if ($countk > 5)
                $countk = 5;

            for ($i = 0; $i < 5; $i++) {
                $label = $i + 1;
                if (isset($this->data['PressImage'][$i]['id']) || $i == '0') {
                    $stylekey = "display:block;";
                } else {
                    $stylekey = "display:none;";
                }
                $saved = '';
                if (isset($this->data['PressImage'][$i]['id']) && !empty($this->data['PressImage'][$i]['id'])) {
                    $saved = 'saved';
                    echo $this->Form->input("PressImage.$i.id", array("type" => 'hidden', "value" => $this->data['PressImage'][$i]['id']));
                }
    ?>
        <div id="<?php echo "pimage-" . $i; ?>" style="<?php echo $stylekey; ?>" class="row">
            <div class="col-sm-4">
                <?php
                if (isset($this->data['PressImage'][$i]['id']) && !empty($this->data['PressImage'][$i]['id'])) { ?>
                    <?php
                    $imgurl = 'files/company/press_image/' . $this->data['PressImage'][$i]['image_path'] . '/' . $this->data['PressImage'][$i]['image_name'];

                    $removeFun = "removeUploadedImage('PressImageName$i'," . $this->data['PressImage'][$i]['id'] . ");";
                    echo "<div id='remove-PressImageName$i'>" . $this->Html->image(SITEFRONTURL . $imgurl, array('width'=>'100%','id' => "imgprev-PressImageName$i")) . "<a href='javascript:void(0)' class='btn btn-remove btn-danger' onclick=$removeFun>X</a></div>";

                    $imgname = (!empty($this->data['PressImage'][$i]['image_name'])) ? $this->data['PressImage'][$i]['image_name'] : "";
                    ?>
                <?php  }
                $fileType = ((!isset($this->data['PressImage'][$i]['id']) && $i == '0')) ? "file" : "hidden";
                $fieldId = "PressImageName$i";
                ?>
                <label>Image <?php echo $label; ?></label>
                <a href="#" data-toggle="tooltip" title="Choose image">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
                <?php echo $this->Form->input("PressImage.$i.image_name", array('label' => false, 'type' => $fileType, 'id' => $fieldId, "onchange" => "uploadImage(this,this.value,'$fieldId')"));

                echo $this->Form->input("PressImage.$i.image_path", array('label' => false, 'type' => 'hidden', 'id' => $fieldId . '-image_path'));

                ?>
            </div>

            <div class="<?php echo "col-sm-4"; ?> pimage-0" style="display:block;">
                <label>Caption <?php echo $label; ?></label>
                <a href="#" data-toggle="tooltip" title="Fill caption here">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
                <?php echo $this->Form->input("PressImage.$i.describe_image", array('label' => false, 'type' => 'text', 'id' => "PressImageDesc$i")); ?>
            </div>
            <div class="<?php echo "col-sm-4"; ?>">
                <label>Alt Text <?php echo $label; ?></label>
                <a href="#" data-toggle="tooltip" title="fill alt text here">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
                <?php echo $this->Form->input("PressImage.$i.image_text", array('type' => 'text', 'label' => false, 'id' => "PressImageAltName$i")); ?>
            </div>
        </div>
    <?php } ?>
    
    <div class="row">
        <div class="col-sm-12 primages-btns">
            <a class='btn btn-info mb-4' id='pimagebtn' href="javascript:void(0)" fnum='<?php echo $countk; ?>' onclick="addmoref('pimage-','pimagebtn','rimgbtn','PressImageName');">Add More Images</a>
            <a class='btn btn-info btn-danger' id='rimgbtn' href="javascript:void(0)" style="<?php if ($countk <= 1) {
                                                                                                    echo 'display: none;';
                                                                                                } ?>" onclick="removefield('pimage-','pimagebtn','rimgbtn','PressImageName','PressImageDesc','PressImageAltName','PressImageOldName');">Remove</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h5 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Youtube Links</span></h5>
        </div>
    </div>
    <?php
            $countk = (!empty($this->data['PressYoutube']) && count($this->data['PressYoutube']) > 0) ? count($this->data['PressYoutube']) : 1;
            if ($countk > 5)
                $countk = 5;

            for ($yloop = 0; $yloop < 5; $yloop++) {
                $label = $yloop + 1;
                if (!empty($this->data['PressYoutube'][$yloop]['id']) || $yloop == '0') {
                    $stylekey = "display:block;";
                } else {
                    $stylekey = "display:none;";
                }
                if (isset($this->data['PressYoutube'][$yloop]['id']) && !empty($this->data['PressYoutube'][$yloop]['id'])) {
                    echo $this->Form->input("PressYoutube.$yloop.id", array("type" => 'hidden', "value" => $this->data['PressYoutube'][$yloop]['id']));
                }
    ?>
        <div id="<?php echo "ylinks-" . $yloop; ?>" style="<?php echo $stylekey; ?>" class="row">
            <div class="col-sm-6">
                <label>Youtube URL <?php echo $label; ?></label>
                <a href="#" data-toggle="tooltip" title="fill youtube url here">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
                <?php echo $this->Form->input("PressYoutube.$yloop.url", array('label' => false, 'type' => 'text', 'id' => "PressYoutubeUrl$yloop")); ?>
            </div>
            <div class="col-sm-6">
                <label>Describe video <?php echo $label; ?></label>
                <a href="#" data-toggle="tooltip" title="fill youtube url here">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
                <?php echo $this->Form->input("PressYoutube.$yloop.description", array('label' => false, 'type' => 'text', 'id' => "PressYoutubeDesc$yloop")); ?>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-sm-12 ylink-btns">
            <a class='btn btn-info mb-4' id='ylinkbtn' href="javascript:void(0)" fnum='<?php echo $countk; ?>' onclick="addmoref('ylinks-','ylinkbtn','rlinkbtn','PressYoutubeUrl');">Add More Youtube Links</a>
            <a class='btn btn-info btn-danger' id='rlinkbtn' href="javascript:void(0)" style="<?php if ($countk <= 1) {
                                                                                                    echo 'display: none;';
                                                                                                } ?>" onclick="removefield('ylinks-','ylinkbtn','rlinkbtn','PressYoutubeUrl','PressYoutubeDesc');">Remove</a>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">
            <h5 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Poadcast</span></h5>
        </div>
    </div>
    <?php
            $countk = (isset($this->data['PressPoadcast']) && count($this->data['PressPoadcast']) > 0) ? count($this->data['PressPoadcast']) : 1;
            if ($countk > 5)
                $countk = 5;

            for ($yloop = 0; $yloop < 5; $yloop++) {
                $label = $yloop + 1;
                if (!empty($this->data['PressPoadcast'][$yloop]) || $yloop == '0') {
                    $stylekey = "";
                } else {
                    $stylekey = "display:none;";
                }
                if (isset($this->data['PressPoadcast'][$yloop]['id']) && !empty($this->data['PressYoutube'][$yloop]['id'])) {
                    echo $this->Form->input("PressPoadcast.$yloop.id", array("type" => 'hidden', "value" => $this->data['PressPoadcast'][$yloop]['id']));
                }
    ?>
        <div id="<?php echo "podlink-" . $yloop; ?>" style="<?php echo $stylekey; ?>" class="row">
            <div class="col-sm-6">
                <label>Podcast Embed code<?php echo $label; ?></label>
                <a href="#" data-toggle="tooltip" title="fill podcast embed code here">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
                <?php echo $this->Form->input("PressPoadcast.$yloop.url", array('label' => false, 'type' => 'textarea', 'id' => "PressPoadcastUrl$yloop")); ?>
            </div>
            <div class="col-sm-6">
                <label>Describe poadcast<?php echo $label; ?></label>
                <a href="#" data-toggle="tooltip" title="fill describe poadcast here">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </a>
                <?php echo $this->Form->input("PressPoadcast.$yloop.description", array('label' => false, 'type' => 'text', 'id' => "PressPoadcastDesc$yloop")); ?>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-sm-12 ylink-btns">
            <a class='btn btn-info mb-4' id='podlinkbtn' href="javascript:void(0)" fnum='<?php echo $countk; ?>' onclick="addmoref('podlink-','podlinkbtn','rpodlinkbtn','PressPoadcastUrl');">Add More Podcasts</a>
            <a class='btn btn-info btn-danger' id='rpodlinkbtn' href="javascript:void(0)" style="<?php if ($countk <= 1) {
                                                                                                        echo 'display: none;';
                                                                                                    } ?>" onclick="removefield('podlink-','podlinkbtn','rpodlinkbtn','PressPoadcastUrl','PressPoadcastDesc');">Remove</a>
        </div>
    </div>

    <?php if ($planDetail['PlanCategory']['is_translated']) { ?>
        <div class="row">
            <div class="col-sm-12">
                <?php
                echo "<div class='form-group'> ";
                $options = array('1' => 'Yes', '0' => 'No');
                $attributes = array(
                    'legend' => "Add translate page",
                    'class' => 'pr_trans',
                    'onchange' => "praddtocart()",
                );
                echo $this->Form->radio('translated_page', $options, $attributes);
                echo "</div>";
                ?>
            </div>
        </div>
    <?php  } ?>
    <div class="row">
        <div class="col-sm-12">
            <h5 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Additional feature</span></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label>Additional Features</label>
            <a href="#" data-toggle="tooltip" title="Choose additional features">
                <i class="fa fa-question-circle" aria-hidden="true"></i>
            </a>

            <?php
            echo $this->Form->input('Distribution.Distribution', array('class' => 'form-control ', 'type' => 'select', 'options' => $distribution_list, 'div' => 'form-group additional-features', 'class' => 'form-control', 'multiple' => 'checkbox', 'label' => false, 'id' => 'additional-feature'));
            ?>
        </div>

        <?php
            $email_list_divClass = "display: none;";
            $email_list_error = "display: block;";
            if (isset($this->request->data['Distribution']) && !empty($this->request->data['Distribution'])) {
                foreach ($this->request->data['Distribution'] as $key => $distribution) {
                    if ($distribution['id'] == 8)
                        $email_list_divClass = "display: block;";
                    $email_list_error = "display: none;";
                }
            }
        ?>
        <div class="col-sm-12" id="email_list_div" style="<?php echo $email_list_divClass; ?>">
            <?php echo $this->Form->input('PressRelease.list_id', array('class' => 'form-control ', 'type' => 'select', 'options' => $email_list, 'div' => 'form-group', 'class' => 'form-control email-select', 'multiple' => false, 'onchange' => 'selectemaillistchange(this.value);', 'empty' => "Select email list", 'label' => "Please select email list *")); ?>
            <div style="<?php echo $email_list_error; ?>" class="error-message emaillist-errormsg">* Please select media email list.</div>
            <p>If your Email list is not listed here click here to <?php echo $this->Html->link('Add new email list', array('controller' => 'sendy', 'action' => 'add')); ?></p>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <h5 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Agreement</span></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 termcontentbox">
            <?php
            include 'term_content.ctp';
            ?>
        </div>
    </div>
    <div class="error-message toserrormsg">* Please read agreement carefully to proceed to post your PR.</div>
    <?php
            if (!empty($company_list)) {
                if (!empty($selectedplan)) { ?>
                <div class="ew-cart-btns-block full row ">
                    <div class="col-sm-9 mt-4"> <strong class="text-danger">Preview press release, and make sure it is accurate before you summit. Press release can't be changed after it is submitted.</strong>
                    </div>
                    <div class="button_pr pull-right col-sm-3">
                        <div class="buy_now_section">
                            <a href="javascript:void(0)" onclick="submitform('preview');" class="btn btn-primary">Preview Press Release</a>
                        </div>
                    </div>
                    <!-- <div class="button_pr col-sm-3">
                        <a href="javascript:void(0)" onclick="submitform('indraft');" class="btn btn-primary">Save PR in draft</a>
                        <p>If you do not want to submit this PR or want to edit letter then save in draft so you will not lost your filled PR content.</p>
                    </div> -->
                </div>
    <?php }
            } ?>
<?php } ?>
<?php $this->Form->end(); ?>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var $featureCheckboxes = $('.additional-features input[type="checkbox"]');
        $featureCheckboxes.change(function() {
            var disid = $(this).val();
            if ($(this).prop("checked") == true) {
                if (disid == 8) {
                    $("#PressReleaseListId option:first").attr('selected', 'selected');
                    $("#PressReleaseListId").attr("required", "required");
                    $("#email_list_div").show();
                }

            } else {
                if (disid == 8) {
                    $("#email_list_div").hide();
                }
            }
        });
    });


    function selectemaillistchange(listId) {
        if (listId != "") {
            $(".emaillist-errormsg").hide();
        } else {
            $(".emaillist-errormsg").show();
        }
    }


    var editor = CKEDITOR.replace('PressReleaseBody', {
        showWordCount: true,
        filebrowserUploadUrl: SITEFRONTURL + "ajax/mediafileupload?typ=1"
    });

    function redirect_selectedplan(selected) {
        let lang=$("#languageId").val();
        var pressReleaseId = $("#PressReleasePlanId").val();  
        window.location.replace(SITEURL + "PressReleases/add/"+lang+'/'+selected);
    }

    function redirectByLanguage(lang) {
        let selectedPlanId=$("#PressReleasePlanId").val(); 
        if(selectedPlanId !==undefined && selectedPlanId != ''){
            let selectedPrId=$("#PressReleasePlanId").val();
            let pressReleaseId = $("#PressReleaseId").val();
            pressReleaseId=(pressReleaseId!=undefined && pressReleaseId!='')?pressReleaseId:'';
           //let coconfirmAction= confirmAction(this.href,'Realy, Do you want to change the language? Your form data will remove.','Change Language','question','true');
            window.location.replace(SITEURL + "PressReleases/add/"+lang+'/'+selectedPlanId+'/'+pressReleaseId); 
        }
    }

    
    $('#PressReleaseTos').click(function() {
        if ($("#PressReleaseTos").prop("checked") == true) {
            $(".button_pr a.btn").removeClass('disabled');
            $(".button_pr a.btn").removeAttr('disabled');
            $(".toserrormsg").text("* Please read agreement carefully to proceed to post your PR.").hide();
        } else {
            $(".button_pr a.btn").addClass('disabled');
            $(".button_pr a.btn").attr('disabled');
            $(".toserrormsg").show();
        }

    });
    $('.term-content').scroll(function() {
        if ($("#PressReleaseTos").prop("checked") == true) {
            $(".button_pr a.btn").removeClass('disabled');
            $(".button_pr a.btn").removeAttr('disabled');
            $(".toserrormsg").text("* Please read agreement carefully to proceed to post your PR.").hide();
        } else {
            if ($(this).scrollTop() == $(this)[0].scrollHeight - $(this).height()) {
                $(".tosdiv").removeClass("hide");
                $(".toserrormsg").text("* Please click on the I Agree checkbox above, then proceed to post your PR.").show();
            }
            $(".button_pr a.btn").addClass('disabled');
            $(".button_pr a.btn").attr('disabled');
        }
    });

    $(document).ready(function() {
        if ($("#PressReleaseTos").prop("checked") == true) {
            $(".tosdiv").removeClass("hide");
            $(".button_pr a.btn").removeClass('disabled');
            $(".button_pr a.btn").removeAttr('disabled');
            $(".toserrormsg").text("* Please read agreement carefully to proceed to post your PR.").hide();
        } else {
            $(".button_pr a.btn").addClass('disabled');
            $(".button_pr a.btn").attr('disabled', 'disabled');
            $(".toserrormsg").show();
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('.company-select,.bootselect,.state-select,.distribute-select').select2();
    });

    function load_company_detail(value) {
        if (value != 0 && value != '') {
            $("#AjaxLoading").show();
            $.ajax({
                type: 'POST',
                url: SITEURL + 'ajax/load_company_detail',
                data: {
                    company_id: value
                },
                async: false,
                success: function(data) {
                    $("#AjaxLoading").hide();
                    var obj = JSON.parse(data);
                    $("#userid").val(obj.userid);
                    $("#contact_name").val(obj.contact_name);
                    $("#email").val(obj.email);
                    $("#phone").val(obj.phone_number);
                    $("#job_title").val(obj.job_title);
                }
            });
        }
    }


    function countword(values, responseId) {
        var words = $.trim(values).length ? values.match(/\S+/g).length : 0;
        $('#' + responseId).text(words);
        // $('#'+responseId).attr('wrd',words); 
        return words;
    }

    function sumofwords() {
        var title_count = countword($("#PressReleaseTitle").val(), "title_count");
        var prsummary = countword($("#PressReleaseSummary").val(), "prsummary");
        var prbody = countword($("#PressReleaseBodyHidden").val(), "prbody");
        var totalword = eval(title_count) + eval(prsummary) + eval(prbody);
        return totalword;
    }

    function checktotalwords() {
        var prWordLimit = $("#pr_word_limit").val();
        var totalword = sumofwords();
        var checkCartItem = $("#cartplanlist li").length;
        /*if(totalword<=prWordLimit&&checkCartItem>0&&$("#cartplanlist li").hasClass("words_charges")){ 
            praddtocart();
        }else{
            if(totalword>prWordLimit){ 
                praddtocart();
            }else{
                getprcart();
            }
        }*/
    }


    function addmoref(fieldId, keybtn, rbtnId, inputFieldId) {
        var fnum = $("#" + keybtn).attr('fnum');
        if (fnum > 0 && fnum < 5) {
            var next = eval(fnum) + 1;
            var previous = eval(fnum) - 1;
            var previousField = $("#" + inputFieldId + previous);
            var genratePreviousfieldErrId = "err-" + inputFieldId + previous;
            var previousVal = previousField.val();
            $("#" + genratePreviousfieldErrId).remove();
            if (previousVal != '') {
                $("#" + keybtn).attr('fnum', next);
                $("#" + fieldId + fnum).show();
                $("#" + rbtnId).show();
                if (inputFieldId == "PressImageName") {
                    $("#" + inputFieldId + fnum).attr('type', "file");
                }
            } else {
                previousField.after("<span id='" + genratePreviousfieldErrId + "' class='text-danger '>Please enter value.</p>");
            }

        }
    }

    function removefield(fieldId, atagbtn, rbtnId, inputFieldId1 = '', inputFieldId2 = '', inputFieldId3 = '', inputFieldId4 = '') {
        var fnum = $("#" + atagbtn).attr('fnum');
        if (fnum > 0 && fnum <= 5) {
            var prev = eval(fnum) - 1;
            if (prev == 1) {
                $("#" + rbtnId).hide();
            }
            if (inputFieldId1 != '') {
                $("#" + inputFieldId1 + prev).val('');
            }
            if (inputFieldId2 != '') {
                $("#" + inputFieldId2 + prev).val('');
            }
            if (inputFieldId3 != '') {
                $("#" + inputFieldId3 + prev).val('');
            }
            if (inputFieldId4 != '') {
                $("#" + inputFieldId4 + prev).val('');
            }
            $("#" + atagbtn).attr('fnum', prev);
            $("#" + fieldId + prev).hide();
            if (inputFieldId1 == "PressImageName") {
                $("#" + fieldId + prev).attr('type', "file");
                var inputId = inputFieldId1 + prev;
                var PrImgId = $('#PressImage' + prev + 'Id').val();
                if (PrImgId == undefined) {
                    PrImgId = "";
                }
                removeUploadedImage(inputId, PrImgId)
            }
        }
    }


    function submitform(submittype) {
        $("#submittype").val(submittype);
        $("#PressReleaseform").submit();
    }


    $(".release_date").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        onSelect: function(dateText, inst) {
            var date_val = $(this).val();
            var c_date = '<?php echo date('d-m-Y'); ?>';
            if (dateText == c_date) {
                $('#additional-feature1').trigger("click");
            } else {
                if ($('#additional-feature1').prop("checked") == true) {
                    $('#additional-feature1').trigger("click");
                }
            }
        }
    });


    function readURL(input, inputId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
                $("#" + inputId).hide();
                var removeFun = "removeUploadedImage('" + inputId + "');";
                $("#" + inputId).after('<div id="remove-' + inputId + '"><img id="imgprev-' + inputId + '" src=' + e.target.result + ' /><a class="btn btn-remove btn-danger" href="javascript:void(0)" onclick="' + removeFun + '">X</a></div>');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeUploadedImage(inputId, prImgId = "") {

        var oldimage = $("#imgprev-" + inputId).attr("src");
        $.ajax({
            type: 'POST',
            url: '<?php echo SITEFRONTURL; ?>ajax/removePrImage',
            data: {
                oldimage: oldimage,
                prImgId: prImgId
            },
            success: function(data) {
                if ($("#PressReleaseTos").prop("checked") == true) {
                    $(".button_pr a.btn").removeClass('disabled');
                    $(".button_pr a.btn").removeAttr('disabled');
                }
                $("#imagespiner").remove();
                var obj = JSON.parse(data);
                if (obj.status == 'success') {
                    $("#remove-" + inputId).remove();
                    $("#" + inputId).attr("type", "file").val("");
                    $("#" + inputId + '-image_path').val("");
                    if (prImgId != '') {
                        $("#" + inputId + 'Id').val("");
                    }
                    messgae_box("PR image removed successfully.", "Success", "success");
                    $("#" + inputId).attr("type", "file").val("").show();
                } else {
                    $("#" + inputId).after('<p class="error">' + obj.message + '</p>');
                    messgae_box(obj.message, "Failed", "failed");

                }
            }
        });

    }


    function uploadImage(image, imageTempPath, inputId) {
        $("#" + inputId).after('<div id="imagespiner"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> Uploading...</div>');
        readURL(image, inputId);
        $(".button_pr a.btn").addClass('disabled');
        $(".button_pr a.btn").attr('disabled', 'disabled');
        var filePath = $('#' + inputId).val();
        var file = image.files[0];
        var formData = new FormData();
        formData.append('formData', file);
        $.ajax({
            type: 'POST',
            url: '<?php echo SITEFRONTURL; ?>ajax/pruploadimage',
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
                if ($("#PressReleaseTos").prop("checked") == true) {
                    $(".button_pr a.btn").removeClass('disabled');
                    $(".button_pr a.btn").removeAttr('disabled');
                }
                $("#imagespiner").remove();
                var obj = JSON.parse(data);
                if (obj.status == 'success') {

                    messgae_box("PR image successfully uploaded.", "Success", "success");
                    var img_url = obj.img_url;
                    $("#imgprev-" + inputId).attr("src", img_url);
                    $("#" + inputId).attr("type", "hidden").val(obj.image_name);
                    $("#" + inputId + '-image_path').val(obj.image_path);
                } else {
                    $("#" + inputId).after('<p class="error">' + obj.message + '</p>');
                    messgae_box(obj.message, "Failed", "failed");

                }
            }
        });
    }
 
    



    $('#PressReleaseReleaseDate').keyup(function() {
        var date_val = $(this).val();
        var c_date = '<?php echo date('d-m-Y'); ?>';
        if (date_val == c_date) {
            $('#additional-feature1').trigger("click");
        } else {
            if ($('#additional-feature1').prop("checked") == true) {
                $('#additional-feature1').trigger("click");
            }
        }
    });
    $('input#additional-feature1[type="checkbox"]').click(function() {
        if ($(this).prop("checked") == true) {
            var c_date = '<?php echo date('d-m-Y'); ?>';
            $('#PressReleaseReleaseDate').val(c_date);

        } else if ($(this).prop("checked") == false) {
            var date_val = $('#PressReleaseReleaseDate').val();
            var c_date = '<?php echo date('d-m-Y'); ?>';
            if (date_val == c_date) {
                $('#PressReleaseReleaseDate').val('');
            }
        }
    });

    $('#is_source_manually').click(function(event) {
        if ($(this).prop("checked") == true) {
            $('#manullalsourcebox').addClass('show').removeClass("hide");
            $('#sourcebox').addClass('hide').removeClass("show");
            $("#PressReleaseSourceCountry").attr('required', 'required');
            $("#PressReleaseSourceState").attr('required', 'required');
            $("#PressReleaseSourceMsa").attr('required', 'required');

        } else if ($(this).prop("checked") == false) {
            $('#manullalsourcebox').addClass('hide').removeClass("show");
            $('#sourcebox').addClass('show').removeClass("hide");
            $("#PressReleaseSourceCountry").removeAttr('required');
            $("#PressReleaseSourceState").removeAttr('required');
            $("#PressReleaseSourceMsa").removeAttr('required');
        }
        /*
        alert('checked = ' + event.target.checked);
        alert('value = ' + event.target.value);*/
    });
</script>

<style type="text/css">
    a.disabled {
        pointer-events: none;
        cursor: default;
    }

    .hide {
        display: none;
    }

    .block-title {
        color: #ffffff;
    }

    .block-title span {
        line-height: 17px;
        display: inline-block;
        padding: 7px 12px 4px;
        font-family: 'Roboto', sans-serif;
        font-weight: 600;
        font-size: 14px;
    }

    .category_section {
        height: 300px;
        overflow-y: scroll;
        margin-bottom: 50px;
    }

    .legend {
        display: block;
        width: 100%;
        padding: 0;
        line-height: inherit;
        color: #333;
        border: 0;
        border-bottom: 1px solid grey;
    }

    .category_section legend {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .category_checkbox{
        margin-bottom: 3px;
    }
</style>