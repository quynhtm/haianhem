<?php global $base_url;?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post">
				<div class="wrap-main-view">
					<h1 class="title-head"><a href="<?php echo $base_url?>/dang-ky" title="Đăng ký">Đắng ký thành viên</a></h1>
					<div class="view-content-static">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<form class="formSendRegister" method="post" action="" class="form-inline">
									<div class="form-group">
										<label class="control-label">Tên đầy đủ<span>(*)</span></label>
										<input type="text" id="txtFullName" class="form-control" name="txtFullName">
									</div>
									<div class="form-group">
										<label class="control-label">Tên đăng nhập<span>(*)</span></label>
										<input type="text" id="txtName" class="form-control" name="txtName">
									</div>
									<div class="form-group">
										<label class="control-label">Mật khẩu<span>(*)</span></label>
										<input type="password" id="txtPass" class="form-control" name="txtPass">
									</div>
									<div class="form-group">
										<label class="control-label">Nhập lại mật khẩu<span>(*)</span></label>
										<input type="password" id="txtRePass" class="form-control" name="txtRePass">
									</div>
									<div class="form-group">
										<label class="control-label">Số điện thoại<span>(*)</span></label>
										<input type="text" id="txtMobile" name="txtMobile" class="form-control">
									</div>
									<div class="form-group">
										<label class="control-label">Địa chỉ<span>(*)</span></label>
										<input type="text" id="txtAddress" name="txtAddress" class="form-control">
									</div>
									 <input type="hidden" name="txtFormName" id="txtFormName" value="txtFormName"/>
									<button type="submit" id="submitRegister" class="btn btn-primary">Đăng ký</button>
									<button type="reset" id="resetRegister" class="btn btn-default">Làm lại</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>