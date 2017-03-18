<div class="list-popup zindex99999">
	<div id="sys-popup-clickShowPopupFanpagePageInAction" class="content-popup-show fade zindex99999" style="display:none">
		<div class="modal-dialog modal-dialog-content">
	        <div class="modal-header">
	        	<div class="modal-title">Chọn trang để kích hoạt <span class="btn-close pull-right sound" data-dismiss="modal">×</span></div>
	        </div>
	        <div class="modal-content">
	            <div class="content-popup-body">
	              <div class="list-page-not-active text-center">
		           @if(isset($member['list_page']) && !empty($member['list_page']))
						@foreach($member['list_page'] as $page)
							@if(isset($page['member_page_status']) && $page['member_page_status'] == CGlobal::status_hide)
								@if(isset($page['member_page_facebook_id']) && $page['member_page_facebook_id'] != '')
								<div class="item-fanpage-page sound" title="{{$page['member_page_facebook_name']}}" data-id="{{$page['member_page_facebook_id']}}">
					               	<img src="https://graph.facebook.com/{{$page['member_page_facebook_id']}}/picture?height=250&amp;width=250">
					            </div>
					           @endif
							@endif
						@endforeach
					@endif
	              </div>
	            </div>
	        </div>
	        <div class="modal-footer">
	        	<div class="modal-footer-wp text-left">
	        		<span class="first-message">Vui lòng chọn trang</span> <span class="actPageClick box-spc"></span>
	        		<button disabled="" class="btnPageAcitve btn btn-xs btn-primary pull-right">Kích hoạt</button>
	        	</div>
	        </div>
	    </div>
	</div>
</div>