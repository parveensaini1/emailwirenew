 
    <label>State</label>
    <a href="javascript:void(0)" data-toggle="tooltip" title="Select state here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
    <?php echo $this->Form->input("$model.media_state_id", array('class' => 'form-control select2', 'options' => $state_list,  'empty' => '-Select state-', 'label' => false, "required" => "required","onchange"=>"getAjxSourceCities(this.value,'media_msa_id_box','$model')")); ?>
<script type="text/javascript">
	 $(function () {
        $(".state-select").select2(); 

    }); 

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