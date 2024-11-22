
        <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout; ?> </div></div>
        <div class="col-lg-12">      
<div id="filter_category" class="category_section">
<?php 
echo $this->Custom->getCategoryFormat($categories,$filterby);
?>  

</div></div>