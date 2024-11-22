<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">              
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create('PdfSetting', array('type' => 'file', 'novalidate' => 'novalidate','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                    echo $this->Form->input('id'); 
                    ?>
                    
                    <div class="row"> 
                        
                        <div class="col-sm-6">
                            <?php
                           echo $this->Form->input('title', array("type" => 'text', 'class' => 'form-control','maxlength'=>"100",'minlength'=>"10"));
                            ?>
                        </div>
<div class="col-sm-6">
    <?php
    echo $this->Form->input('logo', array('class' => 'form-control', 'type' => 'file', "label" => "Logo Image"));
    echo $this->Form->input('type', array('class' => 'form-control', 'type' => 'hidden', "value" => "clipping_report"));
    // Add a checkbox for deleting the image
    echo $this->Form->input('delete_logo', array('class' => 'form-check-input', 'type' => 'checkbox', "label" => "&nbsp; &nbsp;&nbsp;Delete Image"));
    ?>
</div>

                        
                    </div>
                     <div class="row">
                            <div class="col-sm-6">
                                &nbsp;
                            </div>    
                            <div class="col-sm-6">
                             <img alt="Logo Not Added" src="<?php echo SITEFRONTURL."/files/pdf_settings/".$this->request->data['PdfSetting']['logo'];?>" width="150px" height="100px" />
                             <br/>
                             <?php //echo $this->request->data['PdfSetting']['logo'];?>
                         </div>
                    </div> 
                    <div class="row">
                        <div class="col-sm-12">
                            &nbsp;
                        </div>   
                    </div> 
                    <div class="row"> 
                        
                        <div class="col-sm-6">

                            <?php
                           echo $this->Form->input('welcome_text', array("type" => 'textarea', 'class' => 'form-control','maxlength'=>"500",'minlength'=>"10"));
                            ?>
                            <p><span style="color:red">Note : You are given the liberty to change entire text except the  ##PLAN_NAME##. </span></p>
                        </div>
                        <div class="col-sm-6">
                            <?php
                           echo $this->Form->input('network_description', array("type" => 'textarea', 'class' => 'form-control','maxlength'=>"500",'minlength'=>"10"));
                            ?>
                        </div>
                        
                    </div>  
                    <div class="row"> 
                        <div class="col-sm-6">
                            <?php
                           echo $this->Form->input('email_distribution_description', array("type" => 'textarea', 'class' => 'form-control','maxlength'=>"500",'minlength'=>"10"));
                            ?>
                        </div>
                        <div class="col-sm-6">
                             <?php  echo $this->Form->input('footer_text', array("type" => 'textarea', 'class' => 'form-control','col'=>"80",'row'=>"10"));?>
                            <p><span style="color:red">Note : You are given the liberty to change entire text except the  <i>##PHONE##</i> and <i>##YEAR##</i>  because the phone and year are dynamic. </span></p>
                        </div>
                    </div>
                    
                    <?php                                                             
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                   
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
