<?php 
	global $base_url, $user;
?>
<div id="top">
	<div class="wrapper">
		<div class="logo"></div>
		<div class="right-top">
			<a class="user-logout" href="<?php echo $base_url?>/user/logout" title="<?php echo t('logout')?>"><i></i></a>
			<div class="user-info">
				<i class="icon-user"></i>
				<span class="name-user"><?php echo $user->name?></span>
			</div>
		</div>
	</div>
</div>