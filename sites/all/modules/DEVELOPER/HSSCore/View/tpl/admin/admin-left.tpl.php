<?php
	global $base_url;
	$param = arg();
	$dashboard = 'class="active"';
	if(count($param)>=2){
		$dashboard='';
	}else{
		$param[1] = '';
	}
?>
<div class="navigation">
	<ul>
        <li <?php echo $dashboard ?>>
        	<a class="" title="<?php echo t('Bảng điều khiển')?>" href="<?php echo $base_url?>/admincp">
        		<i class="icon-dashboard"></i> Bảng điều khiển
        	</a>
        </li>
        <li <?php if($param[1]=='type'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/type">
                <i class="icon-elements"></i> Kiểu nội dung
            </a>
        </li>
        <li <?php if($param[1]=='category'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/category">
                <i class="icon-elements"></i> Chuyên mục
            </a>
        </li>
        <li <?php if($param[1]=='product'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/product">
                <i class="icon-elements"></i> Sản phẩm
            </a>
        </li>
         <li <?php if($param[1]=='order'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/order">
                <i class="icon-elements"></i> Đơn hàng
            </a>
        </li>
        <li <?php if($param[1]=='news'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/news">
                <i class="icon-elements"></i> Tin tức
            </a>
        </li>
        <li <?php if($param[1]=='contact'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/contact">
                <i class="icon-elements"></i> Liên hệ
            </a>
        </li>
        <li <?php if($param[1]=='ads'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/ads">
                <i class="icon-elements"></i> Quảng cáo
            </a>
        </li>
        <li <?php if($param[1]=='supportonline'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/supportonline">
                <i class="icon-elements"></i> Nick support
            </a>
        </li>
        <li <?php if($param[1]=='users'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/users">
                <i class="icon-elements"></i> Nhân viên
            </a>
        </li>
        <li <?php if($param[1]=='permission'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/permission">
                <i class="icon-elements"></i> Phân quyền
            </a>
        </li>
        <li <?php if($param[1]=='info'){?> class="active" <?php } ?>>
         	<a class="" title="" href="<?php echo $base_url ?>/admincp/info">
         		<i class="icon-elements"></i> Cài đặt
         	</a>
        </li>
        <li <?php if($param[1]=='recyclebin'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/recyclebin">
                <i class="icon-elements"></i> Thùng rác
            </a>
        </li>
        <li <?php if($param[1]=='logout'){?> class="active" <?php } ?>>
         	<a class="" title="" href="<?php echo $base_url ?>/admincp/logout">
         		<i class="icon-elements"></i> Thoát
         	</a>
        </li>
    </ul>
</div>