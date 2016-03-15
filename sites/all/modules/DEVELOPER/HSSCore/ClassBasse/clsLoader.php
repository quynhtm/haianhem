<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class clsLoader{
	public static $path;
	public static $module;

	public static function load($module, $files){

		self::$module =  $module;
		self::$path = drupal_get_path('module', $module);
		
		$js = array();
		$css = array();
		$inc = array();
		$php = array();

		foreach($files as $key => $file){
			$ext = explode('.', $file);
			switch (strtolower(end($ext))) {
				case 'css': $css[] = $file; break;
				case 'js' : $js[]  = $file; break;
				case 'inc': $inc[] = $file; break;
				case 'php': $php[] = $file; break;
				default: break;
			}
		}

		self::loadCSS($css);
		self::loadJS($js);
		self::loadINC($inc);
		self::loadPHP($php);

	}

	private static function loadCSS($css){
		foreach ($css as $item) {
    		if(file_exists(DRUPAL_ROOT.'/'.self::$path.'/'.$item)){
				drupal_add_css(self::$path.'/'.$item, array('group'=>CSS_DEFAULT, 'every_page'=>FALSE));
			}
		}
	}

	private static function loadJS($js){
		foreach ($js as $item) {
			if(file_exists(DRUPAL_ROOT.'/'.self::$path.'/'.$item)){
				drupal_add_js(self::$path.'/'.$item, array('group'=>JS_DEFAULT, 'every_page'=>FALSE, 'scope' => 'footer',));
			}
		}
	}

	private static function loadINC($inc){
		foreach ($inc as $item) {
			$ext = explode('.', $item);
			if(file_exists(DRUPAL_ROOT.'/'.self::$path.'/'.$item)){
				@module_load_include('inc', self::$module, $ext[0]);
			}
		}
	}

	private static function loadPHP($php){
		foreach ($php as $item) {
			$ext = explode('.', $item);
			if(file_exists(DRUPAL_ROOT.'/'.self::$path.'/'.$item)){
				@module_load_include('php', self::$module, $ext[0]);
			}
		}
	}
}