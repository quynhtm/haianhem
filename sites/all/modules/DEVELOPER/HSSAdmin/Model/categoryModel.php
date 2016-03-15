<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/

class _Category extends Category{
	//list option category from typeid
	function makeListCatFromType($typeid=0, $catid=0, $level=0, &$arrString, $limit=10, $flag = 0){
		$arrListCat = array();
		if($typeid>0){
			if($level==0){
				$arrListCat = $this->getAll("id,title", "status=1 AND parent_id=0 AND type_id=".$typeid, "", "id ASC", $limit);
			}else{
				$arrListCat = $this->getAll("id,title", "status=1 AND parent_id=$catid AND type_id=".$typeid, "", "id ASC", '');
			}
		}
		if (is_array($arrListCat)){
			foreach ($arrListCat as $k => $v){
				$value = $v->id;
				$nameCat = $v->title;
				$option = str_repeat("----", $level).$nameCat;
				$arrString[$value] = $option;
				if($flag == 1){
					//todo get parent
				}else{
					$this->makeListCatFromType($typeid, $v->id, $level+1, $arrString, 10);
				}
			}
			unset($arrListCat);
			return '';
		}else{
			return '';
		}
	}
	//list option category
	function makeListCat($catid='', $level=0, &$arrHtml, $limit=10){
		global $language;
		$listcat = explode(',', $catid);
		$arrListCat = array();
		if(count($listcat)-1 > 0){
			$where = '(';
			foreach($listcat as $cat){
				if($cat != end($listcat)){
					$where .= 'parent_id = '.$cat.' OR ';
				}else{
					$where .= 'parent_id = '.$cat;
				}
			}
			$where .= ')';
			$arrListCat = $this->getAll("id,title", "status=1 AND ".$where, "", "id ASC", $limit);
		}else{
			$arrListCat = $this->getAll("id,title", "status=1 AND parent_id=$catid", "", "id ASC", $limit);
		}
		if (is_array($arrListCat)){
			foreach ($arrListCat as $k => $v){
				$value = $v->id;
				$nameCat = $v->title;
				$option = str_repeat("----", $level).$nameCat;
				$arrHtml[$value] = $option;
				$this->makeListCat($v->id, $level+1, $arrHtml);
			}
			return '';
		}else{
			return '';
		}
	}
	//list row category
	function getListCat($catid=0, $level=0, &$arrHtml, $limit=50, $path_url='', &$total_item){
		global $base_url;

		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$status = isset($_GET['status']) ? $_GET['status'] : '';

		$where='';

		if($keyword!=''){
			$where .=" AND (title LIKE '%$keyword%' OR intro LIKE '%$keyword%' OR content LIKE '%$keyword%')";	
		}

		if($status != ''){
			$where .=' AND status='.$status;
		}
		
		$field = 'id, type_id, title, menu, menu_cat, menu_content, parent_id, language, order_no, status, created';
		$arrListCat = $this->getAll($field, "parent_id=$catid".$where, "id", "order_no ASC", $limit);

		if(is_array($arrListCat)){
			$html='';

			foreach ($arrListCat as $k => $v){
				$type = $this->get_type_name($v->type_id);
				if($v->parent_id==0){
					$title = '<b>'.$v->title.'</b>';
				}else{
					$title = $v->title;
				}

				if($v->menu==1){
					$menu='<span class="bg-status-show">'.t('Hiện').'</span>';
				}else{
					$menu='<span class="bg-status-hidden">'.t('Ẩn').'</span>';
				}

				if($v->menu_cat==1){
					$menu_cat='<span class="bg-status-show">'.t('Hiện').'</span>';
				}else{
					$menu_cat='<span class="bg-status-hidden">'.t('Ẩn').'</span>';
				}

				if($v->menu_content==1){
					$menu_content='<span class="bg-status-show">'.t('Hiện').'</span>';
				}else{
					$menu_content='<span class="bg-status-hidden">'.t('Ẩn').'</span>';
				}

				if($v->status==1){
					$status='<span class="bg-status-show">'.t('Hiện').'</span>';
				}else{
					$status='<span class="bg-status-hidden">'.t('Ẩn').'</span>';
				}

				$created = date('d-m-Y h:i',$v->created);

				$sub_title = str_repeat("----", $level).$title;
				$html = '<tr>
						<td><input type="checkbox" value="'.$v->id.'" name="checkItem[]" class="checkItem"></td>
						<td>'.$sub_title.'</td>
						<td style="text-align: center">'.$type.'</td>
						<td style="text-align: center">'.$menu.'</td>
						<td style="text-align: center">'.$menu_cat.'</td>
						<td style="text-align: center">'.$menu_content.'</td>
						<!--
						<td style="text-align: center">'.$v->language.'</td>
						-->
						<td style="text-align: center">'.$v->order_no.'</td>
						<td style="text-align: center">'.$status.'</td>
						<td style="text-align: center">'.$created.'</td>
						<td>
							<a title="Update Item" href="'.$base_url.'/'.$path_url.'/'.$v->id.'">
								<i class="icon-pencil bgLeftIcon"></i>
							</a>
							<a title="Delete Item" href="javascript:void(0)" id="deleteOneItem">
								<i class="icon-remove bgLeftIcon"></i>
							</a>
						</td>
					</tr>';

				$arrHtml .= $html;
				$total_item = $total_item+1;
				$this->getListCat($v->id, $level+1, $arrHtml, $limit=50, $path_url, $total_item);
			}
			return '';
		}else{
			return '';
		}
	}
	function get_type_name($typeid=0){
		$title='';
		if($typeid>0){
			$_Type = new _Type();
			$arrListType = $_Type->getAll('title', "id=".$typeid, "", "id ASC", 1);
			foreach($arrListType as $v){
				$title = $v->title;
			}
		}
		return $title;
	}
	function get_option_type($limit = 100){
		$arrayOption = array();
		if($limit>0){
			$_Type = new _Type();
			$arrListType = $_Type->getAll('id, title', "status=1", "", "id ASC", $limit);
			if(count($arrListType)>0){
				foreach($arrListType as $v){
					$arrayOption[$v->id] = $v->title;
				}
			}
		}
		return $arrayOption;
	}
	function get_cat_alias_from_catid($catid=0){
    	$arrCat = array();
    	$cat_alias= '';
    	if($catid > 0){
    		$arrCat = $this->getByCond("title_alias", "id='".$catid."'", "", "id ASC", "1");
    	}
    	foreach($arrCat as $v){
    		$cat_alias = $v->title_alias;
    	}

    	return $cat_alias;
    }
    function get_one_cat_from_catid($catid=0){
    	$arrCat = array();
    	if($catid > 0){
    		$arrCat = $this->getAll("title, title_alias", "id=$catid", "", "id ASC", "1");
    	}
    	return $arrCat;
    }
    function listInputForm(){

    	$arrOptionsCategory[0] = t("Danh mục gốc");
		$this->makeListCat(0, 0, $arrOptionsCategory, 50);
		
		$arrOptionsType = $this->get_option_type($limit=100);
		
		$arr_fields = array(
			'id'=>array('type'=>'hidden', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
			'type_id'=>array('type'=>'option', 'label'=>'Nhóm nội dung', 'value'=>'0','require'=>'', 'attr'=>'','list_option'=>$arrOptionsType),
			'parent_id'=>array('type'=>'option', 'label'=>'Danh mục', 'value'=>'0','require'=>'', 'attr'=>'','list_option'=>$arrOptionsCategory),
			'title'=>array('type'=>'text', 'label'=>'Tiêu đề', 'value'=>'','require'=>'require', 'attr'=>''),
			'intro'=>array('type'=>'textarea', 'label'=>'Mô tả', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
			'content'=>array('type'=>'textarea', 'label'=>'Nội dung', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>1),
			'order_no'=>array('type'=>'text', 'label'=>'Số thứ tự', 'value'=>'1','require'=>'', 'attr'=>''),
			'menu'=>array('type'=>'option', 'label'=>'Hiện ở menu ngang', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('Ẩn'),'1'=>t('Hiện'))),
			'menu_cat'=>array('type'=>'option', 'label'=>'Hiện ở menu trái slider', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('Ẩn'),'1'=>t('Hiện'))),
			'menu_content'=>array('type'=>'option', 'label'=>'Hiện ở content', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('Ẩn'),'1'=>t('Hiện'))),
			'img'=>array('type'=>'file', 'label'=>'Ảnh', 'value'=>'','require'=>'', 'attr'=>''),
			'status'=>array('type'=>'option', 'label'=>'Trạng thái', 'value'=>'1', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('Ẩn'),'1'=>t('Hiện'))),
			//'language'=>array('type'=>'language', 'label'=>'Ngôn ngữ', 'value'=>'en', 'require'=>'' ,'attr'=>''),
			'meta_title'=>array('type'=>'textarea', 'label'=>'Meta title', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
			'meta_keywords'=>array('type'=>'textarea', 'label'=>'Meta keywords', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
			'meta_description'=>array('type'=>'textarea', 'label'=>'Meta description', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
		);

		return $arr_fields;
	}
}