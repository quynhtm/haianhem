<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class _Module extends Module{

	function listItemPost(){
		global $base_url, $user;
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$status = isset($_GET['status']) ? $_GET['status'] : '';

		$header = array(
				array('data' => '<input type="checkbox" id="checkAll"/>'),
				array('field' => 'i.title','data' => t('Tên module')),
				//array('field' => 'i.link','data' => t('Liên kết')),
				array('field' => 'i.status','data' => t('Trạng thái')),
				array('field' => 'i.created','data' => t('Ngày tạo')),
				array('data' => t('Action'))
		);
		
		$sql = db_select($this->table, 'i')->extend('PagerDefault')->extend('TableSort');
		
		
		$sql->addField('i', 'id', 'id');
		$sql->addField('i', 'title', 'title');
		$sql->addField('i', 'link', 'link');
		$sql->addField('i', 'created', 'created');
		$sql->addField('i', 'status', 'status');
				
		/*search*/
		if($status != ''){
			$sql->condition('i.status', $status, '=');
		}
		
		$db_or = db_or();
		$db_or->condition('i.title', '%'.$keyword.'%', 'LIKE');
		$sql->condition($db_or);
		/*end search*/

		if(isset($_GET['sort'])){
			$result = $sql->limit(SITE_RECORD_PER_PAGE)->orderByHeader($header)->execute();

		}else{
			$result = $sql->limit(SITE_RECORD_PER_PAGE)->orderBy('i.title', 'ASC')->execute();
		}
		$arrItem = $result->fetchAll();

		//total item
		$totalItem = count($arrItem);
		$rows = array();
		if($totalItem > 0){

			$i=1;
			foreach ($arrItem as $row){
				$row = (object)$row;
				$status = '';
				switch ($row->status) {
					case "0":
						$status = 'Ẩn';break;
					case "1":
						$status = 'Hiện';break;
					default:
						$status = 'Ẩn';
				}
				
				$created = date('d-m-Y h:i',$row->created);

				$rows[$i]['data']['checkbox'] = '<input type="checkbox" class="checkItem" name="checkItem[]" value="'.$row->id.'" />';
				$rows[$i]['data']['title'] = '<a href="'.$base_url.'/admincp/showfields/edit/list-field/'.$row->link.'">'.$row->title.'</a>';
				//$rows[$i]['data']['link'] = $row->link;
				$rows[$i]['data']['status'] = $status;
				$rows[$i]['data']['created'] = $created;
				
				$rows[$i]['data']['action'] = '<a class="icon huge" href="'.$base_url.'/admincp/module/edit/'.$row->id.'"  title="Update Item">
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
		$arrStatus = array(
						'0'=>t('Ẩn'),
						'1'=>t('Hiện'),
					);
		$arr_fields = array(
				'id'=>array('type'=>'hidden', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
				'title'=>array('type'=>'text', 'label'=>'Tên module', 'value'=>'','require'=>'require', 'attr'=>''),
				'link'=>array('type'=>'text', 'label'=>'Liên kết', 'value'=>'','require'=>'require', 'attr'=>''),
				'intro'=>array('type'=>'textarea', 'label'=>'Mô tả', 'value'=>'','require'=>'', 'attr'=>''),
				'order_no'=>array('type'=>'text', 'label'=>'Thứ thự', 'value'=>'','require'=>'', 'attr'=>''),
				'status'=>array('type'=>'option', 'label'=>'Trạng thái', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>$arrStatus),				
		);
		return $arr_fields;
	}

	function get_list_field($field='fields', $class = ''){
		$param = arg();
		$list_field = array();
		$module = '';
		if($param[1]!=''){
			$module = $param[1];
		}
		if($field == 'fields' && $module !='' && $class!=''){
			$clsClass = new $class();
			$arrFields = $clsClass->listInputForm();
			foreach($arrFields as $name_field => $title_field){
				$item_field = array(
								'label' => $title_field['label'],
								'field' => $name_field,
								);
				array_push($list_field, $item_field);
			}
		}
		$data['fields'] = serialize($list_field);
		$this->updateByCond($_data=$data, $_field="link",  $_cond=$module);
		return true;
	}
}