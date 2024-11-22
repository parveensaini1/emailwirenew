<?php 
echo $this->Html->css(array('/plugins/owlcarousel/owl.carousel.min'));
echo $this->Html->script(array('/plugins/owlcarousel/owl.carousel.min'));

?> 
<style type="text/css">
#PressReleaseform section:not(:first-of-type) {
  display: none;
}
#PressReleaseform .action-button {
  width: 100px;
  background: #27AE60;
  font-weight: bold;
  color: white;
  border: 0 none;
  border-radius: 1px;
  cursor: pointer;
  padding: 10px 5px;
  margin: 10px 5px;
}
#PressReleaseform .action-button:hover, #PressReleaseform .action-button:focus {
  box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
} 
</style> 
<div id="main-content" class="row">
    <div id="content" class="col-lg-12 content <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']);  ?>">
      <div class="row">
          <div class="col-sm-6">
          <a href="<?php echo SITEURL.'PressReleases/add/'.$data[$model]['language'].'/'.$data[$model]['plan_id'].'/'.$data[$model]['id'];?>" class="btn btn-primary">Go back edit Press Release</a>
          </div>
          <div class="col-sm-6 text-right">
            <?php 
            echo $this->Form->create('PressRelease', array('id' => 'release_form', 'type' => 'file', 'novalidate' => 'novalidate','id'=>"PressReleaseform",'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
            echo $this->Form->input('id',array("type"=>'hidden',"value"=>$id));
            echo $this->Form->input('status',array("type"=>'hidden',"value"=>'1'));

             echo $this->Form->submit('Submit PR', array('class' => 'btn btn-info', 'div' => false));
            $this->Form->end();

            ?>
          </div>
        </div>
        <br />
        <div class="card card-default"> 
            <div class="card-body">
               <div class="box">
                  <h1 class="box-title"><?php echo ucfirst($data[$model]['title']); ?></h1>
                 <?php if(!empty($data[$model]['summary'])){ ?>
                 <p class="summary"><?php echo $data[$model]['summary']; ?></p>
               <?php } ?>
                 <div class="row primgslider">
                   <div class="col-sm-6">
                     <?php 
                  if(!empty($data['PressImage'])){
                    echo "<div id='primgslider' class='owl-carousel owl-theme'>";
                      foreach ($data['PressImage'] AS $index =>$image) { 
                      $imgurl=SITEFRONTURL.'files/company/press_image/'.$image['image_path'].'/'.$image['image_name'];   
                      $dsc=(!empty($image['describe_image']))?$image['describe_image']:"";
                      $img='<img  alt="'.$image['image_text'].'"  src="'.$imgurl.'">';
                      echo "<div class='item'>$img <p>".$dsc."</p></div>";
                    
                      }
                      echo "</div>";
                       
                   }
                 ?>
                   </div>
                  
                 </div>
                  
                      
                 <?php   $sourceName=$this->Post->summaryPrefix($data[$model]['source_msa'],$data[$model]['source_state'],$data[$model]['source_country'],$data[$model]['is_source_manually']);
              echo $sourceName.$data[$model]['body']; ?>
                    
                      <div class="row">
                         <?php  if(!empty($data['PressSeo'])){?>
                            <div class="col-sm-12">
                              <h2>Related Tags</h2>
                              <ul id='PressSeo' class='PressSeokeywords'>
                               <?php foreach ($data['PressSeo'] AS $index =>$keywords) { 
                                echo "<li class='item'><a target='_blank' href='".SITEFRONTURL."releases/tag/".$keywords['slug']."'>".$keywords['keyword']."</a></li>";  
                                } ?>
                              </ul> 
                              </div>
                          <?php  } ?>
                       </div>

                        <div class="row">
                           <div class="col-sm-6">
                            
                             <?php  
                               if(!empty($data['PressYoutube'])){?>
                               <h2>Youtube videos</h2>
                                  <div id='yvideoslider' class='owl-carousel owl-theme'>
                                 <?php foreach ($data['PressYoutube'] AS $index =>$video) {
                                     echo $index;
                                     if($video['url']){
                                          $videoIfram=$this->Custom->getEmbedCode($video['url']);
                                          echo "<div class='item'>".$videoIfram."<p>".$video['description']."</p></div>";
                                          
                                     }
                                  } ?>
                                  </div>
                              <?php  } ?>
                           </div>
                      

                          <div class="col-sm-6">
                            
                           <?php  
                               if(!empty($data['PressPoadcast'])){
                                   echo "<h2>Poadcasts</h2>";
                                  echo "<div id='poadcastslider' class='owl-carousel owl-theme'>";
                                  foreach ($data['PressPoadcast'] AS $index =>$poadcast) {
                                    if(!empty($poadcast['url'])){
                                      echo "<div class='item'>".$poadcast['url']."<p>".$poadcast['description']."</p></div>";  
                                    }

                                  }
                                  echo " </div>";
                               }
                            ?>
                          </div>   
                     
                         <?php if(!empty($data[$model]['iframe_url'])){?>
                           <div class="iframe-section col-sm-12">
                             <iframe width='100%' height='300' src='<?php echo $data[$model]['iframe_url'];?>' frameborder='0'></iframe>
                           </div>
                           <?php }?>  
                     </div> 
               </div>
            </div>
        </div>
    </div>
<!--     <div id="sidebar" class="col-lg-3">
        <div class="sidebar__inner">
        <?php /// include 'pr_cart.ctp';?>
        </div>
    </div> -->
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
 <script type="text/javascript">
   $('#primgslider').owlCarousel({
        loop:false,
        margin:0,
        nav:true,
        items:1,
         autoplay:true,
      autoplayTimeout:2000,
       autoHeight:true,
      autoplayHoverPause:true
    });
 </script>

  <script type="text/javascript">


   $('#yvideoslider, #poadcastslider').owlCarousel({
        loop:false,
        margin:0,
        nav:true,
        items:1,
        autoplay:false,
        autoplayHoverPause:true
    });
 </script>