<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0 
*/
define('BASE_PATH', str_replace('\\', '/', DRUPAL_ROOT));
define('PATH_SCAN', BASE_PATH.'/sites/all/modules/DEVELOPER/HSSCore');

function auto_load_file($path=''){

	$arrFileInPathScan = array();
	if($path == ''){
		$path_scan = PATH_SCAN;
	}else{
		$path_scan = $path;
	}

	if (is_dir($path_scan)) {
		if ($dh = opendir($path_scan)) {
			while (($file = readdir($dh)) !== false) {
				if ($file != '.' && $file != '..' && $file != '' && is_dir($path_scan."/$file")) {
					$path_scan_sub = $path_scan. '/' .$file;
					self::autoLoadFile($path_scan_sub);
				}else{
					if (substr($file, -3)=='inc' || substr($file, -3)=='php' && substr($file, -7)!='tpl.php' && file_exists($path_scan."/$file")) {
						array_push($arrFileInPathScan, $file);
					}
				}
			}
			closedir($dh);
		}
	}
	foreach ($arrFileInPathScan as $list_file){
		require_once($path_scan. '/' .$list_file);
	}
}
auto_load_file(PATH_SCAN.'/Model/database');
auto_load_file(PATH_SCAN.'/Model/defaultDb');
auto_load_file(PATH_SCAN.'/ClassBasse');
auto_load_file(PATH_SCAN.'/CodeDefine');
auto_load_file(PATH_SCAN.'/Controller');
