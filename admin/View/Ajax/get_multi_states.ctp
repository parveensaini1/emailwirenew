<?php 
echo $this->Form->input('State.State', array('class' => 'form-control ','label'=>false, 'type' => 'select','options' => $state_list,'multiple'=>true,'div'=>false,'id'=>"pcstateid",'data-select-all'=>'no', 'class' => 'form-control state-select','multiple'=>true,'onchange' => "get_multi_msas_by_state('pcstateid','pcmsaid')"));
?>
<script type="text/javascript">
    	 $(function () {
        $(".state-select").select2({
            tags: true
        }); 

    }); 
  
    function get_multi_msas_by_state(state_id,responseId) {
        state_id = $("#pcstateid").val();
        // console.log($("#pcstateid").val());
    if(state_id.length > 0){
        $("#AjaxLoading").show();
        $.ajax({
            type: 'POST',
            url: '<?php echo SITEURL.'ajax/get_multi_msas';?>',
            data: {state_id: state_id},
            async: false,
            success: function (data) {
                $("#AjaxLoading").hide();
                $("#"+responseId).replaceWith(data);
            },
            error: function (xhr) {
                console.log(xhr.statusText);
            }
        });
        }else{
            $('#'+responseId).find('option').remove().end();
        } 
        $("#AjaxLoading").hide();
    }  
    
</script>
 