<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function indexPermission(){
	global $base_url, $user;
	
	$_Permission = new _Permission();
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	
	$_Module = new _Module();
	$_Module->get_list_field($field='fields', $class='_Permission');

	$totalItem = $_Permission->countItem($_fields="rid");
	$listItem = $_Permission->listItemPost();
	
	$data = array(
			'title'=>'Quản lý nhóm',
			'listItem' => $listItem,
			'totalItem' =>$totalItem,
	);
	$view = theme('permission', $data);
	return $view;
}

function formPermissionAction(){
	global $base_url, $user;
	
	$_Permission = new _Permission();
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	$_ShowFields = new _ShowFields();
	$listInputForm = $_ShowFields->get_field_show($class = '_Permission');
	$fields = clsForm::buildItemFields($listInputForm);
	$data = array('fields'=>$fields);
	
	//get item update
	$param = arg();
	$data['id_check'] = 0;
	
	if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
		$arrOneItem = $_Permission->getOne("*", $param[3]);
		foreach ($data['fields'] as $key => $filed) {
			$data['fields'][$key]['value']=$arrOneItem[0]->$key;
			if($key=='order_no'){
				$data['fields'][$key]['value']=intval($data_post[$key]);
			}
		}
		$data['id_check'] = $param[3];
	}
	//check post: insert or update
	if(!empty($_POST) && $_POST['txtFormName']=='txtFormName'){
		$require_post = array();
		
		$data_post = array();
		
		foreach ($data['fields'] as $key => $field) {
			$data_post[$key] = clsForm::itemPostValue($key);
			if($key == 'weight'){
				$data_post[$key] = (int)$data_post[$key];
			}
			$data['fields'][$key]['value'] = $data_post[$key];
			if(isset($field['require']) && $field['require']=='require' && $data_post[$key]==''){
				$require_post[$key] = t($field['label']).' '.t('không được rỗng!');
			}
		 }

		if(count($require_post)>0){
			foreach ($require_post as $k => $v) {
				form_set_error($k, $v);
			}
			unset($data_post);
		}else{
			if($data['fields']['rid']['value'] > 0){
				$query = $_Permission->updateOne($data_post, $data['fields']['rid']['value']);
				$rid = $data['fields']['rid']['value'];
				$arrPer = $clsPermissionDetail->getAll("id", "rid=$rid", "", "id ASC", "");
				if(count($arrPer)>0){
					$data_permission = array('permission'=>serialize(array()));
					if(isset($_POST['access'])){
						$data_permission = array(
								'permission'=>serialize($_POST['access']),
							);
					}
					$clsPermissionDetail->updateByCond($data_permission, "rid", $rid);
				}else{
					$data_permission = array('permission'=>serialize(array()));
					if(isset($_POST['access'])){
						$data_permission = array(
								'rid'=>$rid,
								'permission'=>serialize($_POST['access']),
							);
					}
					$clsPermissionDetail->insertOne($data_permission);
				}
				drupal_set_message('Sửa bài viết thành công.');
				drupal_goto($base_url.'/admincp/permission');
			}else{
				$id = db_insert($_Permission->table)->fields($data_post)->execute();
				if(isset($_POST['access'])){
					$data_permission = array(
							'rid'=>$id,
							'permission'=>serialize($_POST['access']),
						);
					$clsPermissionDetail->insertOne($data_permission);
				}
				if($id){
					drupal_set_message('Thêm bài viết thành công.');
					drupal_goto($base_url.'/admincp/permission');
				}
			}
		}
	}
	$view = theme('permission-form', $data);
	return $view;
}

function deletePermissionAction(){
	global $base_url,$user;
	
	$_Permission = new _Permission();
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : 0;
		foreach($listId as $item){
			$arrName = $_Permission->getByCond("name", "rid=$item", "", "", "1");
			if($item > 0){
				
				$clsRecycleBin = new RecycleBin();
				$clsRecycleBin->addItem($item, "_Permission");

				$query = $_Permission->deleteOne($item);
				$clsPermissionDetail->deleteByCond("rid", $item);
			}
		}
		unset($listId);
		drupal_set_message('Xóa bài viết thành công.');
	}
	drupal_goto($base_url.'/admincp/permission');
}