<?php 
/*
* @Created by:
* @Author	: pt.soleil@gmail.com
* @Date 	: 2013
* @Version	: 1.0 
*/
global $base_url;

/*count user*/
function count_list_user(){
	$clsUsers = new Users();
	$total_users = $clsUsers->countItem($_fields="uid", $_cond="uid>1", $_groupby="", $_oderby="uid ASC", $_limit="");
	return $total_users;
}

function count_list_user_not_active(){
	$clsUsers = new Users();
	$total_users_not_active = $clsUsers->countItem($_fields="uid", $_cond="uid>1 AND status=0", $_groupby="", $_oderby="uid ASC", $_limit="");
	return $total_users_not_active;
}
/*count product*/
function count_list_product(){
	$_Type = new _Type();
	$typeid = $_Type->get_list_type_id($type_keyword='group_product', $limit=1);
	$arrListCat = list_name_category(0,$typeid, 0, 'product');
	return $arrListCat;	
}
/*count news*/
function count_list_news(){
	$_Type = new _Type();
	$typeid = $_Type->get_list_type_id($type_keyword='group_news', $limit=1);
	$arrListCat = list_name_category(0,$typeid, 0 , 'news');
	return $arrListCat;	
}

function list_name_category($_catid=0, $type_id=0, $_parent_id=0, $type_name=''){
		
		$clsCategory = new Category();
		$clsProduct = new  Product();
		$clsNews = new News();
		if($type_id > 0){
			$arrListCat = $clsCategory->getAll("id,title", "parent_id=0 AND type_id=$type_id AND status=1", "", "id ASC", "20");
		}else{
			$arrListCat = $clsCategory->getAll("id,title", "parent_id=0 AND status=1", "", "id ASC", "20");
		}
		if($_parent_id>0){
			$arrListCat = $clsCategory->getAll("id,title", "parent_id>0 AND type_id=$type_id AND status=1", "", "id ASC", "20");
		}
		
		$html='';
		foreach($arrListCat as $catid){
			//catid
			$cat_id = $catid->id;
			if($catid->id > 0){
				if($type_name == "product"){
						$total_post_in_cat_num = $clsProduct->countItem($_fields="id", $_cond="catid=$cat_id AND status=1", $_groupby="", $_oderby="id ASC", $_limit="");
				}else{
					$total_post_in_cat_num = $clsNews->countItem($_fields="id", $_cond="catid=$cat_id AND status=1", $_groupby="", $_oderby="id ASC", $_limit="");
				}
				if($total_post_in_cat_num == 0){
					$total_post_in_cat_num = "--";
				}
				$html .= '<div class="category-parent-name"><span class="name-title">'.$catid->title.'</span><span class="num-count-post">'.$total_post_in_cat_num.'</span></div>';
				//sub catid
				$parentS = $catid->id;
				$arrListSubCat = $clsCategory->getAll("id,title", "parent_id=$parentS AND parent_id>0 AND status=1", "", "id ASC", "50");
				foreach($arrListSubCat as $subCatid){
					$subcatid = $subCatid->id;
					if($type_name == "product"){
						$total_post_in_cat = $clsProduct->countItem($_fields="id", $_cond="subcatid=$subcatid AND status=1", $_groupby="", $_oderby="id ASC", $_limit="");
					}else{
						$total_post_in_cat = $clsNews->countItem($_fields="id", $_cond="catid=$subcatid AND status=1", $_groupby="", $_oderby="id ASC", $_limit="");
					}
					
					$html .= '<div class="category-child-name"><span class="name-title">--'.$subCatid->title.'</span><span class="num-count-post">'.$total_post_in_cat.'</span></div>';
				}	
			}	
		}
		return $html;	
	}

	/*count news*/
	function current_date_order(){
		$_Order = new _Order();
		$clsDate = new clsDate();
		$time_from = date('d/m/Y', time());
		$from_time = $clsDate->convertDate($time_from.' 00:00:00');
		$current_time = time();
		
		$arrListOrder = $_Order->getAll("title, phone, total, pname, link", "status=1 AND (created >= $from_time AND created <= $current_time)", "", "id DESC", "500");
		return $arrListOrder;	
	}

	$count_list_user = count_list_user();
	$count_list_user_not_active = count_list_user_not_active();
	$count_list_news= count_list_news();
	$count_list_product = count_list_product();
	$count_list_order = current_date_order();
?>
<div class="wrapper-admin-cpanel">
	<div class="notification-global">
		<div class="title-global">Bảng thống kê</div>
	</div>
	<div class="content-global">
		<div class="wrapp-content-global">
			<div class="list-item-content-global">
				<div class="left-item-content-global">
					<div class="item-content-global">
						<div class="title-item-content-global">Thống kê số thành viên</div>
						<div class="content-item-content-global">
							<div class="item-global-total"><span class="lb-txt-num">Tổng số thành viên:</span> <span class="num-total-txt"><?php echo $count_list_user ?></span></div>
							<div class="item-global-total"><span class="lb-txt-num">Số thành viên bị khóa:</span> <span class="num-total-txt"><?php echo $count_list_user_not_active ?></span></div>
						</div>
					</div>
					<div class="item-content-global">
						<div class="title-item-content-global">Thống kê số sản phẩm</div>
						<div class="content-item-content-global">
							<div class="item-global-total">
								<?php print_r($count_list_product) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="right-item-content-global">
					<div class="item-content-global">
						<div class="title-item-content-global">Thống kê số tin tức chuyên mục</div>
						<div class="content-item-content-global">
							<div class="item-global-total">
								<?php print_r($count_list_news) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="right-item-content-global">
					<div class="item-content-global">
						<div class="title-item-content-global">Thống kê đơn hàng hôm nay</div>
						<div class="content-item-content-global">
							<div class="item-global-total">
								<table width="100%" class="dash_order">
									<tr>
										<th>Người mua</th>
										<th>Điện thoại</th>
										<th>Sản phẩm</th>
										<th>Giá tiền</th>
									</tr>
									<?php foreach($count_list_order as $v){?>
									<tr>
										<td><?php echo $v->title?></td>
										<td><?php echo $v->phone?></td>
										<td><a href="<?php echo $v->link ?>" target="_blank"><?php echo $v->pname ?></a></td>
										<td><?php echo $v->total?></td>
									</tr>
									<?php } ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

