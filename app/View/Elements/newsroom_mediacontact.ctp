<div class="full ew-newsromm-address-info margin-bottom30">
    <h3>Media Contact:</h3>
    <div class="avtar-user-box-right">
        <?php if (!empty($data['Company']['media_contact_name'])) { ?>
            <h4 id="prev_contact_info" itemprop="author"><i class="far fa-user"></i> <?php echo $data['Company']['media_contact_name']; ?></h4>
            <div id="prev_contact_info" class="ml-4"> <?php echo $data['Company']['media_job_title']; ?></div>
        <?php } ?> 
    </div>
    <?php if (!empty($data['Company']['media_phone_number'])) { ?>
        <div><span class="telphone ml-4" itemprop="telephone"> <?php echo $data['Company']['media_phone_number']; ?></span></div>
    <?php } ?>
    <div><span class="email ml-4"> <?php echo (!empty($data['Company']['media_email'])) ? $data['Company']['media_email'] : ""; ?></span></div>
</div>