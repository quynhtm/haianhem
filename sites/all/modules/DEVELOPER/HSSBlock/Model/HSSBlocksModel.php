<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
function get_menu_header(){
	$clsCategory = new Category();
	$arrListMenu= array();
	$arrListMenuSub = array();
	$arrMenu = array();
	$arrListMenu = $clsCategory->getAll("id, parent_id, type_id, title, title_alias", "status=1 AND menu=1", "", "order_no ASC", "6");
	if(count($arrListMenu > 0)){
		foreach($arrListMenu as $v){
			$arrMenu[$v->id]['title'] = $v->title;
			$arrMenu[$v->id]['title_alias'] = $v->title_alias;
		}
	}
	unset($arrListMenu);
	return $arrMenu;
}
function get_menu_cagegory_left($limit=0){
	$clsCategory = new Category();

	$arrMenu = array();
	$arrListMenu= array();
	$arrListMenuSub = array();
	
	if($limit >0){
		$arrMenu = array();
		$arrListMenu = $clsCategory->getAll("id, parent_id, type_id, title, title_alias", "status=1 AND parent_id=0 AND menu_cat=1", "", "order_no ASC", $limit);
		if(count($arrListMenu > 0)){
			foreach($arrListMenu as $v){
				$arrMenu[$v->id]['title'] = $v->title;
				$arrMenu[$v->id]['title_alias'] = $v->title_alias;
				$arrListMenuSub = $clsCategory->getAll("id, parent_id, title, title_alias", "status=1 AND menu_cat=1 AND parent_id=$v->id", "", "order_no DESC", "4");
				if(count($arrListMenuSub >0)){
					foreach($arrListMenuSub as $s){
						if($s->parent_id == $v->id){
							$arrMenu[$v->id]['sub'][$s->id]['title'] = $s->title;
							$arrMenu[$v->id]['sub'][$s->id]['title_alias'] = $s->title_alias;
						}
					}
				}
			}
		}
	}
	unset($arrListMenu);
	unset($arrListMenuSub);
	return $arrMenu;
}

function get_menu_cagegory_content($limit=0){
	$clsCategory = new Category();
	$clsProduct = new Product;

	$arrMenu = array();
	$arrListMenu= array();
	$arrListMenuSub = array();
	$arrId = array();

	if($limit >0){
		$arrMenu = array();
		$arrListMenu = $clsCategory->getAll("id, parent_id, type_id, title, title_alias, img", "status=1 AND parent_id=0 AND menu_content=1", "", "order_no ASC", $limit);
		if(count($arrListMenu > 0)){
			foreach($arrListMenu as $v){
				
				$arrMenu[$v->id]['title'] = $v->title;
				$arrMenu[$v->id]['title_alias'] = $v->title_alias;
				$arrMenu[$v->id]['img'] = $v->img;
				
				$arrListMenuSub = $clsCategory->getAll("id, parent_id, title, title_alias, img", "status=1 AND parent_id=$v->id", "", "order_no DESC", "8");//AND menu_content=1
				if(count($arrListMenuSub >0)){
					foreach($arrListMenuSub as $s){
						if($s->parent_id == $v->id){
							$arrMenu[$v->id]['sub'][$s->id]['title'] = $s->title;
							$arrMenu[$v->id]['sub'][$s->id]['title_alias'] = $s->title_alias;
							$arrMenu[$v->id]['sub'][$s->id]['img'] = $s->img;
							$arrId[$v->id][] = $s->id;
						}
					}

					/*get product in list category*/
					$sql = db_select($clsProduct->table, 'i')->extend('PagerDefault');
					$sql->addField('i', 'id', 'id');
					$sql->addField('i', 'cat_alias', 'cat_alias');
					$sql->addField('i', 'title', 'title');
					$sql->addField('i', 'title_alias', 'title_alias');
					$sql->addField('i', 'img', 'img');
					$sql->addField('i', 'price', 'price');
					$sql->condition('i.status', 1,'=');
					$sql->condition('i.subcatid', $arrId, 'IN');
					$sql->orderBy('i.id', 'DESC');
					$sql->limit(12);
					$arrProduct = $sql->execute()->fetchAll();
					
					$arrMenu[$v->id]['item'] = $arrProduct;
					unset($arrId);
					unset($arrProduct);
				}
			}
		}
	}
	unset($arrListMenu);
	unset($arrListMenuSub);
	return $arrMenu;
}