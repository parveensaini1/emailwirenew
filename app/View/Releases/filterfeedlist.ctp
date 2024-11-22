<div class="row">
        <div class="col-lg-12"><div class="ew-title full"><?php echo $heading;?></div></div>
              </div>
<p><?php echo $content;?></p>
<div id="feedfilter-category" class="row">
  	<div id="content" class="col-lg-9 content">
    	<div class="panel panel-default"> 
      		<div class="panel-body">
      			<?php if(!empty($lists)){ ?>
      			<ul class="list msa-list">
      			<?php foreach($lists as $slug => $name) {?>
      	         <li class="list-item parent-cat">
                     <div class="row">
                        <div class="col-sm-6 col-12">
							<a href="<?php echo SITEURL.$filterby.'/'.$slug; ?>"><?php echo $name ?></a>
                        </div>
                        <div class="col-sm-2 col-4 rss-feed text-right">
                            <?php 
                          $url=SITEURL.'rss/'.$filterby.'.rss?s='.$slug; 
                          // echo "<a class='rss-link' target='_blank' id='rss-".$slug."' href='$url' onclick='return updaterssfeedurl(event);'>
                          //     <img src='".SITEURL."img/rss2.gif' class='feedimg' alt=''>
                          //  </a>";
                           
                           echo $this->Html->image('rss2.gif', array('class' =>'feedimg','url' => $url));
                          ?>
                        </div>
                        <div class="col-sm-2 col-4 xml-feed text-right">
                            <?php 
                              $url=SITEURL.'rss/'.$filterby.'.rss?s='.$slug.'&c=full'; 

                           //  echo "<a class='rss-link' target='_blank' id='xml-".$slug."' href='$url' onclick='return updaterssfeedurl(event);'>
                           //    <img src='".SITEURL."img/press_release_xml.gif' class='feedimg' alt=''>
                           // </a>";

                          echo $this->Html->image('press_release_xml.gif', array('class' =>'feedimg','url' => $url));
                          ?>
                        </div>
                        <div class="col-sm-2 col-4 js-feed text-right">
                            <?php 
                        // $url=SITEURL.'rss/category.rss?cat='.$slug; 
                            $url="#";
                            echo $this->Html->image('press_release_javascript.gif', array('class' =>'feedimg',"data-toggle"=>"collapse","data-target"=>"#".$slug));
                          ?>
                            </div>
                          <div class="col-sm-12">
                            <div id="<?php echo $slug;?>" class="advertising-textarea collapse">

                         <?php

                         $text='<script type="text/javascript">option={ew_style:3,ew_target:"_blank",ew_limit:5,ew_offset:0,ew_'.$filterby.':"'.$slug.'"}   </script>
                          <script type="text/javascript" src="'.SITEURL.'js/prfeed.js"></script>';

                          echo $this->Form->input('f', array('type'=>'textarea','readonly' => 'readonly','value'=>$text,'label'=>false,'id'=>"code-".$slug)); 
                          ?>
                              <div class="ewtooltip">      
                                  <button onclick="clipboardcode('<?php echo $slug;?>','copied-<?php echo $slug; ?>');"  onmouseout="outInputCode('copied-<?php echo $slug; ?>')">
                                  <span class="tooltiptext" id="copied-<?php echo $slug; ?>">Copy to use</span>
                                  Copy code</button>
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
  <div class="col-lg-3"><?php echo $this->element('sidebar'); ?></div>
</div> 
<?php   echo $this->Html->script(array('bootbox.min')); ?>