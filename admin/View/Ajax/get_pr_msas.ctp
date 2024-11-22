<label>
City
</label>
<a href="javascript:void(0)" data-toggle="tooltip" title="Select city here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>

<?php echo $this->Form->input($fieldName, array('options' => $msa_list, 'div' => 'form-group', 'class' => 'form-control msa-select','multiple'=>$isMultiple,'empty' => '-Select city-','label' => false, "required" => "required")); ?>
<script type="text/javascript">
	 $(function () {
        $(".msa-select").select2({
            tags: true
        }); 

    }); 
</script>