<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 08/2016
* @Version	 : 1.0
*/
if(session_status() == PHP_SESSION_NONE){
	session_start();
}
class ChatController extends BaseSiteController{
	
	public function __construct(){
		parent::__construct();
        FunctionLib::redirectHttps();
	}
	public function getFullPageChat($id=0){
        if(empty($this->member)){
            return Redirect::route('site.index');
        }

	    $page = MemberPage::getByPageFacebookId($id, $this->member['member_id_facebook']);
		$this->header($this->member, $page);
		
		//Meta title
		$meta_title='';
		$meta_keywords='';
		$meta_description='';
		$meta_img='';
		$arrMeta = Info::getItemByKeyword('SITE_SEO_HOME');
		if(!empty($arrMeta)){
			$meta_title = $arrMeta['meta_title'];
			$meta_keywords = $arrMeta['meta_keywords'];
			$meta_description = $arrMeta['meta_description'];
			$meta_img = $arrMeta['info_img'];
			if($meta_img != ''){
				$meta_img = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_INFO, $arrMeta['_id'], $arrMeta['info_img'], 550, 0, '', true, true);
			}
		}
        FunctionLib::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
		
		//Get All Conversations
		if(sizeof($page) > 0 && $page->member_page_status != CGlobal::status_block){
			$fb = new Facebook\Facebook ([
					'app_id' => CGlobal::facebook_app_id,
					'app_secret' => CGlobal::facebook_app_secret,
					'default_graph_version' => CGlobal::facebook_default_graph_version,
					'persistent_data_handler' => CGlobal::facebook_persistent_data_handler
					]);
		
			$facebookApp = new Facebook\FacebookApp(CGlobal::facebook_app_id, CGlobal::facebook_app_secret);
			
			$accessToken = $page->member_page_access_token;
			$permissions = array('manage_pages', 'read_page_mailboxes', 'publish_pages', 'publish_actions', 'public_profile', 'email');
			$fields = array('snippet', 'can_reply', 'senders', 'updated_time');
			
			$request = new Facebook\FacebookRequest(
					$facebookApp,
					$accessToken, 
					'GET', 
					'/'.$id.'/conversations?fields='.implode(',', $fields),
					$permissions
			);

			$response = $fb->getClient()->sendRequest($request);
			$arrMessages = $response->getDecodedBody();

			$this->layout->content = View::make('site.SiteLayouts.chat')
                                    ->with('member', $this->member)
                                    ->with('arrMessages', $arrMessages)
                                    ->with('id', $id);
			$this->footer();
		}else{
			
		}
	}
	//Node Server Request
	public function ajaxGetMessagesConversation(){
        /*
        if(empty($this->member)){
            return Redirect::route('site.index');
        }
        */

        $pageId = addslashes(Request::get('datac', ''));
        $conversationId = addslashes(Request::get('dataid', ''));
        $member_id_facebook = addslashes(Request::get('mid', ''));
        //$member_id_facebook = $this->member['member_id_facebook'];

        if($pageId != '' && $conversationId != '' && $member_id_facebook != '') {

            $page = MemberPage::getByPageFacebookId($pageId, $member_id_facebook);

            $fb = new Facebook\Facebook ([
                'app_id' => CGlobal::facebook_app_id,
                'app_secret' => CGlobal::facebook_app_secret,
                'default_graph_version' => CGlobal::facebook_default_graph_version,
                'persistent_data_handler' => CGlobal::facebook_persistent_data_handler
            ]);

            $facebookApp = new Facebook\FacebookApp(CGlobal::facebook_app_id, CGlobal::facebook_app_secret);
            $accessToken = $page->member_page_access_token;
            $permissions = array('manage_pages', 'read_page_mailboxes', 'publish_pages', 'publish_actions', 'public_profile', 'email');
            $fields = array('message', 'created_time', 'from', 'to', 'id');

            $request = new Facebook\FacebookRequest(
                $facebookApp,
                $accessToken,
                'GET',
                '/' . $conversationId . '/messages?fields=' . implode(',', $fields) . '&limit=1000',
                $permissions
            );

            $response = $fb->getClient()->sendRequest($request);
            $arrMessages = $response->getDecodedBody();

            if (isset($arrMessages['data']) && sizeof($arrMessages['data']) > 0) {
                foreach ($arrMessages['data'] as $item) {
                    $data['messages_conversation_id'] = $conversationId;
                    $data['messages_fanpage_id'] = $pageId;
                    $data['messages_reply_id'] = $item['id'];
                    $data['messages_from_id'] = isset($item['from']['id']) ? $item['from']['id'] : '';
                    $data['messages_from'] = isset($item['from']['name']) ? $item['from']['name'] : '';
                    $data['messages_to_id'] = isset($item['to']['data']['0']['id']) ? $item['to']['data']['0']['id'] : '';
                    $data['messages_to'] = isset($item['to']['data']['0']['name']) ? $item['to']['data']['0']['name'] : '';
                    $data['messages_content'] = $item['message'];
                    $data['messages_created'] = strtotime($item['created_time']);
                    $data['messages_status'] = 1;
                    if (isset($item['from']['id']) && $item['from']['id'] == $pageId) {
                        $data['messages_type'] = CGlobal::status_show;
                    } else {
                        $data['messages_type'] = CGlobal::status_hide;
                    }
                    $check = Messages::getByReplyId($data['messages_reply_id']);
                    if (sizeof($check) == 0) {
                        Messages::addData($data);
                    }
                }
            }
        }
        //Get Full Messages In Conversation
        $dataSearch['messages_fanpage_id'] = $pageId;
        $dataSearch['messages_conversation_id'] = $conversationId;
        $dataSearch['field_get'] = 'messages_content,messages_from,messages_from_id,messages_type,messages_created';
        $arrItem = Messages::searchByConversationBuyCondition($dataSearch, CGlobal::num_max_mess_new_in_conversation);

        $result = array();
        if(sizeof($arrItem) > 0){
            $list = (object)FunctionLib::sortBySubValue($arrItem, 'messages_created', $asc = true, $preserveKeys = true);
            foreach($list as $k=>$item){
                $_item = array(
                    'messages_content'=>$item->messages_content,
                    'messages_from'=>$item->messages_from,
                    'messages_from_id'=>$item->messages_from_id,
                    'messages_type'=>$item->messages_type,
                    'messages_created'=>$item->messages_created
                );
                array_push($result, $_item);
            }
        }
        return $result;
    }
    //Ajax Site
	public function ajaxSendMessageInConversation(){

	    if(empty($this->member)){
            return Redirect::route('site.home');
        }

        $pageId = addslashes(Request::get('datac', ''));
        $conversationId = addslashes(Request::get('dataid', ''));
        $mess = addslashes(Request::get('mess', ''));
        $datacid = addslashes(Request::get('datacid', ''));
        $datacname = addslashes(Request::get('datacname', ''));

        if($pageId != '' && $conversationId != '' && $mess != '') {
            $page = MemberPage::getByPageFacebookId($pageId, $this->member['member_id_facebook']);
            $facebookApp = new Facebook\FacebookApp(CGlobal::facebook_app_id, CGlobal::facebook_app_secret);
            $accessToken = $page->member_page_access_token;

            $fb = new Facebook\Facebook ([
                'app_id' => CGlobal::facebook_app_id,
                'app_secret' => CGlobal::facebook_app_secret,
                'default_graph_version' => CGlobal::facebook_default_graph_version,
                'persistent_data_handler' => CGlobal::facebook_persistent_data_handler
            ]);
            $request = new Facebook\FacebookRequest(
                $facebookApp,
                $accessToken,
                'POST',
                '/' . $conversationId . '/messages',
                array(
                    'message' => $mess,
                )
            );
            $response = $fb->getClient()->sendRequest($request);
            $returnMessages = $response->getDecodedBody();
            if(sizeof($returnMessages) > 0) {
                $data['messages_conversation_id'] = $conversationId;
                $data['messages_fanpage_id'] = $pageId;
                $data['messages_reply_id'] = $returnMessages['id'];
                $data['messages_from_id'] = $pageId;
                $data['messages_from'] = $page->member_page_facebook_name;
                $data['messages_to_id'] = $datacid;
                $data['messages_to'] = $datacname;
                $data['messages_content'] = $mess;
                $data['messages_created'] = time();
                $data['messages_status'] = CGlobal::status_show;
                $data['messages_type'] = CGlobal::status_show;
                $check = Messages::getByReplyId($data['messages_reply_id']);
                if (sizeof($check) == 0) {
                    Messages::addData($data);
                }
                $data = array('pageid'=>$pageId, 'pageName'=>$page->member_page_facebook_name, 'mess'=>$mess, 'date'=>time());
                echo json_encode($data);
            }
        }
        die;
    }
    public function webhooksGetMessageInConversation(){
        $hub_mode = addslashes(Request::get('hub_mode', ''));
        $hub_challenge = addslashes(Request::get('hub_challenge', ''));
        $hub_verify_token = addslashes(Request::get('hub_verify_token', ''));
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == 'GET' && $hub_mode == 'subscribe' && $hub_verify_token == CGlobal::facebook_hub_verify_token){
            echo $hub_challenge;die;
        }elseif($method == 'POST'){
            $updates = json_decode(file_get_contents("php://input"), true);
            error_log('updates = ' . print_r($updates, true));
        }
    }
}
