<?php
/*
* @Created by: HSS
* @Author    : nguyenduypt86@gmail.com
* @Date      : 08/2016
* @Version   : 1.0
*/

//Index
Route::any('/', array('as' => 'site.home','uses' => 'SiteHomeController@index'));
//Login Facebook
Route::match(['GET','POST'], 'facebooklogin', array('as' => 'loginFacebook','uses' => 'MemberLoginController@loginFacebook'));
Route::match(['GET','POST'],'thoat', array('as' => 'member.logout','uses' => 'MemberLoginController@logout'));
Route::match(['GET','POST'], 'ajaxActFanpage', array('as' => 'site.ajaxActFanpage','uses' => 'IndexController@ajaxActFanpage'));

//Get List Conversation
Route::match(['GET','POST'], 'page/{id?}', array('as' => 'admin.getFullPageChat','uses' => 'ChatController@getFullPageChat'))->where('id', '[a-z0-9]+');
Route::match(['GET','POST'], 'ajaxGetMessagesConversation', array('as' => 'admin.ajaxGetMessagesConversation','uses' => 'ChatController@ajaxGetMessagesConversation'));
Route::match(['GET','POST'], 'ajaxSendMessageInConversation', array('as' => 'admin.ajaxSendMessageInConversation','uses' => 'ChatController@ajaxSendMessageInConversation'));
Route::match(['GET','POST'], 'webhooksGetMessageInConversation', array('as' => 'admin.webhooksGetMessageInConversation','uses' => 'ChatController@webhooksGetMessageInConversation'));