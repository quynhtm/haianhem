<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function indexModule(){
	global $base_url,$user;
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	
	$_Module = new _Module();
	$_Module->get_list_field($field='fields', $class='_Module');
	
	$totalItem = $_Module->countItem($_fields="id");
	$listItem = $_Module->listItemPost();
	
	$data = array(
			'title'=>'Quản lý bài viết',
			'listItem' => $listItem,
			'totalItem' =>$totalItem,
	);

	$view = theme('module',$data);
	return $view;
}

function formModuleAction(){
	global $base_url, $user;
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	$clsStdio = new clsStdio();
	$_Module = new _Module();
	$clsUpload = new clsUpload();
	
	$_ShowFields = new _ShowFields();
	$listInputForm = $_ShowFields->get_field_show($class = '_Module');
	$fields = clsForm::buildItemFields($listInputForm);
	
	$data = array('fields'=>$fields);
	
	//get item update
	$param = arg();
	#not edit
	drupal_goto($base_url.'/admincp/module');

	if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
		$arrOneItem = $_Module->getOne("*", $param[3]);
		
		foreach ($data['fields'] as $key => $filed) {
			$data['fields'][$key]['value']=$arrOneItem[0]->$key;
		}
	}
	
	//check post: insert or update
	if(!empty($_POST) && $_POST['txtFormName']=='txtFormName'){
		$require_post = array();
		
		$data_post = array();
		$data_post['uid '] = $user->uid;
		$data_post['created'] = time();
		
		foreach ($data['fields'] as $key => $field) {
			$data_post[$key] = clsForm::itemPostValue($key);
			$data['fields'][$key]['value']=$data_post[$key];
			if($key == 'order_no'){
				$data['fields'][$key]['value'] = intval($data_post[$key]);
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
			
			if($data['fields']['id']['value']>0){
				unset($data_post['id ']);
				unset($data_post['created ']);
				$query = $_Module->updateOne($data_post, $data['fields']['id']['value']);

				unset($data_post);
				drupal_set_message('Sửa bài viết thành công.');
				drupal_goto($base_url.'/admincp/module');
			}else{
				$query = $_Module->insertOne($data_post);
				unset($data_post);
				if($query){
					drupal_set_message('Thêm bài viết thành công.');
					drupal_goto($base_url.'/admincp/module');
				}
			}
		}
	}
	
	$view = theme('module-form',$data);
	return $view;
}

function deleteModuleAction(){
	global $base_url,$user;
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	
	#not edit
	drupal_goto($base_url.'/admincp/module');

	$_Module = new _Module();
	$clsUpload = new clsUpload();
	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : 0;
		foreach($listId as $item){
			
			$clsRecycleBin = new RecycleBin();
			$clsRecycleBin->addItem($item, "_Module");

			$arrName = $_Module->getByCond("img", "id=$item", "", "", "1");
			$current_path_img='';
			foreach($arrName as $v){
				$current_path_img .= $v->img;
			}
			if($current_path_img!=''){
				$dir = DRUPAL_ROOT.'/'.$clsUpload->path_image_upload.'/module/'.$current_path_img;
				if(is_file($dir)){
					unlink($dir);
				}
			}
			if($item > 0){
				$query = $_Module->deleteOne($item);	
			}
		}
		unset($listId);
		drupal_set_message('Xóa bài viết thành công.');
	}
	drupal_goto($base_url.'/admincp/module');
}