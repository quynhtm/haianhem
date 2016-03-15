<?php global $base_url; $user?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post">
				<div class="wrap-main-view">
					<h1 class="title-head"><a href="<?php echo $base_url?>/thay-doi-thong-tin-ca-nhan" title="Thay đổi thông tin cá nhân">Thay đổi thông tin cá nhân</a></h1>
					<div class="view-content-static">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<form class="formChangeInfo" method="post" action="" class="form-inline">
									<div class="form-group">
										<label class="control-label">Tên đầy đủ<span>(*)</span></label>
										<input type="text" id="txtFullName" class="form-control" name="txtFullName" value="<?php echo $user->fullname ?>">
									</div>
									<div class="form-group">
										<label class="control-label">Số điện thoại<span>(*)</span></label>
										<input type="text" id="txtMobile" name="txtMobile" class="form-control" value="<?php echo $user->phone ?>">
									</div>
									<div class="form-group">
										<label class="control-label">Email<span>(*)</span></label>
										<input type="text" id="txtEmail" name="txtEmail" class="form-control" value="<?php echo $user->mail ?>">
									</div>
									<div class="form-group">
										<label class="control-label">Địa chỉ<span>(*)</span></label>
										<input type="text" id="txtAddress" name="txtAddress" class="form-control" value="<?php echo $user->address ?>">
									</div>
									 <input type="hidden" name="txtFormName" id="txtFormName" value="txtFormName"/>
									<button type="submit" id="submitChangeInfo" class="btn btn-primary">Thay đổi thông tin</button>
									<button type="reset" id="resetChangeInfo" class="btn btn-default">Làm lại</button>
									<div class="change-info-txt"><a href="<?php echo $base_url?>/thay-doi-mat-khau" title="Thay đổi mật khẩu cá nhân">Thay đổi mật khẩu cá nhân</a></div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>

