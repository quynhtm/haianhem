<?php 
	global $base_url, $user;
?>
<div id="top">
	<div class="wrapper">
		<div class="logo"></div>
		<div class="right-top">
			<div class="box-menu-click" id="box-menu-click">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<a class="user-logout" href="<?php echo $base_url?>/user/logout" title="<?php echo t('logout')?>"><i></i></a>
			<div class="user-info">
				<img src="<?php echo $base_url.'/'.path_to_theme() ?>/View/img/user.jpg" alt="">
				<span class="name-user"><?php echo $user->name?></span>
			</div>
		</div>
	</div>
</div>