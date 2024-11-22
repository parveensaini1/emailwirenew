<div class="full ew-sidebar-inner orange-border">
<!-- Your cart -->   
<?php 
    $action = strtolower(trim($this->params['action']));
    if($action!="signup"&&$action!="create_newsroom"){ ?> 
        <div class="full ew-side-cart margin-bottom15"><?php echo $this->element('cart'); ?></div>
    <?php } ?> 
    <!-- End Your cart --> 
      <!-- Already have an account -->
      <!-- &&$action=="create_newsroom" -->
   <?php if(!isset($user_id)){?>
    <!-- Already have an account -->
    <div class="ew-side-gray-box full margin-bottom15">
        <h2>Already have an account?</h2>
        <!-- ?r='.SITEURL.'users/create-newsroom -->
        <?php 
        $redirectAfterLogin='';
        if(($action=='index'&&$this->params['pass'][0]=='plans')||$action=='create_newsroom'){
            $redirectAfterLogin='?r='.SITEURL.$this->request->url;
        }
        ?>
        <p>You should <a href="<?php echo SITEURL.'users/login'.$redirectAfterLogin; ?>">login to your account</a> to continue the order process.</p> 
    </div>         
    <!-- End Already have an account --> 
    <?php } ?>  
    <!-- End Already have an account --> 
    <div class="ew-side-gray-box full margin-bottom15">
        <h2>Have Question?</h2>
        <p>Feel free to contact us if you have any question or concerns.</p>
        <div class="ew-phone ew-gray-b-text full">Call <?php echo Configure::read('Site.phone'); ?></div>
        <div class="ew-ticket ew-gray-b-text full"><a href="<?php echo SITEURL.'users/support'; ?>">Open Ticket</a></div>
    </div>
    <!-- End Have a question? --> 
  
</div>