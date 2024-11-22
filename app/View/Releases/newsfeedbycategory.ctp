<div class="row">
        <div class="col-lg-12"><div class="ew-title full">Subscribe to EmailWire News Releases with Email or Add RSS Content to Your Website</div></div>
              </div>
<p>Subscribe to news release via email or news reeaders, or add news release feeds to your websites or blogs based on <?php  echo $this->Html->link("Companies",'/news-feeds-by-companies', array('class' => 'company-link link')); ?>, <?php  echo $this->Html->link("Categories",'/news-feeds-by-categories', array('class' => 'company-link link')); ?>, U.S. major cities <?php  echo $this->Html->link("(MSA)",'/news-feeds-by-msa', array('class' => 'msa-link link')); ?>  or <?php  echo $this->Html->link("Countries",'/news-feeds-by-countries', array('class' => 'company-link link')); ?>. You have the option to cut a snippent of java code and place it on your website for daily fresh content, or you can use a feed parser to place RSS feeds of our fresh content on your site.

To add company news on your website or to subscribe to company news with news readers, go to <?php  echo $this->Html->link("Companies",'/category', array('class' => 'company-link link')); ?>.
</p> 
<div id="feedfilter-category" class="row">
  	<div id="content" class="col-lg-12 content">
    	<div class="panel panel-default"> 
      		<div class="panel-body">
      			<?php if(!empty($pCategory_list)){ ?>
      			<ul class="list msa-list list-unstyled">
	      			<?php foreach($pCategory_list as $cId => $parentCat) {
	      				$cId=$parentCat['id'];
	      				 $pslug=$parentCat['slug']."&pc=".$cId;

	                     ?>
	                    <li class="list-item <?php echo "parent-cat"; ?>">
	                    <div class="row">
			                <div class="col-sm-6 col-12">
			                	<a href="<?php echo SITEURL.$filterby.'/'.$parentCat['slug']; ?>"><?php echo $parentCat['name'] ?></a>
			                </div>
			                    <div class="col-sm-2 col-4 rss-feed text-right">
				                    <?php 
				                    $url=SITEURL.'rss/category.rss?cat='.$pslug; 
				                  	  echo $this->Html->image('rss2.gif', array('class' =>'feedimg','url' => $url));
				                  	?>
			                    </div>
			                    <div class="col-sm-2 col-4 xml-feed text-right">
				                    <?php 
				                 	  $url=SITEURL.'rss/category.rss?cat='.$pslug.'&c=full'; 
				                 	echo $this->Html->image('press_release_xml.gif', array('class' =>'feedimg','url' => $url));
				                  	?>
			                    </div>
			                    <div class="col-sm-2 col-4 js-feed text-right">
			                      <?php  // $url=SITEURL.'rss/category.rss?cat='.$pslug; 
					               echo $this->Html->image('press_release_javascript.gif', array('class' =>'feedimg',"data-toggle"=>"collapse","data-target"=>"#".$parentCat['slug']));
					                ?>
			                    </div>
			                <div class="col-sm-12">
		                   		 <div  id="<?php echo $parentCat['slug'];?>" class="advertising-textarea collapse">
				                 <?php
				                 $parentSlug=$parentCat['slug'];
				                 $text='<script type="text/javascript">option={ew_style:3,ew_target:"_blank",ew_limit:5,ew_offset:0,ew_pcat:"'.$cId.'"}   </script>
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
		            	<div class="subcategory">
		            		  <?php $subcategory=$this->Custom->getSubCategoryWithSlug($cId); ?>
		            		  <ul>
		            		  	<?php  
		            		  		foreach ($subcategory as $slug => $subcatname) {?>
		            		  			<li class="subcat">
		            		  				<div class="row">
								                <div class="col-sm-6 col-12">
								                	<a href="<?php echo SITEURL.$filterby.'/'.$slug; ?>"><?php echo $subcatname ?></a>
								                </div>												
								               	<div class="col-sm-2 col-4 rss-feed text-right">
									                <?php 
									                $url=SITEURL.'rss/category.rss?cat='.$slug; 
									                echo $this->Html->image('rss2.gif', array('class' =>'feedimg','url' => $url)); 
									                ?>
								                </div>
								                <div class="col-sm-2 col-4 xml-feed text-right">
								                    <?php 
									                $url=SITEURL.'rss/category.rss?cat='.$slug.'&c=full'; 
									                echo $this->Html->image('press_release_xml.gif', array('class' =>'feedimg','url' => $url));
													?>
								                </div>
								                <div class="col-sm-2 col-4 js-feed text-right">
								                      <?php  
										                $url="#";
										                echo $this->Html->image('press_release_javascript.gif', array('class' =>'feedimg',"data-toggle"=>"collapse","data-target"=>"#".$slug));
										               ?>
								                </div>

								                <div class="col-sm-12">
							                   		 <div id="<?php echo $slug;?>" class="advertising-textarea collapse">
									                 <?php

									                 $text='<script type="text/javascript">option={ew_style:3,ew_target:"_blank",ew_limit:5,ew_offset:0,ew_cat:"'.$slug.'"}   </script>
									                  <script type="text/javascript" src="'.SITEURL.'js/prfeed.js"></script>';

									                  echo $this->Form->input('code', array('type'=>'textarea','readonly' => 'readonly','value'=>$text,'label'=>false,'id'=>"code-".$slug));
									              
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