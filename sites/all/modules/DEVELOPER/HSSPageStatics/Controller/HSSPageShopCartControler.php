<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/

drupal_session_start();

function pagelistAllCart(){
	global $base_url;
	
	$listCart = isset($_SESSION['shopcart']) ? $_SESSION['shopcart'] :  array();
	//update list cart
	$updateCart = isset($_POST['listCart']) ? $_POST['listCart'] : array();
	
	if(count($updateCart) != 0){
		foreach($updateCart as $k=>$v){
			foreach($v as $_size => $_num){
				if($_num == 0){
					unset($_SESSION['shopcart'][$k][$_size]);
					if(count($_SESSION['shopcart'][$k]) == 0){
						unset($_SESSION['shopcart'][$k]);
					}
				}else{
					$_SESSION['shopcart'][$k][$_size] = $_num;
				}
			}
		}
		$listCart = $_SESSION['shopcart'];
		unset($_POST);
		drupal_goto($base_url.'/shop-cart');
	}
	//show list cart
	$html='';
	
	if(count($listCart)>0){
		$clsProduct = new Product();
		$i=1;
		
		$html.='<table class="list-shop-cart-item" width="100%">
					<tr class="first">
						<th>STT</th>
						<th>Tên sản phẩm</th>
						<th>Kích thước</th>
						<th>Số lượng</th>
						<th>Giá / 1 sản phẩm</th>
						<th>Thành tiền</th>
						<th>Thao tác</th>
					</tr>';
		$total=0;
		$bg = 0;
		$_bg = '';

		foreach($listCart as $k => $v){
			$result = db_select($clsProduct->table, 'sp');
			$result->addField('sp', 'id', 'id');
			$result->addField('sp', 'title', 'title');
			$result->addField('sp', 'title_alias', 'title_alias');
			$result->addField('sp', 'price', 'price');
			$result->addField('sp', 'cat_alias', 'cat_alias');
			$result->condition('sp.id', $k,'=');
			$listItem = $result->execute()->fetchAll();
			foreach($v as $size => $num){
				if($k!=0){
					$bg++;
					if($bg % 2 == 0){
						$_bg = 'class="even"';
					}else{
						$_bg = 'class="odd"';
					}

					foreach($listItem as $it){
						$totalPrice = ceil($num * $it->price);
						$total += $totalPrice;
					
						$html.='<tr '.$_bg.'>
									<td>'.$i.'</td>
									<td><a href="'.$base_url.'/'.$it->cat_alias.'/'.$it->title_alias.'" target="_blank">'.$it->title.'</a></td>
									<td>'.$size.'</td>
									<td><input type="text" class="num-item-in-one-product" value="'.$_SESSION['shopcart'][$it->id][$size].'" name="listCart['.$it->id.']['.$size.']" /></td>
									<td>'.number_format($it->price, 0).'<sup>đ</sup></td>
									<td>'.number_format($totalPrice, 0).'<sup>đ</sup></td>
									<td>
										<a href="javascript:void(0)" class="delOneItemCart" data="'.$it->id.'" data-size="'.$size.'">Xóa</a>
									</td>
								</tr>	
							';
					}
					$i++;
				}
			}
		}
		$html.='<tr>
				<td colspan="5"><b>Tổng số tiền thanh toán:</b></td>
				<td colspan="2"><b>'.number_format($total, 0).'</b><sup>đ</sup></td>
			</tr>	
			';
		$html.='</table>';
	}else{
		$html.='<div class="not-product-in-cat">Không có sản phẩm nào trong giỏ hàng...</div>';
	}
	$data = array(
				'html'=>$html,
				'listCart'=>$listCart,
			);
	$view = theme('page-list-shop-cart', $data);
	return $view;
}
function pageAddCart(){
	global $base_url;
	
	$clsProduct = new Product();
	if(empty($_POST)){
		drupal_goto($base_url);
	}

	$pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
	$psize = isset($_POST['psize']) ? $_POST['psize'] : '';
	$pnum = isset($_POST['pnum']) ? intval($_POST['pnum']) : 0;
	
	if($pid > 0 && $pnum > 0){
		$checkItem = $clsProduct->getByCond("id","id=$pid", "", "id ASC", "1");
		$num_cart = 0;
		if(count($checkItem) > 0){
			if(isset($_SESSION['shopcart'][$pid][$psize])){
				$num_cart =$_SESSION['shopcart'][$pid][$psize] + 1;	
			}else{
				$num_cart = 1;
			}
			$_SESSION['shopcart'][$pid][$psize] = $num_cart;
			echo 'okAddCart';
		}else{
			if(isset($_SESSION['shopcart'][$pid][$psize])){
				unset($_SESSION['shopcart'][$pid][$psize]);
			}
			echo 'notAddCart';
		}
	}
	exit();
}
function pageDelOneItemInCart(){
	global $base_url;
	if(empty($_POST)){
		drupal_goto($base_url);
	}
	$pid = isset($_POST['pid']) ? $_POST['pid'] : 0;
	$psize = isset($_POST['psize']) ? $_POST['psize'] : 0;
	if($pid > 0){
		unset($_SESSION['shopcart'][$pid][$psize]);
		if(count($_SESSION['shopcart'][$pid]) == 0){
			unset($_SESSION['shopcart'][$pid]);
		}
		echo 'DeleteOK';
		exit();
	}
}
function pageDelAllItemInCart(){
	global $base_url;
	if(empty($_POST)){
		drupal_goto($base_url);
	}
	$all = isset($_POST['all']) ? $_POST['all'] : '';
	if($all == 'del-all'){
		unset($_SESSION['shopcart']);
		echo 'DeleteAllItemOK';
		exit();
	}
}
function payOrderCart(){
	global $base_url;

	$listCart = isset($_SESSION['shopcart']) ? $_SESSION['shopcart'] :  array();
	if(count($listCart)>0){
		if(isset($_POST) && !empty($_POST)){
			$clsProduct = new Product();
			$clsOrder = new Order();

			$txtName 	= isset($_POST['txtName'])		? trim($_POST['txtName']) 	 : '';
			$txtMobile 	= isset($_POST['txtMobile'])	? trim($_POST['txtMobile']) : '';
			$txtMessage = isset($_POST['txtMessage']) 	? $_POST['txtMessage']: '';
			$txtAddress = isset($_POST['txtAddress'])  ? $_POST['txtAddress'] 	 : '';
			$txtFormName = isset($_POST['txtFormName'])  ? $_POST['txtFormName'] : '';

			$str_name = '';
			
			$title_alias = '';
			$pid = 0;
			$total = 0;
			$num = 0;
			foreach($listCart as $k=>$v){
				$arrOneDeltail = $clsProduct->getByCond("id, title, title_alias, cat_alias, price", "id='".$k."' AND status=1", "", "id DESC", 1);
				foreach ($v as $_size => $_num) {
					foreach($arrOneDeltail as $item){
						$pid = $item->id;
						$str_name .=  "<a href='".$base_url."/".$item->cat_alias."/".$item->title_alias."' target='_blank'>".$item->title."</a>".'<span class="red">(Kích thước: '.$_size.')-----(số lượng: '.$_num.')</span><br/>';
						$total += $_num * $item->price;
						$num += $_num;	
					}	
				}
			}

			if($txtFormName=='txtFormName' && $txtName!='' && $txtMobile!='' && $txtMessage!='' && $pid >0){
				
				$data = array(
					'title' => $txtName,
					'total' => $total,
					'pname'=> 'Mua nhiều sản phẩm',
					'phone' => $txtMobile,
					'address' => $txtAddress,
					'note' => $txtMessage.'<br/>'.$str_name,
					'num'=>$num,
					'checking'=>0,
					'created' => time(),
					'status' => 1
				);
				
				$query = $clsOrder->insertOne($data);
				unset($_SESSION['shopcart']);
				drupal_goto($base_url.'/thanks-order?pid='.base64_encode($pid));
			}else{
				drupal_set_message('Bạn vui lòng nhập các trường có dấu *!');
			}
		}
	}else{
		drupal_goto($base_url);
	}
	
	$view = theme('page-pay-cart');
	return $view;	
}

function numCartItem(){
	$cartItem = 0;
	if(isset($_SESSION['shopcart']) && count($_SESSION['shopcart'])>0){
	  foreach ($_SESSION['shopcart'] as $v){
	    if(count($v)>0){
	    	foreach($v as $_num){
            	if($_num>0){
                	$cartItem += $_num;
             	}
          	}
	    }
	  }
	}
	return $cartItem;
}