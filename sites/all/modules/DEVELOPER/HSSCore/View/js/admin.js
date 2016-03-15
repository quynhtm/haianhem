jQuery(document).ready(function($){
	CHECKALL_ITEM.init();
	HISTORY_BACK.init();
	HIDDEN_MENU_ADMIN.init();
	ADD_SIZE_PRODUCT.init();
	RESTORE_ITEM.init();
});

CHECKALL_ITEM = {
	init:function(){
		jQuery("input#checkAll").click(function(){
			var checkedStatus = this.checked;
			jQuery("input.checkItem").each(function(){
				this.checked = checkedStatus;
			});
		});
	},
	check_all:function(strs){
		if(strs != ''){
			jQuery("input." + strs).click(function(){
				var checkedStatus = this.checked;
				jQuery("input.item_" + strs).each(function(){
					this.checked = checkedStatus;
				});
			});
		}
	},
}
DELETE_ITEM={
	init:function(path_menu){
		jQuery('a#deleteMoreItem, a#deleteOneItem').click(function(){
			var total = jQuery( "input:checked" ).length;
			if(total==0){
				alert('Please select at least 1 item to delete!');
				return false;
			}else{
				if (confirm('Do you want to delete[OK]:Yes[Cancel]:No?)')){
					jQuery('form#formListItem').attr("action", BASEPARAMS.base_url+"/"+path_menu+"/delete");
					jQuery('form#formListItem').submit();
					return true;
				}
				return false;
			}
		});
	}
}

RESTORE_ITEM = {
	init:function(){
		
		jQuery('a#btnRestore').click(function(){
			var total = jQuery( "input:checked" ).length;
			if(total==0){
				alert('Please select at least 1 item to restore!');
				return false;
			}else{
				if (confirm('Do you want to restore[OK]:Yes[Cancel]:No?)')){
					jQuery('form#formListItem').attr("action", BASEPARAMS.base_url+"/admincp/recyclebin/restore");
					jQuery('form#formListItem').submit();
					return true;
				}
				return false;
			}
		});
	}
}

HISTORY_BACK = {
	init:function(){
		jQuery("button[type=reset]").click(function(){
	   		window.history.back();
	   });
	}
}

FORMAT_NUMBER={
	init:function(nStr){
		nStr += '';
	    var x = nStr.split('.');
	    var x1 = x[0];
	    var x2 = x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    }
	    return x1 + x2;
	}
}

HIDDEN_MENU_ADMIN = {
	init:function(){
		jQuery(".logo").click(function(){
			jQuery('.pageWrapper').toggleClass('act');
		});
	},
}

ADD_SIZE_PRODUCT = {
	init:function(){
		jQuery('.btn-add-size').click(function(){
			var item_size = '<div class="p-size-item"><div class="p-size"><span>Cỡ</span><input name="size[]" class=""></div><div class="p-num"><span>Số lượng</span><input name="num[]" class=""></div><span class="del-size">Xóa</span></div>';
			jQuery('.control-group .list-size').append(item_size);
			jQuery('.del-size').click(function(){
				jQuery(this).parent('div.p-size-item').remove();
			});
		});
		jQuery('.del-size').click(function(){
			jQuery(this).parent('div.p-size-item').remove();
		});
	}
}