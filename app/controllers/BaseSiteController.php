<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 11/2016
* @Version   : 1.0
*/

class BaseSiteController extends BaseController{
    protected $layout = 'site.BaseLayouts.index';
   
    public function __construct(){
    	FunctionLib::site_css('font-awesome/4.2.0/css/font-awesome.min.css', CGlobal::$POS_HEAD);
    	FunctionLib::site_js('frontend/js/site.js', CGlobal::$POS_END);
    }
    public function header(){
    	$this->layout->header = View::make("site.BaseLayouts.header");
    }
	public function footer(){
        $footer = '';
        $arrFooter = Info::getItemByKeyword('SITE_FOOTER_LEFT');
        if(sizeof($arrFooter) > 0){
            $footer = stripslashes($arrFooter->info_content);
        }
		$this->layout->footer = View::make("site.BaseLayouts.footer")->with('footer', $footer);
	}
}