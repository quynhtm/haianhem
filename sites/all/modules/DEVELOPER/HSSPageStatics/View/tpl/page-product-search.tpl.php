<?php 
	global $base_url;
?>
<div class="container">

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post">
				<div class="wrap-main-view">
					<div class="line-post">
						<div class="content-post-line">
							<h1 class="title-head text-left"><a title="<?php echo $cat_name ?>" href="<?php echo $base_url.'/'.$cat_alias ?>"><?php echo $cat_name ?></a></h1>
							<div class="row">
								<?php if(count($arrItem) > 0){?>
								<?php foreach($arrItem as $v){ ?>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<div class="item-product">
										<div class="product-img">
											 <a href="<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" title="<?php echo $v->title?>">
												<?php 
												$img='';
												if($v->img!=''){
													$img = "product/".$v->img;
													}else{
														$img = "default.jpg";
													}
													$img = modThumbBase( $img, 500, 600, $alt="$v->title", true, 100, false, "" );
													echo $img;
												?>
											</a>
										</div>
										<div class="product-info">
									        <div class="product-name">
									           <a href="<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" title="<?php echo $v->title?>">
						                			<?php echo $v->title?>
						                		</a>
									        </div>
									        <div class="product-price">
									          	<?php if((float)$v->price_normal > (float)$v->price) {?>
									          	<span class="price-sale-close"><?php echo number_format($v->price_normal,0) ?><sup>đ</sup></span>	
									          	<?php } ?>
									          	<span class="price-sale"><?php echo number_format($v->price,0) ?><sup>đ</sup></span>	
									        </div>
									    </div>
										<?php if((float)$v->price_normal > (float)$v->price) {?>
									    <div class="sale-off">
									    	<?php echo number_format(100 - ((float)$v->price/(float)$v->price_normal)*100, 1) ?>
									    </div>
									    <?php } ?>
									</div>
								</div>
								<?php } ?>
								<?php }else{ ?>
								<div class="updating">Sản phẩm đang cập nhật...</div>
								<?php } ?>
							</div>

							<?php if(count($arrItem)>0){ ?>
							<div class="list-page-show front-end">
								<?php echo render($listPage['pager']);?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

