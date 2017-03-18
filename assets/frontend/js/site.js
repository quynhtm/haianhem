jQuery(document).ready(function($){
    SITE.sound();
    SITE.clickLoginFacebook();
    SITE.clickShowPopupFanpagePageInAction();
    SITE.clickAddActiveFanpage();
    SITE.clickOneConversation();
    SITE.replyMessagesInConversation();
});
var CONFIG = {
    host: 	'chat.quanly.org',
    port: 	'3333',
}

SITE = {

    clickLoginFacebook:function(){
        jQuery('#clickLoginFacebook').click(function(){
            $.oauthPopup({
                path: WEB_ROOT+'/facebooklogin',
                width:300,
                height:200,
                callback: function(){
                    window.location.reload();
                }
            });
        });
    },
    sound:function(){
        soundManager.setup({
            debugMode:!1,
            onready:function(){
                soundManager.createSound({
                    id: "drop",url: WEB_ROOT + '/assets/libs/Sound/drop.mp3',
                }),
                    soundManager.createSound({
                        id: "notify",url: WEB_ROOT + '/assets/libs/Sound/notify.mp3'
                    })
            }
        });
        $('.sound').click(function(){
            soundManager.play('notify');
        });
    },
    clickShowPopupFanpagePageInAction:function(){
        jQuery('#clickShowPopupFanpagePageInAction').unbind("click").click(function(){
            jQuery('#sys-popup-clickShowPopupFanpagePageInAction').modal('show');
        });
    },
    clickAddActiveFanpage:function(){
        jQuery('.list-page-not-active .item-fanpage-page').unbind("click").click(function(){
            jQuery('.list-page-not-active .item-fanpage-page').removeClass('act');
            jQuery(this).addClass('act');
            var name=jQuery(this).attr('title');
            var dataid=jQuery(this).attr('data-id');
            if(dataid != ''){
                jQuery('.btnPageAcitve').addClass('act').removeAttr('disabled');
                jQuery('.first-message').text('Vui lòng xác nhận kích hoạt trang');
                jQuery('.actPageClick').addClass('act').text(name);
                var url = WEB_ROOT+'/ajaxActFanpage';
                jQuery('.btnPageAcitve.act').click(function(){
                    jQuery.ajax({
                        type: "POST",
                        url: url,
                        data: "dataid="+encodeURI(dataid),
                        success: function(data){
                            if(data == 1){
                                window.location.reload();
                                return false;
                            }
                        }
                    });
                });
            }
        });
    },
    clickOneConversation:function(){
        var socket = io.connect("http://"+CONFIG.host+':'+CONFIG.port);
        $('.item-conversation').click(function(){
            var dataid = $(this).attr('dataid');
            var datac = $(this).attr('datac');
            var mid = $('input#mid').val();
            var mname = $('input#mname').val();
            var _imgc = $(this).find('.item-conversation-thumb img').attr('src');

            $('.item-conversation').removeClass('act');
            $(this).addClass('act');

            $('input#datacid').val($(this).attr('datacid'));
            $('input#datacname').val($(this).attr('datacname'));
            $('input#datac').val($(this).attr('datac'));
            $('input#dataid').val($(this).attr('dataid'));

            $('.inline-box-chat-info-thumb').html('<img src="'+_imgc+'">');
            $('.inline-box-chat-info-intro .item-name').text($(this).attr('datacname'));
            $('.inline-box-chat-info-intro .item-date-last').text('Đã xem bởi ' + mname);
            $('.loading').show();

            if(dataid != '' && datac != '' && mid != ''){
                socket.emit("ajaxGetMessagesConversation", {
                    "dataid": dataid,
                    "datac": datac,
                    "mid": mid,
                });
                socket.on("ajaxGetMessagesConversation", function (jsonData) {
                    $('#inline-box-chat-messages').html('');
                    for (var i = 0; i < jsonData.length; i++) {
                        if (jsonData[i]['messages_type'] == 1) {
                            var _class = 'right';
                            var _avatar = jsonData[i]['messages_from_id'];
                        } else {
                            var _class = 'left';
                            var _avatar = jsonData[i]['messages_from_id'];
                        }
                        var _date = SITE.convertNumToDate(jsonData[i]['messages_created']);
                        var str = '<div class="item-inline-box-chat-messages ' + _class + '"><div class="item-thumb-chat"><img src="https://graph.facebook.com/' + _avatar + '/picture?height=100&amp;width=100"></div><div class="item-intro-chat">' + jsonData[i]['messages_content'] + '</div><div class="item-intro-send"><b>' + jsonData[i]['messages_from'] + '</b><br/><span class="_date">' + _date + '</span></div></div>';
                        $('#inline-box-chat-messages').append(str);
                    }
                    $('.loading').hide();
                    SITE.scrollBoxChat('#inline-box-chat-messages', '.item-inline-box-chat-messages');
                    return false;
                });
            }
        });
    },
    replyMessagesInConversation:function(){
        $('#replyMessagesBox').on('keydown', function(e) {
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == 13){
                var mess = $('#replyMessagesBox').val();
                var datacid = $('#datacid').val();
                var datacname = $('#datacname').val();
                var dataid = $('#dataid').val();
                var datac = $('#datac').val();
                $('#replyMessagesBox').val('');
                if(mess != '' && dataid != '' && datac != ''){
                    var url = WEB_ROOT+'/ajaxSendMessageInConversation';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: "dataid="+encodeURI(dataid) + '&datac='+encodeURI(datac) + '&mess='+encodeURI(mess) + '&datacid='+encodeURI(datacid) + '&datacname='+encodeURI(datacname),
                        success: function(data){
                            if(data != '') {
                                var jsonData = jQuery.parseJSON(data);
                                var _date = SITE.convertNumToDate(jsonData['date']);
                                var str = '<div class="item-inline-box-chat-messages right"><div class="item-thumb-chat"><img src="https://graph.facebook.com/' + jsonData['pageid'] + '/picture?height=100&amp;width=100"></div><div class="item-intro-chat">' + jsonData['mess'] + '</div><div class="item-intro-send"><b>' + jsonData['pageName'] + '</b><br/><span class="_date">' + _date + '</span></div></div>';
                                $('#inline-box-chat-messages').append(str);
                                $('.item-conversation.act .item-message-last').text(jsonData['mess']);
                                $('.item-conversation.act .item-date-last .item-message-date').text(_date);
                            }
                            SITE.scrollBoxChat('#inline-box-chat-messages', '.item-inline-box-chat-messages');
                            return false;
                        }
                    });
                }
                return false;
            }
        });
    },
    convertNumToDate:function(_time){
        var _date = new Date(1000*_time);
        var thetoday=_date.getDate();
        var themonth=_date.getMonth() + 1;
        var theyear=_date.getFullYear();
        var thehours=_date.getHours();
        var theminutes=_date.getMinutes();
        return thetoday+'/'+themonth+'/'+theyear+' '+thehours+':'+theminutes;
    },
    scrollBoxChat:function(classBoxchat, classItemBoxchat){
        var h = 0;
        $(classItemBoxchat).each(function(){
            h += parseInt($(this).height());
        });

        if(h <= 1000000){
            h = 1000000;
        }

        $(classBoxchat).scrollTop(h);
    },
};

(function(jQuery){
    jQuery.oauthPopup = function(options){
        var leftPos = (screen.width/2)-(options.width/2);
        options.windowName = options.windowName || 'ConnectWithOAuth';
        options.windowOptions = options.windowOptions || 'location=0,status=0,width='+options.width+',height='+options.height+',scrollbars=1,left='+leftPos;
        options.callback = options.callback || function(){
                window.location.reload();
            };
        var that = this;
        that._oauthWindow = window.open(options.path, options.windowName, options.windowOptions);
        that._oauthInterval = window.setInterval(function(){
            if (that._oauthWindow.closed) {
                window.clearInterval(that._oauthInterval);
                options.callback();
            }
        }, 2000);
    };
})(jQuery);