<?php 
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0 
*/
function AdminDefault(){
    global $base_url;
    drupal_set_title(t('Trang quản trị'));   
    return '';
}
function blockAdminHeader(){
    $view= theme('admin-header');
    return $view;
}
function blockAdminLeft(){
	$view= theme('admin-left');
    return $view;
}
function blockAdminContent(){
	$view= theme('admin-content');
    return $view;
}
function blockAdminFooter(){
	$view= theme('admin-footer');
    return $view;
}
function check_login(){
	global $user;

	$isLogin = 0;
	if( in_array('Administrator', $user->roles) || in_array('Manager', $user->roles) ){
		$isLogin = 1;
	}else{
		$isLogin = 0;
	}

	return $isLogin;
}
function router_page(){
	global $base_url;
	$param = arg();

	if(check_login()){
		if($param[0]=='admincp'){
			if(count($param)==1){
				$pageAction = 'AdminDefault';
			}elseif(count($param)==2){
				$pageAction = 'index'.ucfirst($param[1]);
			}else{
				if(isset($param[2]) && $param[2]!=''){
					if(in_array($param[2], array('add', 'edit'))){
						$pageAction = 'form'.ucfirst($param[1]).'Action';
					}elseif(in_array($param[2], array('uploadFile', 'deleteFile', 'deleteDBFile', 'uploadFileGift'))){
						$pageAction = $param[2].ucfirst($param[1]).'Action';
					}else{
						$pageAction = $param[2].ucfirst($param[1]).'Action';
					}
				}
			}
			//check funtion exists
			if(function_exists($pageAction)){
				return $pageAction();
			}else{
				drupal_goto($base_url);
			}
		}
	}else{
		drupal_goto($base_url);
	}
}