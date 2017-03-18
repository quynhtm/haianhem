<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/

class MemberPage extends Eloquent {
    
    protected $table = 'member_page';
    protected $primaryKey = 'member_page_id';
    public  $timestamps = false;

    protected $fillable = array(
	    		'member_page_id', 'member_id', 'member_page_facebook_category', 'member_page_facebook_username',
	    		'member_page_facebook_name', 'member_page_facebook_id', 'member_page_facebook_perms', 'member_page_access_token','member_page_status',
    			);
	//ADMIN
    public static function searchByCondition($dataSearch=array(), $limit=0, $offset=0, &$total){
    	try{
    	  
    		$query = MemberPage::where('member_page_id','>',0);
    	  	
    		if (isset($dataSearch['member_id']) && $dataSearch['member_id'] != '') {
    			$query->where('member_id','=', $dataSearch['member_id']);
    		}
    		
    		if (isset($dataSearch['member_page_facebook_id']) && $dataSearch['member_page_facebook_id'] != '') {
    			$query->where('member_page_facebook_id','=', $dataSearch['member_page_facebook_id']);
    		}
    		
    		if (isset($dataSearch['member_page_status']) && $dataSearch['member_page_status'] != -1) {
    			$query->where('member_page_status','=', $dataSearch['member_page_status']);
    		}
    	  	
    		$total = $query->count();
    		$query->orderBy('member_id', 'desc');
    
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
    	$result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_MEMBER_PAGE_ID.$id) : array();
    	try {
    		if(empty($result)){
    			$result = MemberPage::where('member_page_id', $id)->first();
    			if($result && Memcache::CACHE_ON){
    				Cache::put(Memcache::CACHE_MEMBER_PAGE_ID.$id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
    			}
    		}
    	} catch (PDOException $e) {
    		throw new PDOException();
    	}
    	return $result;
    }
     
    public static function updateData($id=0, $dataInput=array()){
    	try {
    		DB::connection()->getPdo()->beginTransaction();
    		$data = MemberPage::find($id);
    		if($id > 0 && !empty($dataInput)){
    			$data->update($dataInput);
    			if(isset($data->member_page_id) && $data->member_page_id > 0){
    				self::removeCacheId($data->member_page_id);
					if(isset($data['member_page_facebook_id'])){
						self::removeCachePageFacebookId($data['member_page_facebook_id'], $data['member_id']);
					}
    			}
    		}
    		DB::connection()->getPdo()->commit();
    		return true;
    	} catch (PDOException $e) {
    		DB::connection()->getPdo()->rollBack();
    		throw new PDOException();
    	}
    }
    
    public static function updateDataByMemberID($member_id=0, $member_page_facebook_id=0, $dataInput=array()){
    	try {
    		$data = MemberPage::where('member_id', $member_id)->where('member_page_facebook_id', $member_page_facebook_id)->first();
    		if($member_id != '' && $member_page_facebook_id != '' && !empty($dataInput) && sizeof($data) > 0){
    			MemberPage::updateData($data->member_page_id, $dataInput);
    			if(isset($data->member_page_id) && $data->member_page_id != ''){
    				self::removeCacheId($data->member_page_id);
    				if(isset($data['member_page_facebook_id'])){
    					self::removeCachePageFacebookId($data['member_page_facebook_id'], $data['member_id']);
    				}
    			}
    		}
    		return true;
    	} catch (PDOException $e) {
    		DB::connection()->getPdo()->rollBack();
    		throw new PDOException();
    	}
    }
     
    public static function addData($dataInput=array()){
    	try {
    		DB::connection()->getPdo()->beginTransaction();
    		$data = new MemberPage();
    		if (is_array($dataInput) && count($dataInput) > 0) {
    			foreach ($dataInput as $k => $v) {
    				$data->$k = $v;
    			}
    		}
    		if ($data->save()) {
    			DB::connection()->getPdo()->commit();
    			if($data->member_page_id && Memcache::CACHE_ON){
    				Member::removeCacheId($data->member_page_id);
					if(isset($data['member_page_facebook_id'])){
						self::removeCachePageFacebookId($data['member_page_facebook_id'], $data['member_id']);
					}
    			}
    			return $data->member_page_id;
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
    		MemberPage::updateData($id, $data_post);
    		Utility::messages('messages', 'Cập nhật thành công!');
    	}else{
    		MemberPage::addData($data_post);
    		Utility::messages('messages', 'Thêm mới thành công!');
    	}
    
    }
    
    public static function deleteId($id=0){
    	try {
    		DB::connection()->getPdo()->beginTransaction();
    		$data = MemberPage::find($id);
    		if($data != null){
    			$data->delete();
    			if(isset($data->member_page_id) && $data->member_page_id > 0){
    				self::removeCacheId($data->member_page_id);
    			}
				if(isset($data['member_page_facebook_id'])){
    				self::removeCachePageFacebookId($data['member_page_facebook_id'], $data['member_id']);
    			}
    			DB::connection()->getPdo()->commit();
    		}
    		return true;
    	} catch (PDOException $e) {
    		DB::connection()->getPdo()->rollBack();
    		throw new PDOException();
    	}
    }
    public static function deleteByCondMemberIdAndPageId($member_id='', $page_id=''){
        try {
            if($member_id != '' && $page_id != '') {
                $data = MemberPage::where('member_id', '=', $member_id)->where('member_page_facebook_id', '=', $page_id)->first();
                if($data != null){
                    $data->delete();
                    if(isset($data->member_page_id) && $data->member_page_id > 0){
                        self::removeCacheId($data->member_page_id);
                    }
                    if(isset($data['member_page_facebook_id'])){
                        self::removeCachePageFacebookId($data['member_page_facebook_id'], $data['member_id']);
                    }
                }
            }
        } catch (PDOException $e) {
            throw new PDOException();
        }
    }


    public static function removeCacheId($id=0){
    	if($id>0){
    		Cache::forget(Memcache::CACHE_MEMBER_PAGE_ID.$id);
    	}
    }
    
	public static function removeCachePageFacebookId($member_page_facebook_id='', $member_id=''){
    	if($member_page_facebook_id != ''){
    		Cache::forget(Memcache::CACHE_MEMBER_PAGE_FACEBOOK_ID.$member_page_facebook_id.'_'.$member_id);
    	}
    }
	
    public static function getAllPageByUserFacebookId($member_id=''){
    	$result = array();
    	if($member_id != ''){
    		$result = MemberPage::where('member_id', $member_id)->get();
    	}
    	return $result;
    }
	
	public static function getByPageFacebookId($member_page_facebook_id='', $member_id=''){
    	$result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_MEMBER_PAGE_FACEBOOK_ID.$member_page_facebook_id.'_'.$member_id) : array();
    	try {
    		if(empty($result)){
				$query = MemberPage::where('member_id', $member_id);
    			$result = $query->where('member_page_facebook_id', $member_page_facebook_id)->first();
    			if($result != '' && Memcache::CACHE_ON){
    				Cache::put(Memcache::CACHE_MEMBER_PAGE_FACEBOOK_ID.$member_page_facebook_id.'_'.$member_id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
    			}
    		}
    	} catch (PDOException $e) {
    		throw new PDOException();
    	}
    	return $result;
    }
}
