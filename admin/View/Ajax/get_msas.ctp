<?php echo $this->Form->input($fieldName, array('options' => $msa_list, 'div' => 'form-group', 'class' => 'form-control msa-select','multiple'=>$isMultiple,'empty' => '-Select state-')); ?>
<script type="text/javascript">
	 $(function () {
        $(".msa-select").select2({
            tags: true
        }); 

    }); 
</script>