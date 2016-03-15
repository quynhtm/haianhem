<?php 
	global $base_url, $user;
?>
<label for='message'>Nhập mã bảo vệ:</label>
<input id="security_code" name="security_code" type="text"/>
<img id="img_code" src="<?php echo $base_url?>/captcha?rand=<?php echo rand();?>" />
<span id="refresh_code" onclick="refreshCaptcha();" title="Refresh captcha">new captcha</span>