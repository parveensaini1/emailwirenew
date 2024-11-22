

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
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom-preview/' . $slug.'/prnews'; ?>">Press Releases</a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'companyassets') {
                                                            echo "active";
                                                        } ?> " href="<?php echo SITEURL .'users/newsroom-preview/' . $slug. '/companyassets'; ?>">Media Assets</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'social') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL .'users/newsroom-preview/' . $slug. '/social'; ?>" role="tab">Social Media</a>
                                </li>
                              
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'mediacontact') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom-preview/' . $slug.'/mediacontact'; ?>">Media Contact</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'aboutus') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL .'users/newsroom-preview/' . $slug. '/aboutus'; ?>">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'location') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom-preview/' . $slug.'/location'; ?>">Location</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'contactus') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'users/newsroom-preview/' . $slug. '/contactus'; ?>">Contact Us</a>
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