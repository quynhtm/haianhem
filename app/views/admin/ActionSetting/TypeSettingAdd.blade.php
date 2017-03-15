<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.typeSettingView')}}"> Danh sách Type setting</a></li>
            <li class="active">@if($id > 0)Cập nhật Type setting @else Tạo mới Type setting @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('role'=>'form','files' => true))}}
                @if(isset($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif

                <div style="float: left; width: 50%">
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Tên type<span class="red"> (*) </span></label>
                            <input type="text" id="type_title" name="type_title"  class="form-control input-sm" value="@if(isset($data['type_title'])){{$data['type_title']}}@endif">
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Thuộc nhóm</label>
                            <input type="text" @if($id > 0 && isset($data['type_group']) && $data['type_group'] !='') readonly @endif id="type_group" name="type_group"  class="form-control input-sm" value="@if(isset($data['type_group'])){{$data['type_group']}}@endif">
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Key word</label>
                            <input type="text" @if($id > 0 && isset($data['type_keyword']) && $data['type_keyword'] !='') readonly @endif id="type_keyword" name="type_keyword"  class="form-control input-sm" value="@if(isset($data['type_keyword'])){{$data['type_keyword']}}@endif">
                        </div>
                    </div>


                    <div class="clearfix"></div>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Thông tin thêm</label>
                            <input type="text" id="type_infor" name="type_infor"  class="form-control input-sm" value="@if(isset($data['type_infor'])){{$data['type_infor']}}@endif">
                        </div>
                    </div>


                    <div class="clearfix"></div>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Trạng thái</label>
                            <select name="type_status" id="type_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Vị trí hiển thị</label>
                            <input type="text" placeholder="Vị trí hiển thị" id="type_order" name="type_order"  class="form-control input-sm" value="@if(isset($data['type_order'])){{$data['type_order']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-left">
                        <a class="btn btn-warning" href="{{URL::route('admin.typeSettingView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                        <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                    </div>
                    <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
