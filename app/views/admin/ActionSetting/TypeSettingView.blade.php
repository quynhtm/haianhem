<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li class="active">Danh mục Type setting</li>
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
                            <label for="department_name">Tên type</label>
                            <input type="text" class="form-control input-sm" id="type_title" name="type_title" placeholder="Tên khoa - trung tâm" @if(isset($search['type_title']) && $search['type_title'] != '')value="{{$search['department_name']}}"@endif>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="department_status">Trạng thái</label>
                            <select name="type_status" id="type_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                        
                    </div>
                    <div class="panel-footer text-right">
                        @if($is_root || $permission_full ==1 || $permission_create == 1)
                        <span class="">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('admin.typeSettingEdit')}}">
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
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> khoa - trung tâm @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="2%"class="text-center">STT</th>
                            <th width="1%" class="text-center"><input type="checkbox" id="checkAll"/></th>
                            <th width="25%" class="td_list">Tên type</th>
                            <th width="10%" class="text-center">Key word</th>
                            <th width="10%" class="text-center">Group</th>
                            <th width="20%" class="td_list">Thông tin thêm</th>
                            <th width="5%" class="text-center">Status</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td class="text-center"><input class="check" type="checkbox" name="checkItems[]" id="sys_checkItems" value="{{$item['type_id']}}"></td>
                                <td>
                                    [<b>{{ $item['type_id'] }}</b>] {{ $item['type_title'] }}
                                </td>
                                <td class="text-center">{{ $item['type_keyword'] }}</td>
                                <td class="text-center">{{ $item['type_group'] }}</td>
                                <td>{{ $item['type_infor'] }}</td>

                                <td class="text-center">
                                    @if($item['type_status'] == 1)
                                        <a href="javascript:void(0);" onclick="Admin.updateStatusItem({{$item['type_id']}},{{$item['type_status']}},2)"title="Hiện"><i class="fa fa-check fa-2x"></i></a>
                                    @else
                                        <a href="javascript:void(0);" onclick="Admin.updateStatusItem({{$item['type_id']}},{{$item['type_status']}},2)"style="color: red" title="Ẩn"><i class="fa fa-close fa-2x"></i></a>
                                    @endif
                                    <span class="img_loading" id="img_loading_{{$item['type_id']}}"></span>
                                </td>

                                <td class="text-center">
                                    @if($is_root || $permission_full ==1|| $permission_edit ==1  )
                                        <a href="{{URL::route('admin.typeSettingEdit',array('id' => $item['type_id']))}}" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                    @endif
                                    @if($is_root)
                                       &nbsp;&nbsp;&nbsp;
                                       <a href="javascript:void(0);" onclick="Admin.deleteItem({{$item['type_id']}},10)" title="Xóa Item"><i class="fa fa-trash fa-2x"></i></a>
                                    @endif
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
<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    $(document).ready(function() {
        $("#checkAll").click(function () {
            $(".check").prop('checked', $(this).prop('checked'));
        });
    });
</script>