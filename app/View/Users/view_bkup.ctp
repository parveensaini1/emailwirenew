   <?php  
     if(isset($cartdata) &&!empty($cartdata)){ 
        echo $this->element('pr_cart_details'); 
     }elseif(!empty($transdata)){
    
        echo $this->element('prtransaction'); 
     }
   
   ?>
  <section class="content">
   <div class="box">
     <div class="box-header with-border"> 
       <div class="row">
            <div class="col-sm-8">
                <h3 class="box-title"><?php echo ucfirst($data[$model]['title']); ?></h3>
            </div>
       </div>
     </div>
     <div class="box-body" style="">
       <?php echo $data[$model]['summary']; ?>
     </div>
     <div class="box-body" style="">
       <?php echo $data[$model]['body']; ?>         
     </div>  
</section>
<script type="text/javascript">
   $('[data-fancybox="gallery"]').fancybox({
        animationEffect: "zoom",
        protect: true,
});
</script>
