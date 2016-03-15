<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
// $clsCategory = new Category();
// $clsProduct = new Product();
// $arrItem = $clsProduct->getAll("id, subcatid", "", "", "id ASC", "");
// foreach($arrItem as $v){
// 	if($v->id > 0){
// 		$arrCat = $clsCategory->getCatFromID($v->subcatid);
// 		$data = array(
// 			'cat_alias'=>$arrCat[0]->title_alias,
// 			'cat_name'=>$arrCat[0]->title,
// 		);
// 		$query = $clsProduct->updateOne($data, $v->id);
// 	}
// }
function indexProduct(){
	global $base_url;

	$_Category = new _Category();
	$_Product = new _Product();

	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	$_Module = new _Module();
	$_Module->get_list_field($field='fields', $class='_Product');


	$totalItem = $_Product->countItem($_fields="id");
	$listItem = $_Product->listItemPost();

	$arrOptionsCategory[0] = t("Chọn chuyên mục");
	$_Type = new _Type();
	$typeid = $_Type->get_list_type_id($type_keyword='group_product', $limit=1);
	$_Category->makeListCatFromType($typeid, 0, 0, $arrOptionsCategory, 20);

	$data = array(
			'title'=>'Quản lý bài viết',
			'listItem' => $listItem,
			'totalItem' =>$totalItem,
			'arrOptionsCategory'=>$arrOptionsCategory,
	);

	$view = theme('product',$data);
	return $view;
}

function formProductAction(){
	global $base_url, $user;

	$clsStdio = new clsStdio();
	$clsUpload = new clsUpload();
	$_Product = new _Product();
	$ProductImg = new Img();
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	$_ShowFields = new _ShowFields();
	$listInputForm = $_ShowFields->get_field_show($class = '_Product');
	$fields = clsForm::buildItemFields($listInputForm);
	$data = array('fields'=>$fields);

	//get item update
	$param = arg();
	if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
		$arrOneItem = $_Product->getOne("*", $param[3]);
		foreach ($data['fields'] as $key => $filed) {
			if($key!='txtImagesPost'){
				$data['fields'][$key]['value']=$arrOneItem[0]->$key;
			}
		}
		//get all img
		$arrImg = array();
		if(count($arrOneItem)>0){
			$arrImg = $ProductImg->getAll("id, path", "eid=$param[3]", "", "id ASC", 20);
			$data['fields']['txtImagesPost']['value'] = $arrImg;
		}
	}
	//img item post current
	$current_path_img =  $data['fields']['img']['value'];
	if($current_path_img!=''){
		$data['fields']['img']['value'] = '<div class="item-post"><img src="'.$base_url.'/'.$clsUpload->path_image_upload.'/product/'.$current_path_img.'" /></div>';
	}
	//check post: insert or update
	if(!empty($_POST) && $_POST['txtFormName']=='txtFormName'){
		
		$require_post = array();

		$data_post = array();
	
		$data_post['contact ']=$user->name;
		$data_post['created']=time();
		$data_post['view_num'] = 0;

		if(isset($data['fields']['txtImagesPost'])){
			unset($data['fields']['txtImagesPost']);
		}
		foreach ($data['fields'] as $key => $field) {
			$data_post[$key] = clsForm::itemPostValue($key);
			
			if(isset($data['fields'][$key]['type_data']) && $data['fields'][$key]['type_data'] == 'int'){
				$data['fields'][$key]['value'] = $data_post[$key];
				$data_post[$key] = (int)$data_post[$key];
			}elseif(isset($data['fields'][$key]['type_data']) && $data['fields'][$key]['type_data'] == 'float'){
				$data['fields'][$key]['value'] = $data_post[$key];
				$data_post[$key] = (float)$data_post[$key];
			}else{
				$data['fields'][$key]['value'] = $data_post[$key];
			}

			if(isset($field['require']) && $field['require']=='require' && $data_post[$key]==''){
				$require_post[$key] = t($field['label']).' '.t('không được rỗng!');
			}

			if($key=='title'){
				$data_post['title_alias']=$clsStdio->pregReplaceStringAlias(clsForm::itemPostValue('title'));
			}

		 }
		
		if(count($require_post)>0){
			foreach ($require_post as $k => $v) {
				form_set_error($k, $v);
			}
			unset($data_post);
		}else{
			$name_img = $clsUpload->check_upload_file('txtImg', $current_path_img, $name_module='product');
			if($name_img!=''){
				$data_post['img'] = $name_img;
			}else{
				unset($data_post['img']);
			}
			if($data_post['subcatid']){
				$_Category = new _Category();
				$catitem = $_Category->get_one_cat_from_catid($data_post['subcatid']);
				if(count($catitem) > 0){
					foreach ($catitem as $cat) {
						$data_post['cat_name'] = $cat->title;
						$data_post['cat_alias'] = $cat->title_alias;
					}	
				}else{
					$data_post['cat_name'] = '';
					$data_post['cat_alias'] = '';
				}	
			}
			
			if($data_post['uid'] >0 ){
			
			}else{
				$data_post['uid']=$user->uid;
			}
			//begin size
			$size_no = isset($_POST['size']) ? $_POST['size'] : array();
			$num_no = isset($_POST['num']) ? $_POST['num'] : array();
			$arrSize = array();
			//asort($size_no);
			
			if(count($size_no)>0){
				foreach($size_no as $ksize => $kno){
					if($kno == ''){
						unset($size_no[$ksize]);
						unset($num_no[$ksize]);
					}
				}
			}
			foreach ($size_no as $ks=>$vs) {
				foreach ($num_no  as $kn=>$vn) {
					if($ks == $kn){
						$item_size = array(
										'size'=>$vs,
										'no'=>(int)$vn,
									);
						array_push($arrSize, $item_size);
					}
				}
			}

			$arrSize = serialize($arrSize);
			$data_post['size_no'] = $arrSize;
			//end size
			
			if($data['fields']['id']['value']>0){
				
				unset($data_post['created']);
				$query = $_Product->updateOne($data_post, $data['fields']['id']['value']);
				//save img
				$link_file = isset($_POST['link_file']) ? $_POST['link_file'] : array();
				if(count($link_file)>0){
					foreach($link_file as $i){
						$dataImg = array(
							'uid'=>$user->uid,
							'path' =>$i,
							'created' =>time(),
							'status'=>1
						);
						if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
							$dataImg['eid'] = $data['fields']['id']['value'];
						}
						$ProductImg->insertOne($dataImg);
					};
				}

				unset($data_post);
				drupal_set_message('Sửa bài viết thành công.');
				drupal_goto($base_url.'/admincp/product');
			}else{
				//$query = $_Product->insertOne($data_post);
				$id = db_insert($_Product->table)->fields($data_post)->execute();
				//save img
				$link_file = isset($_POST['link_file']) ? $_POST['link_file'] : array();
				if(count($link_file)>0){
					foreach($link_file as $i){
						$dataImg = array(
							'uid'=>$user->uid,
							'eid'=>$id,
							'path' =>$i,
							'created' =>time(),
							'status'=>1
						);
						$ProductImg->insertOne($dataImg);
					};
				}

				unset($data_post);
				if($id){
					drupal_set_message('Thêm bài viết thành công.');
					drupal_goto($base_url.'/admincp/product');
				}
			}
		}
	}

	$view = theme('product-form',$data);
	return $view;
}

function deleteProductAction(){
	global $base_url;
	$_Product = new _Product();
	$clsUpload = new clsUpload();
	$ProductImg = new Img();
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	
	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : 0;
		foreach($listId as $item){
			if($item > 0){
				$clsRecycleBin = new RecycleBin();
				$clsRecycleBin->addItem($item, "_Product", 'product');
				
				$arrName = $_Product->getByCond("img", "id=$item", "", "", "1");
				$current_path_img='';
				foreach($arrName as $v){
					$current_path_img .= $v->img;
				}

				if($current_path_img!=''){
					$dir = DRUPAL_ROOT.'/'.$clsUpload->path_image_upload.'/product/'.$current_path_img;
					if(is_file($dir)){
						unlink($dir);
					}
				}
				$query = $_Product->deleteOne($item);
				//delete all img
				// $arrPath = $ProductImg->getAll("path", "eid=$item", "", "id ASC", "200");
				// if(count($arrPath)>0){
				// 	foreach($arrPath as $v){
				// 		$path = $v->path;
				// 		$dir = DRUPAL_ROOT.'/uploads/images/product/'.$path;
				// 		if($path!='' && is_file($dir)){
				// 			unlink(DRUPAL_ROOT.'/uploads/images/product/'.$path);
				// 		}
				// 	}
				// 	$ProductImg->deleteByCond('eid', $item);
				// }
			}
		}
		unset($listId);
		drupal_set_message('Xóa bài viết thành công.');

	}
	drupal_goto($base_url.'/admincp/product');
}

function uploadFileProductAction(){
	$clsUpload = new clsUpload();
	$data = $clsUpload->upload($_name='txtImagesPost', 
							   $_file_ext='jpg,jpeg,png,gif', 
							   $_max_file_size=10*1024*1024, 
							   $_module='product', 
							   $type_json=1
							   );
	return $data;
}

function deleteFileProductAction(){
	global $base_url;
	if(empty($_POST)){
		drupal_goto($base_url);
	}
	$link = isset($_POST['link']) ? trim($_POST['link']) : '';
	if($link!=''){
		unlink(DRUPAL_ROOT.'/uploads/images/product/'.$link);
	}
	exit();
}

function deleteDBFileProductAction(){
	global $base_url;
	if(empty($_POST)){
		drupal_goto($base_url);
	}
	$link = isset($_POST['link']) ? trim($_POST['link']) : '';
	$id_file = isset($_POST['id_file']) ? $_POST['id_file']: 0;
	if($id_file>0){
		$ProductImg = new Img();
		$ProductImg->deleteOne($id_file);
	}
	$dir = DRUPAL_ROOT.'/uploads/images/product/'.$link;
	if($link!='' && is_file($dir)){
		unlink(DRUPAL_ROOT.'/uploads/images/product/'.$link);
	}
	exit();
}