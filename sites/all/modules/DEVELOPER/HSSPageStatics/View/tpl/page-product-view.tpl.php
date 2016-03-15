<?php global $base_url; $user;
?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post product-view">
				<div class="wrap-main-view">
					<h2 class="title-head text-left"><a title="<?php echo $cat_name;?>" href="<?php echo $base_url.'/'.$cat_alias;?>"><?php echo $cat_name;?></a></h2>
					<div class="view-content-post">
						<div class="box-content-view-more-top">
							<div class="row">
							<?php 
							$pid=0;
							$point = '';
							$promotion = '';
							$content = '';
							$link_share = $base_url;
							foreach($oneItem as $v){
								
								$pid = $v->id;
								$title = $v->title;
								$meta_title = $v->meta_title;
								$meta_keyword = $v->meta_keywords;
								$meta_description = $v->meta_description;
								
								$img='';
								if($v->img != ''){
									$img = $base_url.'/uploads/images/product/'.$v->img;
								}
								$clsSeo = new clsSeo();
								$clsSeo->SEO($title, $img, $meta_title, $meta_keyword, $meta_description);

								$point = $v->point;
								$content = $v->content;

								$link_share = $base_url.'/'.$v->cat_alias.'/'.$v->title_alias;
							?>
								<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
									<div id="gallery" class="view-img-slider">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 item-img-main">
												<div class="img-main-view">
													<?php
														foreach($arrImg as $k=>$item){
														if($k==0){
															$path_check = '';
															if($v->img != ''){
																$path_check = $v->img;
															}else{
																$path_check = $item->img;
															} 
													?>
													<a href="<?php echo $base_url?>/uploads/images/product/<?php echo $path_check ?>" title="<?php echo $title ?>">
														<?php
															$img='';
															if($item->path!=''){
																$img = "product/".$item->path;
															}else{
																$img = "default.png";
															}
															if($v->img != ''){
																$img = modThumbBase( 'product/'.$v->img, 400, 300, $alt="", true, 100, false, "" );
															}else{
																$img = modThumbBase( $img, 400, 300, $alt="", true, 100, false, "" );
															}
															echo $img;
														?>
													</a>
													<?php }
														}
													?>
												</div>
											</div>
											<?php if(count($arrImg)>0){ ?>
											<div class="arr-img">
												<?php foreach($arrImg as $item){ ?>
												<div class="item-one-img-view">
													<a href="<?php echo $base_url?>/uploads/images/product/<?php echo $item->path ?>" title="<?php echo $title ?>" >
														<?php
														if($item->path!=''){
															$img = "product/".$item->path;
															}else{
																$img = "default.png";
															}
															$img = renderThumbCropCenter( $img, 300, 300, $alt="", true, 100, false, "" );
															echo $img;
														?>
													</a>
												</div>
												<?php } ?>
											</div>
											<?php } ?>
											<div class="social-share-view">
										        <div class="div-share">
										            <div id="fb-root"></div>
										            <script>(function(d, s, id) {
										              var js, fjs = d.getElementsByTagName(s)[0];
										              if (d.getElementById(id)) return;
										              js = d.createElement(s); js.id = id;
										              js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
										              fjs.parentNode.insertBefore(js, fjs);
										            }(document, 'script', 'facebook-jssdk'));</script>
										            <div class="fb-like" data-href="<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
										        </div>
										        <div class="div-share google">
										            <script src="https://apis.google.com/js/platform.js" async defer></script>
										            <g:plus action="share" annotation="bubble"></g:plus>
										            <div class="g-plusone" data-size="medium"></div>
										        </div>
										    </div>
										</div>	
									</div>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
									<div class="title-group">
										<h1 class="thread-details-title"><?php echo $v->title ?></h1>
										<?php if($v->intro != ''){?>
										<div class="show-intro"><?php echo $v->intro ?></div>
										<?php } ?>
										<div class="thread-details-price">
											<div class="group-price">
												<div class="item-price">
													<span class="item-price-title">Giá:</span>
													<?php if($v->price_normal >0){?>
													<span class="price-normal"><?php echo number_format(ceil($v->price_normal), 0)?>đ</span>
													<?php } ?>
													<?php if($v->price >0){?>
													<span class="price-red"><?php echo number_format(ceil($v->price), 0)?>đ</span>
													<?php } ?>
												</div>
											</div>
											<div class="code-product">Mã sản phẩm: <span><?php if($v->code != ''){ echo $v->code; }else{ echo '#'.$v->id; } ?></span></div>
											<?php if($v->kg != ''){?>
											<div class="code-product">Trọng lượng: <span><?php echo $v->kg ?> kg</span></div>
											<?php } ?>
										</div>
										
										<?php 
											require_once(DRUPAL_ROOT.'/sites/all/modules/DEVELOPER/HSSPageStatics/View/tpl/block-size-product-view.tpl.php');
										?>
										
										<div class="list-button-view">
											<?php if($cartItem >0 ){ ?>
											<a href="<?php echo $base_url ?>/shop-cart" title="" rel="nofollow"><div class="btn-view-cart btn-primary">Giỏ hàng</div></a>
											<?php } ?>
											<div class="btn-add-cart btn-primary" id="btn-add-cart" data-cart="<?php echo $v->id ?>">Thêm vào giỏ hàng</div>
										</div>
									</div>
									<?php
										$ip = $_SERVER['REMOTE_ADDR'];
										$clsVote = new Vote();
										$check_vote = $clsVote->check_vote($v->id, $ip);
										$star=0;
										foreach($check_vote as $_v){
											$star = $_v->star;
										}
									?>
									<div class="bd-vote-start">
										<div class="vote-star<?php if(count($check_vote)==0){?> activeVote<?php } ?>">
											<a class="<?php if($star>=1){?>act vote<?php }?>" href="javascript:void(0)" rel="1" title="Vote 1 của 5">1</a>
											<a class="<?php if($star>=2){?>act vote<?php }?>" href="javascript:void(0)" rel="2" title="Vote 2 của 5">2</a>
											<a class="<?php if($star>=3){?>act vote<?php }?>" href="javascript:void(0)" rel="3" title="Vote 3 của 5">3</a>
											<a class="<?php if($star>=4){?>act vote<?php }?>" href="javascript:void(0)" rel="4" title="Vote 4 của 5">4</a>
											<a class="<?php if($star==5){?>act vote<?php }?>" href="javascript:void(0)" rel="5" title="Vote 5 của 5">5</a>
										</div>
										<a href="#reviewvote" class="reviewvote">Đánh giá & Nhận xét </a>
										<span style="display:none" id="pid"><?php echo $v->id?></span>
									</div>
								</div>
							<?php } ?>
							</div>
						</div>
						<div class="box-content-view-more">
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 left-post-view-more">
									<div class="conditions-tab">
										<ul class="ul-dieukien-noibat-khuyenmai">
									        <li data="dknb">Điểm nổi bật</li>
									        <li data="dkap">Điều kiện áp dụng</li>
											<li data="kmap" class="selected">Khuyến mãi đang áp dụng</li>
								      </ul>
									</div>
									<div class="dieukien-noibat-view">
										<div class="dknb tab-content-hiden" style="display:none">
											<?php echo $point;?>
										</div>
										<div class="dkap tab-content-hiden" style="display:none">
											<?php echo clsUtility::keyword('SITE_CONDITION')?>
										</div>
										<div class="kmap tab-content-hiden" style="display:block">
											<?php echo clsUtility::keyword('SITE_PROMOTION')?>
										</div>
									</div>
									<div class="view-more-content-product">
										<div class="thread-details-contant">Thông tin chi tiết</div>
										<div class="thread-details-content-view">
											<?php echo $content;?>
										</div>
									</div>
									<div class="box-guide-buy-pay">
										<div class="title-guide-buy-pay">HƯỚNG DẪN MUA HÀNG - NHẬN HÀNG - THANH TOÁN</div>
										<div class="content-guide-buy-pay">
											<div class="wrap-content-guide-buy-pay">
												<?php echo clsUtility::keyword('SITE_GUIDE_BUY_PAY')?>
											</div>
										</div>
										<div class="view-more-guide-buy-pay" name="reviewvote"><?php echo t('Xem thêm')?></div>
									</div>
									<a name="reviewvote"></a>
									<div class="show-resutl-vote">
										<div class="how-vote">Bạn đánh giá sản phẩm này như thế nào?</div>
										<div class="point-vote">
											<span>Đánh giá *</span>
											<div class="vote-star<?php if(count($check_vote)==0){?> activeVote<?php } ?>">
												<a class="<?php if($star>=1){?>act vote<?php }?>" href="javascript:void(0)" rel="1" title="Vote 1 của 5">1</a>
												<a class="<?php if($star>=2){?>act vote<?php }?>" href="javascript:void(0)" rel="2" title="Vote 2 của 5">2</a>
												<a class="<?php if($star>=3){?>act vote<?php }?>" href="javascript:void(0)" rel="3" title="Vote 3 của 5">3</a>
												<a class="<?php if($star>=4){?>act vote<?php }?>" href="javascript:void(0)" rel="4" title="Vote 4 của 5">4</a>
												<a class="<?php if($star==5){?>act vote<?php }?>" href="javascript:void(0)" rel="5" title="Vote 5 của 5">5</a>
											</div>
											<div class="line-vote-show-result">
												<?php
													$result = $clsVote->show_vote_result($pid);
													echo $result;
												?>
											</div>
										</div>
									</div>	
									<div class="social-comment">
										<div class="content-comment-facebook">
											<div class="socialFacebook">
												<div id="fb-root"></div>
												<script>(function(d, s, id) {
												  var js, fjs = d.getElementsByTagName(s)[0];
												  if (d.getElementById(id)) return;
												  js = d.createElement(s); js.id = id;
												  js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1&appId=342626259177944";
												  fjs.parentNode.insertBefore(js, fjs);
												}(document, 'script', 'facebook-jssdk'));</script>
												<div class="fb-comments" data-href="<?php echo $link_share ?>" data-width="500px" data-num-posts="100"></div>

											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="row">
										<div class="count-view-post">Sản phẩm bạn có thể quan tâm</div>
										<?php foreach($arrSameItem as $v){?>
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
											          	<span class="price-sale-close"><?php echo number_format($v->price_normal,0) ?><sup>đ</sup></span>	
											          	<span class="price-sale"><?php echo number_format($v->price,0) ?><sup>đ</sup></span>	
											        </div>
											    </div>
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
</div>
<script>
	jQuery(document).ready(function() {
		jQuery('#gallery').magnificPopup({
			delegate: 'a',
			type: 'image',
			tLoading: 'Loading image...',
			mainClass: 'mfp-img-mobile',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1],
			},
			image: {
				tError: '<a href="%url%">The image</a> could not be loaded.',
				titleSrc: function(item) {
					return item.el.attr('title') + '<small>SanPhamReDep.Com</small>';
				}
			}
		});
	});
</script>