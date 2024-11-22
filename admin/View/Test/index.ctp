<?php echo $this->Html->Script(array('/plugins/uploadify/jquery.uploadify.min')); ?>
<?php echo $this->Html->css(array('/plugins/uploadify/uploadify')); ?>

<input type="file" name="file_upload" id="file_upload" />
<script>
    $(function () {
        $("#file_upload").uploadify({
            'formData': {'sessionId': '<?php echo $sessionId; ?>'},
            'swf': SITEURL + 'plugins/uploadify/uploadify.swf',
            'uploader': SITEURL + "/test/upload_files",
            'debug':true,
            'onUploadStart': function (file) {
                //$("#file_upload").uploadify("settings", "someOtherKey", 2);
            }
        });
    });
</script>