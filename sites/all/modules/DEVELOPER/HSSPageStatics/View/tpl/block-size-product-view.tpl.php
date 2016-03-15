<?php global 
	$base_url; $user;
?>
<?php 
		$size_no = array();
		$num = 0;
		$size = 0;
		foreach($oneItem as $v){
			if($v->size_no != ''){
				$size_no = unserialize($v->size_no);
			}
		}
	?>
<div class="size-product-right">	
	<?php if(count($size_no) > 0){ ?>
	<span class="txt-size">Kích thước:</span>
	<ul class="product-size">
		<?php 
			$i = 0;
			foreach($size_no as $item){ 
				$i++;
				if($i==1){
					$num = $item['no'];
					$size = $item['size'];
				}
		?>
		<li title="<?php if($item['no']>0){?>Còn hàng<?php }else{?>Hết hàng<?php } ?>" class="<?php if($item['no'] == 0){?>disable<?php } ?> in-stock" data-no="<?php echo (int)$item['no'] ?>" data-size="<?php echo $item['size'] ?>"><?php echo $item['size'] ?></li>
		<?php } ?>
	</ul>
	<input type="hidden" name="frmProductSize" id="productSize" value="<?php echo $size ?>" />
	<input type="hidden" name="frmProductNum" id="productNum" value="<?php echo $num ?>" />
	<div id="message-qty">
        <p id="suggestion" class="suggestion">Hiện đang còn <span class="no"><?php echo $num ?></span> sản phẩm</p>
    </div>
	<?php } ?>
</div>