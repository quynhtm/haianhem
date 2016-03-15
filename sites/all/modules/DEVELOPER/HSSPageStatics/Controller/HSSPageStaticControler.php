<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function pageIntro(){
	drupal_set_title('Giới thiệu');
	$clsSeo = new clsSeo();
	$clsSeo->SEO('Giới thiệu', '', 'Giới thiệu', 'Giới thiệu', 'Giới thiệu');
	$detail = clsUtility::keyword('SITE_INTRO');
	$data = array(
		'detail'=>$detail
	);
	$view = theme("page-intro", $data);
	return $view;
}
function pageDelivery(){
	drupal_set_title('Tuyển đại lý');
	$clsSeo = new clsSeo();
	$clsSeo->SEO('Tuyển đại lý', '', 'Tuyển đại lý', 'Tuyển đại lý', 'Tuyển đại lý');
	$detail = clsUtility::keyword('SITE_DELIVERY');
	$data = array(
		'detail'=>$detail
	);
	$view = theme("page-delivery", $data);
	return $view;
}
function pageGuideBuy(){
	drupal_set_title('Hướng dẫn mua hàng');
	$clsSeo = new clsSeo();
	$clsSeo->SEO('Hướng dẫn mua hàng', '', 'Hướng dẫn mua hàng', 'Hướng dẫn mua hàng', 'Hướng dẫn mua hàng');
	$detail = clsUtility::keyword('SITE_GUIDE_BUY_PAY');
	$data = array(
		'detail'=>$detail
	);
	$view = theme("page-guide-buy", $data);
	return $view;
}
function pageContact(){
	global $base_url;

	drupal_set_title('Liên hệ');
	$clsSeo = new clsSeo();
	$clsSeo->SEO('Liên hệ', '', 'Liên hệ', 'Liên hệ', 'Liên hệ');
	$detail = clsUtility::keyword('SITE_CONTACT');
	$data = array(
		'detail'=>$detail
	);

	if($_POST && $_POST['txtFormName']=='txtFormName'){
		$clsContact = new Contact();
		$txtName 	= isset($_POST['txtName'])		? trim($_POST['txtName']) 	 : '';
		$txtMobile 	= isset($_POST['txtMobile'])	? trim($_POST['txtMobile']) : '';
		$txtMessage = isset($_POST['txtMessage']) 	? $_POST['txtMessage']: '';
		$txtAddress = isset($_POST['txtAddress'])  ? $_POST['txtAddress'] 	 : '';
		$txtFormName = isset($_POST['txtFormName'])  ? $_POST['txtFormName'] : '';
		if($txtFormName=='txtFormName' && $txtName!='' && $txtMobile!='' && $txtMessage!=''){
			$data = array(
				'title' => $txtName,
				'phone' => $txtMobile,
				'content' => $txtMessage,
				'address' => $txtAddress,
				'created' => time(),
				'status' => 1
			);
			
			$query = $clsContact->insertOne($data);
			drupal_set_message('Cảm ơn bạn đã gửi thông tin liên hệ. Chúng tôi sẽ liên hệ trong thời gian sớm nhất!');
			drupal_goto($base_url);
		}else{
			drupal_set_message('Bạn vui lòng nhập các trường có dấu *!');
		}
	}

	$view = theme("page-contact", $data);
	return $view;
}
function loadAjaxVote(){
	global $base_url;
	if(empty($_POST)){
		drupal_goto($base_url);
	}else{
		$star = isset($_POST['vote']) ? intval($_POST['vote']) : 0;
		$pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
		$ip = $_SERVER['REMOTE_ADDR'];

		$data = array(
			'star'=>$star,
			'ip'=>$ip,
			'status'=>1,
			'pid'=>$pid,
		);
		if($pid>0 && $ip!='' && $star>0){
			$clsVote = new Vote();
			$check_vote = $clsVote->check_vote($pid, $ip);
			if(count($check_vote)==0){
				$clsVote->insertOne($data);
				$result = $clsVote->show_vote_result($pid);
				echo $result;
			}else{
				echo 'vote exists';
			}
		}else{
			echo 'vote not ok';
		}
		unset($_POST);
	}
	exit();
}
function pageThanksOrder(){
	global $base_url;
	$pid = isset($_GET['pid']) ? intval(base64_decode(trim($_GET['pid']))) : 0;
	if($pid>0){
		$clsProduct = new Product();
		$oneItem = $clsProduct->getByCond('id', "status=1 AND id=$pid", "", "created DESC", '1');
		if(count($oneItem)>0){
			$view = theme("page-thanks-order");
			return $view;
		}else{
			drupal_goto($base_url);
		}
	}else{
		drupal_goto($base_url);
	}
}