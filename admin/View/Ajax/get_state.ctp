 
<label>State</label>
<?php echo $this->Form->input("$model.state_id", array('class' => 'form-control state-select', 'options' => $state_list,  'empty' => '-Select state-', 'label' => false, "required" => "required","onchange"=>"getAjxSourceCities(this.value,'msa_id_box','$model')")); ?>

<script type="text/javascript">
	 $(function () {
        $(".state-select").select2({
            tags: true
        }); 

    }); 
<?php if(!empty($isCity) && $isCity !='no'){ ?>
    function getAjxSourceCities(stateId, responseFieldId = 'media_msa_div',model) {
        $("#AjaxLoading").show();
        var planId = $("#PressReleasePlanId").val();
        $.ajax({
            type: 'POST',
            url: SITEURL + 'ajax/get_source_cities',
            data: {
                stateId: stateId,
                model:model
            },
            async: false,
            success: function (data) {
                $("#" + responseFieldId).html(data);
            }
        }); 
        $("#AjaxLoading").hide();
    }
</script>
<?php } ?>