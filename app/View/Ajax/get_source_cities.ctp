 
    <label>City</label>
    <a href="javascript:void(0)" data-toggle="tooltip" title="Select city here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
    <?php echo $this->Form->input("$model.media_msa_id", array('class' => 'form-control state-select', 'options' => $msa_list,  'empty' => '-Select city-', 'label' => false)); ?>