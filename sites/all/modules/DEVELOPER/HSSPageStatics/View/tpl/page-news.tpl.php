<?php global $base_url;?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post blog">
				<div class="wrap-main-view">
					<h1 class="title-head text-left"><a title="<?php echo $catName[0]->title;?>" href="<?php echo $base_url.'/'.$catName[0]->title_alias;?>"><?php echo $catName[0]->title;?></a></h1>
					<div class="view-content-post">
						<div class="row">
							<?php if(count($arrItem) > 0){?>
							<?php 
								foreach($arrItem as $k => $v){
							?>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="item-product">
									<div class="product-img">
										<a href="<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" title="<?php echo $v->title?>">
											<?php 
												$img='';
												if($v->img!=''){
													$img = "news/".$v->img;
												}else{
													$img = "default.jpg";
												}
												$img = modThumbBase( $img, 429, 300, $alt="$v->title", true, 100, false, "" );
												echo $img;
											?>
										</a>
									</div>
									<div class="product-info">
								        <div class="product-name">
								            <h3><a href="<?php echo $base_url.'/'.$v->cat_alias.'/'.$v->title_alias ?>" title="<?php echo $v->title?>"><?php echo $v->title?></a></h3>
								        </div>
								        <div class="product-desc"><?php echo clsString::substring($v->intro, $length = 180, $replacer='...')?></div>
								        <span class="date">(<?php echo date('d/m/y',$v->created)?>)</span>
								    </div>
								</div>
							</div>
							<?php } ?>
							<?php }else{ ?>
							<div class="updating">Thông tin đang cập nhật...</div>
							<?php } ?>
						</div>
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