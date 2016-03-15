<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function indexShowfields(){
	global $base_url;
	drupal_goto($base_url.'/admincp/module');
}

function formShowfieldsAction(){
	global $base_url, $user;
	
	$_ShowFields = new _ShowFields();
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	$listInputForm = $_ShowFields->get_field_show($class = '_ShowFields');
	$fields = clsForm::buildItemFields($listInputForm);
	$data = array('fields'=>$fields);
	
	$_Module = new _Module();
	$_Module->get_list_field($field='fields', $class='_ShowFields');

	//check post: insert or update
	$data = array();
	if(!empty($_POST) && $_POST['txtFormName']=='txtFormName'){

		$module = isset($_POST['module']) ? trim($_POST['module']) : '';
		if($module != ''){
			$check_module_field_show = $_ShowFields->getAll($_fields="id", $_cond="module='".$module."'", $_groupby="", $_oderby="id ASC", $_limit="1"); 
			if(count($check_module_field_show) > 0){
				$data_fields = array('fields'=>serialize(array()));
				if(isset($_POST['show'])){
					$data_fields = array(
						'fields'=> serialize($_POST['show']),
					);
					$_ShowFields->updateByCond($data_fields, "module", $module);
					drupal_set_message('Cập nhật thành công.');
					if(isset($_POST['txtSubmitNext']) && $_POST['txtSubmitNext']=='Lưu và tiếp tục'){
						$param = arg();
						drupal_goto($base_url.'/admincp/showfields/edit/list-field/'.$param[4]);
					}
					drupal_goto($base_url.'/admincp/module');
				}else{
					$_ShowFields->updateByCond($data_fields, "module", $module);
					drupal_set_message('Cập nhật thành công.');
					if(isset($_POST['txtSubmitNext']) && $_POST['txtSubmitNext']=='Lưu và tiếp tục'){
						$param = arg();
						drupal_goto($base_url.'/admincp/showfields/edit/list-field/'.$param[4]);
					}
					drupal_goto($base_url.'/admincp/module');
				}
			}else{
				$data_fields = array('show'=>serialize(array()));
				if(isset($_POST['show'])){
					$data_fields = array(
						'module' => $module,
						'fields'=> serialize($_POST['show']),
						'created'=> time(),
						'status'=> 1,
					);
					$_ShowFields->insertOne($data_fields);
					drupal_set_message('Thêm mới thành công.');
					drupal_goto($base_url.'/admincp/module');
				}
			}
		}
	}
	$view = theme('showfields-form', $data);
	return $view;
}

function deleteShowfieldsAction(){
	global $base_url;
	drupal_goto($base_url.'/admincp/module');
}