<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/

function indexCategory(){
	global $base_url;
	
	$clsCategory = new _Category();
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	$_Module = new _Module();
	$_Module->get_list_field($field='fields', $class='_Category');

	
	$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
	$status = isset($_GET['status']) ? $_GET['status'] : '';
	
	$array_fields = array(
			'title'=>array('title'=>'Tiêu đề','attr'=>''),
			'type_id'=>array('title'=>'Kiểu nội dung','attr'=>'style="text-align: center"'),
			'menu'=>array('title'=>'Hiện ở menu ngang','attr'=>'style="text-align: center"'),
			'menu_cat'=>array('title'=>'Hiện ở menu trái slider','attr'=>'style="text-align: center"'),
			'menu_content'=>array('title'=>'Hiện ở content','attr'=>'style="text-align: center"'),
			
			/*'language'=>array('title'=>'Ngôn ngữ','attr'=>'style="text-align: center"'),*/
			'order_no'=>array('title'=>'Thứ tự','attr'=>'style="text-align: center"'),
			'status'=>array('title'=>'Trạng thái','attr'=>'style="text-align: center"'),
			'created'=>array('title'=>'Ngày tạo','attr'=>'style="text-align: center"'),
			'action'=>array('title'=>'Action','attr'=>''),
	);

	$list_row = '';
	$total_item = 0;
	$clsCategory->getListCat(0, 0, $list_row, 100, 'admincp/category/edit', $total_item);
	$data = array(
			'title'=>'Quản lý danh mục',
			'array_fields'=>$array_fields,
			'list_row'=>$list_row,
			'total_item'=>$total_item,
			'keyword'=>$keyword,
			'status'=>$status,
	);

	$view = theme('category',$data);
	return $view;
}

function formCategoryAction(){
	global $base_url, $user;
	
	$clsStdio = new clsStdio();
	$clsCategory = new _Category();
	$clsUpload = new clsUpload();

	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	
	$_ShowFields = new _ShowFields();
	$listInputForm = $_ShowFields->get_field_show($class = '_Category');
	$fields = clsForm::buildItemFields($listInputForm);
	$data = array('fields'=>$fields);

	//get item update
	$param = arg();
	if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
		$arrOneItem = $clsCategory->getOne("*", $param[3]);
		foreach ($data['fields'] as $key => $filed) {
			$data['fields'][$key]['value']=$arrOneItem[0]->$key;
		}
	}

	//img item post current
	$current_path_img =  $data['fields']['img']['value'];
	if($current_path_img!=''){
		$data['fields']['img']['value'] = '<div class="item-post"><img src="'.$base_url.'/'.$clsUpload->path_image_upload.'/category/'.$current_path_img.'" /></div>';
	}

	//check post: insert or update
	if(!empty($_POST) && $_POST['txtFormName']=='txtFormName'){
		$require_post = array();

		$data_post = array();
		$data_post['uid ']=$user->uid;
		$data_post['created']=time();
		$data_post['view_num'] = 0;

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

		unset($_POST);
		if(count($require_post)>0){
			foreach ($require_post as $k => $v) {
				form_set_error($k, $v);
			}
			unset($data_post);
		}else{
			
			$name_img = $clsUpload->check_upload_file('txtImg', $current_path_img, $name_module='category');
			if($name_img!=''){
				$data_post['img'] = $name_img;
			}else{
				unset($data_post['img']);
			}

			if($data['fields']['id']['value']>0){
				$query = $clsCategory->updateOne($data_post, $data['fields']['id']['value']);
				unset($data_post);
				drupal_set_message('Sửa bài viết thành công.');
				drupal_flush_all_caches();
				drupal_goto($base_url.'/admincp/category');
			}else{
				$query = $clsCategory->insertOne($data_post);
				unset($data_post);
				if($query){
					drupal_set_message('Thêm bài viết thành công.');
					drupal_flush_all_caches();
					drupal_goto($base_url.'/admincp/category');
				}
			}
		}
	}


	$view = theme('category-form',$data);
	return $view;
}

function deleteCategoryAction(){
	global $base_url;
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	$clsCategory = new _Category();
	$clsUpload = new clsUpload();

	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : 0;
		foreach($listId as $item){
			if($item > 0){
				$clsRecycleBin = new RecycleBin();
				$clsRecycleBin->addItem($item, "_Category");
					
				$arrName = $clsCategory->getByCond("img", "id=$item", "", "", "1");
				$current_path_img='';
				foreach($arrName as $v){
					$current_path_img .= $v->img;
				}

				if($current_path_img!=''){
					$dir = DRUPAL_ROOT.'/'.$clsUpload->path_image_upload.'/category/'.$current_path_img;
					if(is_file($dir)){
						unlink($dir);
					}
				}

				$query = $clsCategory->deleteOne($item);
			}
		}
		unset($listId);
		drupal_set_message('Xóa bài viết thành công.');
	}
	drupal_goto($base_url.'/admincp/category');
}