<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class _Roles  extends Roles{
	function get_all_role(){
		$arrString = array();
		$arrRole = $this->getAll('rid, name', "rid>3", "", "rid ASC", "");
		foreach ($arrRole as $k => $v){
			$valueRole = $v->rid;
			$nameRole = $v->name;
			$arrString[$valueRole] = $nameRole;
		}
		
		return $arrString;
	}
}

class _UsersRoles  extends UsersRoles{
	
}

class _Users extends Users{

	function listItemPost(){
		global $base_url, $user;
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$status = isset($_GET['status']) ? $_GET['status'] : '';

		$header = array(
				array('data' => '<input type="checkbox" id="checkAll"/>'),
				array('field' => 'i.fullname','data' => t('Tên người dùng')),
				array('field' => 'i.role','data' => t('Thuộc quyền')),
				array('field' => 'i.name','data' => t('Tên đăng nhập')),
				array('field' => 'i.pass','data' => t('Mật khẩu')),
				//array('field' => 'i.mail','data' => t('Email')),
				array('field' => 'i.phone','data' => t('Số điện thoại')),
				array('field' => 'i.status','data' => t('Trạng thái')),
				array('field' => 'i.created','data' => t('Ngày tạo')),
				array('data' => t('Action'))
		);
		
		$sql = db_select($this->table, 'i')->extend('PagerDefault')->extend('TableSort');
		$sql->innerjoin('role', 'r', 'i.rid = r.rid');
		
		$sql->addField('i', 'uid', 'uid');
		$sql->addField('i', 'fullname', 'fullname');
		$sql->addField('i', 'name', 'name');
		$sql->addField('i', 'pass', 'pass');
		//$sql->addField('i', 'mail', 'mail');
		$sql->addField('i', 'phone', 'phone');
		$sql->addField('i', 'created', 'created');
		$sql->addField('i', 'status', 'status');
		$sql->addField('r', 'name', 'role');
				
		//$sql->condition('i.uid', array(0, 1), 'NOT IN');
		/*search*/
		if($status != ''){
			$sql->condition('i.status', $status, '=');
		}
		
		$db_or = db_or();
		$db_or->condition('i.fullname', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.name', '%'.$keyword.'%', 'LIKE');
		$db_or->condition('i.phone', '%'.$keyword.'%', 'LIKE');
		$sql->condition($db_or);
		/*end search*/

		if( in_array('Nhân viên', $user->roles) 
			|| in_array('Thư ký', $user->roles)){
			$sql->condition('i.uid', $user->uid, '=');
		}
		
		if(isset($_GET['sort'])){
			$result = $sql->limit(SITE_RECORD_PER_PAGE)->orderByHeader($header)->execute();

		}else{
			$result = $sql->limit(SITE_RECORD_PER_PAGE)->orderBy('i.uid', 'ASC')->execute();
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
						$status = 'Bị khóa';break;
					case "1":
						$status = 'Hoạt động';break;
					default:
						$status = 'Bị khóa';
				}
				
				$created = date('d-m-Y h:i',$row->created);

				$rows[$i]['data']['checkbox'] = '<input type="checkbox" class="checkItem" name="checkItem[]" value="'.$row->uid.'" />';
				$rows[$i]['data']['fullname'] = $row->fullname;
				$rows[$i]['data']['role'] = $row->role;
				$rows[$i]['data']['name'] = $row->name;
				$rows[$i]['data']['pass'] = '**********';
				//$rows[$i]['data']['mail'] = $row->mail;
				$rows[$i]['data']['phone'] = $row->phone;
				$rows[$i]['data']['status'] = $status;
				$rows[$i]['data']['created'] = $created;

				$rows[$i]['data']['action'] = '<a class="icon huge" href="'.$base_url.'/admincp/users/edit/'.$row->uid.'"  title="Update Item">
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
						'0'=>t('Bị khóa'),
						'1'=>t('Hoạt động'),
					);
		$clsRoles = new _Roles();
		$arrRole = $clsRoles->get_all_role();
		$arr_fields = array(
				'uid'=>array('type'=>'hidden', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
				'rid'=>array('type'=>'option', 'label'=>'Nhóm quyền', 'value'=>'0','require'=>'require', 'attr'=>'','list_option'=>$arrRole),
				'fullname'=>array('type'=>'text', 'label'=>'Tên người dùng', 'value'=>'','require'=>'require', 'attr'=>''),
				'name'=>array('type'=>'text', 'label'=>'Tên đăng nhập', 'value'=>'','require'=>'require', 'attr'=>''),
				'pass'=>array('type'=>'password', 'label'=>'Mật khẩu', 'value'=>'','require'=>'require', 'attr'=>''),
				'repass'=>array('type'=>'password', 'label'=>'Nhập lại mật khẩu', 'value'=>'','require'=>'require', 'attr'=>''),
				'mail'=>array('type'=>'text', 'label'=>'Email', 'value'=>'','require'=>'require', 'attr'=>''),
				'phone'=>array('type'=>'text', 'label'=>'Điện thoại', 'value'=>'','require'=>'require', 'attr'=>''),	
				'status'=>array('type'=>'option', 'label'=>'Trạng thái', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>$arrStatus),				
		);
		return $arr_fields;
	}
	function get_all_user($cond=''){
		$arrUser = array();
		if($cond != ''){
			$arrUser = $this->getAll('uid, fullname', $cond, "", "uid ASC", "");
		}
		return $arrUser;
	}
	function get_name_user($uid=0){
		$name='';
		if($uid >0){
			$arrUser = $this->getAll('fullname', "uid=$uid", "", "uid ASC", "");
			if(count($arrUser)>0){
				$name = $arrUser[0]->fullname;
			}
		}
		return $name;
	}
}