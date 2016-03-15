<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class DbBasic{

    public $pkey = '';
    public $table = '';

    function __construct(){
        //init here
    }
    //================================
    //SELECT
    //================================
    function getAll($_fields="", $_cond="", $_groupby="", $_oderby="", $_limit=""){
        //list field
        if($_fields!=""){
            $fields = $_fields;
        }else{
            $fields = "*";
        }
        //cond
        if($_cond!=""){
            $cond = " WHERE $_cond";
        }else{
            $cond = "";
        }
        //groupby
        if($_groupby!=""){
            $groupby = " GROUP BY $_groupby";
        }else{
            $groupby = "";
        }
        //orderby
        if($_oderby!=""){
            $oderby = " ORDER BY $_oderby";
        }else{
            $oderby = "";
        }
        //limit
        if($_limit!=""){
            $limit = " LIMIT 0, $_limit";
        }else{
            $limit = "";
        }

        $sql = db_query("SELECT $fields FROM {$this->table}".$cond.$groupby.$oderby.$limit);
        $record = $sql->fetchAll();

        return $record;

    }
    function getOne($_fields="", $_pkey=""){
        //list field
        if($_fields!=""){
            $fields = $_fields;
        }else{
            $fields = "*";
        }
        //cond
        if($_pkey!=''){
            $cond = " WHERE $this->pkey='$_pkey'";
        }else{
            return "SQL Error";
        }

        $sql = db_query("SELECT $fields FROM {$this->table}".$cond);
        $record = $sql->fetchAll();

        return $record;

    }

    function getByCond($_fields="", $_cond="", $_groupby="", $_oderby="", $_limit=""){
        //list field
        if($_fields!=""){
            $fields = $_fields;
        }else{
            $fields = "*";
        }
        //cond
        if($_cond!=""){
            $cond = " WHERE $_cond";
        }else{
            return "SQL Error";
        }
        //groupby
        if($_groupby!=""){
            $groupby = " GROUP BY $_groupby";
        }else{
            $groupby = "";
        }
        //orderby
        if($_oderby!=""){
            $oderby = " ORDER BY $_oderby";
        }else{
            $oderby = "";
        }
        //limit
        if($_limit!=""){
            $limit = " LIMIT 0, $_limit";
        }else{
            $limit = "";
        }

        $sql = db_query("SELECT $fields FROM {$this->table}".$cond.$groupby.$oderby.$limit);
        $record = $sql->fetchAll();

        return $record;

    }
    //================================
    //INSERT
    //================================
    function insertOne($_data=""){
        //data
        if(is_array($_data) && count($_data)>0){
            $data = $_data;
        }else{
            return "SQL Error";
        }
        $sql = db_insert($this->table)->fields($data)->execute();

		if($sql){
            return  1;
        }else{
            return  0;
        }
    }
    //================================
    //UPDATE
    //================================
    function updateOne($_data="", $_pkey=""){
        //data
        if(is_array($_data) && count($_data)>0){
            $data = $_data;
        }else{
            return "SQL Error";
        }
        //cond
        if($_pkey!=''){
            $cond = $_pkey;
        }else{
            return "SQL Error";
        }

        $sql = db_update($this->table)->fields($data)->condition($this->pkey, $cond, '=')->execute();
        if($sql){
            return  1;
        }else{
            return  0;
        }
    }
    function updateByCond($_data="", $_field="",  $_cond=""){
        //data
        if(is_array($_data) && count($_data)>0){
            $data = $_data;
        }else{
            return "SQL Error";
        }
        //field
        if($_field!=""){
            $field = $_field;
        }else{
            return "SQL Error";
        }
        //cond
        if($_cond!=""){
             $cond = $_cond;
        }else{
            return "SQL Error";
        }

        $sql = db_update($this->table)->fields($data)->condition($field, $cond, '=')->execute();
        if($sql){
            return  1;
        }else{
            return  0;
        }
    }
    //================================
    //DELETE
    //================================
    function deleteOne($_pkey=""){
        //cond
        if($_pkey!=""){
            $cond = $_pkey;
        }else{
            return "SQL Error";
        }

        $sql = db_delete($this->table)->condition($this->pkey, $cond)->execute();
        if($sql){
            return  1;
        }else{
            return  0;
        }
    }
    function deleteByCond($_field="", $_cond=""){
        //field
        if($_field!=""){
            $field = $_field;
        }else{
            return "SQL Error";
        }
        //cond
        if($_cond!=""){
            $cond = $_cond;
        }else{
            return "SQL Error";
        }

        $sql = db_delete($this->table)->condition($field, $cond)->execute();
        if($sql){
            return  1;
        }else{
            return  0;
        }
    }

	function countItem($_fields="", $_cond="", $_groupby="", $_oderby="", $_limit=""){
        //list field
        if($_fields!=""){
            $fields = $_fields;
        }else{
            $fields = "*";
        }
        //cond
        if($_cond!=""){
            $cond = " WHERE $_cond";
        }else{
            $cond = "";
        }
        //groupby
        if($_groupby!=""){
            $groupby = " GROUP BY $_groupby";
        }else{
            $groupby = "";
        }
        //orderby
        if($_oderby!=""){
            $oderby = " ORDER BY $_oderby";
        }else{
            $oderby = "";
        }
        //limit
        if($_limit!=""){
            $limit = " LIMIT 0, $_limit";
        }else{
            $limit = "";
        }

        $sql = db_query("SELECT $fields FROM {$this->table}".$cond.$groupby.$oderby.$limit);
        $record = $sql->rowCount();

        return $record;

    }
}


