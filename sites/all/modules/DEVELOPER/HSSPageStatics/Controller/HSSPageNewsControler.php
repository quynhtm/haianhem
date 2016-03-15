<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function get_list_item_news($typeid=0, $catid=0){
	$arrItem = array();
	$listPage= array();

	if($typeid >0 && $catid > 0){
		$clsCategory = new Category();
		$clsNews = new News();
		$catName = $clsCategory->getCatFromID($catid);
		$arrCatid = $clsCategory->makeCatidQueryString($catid);
		foreach($catName as $v){
			$cat_name = $v->title;
			$meta_title = $v->meta_title;
			$meta_keyword = $v->meta_keywords;
			$meta_description = $v->meta_description;

			$clsSeo = new clsSeo();
			$clsSeo->SEO($cat_name, '', $meta_title, $meta_keyword, $meta_description);
		}

		$sql = db_select($clsNews->table, 'i')->extend('PagerDefault');
		$sql->addField('i', 'id', 'id');
		$sql->addField('i', 'catid', 'catid');
		$sql->addField('i', 'cat_alias', 'cat_alias');
		$sql->addField('i', 'title', 'title');
		$sql->addField('i', 'title_alias', 'title_alias');
		$sql->addField('i', 'intro', 'intro');
		$sql->addField('i', 'img', 'img');
		$sql->addField('i', 'created', 'created');
		
		$sql->condition('i.catid', $arrCatid, 'IN');
		$sql->condition('i.status', 1,'=');		
		$sql->orderBy('i.id', 'DESC');
		$sql->limit(SITE_RECORD_PER_PAGE_NEWS);
		$arrItem = $sql->execute()->fetchAll();
		$listPage['pager'] = array('#theme' => 'pager','#quantity' => 3);
	}
	$data = array(
				'arrItem'=>$arrItem,
				'listPage'=>$listPage,
				'catName'=>$catName,
			);
	$view = theme("page-news", $data);
	return $view;
}
function get_item_new_view($title_alias='', $catid=0){
	if($title_alias != '' && $catid > 0){
		$clsCategory = new Category();
		$clsNews = new News();
		$catName = $clsCategory->getCatHeadAliasFromID($catid);
		$oneItem = $clsNews->getByCond('id, cat_alias, title, title_alias, intro, content, img, created, meta_title, meta_keywords, meta_description', "status=1 AND title_alias='$title_alias' AND catid=$catid", "", "created DESC", '1');
		$arrSameItem = $clsNews->getAll("title, cat_alias, title_alias, created", "status=1 AND title_alias <> '$title_alias' AND catid = $catid", "", "created DESC", SITE_SAME_RECORD_NEWS);
		
		$randomProduct = get_product_in_blog(5);

		$data = array(
				'oneItem' => $oneItem,
				'arrSameItem'=>$arrSameItem,
				'catName'=>$catName,
				'randomProduct'=>$randomProduct,
			);
		$view = theme("page-news-view", $data);
		return $view;
	}else{
		drupal_goto($base_url);
	}
}