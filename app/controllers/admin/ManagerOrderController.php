<?php
/*
* @Author    : QuynhTM
* @Date      : 06/2016
* @Version   : 1.0
*/
class ManagerOrderController extends BaseAdminController
{
    private $permission_view = 'managerOrder_view';
    private $permission_full = 'managerOrder_full';
    private $permission_delete = 'managerOrder_delete';
    private $permission_create = 'managerOrder_create';
    private $permission_edit = 'managerOrder_edit';
    private $arrStatus = array(-1 => 'Chọn trạng thái', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $arrShop = array();

    public function __construct()
    {
        parent::__construct();
        $this->arrShop = array();

        //Include javascript.
        FunctionLib::link_js(array(
            'lib/ckeditor/ckeditor.js',
            'lib/ckeditor/config.js',
            'admin/js/admin.js',
        ));
    }

    public function view() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>1));
        }
        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['order_id'] = addslashes(Request::get('order_id',''));
        $search['order_product_name'] = addslashes(Request::get('order_product_name',''));
        $search['order_customer_name'] = addslashes(Request::get('order_customer_name',''));
        $search['order_customer_phone'] = addslashes(Request::get('order_customer_phone',''));
        $search['order_customer_email'] = addslashes(Request::get('order_customer_email',''));
        $search['time_start_time'] = addslashes(Request::get('time_start_time',''));
        $search['time_end_time'] = addslashes(Request::get('time_end_time',''));
        $search['order_status'] = (int)Request::get('order_status',-1);
        $search['order_user_shop_id'] = (int)Request::get('order_user_shop_id',-1);
        ///$search['field_get'] = 'order_id,order_product_name,order_status';//cac truong can lay

        $data = Order::searchByCondition($search, $limit, $offset,$total);
        //FunctionLib::debug($data);
        $paging = $total > 0 ? Pagging::getNewPager(3, $pageNo, $total, $limit, $search) : '';

        $arrStatusOrder = array(-1 => '---- Trạng thái đơn hàng ----',
            CGlobal::ORDER_STATUS_NEW => 'Đơn hàng mới',
            CGlobal::ORDER_STATUS_CHECKED => 'Đơn hàng đã xác nhận',
            CGlobal::ORDER_STATUS_SUCCESS => 'Đơn hàng thành công',
            CGlobal::ORDER_STATUS_CANCEL => 'Đơn hàng hủy');
        $optionStatus = FunctionLib::getOption($arrStatusOrder, $search['order_status']);

        $this->layout->content = View::make('admin.ManagerOrder.view')
            ->with('paging', $paging)
            ->with('stt', ($pageNo-1)*$limit)
            ->with('total', $total)
            ->with('sizeShow', count($data))
            ->with('data', $data)
            ->with('search', $search)
            ->with('arrShop', $this->arrShop)
            ->with('optionStatus', $optionStatus)
            ->with('arrStatus', $arrStatusOrder)

            ->with('is_root', $this->is_root)//dùng common
            ->with('permission_full', in_array($this->permission_full, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_delete', in_array($this->permission_delete, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_create', in_array($this->permission_create, $this->permission) ? 1 : 0)//dùng common
            ->with('permission_edit', in_array($this->permission_edit, $this->permission) ? 1 : 0);//dùng common
    }

    public function deleteOrderShop(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root){
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && Order::deleteData($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }

    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['order_product_name']) && $data['order_product_name'] == '') {
                $this->error[] = 'Tên đơn hàng không được trống';
            }
            if(isset($data['order_status']) && $data['order_status'] == -1) {
                $this->error[] = 'Bạn chưa chọn trạng thái cho đơn hàng';
            }
            return true;
        }
        return false;
    }

}