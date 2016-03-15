<?php global $base_url;?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post">
				<div class="wrap-main-view">
					<h1 class="title-head"><a href="<?php echo $base_url?>/gioi-thieu" title="Giới thiệu">Giới thiệu về chúng tôi</a></h1>
					<div class="view-content-static">
						<?php 
							if($detail!=''){
								echo $detail;
							}else{
								echo '<div class="post-update">Đang cập nhật...</div>';
							}
						 ?>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>