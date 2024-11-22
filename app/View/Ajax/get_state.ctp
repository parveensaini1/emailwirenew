<label>
State
</label>
<a href="javascript:void(0)" data-toggle="tooltip" title="Select state here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>

<?php echo $this->Form->input($fieldName, array('label'=>false,'options' => $state_list, 'div' => 'form-group', 'class' => 'form-control state-select','multiple'=>$isMultiple,'empty' => '-Select state-','onchange'=>"statechange(this.value,'$cityFldNm','$isCityMultiple','$cityResId','$isPlanDataInclude');",'id'=>"cstfld")); ?>
<script type="text/javascript">
	 $(function () {
        $(".state-select").select2(); 

    }); 

    

    
function statechange(stateId,fieldName='',isMultiple="yes",responseFieldId='',isPlanInclude='yes') {
    $("#AjaxLoading").show();
    var planId = $("#PressReleasePlanId").val();
    if(isMultiple=='yes'){
        var stateIds = ''; 
        var comma ='';
        $("#cstfld option:selected").each(function(i,e) {
            var vl=$(this).val();
            stateIds +=(comma+vl);
            comma =',';
        }); 
        stateId =stateIds;
    }  
    $.ajax({
        type: 'POST',
        url: '<?php echo SITEURL.'ajax/get_msas'; ?>',  // Do not change it. it`s using in admin also
        data: {
            stateId: stateId,
            planId: planId,
            isMultiple:isMultiple,
            fnm:fieldName,
            is_plan_include:isPlanInclude
        },
        async: false,
        success: function(data) {
            $("#AjaxLoading").hide();
            $("#"+responseFieldId).html(data);
        }
    });
}
</script>