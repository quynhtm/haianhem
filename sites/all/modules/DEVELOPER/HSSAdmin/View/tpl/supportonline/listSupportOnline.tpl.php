
<div class="search-box">
	<div class="wrapp-search-box">
		<div class="search-box-title"><?php echo t('Tìm kiếm bài viết')?>:</div>
		<form action="" method="GET" id="frmSearch" class="frmSearch" name="frmSearch">
			<input type="text" id="keyword" class="keyword" name="keyword" />
			<select class="box-select" name="category">
				<?php foreach ($arrOptionsCategory as $k=>$v){?>
				<option value="<?php echo $k ?>"><?php echo $v ?></option>
				<?php } ?>
			</select>
			<select class="box-select" name="status">
				<option value="">--Chọn trạng thái--</option>
				<option value="0">Ẩn</option>
				<option value="1">Hiện</option>
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
				<a href="<?php echo $base_url; ?>/admincp/supportonline/add" title="Add" class="icon-add"></a>
                <a href="javascript:void(0)" title="Delete" id="deleteMoreItem" class="icon-delete"></a>
           </span>
		</div>
	</div>
	<div class="page-content-box">
		<form id="formListItem"  name="txtForm" action="" method="post">
			<div class="showListItem">
				<table class="table table-bordered table-hover table-striped" width="100%" cellpadding="5" cellspacing="1" border="1">
					<thead>
					<tr>
						<th width="3%" class="text_center">STT</th>
						<th width="3%" class="text_center">Tiêu đề</th>
						<th width="35%" class="text_left">Mobile</th>
						<th width="15%" class="text_center">Skype</th>

						<th width="4%" class="text_center">Yahoo</th>
						<th width="4%" class="text_center">Ngày tạo</th>
						<th width="10%" class="text_center">Vị trí</th>

						<th width="10%" class="text_center">Trạng thái</th>
						<th width="10%" class="text_center">Action</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($result as $key => $item) {?>
					<tr id="ItemType_tr_{$value.id}">
						<td class="text_center text_middle"><?php echo $key+1; ?></td>
						<td class="text_center text_middle"><?php echo $item->title; ?></td>
						<td class="text_center text_middle"><?php echo $item->mobile; ?></td>
						<td class="text_center text_middle"><?php echo $item->skyper; ?></td>
						<td class="text_center text_middle"><?php echo $item->yahoo; ?></td>
						<td class="text_center text_middle"><?php echo date('d-m-Y h:i',$item->created); ?></td>
						<td class="text_center text_middle"><?php echo $item->order_no; ?></td>
						<td class="text_center text_middle">
							<?php
								if($item->status==1){
									$status='<span class="bg-status-show">'.t('Hiện').'</span>';
								}else{
									$status='<span class="bg-status-hidden">'.t('Ẩn').'</span>';
								}
							echo $status;
							?>
						</td>
						<td>
							<?php $linkEdit = $base_url.'/admincp/supportonline/edit/'.$item->id; ?>
							<a class="icon huge" href="<?php echo $linkEdit; ?>"  title="Update Item"><i class="icon-pencil bgLeftIcon"></i></a>
							<a class="icon huge" id="deleteOneItem" href="javascript:void(0)" title="Delete Item"><i class="icon-remove bgLeftIcon"></i></a>
						</td>
					</tr>
					<?php }?>
					</tbody>
				</table>

				<input  type="hidden" name="txtFormName" value="txtFormName"/>
			</div>
		</form>
		<div class="show-bottom-info">
			<div class="total-rows"><?php echo t('Tổng số bài viết')?> <?php echo $totalItem ?></div>
			<div class="list-item-page">
				<div class="showListPage">
					<?php print render($pager); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function(){
		DELETE_ITEM.init('admincp/supportonline');
	});
</script>
