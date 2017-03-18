<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 08/2016
* @Version	 : 1.0
*/

class IndexController extends BaseSiteController{
	
	public function __construct(){
		parent::__construct();
		Utility::redirectHttps();
	}
	public function index(){
		$this->header($this->member, array());
		if(!empty($this->member)){
			$this->popupHiden($this->member);
		}
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
		SeoMeta::SEO($meta_img, $meta_title, $meta_keywords, $meta_description);
		
		$this->layout->content = View::make('site.content.index')->with('member', $this->member);
		$this->footer();
	}
	public function ajaxActFanpage(){
		$pageId = Request::get('dataid', '');
		if($pageId != ''){
			$onePageCheck = MemberPage::getByPageFacebookId($pageId, $this->member['member_id_facebook']);
			if(sizeof($onePageCheck) > 0){
				if(isset($onePageCheck['member_id']) && $onePageCheck['member_id'] == $this->member['member_id_facebook']){
					$memberPageID = $onePageCheck['member_page_id'];
					$datePage['member_page_status'] = CGlobal::status_show;
					MemberPage::updateData($memberPageID, $datePage);
					$listPage = MemberPage::getAllPageByUserFacebookId($this->member['member_id_facebook']);
					//Update Session Member
					$this->member['list_page'] = $listPage;
					Session::put('member', $this->member, 60*24);
					Session::save();
					
					echo '1';die;
				}
			}
		}
		echo '0';die;
	}
}
