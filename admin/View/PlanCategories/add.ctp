<?php echo $this->element('submenu'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <!-- /.card-heading -->
            <div class="card-body">
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create($model, array('novalidate' => 'novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                    <div class="row">
                        <div class="col-sm-9">
                            <?php

                            echo $this->Form->input('parent_id', array('options' => $data_array, 'empty' => '-Select-', 'class' => 'select2 form-control'));
                            echo $this->Form->input('name');
                            echo $this->Form->input('word_limit');
                            echo $this->Form->input('description', array("class" => "country_dd form-control", "id" => 'editor1', 'label' => 'Description'));

                            echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Plan Options</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body" style="">
                                    <?php

                                    echo $this->Form->input('is_featured_pr', array('div' => 'form-group div-status', 'class' => 'custom_check', 'type' => 'checkbox', 'label' => 'Is Featured'));
                                    echo $this->Form->input('is_translated', array('div' => 'form-group div-status', 'class' => 'custom_check', 'type' => 'checkbox', 'label' => 'Is translattion included'));

                                    echo $this->Form->input('feed_publish_type', array('type' => 'radio', 'options' => ["gwn" => "GWN", "pns" => "PNS"], 'default' => 'gwn', 'legend' => "Feed Publish", "class" => "radio-inline", 'div' => "form-group ",));

                                    echo $this->Form->input('status', array('div' => 'form-group div-status', 'class' => 'custom_check', 'type' => 'checkbox'));
                                    ?>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Press Release Target Options</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body" style="">
                                    <?php

                                    echo $this->Form->input('is_country_allowed', array('div' => 'form-group div-status', 'class' => 'custom_check', 'type' => 'checkbox', 'label' => 'Is country Allowed'));

                                    // echo '<a id="cntry-all" style="font-size: 12px;" href="javascript:void(0)" class="hide text-dark float-right text-right" onclick="selectAll('."'pccountryid'".')">Select All</a>';

                                    echo $this->Form->input('is_allowed_all_country', array('div' => 'form-group allcountrycheckbox div-status ml-2 hide ', 'class' => 'custom_check', 'type' => 'checkbox', 'label' => 'Do want to select all country'));

                                    echo $this->Form->input('Country.Country', array('class' => 'form-control select2', 'onchange' => "get_multi_states('pccountryid','pcstateid')", 'multiple' => true, 'options' => $country_list, 'label' => false, 'data-select-all' => 'no', 'id' => "pccountryid", "div" => "countrydiv hide"));

                                    echo $this->Form->input('is_state_allowed', array('div' => 'form-group div-status ', 'class' => 'custom_check', 'type' => 'checkbox', 'label' => 'Is State Allowed'));

                                     echo '<a id="state-all" style="font-size: 12px;" href="javascript:void(0)" class="hide text-dark float-right text-right" onclick="selectAll('."'pcstateid'".')">Select All</a>';
                                    echo $this->Form->input('is_allowed_all_state', array('div' =>"form-group div-status allstatecheckbox ml-3 hide", 'class' => 'custom_check', 'type' => 'checkbox', 'label' => 'Do want to select all state'));

                                    echo $this->Form->input('State.State', array('class' => 'form-control select2', 'label' => false, 'type' => 'select', 'options' => $state_list, 'multiple' => true, 'div' =>"form-group statediv hide", 'data-select-all' => 'no', 'id' => "pcstateid", 'onchange' => "get_multi_msas('pcstateid','pcmsaid')", 'multiple' => true));

                                    echo $this->Form->input('state_selected_ids', array('label' => false, 'type' => 'hidden', 'div' =>"form-group statediv hide", 'id' => "filterstatevalues"));

                                    echo $this->Form->input('state_ids', array('class' => 'form-control ', 'label' => false, 'type' => 'text', 'div' =>"form-group stateautodiv hide", 'data-select-all' => 'no', 'id' => "searchstate"));

                                    echo $this->Form->input('is_msa_allowed', array('div' => 'form-group div-status', 'class' => 'custom_check', 'type' => 'checkbox', 'label' => 'Is MSA Allowed'));


                                    echo $this->Form->input('is_allowed_all_msa', array('div' => 'form-group div-status ml-3 hide allmsacheckbox', 'class' => 'custom_check', 'type' => 'checkbox', 'label' => 'Do want to select all MSA'));


                                    echo '<a id="msa-all" style="font-size: 12px;" href="javascript:void(0)" class="hide text-dark float-right text-right" onclick="selectAll(' . "'pcmsaid'" . ')">Select All dropdown msa</a>';
                                    echo $this->Form->input('Msa.Msa', array('label' => "City", 'class' => 'select2 form-control ', 'label' => false, 'options' => $mas_list, 'multiple' => true, 'id' => "pcmsaid", "div" => "msadiv hide", 'empty' => "Select City"));
                                    echo $this->Form->input('msa_selected_ids', array('label' => false, 'type' => 'hidden', 'div' =>"form-group hide", 'id' => "filtermsavalues"));

                                    echo $this->Form->input('msa_ids', array('class' =>"form-control msaautodiv hide", 'label' => false, 'type' => 'text', 'div' => 'form-group', 'id' => "searchmsa"));


                                    ?>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Banner Image</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                    </div>

                                </div>
                                <div class="card-body" style="">
                                    <?php
                                    $fieldId = "bannerimg";
                                    $fileType = (!isset($this->data[$model]['banner_image'])) ? "file" : "hidden";
                                    echo $this->Form->input("banner_image", array('label' => false, 'type' => $fileType, 'id' => $fieldId, "onchange" => "uploadImage(this,this.value,'$fieldId')"));
                                    echo $this->Form->input("banner_path", array('label' => false, 'type' => 'hidden', 'id' => $fieldId . '-image_path'));
                                    ?>
                                    <span class="">Note: Please upload width should be minimum 1500px and height should
                                        be maximum 400px;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var editor1 = CKEDITOR.replace('editor1', {
        showWordCount: true,
        filebrowserUploadUrl: SITEFRONTURL + "ajax/mediafileupload?typ=1"
    });

    function readURL(input, inputId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
                $("#" + inputId).hide();
                var removeFun = "removeUploadedImage('" + inputId + "');";
                $("#" + inputId).after('<div id="remove-' + inputId + '"><img id="imgprev-' + inputId + '" src=' + e
                    .target.result + ' /><a class="btn btn-remove btn-danger" href="javascript:void(0)" onclick="' +
                    removeFun + '">X</a></div>');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeUploadedImage(inputId, prImgId = "", model = "") {

        var oldimage = $("#imgprev-" + inputId).attr("src");
        $.ajax({
            type: 'POST',
            url: '<?php echo SITEFRONTURL; ?>ajax/removeBannerImage',
            data: {
                oldimage: oldimage,
                prImgId: prImgId,
                model: model
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
                    messgae_card("Banner image removed successfully.", "Success", "success");
                    $("#" + inputId).attr("type", "file").val("").show();
                } else {
                    $("#" + inputId).after('<p class="error">' + obj.message + '</p>');
                    messgae_card(obj.message, "Failed", "failed");

                }
            }
        });

    }


    function uploadImage(image, imageTempPath, inputId) {
        $("#" + inputId).after(
            '<div id="imagespiner"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> Uploading...</div>');
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

                    messgae_card("Banner image successfully uploaded.", "Success", "success");
                    var img_url = obj.img_url;
                    $("#imgprev-" + inputId).attr("src", img_url);
                    $("#" + inputId).attr("type", "hidden").val(obj.image_name);
                    $("#" + inputId + '-image_path').val(obj.image_path);
                } else {
                    $("#" + inputId).after('<p class="error">' + obj.message + '</p>');
                    messgae_card(obj.message, "Failed", "failed");

                }
            }
        });
    }

    $('#PlanCategoryAddForm').validate({
        rules: {
            'data[PlanCategory][name]': {

                required: true,
            },
            'data[PlanCategory][parent_id]': {


                required: false,
            },
            'data[PlanCategory][word_limit]': {
                required: false,
                number: true,
                max: 100000,
                min: 0,
            },
            'data[Country][Country][]': {
                required: function() {
                    return ($('#PlanCategoryIsCountryAllowed option:checked').val() != '');
                },
            },
            'data[State][State][]': {
                required: function() {
                    return ($('#PlanCategoryIsStateAllowed option:checked').val() != '');
                },
            },
            'data[Msa][Msa][]': {
                required: function() {
                    return ($('#PlanCategoryIsMsaAllowed option:checked').is(':checked'));
                },
            },
            'data[PlanCategory][is_country_allowed]': {
                required: function() {
                    return (($('#PlanCategoryIsStateAllowed').is(':checked')) || ($('#PlanCategoryIsMsaAllowed option:checked').is(':checked')));
                },
            },
            'data[PlanCategory][is_state_allowed]': {
                required: function() {
                    return ($("#PlanCategoryIsMsaAllowed").is(':checked'));
                },
            },
        },
        messages: {
            'licensee_search[searchArea][radius]': {
                required: "This value should not be blank.",
            },
        },
    });

    $('#PlanCategoryIsCountryAllowed').click(function() {
        if ($(this).is(':checked')) {
            // $("#cntry-all").removeClass('hide');
            $(".allcountrycheckbox").removeClass('hide');
            $(".countrydiv").removeClass('hide');
        } else {
            $(".allcountrycheckbox").addClass('hide');
            $(".countrydiv").addClass('hide');
            // $("#cntry-all").removeClass('hide');
        }

        $("#PlanCategoryIsStateAllowed").prop('checked', false);
        $("#PlanCategoryIsAllowedAllState").prop('checked', false);
        $(".allstatecheckbox").addClass('hide');
        $(".stateautodiv").addClass('hide');

        $("#PlanCategoryIsMsaAllowed").prop('checked', false);
        $("#PlanCategoryIsAllowedAllMsa").prop('checked', false);
        $(".allmsacheckbox").addClass('hide');
        $(".msadiv").addClass('hide');
        $(".msaautodiv").addClass('hide');
        $("#msa-all").addClass('hide');
    });


    $('#PlanCategoryIsAllowedAllCountry').click(function() {
        if ($(this).is(':checked')) {
            // selectAll('pccountryid');
            $(".countrydiv").addClass('hide');
        } else {
            $(".countrydiv").removeClass('hide');
        }
        $("#PlanCategoryIsStateAllowed").prop('checked', false);
        $("#PlanCategoryIsAllowedAllState").prop('checked', false);
        $(".allstatecheckbox").addClass('hide');
        $(".stateautodiv").addClass('hide');

        $("#PlanCategoryIsMsaAllowed").prop('checked', false);
        $("#PlanCategoryIsAllowedAllMsa").prop('checked', false);
        $(".allmsacheckbox").addClass('hide');
        $(".msadiv").addClass('hide');
        $(".msaautodiv").addClass('hide');
        $("#msa-all").addClass('hide');
    });


    $('#PlanCategoryIsStateAllowed').click(function() {
        if ($(this).is(':checked') && $('#PlanCategoryIsCountryAllowed').is(':checked')) {
            //  $(".statediv").removeClass('hide');
            $(".allstatecheckbox").removeClass('hide');

            if ($('#PlanCategoryIsAllowedAllCountry').is(':checked')) {
                $(".statediv").addClass("hide");
                $(".stateautodiv").removeClass("hide");
            } else {
                $(".statediv").removeClass("hide");
                $(".stateautodiv").addClass("hide");
            }
        } else {
            if ($(this).is(':checked')) {
                messgae_box("Please select country first.", "Error!", "warning");
            }
            $(".allstatecheckbox").addClass('hide');
            $(".stateautodiv").addClass("hide");
            $(".statediv").addClass("hide");
        }

        $(".allmsacheckbox").addClass('hide');
        $(".msadiv").addClass('hide');
        $(".msaautodiv").addClass('hide');
        $("#PlanCategoryIsMsaAllowed").prop('checked', false);
        $("#PlanCategoryIsAllowedAllMsa").prop('checked', false);
        $("#msa-all").addClass('hide');
    });


    $('#PlanCategoryIsAllowedAllState').click(function() {
        if ($(this).is(':checked')) {
            $(".stateautodiv").addClass("hide");
            $(".statediv").addClass("hide");
        } else {

            if ($('#PlanCategoryIsAllowedAllCountry').is(':checked')) {
                $(".statediv").addClass("hide");
                $(".stateautodiv").removeClass("hide");
            } else {
                $(".statediv").removeClass("hide");
                $(".stateautodiv").addClass("hide");
            }
        }
        $(".allmsacheckbox").addClass('hide');
        $(".msaautodiv").addClass('hide');
        $(".msadiv").addClass('hide');
        $("#PlanCategoryIsMsaAllowed").prop('checked', false);
        $("#PlanCategoryIsAllowedAllMsa").prop('checked', false);
        $("#msa-all").addClass('hide');
    });


    $('#PlanCategoryIsMsaAllowed').click(function() {
        if ($(this).is(':checked') && $('#PlanCategoryIsCountryAllowed').is(':checked') && $('#PlanCategoryIsStateAllowed').is(':checked')) {
            $(".allmsacheckbox").removeClass('hide');

            if ($('#PlanCategoryIsAllowedAllState').is(':checked')) {
                $(".msadiv").addClass("hide");
                $(".msaautodiv").removeClass("hide");
            } else {
                $("#msa-all").removeClass('hide');
                $(".msadiv").removeClass("hide");
                $(".msaautodiv").addClass("hide");
            }

        } else {
            if ($(this).is(':checked')) {
                messgae_box("Please select State and country first.", "Error!", "warning");
            }
            $(".allmsacheckbox").addClass('hide');
            $(".msaautodiv").addClass("hide");
        }
    });


    $('#PlanCategoryIsAllowedAllMsa').click(function() {
        $("#msa-all").addClass('hide');
        if ($(this).is(':checked')) {
            // selectAll('pccountryid');
            $(".msadiv").addClass('hide');

            $(".msaautodiv").addClass("hide");
        } else {

            if ($('#PlanCategoryIsAllowedAllState').is(':checked')) {
                $(".msadiv").addClass("hide");
                $(".msaautodiv").removeClass("hide");
            } else {
                $(".msadiv").removeClass("hide");
                $("#msa-all").removeClass('hide');
                $(".msaautodiv").addClass("hide");
            }
        }
    });




    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }

    $("#searchstate").autocomplete({
        minLength: 3,
        source: function(request, response) {
            $.getJSON(SITEURL + 'ajax/filterrecords', {
                term: extractLast(request.term),
                model: "State"
            }, response);
        },
        search: function() {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 4) {
                return false;
            }
        },
        focus: function() {
            // prevent value inserted on focus
            return false;
        },
        select: function(event, ui) {
            var labels = split(this.value);
            labels.pop();
            labels.push(ui.item.label);
            labels.push("");
            this.value = labels.join(", ");
            var terms = split($("#filterstatevalues").val());
            terms.pop();
            terms.push(ui.item.value);
            terms.push("");
            console.log(terms);
            get_multi_auto_msas(terms, 'pcmsaid')
            $("#filterstatevalues").val(terms.join(", "));
            return false;
        }
    });


    $("#searchmsa").autocomplete({
        minLength: 3,
        source: function(request, response) {
            $.getJSON(SITEURL + 'ajax/filterrecords', {
                term: extractLast(request.term),
                model: "Msa"
            }, response);
        },
        search: function() {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 4) {
                return false;
            }
        },
        focus: function() {
            // prevent value inserted on focus
            return false;
        },
        select: function(event, ui) {
            var labels = split(this.value);
            labels.pop();
            labels.push(ui.item.label);
            labels.push("");
            this.value = labels.join(", ");
            var terms = split($("#filtermsavalues").val());
            terms.pop();
            terms.push(ui.item.value);
            terms.push("");
            $("#filtermsavalues").val(terms.join(", "));
            return false;
        }
    });
</script>