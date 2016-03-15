<div class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <div class="comment-text">
    <div class="comment-arrow"></div>
<?php //if ($new): ?>
    <!--<span class="new">--><?php // print $new; ?><!--</span>-->
  <?php //endif; ?>
    <div class="attribution">
    <?php print $picture; ?>
    <div class="submitted">
      <p class="commenter-name">
        <?php print $comment->name; ?>
      </p>
      <p class="comment-time">
       <?php $custom_date = format_date($node->created, 'custom', 'd.m.Y  H:m');
			print($custom_date);
		?>
      </p>
    </div>
  </div>
    <div class="content"<?php print $content_attributes; ?>>
      <?php
        // We hide the comments and links now so that we can render them later.
        hide($content['links']);
        print render($content);
      ?>      
    <?php //print render($content['links']); ?>
      <?php if ($signature): ?>
      <div class="user-signature clearfix">
        <?php print $signature; ?>
      </div>
      <?php endif; ?>
    </div> <!-- /.content -->
    <?php if($user->uid){ print render($content['links']);}?>
  </div> <!-- /.comment-text -->
</div>



