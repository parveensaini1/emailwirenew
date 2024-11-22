<div id="main-content" class="row">
    <div id="content" class="col-lg-12 content">
        <?php 
        echo "<b>Press Release Id-</b>".$pr_id;
        echo "<br>";
        echo "<b>Created By-</b>".$user_datas['StaffUser']['first_name']." ".$user_datas['StaffUser']['last_name'];
        echo "<br>";

        echo "<b>Email- </b>".$user_datas['StaffUser']['email'];
        echo "<br>";

        echo "<b>Approved By-</b> ".$approved_datas['StaffUser']['first_name']." ".$approved_datas['StaffUser']['last_name'];
        echo "<div class='viewreport'>".$this->Html->link(__('View Clipping Report'), array('controller' => $controller, 'action' => 'viewclippingreport',$pr_id,rand(0,5000)), array('class' => 'btn btn-xs btn-info')) ."</div>";
        echo "<br>";
        echo "<h3>PR Title- ".$pr_title."</h3>";?>
        <div class="card card-default"> 

            <div class="card-body">

                <div class="dataTable_wrapper" id="add_record_pr">
                                 <?php  
                    echo $this->Form->create('', array('type' => 'file','inputDefaults' => array('div' => 'form-group'),'id' => 'clipreport_form','novalidate'=>"novalidate"));

                                    echo $this->Form->input('site_name', array('div'=>'col-lg-12','id' =>'site_name','type' => 'text',"label"=>"Website name","required"=>"required"));

                                     echo $this->Form->input('location', array('div'=>'col-lg-12', 'id' => 'location', 'type' => 'text',"label"=>"Location"));

                                     echo $this->Form->input('website_media_type', array('div'=>'col-lg-12','id' => 'website_media_type', 'type' => 'text',"label"=>"Media Type"));

                                    echo $this->Form->input('potential_audience', array('div'=>'col-lg-12', 'id' => 'potential_audience', 'type' => 'text',"label"=>"Potential Audience"));
                            
                                    echo $this->Form->input('release_page_url', array('div'=>'col-lg-12','id' => 'release_page_url', 'type' => 'text',"label"=>"Released Page url","required"=>"required"));

                                    echo $this->Form->input('pr_id', array('div'=>'col-lg-12','id' => 'pr_id', 'type' => 'hidden',"default"=>$pr_id));

                                     echo $this->Form->input('xml_link', array('id' => 'xml_link', 'div'=>'col-lg-12','type' => 'text',"label"=>"Xml Link"));

                                   echo $this->Form->input('website_domain', array('div'=>'col-lg-12','id' => 'website_domain', 'type' => 'text',"label"=>"Website Domain","required"=>"required"));



                                   echo '<label for="SocialShareIcon" class="logocls">Website Logo</label>';
                                   echo $this->Form->file('website_logo',array('label'=>'Website Logo'));

                                    echo $this->Form->submit('Save', array('name'=>'add_media', 'class'=>'btn btn-info', 'div'=>'col-sm-2','div' => false,'style'=>'margin-top:25px;'));
                    ?>
                    <?php echo $this->Form->end(); ?>
                    </div>
            </div>
        </div>

        <div class="card card-default"> 

            <div class="card-body">

                <div class="dataTable_wrapper">

                <h3>Media Pickup</h3>
                    <?php $nwrelationships=$this->data['nw_relationships'];
                            if(isset($nwrelationships) && !empty($nwrelationships)){
                            $i = 1;
              
                            foreach ($nwrelationships as $index => $nwrelationship) {
                                
                                    $networkwebsite = $nwrelationship;

                                    // echo "<pre>";
                                    // print_r($networkwebsite);
                                    // echo "</pre>";
                                    
                                    $class="form-control form-class".$i;
                                    $btnclass="btn btn-info sbmtbtn btnclass".$i;
                                    $btn_name="edit_".$i;
                                    $nw_id=$networkwebsite['NwRelationships']['id'];
                            
                                    echo "<div class='row' style='margin-bottom:40px;'>";
                            
                                    echo $this->Form->create('', array('inputDefaults' => array('div' => 'form-group'),'id' => 'clipreport_form_edit','novalidate'=>"novalidate"));

                                    echo $this->Form->input('db_table', array('div'=>'col-sm-2','class' => $class,'id' => 'db_table', 'type' => 'hidden',"default"=>"nw_relationship"));

                                    echo $this->Form->input('site_name', array('div'=>'col-sm-2','class' => $class,'id' =>'site_name','type' => 'text',"label"=>"Website name",'disabled' => 'disabled','rowno'=>$i,"default"=>$networkwebsite['NwRelationships']['site_name']));

                                    echo $this->Form->input('location', array('div'=>'col-sm-2','class' => $class, 'id' => 'location', 'type' => 'text',"label"=>"Location","default"=>$networkwebsite['NwRelationships']['location'],'disabled' => 'disabled','rowno'=>$i));

                                    echo $this->Form->input('website_media_type', array('div'=>'col-sm-2','class' => $class, 'id' => 'website_media_type', 'type' => 'text',"label"=>"Media Type",'disabled' => 'disabled','rowno'=>$i,"default"=>$networkwebsite['NwRelationships']['type']));
                                    
                                    $pa=$networkwebsite['NwRelationships']['potential_audience'];
                                    if($pa=='0' || $pa=='')
                                    {
                                     $pa="NA";
                                    }

                                    echo $this->Form->input('potential_audience', array('div'=>'col-sm-2','class' => $class, 'id' => 'potential_audience', 'type' => 'text',"label"=>"Potential Audience","default"=>$pa,'disabled' => 'disabled','rowno'=>$i));
                            
                                    echo $this->Form->input('release_page_url', array('div'=>'col-sm-2','class' => $class,'id' => 'release_page_url', 'type' => 'text',"label"=>"Released Page url",'disabled' => 'disabled','rowno'=>$i,"default"=>$nwrelationship['NwRelationships']['press_release_link']));

                                    echo $this->Form->input('db_row_id', array('div'=>'col-sm-2','class' => $class,'id' => 'db_row_id', 'type' => 'hidden',"label"=>"db_row_id",'disabled' => 'disabled','rowno'=>$i,"default"=>$nw_id));

                                   
                                    echo $this->Form->submit('Save', array('name'=>$btn_name,'div'=>'col-sm-2','class' => $btnclass, 'div' => false,'rowno'=>$i,'style'=>'margin-top:25px;'));

                                    echo "<div style='text-align: center;' class='col-sm-1 enablebtn' rowno=".$i."><label for='site_name'>Edit</label><div class='form-control form-class2 edit_link'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></div></div>";
                                    echo "<div style='text-align: center;' class='col-sm-1 enablebtn' rowno=".$i."><a href='".SITEFRONTURL."admin/ClippingReports/delete/".$pr_id."/".$nw_id."'><label for='site_name'>Delete</label><div class='form-control form-class2 edit_link'><i class='fa fa-trash-o' aria-hidden='true'></i></div></a></div>";
                            
                                    echo $this->Form->end();
                                    echo "</div>";
                                    $i++;
                                
                            }
                         }

                        // $i=1;

                        // foreach ($nw_websites as $nw_website)
                        // {   

                        // }
                          

                    ?>
                </div>
            </div>
        </div>
    </div> 
</div> 
<script type="text/javascript">
    $("#clipreport_form").validate();
    $(".sbmtbtn").hide();
    $(".enablebtn").click(function(){
        var rowno = $(this).attr("rowno");
        var inputclass="form-class"+rowno;
        var btnclass="btnclass"+rowno;
        $("."+inputclass).prop('disabled', false);
        $("."+btnclass).show();
        $(this).remove();
    });
    
</script>

<?php
        //  echo '<pre>';
        //  print_r($this->data);die; 
?>