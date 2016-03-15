<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class clsString{

	static function substring($str, $length = 100, $replacer='...'){
    	$str = strip_tags($str);
    	if(strlen($str) <= $length){
    		return $str;
    	}
    	$str = trim(@substr($str,0,$length));
    	$posSpace = strrpos($str,' ');
        $replacer="...";
    	return substr($str,0,$posSpace).$replacer;
    }
	
	static function cut_link_html($str=''){
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
