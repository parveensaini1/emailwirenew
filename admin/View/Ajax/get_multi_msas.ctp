<?php 
    echo $this->Form->input('Msa.Msa', array('class' => 'form-control ','label'=>false, 'type' => 'select','options' => $msa_list,'multiple'=>true,'div'=>false,'id'=>"pcmsaid", 'class' => 'form-control  msa-select','multiple'=>true));
?>
<script type="text/javascript">
    	 $(function () {
        $(".msa-select").select2({
            tags: true
        }); 

    }); 
  
	 
</script>
 