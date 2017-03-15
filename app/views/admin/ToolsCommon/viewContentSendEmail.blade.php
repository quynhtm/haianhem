<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li class="active">Quản lý nội dung gửi email</li>
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
                            <label for="mail_send_title">Title gửi email</label>
                            <input type="text" class="form-control input-sm" id="mail_send_title" name="mail_send_title" placeholder="Tên đăng nhập" @if(isset($search['mail_send_title']) && $search['mail_send_title'] != '')value="{{$search['mail_send_title']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="order_status">Trạng thái</label>
                            <select name="mail_send_status" id="mail_send_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        @if($is_root || $permission_full ==1 || $permission_create == 1)
                            <span class="">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('admin.contentSendEmail_edit')}}">
                                <i class="ace-icon fa fa-plus-circle"></i>
                                Thêm mới
                            </a>
                        </span>
                        @endif
                        <span class="">
                            <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </span>
                    </div>
                    {{ Form::close() }}
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> item @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="5%" class="text-center">STT</th>
                            <th width="15%">Title mail</th>
                            <th width="45%" class="text-center">Nội dung </th>
                            <th width="15%">Sản phẩm liên quan</th>
                            <th width="10%" class="text-center">Tạo ngày</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center">
                                    {{ $stt + $key+1 }}
                                </td>
                                <td>
                                    [<b>{{ $item->mail_send_id }}</b>] {{ $item->mail_send_title }}
                                </td>
                                <td>
                                    {{ $item->mail_send_content }}
                                </td>
                                <td class="text-center text-middle">
                                    {{ $item->mail_send_str_product_id }}
                                </td>
                                <td class="text-center text-middle">
                                    {{date('d-m-Y',$item->mail_send_time_creater)}}
                                </td>
                                <td class="text-center text-middle">
                                    @if($item->mail_send_status == CGlobal::status_show)
                                        <a href="javascript:void(0);" title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                    @else
                                        <a href="javascript:void(0);" style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                    @endif
                                    @if($is_root || $permission_full ==1|| $permission_edit ==1  )
                                        <a href="{{URL::route('admin.contentSendEmail_edit',array('id' => $item->mail_send_id))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                    @endif
                                    @if($is_root || $permission_full ==1 || $permission_delete == 2)
                                        <a href="javascript:void(0);" onclick="Admin.deleteItem({{$item->mail_send_id}},7)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                    @endif
                                    <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading_{{$item->mail_send_id}}">
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

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.page-content -->
</div>

