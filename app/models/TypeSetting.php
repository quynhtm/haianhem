<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class TypeSetting extends Eloquent
{
    protected $table = 'web_type_setting';
    protected $primaryKey = 'type_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('type_title','type_infor','type_keyword','type_group','type_order','type_status');

    public static function getByID($id) {
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_TYPE_SETTING_ID.$id) : array();
        if (sizeof($data) == 0) {
            $data = TypeSetting::where('type_id','=', $id)->first();
            if($data && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_TYPE_SETTING_ID.$id, $data, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public static function getTypeSettingWithGroup($group_type,$key_id = false){
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_GROUP_TYPE_SETTING.$group_type) : array();
        if (sizeof($data) == 0) {
            $department = TypeSetting::where('type_id', '>', 0)
                ->where('type_group',$group_type)
                ->where('type_status',CGlobal::status_show)
                ->orderBy('type_order','asc')->get();
            if($department){
                foreach($department as $itm) {
                    if($key_id){
                        $data[$itm['type_id']] = $itm['type_title'];
                    }else{
                        $data[$itm['type_keyword']] = $itm['type_title'];
                    }

                }
            }
            if($data && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_GROUP_TYPE_SETTING.$group_type, $data, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public static function getTypeSettingWithKe($keyword){
        $data = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_KEYWORD_TYPE_SETTING.$keyword) : array();
        if (sizeof($data) == 0) {
            $data = TypeSetting::where('type_id', '>', 0)->where('type_keyword','=', $keyword)->first();
            if($data && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_KEYWORD_TYPE_SETTING.$keyword, $data, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = TypeSetting::where('type_id','>',0);
            if (isset($dataSearch['type_title']) && $dataSearch['type_title'] != '') {
                $query->where('type_title','LIKE', '%' . $dataSearch['type_title'] . '%');
            }
            if (isset($dataSearch['type_status']) && $dataSearch['type_status'] != -1) {
                $query->where('type_status', $dataSearch['type_status']);
            }
            $query->orderBy('type_id', 'desc');
            $total = $query->count();

            //get field can lay du lieu
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

    /**
     * @desc: Tao Data.
     * @param $data
     * @return bool
     * @throws PDOException
     */
    public static function addData($dataInput)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = new TypeSetting();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                if(isset($data->type_id) && $data->type_id > 0){
                    self::removeCache($data->type_id,$data);
                }
                return $data->type_id;
            }
            DB::connection()->getPdo()->commit();
            return false;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    /**
     * @desc: Update du lieu
     * @param $id
     * @param $data
     * @return bool
     * @throws PDOException
     */
    public static  function updateData($id, $dataInput)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = TypeSetting::find($id);
            if (!empty($dataInput)){
                $data->update($dataInput);
                if(isset($data->type_id) && $data->type_id > 0){
                    self::removeCache($data->type_id,$data);
                }
            }
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    /**
     * @desc: Update Data.
     * @param $id
     * @param $status
     * @return bool
     * @throws PDOException
     */
    public static function deleteData($id){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = TypeSetting::find($id);
            $data->delete();
            if(isset($data->type_id) && $data->type_id > 0){
                self::removeCache($data->type_id,$data);
            }
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            Cache::forget(Memcache::CACHE_TYPE_SETTING_ID.$id);
        }
        Cache::forget(Memcache::CACHE_KEYWORD_TYPE_SETTING.$data->type_keyword);
        Cache::forget(Memcache::CACHE_GROUP_TYPE_SETTING.$data->type_group);
    }
}