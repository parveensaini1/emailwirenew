<div class="row">
  <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
   <div class="col-lg-12">
      <div class="panel panel-default" > 
         <!-- /.panel-heading -->
         <div class="panel-body">
            <div class="row">
            	<div class="col-sm-8">
            		<?php // include "zohoform.ctp";

                echo $this->element('zohoform');
                 ?>
  		        </div>
    		    <div id="sidebar" class="col-sm-4 ew-sidebar ">
                    <div class="sidebar__inner orange-border">
                        <!-- End Already have an account --> 
                        <div class="ew-side-gray-box full margin-bottom15">
                            <h2>Have Question?</h2>
                            <p>Feel free to contact us if you have any question or concerns.</p>
                            <div class="ew-phone ew-gray-b-text full">Call <?php echo Configure::read('Site.phone'); ?></div>
                            <?php $tickSlug=(!empty($this->Session->read('Auth.User')))?'users/contact-us':'contact-us'; ?>
                            <div class="ew-ticket ew-gray-b-text full"><a href="<?php echo SITEURL.$tickSlug; ?>">Open Ticket</a></div>
                        </div> 
                        <div class="ew-side-gray-box full margin-bottom15">
                            <h2>Address & Location</h2>
                            <?php echo Configure::read('Site.address'); ?>
                        </div>
                        <div class="full margin-bottom15">
                             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d221152.3731887279!2d-95.54484898232744!3d29.99362880592412!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640ca64d6c0a605%3A0xcf9db6e2c0030ddd!2sGroupWeb+Media+LLC+(EMAILWIRE.COM)+-+Press+Release+Distribution+Services!5e0!3m2!1sen!2sin!4v1550048677693" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>   
                        </div>
                    </div>
                </div>
            </div>
         </div>
      </div>
   </div>
</div>

