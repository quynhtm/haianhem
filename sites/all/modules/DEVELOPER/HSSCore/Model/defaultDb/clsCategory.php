<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class Category extends DbBasic{

	function __construct(){
        $this->pkey = 'id';
        $this->table = 'hss_category';
    }

    function makeListCatFromTypeFull($typeid=0, $catid=0, $level=0, &$arrString, $limit=10){
		$arrListCat = array();
		if($typeid>0){
			if($level==0){
				$arrListCat = $this->getAll("id,title", "status=1 AND parent_id=0 AND type_id=".$typeid, "", "id ASC", $limit);
			}else{
				$arrListCat = $this->getAll("id,title", "status=1 AND parent_id=$catid AND type_id=".$typeid, "", "id ASC", $limit);
			}
		}
		if (is_array($arrListCat)){
			foreach ($arrListCat as $k => $v){
				$value = $v->id;
				$nameCat = $v->title;
				$option = str_repeat("----", $level).$nameCat;
				$arrString[$value] = $option;
				$this->makeListCatFromTypeFull($typeid, $v->id, $level+1, $arrString, 10);
			}
			unset($arrListCat);
			return '';
		}else{
			return '';
		}
	}

    function makeCatidQueryString($catid=0){
		if($catid>0){
			$arrCatId[] = $catid;
		}
		$arrListCat = $this->getAll("id", "status=1 AND parent_id=$catid", "", "id ASC", "20");
		if (count($arrListCat)>0){
			foreach ($arrListCat as $v){
				$arrCatId[] = $v->id;
				$arrListSubCat = $this->getAll("id", "status=1 AND parent_id=$v->id", "", "id ASC", "20");  
				if(count($arrListSubCat)>0){
					foreach($arrListCat as $subid){
						$arrCatId[] = $subid->id;
					}	
				}	
			}
		}
		return $arrCatId;
	}

	function getListCatName($catid=0){
		$arrCat = array();
		$arrListCat = $this->getAll("id, title, title_alias", "status=1 AND parent_id=$catid", "", "id ASC", "50");
		if (count($arrListCat)>0){
			foreach ($arrListCat as $v){
				$arrCat[$v->id] = array(
										'title'=>$v->title,
										'title_alias'=>$v->title_alias,
									);
				$arrListSubCat = $this->getAll("id, title, title_alias", "status=1 AND parent_id=$v->id", "", "id ASC", "50");  
				if(count($arrListSubCat)>0){
					foreach($arrListCat as $subid){
						$arrCat[$v->id] = array(
										'title'=>$v->title,
										'title_alias'=>$v->title_alias,
									);
					}	
				}	
			}
		}
		return $arrCat;
	}

	function getCatFromAlias($str=""){
		$arrCat = array();
		if($str!=""){
			$arrCat = $this->getByCond("id, title, title_alias, parent_id, type_id, intro, content, meta_title, meta_keywords, meta_description", "title_alias='".$str."'", "", "id ASC", "1");
		}
		return $arrCat;
	}
	function getCatNameFromAlias($id=""){
		$name = "";
		if($id!=0){
			$arrCat = $this->getByCond("title", "id='".$id."'", "", "id ASC", "1");
			foreach($arrCat as $v){
				$name = $v->title;
			}
		}
		return $name;
	}
	function getCatAliasFromID($id=""){
		$name = "";
		if($id!=0){
			$arrCat = $this->getByCond("title_alias", "id='".$id."'", "", "id ASC", "1");
			foreach($arrCat as $v){
				$name = $v->title_alias;
			}
		}
		return $name;
	}
	function get_menu_link_category(){
		$arrListMenu = $this->getAll("id, title_alias", "status=1 AND type_id<>5", "", "order_no ASC", "");
		return $arrListMenu;
	}
	function list_category_root_search(){
		$arrListCat = $this->getAll("id,title", "parent_id=0 and type_id<>5 AND status=1", "", "id ASC", "20");
		$_catid = isset($_GET['txtCat']) ? trim($_GET['txtCat']) : '';
		$option='';
		foreach($arrListCat as $catid){
			$selected = 'selected="selected"';
			if($catid->id > 0 && $catid->id==$_catid){
				$option .= '<option value="'.$catid->id.'"'.$selected.'>'.$catid->title.'</option>';
			}else{
				$option .= '<option value="'.$catid->id.'">'.$catid->title.'</option>';
			}	
		}
		$html='';
		$html.= $option;
		
		return $html;	
	}
	function getCatNameFromId($id=0){
		$name = "";
		if($id!=0){
			$arrCat = $this->getByCond("title", "id='".$id."'", "", "id ASC", "1");
			foreach($arrCat as $v){
				$name = $v->title;
			}
		}
		return $name;
	}

	function list_category($catid=0, $type_id=0, $parent_id=0){
		
		if($parent_id > 0){
			$parent_id = " AND parent_id>0";
		}else{
			$parent_id = '';
		}
		$arrListCat = $this->getAll("id,title", "status=1 AND type_id=$type_id ".$parent_id, "", "id ASC", "20");
		$option='';
		foreach($arrListCat as $v){
			$selected = 'selected="selected"';
			if($v->id == $catid && $catid > 0){
				$option .= '<option value="'.$v->id.'"'.$selected.'>'.$v->title.'</option>';
			}else{
				$option .= '<option value="'.$v->id.'">'.$v->title.'</option>';
			}
		}
		$html='';
		$html.= $option;
		
		return $html;
	}
	function getCatHeadAliasFromID($id=""){
		$arrCat = "";
		if($id!=0){
			$arrCat = $this->getByCond("title, title_alias", "id='".$id."'", "", "id ASC", "1");
		}
		return $arrCat;
	}
	function getCatFromID($id=""){
		$arrItem = "";
		if($id!=0){
			$arrItem = $this->getByCond("*", "id='".$id."'", "", "id ASC", "1");
		}
		return $arrItem;
	}

	//list provices
	function list_category_post_of_user($_catid=0){
		$clsType = new Type(); 
		$typeid = $clsType->get_type_id($type_keyword='group_estates', $limit=1);

		$arrListCat = $this->getAll("id,title", "status=1 AND type_id=$typeid AND parent_id=0", "", "id ASC", "100");
		$option='';
		foreach($arrListCat as $catid){
			//catid
			$selected = 'selected="selected"';
			if($catid->id > 0){
				if($catid->id==$_catid){
					$option .= '<option value="'.$catid->id.'"'.$selected.'>'.$catid->title.'</option>';
				}else{
					$option .= '<option value="'.$catid->id.'">'.$catid->title.'</option>';
				}	
			}	
		}
		$html='';
		$html.= $option;
		
		return $html;	
	}
}