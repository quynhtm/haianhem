<?php
	global $base_url;
	$checking = isset($_GET['checking']) ? $_GET['checking'] : '';
?>
<div class="search-box">
	<div class="wrapp-search-box">
		<div class="search-box-title"><?php echo t('Tìm kiếm bài viết')?>:</div>
		<form action="" method="GET" id="frmSearch" class="frmSearch" name="frmSearch">
			<input type="text" id="keyword" class="keyword" name="keyword" />
			<select class="box-select" name="checking">
				<option value="">--Chọn trạng thái--</option>
				<?php foreach($arrStatus as $k=>$v){?>
				<option value="<?php echo $k ?>" <?php if($k==$checking){?>selected="selected"<?php } ?>><?php echo $v?></option>
				<?php } ?>
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
						echo t('Quản lý bài viết');
					}
				?></h5>
			<span class="tools">
				<a href="<?php echo $base_url ?>/admincp/order/add" title="Add" class="icon-add"></a>
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
		DELETE_ITEM.init('admincp/order');
	});
</script>

<div id="dialog_comment" style="display:none">
	<div class="content-dialog-view-print box-print-view box-comment">
		<span class="pidComment" rel=""></span>
		<div class="full-item-line">
           	<div class="left-popup-comment detail-product-order">
           		
           	</div>
           	<div class="right-popup-comment">
           		 <div class="list-comment">
	                <ul>
	                   
	                </ul>
	            </div>
	            <div class="box-note-press">
	                <textarea id="frmcomment" name="comment" cols="30" rows="10"></textarea>
			        <div class="txtclickcomment">Click để gửi báo cáo</div>
	            </div>
           	</div>
        </div>
	</div>
</div>
<div id="dialog_update_status" style="display:none">
	<div class="content-dialog-view-print box-print-view box-update_status">
		<span class="pidupdatestatus" rel=""></span>
		<div class="full-item-line">
      		<div class="list-status">
      			<select class="box-select" name="statusUpdate">
					<?php foreach($arrStatus as $k=>$v){?>
					<option value="<?php echo $k ?>"><?php echo $v?></option>
					<?php } ?>
				</select>
      		</div>
            <div class="txtupdatestatus ">Click để cập nhật trạng thái</div>
        </div>
	</div>
</div>
