<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function indexContact(){
	global $base_url;

	$_Contact = new _Contact();

	$totalItem = $_Contact->countItem($_fields="id");
	$listItem = $_Contact->listItemPost();

	$data = array(
			'title'=>'Quản lý bài viết',
			'listItem' => $listItem,
			'totalItem' =>$totalItem,
	);

	$view = theme('contact',$data);
	return $view;
}

function formContactAction(){
	global $base_url, $user;

	$clsStdio = new clsStdio();
	$clsUpload = new clsUpload();
	$_Contact = new _Contact();

	$fields = clsForm::buildItemFields($_Contact->listInputForm());
	$data = array('fields'=>$fields);

	//get item update
	$param = arg();
	if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
		$arrOneItem = $_Contact->getOne("*", $param[3]);
		foreach ($data['fields'] as $key => $filed) {
			$data['fields'][$key]['value']=$arrOneItem[0]->$key;
		}
	}

	//img item post current
	//$current_path_img =  $data['fields']['img']['value'];
	$current_path_img =  '';
	if($current_path_img!=''){
		$data['fields']['img']['value'] = '<div class="item-post"><img src="'.$base_url.'/'.$clsUpload->path_image_upload.'/contact/'.$current_path_img.'" /></div>';
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
			$data['fields'][$key]['value']=$data_post[$key];

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
			$name_img = $clsUpload->check_upload_file('txtImg', $current_path_img, $name_module='contact');
			if($name_img!=''){
				$data_post['img'] = $name_img;
			}else{
				unset($data_post['img']);
			}
			if($data['fields']['id']['value']>0){
				$query = $_Contact->updateOne($data_post, $data['fields']['id']['value']);
				unset($data_post);
				drupal_set_message('Sửa bài viết thành công.');
				drupal_goto($base_url.'/admincp/contact');
			}else{
				$query = $_Contact->insertOne($data_post);
				unset($data_post);
				if($query){
					drupal_set_message('Thêm bài viết thành công.');
					drupal_goto($base_url.'/admincp/contact');
				}
			}
		}
	}

	$view = theme('contact-form',$data);
	return $view;
}

function deleteContactAction(){
	global $base_url;
	$_Contact = new _Contact();
	$clsUpload = new clsUpload();

	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : 0;
		foreach($listId as $item){
			if($item > 0){
				
				$clsRecycleBin = new RecycleBin();
				$clsRecycleBin->addItem($item, "_Contact");

				$arrName = $_Contact->getByCond("img", "id=$item", "", "", "1");
				$current_path_img='';
				foreach($arrName as $v){
					$current_path_img .= $v->img;
				}

				if($current_path_img!=''){
					$dir = DRUPAL_ROOT.'/'.$clsUpload->path_image_upload.'/contact/'.$current_path_img;
					if(is_file($dir)){
						unlink($dir);
					}
				}

				$query = $_Contact->deleteOne($item);
			}
		}
		unset($listId);
		drupal_set_message('Xóa bài viết thành công.');

	}
	drupal_goto($base_url.'/admincp/contact');
}