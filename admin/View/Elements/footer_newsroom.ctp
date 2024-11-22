 <footer class="ew-footer full mt-2 p-4" id="footernewsroom">
     <div class="container">
         <!-- logo and social icon -->
         <div class="full ew-footer-logo-social margin-bottom30">
             <div class="row">
                 <div class="col-sm-3 ew-footer-logo">
                     <a href="#"><img src="<?php echo SITEFRONTURL; ?>website/img/emailwire-logo.jpg" alt="" /></a>
                 </div>
                 <div class="ew-pr-social col-sm-6 text-center">
                     <?php
                        echo $this->Post->sharelinks($data[$model]['job_title'], SITEFRONTURL . 'newsroom/' . $data[$model]['slug'], substr(strip_tags($data[$model]['description']), 0, 255) . '...', SITEFRONTURL . 'files/company/logo/' . $data[$model]['logo_path'] . '/' . $data[$model]['logo']);
                        ?>
                 </div>
                 <div class="ew-pr-social col-sm-3 text-center"></div>
             </div>

             <div class="row mt-2">
         <div class="col-sm-3 mb-2"></div>
         <div class="col-sm-8  copyshareurl">
             <?php echo $this->Form->input('f', array('type' => 'text', 'readonly' => 'readonly', 'value' => Router::url($this->here, true), 'label' => false, 'id' => "code-newsroom-slug-footer"));  ?> <div class="newsroom-ewtooltip ewtooltip">
                 <button onclick="clipboardcode('newsroom-slug-footer');" data-toggle="tooltip" title="Copy to clipboard">
                 </button>
             </div>

         </div>
         <div class="col-sm-2 "> </div>
     </div>

         </div>


     </div>
     <!-- Footer bottom -->

  

     <div class="full text-center ew-footer-copyright">
         <div class="<?php echo (!empty($isFullwidth)) ? "container-fluid" : "container"; ?>">
             <div class="row" id="newsroom_text">
                 
                     <div class="col-sm-2">
                         <div id="buttons_footer1" class="pl-1 float-left">
                             <a href="<?php echo SITEFRONTURL; ?>users/create-newsroom">Create News Room</a>
                         </div>
                     </div>
                     <div class="col-sm-8">
                         <?php
                            echo str_replace(["##YEAR##", "EmailWire is"], [date('Y'), "This newroom is provided by <a href='" . SITEFRONTURL . "'>" . $siteName . "</a> -- "], Configure::read('Site.Copyright'));

                            ?>
                     </div>
                     <div class="col-sm-2" id="buttons_footer2"  class="pr-1  float-right">
                     <a class="orange-back" href="<?php echo SITEFRONTURL; ?>users/create-newsroom">Send a Release</a>
                     </div> 
             </div>

         </div>
     </div>

     <!-- End Footer bottom -->
 </footer>