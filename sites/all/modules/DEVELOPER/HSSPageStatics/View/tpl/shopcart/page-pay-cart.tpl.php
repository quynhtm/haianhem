<?php global $base_url; $user?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post shopcart">
				<div class="wrap-main-view">
					<h1 class="title-head"><a title="Thanh toán đơn hàng" href="<?php echo $base_url.'/payment-cart'?>">Gửi đơn hàng</a></h1>
					<div class="view-content-post">
						<form method="post" action="" name="txtFormPaymentCart" id="txtFormPaymentCart" class="txtFormPaymentCart">
							<div class="form-group">
								<label>Họ và tên<span>(*)</span></label>
								<input type="text" id="txtName" class="form-control" name="txtName" value="<?php if($user->uid){ echo $user->fullname; } ?>">
							</div>
							<div class="form-group">
								<label class="control-label">Số điện thoại<span>(*)</span></label>
								<input type="text" id="txtMobile" name="txtMobile" class="form-control" value="<?php if($user->uid){ echo $user->phone; } ?>">
							</div>
							<div class="form-group">
								<label class="control-label">Địa chỉ<span>(*)</span></label>
								<input type="text" id="txtAddress" name="txtAddress" class="form-control" value="<?php if($user->uid){ echo $user->address; } ?>">
							</div>
							<div class="form-group">
								<label>Ghi chú<span>(*)</span></label>
								<textarea  id="txtMessage" class="form-control" rows="5" name="txtMessage"></textarea>
								<span class="des">VD: thời gian nhận hàng...</span>
							</div>
							 <input type="hidden" name="txtFormName" id="txtFormName" value="txtFormName"/>
							<button type="submit" id="submitPaymentOrder" class="btn btn-primary">Gửi đơn hàng</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>