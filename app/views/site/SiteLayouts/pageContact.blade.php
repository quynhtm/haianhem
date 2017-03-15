<div class="col-middle contact">
	<h1 class="title-path"><a href="{{URL::route('site.pageContact')}}" title="Liên hệ">Liên hệ</a></h1>
	<div class="info-contact">
		{{$info}}
	</div>
	{{Form::open(array('method' => 'POST', 'id'=>'formSendContact', 'class'=>'formSendContact', 'name'=>'txtForm'))}}
	{{$messages}}
	<div class="col-lg-10 col-md-10 col-sm-10">
		<div class="row">
			<div class="form-group">
				<label class="control-label">Họ và tên<span>(*)</span></label>
				<input id="txtName" name="txtName" class="form-control" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">Số điện thoại<span>(*)</span></label>
				<input id="txtMobile" name="txtMobile" class="form-control" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">Email</label>
				<input id="txtEmail" name="txtEmail" class="form-control" type="text">
			</div>
			<div class="form-group">
				<label class="control-label">Tiêu đề liên hệ<span>(*)</span></label>
				<input id="txtTitle" name="txtTitle" class="form-control" type="text">
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label">Nội dung<span>(*)</span></label>
		<textarea id="txtMessage" name="txtMessage" class="form-control" rows="5"></textarea>
	</div>
	<div class="form-group">
		<label class="control-label labelInputCaptchar">Xác nhận<span>(*)</span></label>
		<input type="text" id="securityCode" name="captcha" maxlength="255" class="txtInputCaptchar"/>
		<img id="imageCaptchar" src="{{URL::route('site.home')}}/captcha?rand=<?php echo rand();?>" class="imageCaptchar">
		<a href="javascript:void(0)" class="iconRefreh" onclick="SITE.refreshCaptcha();" title="Mã an toàn mới"></a>
	</div>
	<div class="form-group">
		<button type="submit" id="submitContact" class="btn btn-primary btn-ext">Gửi đi</button>
	</div>
	{{Form::close()}}
</div>