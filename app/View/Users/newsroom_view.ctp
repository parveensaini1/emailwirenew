<?php /*
if($data['Company']['status']==2 || $data['Company']['status']==3 ){
?>
<section class="content-section">
    <div class="box">
      <div class="box-header with-border"> 
        <div class="row">
          <?php if($data['Company']['disapproval_reason']){ ?>
           <div class="col-sm-8">
             <?php 
                echo "<p>".$data['Company']['disapproval_reason']."</p>";
             ?>
           </div>
         <?php } ?>
            <!-- <div class="col-sm'Company' text-right view-btn">
                <a href="<?php echo SITEURL."users/edit/".$data['Company']['id'];?>" class="btn btn-xs btn-info">Edit PR</a> 
            </div> -->
          </div>
        </div>
      </div>
</section>
<?php }*/ ?>
   
<style>
    .modal-header-notification {
        padding: 10px 0px 10px;
        display: block;
        clear: both;
        background: #fa7d07;
        float: left;
        width: 100%;
        color: #fff;
        margin-bottom: 9px;
    }
</style>
<!-- <div class="card modal-header-notification">
    <div class="card-body full ew-sub-page ew-newsroom-block text-center">
        <h4 class="modal-title">Newsroom Preview</h4>
        <p>This is how your company newsroom will be visible to the public. Please scroll to preview newsroom.<br>Check your entries carefully before submission.<br>If all details are fine, then click on the button, "Proceed with this newsroom" else click on "Go back and edit" button below.</p>
    </div>
</div> -->
 
<div class="">
    <div class="card">
        <div class="card-body full ew-sub-page ew-newsroom-block">
            <!-- newsroom mid -->
            <div class="full ew-newsroom-block">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 ew-newsroom-left-user">
                        <div class="full ew-newsromm-user margin-bottom15">
                            <div class="ewlogobx">
                                <?php
                                $newsroomUrl="#";
                                if ($data[$model]['logo'] != '') {
                                    $newsroomUrl = ($data[$model]['status'] == 1) ? SITEURL . 'newsroom/' . $data[$model]['slug'] : "#";
                                } else {
                                    echo $this->Html->image('no_image.jpeg', array('class' => 'user-image', "id" => "prev_logo_image", "width" => "100%"));
                                }
                                echo $this->Html->image(SITEURL . 'files/company/logo/' . $data[$model]['logo_path'] . '/' . $data[$model]['logo'], array('width' => "100%", 'id' => 'prev_logo_image', "url" => $newsroomUrl, 'target' => "_blank"));


                                ?>
                            </div>
                        </div>

                       <? echo $this->element('newsroom_left_sidebar') ?>
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-6 ew-newsroom-right-section">
                        <div class="full ew-newsroom-user-bio">
                            <h3 id="prev_contact_name" class="ew-user-name my-3" itemprop="name"><?php echo ucfirst($data[$model]['name']); ?> Newsroom</h3>
                        </div>
                        <div class="ew-newsroom-tabing full">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                 
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'prnews') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom_view/' . $data[$model]['slug'] . '/prnews/'.$returnType ?>">Press Releases</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'companyassets') {
                                                            echo "active";
                                                        } ?> " href="<?php echo SITEURL . 'users/newsroom_view/' . $data[$model]['slug'] . '/companyassets/'.$returnType; ?>">Media Assets</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'social') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom_view/' . $data[$model]['slug'] . '/social/'.$returnType; ?>" role="tab">Social Media</a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'mediacontact') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom_view/' . $data[$model]['slug'] . '/mediacontact/'.$returnType; ?>">Media Contact</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'aboutus') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom_view/' . $data[$model]['slug'] . '/aboutus/'.$returnType; ?>">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'location') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom_view/' . $data[$model]['slug'] . '/location/'.$returnType; ?>">Location</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'contactus') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom_view/' . $data[$model]['slug'] . '/contactus/'.$returnType; ?>">Contact Us</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane show active" id="compnaynews" role="tabpanel" aria-labelledby="home-tab">
                                    <?php
                                    if ($newsroomFilter == 'aboutus') { ?>
                                        <p id="pre_des" class="ew-bio"><?php echo $data[$model]['description']; ?></p>
                                    <?php } else if ($newsroomFilter == 'social') {
                                        echo $this->element("newsroom_social");
                                    } else if ($newsroomFilter == 'companyassets') {
                                        echo $this->element("newsroom_assets_edit");
                                    } else if ($newsroomFilter == 'location') {
                                        echo $this->element("newsroom_map");
                                    } else if ($newsroomFilter == 'contactus') {
                                        echo $this->element("newsroom_contact_us_form");
                                    } else if ($newsroomFilter == 'mediacontact') {
                                        echo $this->element("newsroom_mediacontact");
                                    } else {
                                        echo $this->element("newsroom_pr");
                                    }
                                    ?>
                                </div>

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
</div>
<style>
    .ew-pr-social ul {
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