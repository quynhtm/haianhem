<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.categoryView',array('category_type'=>$category_type))}}"> Danh sách danh mục</a></li>
            <li class="active">@if($id > 0)Cập nhật danh mục @else Tạo mới danh mục @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('method' => 'POST','role'=>'form','files' => true))}}
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
                            <label for="name" class="control-label">Tên danh mục<span class="red"> (*) </span></label>
                            <input type="text" placeholder="Tên danh mục" id="category_name" name="category_name"  class="form-control input-sm" value="@if(isset($data['category_name'])){{$data['category_name']}}@endif">
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Loại danh mục</label>
                            <select name="category_type" id="category_type" class="form-control input-sm" readonly>
                                {{$optionTypeCategory}}
                            </select>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Thuộc danh mục cha</label>
                            <select name="category_parent_id" id="category_parent_id" class="form-control input-sm">
                                <option value="0">--- Chọn danh mục cha ---</option>
                                {{$optionCategoryParent}}
                            </select>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="name" class="control-label">Trạng thái</label>
                            <select name="category_status" id="category_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="name" class="control-label">Thứ tự hiển thị</label>
                            <input type="text" placeholder="Thứ tự hiển thị" id="category_order" name="category_order"  class="form-control input-sm" value="@if(isset($data['category_order'])){{$data['category_order']}}@endif">
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-left">
                        <a class="btn btn-warning" href="{{URL::route('admin.categoryView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                        <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                    </div>
                    <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                </div>

                <div style="float: left; width: 50%">
                    <div class="col-sm-10" style="display: none">
                        <div class="form-group">
                            <label for="name" class="control-label">Hiển thị menu header</label>
                            <select name="category_show_top" id="category_show_top" class="form-control input-sm">
                                @foreach ($arrShowHide as $key_top => $category_show_top)
                                    <option value="{{$key_top}}" @if(isset($data['category_show_top']) && $data['category_show_top'] == $key_top) selected @endif>{{$category_show_top}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div><!-- /.page-content -->
</div>
