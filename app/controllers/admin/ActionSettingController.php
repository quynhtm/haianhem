<?php

/**
 * Created by PhpStorm.
 * User: QuynhTM
 */
class ActionSettingController extends BaseAdminController
{
    private $permission_full = 'setting_site_full';
    private $permission_view = 'setting_site_view';
    private $permission_delete = 'setting_site_delete';
    private $permission_create = 'setting_site_create';
    private $permission_edit = 'setting_site_edit';
    private $arrStatus = array(-1 => 'Chọn trạng thái', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $error = array();
    public function __construct()
    {
        parent::__construct();

        /*//Include style.
        FunctionLib::link_css(array(
            'lib/cssUpload.css',
        ));

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/jquery.uploadfile.js',
        ));*/
    }

    /**************************************************************************************************************************
     Quản lý table: web_type_setting
     **************************************************************************************************************************/
    public function viewTypeSetting() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = $treeCategroy = array();
        $total = 0;

        $search['type_title'] = addslashes(Request::get('type_title',''));
        $search['type_status'] = (int)Request::get('type_status',-1);

        $dataSearch = TypeSetting::searchByCondition($search, 500, $offset,$total);
        $paging = '';

        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['type_status']);
        $this->layout->content = View::make('admin.ActionSetting.TypeSettingView')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('data', $dataSearch)
            ->with('search', $search)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $this->arrStatus)

            
            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_delete', in_array($this->permission_delete, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0);//dùng common
    }

    public function getTypeSetting($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $data = array();
        if($id > 0) {
            $data = TypeSetting::find($id);
        }

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['type_status'])? $data['type_status'] : CGlobal::status_show);
        $this->layout->content = View::make('admin.ActionSetting.TypeSettingAdd')
            ->with('id', $id)
            ->with('data', $data)
            ->with('optionStatus', $optionStatus);
    }

    public function postTypeSetting($id=0) {
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }

        $dataSave['type_title'] = addslashes(Request::get('type_title'));
        $dataSave['type_keyword'] = addslashes(Request::get('type_keyword'));
        $dataSave['type_group'] = addslashes(Request::get('type_group'));
        $dataSave['type_infor'] = addslashes(Request::get('type_infor'));
        $dataSave['type_status'] = (int)Request::get('type_status', CGlobal::status_show);
        $dataSave['type_order'] = (int)Request::get('type_order', 1);

        if($this->validTypeSetting($dataSave) && empty($this->error)) {
            if($id > 0) {
                //cap nhat
                if(TypeSetting::updateData($id, $dataSave)) {
                    return Redirect::route('admin.typeSettingView');
                }
            } else {
                //them moi
                if(TypeSetting::addData($dataSave)) {
                    return Redirect::route('admin.typeSettingView');
                }
            }
        }
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($dataSave['type_status'])? $dataSave['type_status'] : CGlobal::status_show);
        $this->layout->content =  View::make('admin.ActionSetting.TypeSettingAdd')
            ->with('id', $id)
            ->with('data', $dataSave)
            ->with('optionStatus', $optionStatus)
            ->with('error', $this->error);
    }

    private function validTypeSetting($data=array()) {
        if(!empty($data)) {
            if(isset($data['type_title']) && $data['type_title'] == '') {
                $this->error[] = 'Tên không được bỏ trống';
            }
            if(isset($data['type_group']) && $data['type_group'] == '') {
                $this->error[] = 'Nhóm không được bỏ trống';
            }
            if(isset($data['type_keyword']) && $data['type_keyword'] == '') {
                $this->error[] = 'Keyword không được bỏ trống';
            }
            if(isset($data['type_status']) && $data['type_status'] == -1) {
                $this->error[] = 'Bạn chưa chọn trạng thái';
            }
            return true;
        }
        return false;
    }

    //ajax
    public function deleteTypeSetting()
    {
        $result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($result);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && TypeSetting::deleteData($id)) {
            $result['isIntOk'] = 1;
        }
        return Response::json($result);
    }

    //ajax
    public function updateStatusTypeSetting()
    {
        $id = (int)Request::get('id', 0);
        $category_status = (int)Request::get('status', CGlobal::status_hide);
        $result = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($result);
        }

        if ($id > 0) {
            $dataSave['type_status'] = ($category_status == CGlobal::status_hide)? CGlobal::status_show : CGlobal::status_hide;
            if(TypeSetting::updateData($id, $dataSave)) {
                $result['isIntOk'] = 1;
            }
        }
        return Response::json($result);
    }


}