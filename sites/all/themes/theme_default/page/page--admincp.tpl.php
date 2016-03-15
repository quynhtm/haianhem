<?php global $base_url;?>
<script type="text/javascript" src="<?php echo $base_url?>/sites/all/modules/DEVELOPER/HSSCore/View/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $base_url?>/sites/all/modules/DEVELOPER/HSSCore/View/js/ckeditor/config.js"></script>
<div class="pageWrapper">
    <?php if ($page['header']){ ?>
    <div class="headerRegion">
        <?php echo render($page['header']); ?>
    </div>
    <?php } ?><!--end header-->
    <div class="contentRegion">
        <?php if(!isset($messages)) $messages = ''; echo $messages; ?>
        <?php if ($page['left']){ ?>
            <div class="leftRegion">
               <?php echo render($page['left']); ?>
            </div>
        <?php } ?><!--end left-->
        <?php if ($page['right']){ ?>
        <div class="rightRegion">
            <?php echo render($page['right']); ?>
        </div>
        <?php } ?><!--end right-->
        <?php if ($page['content']){ ?>
        <div class="rightRegion">
            <?php  unset($page['content']['system_main']['default_message']); ?>
            <?php echo render($page['content']); ?>
        </div>
        <?php } ?><!--end content-->
    </div><!--end content-->
    <?php if ($page['footer']){ ?>
        <div class="footerRegion">
            <?php echo render($page['footer']); ?>
        </div>
    <?php } ?><!--end footer-->
</div>