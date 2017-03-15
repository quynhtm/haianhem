<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.user_view')}}"> Danh sách tài khoản</a></li>
            <li class="active">@if($id > 0)Cập nhật thông tin user @else Tạo mới thông tin user @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content marginTop30">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('method' => 'POST','role'=>'form','files' => true))}}
                @if(isset($error) && !empty($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif
                <div style="float: left; width: 50%">
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label for="name" class="control-label">Tài khoản đăng nhập<span class="red"> (*) </span></label>
                            <input type="text" placeholder="Tài khoản đăng nhập" id="user_name" name="user_name"  class="form-control input-sm" value="@if(isset($data['user_name'])){{$data['user_name']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Tên nhân viên</label>
                            <input type="text" placeholder="Tên nhân viên" id="user_full_name" name="user_full_name"  class="form-control input-sm" value="@if(isset($data['user_full_name'])){{$data['user_full_name']}}@endif">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="control-label">Chức vụ</label>
                            <input type="text" placeholder="Chức vụ" id="user_service" name="user_service"  class="form-control input-sm" value="@if(isset($data['user_service'])){{$data['user_service']}}@endif">
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="control-label">Email</label>
                            <input type="text" placeholder="Email" id="user_email" name="user_email"  class="form-control input-sm" value="@if(isset($data['user_email'])){{$data['user_email']}}@endif">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="control-label">Số điện thoại</label>
                            <input type="text" placeholder="Số điện thoại" id="user_phone" name="user_phone"  class="form-control input-sm" value="@if(isset($data['user_phone'])){{$data['user_phone']}}@endif">
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="name" class="control-label">Ngày bắt đầu làm việc</label>
                            <input type="text" class="form-control" id="user_time_work_start" name="user_time_work_start"  data-date-format="dd-mm-yyyy" value="@if(isset($data['user_time_work_start']) && $data['user_time_work_start'] > 0){{date('d-m-Y',$data['user_time_work_start'])}}@endif">
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="name" class="control-label">Ngày nghỉ việc</label>
                            <input type="text" class="form-control" id="user_time_work_end" name="user_time_work_end"  data-date-format="dd-mm-yyyy" value="@if(isset($data['user_time_work_end']) && $data['user_time_work_end'] > 0){{date('d-m-Y',$data['user_time_work_end'])}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="name" class="control-label">Trạng thái</label>
                            <select name="user_status" id="user_status" class="form-control input-sm">
                                {{$optionStatus}}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="name" class="control-label">Giới tinh</label>
                            <select name="user_sex" id="user_sex" class="form-control input-sm">
                                {{$optionSex}}
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12 text-left">
                        <a class="btn btn-warning" href="{{URL::route('admin.user_view')}}"><i class="fa fa-reply"></i> Trở lại</a>
                        <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                    </div>
                    <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                </div>

                <div style="float: left; width: 50%">
                    <div id="show_category_sub_campaign" class="body">
                        <div class="form-group">
                            <label for="textDescrip" class="control-label col-lg-12 font2">Thuộc khoa, ngành</label>
                        </div>
                        @if(isset($arrDepart) && !empty($arrDepart))
                            <div style="float: left; width: 100%; height: 400px;overflow: hidden; overflow-x: hidden;overflow-y: scroll">
                                <table class="table table-bordered table-hover">
                                    @foreach ($arrDepart as $depart_id => $depart_name)
                                        <tr>
                                            <td class="text-center text-middle">
                                                    <input type="checkbox" class="checkItem" name="checkGroupDepart[]"
                                                           @if(in_array($depart_id,$arrUserGroupDepart)) checked="checked" @endif
                                                           value="{{(int)$depart_id}}" />
                                            </td>
                                            <td class="text-left text-middle">
                                                    <b>{{$depart_name}}</b>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="clearfix"></div>
                <hr/>
                <h2>Thuộc nhóm quyền</h2>
                @foreach($arrGroupUser as $key => $val)
                    <div class="form-group col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="user_group[]" id="user_group_{{$key}}" value="{{$key}}" @if(isset($data['user_group']) && in_array($key,$data['user_group'])) checked="checked" @endif> {{$val}}
                            </label>
                        </div>
                    </div>
                @endforeach
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var checkin = $('#user_time_work_start').datepicker({ });
        var checkout = $('#user_time_work_end').datepicker({ });
    });
</script>