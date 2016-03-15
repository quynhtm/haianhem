<?php 
	global $base_url;
?>
<div class="container">
	<div class="logo-footer">
		<span class="icon-logo"><a href="<?php echo $base_url ?>"><img src="<?php echo $base_url.'/'.path_to_theme()?>/View/img/logo.png" /></a></span>
	</div>
	<div class="address">
		<?php echo clsUtility::keyword('SITE_FOOTER_LEFT')?>
	</div>
	<div class="social-share-footer">
        <div class="div-share">
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
            <div class="fb-like" data-href="<?php echo $base_url ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
        </div>
        <div class="div-share google">
            <script src="https://apis.google.com/js/platform.js" async defer></script>
            <g:plus action="share" annotation="bubble"></g:plus>
            <div class="g-plusone" data-size="medium"></div>
        </div>
    </div>
</div>
<div id="back-top-wrapper" style="display: block;"><span id="back-top"><span></span></span></div>