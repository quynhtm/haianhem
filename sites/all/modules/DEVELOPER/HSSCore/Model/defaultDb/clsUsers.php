<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class Users extends DbBasic{

	function __construct(){
        $this->pkey = 'uid';
        $this->table = 'users';
    }
}

class Roles extends DbBasic{

	function __construct(){
        $this->pkey = 'rid';
        $this->table = 'role';
    }
}

class UsersRoles extends DbBasic{

	function __construct(){
        $this->pkey = 'rid';
        $this->table = 'users_roles';
    }
}

class PermissionDetail extends DbBasic{

	function __construct(){
        $this->pkey = 'id';
        $this->table = 'hss_permission';
    }

    function check_permission($link_back=''){
    	global $base_url, $user;
    	
    	if($user->uid > 0){
    		$param = arg();
    		$clsUsersRoles = new UsersRoles();
    		$arrUserRole = $clsUsersRoles->getAll("rid", "uid = $user->uid", "", "uid ASC", "1");
    		if (count($arrUserRole)>0){
    			$arrPermission = $this->getAll("rid, permission", "rid=".$arrUserRole[0]->rid, "", "id ASC", "1");
    			$permission = unserialize($arrPermission[0]->permission);
    			if($param[0]=='admincp' && $param[1] != '' ){
    				$clsModule = new Module();
                    $check_module_exists = $clsModule->getAll("link", "link='".$param[1]."'", "", "id ASC", "1");
                    if(count($check_module_exists) == 0){
                        $data_module = array(
                                            'uid'=>1,
                                            'title'=>ucwords($param[1]),
                                            'title_alias'=>$param[1],
                                            'link'=>$param[1],
                                            'intro'=>'Manager '.$param[1],
                                            'created'=>time(),
                                            'status'=>1,
                                            );
                        $clsModule->insertOne($data_module);
                    }

                    if(isset($permission[$param[1]])){
    					if(isset($param[2]) && isset($permission[$param[1]])){
    						if(array_key_exists($param[2], $permission[$param[1]])){
    							return true;
    						}else{
    							if(isset($_POST)){ unset($_POST); }
    							drupal_goto($base_url .'/page-403');
    						}
    					}else{
    						if(array_key_exists($param[1], $permission) && array_key_exists('list', $permission[$param[1]])){
    							return true;
    						}else{
    							if(isset($_POST)){ unset($_POST); }
    							drupal_goto($base_url .'/page-403');
    						}
    					}
    				}
    			}
    		}
    	}
    	drupal_goto($base_url .'/page-403');
    }
}

