<?php global $base_url; $user?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post">
				<div class="wrap-main-view">
					<h1 class="title-head"><a href="<?php echo $base_url?>/thay-doi-mat-khau" title="Thay đổi mật khẩu cá nhân">Thay đổi mật khẩu cá nhân</a></h1>
					<div class="view-content-static">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<form class="formChangeInfo" method="post" action="" class="form-inline">
									<div class="form-group">
										<label class="control-label">Tên đăng nhập<span>(*)</span></label>
										<input type="text" id="txtName" class="form-control" name="txtName" value="<?php echo $user->name ?>">
									</div>
									<div class="form-group">
										<label class="control-label">Mật khẩu mới<span>(*)</span></label>
										<input type="password" id="txtPass" name="txtPass" class="form-control">
									</div>
									<div class="form-group">
										<label class="control-label">Nhập lại mật khẩu mới<span>(*)</span></label>
										<input type="password" id="txtRePass" name="txtRePass" class="form-control">
									</div>
									<input type="hidden" name="txtFormName" id="txtFormName" value="txtFormName"/>
									<button type="submit" id="submitChangePass" class="btn btn-primary">Thay đổi mật khẩu</button>
									<button type="reset" id="resetChangePass" class="btn btn-default">Làm lại</button>
									<div class="change-info-txt"><a href="<?php echo $base_url?>/thay-doi-thong-tin-ca-nhan" title="Thay đổi thông tin cá nhân">Thay đổi thông tin cá nhân</a></div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>