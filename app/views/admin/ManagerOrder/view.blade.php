<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li class="active">Đơn hàng</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="panel panel-info">
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div class="panel-body">
                        <div class="form-group col-lg-3">
                            <label for="order_product_name">Tên sản phẩm</label>
                            <input type="text" class="form-control input-sm" id="order_product_name" name="order_product_name" placeholder="Tên sản phẩm" @if(isset($search['order_product_name']))value="{{$search['order_product_name']}}"@endif>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="order_customer_name">Tên khách hàng</label>
                            <input type="text" class="form-control input-sm" id="order_customer_name" name="order_customer_name" placeholder="Tên khách hàng" @if(isset($search['order_customer_name']))value="{{$search['order_customer_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_customer_phone">SĐT khách hàng</label>
                            <input type="text" class="form-control input-sm" id="order_customer_phone" name="order_customer_phone" placeholder="SĐT khách hàng" @if(isset($search['order_customer_phone']))value="{{$search['order_customer_phone']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_customer_email">Email khách hàng</label>
                            <input type="text" class="form-control input-sm" id="order_customer_email" name="order_customer_email" placeholder="Email khách hàng" @if(isset($search['order_customer_email']))value="{{$search['order_customer_email']}}"@endif>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="name" class="control-label">Đặt hàng từ ngày </label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="time_start_time" name="time_start_time"  data-date-format="dd-mm-yyyy" value="@if(isset($data['time_start_time'])){{date('d-m-Y',$data['time_start_time'])}}@endif">
                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="name" class="control-label">đến ngày</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="time_end_time" name="time_end_time"  data-date-format="dd-mm-yyyy" value="@if(isset($data['time_end_time'])){{date('d-m-Y',$data['time_end_time'])}}@endif">
                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_user_shop_id">ĐH của Shop</label>
                            <select name="order_user_shop_id" id="order_user_shop_id" class="form-control input-sm chosen-select-deselect" tabindex="12" data-placeholder="Chọn tên shop">
                                <option value=""></option>
                                @foreach($arrShop as $shop_id => $shopName)
                                    <option value="{{$shop_id}}" @if($search['order_user_shop_id'] == $shop_id) selected="selected" @endif>{{$shopName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_status">Trạng thái</label>
                            <select name="order_status" id="order_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                        <div class="form-group col-lg-12 text-right">
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> đơn hàng @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="5%" class="text-center">STT</th>
                            <th width="30%">Thông tin đơn hàng</th>
                            <th width="30%" class="text-left">Thông tin khách hàng</th>
                            <th width="8%" class="text-center">Ngày đặt</th>
                            <th width="12%" class="text-center">Tình trạng ĐH</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center text-middle">{{ $stt + $key+1 }}</td>
                                <td>
                                    Mã ĐH: {{ $item->order_id }}
                                    <br/>Mã SP: {{ $item->order_product_id }}
                                    <br/>Tổng tiền: <b class="red">{{ FunctionLib::numberFormat($item->order_total_money) }} đ</b>
                                    <br/>Phí ship: <b class="red">{{ FunctionLib::numberFormat($item->order_money_ship) }} đ</b>
                                    <br/>Tổng SL: <b>{{ $item->order_total_buy }}</b> sản phẩm
                                </td>
                                <td>
                                    @if($item->order_customer_name != '')Tên KH: <b>{{ $item->order_customer_name }}</b><br/>@endif
                                    @if($item->order_customer_phone != '')Phone: {{ $item->order_customer_phone }}<br/>@endif
                                    @if($item->order_customer_email != '')Email: {{ $item->order_customer_email }}<br/>@endif
                                    @if($item->order_customer_address != '')Địa chỉ: {{ $item->order_customer_address }}<br/>@endif
                                    @if($item->order_customer_note != '')<span class="red">**Ghi chú: {{ $item->order_customer_note }}</span>@endif
                                </td>
                                <td class="text-center text-middle">{{ date ('d-m-Y H:i:s',$item->order_time_creater) }}</td>
                                <td class="text-center text-middle">
                                    @if(isset($arrStatus[$item->order_status])){{$arrStatus[$item->order_status]}}@else --- @endif
                                    @if($is_root)
                                         <br/><a href="javascript:void(0);" onclick="Admin.deleteItem({{$item->order_id}},8)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                     @endif
                                    <span class="img_loading" id="img_loading_{{$item->order_id}}"></span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        {{$paging}}
                    </div>
                @else
                <div class="alert">
                    Không có dữ liệu
                </div>
                @endif
                            <!-- PAGE CONTENT ENDS -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.page-content -->
</div>

<script>
    $(document).ready(function(){
        var checkin = $('#time_start_time').datepicker({ });
        var checkout = $('#time_end_time').datepicker({ });
    });
    //tim kiem cho shop
    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Không có kết quả'}
        //      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
</script>
