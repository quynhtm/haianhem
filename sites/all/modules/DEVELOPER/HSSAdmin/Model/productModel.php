<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class _Product extends Product{

	function listItemPost(){
		global $base_url;

		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$category = isset($_GET['category']) ? $_GET['category'] : '';
		$status = isset($_GET['status']) ? $_GET['status'] : '';

		$header = array(
				array('data' => '<input type="checkbox" id="checkAll"/>'),
				array('field' => 'i.title','data' => t('Tiêu đề')),
				array('field' => 'i.cat_name','data' => t('Danh mục')),
				array('field' => 'i.code','data' => t('Mã')),
				array('field' => 'i.price','data' => t('Giá mới')),
				array('field' => 'i.price_normal','data' => t('Giá cũ')),
				array('field' => 'i.price_input','data' => t('Giá nhập vào')),
				array('field' => 'i.banbuon','data' => t('Bán buôn')),
				array('field' => 'i.focus','data' => t('Hiện ở trang chủ')),
				array('field' => 'i.new','data' => t('Hàng mới về')),
				array('field' => 'i.created','data' => t('Ngày tạo')),
				array('field' => 'i.status','data' => t('Trạng thái')),
				array('data' => t('Action'))
		);

		$sql = db_select($this->table, 'i')->extend('PagerDefault')->extend('TableSort');

		$sql->addField('i', 'cat_name', 'cat_name');
		$sql->addField('i', 'banbuon', 'banbuon');
		$sql->addField('i', 'id', 'id');
		$sql->addField('i', 'title', 'title');
		$sql->addField('i', 'code', 'code');
		$sql->addField('i', 'focus', 'focus');
		$sql->addField('i', 'new', 'new');
		$sql->addField('i', 'status', 'status');
		$sql->addField('i', 'created', 'created');
		$sql->addField('i', 'price', 'price');
		$sql->addField('i', 'price_normal', 'price_normal');
		$sql->addField('i', 'price_input', 'price_input');
		/*search*/
		if($status != ''){
			$sql->condition('i.status', $status, '=');
		}

		if($category > 0){
			$sql->condition('i.catid', $category, '=');
		}

		$db_or = db_or();
		$db_or->condition('i.title', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.title_alias', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.content', '%'.$keyword.'%', 'LIKE');
		$sql->condition($db_or);
		/*end search*/

		if(isset($_GET['sort'])){
			$result = $sql->limit(SITE_RECORD_PER_PAGE)->orderByHeader($header)->execute();

		}else{
			$result = $sql
			->limit(SITE_RECORD_PER_PAGE)->orderBy('i.id', 'DESC')->execute();
		}
		$arrItem = $result->fetchAll();

		//total item
		$totalItem = count($arrItem);
		$rows = array();
		if($totalItem > 0){

			$i=1;
			foreach ($arrItem as $row){
				$row = (object)$row;

				if($row->status==1){
					$status='<span class="bg-status-show">'.t('Hiện').'</span>';
				}else{
					$status='<span class="bg-status-hidden">'.t('Ẩn').'</span>';
				}

				$created = date('d-m-Y h:i',$row->created);

				if($row->focus==0){
					$focus = t('Tin thường');
				}else{
					$focus = '<span class="hot">'.t('Tin nổi bật').'</span>';
				}
				
				if($row->banbuon==0){
					$banbuon = t('Không');
				}else{
					$banbuon = '<span class="hot">'.t('Có').'</span>';
				}

				if($row->new==0){
					$new = t('Không');
				}else{
					$new = '<span class="hot">'.t('Có').'</span>';
				}

				$rows[$i]['data']['checkbox'] = '<input type="checkbox" class="checkItem" name="checkItem[]" value="'.$row->id.'" />';
				$rows[$i]['data']['title'] = $row->title;
				$rows[$i]['data']['cat_name'] = $row->cat_name;
				$rows[$i]['data']['code'] = $row->code;
				$rows[$i]['data']['price'] = number_format((float)$row->price,0).' đ';
				$rows[$i]['data']['price_normal'] = number_format((float)$row->price_normal,0).' đ';
				$rows[$i]['data']['price_input'] = number_format((float)$row->price_input,0).' đ';
				$rows[$i]['data']['provice_name'] = $banbuon;
				$rows[$i]['data']['focus'] =  $focus;
				$rows[$i]['data']['new'] =  $new;
				$rows[$i]['data']['created'] = $created;
				$rows[$i]['data']['status'] =  $status;

				$rows[$i]['data']['action'] = '<a class="icon huge" href="'.$base_url.'/admincp/product/edit/'.$row->id.'"  title="Update Item">
											<i class="icon-pencil bgLeftIcon"></i>
										</a>
										<a class="icon huge" id="deleteOneItem" href="javascript:void(0)" title="Delete Item">
											<i class="icon-remove bgLeftIcon"></i>
										</a>';
				$i++;
			}
		}
		$listItem['table']['tablesort_table'] = array(
				'#theme' => 'table',
				'#header' => $header,
				'#rows' => $rows,
		);
		$listItem['pager'] = array('#theme' => 'pager','#quantity' => SITE_SCROLL_PAGE);

		return  $listItem;
	}

	function listInputForm(){

		$_Category = new _Category();
		
		$arrOptionsCategory[0] = t("--Nhóm--");
		$_Type = new _Type();
		$typeid = $_Type->get_list_type_id($type_keyword='group_product', $limit=1);
		$_Category->makeListCatFromType($typeid, 0, 0, $arrOptionsCategory, 20, $flag=1);

		$arrOptionsSubCategory[0] = t("--Loại--");
		$_Category->makeListCatFromType($typeid, 0, 0, $arrOptionsSubCategory, 200);

		$type_unit = $_Type->get_list_type_id($type_keyword='group_unit', $limit=1);
		$_Category->makeListCatFromType($type_unit, 0, 0, $arrOptionsUnit, 20);

		$arr_fields = array(
				'id'=>array('type'=>'hidden', 'type_data'=>'int', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
				'uid'=>array('type'=>'hidden', 'type_data'=>'int', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
				'catid'=>array('type'=>'option', 'label'=>'Thuộc nhóm', 'value'=>'0','require'=>'require', 'attr'=>'','list_option'=>$arrOptionsCategory),
				'subcatid'=>array('type'=>'option', 'label'=>'Danh mục', 'value'=>'0','require'=>'require', 'attr'=>'','list_option'=>$arrOptionsSubCategory),
				'title'=>array('type'=>'text', 'label'=>'Tiêu đề', 'value'=>'','require'=>'require', 'attr'=>''),
				'code'=>array('type'=>'text', 'label'=>'Mã', 'value'=>'','require'=>'', 'attr'=>''),
				'intro'=>array('type'=>'textarea', 'label'=>'Mô tả', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
				'content'=>array('type'=>'textarea', 'label'=>'Nội dung', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>1),
				'price'=>array('type'=>'text', 'type_data'=>'float', 'label'=>'Giá mới', 'value'=>'','require'=>'require', 'attr'=>''),
				'price_normal'=>array('type'=>'text', 'type_data'=>'float', 'label'=>'Giá cũ', 'value'=>'','require'=>'require', 'attr'=>''),
				'price_input'=>array('type'=>'text', 'type_data'=>'float', 'label'=>'Giá nhập vào', 'value'=>'','require'=>'require', 'attr'=>''),
				'kg'=>array('type'=>'text', 'type_data'=>'float', 'label'=>'Trọng lượng', 'value'=>'','require'=>'', 'attr'=>''),
				'img'=>array('type'=>'file', 'label'=>'Ảnh đại diện', 'value'=>'','require'=>'', 'attr'=>''),
				'txtImagesPost'=>array('type'=>'ajaxUploadMutiImg', 'label'=>'Ảnh slide', 'value'=>array(),'require'=>'', 'attr'=>''),
				'size_no'=>array('type'=>'addMutiSize', 'label'=>'Kích thước', 'value'=>'','require'=>'', 'attr'=>''),
				//'khuyenmai'=>array('type'=>'textarea', 'label'=>'Khuyến mãi', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>1),
				'point'=>array('type'=>'textarea', 'label'=>'Điểm nổi bật', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>1),
				'order_no'=>array('type'=>'text', 'type_data'=>'int', 'label'=>'Số thứ tự', 'value'=>'1','require'=>'', 'attr'=>''),
				'focus'=>array('type'=>'option', 'label'=>'Hiện ở trang chủ', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('không'),'1'=>t('Có'))),
				//'banbuon'=>array('type'=>'option', 'label'=>'Bán buôn', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('không'),'1'=>t('Có'))),
				//'new'=>array('type'=>'option', 'label'=>'Hàng mới về', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('không'),'1'=>t('Có'))),
				'status'=>array('type'=>'option', 'label'=>'Trạng thái', 'value'=>'1', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('Ẩn'),'1'=>t('Hiện'))),
				
				'meta_title'=>array('type'=>'textarea', 'label'=>'Meta title', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
				'meta_keywords'=>array('type'=>'textarea', 'label'=>'Meta keywords', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
				'meta_description'=>array('type'=>'textarea', 'label'=>'Meta description', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
		);

		return $arr_fields;
	}

	function get_dictrict_id($product_id=0){
		$dictrict_id=0;
		if($product_id > 0){
			$arrItem = $this->getAll("dictrict", "id=$product_id", "", "id ASC", 1);
			if(count($arrItem)>0){
				$dictrict_id = intval($arrItem[0]->dictrict);
			}
		}
		return $dictrict_id;
	}
}