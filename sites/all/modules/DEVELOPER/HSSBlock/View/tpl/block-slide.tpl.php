<?php 
	global $base_url;
?>
<div id="slider-box">
	<div class="container">
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
			<div class="row">
				<div class="box-left-slider">
					<div class="title-cat-menu-left">Danh mục sản phẩm</div>
					<ul class="cat-menu-parent">
						<?php 
							$param = arg();
							$act = '';
							if(isset($param[0])){
								$act = $param[0];
							}
						?>
						<?php
							foreach($listMenu as $v){
						?>
						<li <?php if($act == $v['title_alias']){ ?>class="act" <?php } ?>>
							<a title="<?php echo $v['title']?>" href="<?php echo $base_url.'/'.$v['title_alias'] ?>"><?php echo $v['title']?></a>
							<?php if(isset($v['sub'])){ ?>
							<ul class="cat-submenu">
								<?php foreach($v['sub'] as $s){?>
								<li><a title="<?php echo $s['title']?>" href="<?php echo $base_url.'/'.$s['title_alias'] ?>"><?php echo $s['title']?></a></li>
								<?php } ?>
							</ul>
							<?php } ?>
							<!-- <div class="list-sub-in-cat-parent"></div> -->
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<div class="slider-box-mid theme-default">
				<div class="nivoSlider" id="slider">
					<?php
						foreach($arrItem as $v){
						if(is_file(DRUPAL_ROOT.'/uploads/images/ads/'.$v->img)){
					?>
					<a target="_blank" href="<?php echo $v->link?>" title="<?php echo $v->title_show?>">
						<img  src="<?php echo $base_url.'/uploads/images/ads/'.$v->img ?>" alt="<?php echo $v->title_show?>" />
					</a>
					<?php } 
						}
					?>
				</div>
			</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
			<div class="row">
				<div class="box-right-slider">
					<?php 
						foreach($arrItemRight as $v){
					?>
					<div class="item-right-slider">
						<a target="_blank" class="slide-banner" href="<?php echo $v->link?>" title="<?php echo $v->title_show?>">
							<img width="190" height="220" src="<?php echo $base_url.'/uploads/images/ads/'.$v->img ?>" alt="<?php echo $v->title_show?>" />
						</a>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#slider').nivoSlider();
    });
</script>