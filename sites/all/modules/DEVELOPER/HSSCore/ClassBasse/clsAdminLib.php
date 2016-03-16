<?php
/*
* QuynhTM
 * Function dùng common trong admin
*/
class clsAdminLib{
	/**
	 * @param $data
	 */
	static function FunctionDebug($data){
		echo '<pre>';
			print_r($data);
		echo '</pre>';
		die();
	}

	/**
	 * @param $name
	 * @param string $default
	 * @return string
	 */
	static function getParam($name, $default = ''){
		if (isset($_POST[$name])){
			return trim($_POST[$name]);
		}
		elseif(isset($_GET[$name])){
			return trim($_GET[$name]);
		}
		elseif (isset($_REQUEST[$name])){
			return trim($_REQUEST[$name]);
		}
		elseif(isset($_COOKIE[$name])){
			return trim($_COOKIE[$name]);
		}
		else{
			return $default;
		}
	}

	/**
	 * @param $name
	 * @param int $default
	 * @return int
	 */
	static function getIntParam($name, $default = 0){
		if (isset($_POST[$name])){
			return (int)trim($_POST[$name]);
		}
		elseif(isset($_GET[$name])){
			return (int)trim($_GET[$name]);
		}
		elseif (isset($_REQUEST[$name])){
			return (int)trim($_REQUEST[$name]);
		}
		elseif(isset($_COOKIE[$name])){
			return (int)trim($_COOKIE[$name]);
		}
		else{
			return $default;
		}
	}

	// phan trang dùng cho Boostrap
	public static function getNewPager(&$limit,$numPageShow = 10, $page = 1,$total = 1,$item_per_page = 1,$dataSearch, $page_name = 'page_no'){
		$total_page = ceil($total/$item_per_page);
		if($total_page == 1) return '';
		$next = '';
		$last = '';
		$prev = '';
		$first= '';
		$left_dot  = '';
		$right_dot = '';
		$from_page = $page - $numPageShow;
		$to_page = $page + $numPageShow;
		$limit = ' LIMIT '.(($page -1)*$item_per_page).','.$item_per_page.'';

		//get prev & first link
		if($page > 1){
			$prev = self::parseNewLink($page-1, '', "&lt; Tr??c", $page_name,$dataSearch);
			$first= self::parseNewLink(1, '', "&laquo; ??u", $page_name,$dataSearch);
		}
		//get next & last link
		if($page < $total_page){
			$next = self::parseNewLink($page+1, '', "Sau &gt;", $page_name,$dataSearch);
			$last = self::parseNewLink($total_page, '', "Cu?i &raquo;", $page_name,$dataSearch);
		}
		//get dots & from_page & to_page
		if($from_page > 0)	{
			$left_dot = ($from_page > 1) ? '<li><span>...</span></li>' : '';
		}else{
			$from_page = 1;
		}

		if($to_page < $total_page)	{
			$right_dot = '<li><span>...</span></li>';
		}else{
			$to_page = $total_page;
		}
		$pagerHtml = '';
		for($i=$from_page;$i<=$to_page;$i++){
			$pagerHtml .= self::parseNewLink($i, (($page == $i) ? 'active' : ''), $i, $page_name,$dataSearch);
		}
		$html_msg = '<li><span style="border: none; background: #ffffff!important; color: #000000!important;"><b>T?ng s?: '.$total.' items</b></span></li>';
		return '<ul class="pagination">'.$html_msg.$first.$prev.$left_dot.$pagerHtml.$right_dot.$next.$last.'</ul>';
	}
	static function parseNewLink($page = 1, $class="", $title="", $page_name = 'page_no',$dataSearch){
		$param = $dataSearch;
		$pageCurrent = EnBac::$page['name'];//////////////////////////////////////
		$param[$page_name] = $page;
		if($class == 'active'){
			return '<li class="'.$class.'"><a href="#" title="xem trang '.$title.'">'.$title.'</a></li>';
		}
		return '<li class="'.$class.'"><a href="'.Url::build($pageCurrent,$param).'" title="xem trang '.$title.'">'.$title.'</a></li>';
	}
}
