<?php 
   echo $this->Html->css(array('/plugins/owlcarousel/owl.carousel.min'));
   echo $this->Html->script(array('/plugins/owlcarousel/owl.carousel.min'));
?> 
<style type="text/css">
   form#PressReleaseCartFrom {
    width: 100%;
    float: left;
}

#PressReleaseform section:not(:first-of-type) {
  display: none;
}

#PressReleaseform .action-button {
  width: 100px;
  background: #27AE60;
  font-weight: bold;
  color: white;
  border: 0 none;
  border-radius: 1px;
  cursor: pointer;
  padding: 10px 5px;
  margin: 10px 5px;
}
#PressReleaseform .action-button:hover, #PressReleaseform .action-button:focus {
  card-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
} 
</style> 
 <?php
  $promo_code=''; 

  $total_amount=(isset($cartdata['totals']['total'])&&$cartdata['totals']['total']>0)?$cartdata['totals']['total']:"0.00"; 
  $subtotal=(isset($cartdata['totals']['subtotal'])&&$cartdata['totals']['subtotal']>0)?$cartdata['totals']['subtotal']:"0.00";
  $discount=(isset($cartdata['totals']['discount'])&&$cartdata['totals']['discount']>0)?$cartdata['totals']['discount']:"0.00";
  $tax=(isset($cartdata['totals']['tax'])&&$cartdata['totals']['tax']>0)?$cartdata['totals']['tax']:"0.00";
  $submitBtnlabel=($subtotal>0)?"Submit PR without payment for approval":"Submit PR for approval"; 
  ?>
<div  class="row">
   <?php echo $this->Form->create('PressRelease', array('id' => 'release_form', 'type' => 'file', 'novalidate' => 'novalidate','id'=>"PressReleaseCartFrom",'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); 
      echo $this->Form->input('submittype', array("type" => "hidden", 'id' => "submittype"));

   ?>
         <div id="content" class="<?php echo $this->Post->classAccordingToLanguage($data["Language"]['code']);?> col-lg-9 col-sm-9 col-md-9 content p-0 float-left">
            <div class="col-sm-12">
                <div class="card ls-card">
                     <div class="card-body">
                     <h1 class="card-title"><?php echo ucfirst($data[$model]['title']); ?></h1>
                        <?php if (!empty($data[$model]['summary'])) { ?>
                            <p class="summary"><?php echo $data[$model]['summary']; ?></p>
                        <?php } ?>
                        <div class="row primgslider">
                            <div class="col-sm-6">
                                <?php
                                if (!empty($data['PressImage'])) {
                                    echo "<div id='primgslider' class='owl-carousel owl-theme'>";
                                    foreach ($data['PressImage'] as $index => $image) {
                                        $imgurl = SITEURL . 'files/company/press_image/' . $image['image_path'] . '/' . $image['image_name'];
                                        $dsc = (!empty($image['describe_image'])) ? $image['describe_image'] : "";
                                        $img = '<img  alt="' . $image['image_text'] . '"  src="' . $imgurl . '">';
                                        echo "<div class='item'>$img <p>" . $dsc . "</p></div>";
                                    }
                                    echo "</div>";
                                }
                                ?>
                            </div>
                        </div>

                        <?php $sourceName = $this->Post->summaryPrefix($data[$model]['source_msa'], $data[$model]['source_state'], $data[$model]['source_country'], $data[$model]['is_source_manually']);
                        echo $sourceName . $data[$model]['body']; ?>

                        <div class="row">
                            <div id="contact_info" class="col-sm-4 prcontact_details">
                                <div class="inner_tag">
                                    <h2>Media Contact</h2>
                                    <ul>
                                        <li><strong><?php echo ucfirst($data[$model]['contact_name']); ?></strong> </li>
                                        <li>
                                            <a href="mailto:<?php echo ucfirst($data[$model]['email']); ?>"><?php echo $data[$model]['email']; ?></a>
                                        </li>
                                        <li> <?php echo $data[$model]['phone']; ?></li>
                                        <li> <?php echo $data[$model]['job_title']; ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4"></div>
                            <?php if (!empty($data['PressSeo'])) { ?>
                                <div id="keywords_section" class="col-sm-4">
                                    <div class="inner_tag">
                                        <h2>Related Tags</h2>
                                        <ul id='PressSeo' class='PressSeokeywords'>
                                            <?php foreach ($data['PressSeo'] as $index => $keywords) {
                                                echo "<li class='item'><a href='" . SITEURL . "releases/tag/" . $keywords['slug'] . "'>" . $keywords['keyword'] . "</a></li>";
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php  } ?>
                        </div>

                        <div class="row youtubetitle">
                            <?php
                            if (!empty($data['PressYoutube'])) { ?>
                                <div class="col-sm-6">


                                    <h2>Youtube videos</h2>
                                    <div id='yvideoslider' class='owl-carousel owl-theme'>
                                        <?php foreach ($data['PressYoutube'] as $index => $video) {
                                            $videoIfram = $this->Custom->getEmbedCode($video['url']);
                                            echo "<div class='item'>" . $videoIfram . "<p>" . $video['description'] . "</p></div>";
                                        } ?>
                                    </div>

                                </div>
                            <?php } ?>

                            <?php
                            if (!empty($data['PressPoadcast'])) { ?>
                                <div class="col-sm-6">

                                    <?php
                                    echo "<h2>Poadcasts</h2>";
                                    echo "<div id='poadcastslider' class='owl-carousel owl-theme'>";
                                    foreach ($data['PressPoadcast'] as $index => $poadcast) {
                                        if (!empty($poadcast['url'])) {
                                            echo "<div class='item'>" . $poadcast['url'] . "<p>" . $poadcast['description'] . "</p></div>";
                                        }
                                    }
                                    echo " </div>";

                                    ?>
                                </div>
                            <?php  } ?>
                            <?php if (!empty($data[$model]['iframe_url'])) { ?>
                                <div class="iframe-section col-sm-12">
                                    <iframe width='100%' height='300' src='<?php echo $data[$model]['iframe_url']; ?>' frameborder='0'></iframe>
                                </div>
                            <?php } ?>
                        </div>


                        <?php /*?>
                        <div class="card-body" style="">
                            <div class="row">
                                <div class="col-sm-2 text-center">
                                <h4>Stock ticker </h4>
                                <?php echo (!empty($data[$model]['stock_ticker']))?$data[$model]['stock_ticker']:"<strong class='text-center blank'>-</strong>"; ?>
                                </div>
                                <div class="col-sm-3 text-center">
                                <h4>Release date</h4>
                                    <?php echo date("d F, Y",strtotime($data[$model]['release_date'])); ?>
                                </div>
                                <div class="col-sm-3 text-center">
                                <h4>Is translate the content</h4>
                                    <?php echo ($data[$model]['translated_page']=="1")?"<span class='text-success'>Yes</span>":"<span class='text-danger'>No</span>"; ?>
                                </div>
                                <div class="col-sm-4 text-center">
                                <h4>Iframe url</h4>
                                <?php echo (!empty($data[$model]['iframe_url']))? "<a target='_blank' href='".$data[$model]['iframe_url']."'>".$data[$model]['iframe_url']."</a>" :"<strong class='text-center blank'>-</strong>"; ?> 
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2 text-center">
                                <h4>Company</h4>
                                <?php echo (!empty($data['Company']['name']))?ucfirst($data['Company']['name']):"<strong class='text-center blank'>-</strong>"; ?>
                                </div>

                                <div class="col-sm-3 text-center">
                                <h4>Contact name</h4>
                                <?php echo (!empty($data[$model]['contact_name']))?$data[$model]['contact_name']:"<strong class='text-center blank'>-</strong>"; ?>
                                </div>

                                <div class="col-sm-3 text-center">
                                <h4>Contact email</h4>
                                <?php echo (!empty($data[$model]['email']))?$data[$model]['email']:"<strong class='text-center blank'>-</strong>"; ?>
                                </div>
                                <div class="col-sm-2 text-center">
                                <h4>Contact phone</h4>
                                    <?php echo (!empty($data[$model]['phone']))?$data[$model]['phone']:"<strong class='text-center blank'>-</strong>"; ?>
                                </div>
                                <div class="col-sm-2 text-center">
                                <h4>Zip code</h4>
                                    <?php echo (!empty($data[$model]['zip_code']))?$data[$model]['zip_code']:"<strong class='text-center blank'>-</strong>"; ?>
                                </div> 
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            
                        </div> 
                    </div>
                    <div class="row">
                            <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header with-border">
                                <h3 class="card-title">Press seo list</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <?php 
                                        $tableHeaders = $this->Html->tableHeaders(array(
                                            __('#'), 
                                        __("Name"), 
                                        ));
                                    echo $tableHeaders;
                                    ?>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $rows = array();
                                        if(!empty($data['PressSeo'])){
                                            foreach ($data['PressSeo'] AS $index =>$seo) { 
                                            $link="<a title='Click' href='".$seo['urls']."' target='_blank'> <i class='fa fa-link'></i></a>";
                                            $rows[] = array(
                                                    __($index+1),
                                                    __($seo['keyword']), 
                                                );
                                            }
                                            echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                                        }else{
                                            echo '<tr><td align="center" colspan="2">
                                                    <div class="alert alert-dismissable label-default fade in">
                                                        <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                                        No record found.
                                                    </div> 
                                                </td></tr>';
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                </div> 
                            </div> 
                            </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header with-border">
                                <h3 class="card-title">Msa list</h3>
                                </div> 
                                <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <?php 
                                        $tableHeaders = $this->Html->tableHeaders(array(
                                            __('#'), 
                                        __("Name"),
                                        ));
                                    echo $tableHeaders;
                                    ?>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $rows = array();
                                        if(!empty($data['Msa'])){
                                            foreach ($data['Msa'] AS $index =>$msa) { 
                                            $rows[] = array(
                                                    __($index+1),
                                                    __($msa['name']),
                                                );
                                            }
                                            echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                                        }else{
                                            echo '<tr><td align="center" colspan="2">
                                                    <div class="alert alert-dismissable label-default fade in">
                                                        <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                                        No record found.
                                                    </div> 
                                                </td></tr>';
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                </div> 
                            </div>  
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                <h3 class="card-title">Category list</h3>
                                <div class="card-body no-padding">
                                    <table class="table table-bordered">
                                    <thead>
                                    <?php 
                                        $tableHeaders = $this->Html->tableHeaders(array(
                                            __('#'), 
                                            __("Name"),
                                        ));
                                    echo $tableHeaders;
                                    ?>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $rows = array();
                                        if(!empty($data['Category'])){
                                            foreach ($data['Category'] AS $index =>$category) { 
                                            $rows[] = array(
                                                    __($index+1),
                                                    __($category['name']),
                                                );
                                            }
                                            echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                                        }else{
                                            echo '<tr><td align="center" colspan="2">
                                                    <div class="alert alert-dismissable label-default fade in">
                                                        <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                                        No record found.
                                                    </div> 
                                                </td></tr>';
                                        }
                                    ?>
                                        </tbody>
                                    </table>
                                </div> 
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                <h3 class="card-title">State list</h3>
                                <div class="card-body no-padding">
                                    <table class="table table-bordered">
                                    <thead>
                                    <?php 
                                        $tableHeaders = $this->Html->tableHeaders(array(
                                            __('#'), 
                                            __("Name"),
                                        ));
                                    echo $tableHeaders;
                                    ?>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $rows = array();
                                        if(!empty($data['State'])){
                                            foreach ($data['State'] AS $index =>$state) { 
                                            $rows[] = array(
                                                    __($index+1),
                                                    __($state['name']),
                                                );
                                            }
                                            echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                                        }else{
                                            echo '<tr><td align="center" colspan="2">
                                                    <div class="alert alert-dismissable label-default fade in">
                                                        <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                                        No record found.
                                                    </div> 
                                                </td></tr>';
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                </div> 
                            </div> 
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                                <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Additional Features</h3>
                                    <div class="card-body no-padding">
                                        <table class="table table-bordered">
                                            <thead>
                                            <?php 
                                            $tableHeaders = $this->Html->tableHeaders(
                                                array(
                                                    __('#'), 
                                                    __("Name"),
                                                ));
                                            echo $tableHeaders;
                                            ?>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            $rows = array();
                                            if(!empty($data['Distribution'])){
                                                foreach ($data['Distribution'] AS $index =>$distribution) { 
                                                $rows[] = array(
                                                            __($index+1),
                                                            __($distribution['name']),
                                                        );
                                                }
                                                echo $this->Html->tableCells($rows, array('class' => 'gradeX'));
                                            }else{
                                                echo '<tr><td align="center" colspan="2">
                                                        <div class="alert alert-dismissable label-default fade in">
                                                            <h4><i class="icon fa fa-warning"></i> INFORMATION!</h4>
                                                            No record found.
                                                        </div> 
                                                    </td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        
                    <?php */ ?>


                    </div>
                </div>
            </div>
            <?php if ($cartdata['totals']['subtotal'] == 0) { ?>
               <div class="ew-cart-btns-block full col-sm-12">
                  <div class="button_pr col-sm-4 float-left">
                     <div class="buy_now_section">
                           <a href="<?php echo SITEURL . 'users/add-press-release/'.$data[$model]['language'].'/' . $data[$model]['plan_id'] . '/' . $data[$model]['id']; ?>" class="btn orange-btn">Edit Press Release</a>
                     </div>
                  </div>
                  <div class="button_pr col-sm-4 float-right">
                     <div class="buy_now_section">
                           <?php

                          //echo "  ".$this->Form->submit('Checkout and submit PR', array('class' => 'btn orange-btn', 'div' => false));
                           ?>

                           
                              <a href="javascript:void(0)" onclick="submitform('submitwithoutpayment');" class="btn orange-btn">Submit Press Release for Approval</a>
                           
                           <br />


                           <!-- <a href="javascript:void(0)" onclick="submitform('submitwithpayment');" class="btn orange-btn">Submit PR for Approval</a> -->

                     </div>
                  </div>
               </div>
            <?php } ?>
            <?php if ($cartdata['totals']['subtotal'] == 0) { ?>
                <div class="buy_now_section">
                <div class="col-sm-12 text-right">
                    <p class="text-danger"><strong>Once the press release is submitted, it cannot be edited again. To make changes, click on "Edit Press Release".</strong> </p>
                </div>
                </div>
            <?php } ?>
        </div>
    
    <?php if(!empty($cartdata['totals']['subtotal']) && $cartdata['totals']['subtotal'] > 0 ){ ?>
      <div id="sidebar" class="col-lg-3 col-sm-3 col-md-3 customstickysidebar float-left">
         <div class="sidebar__inner">
         <?php  echo $this->element('pr_cart_make_payment');?>
            <div class="buy_now_section">
            <div class="col-sm-12"> 
               <p class="text-danger mt-2"><strong>Once the press release is submitted, it cannot be edited again. To make changes, click on "Edit Press Release".</strong> </p>
            </div> 
               <a href="<?php echo SITEURL . 'users/add-press-release/'.$data[$model]['language'].'/'  . $data[$model]['plan_id'] . '/' . $data[$model]['id']; ?>" class="btn orange-btn">Edit Press Release</a>
            </div>
         </div>
         
      </div>
    <?php } ?>
   <?php echo $this->Form->end(); ?> 
       
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
 <script type="text/javascript">
   $('#primgslider').owlCarousel({
        loop:false,
        margin:0,
        nav:true,
        items:1,
         autoplay:true,
      autoplayTimeout:2000,
       autoHeight:true,
      autoplayHoverPause:true
    });
 </script>

  <script type="text/javascript">


   $('#yvideoslider, #poadcastslider').owlCarousel({
        loop:false,
        margin:0,
        nav:true,
        items:1,
        autoplay:false,
        autoplayHoverPause:true
    });
 function submitform(submittype){
    $("#submittype").val(submittype);
    $("#PressReleaseCartFrom").submit();
}
 </script>
