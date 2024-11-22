<div class=""> 
    <div class="modal-content">
        <div class="modal-body full ew-sub-page ew-newsroom-block">
            <!-- newsroom mid -->
            <div class="full ew-newsroom-block">
            <div class="row" itemscope itemtype="http://schema.org/Organization">    
                        <div class="col-lg-3 col-md-4 col-sm-6 ew-newsroom-left-user">
                            <div class="full ew-newsromm-user margin-bottom15">
                                <div class="ewlogobx">
                                <?php 
                                if ($data['Company']['logo']!= '') {
                                 $newsroomUrl=($data['Company']['status']==1)?SITEURL.'newsroom/'.$data['Company']['slug']:"#";
                                 echo $this->Html->image(SITEURL.'files/company/logo/'.$data['Company']['logo_path'].'/'.$data['Company']['logo'], array('width'=>"100%",'id'=>'prev_logo_image',"url"=>$newsroomUrl));

                                } else {
                                   echo $this->Html->image('no_image.jpeg', array('class' => 'user-image',"id"=>"prev_logo_image", "width"=>"100%"));
                                }
                                ?> 
                                </div>
                            </div>

							<? echo $this->element('newsroom_left_sidebar') ?>  
                        </div>    
                        <div class="col-lg-9 col-md-8 col-sm-6 ew-newsroom-right-section">
                            <div class="full ew-newsroom-user-bio">
                                <h3 id="prev_contact_name" class="ew-user-name" itemprop="name"><?php echo ucfirst($data['Company']['name']); ?> Newsroom</h3>   
                            </div>
                            <div class="ew-newsroom-tabing full">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link <?php if($newsroomFilter=='prnews'){echo "active"; } ?>" href="<?php echo SITEURL.'newsroom/'.$slug.'/prnews'; ?>">Press Releases</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?php if($newsroomFilter=='companyassets'){echo "active"; } ?> " href="<?php echo SITEURL.'newsroom/'.$slug.'/companyassets'; ?>">Media Assets</a>
                                    </li>                                     
                                    <li class="nav-item">
                                        <a class="nav-link <?php if($newsroomFilter=='social'){echo "active"; } ?>" href="<?php echo SITEURL.'newsroom/'.$slug.'/social'; ?>" role="tab">Social Media</a>
                                    </li>
                                   
                                    <li class="nav-item"> 
                                        <a class="nav-link <?php if($newsroomFilter=='mediacontact'){echo "active"; } ?>" href="<?php echo SITEURL.'newsroom/'.$slug.'/mediacontact'; ?>">Media Contact</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?php if($newsroomFilter=='aboutus'){echo "active"; } ?>" href="<?php echo SITEURL.'newsroom/'.$slug.'/aboutus'; ?>">About Us</a>
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link <?php if($newsroomFilter=='location'){echo "active"; } ?>" href="<?php echo SITEURL.'newsroom/'.$slug.'/location'; ?>">Location</a>
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link <?php if($newsroomFilter=='contactus'){echo "active"; } ?>" href="<?php echo SITEURL.'newsroom/'.$slug.'/contactus'; ?>">Contact Us</a>
                                    </li>
                                    </ul>
                                <div class="tab-content" id="myTabContent" >
                                <div class="tab-pane show active" id="compnaynews" role="tabpanel" aria-labelledby="home-tab"> 
                                    <?php 
                                     if($newsroomFilter=='aboutus'){ ?>
                                           <p id="pre_des" class="ew-bio"><?php echo $data['Company']['description']; ?></p>  
                                    <?php }else if($newsroomFilter=='social'){
                                        echo $this->element("newsroom_social");
                                    }else if($newsroomFilter=='companyassets'){
                                        echo $this->element("newsroom_assets_edit");
                                    }else if($newsroomFilter=='location'){
                                        echo $this->element("newsroom_map");
                                    }else if($newsroomFilter=='contactus'){
                                        echo $this->element("newsroom_contact_us_form");
                                    }else if($newsroomFilter=='mediacontact'){
                                        echo $this->element("newsroom_mediacontact");
                                    }else{
                                        echo $this->element("newsroom_pr");
                                    } 
                                    ?> 
                                </div>
                                <?php /*
                                $paginatorInformation = $this->Paginator->params();
                                if($paginatorInformation['pageCount']>1){ ?>
                                    <div class="row">
                                        <?php echo $this->element('pagination'); ?>
                                    </div>
                                <?php }elseif(isset($totalCount)){?>
                                        <?php echo $this->element('custom_pagination');?>
                                <?php } */ ?>
                                </div>
                            </div>
                        </div>
                    </div>    
            </div>
        </div> 
    </div>
    <style>
        <?php
        /*
            $icons = $this->Post->getSocialShares();
            foreach ($icons as $key => $icon_value) {
              ?>
              .ew-<?php echo strtolower($icon_value['SocialShare']['title']); ?>{
                background: url(<?php echo SITEURL.'website/img/'.$icon_value['SocialShare']['icon_url']; ?>)  no-repeat left top !important;
              }
              <?php
            }*/
        ?>
    </style>
    <div class="ew-pr-social col-sm-12 text-center">
        <?php
            echo $this->Post->sharelinks($data['Company']['job_title'],SITEURL.'newsroom/'.$data['Company']['slug'],substr(strip_tags($data['Company']['description']),0,255).'...',SITEURL.'files/company/logo/'.$data['Company']['logo_path'].'/'.$data['Company']['logo']);
        ?>
    </div> 

    <?php // newsroom footer
             if($action=='newsroom'&& $controller=='pages'){?>
             <div class="row mt-3">
             <div class="col-sm-2 mb-2 ew-footer-logo">
                <a href="<?php echo SITEURL;?>"><img src="<?php echo SITEURL; ?>website/img/emailwire-logo.jpg" alt=""/></a>                
            </div>
                <div class="col-sm-8  copyshareurl"> 
                    <?php echo $this->Form->input('f', array('type'=>'text','readonly' => 'readonly','value'=>Router::url($this->here, true) ,'label'=>false,'id'=>"code-newsroom-slug-footer"));  ?>     <div class="newsroom-ewtooltip ewtooltip">   
                    <button onclick="clipboardcode('newsroom-slug-footer');" data-toggle="tooltip" title="Copy to clipboard">                
                    </button>
                </div>
          
            </div>  
            <div class="col-sm-2 "> </div>
            </div>
          <?php } ?>
    
    
    
</div> 
<style>
    .ew-pr-social ul{
        margin: auto;
    }
.ew-latest-news-inner .ew-link-title {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
}
/*
.ew-latest-news-inner .ew-link-title:hover {
    overflow: initial;
    display: block;
}*/

</style>