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
                        echo 'Thêm nhóm';
                    }else{
                        echo 'Sửa nhóm';
                    }
                ?>
            </h5>
        </div>
    </div>
    <div class="page-content-box permission">
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

                                    case 'file':
                                            echo clsForm::addInputFile($key, $filed);break;

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
            <div class="list-access-module">
                <div class="title-access">Phân quyền truy cập:</div>
                <div class="content-access">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <?php 
                            $arrAccessLink = array(
                                                'list'=>'List',
                                                'add'=>'Add',
                                                'view'=>'View',
                                                'edit'=>'Edit',
                                                'delete'=>'Delete',
                                                );
                            $clsModule = new _Module();
                            $clsPermissionDetail = new PermissionDetail();
                            $arrModule = $clsModule->getAll("title, title_alias, link", "status=1", "", "order_no ASC", "");
                            
                            $permission=array();
                            
                            if($id_check > 0){
                                $arrPermission = $clsPermissionDetail->getAll("rid, permission", "rid=$id_check", "", "id ASC", "1");
                                if(count($arrPermission)>0){
                                    $permission = unserialize($arrPermission[0]->permission);
                                }
                            }
                        ?>
                        <tr style="background:#ddd;border-bottom:1px solid #ccc;">
                            <td width="20%"><strong>Tên module</strong></td>
                            <?php foreach($arrAccessLink as $k=>$v){?>
                            <td style="text-align: center;"><strong><?php echo ucwords($v) ?></strong><br><input type="checkbox" name="<?php echo $k ?>" class="<?php echo $k ?>" /></td>
                            <?php } ?>
                        </tr>
                        <?php foreach($arrModule as $m){?>
                        <tr>
                            <td width="20%"><?php echo $m->title ?></td>
                            <?php foreach($arrAccessLink as $k=>$v){
                                if(isset($permission[$m->link][$k]) && isset($permission[$m->link][$k]) == 1){
                                    $str = ' value="1" checked="checked"';
                                }else{
                                   $str =' value="1"'; 
                                }
                            ?>
                            <td style="text-align: center;"><input type="checkbox" name="access<?php echo "[$m->link][$k]" ?>" class="item_<?php echo $k ?>" <?php echo $str ?>/></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <div class="form-actions">
                <input type="hidden" value="txtFormName" name="txtFormName">
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu</button>
                <button type="reset" class="btn">Bỏ qua</button>
            </div>
         </form>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        CHECKALL_ITEM.check_all('list');
        CHECKALL_ITEM.check_all('add');
        CHECKALL_ITEM.check_all('view');
        CHECKALL_ITEM.check_all('edit');
        CHECKALL_ITEM.check_all('delete');
    });
</script>