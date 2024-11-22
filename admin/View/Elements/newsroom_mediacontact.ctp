<div class="full ew-newsromm-address-info margin-bottom30">
    <h3>Media Contact:</h3>
    <div class="avtar-user-box-right">
        <?php if (!empty($data['Company']['media_contact_name'])) { ?>
            <h4 id="prev_contact_info" itemprop="author"><i class="far fa-user"></i> <?php echo $data['Company']['media_contact_name']; ?></h4>
            <div id="prev_contact_info"> <i class="fas fa-laptop-house"></i> <?php echo $data['Company']['media_job_title']; ?></div>
        <?php } ?>
        <?php if (!empty($data['Company']['media_phone_number'])) { ?>
            <div><span class="telphone" itemprop="telephone"> <i class="fas fa-phone-square-alt"></i> <?php echo $data['Company']['media_phone_number']; ?></span></div>
        <?php } ?>
    </div>
    <div><span class="email"> <i class="far fa-envelope"></i> <?php echo (!empty($data['Company']['media_email'])) ? $data['Company']['media_email'] : ""; ?></span></div>
</div>