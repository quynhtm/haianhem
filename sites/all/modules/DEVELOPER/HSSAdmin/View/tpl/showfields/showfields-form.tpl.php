<?php
    global $base_url;
    $param = arg();
    $module = isset($param[4]) ? $param[4] : '';
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
                ?> / <a href="<?php echo $base_url ?>/admincp/module">Quay lại</a>
            </h5> 
        </div>
    </div>
    <div class="page-content-box showfield">
         <form class="form-horizontal" name="txtForm" action="" method="post" enctype="multipart/form-data">
            <div class="control-group"><label class="control-label">Tên module: <b><?php echo ucwords($module) ?></b></label></div>
            <input type="hidden" value="<?php echo $module ?>" name="module">
            <div class="list-access-module">
                <div class="title-access">Phân quyền hiển thị trường dữ liệu:</div>
                <div class="content-access">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <?php 
                            $clsRoles = new _Roles();
                            $arrRole = $clsRoles->getAll($_fields="rid, name", $_cond="", $_groupby="", $_oderby="name ASC", $_limit="");
                            $arrFields = array();
                            $list_field = array();
                            if($param[0] = 'admincp' && $module != ''){
                                $clsModule = new _Module();
                                $arrFields = $clsModule->getAll("id, link, fields", "link='".$module."'", "", "id ASC", "1");
                                if(count($arrFields)>0){
                                    if($arrFields[0]->fields != ''){
                                        $list_field = unserialize($arrFields[0]->fields);
                                    }
                                }
                            }
                        ?>
                        <tr style="background:#ddd;border-bottom:1px solid #ccc;">
                            <td width="20%"><strong>Tên trường hiển thị</strong></td>
                            <?php foreach($arrRole as $v){?>
                            <td style="text-align: center;"><strong><?php echo ucwords($v->name) ?></strong><br><input type="checkbox" name="<?php echo $v->name ?>" class="<?php echo str_replace(" ","_",$v->name) ?>" /></td>
                            <?php } ?>
                        </tr>
                        
                        <?php
                            foreach($list_field as $field){
                        ?>
                        <tr>
                            <td width="20%"><?php if($field['label']==''){ echo 'ID'; }else{ echo $field['label']; } ?></td>
                            <?php foreach($arrRole as $v){?>
                            <?php 
                                $_ShowFields = new _ShowFields();
                                $check_module_field_show = $_ShowFields->getAll($_fields="id, fields", $_cond="module='".$module."'", $_groupby="", $_oderby="id ASC", $_limit="1"); 
                                $str='';
                                if(count($check_module_field_show)>0){
                                    $module_field_show = unserialize($check_module_field_show[0]->fields);
                                    if(isset($module_field_show[$v->name][$module][$field['field']])){
                                        $str = ' value="1" checked="checked"';
                                        if($field['field'] == 'id'){
                                            $str = ' value="1" checked="checked"';
                                        }
                                    }else{
                                        $str =' value="1"';
                                        if($field['field'] == 'id'){
                                            $str = ' value="1" checked="checked"';
                                        }
                                    }
                                }
                            ?>
                            <td style="text-align: center;"><input type="checkbox" name="show<?php echo '['.$v->name.']['.$module.']['.$field['field'].']' ?>" class="item_<?php echo str_replace(" ","_",$v->name) ?>" <?php echo $str ?>/></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <div class="form-actions">
                <input type="hidden" value="txtFormName" name="txtFormName">
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu</button>
                <input type="submit" name="txtSubmitNext" id="buttonSubmitNext" class="btn btn-primary" value="Lưu và tiếp tục" />
                <button type="reset" class="btn">Bỏ qua</button>
            </div>
         </form>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        <?php foreach($arrRole as $v){?>
        CHECKALL_ITEM.check_all('<?php echo str_replace(" ","_",$v->name) ?>');
        <?php } ?>
    });
</script>