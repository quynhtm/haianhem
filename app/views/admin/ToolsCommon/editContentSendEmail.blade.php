<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.contentSendEmail_list')}}"> Danh sách nội dung gửi mail</a></li>
            <li class="active">@if($id > 0)Cập nhật nội dung gửi mail @else Tạo mới nội dung gửi mail @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('method' => 'POST','role'=>'form','files' => true))}}
                @if(isset($error) && sizeof($error) > 0)
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name" class="control-label">Tiêu đề gửi email</label>
                        <input type="text" placeholder="Tiêu đề mail" id="mail_send_title" name="mail_send_title" class="form-control input-sm" value="@if(isset($data['mail_send_title'])){{$data['mail_send_title']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name" class="control-label">Link view</label>
                        <input type="text" placeholder="Link view" id="mail_send_link" name="mail_send_link" class="form-control input-sm" value="@if(isset($data['mail_send_link'])){{$data['mail_send_link']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name" class="control-label">Sản phẩm liên quan</label>
                        <input type="text" placeholder="Danh sách sản phẩm: 1,2,3" id="mail_send_str_product_id" name="mail_send_str_product_id" class="form-control input-sm" value="@if(isset($data['mail_send_str_product_id'])){{$data['mail_send_str_product_id']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name" class="control-label">Trạng thái</label>
                        <select name="mail_send_status" id="mail_send_status" class="form-control input-sm">
                            {{$optionStatus}}
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Nội dung gửi mail</label>
                        <textarea class="form-control input-sm" rows="8" name="mail_send_content">@if(isset($data['mail_send_content'])){{$data['mail_send_content']}}@endif</textarea>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-sm-12 text-left">
                    <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                </div>
                {{ Form::close() }}
                <!-- PAGE CONTENT ENDS -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.page-content -->
</div>
<script>
    CKEDITOR.replace('mail_send_content', {height:400});
</script>
