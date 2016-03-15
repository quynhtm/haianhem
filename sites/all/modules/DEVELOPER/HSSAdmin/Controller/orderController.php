<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function indexOrder(){
	global $base_url;
	
	$_Order = new _Order();
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();
	$_Module = new _Module();
	$_Module->get_list_field($field='fields', $class='_Order');

	$totalItem = $_Order->countItem($_fields="id");
	$listItem = $_Order->listItemPost();
	$arrStatus = $_Order->checking();
	$data = array(
			'title'=>'Quản lý bài viết',
			'listItem' => $listItem,
			'totalItem' =>$totalItem,
			'arrStatus'=>$arrStatus,
	);

	$view = theme('order',$data);
	return $view;
}

function formOrderAction(){
	global $base_url, $user;
	
	$clsStdio = new clsStdio();
	$_Order = new _Order();
	$clsDate = new clsDate();

	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	$_ShowFields = new _ShowFields();
	$listInputForm = $_ShowFields->get_field_show($class = '_Order');
	$fields = clsForm::buildItemFields($listInputForm);
	$data = array('fields'=>$fields);

	//get item update
	$param = arg();
	if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
		$arrOneItem = $_Order->getOne("*", $param[3]);
		foreach ($data['fields'] as $key => $filed) {
			$data['fields'][$key]['value']=$arrOneItem[0]->$key;

			if($key=='time_send'){
				$data['fields'][$key]['value'] = $clsDate->showDate($arrOneItem[0]->$key);
			}
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
			

			if(isset($data['fields'][$key]['type_data']) && $data['fields'][$key]['type_data'] == 'int'){
				$data['fields'][$key]['value'] = $data_post[$key];
				$data_post[$key] = (int)$data_post[$key];
			}elseif(isset($data['fields'][$key]['type_data']) && $data['fields'][$key]['type_data'] == 'float'){
				$data['fields'][$key]['value'] = $data_post[$key];
				$data_post[$key] = (float)$data_post[$key];
			}else{
				$data['fields'][$key]['value'] = $data_post[$key];
			}

			if(isset($field['require']) && $field['require']=='require' && $data_post[$key]==''){
				$require_post[$key] = t($field['label']).' '.t('không được rỗng!');
			}

			if($key=='title'){
				$data_post['title_alias']=$clsStdio->pregReplaceStringAlias(clsForm::itemPostValue('title'));
			}

			if($key=='time_send'){
				$data_post['time_send'] = $clsDate->convertDate(clsForm::itemPostValue($key)); 
				$data['fields'][$key]['value']= $data_post[$key];
			}

			if($key == 'phone'){
				$data_post[$key] = str_replace(".","",$data['fields'][$key]['value']);
				#check phone trung nhau
				$id_check = $data_post['id'];
				$phone_check = $data_post['phone'];
				if($data_post['phone'] != ''){
					$arrPhone = $_Order->getByCond("phone", "phone = $phone_check AND id<>$id_check", $_groupby="", $_oderby="id DESC", $_limit="1");
					if(count($arrPhone)>=1){
						drupal_set_message('Danh sách đơn hàng có cùng 1 số số điện thoại: <br/><a target="_blank" href="'.$base_url.'/admincp/order?keyword='.$arrPhone[0]->phone.'">Xem ở đây.</a>');
					}
				}
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
				unset($data_post['created']);
				$query = $_Order->updateOne($data_post, $data['fields']['id']['value']);
				unset($data_post);
				drupal_set_message('Sửa bài viết thành công.');
				drupal_goto($base_url.'/admincp/order');
			}else{
				$query = $_Order->insertOne($data_post);
				unset($data_post);
				if($query){
					drupal_set_message('Thêm bài viết thành công.');
					drupal_goto($base_url.'/admincp/order');
				}
			}
		}
	}
	
	$view = theme('order-form',$data);
	return $view;
}

function deleteOrderAction(){
	global $base_url;
	$_Order = new _Order();
	
	$clsPermissionDetail = new PermissionDetail();
	$clsPermissionDetail->check_permission();

	if(isset($_POST) && $_POST['txtFormName']=='txtFormName'){
		$listId = isset($_POST['checkItem'])? $_POST['checkItem'] : 0;
		foreach($listId as $item){
			if($item > 0){
				$clsRecycleBin = new RecycleBin();
				$clsRecycleBin->addItem($item, "_Order");
				$query = $_Order->deleteOne($item);	
			}
		}
		unset($listId);
		drupal_set_message('Xóa bài viết thành công.');
	}
	drupal_goto($base_url.'/admincp/order');
}

function ajaxUpdateStatus(){
	global $base_url, $user;
	$html='';

	if(empty($_POST)){
		drupal_set_message('Liên kết không tồn tại!');
		drupal_goto($base_url.'/admincp');
	}else{
		$pid = isset($_POST['pid']) ? trim($_POST['pid']) : '';
		$checking = isset($_POST['checking']) ? trim($_POST['checking']) : '';
		if($user->uid>0 && $pid>0 && $checking != ''){
				$_Order = new _Order();
				$data['checking'] = $checking;
				$query = $_Order->updateOne($data, $pid);
				$html = 'ok';
			}
	}
	echo $html;die();
}

function printerOrderAction(){
	$order= isset($_GET['order']) ? $_GET['order'] : '';
	
	if($order>0){
		$_Order = new _Order();
		$arrOneItem = $_Order->getByCond("*", "id=$order", "", " id DESC", "1");
		
		if(count($arrOneItem>0)){
			header('Content-Type: text/html; charset=utf-8');
			
			$html = '<div class="line-print-box">';
				$html .= '<div class="line-print-txt fz10">';
					$html.= clsUtility:: keyword('SITE_LABEL_PRODUCT');
				$html.='</div>';

				$html .= '<div class="line-print-txt" style="text-align:center; margin:10px 0px;">';						
				$html.='<strong>Tổng tiền thu hộ:</strong> <b>'.number_format($arrOneItem[0]->total).'đ</b>';
				$html.='</div>';
				$name_sp = '#####';
				if($arrOneItem[0]->pcode != ''){
					$name_sp = $arrOneItem[0]->pcode;
				}
				$html .= '<div class="line-print-txt fz12">';
					$html .= '<p>';
						$html.='<strong>NGƯỜI NHẬN:</strong> '.$arrOneItem[0]->title.'. <br/>';
						$html.='<strong>Điện thoại:</strong> '.$arrOneItem[0]->phone.'. <br/>';
						$html.='<strong>Địa chỉ:</strong> '.$arrOneItem[0]->address.'.<br/>';
						$html.='<strong>Tên SP:</strong> '.$name_sp.'(SL:'.$arrOneItem[0]->num.')<br/>';
					$html.='</p>';
				$html.='</div>';

				$html .= '<div class="line-print-txt mg_10"><br/>';
					$html.= clsUtility:: keyword('SITE_LABEL_NOTE');
				$html.='</div>';
			$html.='</div>';
			
			$html .= '<div class="line-print-txt line-button">';
				$html.= '<input type="button" class="btn-print" onclick="window.print()" value="In phiếu" name="B1">';
			$html.='</div>';
			$html .='<style>
						.line-print-box {
							border: 1px dotted #000;
							margin: 280px auto 0;
							padding: 4px;
							width: 255px;
						}
						.line-print-txt {
						    clear: both;
						    display: inline-block;
						    width: 100%;
						}
						.line-print-txt p {
						    font-size: 13px;
						    margin: 5px 0;
						}
						.line-print-txt.line-button{
							margin: 20px 0;
							text-align: center;
						}
						.fz10{
							font-size:10px;
						}
						.fz12{
							font-size: 12px;
						}
						.mgt20{
							margin-top:20px;
						}
						.mg_10{
							margin-top:-10px;
						}
					</style>';
			echo $html;
		}

	}
}