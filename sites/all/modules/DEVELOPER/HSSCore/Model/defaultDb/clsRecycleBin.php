<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class RecycleBin extends DbBasic{

	function __construct(){
        $this->pkey = 'id';
        $this->table = 'hss_recycle_bin';
    }

    public function addItem($id=0, $class="", $folder = ""){
    	global $user, $base_url;
    	if($user->uid > 0){
    		$uid = $user->uid;
    	}else{
    		$uid = 0;
    	}
    	if($id > 0){
    		if(class_exists($class)){
    			$objClass = new $class();
    			$oneItem = $objClass->getOne("*", $id);
    			$img = '';
                if(isset($oneItem[0]->img) && $oneItem[0]->img !=''){
                    $arrEx = explode("/", $oneItem[0]->img);
                    $folder_recyclebin = DRUPAL_ROOT.'/uploads/images/recyclebin/'.$arrEx[0];
                    if(!is_dir($folder_recyclebin)){
                        @mkdir($folder_recyclebin,0777,true);
                        chmod($folder_recyclebin,0777);
                    }
                    $file_current = DRUPAL_ROOT.'/uploads/images/'.$folder.'/'.$oneItem[0]->img;
                    $file_recyclebin = $folder_recyclebin.'/'.$arrEx[1];
                    if(is_file($file_current)){
                        copy($file_current, $file_recyclebin);
                        $img = $oneItem[0]->img;
                    }
                }
                if(count($oneItem)>0){
    				$title = '';
                    if(isset($oneItem[0]->title)){
                        $title = $oneItem[0]->title;
                    }elseif(isset($oneItem[0]->name)){
                        $title = $oneItem[0]->name;
                    }else{
                        $title = '';
                    }
                    $data = array(
    							'title'=>$title,
    							'uid' => $uid,
    							'class' => $class,
    							'content'=>serialize($oneItem),
                                'img'=>$img,
                                'folder'=>$folder,
    							'created'=>time(),
    						);
    				$this->insertOne($data);
    			}
    		}
    	}
    	return true;
    }

    public function restoreItem($id=0){
    	global $user;
    	if($user->uid > 0){
    		$uid = $user->uid;
    	}else{
    		$uid = 0;
    	}
    	if($id > 0){
			$oneItem = $this->getOne("*", $id);
			if(count($oneItem) > 0){
				$data = array();
				if(class_exists($oneItem[0]->class)){
					$objClass = new $oneItem[0]->class();
					$showItem = unserialize($oneItem[0]->content);
					if(count($showItem) > 0){
                        if(isset($showItem[0]->img) && $showItem[0]->img !=''){
                            $arrEx = explode("/", $showItem[0]->img);
                            $file_recyclebin = DRUPAL_ROOT.'/uploads/images/recyclebin/'.$showItem[0]->img;
                            $folder_current = DRUPAL_ROOT.'/uploads/images/'.$oneItem[0]->folder.'/'.$arrEx[0];
                            if(!is_dir($folder_current)){
                                @mkdir($folder_current,0777,true);
                                chmod($folder_current,0777);
                            }
                            $file_current = $folder_current.'/'.$arrEx[1];
                            if(is_file($file_recyclebin)){
                                copy($file_recyclebin, $file_current);
                                unlink($file_recyclebin);
                            }
                        }
                        $data = (array)$showItem[0];
						$objClass->insertOne($data);
					}
				}
			}	
    	}
    	return true;
    }
}





