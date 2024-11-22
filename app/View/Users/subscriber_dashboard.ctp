<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
        <?php if($email_confirmed!=0&&$newsletter_subscription!=1){ ?>
            <div class="panel-body"> 
                <div class="dataTable_wrapper">
                    <?php  include 'subscriber_newsletter_form.php'; ?>
                    
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
</div>