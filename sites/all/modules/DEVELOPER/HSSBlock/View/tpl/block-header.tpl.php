<?php 
	global $base_url, $user;
	$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
?>
<div class="link-top-head">
	<div class="container">
		<div class="box-login">
			<a href="<?php echo $base_url.'/huong-dan-mua-hang' ?>" class="link-normal">Hướng dẫn mua hàng</a>
			<?php if($user->uid == 0){?>
			<a href="<?php echo $base_url.'/user' ?>" class="btnLog" rel="nofollow" >Đăng nhập</a>
			<a href="<?php echo $base_url.'/dang-ky' ?>" class="btnLog" rel="nofollow" >Đăng ký</a>
			<?php }else{ ?>
			<a href="<?php echo $base_url.'/thay-doi-thong-tin-ca-nhan' ?>" rel="nofollow" class="btnLog">Cài đặt</a>
			<a href="<?php echo $base_url.'/user/logout' ?>" rel="nofollow" class="btnLog">Thoát</a>
			<?php } ?>
		</div>
	</div>
</div>
<nav class="navbar navbar-inverse">
 	<div class="container">
	    <div class="top-header">	
	    	<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Menu icon</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
	      		<?php if(drupal_is_front_page()){?>
	      		<h1 id="logo"><a href="<?php echo $base_url ?>"><img src="<?php echo $base_url.'/'.path_to_theme()?>/View/img/logo.png" alt="Sản phẩm rẻ đẹp - San pham re dep" /></a></h1>
	      		<?php }else{ ?>
	      		<span id="logo"><a href="<?php echo $base_url ?>"><img src="<?php echo $base_url.'/'.path_to_theme()?>/View/img/logo.png" alt="Sản phẩm rẻ đẹp - San pham re dep" /></a></span>
	      		<?php } ?>
	    	</div>
		    <div class="box-top-header-right">
		    	<div class="search-top-center">
		    		<div class="box-search">
						<form name="frmsearch" id="frmsearch" class="frmsearch" method="GET" action="<?php echo $base_url?>/tim-kiem">
							<input type="text" name="keyword" class="keyword" value="<?php echo $keyword ?>" autocomplete="off"  placeholder="Nhập tên hoặc mã sản phẩm..."/>
							<input type="submit" class="btn-search" value="Tìm kiếm"/>
						</form>
					</div>
					<div class="guide-link">Gợi ý tìm kiếm:
						<a href="" title="">Giày nam</a>,
						<a href="" title="">Áo sơ mi nữ</a>,
						<a href="" title="">Túi xách nữ</a>,
						<a href="" title="">Áo thun nam</a>,...
					</div>
					<div class="phone-call-num">
						<span class="icon-phone-head"><?php echo clsUtility::keyword('SITE_HOTLINE')?></span>
						<span class="time-work">(Thời gian làm việc: 8:00 - 17:30 các ngày trong tuần)</span>
					</div>
		    	</div>
				<a href="<?php echo $base_url ?>/shop-cart" title="Giỏ hàng">
				<div class="box-shop-cart">
					<span class="icon-shop"></span>
					<span class="num-in-cart"><?php if($cartItem>0){ echo $cartItem.' SP'; }else{ ?>Giỏ hàng<?php } ?></span>
				</div>
				</a>
		    </div>
		</div>
	</div>
	 <div class="bottom-header">
	    <div class="container">
		    <div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav menu-header">
					<?php 
						$param = arg();
						$act = '';
						if(isset($param[0])){
							$act = $param[0];
						}
					?>
					<li class="home-focus<?php if($act == 'trang-chu'){ ?> act<?php } ?>"><a title="Trang chủ" href="<?php echo $base_url ?>">Trang chủ</a></li>
					<?php
						foreach($listMenu as $v){
					?>
					<li <?php if($act == $v['title_alias']){ ?>class="act" <?php } ?>>
						<a title="<?php echo $v['title']?>" href="<?php echo $base_url.'/'.$v['title_alias'] ?>"><?php echo $v['title']?></a>
					</li>
					<?php } ?>
					<li <?php if($act == 'tuyen-dai-ly'){ ?>class="act" <?php } ?>><a title="Tuyển đại lý" href="<?php echo $base_url.'/tuyen-dai-ly' ?>">Tuyển đại lý</a></li>
					<li <?php if($act == 'lien-he'){ ?>class="act" <?php } ?>><a title="Liên hệ" href="<?php echo $base_url.'/lien-he' ?>">Liên hệ</a></li>
				</ul>
			</div>
		</div>
	</div>
</nav>
	
