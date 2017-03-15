<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Quynhtm
 */
class Product extends Eloquent
{
    protected $table = 'web_product';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    //cac truong trong DB
    protected $fillable = array('product_id','product_code', 'product_name', 'category_name', 'category_id','provider_id',
        'product_price_sell', 'product_price_market', 'product_price_input', 'product_price_provider_sell','product_type_price','product_selloff',
        'product_is_hot', 'product_sort_desc', 'product_content','product_image','product_image_hover','product_image_other',
        'product_order', 'quality_input','quality_out','product_status','is_block','is_sale',
        'user_shop_id', 'user_shop_name', 'is_shop','shop_province','time_created', 'time_update');

    /**
     * @param $product_id
     * @return array
     */
    public static function getProductByID($product_id) {
        $product = (Memcache::CACHE_ON)? Cache::get(Memcache::CACHE_PRODUCT_ID.$product_id) : array();
        if (sizeof($product) == 0) {
            $product = Product::where('product_id', $product_id)->first();
            if($product && Memcache::CACHE_ON){
                Cache::put(Memcache::CACHE_PRODUCT_ID.$product_id, $product, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $product;
    }

    /**
     * @param $shop_id
     * @param $id
     * @return array
     */
    public static function getProductByShopId($shop_id,$product_id) {
        if($product_id > 0){
            $product = Product::getProductByID($product_id);
            if (sizeof($product) > 0) {
                if(isset($product->user_shop_id) && (int)$product->user_shop_id == $shop_id){
                    return $product;
                }
            }
        }
        return array();
    }

    public static function getListProductOfShopId($shop_id = 0, $field_get = array()) {
        if($shop_id > 0){
            $query = Product::where('user_shop_id','=',$shop_id);
            return $result = (!empty($field_get)) ? $query->get($field_get) : $query->get();
        }
        return array();
    }

    public static function getProductForSite($dataSearch = array(), $limit =0, $offset = 0, &$total){
        try{
            $query = Product::where('product_id','>',0);
            $query->where('product_status','=',CGlobal::status_show);
            $query->where('is_block','=',CGlobal::PRODUCT_NOT_BLOCK);
			//Duy add: get list product in array id
            if (isset($dataSearch['product_id'])) {
            	if (is_array($dataSearch['product_id'])) {
            		$query->whereIn('product_id', $dataSearch['product_id']);
            	}
            	elseif ((int)$dataSearch['product_id'] > 0) {
            		$query->where('product_id','=', (int)$dataSearch['product_id']);
            	}
            }
            
            if (isset($dataSearch['category_id'])) {
                if (is_array($dataSearch['category_id'])) {//tim theo m?ng id danh muc
                    $query->whereIn('category_id', $dataSearch['category_id']);
                }
                elseif ((int)$dataSearch['category_id'] > 0) {//theo id danh muc
                    $query->where('category_id','=', (int)$dataSearch['category_id']);
                }
            }

            if (isset($dataSearch['category_parent_id']) && $dataSearch['category_parent_id'] > 0) {
                $arrCatId = array();
                $arrChildCate = Category::getAllChildCategoryIdByParentId($dataSearch['category_parent_id']);
                if(!empty($arrChildCate)){
                    $arrCatId = array_keys($arrChildCate);
                }
                $query->whereIn('category_id', $arrCatId);
            }

            if (isset($dataSearch['user_shop_id']) && $dataSearch['user_shop_id'] != -1) {
                $query->where('user_shop_id','=', $dataSearch['user_shop_id']);
            }

            if (isset($dataSearch['product_is_hot']) && $dataSearch['product_is_hot'] != -1) {
                $query->where('product_is_hot','=', $dataSearch['product_is_hot']);
            }
            
            if (isset($dataSearch['shop_province']) && $dataSearch['shop_province'] != -1) {
            	$query->where('shop_province','=', $dataSearch['shop_province']);
            }
            //l?y kh�c shop id n�y
            if (isset($dataSearch['shop_id_other']) && $dataSearch['shop_id_other'] > 0) {
            	$query->where('user_shop_id','<>', $dataSearch['shop_id_other']);
            }

            //1: shop free, 2: shop thuong: 3 shop VIP
            if (isset($dataSearch['is_shop'])) {
                if (is_array($dataSearch['is_shop'])) {
                    $query->whereIn('is_shop', $dataSearch['is_shop']);
                }
                elseif ((int)$dataSearch['is_shop'] > 0) {
                    $query->where('is_shop', (int)$dataSearch['is_shop']);
                }
            }
            $total = $query->count();
            $query->orderBy('is_shop', 'desc')->orderBy('time_update', 'desc');

            //get field can lay du lieu
            $str_field_product_get = 'product_id,product_name,category_id,category_name,product_image,product_image_hover,product_status,product_price_sell,product_price_market,product_type_price,product_selloff,user_shop_id,user_shop_name,is_shop,is_block';//cac truong can lay
            $fields_get = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '')?trim($dataSearch['field_get']) : $str_field_product_get;
            $fields = (trim($fields_get) != '') ? explode(',',trim($fields_get)): array();
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


    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Product::where('product_id','>',0);
            if (isset($dataSearch['product_name']) && $dataSearch['product_name'] != '') {
                $query->where('product_name','LIKE', '%' . $dataSearch['product_name'] . '%');
            }
            if (isset($dataSearch['is_block']) && $dataSearch['is_block'] != -1) {
                $query->where('is_block', $dataSearch['is_block']);
            }
            if (isset($dataSearch['product_status']) && $dataSearch['product_status'] != -1) {
                $query->where('product_status', $dataSearch['product_status']);
            }
            if (isset($dataSearch['category_id']) && $dataSearch['category_id'] != -1) {
                $query->where('category_id', $dataSearch['category_id']);
            }
            if (isset($dataSearch['provider_id']) && $dataSearch['provider_id'] != -1) {
                $query->where('provider_id', $dataSearch['provider_id']);
            }
            if (isset($dataSearch['user_shop_id']) && $dataSearch['user_shop_id'] != -1) {
                $query->where('user_shop_id', $dataSearch['user_shop_id']);
            }
            
            if (isset($dataSearch['user_shop_id'])) {
            	if (is_array($dataSearch['user_shop_id'])) {
            		$query->whereIn('user_shop_id', $dataSearch['user_shop_id']);
            	}
            	elseif ((int)$dataSearch['user_shop_id'] > 0) {
            		$query->where('user_shop_id','=', (int)$dataSearch['user_shop_id']);
            	}
            }
            if (isset($dataSearch['product_id'])) {
            	if (is_array($dataSearch['product_id'])) {
            		$query->whereIn('product_id', $dataSearch['product_id']);
            	}
            	elseif ((int)$dataSearch['product_id'] > 0) {
            		$query->where('product_id','=', (int)$dataSearch['product_id']);
            	}
            }
        
            if (isset($dataSearch['product_is_hot']) && $dataSearch['product_is_hot'] > 0) {
                $query->where('product_is_hot', $dataSearch['product_is_hot']);
            }
            //lay theo id SP truyen vào và sap xep theo vi tri đã truyề vào
            if(isset($dataSearch['str_product_id']) && $dataSearch['str_product_id'] != ''){
                $arrProductId = explode(',', trim($dataSearch['str_product_id']));
                $query->whereIn('product_id', $arrProductId);
                //$query->orderBy('product_id', 'desc');
                $query->orderByRaw(DB::raw("FIELD(product_id, ".trim($dataSearch['str_product_id'])." )"));

            }else{
                $orderBy = 'desc';
                if(isset($dataSearch['orderBy']) && $dataSearch['orderBy'] !=''){
                    $orderBy = $dataSearch['orderBy'];
                }
                $query->orderBy('product_id', $orderBy);
            }

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
            $data = new Product();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                if(isset($data->product_id) && $data->product_id > 0){
                    self::removeCache($data->product_id);
                }
                return $data->product_id;
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
            $dataSave = Product::getProductByID($id);
            if (!empty($dataInput)){
                $dataSave->update($dataInput);
                if(isset($dataSave->product_id) && $dataSave->product_id > 0){
                    self::removeCache($dataSave->product_id);
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
            $dataSave = Product::find($id);
            $dataSave->delete();
            //xoa anh san pham
            if($dataSave->product_image_other != ''){
                $aryImages = unserialize($dataSave->product_image_other);
                if(sizeof($aryImages) > 0){
                    //xoa anh chinh
                    foreach($aryImages as $ki => $name_img){
                        FunctionLib::deleteFileUpload($name_img,$dataSave->product_id,CGlobal::FOLDER_PRODUCT);
                    }
                    //xoa anh thumb
                    $arrSizeThumb = CGlobal::$arrSizeImage;
                    foreach($aryImages as $kii => $name_img){
                        foreach($arrSizeThumb as $k=>$size){
                            $sizeThumb = $size['w'].'x'.$size['h'];
                            FunctionLib::deleteFileThumb($name_img,$dataSave->product_id,CGlobal::FOLDER_PRODUCT,$sizeThumb);
                        }
                    }
                }
            }
            if(isset($dataSave->product_id) && $dataSave->product_id > 0){
                self::removeCache($dataSave->product_id);
            }
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function removeCache($id = 0){
        if($id > 0){
            Cache::forget(Memcache::CACHE_PRODUCT_ID.$id);
        }
    }
}