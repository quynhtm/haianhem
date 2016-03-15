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
        	<a class="" title="<?php echo t('Bảng điều khiển')?>" href="<?php echo $base_url?>/admincp">Bảng điều khiển</a>
        </li>
        <li <?php if($param[1]=='type'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/type">Kiểu nội dung </a>
        </li>
        <li <?php if($param[1]=='category'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/category">Chuyên mục</a>
        </li>
        <li <?php if($param[1]=='product'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/product">Sản phẩm</a>
        </li>
         <li <?php if($param[1]=='order'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/order">Đơn hàng</a>
        </li>
        <li <?php if($param[1]=='news'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/news">Tin tức</a>
        </li>
        <li <?php if($param[1]=='contact'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/contact">Liên hệ</a>
        </li>
        <li <?php if($param[1]=='ads'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/ads">Quảng cáo</a>
        </li>
        <li <?php if($param[1]=='supportonline'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/supportonline">Nick support</a>
        </li>
        <li <?php if($param[1]=='users'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/users">Nhân viên</a>
        </li>
        <li <?php if($param[1]=='permission'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/permission">Phân quyền</a>
        </li>
        <li <?php if($param[1]=='info'){?> class="active" <?php } ?>>
         	<a class="" title="" href="<?php echo $base_url ?>/admincp/info">Cài đặt</a>
        </li>
        <li <?php if($param[1]=='recyclebin'){?> class="active" <?php } ?>>
            <a class="" title="" href="<?php echo $base_url ?>/admincp/recyclebin">Thùng rác</a>
        </li>
    </ul>
</div>