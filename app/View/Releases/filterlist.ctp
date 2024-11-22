<?php
// echo '<pre>';
// print_r($lists);die;
?>
<div class="row">
        <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout; ?></div></div>
              </div>
<div id="feedfilter-company" class="row">
    <div id="content" class="col-lg-12 content">
      <div class="panel panel-default"> 
          <div class="panel-body">
            <?php if(!empty($lists)){ ?>
            <ul class="list msa-list list-unstyled">
              <?php foreach($lists as $slug => $lists) {
                 $pslug=$slug;

                       ?>
                      <li class="list-item parent-cat">
                      <div class="row">
                      <div class="col-sm-6 col-12">
                        <a href="<?php echo SITEURL.$filterby.'/'.$slug; ?>"><?php echo $lists ?></a>
                      </div>
                          <div class="col-sm-2 col-4 rss-feed text-right">
                            <?php 
                            $url=SITEURL.'rss/company.rss?s='.$pslug; 
                              echo $this->Html->image('rss2.gif', array('class' =>'feedimg','url' => $url));
                            ?>
                          </div>
                          <div class="col-sm-2 col-4 xml-feed text-right">
                            <?php 
                            $url=SITEURL.'rss/company.rss?s='.$pslug.'&c=full';
                          echo $this->Html->image('press_release_xml.gif', array('class' =>'feedimg','url' => $url));
                            ?>
                          </div>
                          <div class="col-sm-2 col-4 js-feed text-right">
                            <?php  // $url=SITEURL.'rss/company.rss?s='.$pslug; 
                         echo $this->Html->image('press_release_javascript.gif', array('class' =>'feedimg',"data-toggle"=>"collapse","data-target"=>"#".$slug));
                          ?>
                          </div>
                      <div class="col-sm-12">
                           <div  id="<?php echo $slug;?>" class="advertising-textarea collapse">
                         <?php
                         $parentSlug=$slug;
                         $text='<script type="text/javascript">option={ew_style:3,ew_target:"_blank",ew_limit:5,ew_offset:0,ew_company:"'.$pslug.'"}   </script>
                          <script type="text/javascript" src="'.SITEURL.'js/prfeed.js"></script>';

                          echo $this->Form->input('code', array('type'=>'textarea','readonly' => 'readonly','value'=>$text,'label'=>false,'id'=>"code-".$parentSlug));
                            
                          ?>
                        <div class="ewtooltip">
                          <button onclick="clipboardcode('<?php echo $parentSlug;?>','copied-<?php echo $parentSlug; ?>');" onmouseout="outInputCode('copied-<?php echo $parentSlug; ?>')">
                          <span class="tooltiptext" id="copied-<?php echo $parentSlug; ?>">Copy to use</span>          Copy code</button>
                        </div>
                        </div>
                        </div>

                      
                  </div>
                  </li>
                <?php } ?>
            </ul>
          <?php }else{?>
            <p class="notfound-err">Record not found.</p> 
          <?php }?>
        </div>
      </div>
  </div>
</div>
<?php   echo $this->Html->script(array('bootbox.min')); ?>