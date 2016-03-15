<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/

class _RecycleBin extends RecycleBin{

	function listItemPost(){
		global $base_url;

		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		
		$header = array(
				array('data' => '<input type="checkbox" id="checkAll"/>'),
				array('field' => 'i.title','data' => t('Tiêu đề')),
				array('field' => 'i.class','data' => t('Class')),
				array('field' => 'i.uid','data' => t('Người xóa')),
				array('field' => 'i.created','data' => t('Ngày tạo')),
				array('data' => t('Action'))
		);

		$sql = db_select($this->table, 'i')->extend('PagerDefault')->extend('TableSort');
		$sql->addField('i', 'id', 'id');
		$sql->addField('i', 'title', 'title');
		$sql->addField('i', 'uid', 'uid');
		$sql->addField('i', 'class', 'class');
		$sql->addField('i', 'created', 'created');

		/*search*/
		$db_or = db_or();
		$db_or->condition('i.title', '%'.$keyword.'%', 'LIKE');
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
				$created = date('d-m-Y h:i',$row->created);

				$rows[$i]['data']['checkbox'] = '<input type="checkbox" class="checkItem" name="checkItem[]" value="'.$row->id.'" />';
				$rows[$i]['data']['title'] = $row->title;
				$rows[$i]['data']['class'] = $row->class;
				$rows[$i]['data']['uid'] = $row->uid;
				$rows[$i]['data']['created'] = $created;

				$rows[$i]['data']['action'] = '<a class="icon huge" id="deleteOneItem" href="javascript:void(0)" title="Delete Item">
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

		$arr_fields = array(
				'id'=>array('type'=>'hidden', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
				'title'=>array('type'=>'text', 'label'=>'Tiêu đề', 'value'=>'','require'=>'', 'attr'=>''),
				'class'=>array('type'=>'text', 'label'=>'Class', 'value'=>'','require'=>'', 'attr'=>''),
				'uid'=>array('type'=>'text', 'label'=>'Người xóa', 'value'=>'','require'=>'', 'attr'=>''),
				//'content'=>array('type'=>'textarea', 'label'=>'Nội dung', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
		);

		return $arr_fields;
	}
}