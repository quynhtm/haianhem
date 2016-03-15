<?php
	global $base_url;
?>
<div class="search-box">
	<div class="wrapp-search-box">
		<div class="search-box-title"><?php echo t('Tìm kiếm bài viết')?>:</div>
		<form action="" method="GET" id="frmSearch" class="frmSearch" name="frmSearch">
			<input type="text" id="keyword" class="keyword" name="keyword" value="<?php echo $keyword?>" />
			<select class="box-select" name="status">
				<option value="" <?php if ($status==''){?>selected="selected"<?php } ?>>--Chọn trạng thái--</option>
				<option value="0" <?php if ($status=='0'){?>selected="selected"<?php } ?>>Ẩn</option>
				<option value="1" <?php if ($status=='1'){?>selected="selected"<?php } ?>>Hiện</option>
			</select>
			<input type="submit" id="btnSearch" class="btnSearch" value="<?php echo t('Tìm kiếm')?>">
		</form>
	</div>
</div>
<div class="inner-box">
	<div class="page-title-box">
		<div class="wrapper">
			<h5>
				<?php
					if(isset($title)){
						echo $title;
					}else{
						echo t('Quản lý bài viết');
					}
				?>
				</h5>
			<span class="tools">
				<a href="<?php echo $base_url ?>/admincp/category/add" title="Add" class="icon-add"></a>
                <a href="javascript:void(0)" title="Delete" id="deleteMoreItem" class="icon-delete"></a>
           </span>
		</div>
	</div>
	<div class="page-content-box">
		<form id="formListItem"  name="txtForm" action="" method="post">
			<div class="showListItem">
				<table class="sticky-enabled tableheader-processed sticky-table">
					<thead>
						<tr>
							<th width="5%"><input type="checkbox" id="checkAll" name="checkAll"></th>
							<?php if(isset($array_fields)){foreach ($array_fields as $field){?>
							<th <?php if(isset($field['attr']) && $field['attr']!=''){echo $field['attr'];}?>><?php echo $field['title'] ?></th>
							<?php } } ?>
						</tr>
					</thead>
					<tbody>
						<?php echo $list_row; unset($list_row);?>
					</tbody>
				</table>
			</div>
			<input type="hidden" value="txtFormName" name="txtFormName">
		</form>
		<div class="show-bottom-info">
			<div class="total-rows"><?php echo t('Tổng số bài viết')?> <?php echo $total_item; unset($total_item)?></div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function(){
		DELETE_ITEM.init('admincp/category');
	});
</script>
