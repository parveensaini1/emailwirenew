<?php if(!empty($prarray)){ 
	$i=0;
    foreach ($prarray as $key => $pressrelease) { if($i%4==0) {?>
      </div> <div class="row">
    <?php  }
     ?>
    <div class="col-lg-3  col-sm-3 col-md-3 col-xs-12 ew-latest-news-post margin-bottom20">
        <div class="full ew-latest-news-inner"> 
        	<?php //if(!empty($pressrelease['PressImage'])){ ?> 
            <div class="orange-border ew-lastest-news-img-single full relative mb-2">
              
              <a href="<?php echo SITEFRONTURL."release/".$pressrelease['PressRelease']['slug'];?>">
                <?php echo $this->Post->getPrSingleImage($pressrelease['PressImage'],'crop','250','150'); ?>
              </a>
                <div class="ew-date-sm ew-absolute-date"><?php echo date($dateformate,strtotime($pressrelease['PressRelease']['release_date'])); ?>
                </div>
            </div>
            <?php // } ?>
            
            <div class="full ew-lastest-news-single-content mt-4">
                 <?php echo $this->Post->get_title($pressrelease['PressRelease']['title'],$pressrelease['PressRelease']['slug']); ?>
                 <?php /* ?>
                <div class="ew-company-news-bl float-left">
                  <?php if($data['Company']['logo']){?>
                    <div class="ew-comany-logo float-left">
                      <div  class="ewlogobx">
                        <?php echo $this->Post->getNewsroomLogo($data['Company']['logo_path'],$data['Company']['logo'],$data['Company']['slug']);?>
                      </div> 
                    </div>
                  <?php } ?>
                  <div id="prev_company_name" class="ew-compnay-name float-left"><?php echo $this->Post->get_company($data['Company']['name'],$data['Company']['slug']); ?></div>
                </div>
                <?php */ ?>
            </div>
        </div>  
    </div>  
<?php
++$i;
if($i%4==0) { ?> 
  </div><div class="row">
  <?php }
} ?>

<?php  }  ?>