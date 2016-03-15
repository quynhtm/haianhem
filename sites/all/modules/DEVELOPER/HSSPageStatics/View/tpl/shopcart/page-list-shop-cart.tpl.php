<?php global $base_url;?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="main-view-post shopcart">
				<div class="wrap-main-view">
					<h1 class="title-head text-left"><a title="Tất cả các sản phẩm trong giỏ hàng" href="<?php echo $base_url.'/shop-cart'?>">Tất cả các sản phẩm trong giỏ hàng</a></h1>
					<div class="view-content-post">
						<form method="post" action="" name="txtFormShopCart" id="txtFormShopCart">
						<div class="grid-shop-cart">
							<?php echo $html; ?>
						</div>
						</form>
						<div class="list-btn-control">
							<a id="backSell" class="btndefault btn-primary" href="<?php echo $base_url.'/san-pham'?>">Tiếp tục mua hàng</a>
							<?php if(count($listCart) >0){?>
								<a id="updateCart" class="btndefault btn-primary" href="javascript:void(0)">Cập nhật đơn hàng</a>
								<a id="dellAllCart" class="btndefault btn-primary" data="del-all" href="javascript:void(0)">Xóa toàn bộ đơn hàng</a>
								<a id="payCart" class="btndefault btn-primary" href="<?php echo $base_url?>/payment-cart">Gửi đơn hàng</a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>