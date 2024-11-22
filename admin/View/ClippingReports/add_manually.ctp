<div id="main-content" class="row">
    <div id="content" class="col-lg-9 content">
    <?php 
        echo "<b>Press Release Id-</b>".$pr_id;
        echo "<br>";
        echo "<b>Created By-</b>".$user_datas['StaffUser']['first_name']." ".$user_datas['StaffUser']['last_name'];
        echo "<br>";
        echo "<b>Email- </b>".$user_datas['StaffUser']['email'];
        echo "<br>";
        echo "<b>Approved By-</b> ".$approved_datas['StaffUser']['first_name']." ".$approved_datas['StaffUser']['last_name'];
        echo "<br>";
        echo "<div class='viewreport'>".$this->Html->link(__('View Clipping Report'), array('controller' => $controller, 'action' => 'viewclippingreport',$pr_id), array('class' => 'btn btn-xs btn-info')) ."</div>";
        echo "<br>";
        echo "<h3>PR Title- ".$pr_title."</h3>";?>
        <div class="card card-default"> 
            <div class="card-body">
                <div class="dataTable_wrapper">
                <?php
                    echo $this->Form->create($model, array('id' => 'clipreport_form', 'type' => 'file', 'novalidate' => 'novalidate','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                    echo $this->Form->input('press_release_id', array('type'=>'hidden','value'=>$pId));
                    echo $this->Form->input('is_manually_added', array('type'=>'hidden','value'=>1));
                    echo $this->Form->input('release_page_url', array('class' => 'form-control ', 'id' => 'release_page_url', 'type' => 'text',"required"=>"required","label"=>"Released Page url"));
                    echo $this->Form->input('site_name', array('class' => 'form-control ','id' =>'site_name','type' => 'text',"required"=>"required","label"=>"Website name"));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                  	$this->Form->end();
                ?>
                </div>
            </div>
        </div>
    </div> 
</div> 
<script type="text/javascript">
    $("#clipreport_form").validate();
</script>