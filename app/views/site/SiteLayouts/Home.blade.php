@if(isset($member['member_id_facebook']) && $member['member_id_facebook'] != '')
    Thanh cong
@else
    <div class="box-front">
        <div class="main-text-front">{{CGlobal::web_name}}</div>
        <button id="clickLoginFacebook" class="login-facebook">
            <span class="fa fa-facebook"></span> Login with Facebook
        </button>
    </div>
@endif