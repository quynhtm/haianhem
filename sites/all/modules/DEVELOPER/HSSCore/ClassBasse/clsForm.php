<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class clsForm{

	static function setFieldName($key=null){
		if($key==''){
			return ;
		}else{
			return 'txt'.ucfirst($key);
		}
	}

	static function itemPostValue($key=null){
		if($key!=''){
			$inputName = self::setFieldName($key);
			if(isset($_POST[$inputName])){
				return trim($_POST[$inputName]);
			}
		}
		return null;
	}

	static function buildItemFields($arr_fields=null){
		if(is_array($arr_fields)){

			foreach($arr_fields as $key => $field){
				//label of input field: title, intro, content....
				if(!isset($field['label'])){
					$arr_fields[$key]['label'] = ucfirst($key);
				}

				//type of input field: text, password, textarea, radio, checkbox, hidden...
				if(!isset($field['type'])){
					$arr_fields[$key]['type'] = 'text';
				}

				//value of input field
				if(!isset($field['value'])){
					$arr_fields[$key]['value'] = '';
				}

				//require of input field: allow is null = 0, not null = 1
				if(!isset($field['require'])){
					$arr_fields[$key]['require'] = '';
				}

				//attr of input field is: style, class, id....
				if(!isset($field['attr'])){
					$arr_fields[$key]['attr'] = '';
				}

				//list option
				if($field['type']=='option'){
					if(!isset($field['list_option'])){
						$arr_fields[$key]['list_option'] = array();
					}
				}

				//editor of textarea....
				if($field['type']=='textarea'){
					if(!isset($field['editor'])){
						$arr_fields[$key]['editor'] = 0;
					}
				}

			}

			return $arr_fields;
		}
		return '';
	}

	static function addInputText($key=null, $field=null){
		$fieldName = self::setFieldName($key);
		$fieldValue = $field['value'];
		$fieldAttr = $field['attr'];
		$input = '<input type="text" name="'.$fieldName.'"  value="'.$fieldValue.'" '.$fieldAttr.' />';
		return $input;
	}

	static function addInputPassword($key=null, $field=null){
		$fieldName = self::setFieldName($key);
		$fieldValue = $field['value'];
		$fieldAttr = $field['attr'];
		$input = '<input type="password" name="'.$fieldName.'"  value="'.$fieldValue.'" '.$fieldAttr.' />';
		return $input;

	}

	static function addInputHidden($key=null, $field=null){
		$fieldName = self::setFieldName($key);
		$fieldValue = $field['value'];
		$fieldAttr = $field['attr'];
		$input = '<input type="hidden" name="'.$fieldName.'"  value="'.$fieldValue.'" '.$fieldAttr.' />';
		return $input;

	}

	static function addInputTextarea($key=null, $field=null){
		$fieldName = self::setFieldName($key);
		$fieldValue = $field['value'];
		$fieldAttr = $field['attr'];
		$input = '<textarea name="'.$fieldName.'" '.$fieldAttr.'>'.$fieldValue.'</textarea>';
		return $input;
	}

	static function addEditor($key=null, $field=null){
		$fieldName = self::setFieldName($key);
		$fieldTextarea = $field['type'];
		return 'CKEDITOR.replace("'.$fieldName.'"); ';
	}

	static function addSelect($key=null, $field=null){
		$fieldName = self::setFieldName($key);
		$fieldValue = $field['value'];
		$list_option = $field['list_option'];

		$html='';
		$selected='';
		if($list_option && is_array($list_option)){
			$html.='<select name="'.$fieldName.'">';
			foreach($list_option as $val=>$title){
				$selected=($val==$fieldValue) ? 'selected="selected"' : '';
				$html.='<option value="'.$val.'" '.$selected.'>'.$title.'</option>';
			}
			$html.='</select>';
		}

		return $html;
	}

	static function addInputFile($key=null, $field=null){
		$fieldName = self::setFieldName($key);
		$fieldValue = $field['value'];
		$fieldAttr = $field['attr'];
		$input = '<input type="file" name="'.$fieldName.'" '.$fieldAttr.' />';
		$input .= '<div class="listfiles">'.$fieldValue.'</div>';

		return $input;
	}

	static function addInputLang($key=null, $field=null){
		global $base_url;
		$languages = language_list('enabled');

		$fieldName = self::setFieldName($key);
		$fieldValue = $field['value'];
		$fieldAttr = $field['attr'];

		$html = '';
		$html .= '<select name="'.$fieldName.'" '.$fieldAttr.'>';
			$html .= '<option value="">---'.t("Choise language").'---</option>';
			foreach($languages[1] as $v){
				$selected=($fieldValue==$v->language) ? 'selected="selected"' : '';
				$html .= '<option value="'.$v->language.'" '.$selected.'>'.$v->name.'</option>';
			}
			$html .= '</select>';

		return $html;
	}
	
	static function addMutiUploadFile($field=null){
		global $base_url;
		$fieldValue = isset($field['value']) ? $field['value'] : array();
		$str='';
		if(count($fieldValue)>0){
			foreach($fieldValue as $v){
				$str .= '<div class="listFildDb">';
					$str .='<div class = "rowprocess" id ="id_file'.$v->id.'" >';
						$str .='<div class = "thumb" >';
							$str .='<img src="'.$base_url.'/uploads/images/product/'.$v->path.'">';
						$str .='</div>';
						$str .='<p>';
							$str .='<span class = "status" ></span>';
							$str .='<a id="del'.$v->id.'" href="javascript:void(0)" class="del_file" rel="'.$v->id.'" title="'.$v->path.'">Xóa ảnh</a>';
						$str .='</p>';
					$str .='</div>';
				$str .='</div>';
			}
		}
		$html = '';
		$html .= '<span class="wrappButton">
						<span class="btn-add"> Thêm ảnh...</span>
							<input type="file" id="txtImagesPost" class="btn btn-primary"  name="txtImagesPost"/>
						</span>
				  </span>';
		$html.=	'<div class="control-group">
					  <div class="controls" style="margin-left: 0px;">';		 
						  $html .= $str;
						  $html .= '<div id="load_file_ajax"></div>';
			 $html.=  '</div>';
		$html.=	'</div>';
		return $html;
	}

	static function addMutiUploadFileGift($field=null){
		global $base_url;
		$fieldValue = isset($field['value']) ? $field['value'] : array();
		$str='';
		if(count($fieldValue)>0){
			foreach($fieldValue as $v){
				$str .= '<div class="listFildDb">';
					$str .='<div class = "rowprocess" id ="id_file'.$v->id.'" >';
						$str .='<div class = "thumb" >';
							$str .='<img src="'.$base_url.'/uploads/images/product/'.$v->path.'">';
						$str .='</div>';
						$str .='<p>';
							$str .='<span class = "status" ></span>';
							$str .='<a id="del'.$v->id.'" href="javascript:void(0)" class="del_file" rel="'.$v->id.'" title="'.$v->path.'">Xóa ảnh</a>';
						$str .='</p>';
					$str .='</div>';
				$str .='</div>';
			}
		}
		$html = '';
		$html .= '<span class="wrappButton">
						<span class="btn-add"> Thêm ảnh...</span>
							<input type="file" id="txtImagesPostGift" class="btn btn-primary"  name="txtImagesPostGift"/>
						</span>
				  </span>';
		$html.=	'<div class="control-group">
					  <div class="controls" style="margin-left: 0px;">';		 
						  $html .= $str;
						  $html .= '<div id="load_file_ajax_gift"></div>';
			 $html.=  '</div>';
		$html.=	'</div>';
		return $html;
	}
	static function addMutiSize($field=null){
		global $base_url;
		$fieldValue = isset($field['value']) ? unserialize($field['value']) : array(); 
		$str='';

		$str .= '<div class="list-size">';
					if(is_array($fieldValue) && count($fieldValue)>0){
						foreach($fieldValue as $v){
							$str .= '<div class="p-size-item">';
								$str .= '<div class="p-size"><span>Cỡ</span><input class="" name="size[]" value="'.$v['size'].'" /></div>';
								$str .= '<div class="p-num"><span>Số lượng</span><input class="" name="num[]" value="'.$v['no'].'" /></div> <span class="del-size">xóa</span>';
							$str .='</div>';
						}
					}else{
						$str .= '<div class="p-size-item">';
							$str .= '<div class="p-size"><span>Cỡ</span><input class="" name="size[]" /></div>';
							$str .= '<div class="p-num"><span>Số lượng</span><input class="" name="num[]" /></div>';
						$str .='</div>';
					}

		$str .='</div>';
		
		$click_add = '';
		$click_add .= '<span class="btn-add-size"> Click thêm size...</span>';
		
		$html = '';
		$html.=	'<div class="control-group">
					  <div class="controls" style="margin-left: 0px;">';		 
						  $html .= $str.$click_add;
			 $html.=  '</div>';
		$html.=	'</div>';
		return $html;
	}
}
//example:
/*
$arr_fields = array(
	'title'=>array('type'=>'text', 'label'=>'Title', 'value'=>'','require'=>'require', 'attr'=>''),
	'password'=>array('type'=>'password', 'label'=>'Password', 'value'=>'','require'=>'require', 'attr'=>''),
	'intro'=>array('type'=>'textarea', 'label'=>'Intro', 'value'=>'', 'attr'=>''),
);

$fields = HSSForm::buildItemFields($arr_fields);

//build field;
if(isset($fields)){
	$require='';
	foreach ($fields as $key => $filed) {
		if(isset($filed['require']) && $filed['require']=='require'){
			$require='<span>*</span>';
		}else{
			$require='';
		}
		echo '<div class="control-group">';
		echo '<label class="control-label">'.$filed['label'].' '.$require.'</label>';
			echo '<div class="controls">';
				switch ($filed['type']) {
					case 'text':
						echo HSSForm::addInputText($key, $filed);break;

					case 'textarea':
						echo HSSForm::addInputTextarea($key, $filed);break;

					case 'password':
						echo HSSForm::addInputPassword($key, $filed);break;

					case 'hidden':
						echo HSSForm::addInputHidden($key, $filed);break;
					 case 'option':
                         $list_status = array(
                                        '0'=>t('Not published'),
                                        '1'=>t('Published')
                                    );
                        echo HSSForm::addSelect($key, $filed, $filed['value'], $list_status);break;
					default:
						echo HSSForm::addInputText($key,$field);break;
				}
			echo '</div>';
		echo '</div>';
	}
}
//check field
if(!empty($_POST) && $_POST['txtFormName']=='txtFormName'){
		$require_post = array();
		$data_post = array();
		$data_post['uid ']=$user->uid;
		$data_post['created ']=time();
		$data_post['view_num'] = 0;

		foreach ($data['fields'] as $key => $field) {
			$data_post[$key] = HSSForm::itemPostValue($key);
			$data['fields'][$key]['value']=$data_post[$key];

			if(isset($field['require']) && $field['require']=='require' && $data_post[$key]==''){
				$require_post[$key] = t($field['label']).' '.t('không được rỗng!');
			}

			if($key=='title'){
				$data_post['title_alias']=$HSSStdio->pregReplaceStringAlias(HSSForm::itemPostValue('title'));
			}
		}

		unset($_POST);
		if(count($require_post)>0){
			foreach ($require_post as $k => $v) {
				form_set_error($k, $v);
			}
			unset($data_post);
		}else{
			if($data['fields']['id']['value']>0){
				$query = $AdminCategoryModel->updateOne($data_post, $data['fields']['id']['value']);
				unset($data_post);
				drupal_set_message('Sửa bài viết thành công.');
				drupal_goto($base_url.'/admincp/category');
			}else{
				$query = $AdminCategoryModel->insertOne($data_post);
				unset($data_post);
				if($query){
					drupal_set_message('Thêm bài viết thành công.');
					drupal_goto($base_url.'/admincp/category');
				}
			}
		}
	}

	//get item update
	$param = arg();
	if(isset($param[2]) && isset($param[3]) && $param[2]=='edit' && $param[3]>0){
		$arrOneItem = $AdminCategoryModel->getOne("*", $param[3]);
		foreach ($data['fields'] as $key => $filed) {
			$data['fields'][$key]['value']=$arrOneItem[0]->$key;
		}
	}
*/