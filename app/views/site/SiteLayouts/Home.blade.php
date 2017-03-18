@if(isset($member['member_id_facebook']) && $member['member_id_facebook'] != '')
    <div class="panel-page">
        <div class="line-page list-page-active">
            @if(isset($member['list_page']) && !empty($member['list_page']))
                <?php $i=0; ?>
                @foreach($member['list_page'] as $page)
                    @if(isset($page['member_page_status']) && ($page['member_page_status'] == CGlobal::status_show || $page['member_page_status'] == CGlobal::status_block))
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-xs-12">
                            <a href="{{URL::route('site.home')}}/page/{{$page['member_page_facebook_id']}}" class="item sound">
                                <div class="thumb">
                                    @if(isset($page['member_page_facebook_id']) && $page['member_page_facebook_id'] != '')
                                        <img src="https://graph.facebook.com/{{$page['member_page_facebook_id']}}/picture?height=150&amp;width=150">
                                    @endif
                                </div>
                                <div class="item-content-intro">
                                    @if(isset($page['member_page_facebook_name']) && $page['member_page_facebook_name'] != '')
                                        <div class="name-page-active">{{$page['member_page_facebook_name']}}</div>
                                    @endif
                                    @if(isset($page['member_page_facebook_id']) && $page['member_page_facebook_id'] != '')
                                        <div class="link-page-active"><span class="fa fa-facebook"></span> /{{$page['member_page_facebook_id']}}</div>
                                    @endif
                                </div>
                            </a>
                        </div>
                        <?php $i++; ?>
                    @endif
                @endforeach

                @if($i == 0)
                    <div class="text-center red">Chưa có fanpage nào được kích hoạt!</div>
                @endif

            @endif
        </div>
        <div class="clickActionPage">Click vào <span class="box-spc">Kích hoạt</span> để xem danh sách và kích hoạt trang mới</div>
        <div class="line-page text-center">
            <div id="clickShowPopupFanpagePageInAction" class="btn btn-primary">Kích hoạt</div>
        </div>
    </div>
@else
    <div class="box-front">
        <div class="main-text-front">{{CGlobal::web_name}}</div>
        <button id="clickLoginFacebook" class="login-facebook">
            <span class="fa fa-facebook"></span> Login with Facebook
        </button>
    </div>
@endif