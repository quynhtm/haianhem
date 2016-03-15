<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function page_register(){
	global $base_url, $user;
	if($user->uid != 0){
		drupal_goto($base_url);
	}
	drupal_set_title('Đăng ký thành viên');
	clsSeo::SEO($title='Đăng ký thành viên', $img='', $meta_title='Đăng ký thành viên', $meta_keyword='Đăng ký thành viên', $meta_description='Đăng ký thành viên');
	create_account();
	$view = theme('page-register');
	return $view;
}
function create_account(){
	global $base_url;
	if(isset($_POST['txtFormName'])){
		$clsUsers = new Users();
		$timestamp = REQUEST_TIME;
    	$timestamp = $timestamp.'-'.$_SERVER['SERVER_NAME'];
		
		$txtFullName= isset($_POST['txtFullName'])? trim($_POST['txtFullName'])  : '';
		$txtName= isset($_POST['txtName'])? trim($_POST['txtName'])  : '';
		$txtPass 	= isset($_POST['txtPass']) 	? trim($_POST['txtPass'])  : '';
		$txtRePass 	= isset($_POST['txtRePass'])? trim($_POST['txtRePass'])  : '';
		$txtMobile 	= isset($_POST['txtMobile'])? trim($_POST['txtMobile'])  : '';
		$txtAddress = isset($_POST['txtAddress'])? trim($_POST['txtAddress'])  : '';
		
		$isPhoneNum=0;
		if(preg_match("/^[0-9() -]+$/", $txtMobile)){
		    if (strlen($txtMobile) >= 9 && strlen($txtMobile) <= 20){
				$isPhoneNum = 1;//true;
			}else{
				 drupal_set_message("Số điện thoại không đúng. Vui lòng thử lại");
				 drupal_goto($base_url.'/dang-ky');exit();
			}
		}else{
			drupal_set_message("Số điện thoại không đúng. Vui lòng thử lại");
			drupal_goto($base_url.'/dang-ky');exit();
		}
		
		if($isPhoneNum==1 && strlen($txtPass)>=6 && $txtPass == $txtRePass && $txtName!='' && $txtAddress!=''){
			$checkName = check_name_exists($txtName);
			if($checkName > 0){
				drupal_set_message("Tên đăng nhập đã tồn tại. Vui lòng thử lại!");
				drupal_goto($base_url.'/dang-ky');exit();
			}else{
				$userId = db_next_id(db_query('SELECT MAX(uid) FROM {users}')->fetchField());
				require_once DRUPAL_ROOT . '/' . variable_get('password_inc', 'includes/password.inc');
				$editPassMd5 = user_hash_password(trim($txtPass));
				if($userId){
					$data = array(
								'rid' => 5,
								'uid' => $userId,
								'name' => $txtName,
								'pass' => $editPassMd5,
								'fullname' => $txtFullName,
								'phone' => $txtMobile,
								'address' => $txtAddress,
								'active_code'=>$timestamp,
								'created' => time(),
								'status' => 0,
							);
						
						$sql = $clsUsers->insertOne($data);
						//set roler
						$roleId = 5;
						$sql = db_query("INSERT INTO {users_roles} (uid, rid) VALUES ('$userId','$roleId')");
						
						$timestamp = REQUEST_TIME;
					    $timestamp = base64_encode(strtr($timestamp, '-_', '+/'));
						$linkpath = $base_url.'/dang-ky/kich-hoat-tai-khoan/'.$userId.'/?active_code='.$timestamp;
						
						drupal_set_message('Bạn đã đăng ký thành công. Vui lòng <a href="'.$linkpath.'">nhấn vào đây</a> để kích hoạt!');
						drupal_goto($base_url);
					}
			}
		}else{
			 drupal_set_message("Thông tin bạn nhập chưa đúng. Vui lòng thử lại!");
			 drupal_goto($base_url.'/dang-ky');exit();
		}
		
	}
}
function page_register_actitive(){
	global $user, $base_url;
    
	$code = $_GET['active_code'];
	$decode = '';
	if($code){
	   $decode = base64_decode($code).'-'.$_SERVER['SERVER_NAME'];
	    $checkActiveCode = db_query("SELECT us.active_code FROM {users} AS us WHERE us.active_code = '{$decode}'")->fetchField();
		
		if($checkActiveCode != 'activated'){
			$uid = db_query('SELECT uid FROM {users} WHERE active_code =:active_code ',array(':active_code' => $decode))->fetchField();
			$check = db_update('users')
          			 ->fields(array(
          							'status' => 1,
          							'active_code' => 'activated',
          					 ))
          			->condition('active_code', $decode)
         			->condition('status', '1','<>')
          			->execute();
					
			 if($check){
			 	drupal_set_message(t('Bạn tạo tài khoản thành công!'));
				 drupal_goto($base_url);
			 }else{
			 	 drupal_set_message(t('Tài khoản này đã kích hoạt!'));
				 drupal_goto($base_url);
			 }		
		}	
	}
}
function check_name_exists($name=''){
	$clsUsers = new Users();
	if($name !=''){
		$sql = $clsUsers->getByCond("name", "name = '".$name."'");
		if(count($sql) > 0){
			return 1;
		}
	}
	return 0;
}
function change_info_user(){
	global $base_url, $user;
	if($user->uid==0){
		drupal_goto($base_url.'/user');
		exit();
	}
	clsSeo::SEO($title='Quản lý thông tin cá nhân', $img='', $meta_title='Quản lý thông tin cá nhân', $meta_keyword='Quản lý thông tin cá nhân', $meta_description='Quản lý thông tin cá nhân');
	if(isset($_POST['txtFormName'])){
		$clsUsers = new Users();
		
		$txtEmail 	= isset($_POST['txtEmail'])? trim($_POST['txtEmail'])  : '';
		$txtFormName= isset($_POST['txtFormName'])? trim($_POST['txtFormName'])  : '';
		$txtFullName= isset($_POST['txtFullName'])? trim($_POST['txtFullName'])  : '';
		$txtMobile 	= isset($_POST['txtMobile'])? trim($_POST['txtMobile'])  : '';
		$txtAddress = isset($_POST['txtAddress'])? trim($_POST['txtAddress'])  : '';
		
		$regexEmail = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
		
		if (!preg_match($regexEmail, $txtEmail)) {
		     drupal_set_message("Email không đúng định dạng. Vui lòng thử lại");
			 drupal_goto($base_url.'/dang-ky');exit();
		}

		$phone_ok = 0;
		if(preg_match("/^[0-9() -]+$/", $txtMobile)) {
		  if(strlen($txtMobile) >= 10 && strlen($txtMobile) <= 20){
		  	 $phone_ok = 1;
		  }else{
			drupal_set_message('Bạn vui lòng nhập đúng số điện thoại!');
			unset($_POST);
			drupal_goto($base_url.'/thay-doi-thong-tin-ca-nhan');
		  }
		}else{
			drupal_set_message('Bạn vui lòng nhập đúng số điện thoại!');
			unset($_POST);
			drupal_goto($base_url.'/thay-doi-thong-tin-ca-nhan');
		}
		
		if($txtFormName == 'txtFormName' && $txtMobile!='' && $txtFullName!='' && $phone_ok==1 && $txtAddress!='' && $txtEmail!=''){
			
			$data = array(
				'fullname'=>$txtFullName,
				'phone'=>$txtMobile,
				'address'=>$txtAddress,
				'mail'=>$txtEmail
			);
			
			$clsUsers->updateOne($data, $user->uid);
			drupal_set_message('Bạn đã thay đổi thông tin cá nhân thành công.');
			drupal_goto($base_url.'/thay-doi-thong-tin-ca-nhan');
			
		}else{
			drupal_set_message('Bạn vui lòng nhập đầy đủ thông tin!');
			unset($_POST);
			drupal_goto($base_url.'/thay-doi-thong-tin-ca-nhan');
		}
	}
	$view = theme('change-user-info');
	return $view;
}
function change_pass_user(){
	global $base_url, $user;
	if($user->uid == 0){
		drupal_goto($base_url.'/user');
		exit();
	}
	clsSeo::SEO($title='Thay đổi mật khẩu cá nhân', $img='', $meta_title='Thay đổi mật khẩu cá nhân', $meta_keyword='Thay đổi mật khẩu cá nhân', $meta_description='Thay đổi mật khẩu cá nhân');
	if(isset($_POST['txtFormName'])){
		
		$clsUsers = new Users();
		$txtName 	= isset($_POST['txtName'])? trim($_POST['txtName'])  : '';
		$txtPass 	= isset($_POST['txtPass']) 	? trim($_POST['txtPass'])  : '';
		$txtRePass 	= isset($_POST['txtRePass'])? trim($_POST['txtRePass'])  : '';
		$txtFormName = isset($_POST['txtFormName'])? trim($_POST['txtFormName'])  : '';
		
		//check name phu hop voi user
		$is_user = check_name_of_user($user->uid, $txtName);
		if($is_user > 0){
			if($txtPass!='' && strlen($txtPass)>=6 && $txtPass == $txtRePass && $txtFormName=='txtFormName'){
				require_once DRUPAL_ROOT . '/' . variable_get('password_inc', 'includes/password.inc');
				$editPassMd5 = user_hash_password(trim($txtPass));
				$data = array(
							'pass' => $editPassMd5,
						);
				//update password
				$clsUsers->updateByCond($data, "name",  $txtName);
				drupal_set_message('Bạn đã thay đổi mật khẩu thành công. Vui lòng thoát và đăng nhập lại để kiểm tra!');
				drupal_goto($base_url.'/thay-doi-mat-khau');
			}
		}else{
			drupal_set_message('Bạn nhập thông tin chưa đúng!');
			unset($_POST);
			drupal_goto($base_url.'/thay-doi-mat-khau');
		}
		
	}
	
	$view = theme('change-user-pass');
	return $view;
}
function check_name_of_user($uid, $name){
	$clsUsers = new Users();
	if($uid > 0 && $name!=''){
		$sql = $clsUsers->getByCond("uid", "uid = '".$uid."' AND name='".$name."'");
		if(count($sql) > 0){
			return 1;
		}
	}
	return 0;
}