@if(isset($member['member_id_facebook']) && $member['member_id_facebook'] != '')
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header"><a href="{{URL::route('site.home')}}" class="navbar-brand">{{CGlobal::web_name}}</a>
                @if(sizeof($fanpage_current) > 0)
                    <ul class="fnc-tab">
                        <li class="act"><a href="#"><i class="glyphicon glyphicon-envelope"></i> Hội thoại</a></li>
                        <li><a href="#"><i class="glyphicon glyphicon-list-alt"></i> Bài viết</a></li>
                        <li><a href="#"><i class="glyphicon glyphicon-equalizer"></i> Thống kê</a></li>
                        <li><a href="#"><i class="glyphicon glyphicon-cog"></i> Cài đặt</a></li>
                    </ul>
                @endif
                <ul class="nav navbar-nav navbar-right">
                    <li class="avatar-navbar custom-dropdown-menu">
                        <a href="javascript:void(0)">
                            <div class="line-user">
                                @if(sizeof($fanpage_current) == 0)
                                    <span class="line-user-name">@if(isset($member['member_full_name']) && $member['member_full_name'] != '') {{$member['member_full_name']}} @else No Name @endif</span>
                                @else

                                    @if(isset($fanpage_current['member_page_facebook_name']) && $fanpage_current['member_page_facebook_name'] != '')
                                        <span  class="line-user-name">{{$fanpage_current['member_page_facebook_name']}}</span>
                                    @endif

                                    @if(isset($fanpage_current['member_page_facebook_id']) && $fanpage_current['member_page_facebook_id'] != '')
                                        <img src="https://graph.facebook.com/{{$fanpage_current['member_page_facebook_id']}}/picture?height=100&amp;width=100">
                                    @endif

                                @endif

                                @if(isset($member['member_id_facebook']) && $member['member_id_facebook'] != '')
                                    <img src="https://graph.facebook.com/{{$member['member_id_facebook']}}/picture?height=100&amp;width=100">
                                @endif
                            </div>
                        </a>
                        <div class="member-cpanel" style="display:none">

                            @if(isset($member['list_page']) && sizeof($member['list_page']) > 0)
                                <div class="list-page-item">
                                    @foreach($member['list_page'] as $page)
                                        @if(isset($page['member_page_status']) && ($page['member_page_status'] == CGlobal::status_show || $page['member_page_status'] == CGlobal::status_block))
                                            <a class="sound" href="{{URL::route('site.home')}}/page/{{$page['member_page_facebook_id']}}" @if(isset($fanpage_current['member_page_facebook_id']) && $fanpage_current['member_page_facebook_id'] == $page['member_page_facebook_id']) style="display:none" @endif>
                                                <div class="option-item">
                                                    @if(isset($page['member_page_facebook_id']) && $page['member_page_facebook_id'] != '')
                                                        <div class="option-item-thumb">
                                                            <img src="https://graph.facebook.com/{{$page['member_page_facebook_id']}}/picture?height=150&amp;width=150">
                                                        </div>
                                                    @endif
                                                    @if(isset($page['member_page_facebook_name']) && $page['member_page_facebook_name'] != '')
                                                        <div class="option-item-name">{{$page['member_page_facebook_name']}}</div>
                                                    @endif
                                                </div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            <div class="navbar-option"><a href="{{URL::route('site.home')}}" title="logout" class="sound"><i class="fa fa-table"></i> Bảng điều khiển</a></div>
                            <div class="navbar-option"><a href="{{URL::route('member.logout')}}" title="logout" class="sound"><i class="fa fa-sign-out"></i> Đăng xuất</a></div>
                        </div>
                    </li>
                    <li class="custom-drop-note">
                        <a id="notification" href="javascript:void(0)" class="sound">
                            <span class="fa fa-bell-o fontsize20"></span>
                        </a>
                    </li>
                </ul>
            </div>
    </nav>
@endif