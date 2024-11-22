<div id="main-content" class="row">
    <div id="content" class="col-lg-9 content">
      <div class="panel panel-default"> 
        <div class="panel-body">
          <p>Select the date range to search:</p>
            <?php 
              echo $this->Form->create($model, array("url"=>SITEURL."news-by-date",'type'=>"get",'novalidate' => 'novalidate', 'inputDefaults' => array('autocomplete'=>"off",'div' => 'form-group', 'class' => 'form-control')));

            ?>
                <div class="row">
                    <div class="col-sm-4">
                       <?php echo $this->Form->input('sd',array('type' =>'text','class'=>'cdatepicker form-control','label'=>'From','autocomplete'=>"off" ));?>
                    </div>
                    <div class="col-sm-4">
                       <?php echo $this->Form->input('ed',array('type' =>'text','class'=>'cdatepicker form-control','label'=>'To','autocomplete'=>"off" ));?>
                    </div>
                    <div class="col-sm-2"><label for="PressReleaseSd">&nbsp; </label><?php echo $this->Form->submit('Search by date', array('class' => 'btn btn-info', 'div' => false)); ?></div>
                </div>
                <?php
                 echo $this->Form->end(); ?>    
          </div>
      </div>
  </div>
</div>
<div id="category_page" class="row">
  <div id="content" class="col-lg-9 content ">
    <div class="panel panel-default"> 
      <div class="panel-body">
          <div class="row">
        <div class="col-lg-12"><div class="ew-title full"><?php echo $title;?></div></div>
              </div>

           <?php if(!empty($plan_categories)){ ?>  
          <div class="company-dtails"> 
            <h5 class="box-title">Plan Categories</h5>  
            <ul>
          <?php   foreach ($plan_categories as $index => $plan_category) { ?>    
               
                     <li>
                        <span><a href="<?php echo SITEURL.'plans/'.$plan_category['PlanCategory']['slug'];?>"><?php echo $plan_category['PlanCategory']['name']; ?></a></span>
                     </li>
          <?php } ?>
            </ul>
           </div>
           <div class="ew-title"> &nbsp; </div>
          <?php } ?>    
           
          <?php if(!empty($pages)){ ?>  
          <div class="company-dtails"> 
            <h5 class="box-title">Pages</h5>  
            <ul>
          <?php   foreach ($pages as $index => $page) { ?>    
               
                     <li>
                        <span><a href="<?php echo SITEURL.$page['Page']['slug'];?>"><?php echo $page['Page']['title']; ?></a></span>
                     </li>
          <?php } ?>
            </ul>
           </div>
           <div class="ew-title"> &nbsp; </div>
          <?php } ?>
         <?php 
          if(!empty($data_array)){
            foreach ($data_array as $index => $data) {?>
          <article class="<?php echo $this->Post->classAccordingToLanguage($data[$model]['language']);?>">
              <h4 class="box-title"><a href="<?php echo SITEURL.'release/'.$data[$model]['slug']; ?>"><?php echo ucfirst($data[$model]['title']); ?></a>  </h4>
                  <div class="company-dtails">
                    <?php if($data['Company']['logo']){?>
                     <div class="ew-comany-logo float-left">
                         <div class="newsroom_inner">
                         <?php echo $this->Post->getNewsroomLogo($data['Company']['logo_path'], $data['Company']['logo'], $data['Company']['slug'], $data['Company']['status']);?></div></div>
                    <?php } ?>
                    <div id="prev_company_name" class="ew-compnay float-left">
                    <?php echo $this->Post->get_company($data['Company']['name'], $data['Company']['slug'],$data['Company']['status']); ?></span> - <date itemprop="datePublished" content="yyyy-mm-dd <?php echo date($dateformate, strtotime($data['PressRelease']['release_date'])) ?>"><?php echo date($dateformate, strtotime($data['PressRelease']['release_date'])) ?>
                    
                    </div>  
                </div>

              <?php if(!empty($data[$model]['summary'])){ ?>                
                <div class="summarybox">
                  <?php  if(!empty($data['PressImage'])){ ?>
                    <div class="post_image">
                      <a href="<?php echo SITEURL."release/".$data[$model]['slug'];?>">
                    <?php echo $this->Post->getPrSingleImage($data['PressImage']); ?>
                  </a>
                    </div>
                  <?php } ?>
                  <div class="post_content <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']);?>">
                    <p class="summary"><?php echo $data[$model]['summary']; ?></p>
                  </div>
                </div>
              <?php }else{ ?>
                    <div class="summarybox <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']);?>">
                      <?php  if(!empty($data['PressImage'])){ ?>
                        <div class="post_image">
                          <a href="<?php echo SITEURL."release/".$data[$model]['slug'];?>">
                        <?php echo $this->Post->getPrSingleImage($data['PressImage']); ?>
                      </a>
                        </div>
                      <?php } ?>
                      <div class="post_content">
                        <p class="summary">
                          <?php 
                            $summary  =  strip_tags($data[$model]['body']);
                     
                              echo substr($summary,0,250);
                          ?>
                        </p>
                      </div>
                    </div>
                    
              <?php  } ?>
          </article>
            <?php } 
          }else{?>
            <h5 class="box-title">Press Release</h5>
           <p>Record not found.</p> 
        <?php }?>
        <?php 
        $paginatorInformation = $this->Paginator->params();
        if($paginatorInformation['pageCount']>1){ ?>
            <div class="row" id="paging">
                <?php echo $this->element('pagination'); ?>
            </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="col-lg-3">
    <?php echo $this->element('sidebar'); ?>
  </div>
</div>
<script type="text/javascript">
 $(function () {
    $(".cdatepicker").datepicker({
        dateFormat: "yy-mm-dd",
         maxDate : 0,
        changeMonth: true,
        changeYear: true,
    });
    //   $('.timepicker').timepicker();
});

</script>