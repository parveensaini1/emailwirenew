<div class="cus-ew-newsromm-user-box">
    <h4>Company Info</h4>
    <div class="cus-ew-newsromm-user text-left" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <div class="full ew-newsromm-address-info margin-bottom30">

            <div class="avtar-user-box-right">
                <?php if (!empty($data[$model]['contact_name'])) { ?>
                    <h4 id="prev_contact_info" class="contact-icons" itemprop="author"><i class="far fa-user"></i> <?php echo $data[$model]['contact_name']; ?></h4>
                    <p id="prev_contact_info" class="ml-4">
                        <!-- <i class="fas fa-laptop-house"></i> --> <?php echo $data[$model]['job_title']; ?>
                    </p>
                <?php } ?>
                <?php if (!empty($data[$model]['phone_number'])) { ?>
                    <!-- <i class="fas fa-phone-square-alt"></i> -->
                    <p class="ml-4"><span class="telphone" itemprop="telephone"> <?php echo $data[$model]['phone_number']; ?></span></p>
                <?php } ?>
            </div>
            <!-- <i class="far fa-envelope"></i> -->
            <p class="ml-4"><span class="email"> <?php echo (!empty($data[$model]['media_email'])) ? $data[$model]['media_email'] : ""; ?></span></p>
        </div>
        <?php /* ?>
                                <div class="full ew-newsromm-contact-info margin-bottom30">
                                    <h4>About us</h4>
                                    <p id="pre_about" class="ew-bio"><?php echo $data[$model]['hear_about_us']; ?></p>    

                                </div> 
                                <?php */
        ?>
        <?php if (!empty($data[$model]['address']) || !empty($data[$model]['city']) || !empty($data[$model]['state']) || !empty($data[$model]['zip_code']) || !empty($data['Country']['name'])) { ?>
            <div class="full ew-newsromm-address-info margin-bottom30">
                <h4 class="contact-icons"><i class="far fa-address-card"></i> Address</h4>
                <p class="ml-4" id="prev_address" itemprop="streetAddress"><?php echo $data[$model]['address']; ?> <br /><?php echo $data[$model]['city']; ?>, <?php echo $data[$model]['state']; ?> <?php echo $data[$model]['zip_code']; ?> <br /><?php echo $data['Country']['name']; ?></p>
            </div>
        <?php } ?>
        <div class="full ew-newsromm-org-info">
            <?php if (!empty($data['OrganizationType']['name'])) { ?>
                <div class="organizationbox margin-bottom30">
                    <h4 class="contact-icons"><i class="far fa-building"></i> Organization Type</h4>
                    <p id="prev_org" class="ew-organization ml-4"><?php echo $data['OrganizationType']['name']; ?></p>
                </div>
            <?php } ?>
            <?php if (!empty($data[$model]['web_site'])) { ?>
                <div class="webbox margin-bottom30">
                    <h4 class="contact-icons"><i class="fas fa-external-link-alt"></i> Website</h4>
                    <a class="ml-4" id="prev-web" class="ew-web" target="_blank" href="<?php echo $data[$model]['web_site']; ?>"><?php echo $data[$model]['web_site']; ?></a>
                </div>
            <?php } ?>
        </div>
        <div class="full social-media-icon-newsroom margin-bottom20">
            <?php echo $this->element('socialicons');  ?>
        </div>
    </div>
</div>