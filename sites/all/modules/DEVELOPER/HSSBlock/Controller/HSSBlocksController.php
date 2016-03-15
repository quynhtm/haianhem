<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function block_header(){
	$listMenu = get_menu_header();
	$cartItem = numCartItem();
	$data = array(
				'listMenu'=>$listMenu,
				'cartItem'=>$cartItem,
			);
	$view= theme('block-header', $data);
	return $view;
}
function block_slide(){
	$clsAds = new Ads();
	$arrItem = $clsAds->getByCond('title_show, link, img', 'status=1 and pos=5', '', 'order_no ASC', 5);
	$arrItemRight = $clsAds->getByCond('title_show, link, img', 'status=1 and pos=4', '', 'order_no ASC', 2);
	$listMenu = get_menu_cagegory_left(20);
	$data = array(
		'arrItem'=>$arrItem,
		'arrItemRight'=>$arrItemRight,
		'listMenu'=>$listMenu,
	);
	$view= theme('block-slide', $data);
	return $view;
}
function block_content_post_product(){
	$arrItem = get_menu_cagegory_content(5);
	$data = array(
		'arrItem'=>$arrItem,
	);
	$view= theme('block-content-post-product', $data);
	return $view;
}
function block_footer(){
	$view= theme('block-footer');
	return $view;
}
function get_content_product($limit=12){
	$clsProduct = new Product();
	$arrItem  = array();
	if($limit>0){
		$sql = db_select($clsProduct->table, 'i');
		$sql->addField('i', 'id', 'id');
		$sql->addField('i', 'cat_alias', 'cat_alias');
		$sql->addField('i', 'title', 'title');
		$sql->addField('i', 'title_alias', 'title_alias');
		$sql->addField('i', 'img', 'img');
		$sql->addField('i', 'price', 'price');
		$sql->addField('i', 'price_normal', 'price_normal');
		$sql->condition('i.status', 1,'=');
		$sql->condition('i.new', 0,'=');
		$sql->orderBy('i.id', 'DESC');
		$sql->range(0, $limit);
		$arrItem = $sql->execute()->fetchAll();
	}
	return $arrItem;
}