<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class Type extends DbBasic{

	function __construct(){
        $this->pkey = 'id';
        $this->table = 'hss_type';

    }

    function get_type_id($type_keyword='', $limit=1){
		$arrListType = array();
		$typeid = 0;
		if($type_keyword!='' && $limit>0){
			$arrListType = $this->getAll('id', "type_keyword='".$type_keyword."'", "", "id ASC", $limit);
		}
		foreach($arrListType as $v){
			$typeid = $v->id;
		}
		return $typeid;
	}
}





