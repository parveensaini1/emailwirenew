<?php
echo $this->Form->create('User');
echo $this->Form->input('Email');
echo $this->Form->input('password');
echo $this->Form->button('Save',array('type'=>'button','onclick'=>'test();'));
echo $this->Form->end();
?>
<script>
    function test(){
        alert($("#UserEmail").val());
    }
    </script>