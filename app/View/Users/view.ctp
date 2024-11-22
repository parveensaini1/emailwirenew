<?php 
echo $this->Html->css(array('/plugins/owlcarousel/owl.carousel.min'));
echo $this->Html->script(array('/plugins/owlcarousel/owl.carousel.min'));

?> 
<style type="text/css">
 .view-btn{float: right;} 
</style>
<?php 

if($data[$model]['status']==1 || $data[$model]['status']==4 ){
?>
<section class="content-section">
    <div class="card">
      <div class="card-header with-border"> 
        <div class="row <?php if(empty($data[$model]['disapproval_reason'])){ echo "float-right"; }?>">
            <?php if($data[$model]['disapproval_reason']){ ?>
            <div class="col-sm-8">
              <?php 
                  echo "<p>".$data[$model]['disapproval_reason']."</p>";
              ?>
            </div>
          <?php } ?>
            <div class="col-sm-4 text-right view-btn">
              <?php if($data[$model]['status']=='1'){?>
                <a target="_blank" href="<?php echo SITEURL."release/".$data[$model]['slug'];?>" class="btn btn-sm btn-bg-orange">Live view</a>
              <?php }else if($data[$model]['status']=='1'){ ?>
                <a href="<?php echo SITEURL."users/edit/".$data[$model]['id'];?>" class="btn btn-xs btn-info">Edit PR</a>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
</section>
<?php } ?>
<section class="content">
<div id="main-content" class="row">
    <div id="content" class="col-lg-9 col-sm-9 col-md-9 content">
        <div class="card panel-default"> 
            <div class="card-body">
               <div class="">
                  

                 <?php if($data[$model]['language']=="Arabic"){ ?>
                 <h1 class="card-title arabicrtl"><?php echo ucfirst($data[$model]['title']); ?></h1>
                  <?php }else{ ?>
                  <h1 class="card-title"><?php echo ucfirst($data[$model]['title']); ?></h1>
                 <?php } ?>
                 <?php if(!empty($data[$model]['summary'])){ ?>
                 <p class="summary"><?php echo $data[$model]['summary']; ?></p>
               <?php } ?>
               
               <?php if (!empty($data['PressImage'])) { ?>
							  <div class="row primgslider">
                   <div class="col-sm-12">
                     <?php  
                    echo "<div id='primgslider' class='owl-carousel owl-theme'>";
                      foreach ($data['PressImage'] AS $index =>$image) { 
                      $imgurl=SITEURL.'files/company/press_image/'.$image['image_path'].'/'.$image['image_name'];   
                      $dsc=(!empty($image['describe_image']))?$image['describe_image']:"";
                      $img='<img  alt="'.$image['image_text'].'"  src="'.$imgurl.'">';
                      echo "<div class='item'>$img <p>".$dsc."</p></div>";
                    
                      }
                      echo "</div>";
                   
                 ?>
                   </div>
                  
                 </div> 
							<?php } ?>

                <?php   $sourceName=$this->Post->summaryPrefix($data[$model]['source_msa'],$data[$model]['source_state'],$data[$model]['source_country'],$data[$model]['is_source_manually']);
              echo $sourceName.$data[$model]['body']; ?>
               <div class="row">
                    <div id="contact_info" class="col-sm-4 prcontact_details">
                        <div class="inner_tag">
                            <h2>Media Contact</h2> 
                            <ul>
                                <li><strong><?php echo ucfirst($data[$model]['contact_name']); ?></strong> </li>
                                <li>
                               <a href="mailto:<?php echo ucfirst($data[$model]['email']); ?>"><?php echo $data[$model]['email']; ?></a></li>
                               <li> <?php echo $data[$model]['phone']; ?></li>
                               <?php if(!empty($data[$model]['job_title']) || $data[$model]['job_title'] != ''){ ?>
                                <li> <?php echo $data[$model]['job_title']; ?></li>
                               <?php } ?>
                            </ul>
                        </div> 
                    </div>
                  <div class="col-sm-4"></div>
                    <div id="keywords_section" class="col-md-4">
                        <div class="inner_tag">
                        <?php  if(!empty($data['PressSeo'])){?>
                          <h2>Related Tags</h2>
                            <ul id='PressSeo' class='PressSeokeywords'>
                              <?php foreach ($data['PressSeo'] AS $index =>$keywords) {  
                                echo "<li class='item'><a href='".SITEURL."releases/tag/".$keywords['slug']."'>".$keywords['keyword']."</a></li>";  
                              } ?>
                            </ul> 
                        <?php  } ?>
                      </div>
                    </div>
               </div>               

                <div class="row">
                  <?php  if(!empty($data['PressYoutube'])){?>
                   <div class="col-sm-6">
                     <h2>Youtube videos</h2>
                        <div id='yvideoslider' class='owl-carousel owl-theme'>
                       <?php foreach ($data['PressYoutube'] AS $index =>$video) {
                        
                        if (filter_var($video['url'], FILTER_VALIDATE_URL)) { 
                          $videoIfram=$this->Custom->getEmbedCode($video['url']);
                          echo "<div class='item'>".$videoIfram."<p>".$video['description']."</p></div>";  
                        }
                        } ?>
                        </div>
                     </div>
                  <?php  } ?>
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
                     
                         <?php /* if(!empty($data[$model]['iframe_url'])){?>
                           <div class="iframe-section col-sm-12">
                             <iframe width='100%' height='300' src='<?php echo $data[$model]['iframe_url'];?>' frameborder='0'></iframe>
                           </div>
                           <?php }*/?>  
                </div> 
               </div>
            </div>
        </div>
    </div>
    <?php if(!empty($transdata) || !empty($cartdata)){ ?>
    <div class="col-lg-3 col-sm-3 col-md-3 customstickysidebar">
       <div class="sidebar__inner">
          <?php  
            if(isset($cartdata) &&!empty($cartdata)){ 
                echo $this->element('pr_cart_details'); 
            }elseif(!empty($transdata)){
                echo $this->element('prtransaction'); 
            } 
            ?>
        </div>
    </div>
    <?php } ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
 <script type="text/javascript">
   $('#primgslider').owlCarousel({
        loop:false,
        margin:0,
        nav:true,
        items:1,
       autoHeight:true,
         autoplay:true,
      autoplayTimeout:2000,
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
</section>



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