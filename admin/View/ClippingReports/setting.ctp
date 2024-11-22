<div id="main-content" class="row">
    <div id="content" class="col-lg-9 content">
        <div class="card card-default"> 
            <div class="card-body">
                <div class="dataTable_wrapper">
                <?php
                echo $this->Form->create($model, array('id' => 'clipreport_form', 'type' => 'file', 'novalidate' => 'novalidate','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                echo $this->Form->input('xmlurl', array('class' => 'form-control ', 'id' => 'xmlname', 'type' => 'text',"required"=>"required","label"=>"Report url",'onclick'=>'disable_csv(this.value)'));
                echo '<strong> OR </strong>';
                echo $this->Form->input('csvurl', array('class' => 'form-control ', 'id' => 'csvname', 'type' => 'file',"required"=>"required","label"=>"Csv url",'onclick'=>'disable_xml(this.value)'));
                echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                     
              	$this->Form->end();?>
                </div>
            </div>
        </div>
    </div> 
</div> 
<script type="text/javascript">
function disable_csv(value=null){
    $('#xmlname').prop("disabled", false);
    $('#csvname').prop("disabled", true);
    
}

function disable_xml(value=null){
    $('#csvname').prop("disabled", false);
    $('#xmlname').prop("disabled", true);
    
    
}
</script>