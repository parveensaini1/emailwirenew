<div class="full ew-featured-news-st ew-latest-news-st">
    <div class="row">    
        <div class="col-sm-12"><div class="ew-title full">Featured News Stories</div></div>
        <?php if(!empty($featured_arr)){ 
            foreach($featured_arr as $index => $featurepr){ 
                if($index<$nofeaturePr){
            ?>
        <div class="col-sm-6 ew-featured-news">
            <div class="ew-featured-news-bl full">
                <div class="orange-border full ew-featured-img">
                <?php  echo $this->Post->getPrSingleImage($featurepr['PressImage'],'crop','545','304');?>
                </div>
                <div class="full ew-featured-news-content class_newsroom">
                    <div class="company_logo_name">
                        <?php if($featurepr['Company']['logo']){?>
                            <div class="company_logo">
                                <?php echo $this->Post->getNewsroomLogo($featurepr['Company']['logo_path'],$featurepr['Company']['logo']);?>
                            </div>
                        <?php } ?>
                        <div id="prev_company_name" class="ew-compnay float-left">
                            <?php echo $this->Post->get_company($featurepr['Company']['name'],$featurepr['Company']['slug']); ?>
                            - <?php echo date($dateformate,strtotime($featurepr['PressRelease']['release_date'])) ?>    
                        </div>  
                    </div>
                    <h2 class="post-title">
                   <?php echo $this->Post->get_title($featurepr['PressRelease']['title'],$featurepr['PressRelease']['slug']); ?>                   
                    </h2>
                </div>
            </div>
        </div>
    <?php       } 
            }
        }  
        ?>  
        <?php if(!empty($featured_arr)&&count($featured_arr)>$nofeaturePr){ ?>
            <div class="col-sm-6 ew-featured-news-slide">
            <div class="bd-example">
                <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                    </ol>
                    <div class="carousel-inner orange-border">
                        <?php foreach ($featured_arr as $index => $featurepr){ 
                            if($index>$nofeaturePr){ ?>
                        <div class="carousel-item  <?php if($index==($nofeaturePr+1)){ echo "active";} ?>">
                        <?php echo $this->Post->getPrSingleImage($featurepr['PressImage'],'crop','544','474'); ?>
                            <div class="carousel-caption d-none d-md-block trans-bg class_newsroom">
                                <div class="company_logo_name">
                                    <?php if($featurepr['Company']['logo']){?>
                                    <div class="ew-comany-logo">
                                        <div class="newsroom_inner">
                                            <?php echo $this->Post->getNewsroomLogo($featurepr['Company']['logo_path'],$featurepr['Company']['logo']);?></div>
                                    </div>
                                    <?ph } ?>
                                    <div id="prev_company_name" class="ew-compnay float-left">
                                     <?php echo $this->Post->get_company($featurepr['Company']['name'],$featurepr['Company']['slug']); ?>
                                        - <?php echo date($dateformate,strtotime($featurepr['PressRelease']['release_date'])) ?> 
                                    </div>  
                                </div> 
                                <h2 class="post-title"><?php echo $this->Post->get_title($featurepr['PressRelease']['title'],$featurepr['PressRelease']['slug']); ?></h2>       
                            </div>
                        </div> 
                        <?php 
                            } 
                         }
                    ?>
                    </div>
                </div>
            </div>
            </div>
        <?php } ?>
    </div>
    <div class="newsrooms-btns col-sm-12">
        <div class="row">
            <div class="col-sm-6"></div>
           <div class="browse-btn col-sm-6 text-right"><a href="<?php echo SITEURL.'featured-press-release'; ?>">View all</a></div>
       </div>
    </div>
</div>
    <div class="full ew-latest-news-st" id="newsroom_list">
        <div class="row">
            <div class="col-sm-12"><div class="ew-title full">Latest Newsrooms</div></div>
            <?php foreach ($newsrooms as $index => $newsroom) {?>
                <div class="col-sm-3 ew-latest-news-post">
                    <div class="full ew-latest-news-inner"> 
                        <div class="orange-border ew-lastest-news-img-single full">
                            <div class="newsroom_inner">
                            <?php 
                                if ($newsroom['Company']['logo']!= '') {
                                 echo $this->Html->image(SITEURL.'files/company/logo/'.$newsroom['Company']['logo_path'].'/'.$newsroom['Company']['logo'], array('width'=>"100%",'id'=>'prev_logo_image','url'=>SITEURL.'newsroom/'.$newsroom['Company']['slug']));

                                } else {
                                   echo $this->Html->image('no_image.jpeg', array('class' => 'user-image',"id"=>"prev_logo_image", "width"=>"100%"));
                                }
                                ?>
                            </div>
                        </div>
                        <div class="full ew-lastest-news-single-content">
                            <?php echo $this->Post->get_company($newsroom['Company']['name'],$newsroom['Company']['slug']); ?> 
                        </div>
                    </div>  
                </div> 
            <?php } ?>          
            <div class="newsrooms-btns col-sm-12">
                <div class="row">
               <div class="browse-btn col-sm-6 text-left"><a href="<?php echo SITEURL.'users/create-newsroom/'; ?>">Create your own company newsroom</a></div>
               <div class="browse-btn col-sm-6 text-right"><a href="<?php echo SITEURL.'newsrooms/'; ?>">Browse Newsrooms</a></div>
               </div>
            </div>
        </div>
    </div>
<div class="full ew-latest-news-st" id="latest_news">
      <?php if(!empty($latestPr)){?>
    <div class="row">
        <div class="col-sm-12"><div class="ew-title full">Latest news</div></div>
         <div class="col-sm-12 ew-lcn-right-news">
        <?php  foreach ($latestPr as $loop1 => $latest) { ?>        
            <div class="full ew-lcn-right-single">
                <div class="orange-border ew-lcn-img-single float-left">
                    <?php  echo $this->Post->getPrSingleImage($latest['PressImage'],'crop','333','215','0','0','0'); ?>
                </div>
                <div class="float-left ew-lcn-right-single-content class_newsroom">
                   <h2 class="post-title"><?php echo $this->Post->get_title($latest['PressRelease']['title'],$latest['PressRelease']['slug']); ?></h2>
                    <div class="company_logo_name">
                        <?php if($latest['Company']['logo']){?>
                            <div class="ew-comany-logo">
                                <div class="newsroom_inner">
                                    <?php echo $this->Post->getNewsroomLogo($latest['Company']['logo_path'],$latest['Company']['logo']);?>
                                </div>
                            </div>
                        <?php } ?>
                        <div id="prev_company_name" class="ew-compnay float-left">
                            <?php echo $this->Post->get_company($latest['Company']['name'],$latest['Company']['slug']); ?>
                            - <?php echo date($dateformate,strtotime($latest['PressRelease']['release_date'])) ?>
                        </div>  
                    </div>                     
                    <div class="prsummary"><?php echo $this->Post->wordLimit($latest['PressRelease']['summary'],$latest['PressRelease']['slug']);?></div>
                </div>   
            </div> 
        <?php  } ?>
        </div>  
        </div>
    <?php } // end if $latestPr condition   ?>
    <div class="row newsrooms-btns">        
           <div class="browse-btn col-sm-6 text-left"><a href="<?php echo SITEURL; ?>latest-news">View All Latest PR</a></div>
           <div class="browse-btn col-sm-6 text-right">
                <?php if(isset($role_id)&&$role_id==3){?> 
                    <a class="browse-btn" href="<?php echo SITEURL; ?>users/add-press-release">Add press release</a>
                <?php }else{?>
                    <a class="browse-btn" href="<?php echo SITEURL; ?>users/create-newsroom">Add press release</a>
                <?php }?>
           </div>
    </div> 
</div>

