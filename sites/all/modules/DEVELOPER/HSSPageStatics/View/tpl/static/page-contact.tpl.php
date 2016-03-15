<?php global $base_url;?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post">
				<div class="wrap-main-view">
					<h1 class="title-head"><a href="<?php echo $base_url?>/lien-he" title="Liên hệ">Thông tin liên hệ</a></h1>
					<div class="view-content-static">
						<?php 
							if($detail!=''){
								echo $detail;
							}
						 ?>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<a name="feedback"></a>
								<div class="tile-box-head">SanPhamReDep.COM Được Hỗ Trợ Quý Khách</div>
								<form class="formSendContact" method="post" action="" class="form-inline">
									<div class="form-group">
										<label class="control-label">Họ và tên<span>(*)</span></label>
										<input type="text" id="txtName" class="form-control" name="txtName">
									</div>
									<div class="form-group">
										<label class="control-label">Số điện thoại<span>(*)</span></label>
										<input type="text" id="txtMobile" name="txtMobile" class="form-control">
									</div>
									<div class="form-group">
										<label class="control-label">Địa chỉ<span>(*)</span></label>
										<input type="text" id="txtAddress" name="txtAddress" class="form-control">
									</div>
									<div class="form-group">
										<label class="control-label">Nội dung<span>(*)</span></label>
										<textarea  id="txtMessage" class="form-control" rows="5" name="txtMessage"></textarea>
									</div>
									 <input type="hidden" name="txtFormName" id="txtFormName" value="txtFormName"/>
									<button type="submit" id="submitContact" class="btn btn-primary">Gửi đi</button>
								</form>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="tile-box-head">Thông tin liên hệ khác</div>
								<?php 
									$siteAll = clsUtility::keyword_all('SITE_JOIN');
									foreach($siteAll as $v){
								?>
								<div class="address-contact"><?php echo $v->content;?></div>
								<div class="address-contact">
									<?php if($v->img!=''){ ?>
										<img src="<?php echo $base_url."/uploads/images/info/".$v->img;?>" alt="Địa chỉ">
									<?php } ?>
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