<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/

function indexRecycleBin(){
	global $base_url;
	
	$_RecycleBin = new _RecycleBin();

	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	$_Module = new _Module();
	$_Module->get_list_field($field='fields', $class='_RecycleBin');

	$totalItem = $_RecycleBin->countItem($_fields="id");
	$listItem = $_RecycleBin->listItemPost();

	$data = array(
			'title'=>'Quản lý bài viết',
			'listItem' => $listItem,
			'totalItem' =>$totalItem,
	);

	$view = theme('recyclebin',$data);
	return $view;
}

function deleteRecycleBinAction(){
	global $base_url;
	$_RecycleBin = new _RecycleBin();
	$clsUpload = new clsUpload();

	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : 0;
		foreach($listId as $item){
			if($item > 0){

				$arrName = $_RecycleBin->getByCond("img", "id=$item", "", "", "1");
				$current_path_img='';
				foreach($arrName as $v){
					$current_path_img .= $v->img;
				}
				if($current_path_img!=''){
					$dir = DRUPAL_ROOT.'/'.$clsUpload->path_image_upload.'/recyclebin/'.$current_path_img;
					if(is_file($dir)){
						unlink($dir);
					}
				}
				$query = $_RecycleBin->deleteOne($item);
			}
		}
		unset($listId);
		drupal_set_message('Xóa bài viết thành công.');
	}
	drupal_goto($base_url.'/admincp/recyclebin');
}

function restoreRecyclebinAction(){
	global $base_url;
	$_RecycleBin = new _RecycleBin();
	$clsUpload = new clsUpload();

	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : array();
		foreach($listId as $item){
			if($item > 0){
				$_RecycleBin->restoreItem($item);
				$query = $_RecycleBin->deleteOne($item);
			}
		}
		unset($listId);
		drupal_set_message('Phục hồi bài viết thành công.');
	}
	drupal_goto($base_url.'/admincp/recyclebin');
}