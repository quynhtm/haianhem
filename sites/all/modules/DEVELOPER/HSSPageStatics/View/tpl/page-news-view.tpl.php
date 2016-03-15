<?php global $base_url;?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post blog-view">
				<div class="wrap-main-view">
					<h2 class="title-head text-left"><a title="<?php echo $catName[0]->title;?>" href="<?php echo $base_url.'/'.$catName[0]->title_alias;?>"><?php echo $catName[0]->title;?></a></h2>
					<div class="view-content-post">
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 left-post-view-more">
								<?php foreach($oneItem as $v){
								$title = $v->title;
								$meta_title = $v->meta_title;
								$meta_keyword = $v->meta_keywords;
								$meta_description = $v->meta_description;
								$img='';
								if($v->img != ''){
									$img = $base_url.'/uploads/images/news/'.$v->img;
								}
								$clsSeo = new clsSeo();
								$clsSeo->SEO($title, $img, $meta_title, $meta_keyword, $meta_description);
							?>
							<h1 class="title-view"><?php echo $v->title ?></h1>
							<div class="date-view"><?php echo date('h:i d/m/Y',$v->created)?></div>
							<div class="social-view">
								<div class="like-facebook">
									<div id="fb-root"></div>
									<script>(function(d, s, id) {
									  var js, fjs = d.getElementsByTagName(s)[0];
									  if (d.getElementById(id)) return;
									  js = d.createElement(s); js.id = id;
									  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=1406620156256966";
									  fjs.parentNode.insertBefore(js, fjs);
									}(document, 'script', 'facebook-jssdk'));</script>
									<div class="fb-like" data-href="<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
								</div>
								<div class="social-share">
									<a target="_blank" class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" title="share facebook" rel="nofollow">share facebook</a>
									<a target="_blank" class="google" href="https://plus.google.com/share?url=<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" title="share google" rel="nofollow">share google</a>
									<a target="_blank" class="zing" href="http://link.apps.zing.vn/share?u=<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" title="share zing" rel="nofollow">share zing</a>
								</div>
							</div>
							<div class="intro-view">
								<?php echo $v->intro ?> 
							</div>
							<div class="content-view">
								<?php echo $v->content ?>
							</div>
							<?php } ?>
							<div class="same-post-list">
								<div class="same-view">Bài viết khác:</div>
								<ul class="list-same-post">
									<?php foreach($arrSameItem as $v){?>
									<li><a href="<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" title="<?php echo $v->title?>"><?php echo $v->title?></a> <span>(<?php echo date('h:i d/m/Y',$v->created)?>)</span></li>
									<?php } ?>
								</ul>
							</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
								<div class="row">
									<div class="count-view-post">Sản phẩm bạn có thể quan tâm</div>
									<?php foreach($randomProduct as $v){?>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>