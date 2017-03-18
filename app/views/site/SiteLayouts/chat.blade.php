@if(isset($member['member_id_facebook']) && $member['member_id_facebook'] != '')
	<div class="panel-page-chat">
		<div class="sidebar">
			<ul class="sidebar-nav">
				<li class="act"><i class="glyphicon glyphicon-inbox"></i><span>Hộp thư đến</span></li>
				<li><i class="glyphicon glyphicon-asterisk"></i><span>Lọc chưa đọc</span></li>
				<li><i class="glyphicon glyphicon-envelope"></i><span>Lọc tin nhắn</span></li>
				<li><i class="glyphicon glyphicon-comment"></i><span>Lọc bình luận</span></li>
				<li><i class="glyphicon glyphicon-star-empty"></i><span>Lọc đánh giá</span></li>
				<li><i class="glyphicon glyphicon-earphone"></i><span>Lọc số ĐT</span></li>
				<li><i class="glyphicon glyphicon-time"></i><span>Lọc theo thời gian</span></li>
				<li><i class="glyphicon glyphicon-list-alt"></i><span>Lọc theo bài viết</span></li>
			</ul>
		</div>
		<div class="box-chat">
			<div class="box-conversation">
				<div class="list-conversation" id="list-conversation">
					@if(isset($arrMessages['data']) && sizeof($arrMessages['data']) > 0)
						@foreach($arrMessages['data'] as $item)
						<div class="item-conversation" dataid="@if($item['id']){{$item['id']}}@endif" datac="{{$id}}" datacid="@if($item['senders']['data'][0]['id']){{$item['senders']['data'][0]['id']}}@endif" datacname="@if(isset($item['senders']['data'][0]['name'])){{$item['senders']['data'][0]['name']}}@endif">
							@if(isset($item['senders']) && sizeof($item['senders']) > 0)
							@if(isset($item['senders']['data']) && sizeof($item['senders']['data']) > 0)
							<div class="item-conversation-thumb">
								<img src="https://graph.facebook.com/@if($item['senders']['data'][0]['id']){{$item['senders']['data'][0]['id']}}@endif/picture?height=100&amp;width=100">
							</div>
							@endif
							@endif
							<div class="item-conversation-intro">
								<p class="item-name">@if(isset($item['senders']['data'][0]['name'])) {{$item['senders']['data'][0]['name']}} @endif</p>
								<p class="item-message-last">@if(isset($item['snippet'])) {{$item['snippet']}} @endif</p>
								<p class="item-date-last">
									<span class="item-message-date">@if(isset($item['updated_time'])) {{date('d/m/Y H:i', strtotime($item['updated_time']))}} @endif</span>
									<span class="item-message-inbox"><span class="pull-right fa fa-envelope"></span></span>
								</p>
							</div>
						</div>
						@endforeach
					@else
						<div class="not-item">Chưa có cuộc hội thoại nào.</div>
					@endif
				</div>
			</div>
			<div class="messages-conversation" id="messages-conversation">
				<div class="loading"></div>
				<div class="inline-box-chat-info">
					<div class="inline-box-chat-info-thumb"></div>
					<div class="inline-box-chat-info-intro">
						<p class="item-name"></p>
						<p class="item-date-last"></p>
					</div>
				</div>
				<div class="inline-box-chat-messages" id="inline-box-chat-messages">
					<!--Tat ca cac hoi thoai hien thi o day-->
				</div>
				<div class="box-send-input-messages">
					<textarea id="replyMessagesBox" placeholder="Nhập nội dung tin nhắn"></textarea>
					<input type="hidden" id="datacid"/>
					<input type="hidden" id="datacname"/>
					<input type="hidden" id="datac" @if(isset($id) && $id != '') value="{{$id}}" @endif/>
					<input type="hidden" id="dataid"/>
					<input type="hidden" id="mid" @if(isset($member['member_id_facebook']) && $member['member_id_facebook'] != '') value="{{$member['member_id_facebook']}}" @endif/>
                    <input type="hidden" id="mname" @if(isset($member['member_full_name']) && $member['member_full_name'] != '') value="{{$member['member_full_name']}}" @endif/>
					<div class="note-mess">
						<span class="note-sc">Nhấn enter</span>
						<i> để gửi tin nhắn</i>
					</div>
					<div class="files-upload-send">
						<span class="fa fa-camera reply-box-button"></span>
					</div>
				</div>
			</div>
			<div class="box-info-order">
				<div class="tab-info-order">
					<ul>
						<li class="act">Thông tin</li>
						<li>Tạo đơn hàng</li>
					</ul>
				</div>
				<div class="box-info-order-input">
					<div lass="">
						<div class="col-lg-6 col-md-6 col-sm-6 txt-input">
							<input placeholder="Họ tên" class="form-control">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 txt-input">
							<input placeholder="Điện thoại" class="form-control">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 txt-input">
							<input placeholder="Địa chỉ" class="form-control">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 txt-input">
							<input placeholder="Kho hàng" class="form-control">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 txt-input">
							<input placeholder="Mã đơn hàng" class="form-control">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 txt-input">
							<input placeholder="Tổng tiền" class="form-control">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 txt-input">
							<input placeholder="Phí vận chuyển" class="form-control">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 txt-input">
							<textarea placeholder="Ghi chú" class="form-control"></textarea>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 txt-input">
							<div id="clickAddOrderAction" class="btn btn-primary">Lưu đơn hàng</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@else
<div class="box-front">
	<div class="main-text-front">{{CGlobal::nameSite}}</div>
	<button id="clickLoginFacebook" class="login-facebook">
		<span class="fa fa-facebook"></span> Login with Facebook
	</button>
</div>
@endif