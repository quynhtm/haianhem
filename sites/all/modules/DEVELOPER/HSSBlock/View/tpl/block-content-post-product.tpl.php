<?php 
	global $base_url;
?>
<div class="container">	
	<?php $i=0; foreach($arrItem as $v){ $i++; ?>
	<div class="line-post line-<?php echo $i ?>">
		<ul class="product-tab">
			<li><a title="Sản phẩm" href="<?php echo $base_url.'/'.$v['title_alias']?>"><?php echo $v['title']?></a></li>
		</ul>
		<div class="content-post-line">
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
				<div class="row">
					<div class="left-row-post-line">
						<?php if(isset($v['sub'])){ ?>
						
							<?php 
								$sx=0;
								foreach($v['sub'] as $s){
								$sx++;
								if($sx % 2 == 0){
									$ex = 'even item-cat-'.$sx;
								}else{
									$ex = 'odd item-cat-'.$sx;
								}
							?>
							<a class="<?php echo $ex ?>" title="<?php echo $s['title']?>" href="<?php echo $base_url.'/'.$s['title_alias'] ?>">
								<?php 
									$img='';
									if($s['img'] != ''){
										$img = "category/".$s['img'];
									}else{
										$img = "default.jpg";
									}
									$img = modThumbBase( $img, 200, 200, $alt=$s["title"], true, 100, false, "" );
									echo $img;
								?>
								<span><?php echo $s['title']?></span>
							</a>
							<?php } ?>
						
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
				<div class="row">
					<div class="right-row-post-line">
						<?php 
							if(count($v['item'])>0){
							foreach($v['item'] as $item) {
						?>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<div class="item-in-row">
									<div class="thumb">
										<a target="_blank" href="<?php echo $base_url.'/'.$item->cat_alias.'/'.$item->title_alias ?>" title="<?php echo $item->title?>" class="image-wrap">
											<?php 
												$img='';
												if($item->img!=''){
													$img = "product/".$item->img;
												}else{
													$img = "default.jpg";
												}
												$img = modThumbBase( $img, 200, 200, $alt="$item->title", true, 100, false, "" );
												echo $img;
											?>		
										</a>
									</div>
									<div class="title-show-name">
										<a target="_blank" title="" href="<?php echo $base_url.'/'.$item->cat_alias.'/'.$item->title_alias ?>"><?php echo $item->title?></a>
									</div>
									<p>Giá: <label class="text-color-red"><b class="text-price"><?php echo number_format($item->price,0) ?></b>đ</label></p>
								</div>
							</div>
						<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>


