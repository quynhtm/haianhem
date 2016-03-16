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

}
