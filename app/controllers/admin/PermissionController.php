<?php

/**
 * Created by PhpStorm.
 * User: MT969
 * Date: 5/30/2015
 * Time: 4:22 PM
 */
class PermissionController extends BaseAdminController
{

    private $permission_full = 'permission_full';
    private $permission_create = 'permission_create';
    private $permission_edit = 'permission_edit';
    private $arrStatus = array(-1 => 'Xóa', 0 => 'Tất cả', 1 => 'Hoạt động');

    public function __construct()
    {
        parent::__construct();
    }

    public function view(){
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        /*echo strtotime('17-10-2016 14:30:41');
        echo '<br/>'.strtotime('17-10-2016 14:32:41');
        die;*/

        $dataSearch = $dataResponse = $data = array();
        $page_no = Request::get('page_no', 1);//phan trang

        $dataSearch['permission_code'] = Request::get('permission_code', '');
        $dataSearch['permission_name'] = Request::get('permission_name', '');
        $dataSearch['permission_status'] = Request::get('permission_status', 0);
        $limit = 30;
        $offset = ($page_no - 1) * $limit;
        $total = 0;
        //call api
        $aryPermission = Permission::searchPermission($dataSearch, $limit, $offset, $total);
        if (!empty($aryPermission)) {
            $aryPermissionId = array();
            foreach ($aryPermission as $val) {
                $aryPermissionId[] = $val->permission_id;
            }
        }

        $paging = $total > 0 ? Pagging::getNewPager(3,$page_no,$total,$limit,$dataSearch) : '';
        $this->layout->content = View::make('admin.Permission.view')
            ->with('data', $aryPermission)
            ->with('dataSearch', $dataSearch)
            ->with('total', $total)
            ->with('paging', $paging)
            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0)
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)
            ->with('start', ($page_no - 1) * $limit)
            ->with('arrStatus', $this->arrStatus);
    }

    public function createInfo()
    {
//        CGlobal::$pageTitle = "Tạo mới quyền | Admin Seo";
//        if (!in_array($this->permission_create, $this->permission)) {
//            return Redirect::route('admin.dashboard',array('error'=>1));
//        }
        //$optionStatus = FunctionLib::getOption($this->arrStatus, 1);
        // Show the page
//        $arrGroupUser = GroupUser::getListGroupUser();
        $this->layout->content = View::make('admin.Permission.create');
        //->with('optionStatus',$optionStatus)
    }

    public function create()
    {
//        //check permission
//        if (!in_array($this->permission_create, $this->permission)) {
//            return Redirect::route('admin.dashboard',array('error'=>1));
//        }
        $error = array();
        $data['permission_code'] = htmlspecialchars(trim(Request::get('permission_code', '')));
        $data['permission_name'] = htmlspecialchars(trim(Request::get('permission_name', '')));
        $data['permission_group_name'] = htmlspecialchars(trim(Request::get('permission_group_name', '')));
        //encode các ký tự html
//        $dataResult = FunctionLib::encodeParam($dataEncode);
//        $dataSave['permission_code'] = (!FunctionLib::stripUnicode($dataResult['permission_code'])) ? $dataResult['permission_code'] : FunctionLib::stripUnicode($dataResult['permission_code']);
//        $dataSave['permission_name'] = $dataResult['permission_name'];
        $data['permission_status'] = 1;
//        $dataSave['permission_group_name'] = (!FunctionLib::stripUnicode($dataResult['permission_group_name'])) ? $dataResult['permission_group_name'] : FunctionLib::stripUnicode($dataResult['permission_group_name']);
//        $data = $dataSave;

//        $arrGroupUser = $data['user_group'] = Request::get('user_group',array());

        if ($data['permission_code'] == '') {
            $error[] = 'Mã quyền không được để trống ';
        }
        if ($data['permission_name'] == '') {
            $error[] = 'Tên quyền không được để trống ';
        }
        if ($data['permission_group_name'] == '') {
            $error[] = 'Nhóm quyền không được để trống ';
        }
        if (Permission::checkExitsPermissionCode($data['permission_code'])) {
            $error[] = 'Mã quyền đã tồn tại ';
        }

        if ($error != null) {
            $this->layout->content = View::make('admin.Permission.create')
                ->with('error', $error)
                ->with('data', $data);
        } else {

            //insert dl
            if (Permission::createPermission($data)) {
                return Redirect::route('admin.permission_view');
            } else {
                $error[] = 'Lỗi truy xuất dữ liệu';
                $this->layout->content = View::make('admin.Permission.create')
                    ->with('error', $error)
                    ->with('data', $data);
            }
        }

    }

    public function editInfo($id)
    {
//        CGlobal::$pageTitle = "Sửa quyền | Admin Seo";
//        if (!in_array($this->permission_edit, $this->permission)) {
//            return Redirect::route('admin.dashboard',array('error'=>1));
//        }
        $data = Permission::find($id);//lay dl permission theo id
//        $groupPermission = new GroupUserPermission();
//        $dataGroups = $groupPermission->getListGroupByPermissionId(array($id));

//        $aryGroup = array();
//        if($dataGroups){
//            foreach($dataGroups as $group){
//                $aryGroup[] = $group->group_user_id;
//            }
//        }
//        $data->user_group = $aryGroup;

        $this->layout->content = View::make('admin.Permission.edit')
            ->with('data', $data)
            ->with('arrStatus', $this->arrStatus);
//            ->with('arrGroupUser',GroupUser::getListGroupUser());
    }

    public function edit($id)
    {
        //check permission
//        if (!in_array($this->permission_edit, $this->permission)) {
//            return Redirect::route('admin.dashboard',array('error'=>1));
//        }
        $error = array();
        $data['permission_code'] = htmlspecialchars(trim(Request::get('permission_code', '')));
        $data['permission_name'] = htmlspecialchars(trim(Request::get('permission_name', '')));
        $data['permission_group_name'] = htmlspecialchars(trim(Request::get('permission_group_name', '')));
        $data['permission_status'] = (int)Request::get('permission_status', 1);
        //encode các ký tự html
        if ($data['permission_code'] == '') {
            $error['mess'] = 'Mã quyền không được để trống ';
        }
        if ($data['permission_name'] == '') {
            $error['mess'] = 'Tên quyền không được để trống ';
        }
        if ($data['permission_group_name'] == '') {
            $error['mess'] = 'Nhóm quyền không được để trống ';
        }

        if (Permission::checkExitsPermissionCode($data['permission_code'], $id)) {
            $error[] = 'Mã quyền đã tồn tại ';
        }

        if ($error != null) {
            $this->layout->content = View::make('admin.Permission.edit')
                ->with('error', $error['mess'])
                ->with('data', $data)
                ->with('arrStatus', $this->arrStatus);
        } else {

            if (Permission::updatePermission($id, $data)) {
                return Redirect::route('admin.permission_view');
            } else {
                $error[] = 'Lỗi truy xuất dữ liệu';
                $this->layout->content = View::make('admin.Permission.edit')
                    ->with('error', $error)
                    ->with('data', $data)
                    ->with('arrStatus', $this->arrStatus);
            }
        }
    }

    public function deletePermission()
    {
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)){
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && Permission::deleteData($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
}