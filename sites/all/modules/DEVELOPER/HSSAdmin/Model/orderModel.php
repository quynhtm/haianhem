<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/

class _Order extends Order{

	function listItemPost(){
		global $base_url;
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$checking = isset($_GET['checking']) ? $_GET['checking'] : '';

		$header = array(
				array('data' => '<input type="checkbox" id="checkAll"/>'),
				array('field' => 'i.title','data' => t('Tên ng mua')),
				array('field' => 'i.pname','data' => t('Tên SP')),
				array('field' => 'i.link_ship','data' => t('Link ship')),
				array('field' => 'i.phone','data' => t('SĐT')),
				array('field' => 'i.num','data' => t('SL')),
				array('field' => 'i.total','data' => t('Tổng tiền')),
				array('field' => 'i.checking','data' => t('Kiểm duyệt')),
				array('field' => 'i.note','data' => t('Ghi chú')),
				array('field' => 'i.created','data' => t('Ngày tạo')),		
				//array('field' => 'i.status','data' => t('Trạng thái')),
				array('field' => 'i.time_send','data' => t('Ngày gửi')),
				array('data' => t('Action'))
		);

		$sql = db_select($this->table, 'i')->extend('PagerDefault')->extend('TableSort');

		$sql->addField('i', 'id', 'id');
		$sql->addField('i', 'title', 'title');
		$sql->addField('i', 'pname', 'pname');
		$sql->addField('i', 'num', 'num');
		$sql->addField('i', 'link', 'link');
		$sql->addField('i', 'total', 'total');
		$sql->addField('i', 'phone', 'phone');
		$sql->addField('i', 'created', 'created');
		//$sql->addField('i', 'status', 'status');
		$sql->addField('i', 'checking', 'checking');
		$sql->addField('i', 'link_ship', 'link_ship');
		$sql->addField('i', 'time_send', 'time_send');
		/*search*/
		if($checking != ''){
			$sql->condition('i.checking', $checking, '=');
		}

		$db_or = db_or();
		$db_or->condition('i.title', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.title_alias', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.intro', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.content', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.phone', '%'.$keyword.'%', 'LIKE');
		//$db_or->condition('i.email', '%'.$keyword.'%', 'LIKE');
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
				// if($row->status==1){
				// 	$status='<span class="bg-status-show">'.t('Hiện').'</span>';
				// }else{
				// 	$status='<span class="bg-status-hidden">'.t('Ẩn').'</span>';
				// }
				if($row->checking == 0){
					$checking='<span style="color:#ff0000" class="item-status" data-check="'.$row->checking.'">'.t('Chưa kiểm duyệt').'<a href="javascript:void(0)" rel="'.$row->id.'" class="update_status right icon-pencil bgLeftIcon">Edit status</a></span>';
				}elseif($row->checking == 1){
					$checking='<span style="color:#468847" class="item-status" data-check="'.$row->checking.'">'.t('Đã kiểm duyệt').'<a href="javascript:void(0)" rel="'.$row->id.'" class="update_status right icon-pencil bgLeftIcon">Edit status</a></span>';
				}elseif($row->checking == 2){
					$checking='<span style="color:#ff0000" class="item-status" data-check="'.$row->checking.'">'.t('Hủy đơn hàng').'<a href="javascript:void(0)" rel="'.$row->id.'" class="update_status right icon-pencil bgLeftIcon">Edit status</a></span>';
				}elseif($row->checking == 3){
					$checking='<span style="color:#468847" class="item-status" data-check="'.$row->checking.'">'.t('Đã gửi').'<a href="javascript:void(0)" rel="'.$row->id.'" class="update_status right icon-pencil bgLeftIcon">Edit status</a></span>';
				}elseif($row->checking == 4){
					$checking='<span style="color:#ff0000" class="item-status" data-check="'.$row->checking.'">'.t('Bị trả').'<a href="javascript:void(0)" rel="'.$row->id.'" class="update_status right icon-pencil bgLeftIcon">Edit status</a></span>';
				}elseif($row->checking == 5){
					$checking='<span style="color:#468847" class="item-status" data-check="'.$row->checking.'">'.t('Đã thu tiền').'<a href="javascript:void(0)" rel="'.$row->id.'" class="update_status right icon-pencil bgLeftIcon">Edit status</a></span>';
				}elseif($row->checking == 6){
					$checking='<span style="color:#468847" class="item-status" data-check="'.$row->checking.'">'.t('Đã lấy hàng hoàn').'<a href="javascript:void(0)" rel="'.$row->id.'" class="update_status right icon-pencil bgLeftIcon">Edit status</a></span>';
				}else{
					$checking='<span style="color:#000000" class="item-status" data-check="'.$row->checking.'">'.t('Chưa biết').'<a href="javascript:void(0)" rel="'.$row->id.'" class="update_status right icon-pencil bgLeftIcon">Edit status</a></span>';
				}
				
				if($row->link_ship != ''){
					$link_ship='<a href="'.$row->link_ship.'" target="_blank">Click xem</a>';
				}else{
					$link_ship='Không';
				}

				$created = date('d-m-Y h:i',$row->created);

				if($row->time_send > 0 ){
					$time_send = date('d-m-Y',$row->time_send);
				}else{
					$time_send = '';
				}

				$clsComment = new Comment();
				$OneComent = $clsComment->getAll("*", "pid=".$row->id, "", "id DESC", "3");
				$note='';
				if(count($OneComent)>0){
					foreach($OneComent as $comment){
						$time = date('d/m/y h:i', $comment->created);
						$note = $note. '<b>'. $time .'</b>' .': '.$comment->content.'(<span style="color:#ff0000">'.$comment->username.'</span>)<br/>';
					}
				}else{
					$note='';
				}
				if(count($OneComent)==3){
					$note = $note.'<a href="javascript:void(0)" class="x_check_comment" rel="'.$row->id.'">(Xem thêm)</a>';
				}

				$rows[$i]['data']['checkbox'] = '<input type="checkbox" class="checkItem" name="checkItem[]" value="'.$row->id.'" />';
				$rows[$i]['data']['title'] = $row->title;
				$rows[$i]['data']['pname'] = "<a href='".$row->link."' target='_blank'>".$row->pname."</a>";
				$rows[$i]['data']['title'] = $row->title;
				$rows[$i]['data']['link_ship'] = $link_ship;
				$rows[$i]['data']['phone'] = $row->phone;
				$rows[$i]['data']['num'] = $row->num;
				$rows[$i]['data']['total'] = number_format($row->total);
				$rows[$i]['data']['checking'] = $checking;
				$rows[$i]['data']['note'] = $note;
				$rows[$i]['data']['created'] = $created;
				$rows[$i]['data']['time_send'] = $time_send;
				//$rows[$i]['data']['status'] =  $status;

				$rows[$i]['data']['action'] = '
										<a class="icon huge item-print" href="javascript:void(0)" rel="'.$row->id.'"  title="Print Item">
											<i class="icon-print bgLeftIcon"></i>
										</a>
										<a class="icon huge" href="'.$base_url.'/admincp/order/edit/'.$row->id.'"  title="Update Item">
											<i class="icon-pencil bgLeftIcon"></i>
										</a>
										<a class="icon huge item-comment" href="javascript:void(0)" rel="'.$row->id.'"  title="Comment Item">
											<i class="icon-comment bgLeftIcon"></i>
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
		
		//$_Category = new _Category();
		//$arrOptionsCategory[0] = t("Danh mục gốc");
		//$_Category->makeListCat(0, 0, $arrOptionsCategory, 50);
		//$_Type = new _Type();
		//$typeid = $_Type->get_list_type_id($type_keyword='PRODUCT', $limit=1);
		//$_Category->makeListCatFromType($typeid, 0, 0, $arrOptionsCategory, 20);
		$optionChecking = $this->checking();
		
		$arr_fields = array(
				'id'=>array('type'=>'hidden', 'type_data'=>'int', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
				//'catid'=>array('type'=>'option', 'label'=>'Danh mục', 'value'=>'0','require'=>'', 'attr'=>'','list_option'=>$arrOptionsCategory),
				'pname'=>array('type'=>'text', 'label'=>'Tên sản phẩm', 'value'=>'','require'=>'require', 'attr'=>''),
				'pcode'=>array('type'=>'text', 'label'=>'Mã sản phẩm', 'value'=>'','require'=>'require', 'attr'=>''),
				'link'=>array('type'=>'text', 'label'=>'Linh tới sản phẩm', 'value'=>'','require'=>'', 'attr'=>''),
				'link_ship'=>array('type'=>'text', 'label'=>'Linh tới ship hàng', 'value'=>'','require'=>'', 'attr'=>''),
				'title'=>array('type'=>'text', 'label'=>'Tên người mua', 'value'=>'','require'=>'require', 'attr'=>''),
				//'email'=>array('type'=>'text', 'label'=>'Email', 'value'=>'','require'=>'', 'attr'=>''),
				'phone'=>array('type'=>'text', 'label'=>'Số điện thoại', 'value'=>'','require'=>'require', 'attr'=>''),
				'address'=>array('type'=>'text', 'label'=>'Địa chỉ', 'value'=>'','require'=>'require', 'attr'=>''),
				//'intro'=>array('type'=>'textarea', 'label'=>'Mô tả', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>0),
				'note'=>array('type'=>'textarea', 'label'=>'Ghi chú', 'value'=>'', 'require'=>'', 'attr'=>'', 'editor'=>1),
				'num'=>array('type'=>'text', 'type_data'=>'int', 'label'=>'Số lượng mua', 'value'=>'0','require'=>'', 'attr'=>''),
				'total'=>array('type'=>'text', 'type_data'=>'int', 'label'=>'Tổng tiền', 'value'=>'0','require'=>'', 'attr'=>''),
				'checking'=>array('type'=>'option', 'label'=>'Kiểm duyệt', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>$optionChecking),
				'time_send'=>array('type'=>'text', 'label'=>'Ngày gửi hàng', 'value'=>'','require'=>'', 'attr'=>''),
				//'status'=>array('type'=>'option', 'label'=>'Trạng thái', 'value'=>'1', 'require'=>'' ,'attr'=>'','list_option'=>array('0'=>t('Ẩn'),'1'=>t('Hiện'))),
		);
		return $arr_fields;
	}

	function checking(){
		$status = array(
						'0'=>'Chưa kiểm duyệt',
						'1'=>'Đã kiểm duyệt',
						'2'=>'Hủy đơn hàng', 
						'3'=>'Đã gửi',
						'4'=>'Bị trả',
						'5'=>'Đã thu tiền',
						'6'=>'Đã lấy hàng hoàn',
					);
		return $status;
	}
}