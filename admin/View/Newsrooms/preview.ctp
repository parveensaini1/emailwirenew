<style>
    #register_from #submit-btn {
    background: #fa7d07;
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    width: auto;
    text-align: center;
    height: 36px !important;
    border: none;
    padding: 0px 30px;
}

    .pagetitle {
        display: none;
    }

    .submitsticky {
        position: -webkit-sticky;
        /* Safari */
        position: sticky;
        top: 0;
        z-index: 9;
    }

    .nav-tabs {
    border-bottom: none;
    margin-bottom: 20px;
    display: flex;
    flex-direction: row;
}
.ew-newsroom-tabing .nav-item {
    margin: 0px 10px 0px 0px;
}

#myTab li.nav-item a {
    background: #ebbf74;
    font-size: 14px;
    color: #000;
    font-weight: 600;
    border-radius: 4px;
    border: none;
    margin-right: 0px;
}

#myTab li.nav-item a:hover, #myTab li.nav-item a.active {
    border: none;
    background: #fa7d07;
    border-radius: 4px;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}
</style>

<div class="row submitsticky">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?php echo $this->Html->link(__("Back to Edit"), array('controller' =>'newsrooms','action' =>"create_newsroom",$data[$model]['id'],$returnType), array('class' => 'btn btn-primary mr-4',));?>
                    <?php echo $title_for_layout; ?>
                </h3>
                <div class="card-tools">
                    <?php
                    
                    echo $this->Form->create($model, array('type' => 'file', 'inputDefaults' => array('class' => 'form-control', 'label' => false, 'div' => false,), 'class' => "-right form", "id" => "register_from", 'validate'));
                    
                    echo $this->Form->input("$model.id", array("type" => "hidden","value"=>$data[$model]['id']));
                    echo $this->Form->input("$model.company_id", array("type" => "hidden","value"=>$data[$model]['id']));
                    
                    
                    echo $this->Form->input('Proceed with this newsroom', array("type" => 'submit', 'id' => 'submit-btn', 'class' => "btn btn-info", "div" => "input-group input-group-sm"));
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .modal-header-notification{
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
<div class="card modal-header-notification">
    <div class="card-body full ew-sub-page ew-newsroom-block text-center"> 
        <h4 class="modal-title">Newsroom Preview</h4>
        <p>This is how your company newsroom will be visible to the public. Please scroll to preview newsroom.<br>Check your entries carefully before submission.<br>If all details are fine, then click on the button, "Proceed with this newsroom" else click on "Go back and edit" button below.</p>
    </div>
</div>
<?php
$banner = SITEFRONTURL . 'files/company/banner/' . $data[$model]['banner_path'] . '/' . $data[$model]['banner_image'];
?>
<div class="header-newsroom ew-banner-newsroom full">
    <img src="<?php echo $banner; ?>" style="width:100%;">
</div>
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
                                    $newsroomUrl = ($data[$model]['status'] == 1) ? SITEFRONTURL . 'newsroom/' . $data[$model]['slug'] : "#";
                                } else {
                                    echo $this->Html->image('no_image.jpeg', array('class' => 'user-image', "id" => "prev_logo_image", "width" => "100%"));
                                }
                                echo $this->Html->image(SITEFRONTURL . 'files/company/logo/' . $data[$model]['logo_path'] . '/' . $data[$model]['logo'], array('width' => "100%", 'id' => 'prev_logo_image', "url" => $newsroomUrl, 'target' => "_blank"));


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
                                                        } ?>" href="<?php echo SITEURL . 'newsrooms/preview/' . $companyId.'/'.$returnType; ?>">Press Releases</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'companyassets') {
                                                            echo "active";
                                                        } ?> " href="<?php echo SITEURL . 'newsrooms/preview/' . $companyId .'/'.$returnType. '/companyassets'; ?>">Media Assets</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'social') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'newsrooms/preview/' . $companyId .'/'.$returnType. '/social'; ?>" role="tab">Social Media</a>
                                </li>
                               
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'mediacontact') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'newsrooms/preview/' . $companyId .'/'.$returnType. '/mediacontact'; ?>">Media Contact</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'aboutus') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'newsrooms/preview/' . $companyId .'/'.$returnType. '/aboutus'; ?>">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'location') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'newsrooms/preview/' . $companyId .'/'.$returnType. '/location'; ?>">Location</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php if ($newsroomFilter == 'contactus') {
                                                            echo "active";
                                                        } ?>" href="<?php echo SITEURL . 'newsrooms/preview/' . $companyId .'/'.$returnType. '/contactus'; ?>">Contact Us</a>
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
                                        echo $this->element("newsroom_assets");
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
                background: url(<?php echo SITEFRONTURL.'website/img/'.$icon_value['SocialShare']['icon_url']; ?>)  no-repeat left top !important;
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