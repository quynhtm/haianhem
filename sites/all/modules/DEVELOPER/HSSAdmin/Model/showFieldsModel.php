<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class _ShowFields extends ShowFields{

	function listInputForm(){
		$arrStatus = array(
						'0'=>t('Ẩn'),
						'1'=>t('Hiện'),
					);
		$arr_fields = array(
				'id'=>array('type'=>'hidden', 'label'=>'', 'value'=>'0','require'=>'', 'attr'=>''),
				'module'=>array('type'=>'text', 'label'=>'Tên module', 'value'=>'','require'=>'require', 'attr'=>''),
				//'fields'=>array('type'=>'text', 'label'=>'Tên trường hiển thị', 'value'=>'','require'=>'require', 'attr'=>''),
				'status'=>array('type'=>'option', 'label'=>'Trạng thái', 'value'=>'0', 'require'=>'' ,'attr'=>'','list_option'=>$arrStatus),			
		);
		return $arr_fields;
	}

	function get_field_show($class = ''){
		global $base_url, $user;
		
		$param = arg();
		$list_field = array();
		$module = '';
		if($param[1] != ''){
			$module = $param[1];
		}
		if($module !='' && $class!=''){
			
			$arr_check_field = array();
			$arrField = $this->getAll($_fields="fields", $_cond="module='".$module."'", $_groupby="", $_oderby="id ASC", $_limit="1"); 
			
			if(count($arrField)>0){
				$arr_check_field = unserialize($arrField[0]->fields);

				$class = new $class();
				$list_field = $class->listInputForm();

				$list_roles = $user->roles;
				foreach($arr_check_field as $role=>$mod){
					if(!in_array($role, $list_roles)){
						unset($arr_check_field[$role]);
					}
				}
				
				$fields_show = array();
				foreach($arr_check_field as $k=> $mod){
					foreach($mod[$module] as $key=>$value){
						if(!in_array($key, $fields_show)){
							array_push($fields_show, $key);
						}
					}
					
				}

				foreach($list_field as $key_default=>$v){
					if(!in_array($key_default, $fields_show)){
						unset($list_field[$key_default]);
					}
				}
				
			}
		}
		return $list_field;
	}
}