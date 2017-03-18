<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/

class Messages extends Eloquent {
	protected $table = 'chat_messages';
    protected $primaryKey = 'messages_id';
    public  $timestamps = false;

    protected $fillable = array(
	    		'messages_id', 'messages_conversation_id', 'messages_fanpage_id', 'messages_reply_id',
	    		'messages_from_id', 'messages_from', 'messages_to_id', 'messages_to', 'messages_content', 'messages_type',
	    		'messages_created', 'messages_status'
    			);
	//ADMIN
    public static function searchByCondition($dataSearch=array(), $limit=0, $offset=0, &$total){
    	try{
			$query = Messages::where('messages_id','>',0);
    		if (isset($dataSearch['messages_fanpage_id']) && $dataSearch['messages_fanpage_id'] != '') {
    			$query->where('messages_fanpage_id','=', $dataSearch['messages_fanpage_id']);
    		}

    		if (isset($dataSearch['messages_status']) && $dataSearch['messages_status'] != -1) {
    			$query->where('messages_status','=', $dataSearch['messages_status']);
    		}
    		
    		$total = $query->count();
    		$query->orderBy('messages_id', 'desc');
    		 
    		$fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
    		 
    		if(!empty($fields)){
    			$result = $query->take($limit)->skip($offset)->get($fields);
    		}else{
    			$result = $query->take($limit)->skip($offset)->get();
    		}
    		return $result;
    		 
    	}catch (PDOException $e){
    		throw new PDOException();
    	}
    }
     
    public static function getById($id=0){
		$result = Messages::where('messages_id', $id)->first();
    	return $result;
    }
    public static function getByReplyId($messages_reply_id =''){
        $result = Messages::where('messages_reply_id', $messages_reply_id)->first();
        return $result;
    }
     
    public static function updateData($id=0, $dataInput=array()){
    	try {
    		DB::connection()->getPdo()->beginTransaction();
    		$data = Messages::find($id);
    		if($id > 0 && !empty($dataInput)){
    			$data->update($dataInput);
    		}
    		DB::connection()->getPdo()->commit();
    		return true;
    	} catch (PDOException $e) {
    		DB::connection()->getPdo()->rollBack();
    		throw new PDOException();
    	}
    }
     
    public static function addData($dataInput=array()){
    	try {
    		DB::connection()->getPdo()->beginTransaction();
    		$data = new Messages();
    		if (is_array($dataInput) && count($dataInput) > 0) {
    			foreach ($dataInput as $k => $v) {
    				$data->$k = $v;
    			}
    		}
    		if ($data->save()) {
    			DB::connection()->getPdo()->commit();
    			return $data->messages_id;
    		}
    		DB::connection()->getPdo()->commit();
    		return false;
    	} catch (PDOException $e) {
    		DB::connection()->getPdo()->rollBack();
    		throw new PDOException();
    	}
    }
    
    public static function saveData($id=0, $data=array()){
    	$data_post = array();
    	if(!empty($data)){
    		foreach($data as $key=>$val){
    			$data_post[$key] = $val['value'];
    		}
    	}
    	if($id > 0){
    		Messages::updateData($id, $data_post);
    		Utility::messages('messages', 'Cập nhật thành công!');
    	}else{
    		Messages::addData($data_post);
    		Utility::messages('messages', 'Thêm mới thành công!');
    	}
    
    }
    
    public static function deleteId($id=0){
    	try {
    		DB::connection()->getPdo()->beginTransaction();
    		$data = Messages::find($id);
    		if($data != null){
    			$data->delete();
    			DB::connection()->getPdo()->commit();
    		}
    		return true;
    	} catch (PDOException $e) {
    		DB::connection()->getPdo()->rollBack();
    		throw new PDOException();
    	}
    }

    public static function searchByConversationBuyCondition($dataSearch=array(), $limit=0){
        try{
            $query = Messages::where('messages_id','>',0);
            if (isset($dataSearch['messages_fanpage_id']) && $dataSearch['messages_fanpage_id'] != '') {
                $query->where('messages_fanpage_id','=', $dataSearch['messages_fanpage_id']);
            }
            if (isset($dataSearch['messages_conversation_id']) && $dataSearch['messages_conversation_id'] != '') {
                $query->where('messages_conversation_id','=', $dataSearch['messages_conversation_id']);
            }
            $query->orderBy('messages_created', 'desc');

            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();

            if(!empty($fields)){
                $result = $query->take($limit)->get($fields);
            }else{
                $result = $query->take($limit)->get();
            }
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }
}
