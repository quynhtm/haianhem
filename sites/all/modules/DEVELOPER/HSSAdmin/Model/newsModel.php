<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
$clsCategory = new Category();
$clsNews = new News();
$arrItem = $clsNews->getAll("id, catid", "", "", "id ASC", "");
foreach($arrItem as $v){
	if($v->id > 0){
		$catalias = $clsCategory->getCatAliasFromID($v->catid);
		$data = array(
			'cat_alias'=>$catalias,
		);
		$query = $clsNews->updateOne($data, $v->id);
	}
}
class _News extends News{

	function listItemPost(){
		global $base_url;

		$_Category = new _Category();

		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
		$status = isset($_GET['status']) ? $_GET['status'] : '';

		$header = array(
				array('data' => '<input type="checkbox" id="checkAll"/>'),
				array('field' => 'i.title','data' => t('Tiêu đề')),
				array('field' => 'i.catid','data' => t('Danh mục')),
				array('field' => 'i.created','data' => t('Ngày tạo')),
				array('field' => 'i.hot','data' => t('Tin hot')),
				array('field' => 'i.focus','data' => t('Loại tin')),
				array('field' => 'i.week','data' => t('Tin hàng ngang')),
				array('field' => 'i.order_no','data' => t('Thư tự')),
				array('field' => 'i.status','data' => t('Trạng thái')),
				array('data' => t('Action'))
		);

		$sql = db_select($this->table, 'i')->extend('PagerDefault')->extend('TableSort');
		$sql->leftjoin($_Category->table, 'c', 'c.id = i.catid');

		$sql->addField('c', 'title', 'catname');
		$sql->addField('i', 'id', 'id');
		$sql->addField('i', 'title', 'title');
		$sql->addField('i', 'catid', 'catid');
		$sql->addField('i', 'created', 'created');
		$sql->addField('i', 'hot', 'hot');
		$sql->addField('i', 'focus', 'focus');
		$sql->addField('i', 'week', 'week');
		$sql->addField('i', 'order_no', 'order_no');
		$sql->addField('i', 'status', 'status');

		/*search*/
		if($category > 0){
			$sql->condition('i.catid', $category, '=');
		}

		if($status != ''){
			$sql->condition('i.status', $status, '=');
		}

		$db_or = db_or();
		$db_or->condition('i.title', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.title_alias', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.intro', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.content', '%'.$keyword.'%', 'LIKE');
		$sql->condition($db_or);
		/*end search*/

		if(isset($_GET['sort'])){
			$result = $sql->limit(SITE_RECORD_PER_PAGE)->orderByHeader($header)->execute();

		}else{
			$result = $sql
			->limit(SITE_RECORD_PER_PAGE)->orderBy('i.id', 'DESC')->execute();
		}
		$arrItem = $result->fetchAll();

		//total item
		$totalItem = count($arrItem);
		$rows = array();
		if($totalItem > 0){

			$i=1;
			foreach ($arrItem as $row){
				$row = (object)$row;

				if($row->status==1){
					$status='<span class="bg-status-show">'.t('Hiện').'</span>';
				}else{
					$status='<span class="bg-status-hidden">'.t('Ẩn').'</span>';
				}

				$created = date('d-m-Y h:i',$row->created);

				if($row->focus==0){
					$focus = t('Tin thường');
				}else{
					$focus = '<span class="hot">'.t('Tin nổi bật').'</span>';
				}

				if($row->week==0){
					$week = t('Tin thường');
				}else{
					$week = '<span class="hot">'.t('Tin nổi bật').'</span>';
				}

				if($row->hot==0){
					$hot = t('Tin thường');
				}else{
					$hot = '<span class="hot">'.t('Tin hot').'</span>';
				}

				$rows[$i]['data']['checkbox'] = '<input type="checkbox" class="checkItem" name="checkItem[]" value="'.$row->id.'" />';
				$rows[$i]['data']['title'] = $row->title;
				$rows[$i]['data']['catid'] =  $row->catname;
				$rows[$i]['data']['created'] = $created;
				$rows[$i]['data']['hot'] =  $hot;
				$rows[$i]['data']['focus'] =  $focus;
				$rows[$i]['data']['week'] =  $week;
				$rows[$i]['data']['order_no'] =  $row->order_no;
				$rows[$i]['data']['status'] =  $status;

				$rows[$i]['data']['action'] = '<a class="icon huge" href="'.$base_url.'/admincp/news/edit/'.$row->id.'"  title="Update Item">
											<i class="icon-pencil bgLeftIcon"></i>
										</a>
										<a class="icon huge" id="deleteOneItem" href="javascript:void(0)" title="Delete Item">
											<i class="icon-remove bgLeftIcon"></i>
										</a>';
				$i++;
			}
		}
		$listItem['table']['tablesort_table'] = array(
				'#theme' => 'table',
				'#header' => $header,
				'#rows' => $rows,
		);
		$listItem['pager'] = array('#theme' => 'pager','#quantity' => SITE_SCROLL_PAGE);

		return  $listItem;
	}

	function listInputForm(){

		$_Category = new _Category();
		$arrOptionsCategory[0] = t("Chọn chuyên mục");
		$_Type = new _Type();
		$typeid = $_Type->get_list_type_id($type_keyword='group_news', $limit=1);
		$_Category->makeListCatFromType($typeid, 0, 0, $arrOptionsCategory, 20);

		$arr_fields = array(
				'id'=>array('type'=>'hidden', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
				'catid'=>array('type'=>'option', 'label'=>'Danh mục', 'value'=>'0','require'=>'', 'attr'=>'','list_option'=>$arrOptionsCategory),
				'title'=>array('type'=>'text', 'label'=>'Tiêu đề', 'value'=>'','require'=>'require', 'attr'=>''),
				'intro'=>array('type'=>'textarea', 'label'=>'Mô tả', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
				'content'=>array('type'=>'textarea', 'label'=>'Nội dung', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>1),
				'img'=>array('type'=>'file', 'label'=>'Ảnh', 'value'=>'','require'=>'', 'attr'=>''),
				'order_no'=>array('type'=>'text', 'label'=>'Số thứ tự', 'value'=>'1','require'=>'', 'attr'=>''),
				'hot'=>array('type'=>'option', 'label'=>'Tin hot', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('không'),'1'=>t('Có'))),
				'focus'=>array('type'=>'option', 'label'=>'Nổi bật', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('không'),'1'=>t('Có'))),
				'week'=>array('type'=>'option', 'label'=>'Tin hàng ngang', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('không'),'1'=>t('Có'))),
				'status'=>array('type'=>'option', 'label'=>'Trạng thái', 'value'=>'1', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('Ẩn'),'1'=>t('Hiện'))),
				//'language'=>array('type'=>'language', 'label'=>'Ngôn ngữ', 'value'=>'en', 'require'=>'' ,'attr'=>''),
				'meta_title'=>array('type'=>'textarea', 'label'=>'Meta title', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
				'meta_keywords'=>array('type'=>'textarea', 'label'=>'Meta keywords', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
				'meta_description'=>array('type'=>'textarea', 'label'=>'Meta description', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
		);

		return $arr_fields;
	}
}