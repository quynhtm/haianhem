<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class _Permission extends Roles{

	function listItemPost(){
		global $base_url, $user;
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		
		$header = array(
				array('data' => '<input type="checkbox" id="checkAll"/>'),
				array('field' => 'i.name','data' => t('Tên nhóm')),
				array('data' => t('Action'))
		);
		
		$sql = db_select($this->table, 'i')->extend('PagerDefault')->extend('TableSort');
		
		$sql->addField('i', 'rid', 'rid');
		$sql->addField('i', 'name', 'name');	

		$db_or = db_or();
		$db_or->condition('i.name', '%'.$keyword.'%', 'LIKE');
		$sql->condition($db_or);
		
		if(isset($_GET['sort'])){
			$result = $sql->limit(SITE_RECORD_PER_PAGE)->orderByHeader($header)->execute();

		}else{
			$result = $sql->limit(SITE_RECORD_PER_PAGE)->orderBy('i.name', 'ASC')->execute();
		}
		$arrItem = $result->fetchAll();

		//total item
		$totalItem = count($arrItem);
		$rows = array();
		if($totalItem > 0){

			$i=1;
			foreach ($arrItem as $row){
				$row = (object)$row;
				$rows[$i]['data']['checkbox'] = '<input type="checkbox" class="checkItem" name="checkItem[]" value="'.$row->rid.'" />';
				$rows[$i]['data']['title'] = '<span style="text-transform: capitalize;">'.$row->name.'</span>';
				$rows[$i]['data']['action'] = '<a class="icon huge" href="'.$base_url.'/admincp/permission/edit/'.$row->rid.'"  title="Update Item">
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
		$arr_fields = array(
				'rid'=>array('type'=>'hidden', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
				'name'=>array('type'=>'text', 'label'=>'Tên nhóm', 'value'=>'','require'=>'require', 'attr'=>''),
				//'weight'=>array('type'=>'text', 'label'=>'Thứ tự', 'value'=>'','require'=>'', 'attr'=>''),			
		);
		return $arr_fields;
	}
}