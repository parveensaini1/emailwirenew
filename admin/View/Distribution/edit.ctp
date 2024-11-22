<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create($model, array('novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                    <div class="row">
                        <div class="col-sm-9">
                        <?php
                          echo $this->Form->input('id'); 
                        echo "<div class='row'>";
                        echo $this->Form->input('name', array("class" => "form-control", "empty" => "","div"=>'col-sm-6'));
                        $class="col-sm-6";
                        if($this->data['Distribution']['id']==8){
                            $class="col-sm-3";
                            echo $this->Form->input('number', array("class" => "form-control", "empty" => "","div"=>$class,'min'=>"1",'label'=>"Number of emails"));
                        }

                        echo $this->Form->input('amount', array("class" => "form-control","div"=>$class,'min'=>"1"));
                        echo "</div>";
                        echo "<br/>";
                       ?>
                        <div class="row">

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <?php
                             
                            echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                            ?>
                            <?php
                            echo $this->Html->link('Cancel', array(
                                'controller' => $controller,
                                'action' => 'index'
                                    ), array('class' => 'btn btn-danger')
                            );
                            ?>
                        </div>  
                    </div>
                        </div>
                       
                    </div> 
                    <?php echo $this->Form->end(); ?> 
                </div> 

            </div>
        </div>
    </div>
</div>
 