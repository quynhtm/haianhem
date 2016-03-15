<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class clsUtility{

	/*--------------------------------------set nofollow tag a---------------------------------------------------*/
	static function setNofollow($str){
		return preg_replace('/(<a.*?)(rel=[\"|\'].*?[\"|\'])?(.*?\/a>)/i', '$1 rel="nofollow" $3', $str);
	}

	/*--------------------------------------check file swf - flash-----------------------------------------------*/
	static function chkFileExtension($str='') {
		$match= preg_match('/.swf/i', $str);
		if($match>0){
			return "yes";
		}else{
			return "no";
		}
	}

	/*--------------------------------------add param js header--------------------------------------------------*/
	static function add_js_header($name_value){
		global $base_url,$user;
		$params_js = array(
					    "base_url" => $base_url
					);
		
		$params_js['user_id'] = isset($user->uid) ? $user->uid : 0;
		$params_js['user_name'] = isset($user->full_name) ? $user->full_name : 'Anonymous';
								
		$value_js = "var $name_value=".drupal_json_encode ($params_js);
		$script = array (
			'#tag' => 'script',
			'#prefix' => '',
			'#suffix' => '',
			'#value_prefix' => '',
			'#value' => $value_js,
			'#value_suffix' => '',
			'#attributes' => array('type' => 'text/javascript')
		);
		drupal_add_html_head ($script, $name_value);
	}

	/*--------------------------------------get list language enabled-----------------------------------------------*/
	static function getListLanguage($_type='list'){
		global $base_url;

		$languages = language_list('enabled');
		$html = '';

		//type = list or radio or select
		if($_type == ''){
			$_type == 'list';
		}

		if($_type == 'list'){
			$html .= '<ul class="list-lang">';
			foreach($languages[1] as $v){
				$html .= '<li><a href="'.$base_url.'/'.$v->language.'">'.$v->name.'</a></li>';
			}
			$html .= '</ul>';
		}elseif($_type == 'select'){
			$html .= '<select name="txtLanguage" class="txtLanguage" id="txtLanguage">';
			foreach($languages[1] as $v){
				$html .= '<option value="'.$v->language.'">'.$v->name.'</option>';
			}
			$html .= '</select>';
		}elseif($_type=='radio'){
			$html .= '<ul class="radio-lang">';
			foreach($languages[1] as $v){
				$html .= '<li><input type="radio" name="txtLanguage" value="'.$v->language.'"/>'.$v->name.'</li>';
			}
			$html .= '</ul>';
		}else{
			$html = '';
		}

		return $html;
	}

	static function keyword($keyword=''){
		$html = '';
		if($keyword!=''){
			$clsInfo = new Info();
			$arrConfig = $clsInfo->getByCond("content", "keyword='$keyword'", "", "", "1");
			if(count($arrConfig)>0){
				foreach($arrConfig as $v){
					$html = $v->content;
				}
			}
		}
		return $html;
	}

	static function keyword_all($keyword=''){
		$arrItem = array();
		if($keyword!=''){
			$clsInfo = new Info();
			$arrItem = $clsInfo->getByCond("*", "keyword='$keyword'", "", "", "1");
		}
		return $arrItem;
	}

	static function keyword_seo($keyword=''){
		$arrItem = array();
		if($keyword!=''){
			$clsInfo = new Info();
			$arrItem = $clsInfo->getByCond("meta_title, meta_keywords, meta_description, img", "keyword='$keyword'", "", "", "1");
		}
		return $arrItem;
	}
	
	function cut_link_html($str=''){
		global $base_url;
		$match= preg_match('/.html/i', $str);
		if($match > 0){
			if(substr($str, -5)=='.html'){
				$str = substr($str, 0, -5);
			}
		}else{
			drupal_goto($base_url);
		}
		return $str;
	}
}

clsUtility::add_js_header("BASEPARAMS");

if (!function_exists('bug')){
	function bug($data,$die=true){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		echo '<hr/>';
		if($die){die;}
	}
}