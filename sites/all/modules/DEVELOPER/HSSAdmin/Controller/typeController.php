<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function indexType(){
	global $base_url;
	
	$_Type = new _Type();
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	$_Module = new _Module();
	$_Module->get_list_field($field='fields', $class='_Type');

	$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
	$status = isset($_GET['status']) ? $_GET['status'] : '';
	
	$totalItem = $_Type->countItem($_fields="id");
	$listItem = $_Type->listItemPost();

	$data = array(
			'title'=>'Quản lý bài viết',
			'listItem' => $listItem,
			'totalItem' =>$totalItem,
			'keyword'=>$keyword,
			'status'=>$status,
	);

	$view = theme('type',$data);
	return $view;
}

function formTypeAction(){
	global $base_url, $user;

	$_Type = new _Type();
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	$_ShowFields = new _ShowFields();
	$listInputForm = $_ShowFields->get_field_show($class = '_Type');
	$fields = clsForm::buildItemFields($listInputForm);
	$data = array('fields'=>$fields);

	//get item update
	$param = arg();
	if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
		$arrOneItem = $_Type->getOne("*", $param[3]);
		foreach ($data['fields'] as $key => $filed) {
			$data['fields'][$key]['value']=$arrOneItem[0]->$key;
		}
	}

	//check post: insert or update
	if(!empty($_POST) && $_POST['txtFormName']=='txtFormName'){
		$require_post = array();

		$data_post = array();
		$data_post['uid ']=$user->uid;
		$data_post['created']=time();
		
		foreach ($data['fields'] as $key => $field) {
			$data_post[$key] = clsForm::itemPostValue($key);
			$data['fields'][$key]['value']=$data_post[$key];

			if(isset($field['require']) && $field['require']=='require' && $data_post[$key]==''){
				$require_post[$key] = t($field['label']).' '.t('không được rỗng!');
			}
		 }

		unset($_POST);
		if(count($require_post)>0){
			foreach ($require_post as $k => $v) {
				form_set_error($k, $v);
			}
			unset($data_post);
		}else{
			if($data['fields']['id']['value']>0){
				$query = $_Type->updateOne($data_post, $data['fields']['id']['value']);
				unset($data_post);
				drupal_set_message('Sửa bài viết thành công.');
				drupal_goto($base_url.'/admincp/type');
			}else{
				$query = $_Type->insertOne($data_post);
				unset($data_post);
				if($query){
					drupal_set_message('Thêm bài viết thành công.');
					drupal_goto($base_url.'/admincp/type');
				}
			}
		}
	}

	$view = theme('type-form',$data);
	return $view;
}

function deleteTypeAction(){
	global $base_url;
	$_Type = new _Type();
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : 0;
		foreach($listId as $item){
			if($item > 0){
				$clsRecycleBin = new RecycleBin();
				$clsRecycleBin->addItem($item, "_Type");
				$query = $_Type->deleteOne($item);
			}
		}
		unset($listId);
		drupal_set_message('Xóa bài viết thành công.');
	}
	drupal_goto($base_url.'/admincp/type');
}