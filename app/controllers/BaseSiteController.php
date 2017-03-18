<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 08/2016
* @Version	 : 1.0
*/

class BaseSiteController extends BaseController{
	protected $layout = 'site.BaseLayouts.index';
	protected $member = array();
	
	public function __construct(){
		//Init
        FunctionLib::site_css('lib/font-awesome/4.2.0/css/font-awesome.min.css', CGlobal::$POS_HEAD);
        FunctionLib::site_js('frontend/js/site.js', CGlobal::$POS_END);
        FunctionLib::site_js('frontend/js/socket.io.js', CGlobal::$POS_END);
        FunctionLib::site_js('lib/Sound/soundmanager.js', CGlobal::$POS_END);
		
		$this->member = Member::memberLogin();
	}
	public function header($member=array(), $fanpage_current=array()){
		$this->layout->header = View::make("site.BaseLayouts.header")
								->with('member', $member)
								->with('fanpage_current', $fanpage_current);
	}
	public function popupHiden($member=array()){
		$this->layout->popupHiden = View::make("site.BaseLayouts.popupHiden")
									->with('member', $member);
	}
	public function footer(){
		$this->layout->footer = View::make("site.BaseLayouts.footer");
	}
	public function page403(){
		$arrMeta = array(
				'title'=>'403',
				'description'=>'403',
		);
		foreach($arrMeta as $key=>$val){
			$this->layout->$key = $val;
		}
		$this->header();
		$this->layout->content = View::make('site.SiteLayouts.page403');
		$this->footer();
	}
	public function page404(){
		$arrMeta = array(
				'title'=>'404',
				'description'=>'404',
		);
		foreach($arrMeta as $key=>$val){
			$this->layout->$key = $val;
		}
		$this->header();
		$this->layout->content = View::make('site.SiteLayouts.page404');
		$this->footer();
	}
}