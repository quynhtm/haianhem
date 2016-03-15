<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function get_list_item_product_full(){
	$param = arg();

	$cat_alias = '';
	$cat_name = '';
	if(isset($param[0]) && count($param) == 1){
		$cat_alias = $param[0];
	}
	
	$arrItem = array();
	$listPage= array();
	$clsProduct = new Product();
	$clsSeo = new clsSeo();
	
	$sql = db_select($clsProduct->table, 'i')->extend('PagerDefault');
	$sql->addField('i', 'id', 'id');
	$sql->addField('i', 'cat_alias', 'cat_alias');
	$sql->addField('i', 'title', 'title');
	$sql->addField('i', 'title_alias', 'title_alias');
	$sql->addField('i', 'img', 'img');
	$sql->addField('i', 'price', 'price');
	$sql->addField('i', 'price_normal', 'price_normal');
	$sql->condition('i.status', 1,'=');

	if( $cat_alias == 'san-pham'){
		$cat_name = 'Sản phẩm';
		$sql->orderBy('i.id', 'DESC');
	}elseif( $cat_alias == 'san-pham-re-nhat'){
		$cat_name = 'Giá tăng dần';
		$sql->orderBy('i.price', 'ASC');
	}elseif( $cat_alias == 'san-pham-tot-nhat'){
		$cat_name = 'Giá giảm dần';
		$sql->orderBy('i.price', 'DESC');
	}

	$sql->limit(SITE_RECORD_PER_PAGE);
	$arrItem = $sql->execute()->fetchAll();
	$listPage['pager'] = array('#theme' => 'pager','#quantity' => 3);
	
	$clsSeo->SEO($cat_name, '', $cat_name, $cat_name, $cat_name );

	$data = array(
				'arrItem'=>$arrItem,
				'listPage'=>$listPage,
				'cat_alias'=>$cat_alias,
				'cat_name'=>$cat_name,
			);
	$view = theme("page-product-full", $data);
	return $view;
}
function get_list_item_product_search(){
	$param = arg();

	$cat_alias = 'tim-kiem?keyword=';
	$cat_name = 'Kết quả tìm kiếm';
	
	$keyword = '';
	if(isset($_GET) && !empty($_GET)){
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$cat_alias .= ''.$keyword;
	}
	$arrItem = array();
	$listPage= array();
	$clsProduct = new Product();
	$clsSeo = new clsSeo();
	
	$sql = db_select($clsProduct->table, 'i')->extend('PagerDefault');
	$sql->addField('i', 'id', 'id');
	$sql->addField('i', 'cat_alias', 'cat_alias');
	$sql->addField('i', 'title', 'title');
	$sql->addField('i', 'title_alias', 'title_alias');
	$sql->addField('i', 'img', 'img');
	$sql->addField('i', 'price', 'price');
	$sql->addField('i', 'price_normal', 'price_normal');
	$sql->condition('i.status', 1,'=');

	$db_or = db_or();
	$db_or->condition('title', '%'.$keyword.'%', 'LIKE');
	$db_or->condition('title_alias', '%'.$keyword.'%', 'LIKE');
	$db_or->condition('intro', '%'.$keyword.'%', 'LIKE');
	$db_or->condition('content', '%'.$keyword.'%', 'LIKE');
	$db_or->condition('code', '%'.$keyword.'%', 'LIKE');
	$sql->condition($db_or);

	$sql->limit(SITE_RECORD_PER_PAGE);
	$arrItem = $sql->execute()->fetchAll();
	$listPage['pager'] = array('#theme' => 'pager','#quantity' => 3);
	
	$clsSeo->SEO($cat_name, '', $cat_name, $cat_name, $cat_name );

	$data = array(
				'arrItem'=>$arrItem,
				'listPage'=>$listPage,
				'cat_alias'=>$cat_alias,
				'cat_name'=>$cat_name,
				'keyword'=>$keyword,
			);
	$view = theme("page-product-search", $data);
	return $view;
}
function get_list_item_product($typeid=0, $catid=0){

	$arrItem = array();
	$listPage= array();
	$cat_name = '';
	if($typeid >0 && $catid > 0){
		$clsCategory = new Category();
		$clsProduct = new Product();
		$param = arg();
		$cat_name_alias = trim($param[0]);
		$arrCat = $clsCategory->getCatFromAlias($cat_name_alias);
		$catid=0;
		$cat_name='';
		$cat_name_alias = '';
		$meta_title='';
		$meta_keywords='';
		$meta_description='';
		foreach($arrCat as $v){
			$catid = $v->id;
			$cat_name = $v->title;
			$cat_name_alias = $v->title_alias;
			
			$meta_title = $v->meta_title;
			$meta_keyword = $v->meta_keywords;
			$meta_description = $v->meta_description;

			$clsSeo = new clsSeo();
			$clsSeo->SEO($cat_name, '', $meta_title, $meta_keyword, $meta_description);
		}

		$arrCatid = $clsCategory->makeCatidQueryString($catid);
		$arrListCat = $clsCategory->getListCatName($catid);
		
		$sql = db_select($clsProduct->table, 'i')->extend('PagerDefault');
		$sql->addField('i', 'id', 'id');
		$sql->addField('i', 'cat_alias', 'cat_alias');
		$sql->addField('i', 'title', 'title');
		$sql->addField('i', 'title_alias', 'title_alias');
		$sql->addField('i', 'img', 'img');
		$sql->addField('i', 'price', 'price');
		$sql->addField('i', 'price_normal', 'price_normal');
		$sql->condition('i.status', 1,'=');
		$sql->condition('i.subcatid', $arrCatid, 'IN');
		$sql->orderBy('i.id', 'DESC');
		$sql->limit(SITE_RECORD_PER_PAGE);
		
		$arrItem = $sql->execute()->fetchAll();
		$listPage['pager'] = array('#theme' => 'pager','#quantity' => 3);
	}
	$data = array(
				'arrItem'=>$arrItem,
				'listPage'=>$listPage,
				'cat_name'=>$cat_name,
				'cat_name_alias'=>$cat_name_alias,
				'arrListCat'=>$arrListCat,
			);
	$view = theme("page-product", $data);
	return $view;
}
function get_item_product_view($title_alias='', $catid=0){
	global $base_url;

	$cartItem = numCartItem();

	if($title_alias != '' && $catid > 0){
		
		$clsCategory = new Category();
		$clsProduct = new Product();
		
		$param = arg();
		$title_alias = trim($param[1]);
		
		#detail product
		$sql = db_select($clsProduct->table, 'i');
		$sql->addField('i', 'id', 'id');
		$sql->addField('i', 'code', 'code');
		$sql->addField('i', 'cat_name', 'cat_name');
		$sql->addField('i', 'cat_alias', 'cat_alias');
		$sql->addField('i', 'title', 'title');
		$sql->addField('i', 'point', 'point');
		$sql->addField('i', 'title_alias', 'title_alias');
		$sql->addField('i', 'intro', 'intro');
		$sql->addField('i', 'content', 'content');
		$sql->addField('i', 'img', 'img');
		$sql->addField('i', 'price', 'price');
		$sql->addField('i', 'price_normal', 'price_normal');
		$sql->addField('i', 'kg', 'kg');
		$sql->addField('i', 'size_no', 'size_no');

		$sql->addField('i', 'meta_description', 'meta_description');
		$sql->addField('i', 'meta_keywords', 'meta_keywords');
		$sql->addField('i', 'meta_title', 'meta_title');
		
		$sql->condition('i.status', 1,'=');
		$sql->condition('i.title_alias', $title_alias, '=');
		$sql->orderBy('i.id', 'DESC');
		$sql->range(0,1);
		$oneItem = $sql->execute()->fetchAll();
		
		$arrImg = array();
		$cat_name = '';
		$cat_alias = '';
		
		if(count($oneItem)>0){
			$cat_name = $oneItem[0]->cat_name;
			$cat_alias = $oneItem[0]->cat_alias;
			$clsImg = new Img();
			$arrImg = $clsImg->getAll("path", "status=1 AND eid=".$oneItem[0]->id, "", "id ASC", 4);
		}else{
			drupal_goto($base_url.'/page-404');
		}

		#sampe product
		$rs = db_select($clsProduct->table, 'i');
		$rs->addField('i', 'id', 'id');
		$rs->addField('i', 'cat_alias', 'cat_alias');
		$rs->addField('i', 'title', 'title');
		$rs->addField('i', 'title_alias', 'title_alias');
		$rs->addField('i', 'img', 'img');
		$rs->addField('i', 'price', 'price');
		$rs->addField('i', 'price_normal', 'price_normal');
		$rs->condition('i.status', 1,'=');
		$rs->condition('i.title_alias', $title_alias, '<>');
		$rs->condition('i.subcatid', $catid, '=');
		$rs->orderBy('i.id', 'DESC');
		$rs->range(0, 5);
		$arrSameItem = $rs->execute()->fetchAll();
		
		$data = array(
			'cat_name' => $cat_name,
			'cat_alias' => $cat_alias,
			'oneItem'=>$oneItem,
			'arrImg'=>$arrImg,
			'arrSameItem'=>$arrSameItem,
			'cartItem'=>$cartItem,
		);

		$view = theme("page-product-view", $data);
		return $view;
	}else{
		drupal_goto($base_url);
	}
}
function get_product_in_blog($limit=5){
		
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
		
			$sql->orderBy('i.id', 'DESC');
			$sql->range(0, $limit);
			$sql->orderRandom();

			$arrItem = $sql->execute()->fetchAll();
		}
		return $arrItem;
}