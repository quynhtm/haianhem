<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class Module extends DbBasic{

	function __construct(){
        $this->pkey = 'id';
        $this->table = 'hss_module';

    }
}

class ShowFields extends DbBasic{

	function __construct(){
        $this->pkey = 'id';
        $this->table = 'hss_show_fields';

    }
}





