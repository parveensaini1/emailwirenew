<div id="category_page" class="row">
  <div id="content" class="col-lg-9 content">
    <div class="panel panel-default"> 
      <div class="panel-body">
          <div class="row">
        <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
              </div>
          <?php
          if(!empty($data_array)){
            foreach ($data_array as $index => $data) {?>
            <article>
              <h4 class="box-title"><a href="<?php echo SITEURL.'release/'.$data[$model]['slug']; ?>"><?php echo ucfirst($data[$model]['title']); ?></a>  </h4>
                  <div class="company-dtails">
                    <?php if($data['Company']['logo']){?>
                     <div class="ew-comany-logo float-left">
                         <div class="newsroom_inner">
                         <?php echo $this->Post->getNewsroomLogo($data['Company']['logo_path'],$data['Company']['logo']);?></div>
                       </div>
                     <?php } ?>
                    <div id="prev_company_name" class="ew-compnay float-left">
                     <?php echo $this->Post->get_company($data['Company']['name'],$data['Company']['slug']); ?> - <span class="release_date"><?php echo date($dateformate,strtotime($data[$model]['release_date']));?></span>    
                    </div>  
                </div>

              <?php if(!empty($data[$model]['summary'])){ ?>                
                <div class="summarybox">
                    <div class="post_image">
                    <?php echo $this->Post->getPrSingleImage($data['PressImage']); ?>
                    </div>
                  <div class="post_content">
                    <p class="summary"><?php echo $data[$model]['summary']; ?></p>
                  </div>
                </div>
              <?php }else{ ?>
                    <div class="summarybox">
                        <div class="post_image">
                        <?php echo $this->Post->getPrSingleImage($data['PressImage']); ?>
                        </div>
                        <?php if(isset($data[$model]['body'])&&!empty($data[$model]['body'])){ ?>
                      <div class="post_content">
                        <p class="summary">
                          <?php 
                              $summary  =  strip_tags($data[$model]['body']);
                              echo substr($summary,0,250);
                          ?>
                        </p>
                      </div>
                    <?php } ?>
                    </div>
                    
              <?php  } ?>
          </article>
            <?php } 
          }else{?>
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