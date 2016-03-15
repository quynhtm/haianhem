<?php
	global $base_url;
?>
<div class="search-box">
	<div class="wrapp-search-box">
		<div class="search-box-title"><?php echo t('Tìm kiếm bài viết')?>:</div>
		<form action="" method="GET" id="frmSearch" class="frmSearch" name="frmSearch">
			<input type="text" id="keyword" class="keyword" name="keyword" />
			<select class="box-select" name="status">
				<option value="">--Chọn trạng thái--</option>
				<option value="0">Bị khóa</option>
				<option value="1">Hoạt động</option>
			</select>
			<input type="submit" id="btnSearch" class="btnSearch" value="<?php echo t('Tìm kiếm')?>">
		</form>
	</div>
</div>
<div class="inner-box">
	<div class="page-title-box">
		<div class="wrapper">
			<h5><?php
					if(isset($title)){
						echo $title;
					}else{
						echo t('Quản lý người dùng');
					}
				?></h5>
			<span class="tools">
				<a href="<?php echo $base_url ?>/admincp/users/add" title="Add" class="icon-add"></a>
                <a href="javascript:void(0)" title="Delete" id="deleteMoreItem" class="icon-delete"></a>
           </span>
		</div>
	</div>
	<div class="page-content-box">
		<form id="formListItem"  name="txtForm" action="" method="post">
			<div class="showListItem">
				<?php print render($listItem['table']); ?>
				<input  type="hidden" name="txtFormName" value="txtFormName"/>
			</div>
		</form>
		<div class="show-bottom-info">
			<div class="total-rows"><?php echo t('Tổng số bài viết')?> <?php echo $totalItem ?></div>
			<div class="list-item-page">
				<div class="showListPage">
					<?php print render($listItem['pager']); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function(){
		DELETE_ITEM.init('admincp/users');
	});
</script>
