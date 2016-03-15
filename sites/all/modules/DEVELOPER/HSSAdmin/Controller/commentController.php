<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function allComment($pid, $view=''){
	global $base_url, $user;
	$html='';
	if($user->uid>0 && $pid>0){
			$clsComment = new Comment();
			$arrItem = $clsComment->getAll("*", "pid=".$pid, "", "created", "");
			if(count($arrItem)>0){
				foreach ($arrItem as $v) {
					$html .= '<li>
                        <div class="notetxt">'.$v->username.':</div>
                        <div class="contenttxt">'.$v->content.'</div>
                        <div class="datetxt">'.date('d/m/Y h:i:s',$v->created).'</div>';
	                    if( in_array('Administrator', $user->roles)/* || in_array('Manager', $user->roles)*/) {    
	                    	if($view!='view'){
	                     		$html.='<div class="comment-delete" data="'.$v->id.'">Delete comment item</div>';
	                 	 	}
	                    }
                    $html.='</li>';
				}
			}
		}
	return  $html;
}
function ajaxupComment(){
	global $base_url, $user;
	if(empty($_POST)){
		drupal_set_message('Liên kết không tồn tại!');
		drupal_goto($base_url.'/admincp');
	}else{
		$clsComment = new Comment();
		$uid = $user->uid;
		$username = $user->name;
		$created = time();
		$pid = isset($_POST['pid']) ? trim($_POST['pid']) : '';
		$content = isset($_POST['frmcomment']) ? trim($_POST['frmcomment']) : '';
		if($uid>0 && $pid>0 && $content!=''){
			$data = array(
						'uid'=>$uid,
						'username'=>$username,
						'pid'=>$pid,
						'content'=>$content,
						'created'=>$created,
					);
			//$id = $clsComment->insertOne($data);
			$id = db_insert($clsComment->table)->fields($data)->execute();
			$txt = '<li>
                        <div class="notetxt">'.$user->name.':</div>
                        <div class="contenttxt">'.$content.'</div>
                        <div class="datetxt">'.date('d/m/Y h:i:s',$created).'</div>';
	                    if( in_array('Administrator', $user->roles) || in_array('Manager', $user->roles)) {    
	                     $txt.='<div class="comment-delete" data="'.$id.'">Delete comment item</div>';
	                    }
                    $txt.='</li>';
             echo $txt; die();
		}
		echo ''; die();
	}
}
function ajaxdeleteComment(){
	global $base_url, $user;
	if(empty($_POST)){
		drupal_set_message('Liên kết không tồn tại!');
		drupal_goto($base_url.'/admincp');
	}else{
		if( in_array('Administrator', $user->roles)/* || in_array('Manager', $user->roles)*/) {  
			$clsComment = new Comment();
			$id = isset($_POST['id']) ? trim($_POST['id']) : '';
			if($user->uid>0 && $id>0){
				$query = $clsComment->deleteOne($id);
				echo 'ok';die;
			}
		}else{
			drupal_set_message('Liên kết không tồn tại!');
			drupal_goto($base_url.'/admincp');
		}
	}
	echo 'not ok';die;
}

function popupAjaxAllComment(){
	global $base_url, $user;
	$html='';
	if(empty($_POST)){
		drupal_set_message('Liên kết không tồn tại!');
		drupal_goto($base_url.'/admincp');
	}else{
		$pid = isset($_POST['pid']) ? trim($_POST['pid']) : '';
		
		if($user->uid>0 && $pid>0){
			$clsComment = new Comment();
			$arrItem = $clsComment->getAll("*", "pid=".$pid, "", "created", "");
			if(count($arrItem)>0){
				foreach ($arrItem as $v) {
					$html .= '<li>
                        <div class="notetxt">'.$v->username.':</div>
                        <div class="contenttxt">'.$v->content.'</div>
                        <div class="datetxt">'.date('d/m/Y h:i:s',$v->created).'</div>';
	                    if( in_array('Administrator', $user->roles) || in_array('Manager', $user->roles)) {    
	                    	if($view!='view'){
	                     		$html.='<div class="comment-delete" data="'.$v->id.'">Delete comment item</div>';
	                 	 	}
	                    }
                    $html.='</li>';
				}
			}
		}
	}
	echo $html;die();
}

function ajaxAllDetailOrder(){
	global $base_url, $user;
	$html='';
	if(empty($_POST)){
		drupal_set_message('Liên kết không tồn tại!');
		drupal_goto($base_url.'/admincp');
	}else{
		$pid = isset($_POST['pid']) ? trim($_POST['pid']) : '';
		
		if($user->uid>0 && $pid>0){
			$_Order = new _Order();
			$arrItem = $_Order->getAll("checking, uid, title, phone, address, total, note, created", "id=".$pid, "", "created ASC", "1");
			
			if(count($arrItem)>0){
				foreach ($arrItem as $v) {
					$uid_name = '';
					if($v->uid > 0){
						$_Users = new _Users();
						$arrUser = $_Users->getOne("name", $v->uid);
						
						if(count($arrUser) > 0){
							$uid_name = $arrUser[0]->name;
						}else{
							$uid_name = 'Khách vãng lai';
						}
					}
					$html.='<div>1.	Ngày nhập đơn: <b>'.date('d/m/Y', $v->created).'</b>('.$uid_name.')</div>';
					
					$checking = '';
					switch ($v->checking) {
						case "0":
							$checking = 'Chưa kiểm duyệt';break;
						case "1":
							$checking = 'Đã kiểm duyệt';break;
						case "2":
							$checking = 'Hủy đơn hàng';break;
						case "3":
							$checking = 'Đã gửi';break;	
						case "4":
							$checking = 'Bị trả';break;
						case "5":
							$checking = 'Đã thu tiền';break;
						case "6":
							$checking = 'Đã lấy hàng hoàn';break;
						default:
							$checking = 'Chờ gửi';
					}

					$html.='<div>3.	Trạng thái: <b>'.$checking.'</b></div>';

					$html.='<br><br><div><b>Thông tin khách hàng:</b></div>';
					$html.='<div>1.	Họ tên: <b>'.$v->title.'</b></div>';
					$html.='<div>2.	SĐT: <b>'.$v->phone.'</b></div>';
					$html.='<div>3.	Địa chỉ: <b>'.$v->address.'</b></div><br/><br/>';
					
					$html.='<div>6.	Tổng tiền  COD cả ship: <b>'.$v->total.'</b>đ</div>';
					$html.='<div>7.	Yêu cầu của khách: <br/><b>'.$v->note.'</b></div>';

				}
			}
		}
	}
	echo $html;die();
}

