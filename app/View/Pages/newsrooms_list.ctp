<div class="full ew-featured-news-st margin-bottom20">
	<div class="row">
		<div class="col-lg-12">
			<div class="ew-title full"><?php echo $title_for_layout; ?></div>
		</div>
		<?php if (!empty($newsrooms)) { ?>
			<div class="ew-latest-news-st col-lg-12" id="newsroom_list">
				<div class="row">
					<!-- latest news single post -->
					<?php foreach ($newsrooms as $index => $newsroom) { ?>
						<div class="col-sm-3 ew-latest-news-post margin-bottom20">
							<div class="full ew-latest-news-inner">
								<div class="orange-border ew-lastest-news-img-single full">
									<div class="newsroom_inner">
										<?php
										$newsroomUrl = ($newsroom['Company']['status'] == 1) ? SITEURL . 'newsroom/' . $newsroom['Company']['slug'] : "#";
										if ($newsroom['Company']['logo'] != '') {
											echo $this->Html->image(SITEURL . 'files/company/logo/' . $newsroom['Company']['logo_path'] . '/' . $newsroom['Company']['logo'], array('width' => "100%", 'id' => 'prev_logo_image', 'url' => $newsroomUrl));
										} else {
											echo $this->Html->image('no_image.jpeg', array('class' => 'user-image', "id" => "prev_logo_image", "width" => "100%"));
										}
										?>
									</div>
								</div>
								<div class="full ew-lastest-news-single-content">
									<?php echo $this->Post->get_company($newsroom['Company']['name'], $newsroom['Company']['slug'], $newsroom['Company']['status']); ?>
									<div class="prsummary">
										<p><?php echo $this->Post->wordLimitForNewsroom(strip_tags($newsroom['Company']['description']), $newsroomUrl, 12); ?></p>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

				</div>
			</div>
		<?php } ?>
		<?php
		$paginatorInformation = $this->Paginator->params();
		if ($paginatorInformation['pageCount'] > 1) { ?>
			<div class="row" id="paging">
				<?php echo $this->element('pagination'); ?>
			</div>
		<?php } ?>
	</div>
</div>