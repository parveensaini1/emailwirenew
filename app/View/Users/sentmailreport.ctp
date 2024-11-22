<?php if($action=='viewreport'){ 
  $c = curl_init(SENDYURL.'reportcustom?i=1&c='.$champId.'&userId='.$userId.'&frm=front');
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($c);
    if (curl_error($c)){
        die(curl_error($c));
    }
    $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
    curl_close($c);
    echo $html;
  }else{?>
<div class="row">
   <div class="col-lg-12">
      <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
      <div class="panel panel-default"> 
         <!-- /.panel-heading -->
         <div class="panel-body"> 
                <?php  
                  $c = curl_init(SENDYURL.'reportcustom?i=1&c='.$champId.'&userId='.$userId.'&frm=front');
                  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                  $html = curl_exec($c);
                  if (curl_error($c)){
                      die(curl_error($c));
                  }
                  $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
                  curl_close($c);
                  echo $html;
                  ?>
         </div>
      </div>
   </div>
</div>
<?php } ?>