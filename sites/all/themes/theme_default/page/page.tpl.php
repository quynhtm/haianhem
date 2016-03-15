<div id="wrapper">
	<?php if ($page['header']){ ?>
		<div id="header">
			<?php echo render($page['header']); ?>
		</div>
	<?php } ?>
	<div id="content">
		<div class="wrapper-content">
			<?php if(!isset($messages)) $messages = ''; echo $messages; ?>
			<div <?php if ($page['right']){ ?>class="site-left"<?php }else{ ?> class="site-content"<?php } ?>>
				<?php if ($page['left']){ ?>
					<?php echo render($page['left']); ?>
				<?php } ?>
				<?php if ($page['content']){ ?>
					<?php unset($page['content']['system_main']['default_message']); ?>
			    	<?php echo render($page['content']); ?>
				<?php } ?>
			</div>				
			<?php if ($page['right']){ ?>
				<div class="site-right">
				<?php echo render($page['right']); ?>
				</div>
			<?php } ?>
		</div>	
	</div>
	<?php if ($page['footer']){ ?>
	    <div id="footer">
			<?php echo render($page['footer']); ?>
		</div>
	<?php } ?>
</div>