<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
if(session_status() == PHP_SESSION_NONE){
	session_start();
}
class MemberLoginController extends BaseSiteController{
    protected $member = array();
  
	public function __construct(){
		parent::__construct();
		if(Session::has('member')){
			$this->member = Session::get('member');
		}
	}
	
	public function loginFacebook(){

		$fb = new Facebook\Facebook ([
				'app_id' => CGlobal::facebook_app_id,
				'app_secret' => CGlobal::facebook_app_secret,
				'default_graph_version' => CGlobal::facebook_default_graph_version,
				'persistent_data_handler' => CGlobal::facebook_persistent_data_handler
				]);
		
		$helper = $fb->getRedirectLoginHelper();
		 
		try{
			$accessToken = $helper->getAccessToken();
		}catch(Facebook\Exceptions\FacebookResponseException $e) {
			//When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		}catch(Facebook\Exceptions\FacebookSDKException $e) {
			//When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		 
		if (!isset($accessToken)) {
			$permissions = array('manage_pages', 'read_page_mailboxes', 'publish_pages', 'publish_actions', 'public_profile', 'email'); //Optional permissions
			$loginUrl = $helper->getLoginUrl(Config::get('config.BASE_URL').'/facebooklogin', $permissions);
			header("Location: ".$loginUrl);
			exit;
		}
		 
		try{
			//Returns a 'Facebook\FacebookResponse' object
			$fields = array('id', 'name', 'email','first_name', 'last_name', 'birthday', 'gender', 'locale', 'picture', 'link', 'accounts');
			$response = $fb->get('/me?fields='.implode(',', $fields).'', $accessToken);
		
		}catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		}catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		//Info User
		$user = $response->getGraphUser();
		//Info Pages
		$graphObject = $response->getDecodedBody();
		$pages = isset($graphObject['accounts']) ? $graphObject['accounts'] : array();

		if(sizeof($user) > 0){
			$data = array();
			if(isset($user['id'])){
				$data['member_id_facebook'] = $user['id'];
			}
			if(isset($user['email'])){
				$data['member_mail'] = $user['email'];
			}
			if(isset($user['name'])){
				$data['member_full_name'] = $user['name'];
			}
			if(isset($data['member_id_facebook']) && $data['member_id_facebook'] != ''){
				$member = Member::getMemberByIdFacebook($data['member_id_facebook']);
				if(sizeof($member) > 0){
					if(isset($member['member_status']) && $member['member_status'] != CGlobal::status_block){
						Member::updateLogin((string)$member['_id']);
						//Update List Fanpage
						$listPage = MemberPage::getAllPageByUserFacebookId($data['member_id_facebook']);
						if(sizeof($listPage) > 0){
							$arrPageId = array();
							foreach($listPage as $item){
								$arrPageId[] = $item['member_page_facebook_id'];
							}
							//Check Fanpage Exists And Add
							if(isset($pages['data']) && !empty($pages['data'])){
								$arrPageFacebook = array();
							    foreach($pages['data'] as $page){
									if(!in_array($page['id'], $arrPageId)){
                                        $facebookApp = new Facebook\FacebookApp(CGlobal::facebook_app_id, CGlobal::facebook_app_secret);
                                        $permissions = array('manage_pages', 'read_page_mailboxes', 'publish_pages', 'publish_actions', 'public_profile', 'email');
                                        $fields = array('username');
                                        $request = new Facebook\FacebookRequest($facebookApp, $page['access_token'], 'GET', '/'.$page['id'].'?fields='.implode(',', $fields), $permissions);
                                        $response = $fb->getClient()->sendRequest($request);
                                        $getRequestPage = $response->getDecodedBody();
									    $dataPage = array(
												'member_id'=>$data['member_id_facebook'],
												'member_page_facebook_category'=>$page['category'],
                                                'member_page_facebook_name'=>$page['name'],
												'member_page_facebook_id'=>$page['id'],
												'member_page_facebook_perms'=>serialize($page['perms']),
												'member_page_access_token'=>$page['access_token'],
												'member_page_status'=>CGlobal::status_hide,
										);
                                        $dataPage['member_page_facebook_username'] = isset($getRequestPage['username']) ? $getRequestPage['username'] : '';
										MemberPage::addData($dataPage);
									}else{
                                        $facebookApp = new Facebook\FacebookApp(CGlobal::facebook_app_id, CGlobal::facebook_app_secret);
                                        $permissions = array('manage_pages', 'read_page_mailboxes', 'publish_pages', 'publish_actions', 'public_profile', 'email');
                                        $fields = array('username');
                                        $request = new Facebook\FacebookRequest($facebookApp, $page['access_token'], 'GET', '/'.$page['id'].'?fields='.implode(',', $fields), $permissions);
                                        $response = $fb->getClient()->sendRequest($request);
                                        $getRequestPage = $response->getDecodedBody();
                                        $dataPage['member_page_facebook_username'] = isset($getRequestPage['username']) ? $getRequestPage['username'] : '';
                                        $dataPage['member_page_access_token'] = $page['access_token'];
										MemberPage::updateDataByMemberID($member->member_id_facebook, $page['id'], $dataPage);
									}
                                    $arrPageFacebook[] = $page['id'];
								}
                                //Delete Page Not Administrator
								if(!empty($arrPageFacebook)){
                                    foreach($arrPageId as $_page){
                                        if(!in_array($_page, $arrPageFacebook)){
                                            MemberPage::deleteByCondMemberIdAndPageId($data['member_id_facebook'], $_page);
                                        }
                                    }
                                }
                                //Delete Page Not Administrator
							}
						}else{
							if(isset($pages['data']) && !empty($pages['data'])){
								foreach($pages['data'] as $page){
                                    $facebookApp = new Facebook\FacebookApp(CGlobal::facebook_app_id, CGlobal::facebook_app_secret);
                                    $permissions = array('manage_pages', 'read_page_mailboxes', 'publish_pages', 'publish_actions', 'public_profile', 'email');
                                    $fields = array('username');
                                    $request = new Facebook\FacebookRequest($facebookApp, $page['access_token'], 'GET', '/'.$page['id'].'?fields='.implode(',', $fields), $permissions);
                                    $response = $fb->getClient()->sendRequest($request);
                                    $getRequestPage = $response->getDecodedBody();
								    $dataPage = array(
											'member_id'=>$data['member_id_facebook'],
											'member_page_facebook_category'=>$page['category'],
											'member_page_facebook_name'=>$page['name'],
											'member_page_facebook_id'=>$page['id'],
											'member_page_facebook_perms'=>serialize($page['perms']),
											'member_page_access_token'=>$page['access_token'],
											'member_page_status'=>CGlobal::status_hide,
									);
                                    $dataPage['member_page_facebook_username'] = isset($getRequestPage['username']) ? $getRequestPage['username'] : '';
									MemberPage::addData($dataPage);
								}
							}
						}
					}
				}else{
					$data['member_created'] = time();
					$data['member_status'] = CGlobal::status_show;
					$data['member_last_ip'] = Request::getClientIp();
					$data['member_last_login'] = time();
					$data['member_phone'] = '';
					$data['member_address'] = '';
					Member::addData($data);
					//Add List Fanpage
					if(isset($pages['data']) && !empty($pages['data'])){
						foreach($pages['data'] as $page){
                            $facebookApp = new Facebook\FacebookApp(CGlobal::facebook_app_id, CGlobal::facebook_app_secret);
                            $permissions = array('manage_pages', 'read_page_mailboxes', 'publish_pages', 'publish_actions', 'public_profile', 'email');
                            $fields = array('username');
                            $request = new Facebook\FacebookRequest($facebookApp, $page['access_token'], 'GET', '/'.$page['id'].'?fields='.implode(',', $fields), $permissions);
                            $response = $fb->getClient()->sendRequest($request);
                            $getRequestPage = $response->getDecodedBody();
						    $dataPage = array(
									'member_id'=>$data['member_id_facebook'],
									'member_page_facebook_category'=>$page['category'],
									'member_page_facebook_name'=>$page['name'],
									'member_page_facebook_id'=>$page['id'],
									'member_page_facebook_perms'=>serialize($page['perms']),
									'member_page_access_token'=>$page['access_token'],
									'member_page_status'=>CGlobal::status_hide,
							);
                            $dataPage['member_page_facebook_username'] = isset($getRequestPage['username']) ? $getRequestPage['username'] : '';
							MemberPage::addData($dataPage);
						}
					}
				}

				$member = Member::getMemberByIdFacebook($data['member_id_facebook']);
				$listPage = MemberPage::getAllPageByUserFacebookId($data['member_id_facebook']);
				$member['list_page'] = $listPage;
				$member['facebook_access_token'] = (string)$accessToken;
				Session::put('member', $member, 60*24);
				Session::save();
			}
			echo '<script>window.close();</script>';die;
		}
	}
	
	public function logout(){
		if(Session::has('member')){
			Session::forget('member');
		}
		return Redirect::route('site.index');
	}
}