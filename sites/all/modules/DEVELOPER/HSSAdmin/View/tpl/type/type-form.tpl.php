<?php 
	global $base_url;
	$param = arg();
?>
<div class="inner-box">
	<div class="page-title-box">
		<div class="wrapper">
			<h5>
				<?php 
					if($param[2]=='add'){
						echo 'Thêm bài mới';
					}else{
						echo 'Sửa bài viết';
					}
				?>
			</h5>
		</div>
	</div>
	<div class="page-content-box">
		 <form class="form-horizontal" name="txtForm" action="" method="post" enctype="multipart/form-data">
		 	<?php 
                if(isset($fields)){
                    $require = '';
                    $field_editor ='';
                    foreach ($fields as $key => $filed) {
                        if(isset($filed['require']) && $filed['require']=='require'){
                            $require='<span>*</span>';
                        }else{
                            $require='';
                        }

                        if(isset($filed['editor']) && $filed['editor']==1 && $filed['type']=='textarea'){
                            $field_editor .= clsForm::addEditor($key, $filed);
                        }

                        echo '<div class="control-group">';
                        echo '<label class="control-label">'.$filed['label'].' '.$require.'</label>';
                            echo '<div class="controls">';
                                switch ($filed['type']) {

                                    case 'text':
                                        echo clsForm::addInputText($key, $filed);break;
                                    
                                    case 'textarea':
                                        echo clsForm::addInputTextarea($key, $filed);break;

                                    case 'password':
                                        echo clsForm::addInputPassword($key, $filed);break; 

                                    case 'hidden':
                                        echo clsForm::addInputHidden($key, $filed);break;

                                    case 'language':
                                        echo clsForm::addInputLang($key, $filed);break;    
                                    
                                    case 'option':
                                        echo clsForm::addSelect($key, $filed);break;        
                                    
                                    default:
                                       echo clsForm::addInputText($key, $filed);break;
                                }
                            echo '</div>';
                        echo '</div>';
                    }
                    if($field_editor!=''){
                        echo '<script>'.$field_editor.'</script>';
                    }
                }
            ?>

            <div class="form-actions">
                <input type="hidden" value="txtFormName" name="txtFormName">
				<button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu</button>
                <button type="reset" class="btn">Bỏ qua</button>
            </div>
		 </form>	
	</div>
</div>