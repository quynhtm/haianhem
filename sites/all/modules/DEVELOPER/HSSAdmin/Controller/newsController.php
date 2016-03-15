<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/

// $clsCategory = new Category();
// $clsNews = new News();
// $arrItem = $clsNews->getAll("id, catid", "", "", "id ASC", "");
// foreach($arrItem as $v){
// 	if($v->id > 0){
// 		$catalias = $clsCategory->getCatAliasFromID($v->catid);
// 		$data = array(
// 			'cat_alias'=>$catalias,
// 		);
// 		$query = $clsNews->updateOne($data, $v->id);
// 	}
// }
function indexNews(){
	global $base_url;

	$_Category = new _Category();
	$_News = new _News();

	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	$_Module = new _Module();
	$_Module->get_list_field($field='fields', $class='_News');
	
	$totalItem = $_News->countItem($_fields="id");
	$listItem = $_News->listItemPost();

	$arrOptionsCategory[0] = t("Chọn chuyên mục");
	$_Type = new _Type();
	$typeid = $_Type->get_list_type_id($type_keyword='group_news', $limit=1);
	$_Category->makeListCatFromType($typeid, 0, 0, $arrOptionsCategory, 20);

	$data = array(
			'title'=>'Quản lý bài viết',
			'listItem' => $listItem,
			'totalItem' =>$totalItem,
			'arrOptionsCategory'=>$arrOptionsCategory,
	);

	$view = theme('news',$data);
	return $view;
}

function formNewsAction(){
	global $base_url, $user;

	$clsStdio = new clsStdio();
	$clsUpload = new clsUpload();
	$_News = new _News();

	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	$_ShowFields = new _ShowFields();
	$listInputForm = $_ShowFields->get_field_show($class = '_News');
	$fields = clsForm::buildItemFields($listInputForm);
	$data = array('fields'=>$fields);

	//get item update
	$param = arg();
	if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
		$arrOneItem = $_News->getOne("*", $param[3]);
		foreach ($data['fields'] as $key => $filed) {
			$data['fields'][$key]['value']=$arrOneItem[0]->$key;
		}
	}

	//img item post current
	$current_path_img =  $data['fields']['img']['value'];
	if($current_path_img!=''){
		$data['fields']['img']['value'] = '<div class="item-post"><img src="'.$base_url.'/'.$clsUpload->path_image_upload.'/news/'.$current_path_img.'" /></div>';
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
			$name_img = $clsUpload->check_upload_file('txtImg', $current_path_img, $name_module='news');
			if($name_img!=''){
				$data_post['img'] = $name_img;
			}else{
				unset($data_post['img']);
			}

			if($data_post['catid']>0){
				$clsCategory = new Category();
				$cat_alias = $clsCategory->getCatAliasFromID($data_post['catid']);
				$data_post['cat_alias'] = $cat_alias;
			}

			if($data['fields']['id']['value']>0){
				$query = $_News->updateOne($data_post, $data['fields']['id']['value']);
				unset($data_post);
				drupal_set_message('Sửa bài viết thành công.');
				drupal_goto($base_url.'/admincp/news');
			}else{
				$query = $_News->insertOne($data_post);
				unset($data_post);
				if($query){
					drupal_set_message('Thêm bài viết thành công.');
					drupal_goto($base_url.'/admincp/news');
				}
			}
		}
	}

	$view = theme('news-form',$data);
	return $view;
}

function deleteNewsAction(){
	global $base_url;
	$_News = new _News();
	$clsUpload = new clsUpload();

	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : 0;
		foreach($listId as $item){
			if($item > 0){

				$clsRecycleBin = new RecycleBin();
				$clsRecycleBin->addItem($item, "_News", 'news');

				$arrName = $_News->getByCond("img", "id=$item", "", "", "1");
				$current_path_img='';
				foreach($arrName as $v){
					$current_path_img .= $v->img;
				}

				if($current_path_img!=''){
					$dir = DRUPAL_ROOT.'/'.$clsUpload->path_image_upload.'/news/'.$current_path_img;
					if(is_file($dir)){
						unlink($dir);
					}
				}

				$query = $_News->deleteOne($item);
			}
		}
		unset($listId);
		drupal_set_message('Xóa bài viết thành công.');

	}
	drupal_goto($base_url.'/admincp/news');
}