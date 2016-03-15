jQuery(document).ready(function($){
	
	GET_DICTRICTS.init();
	COMMENT.init();
	COMMENT.delete();
	COMMENT.popup_comment();
	INPUT_TIME.config();
	INPUT_TIME.showDatepicker('input[name="txtTime_send"]');
	UPDATE_CHECKING.update();
	LOAD_PRINT.init();
});

GET_DICTRICTS = {
	init:function(){
		jQuery('select[name="txtProvice"]').change(function(){
			jQuery('select[name="txtProvice"] option:selected').each(function(){
				var provice_id = jQuery('select[name="txtProvice"]').val();
				var estates_id = jQuery('input[name="txtId"]').val();
				//ajax load dictrict
				var domain = BASEPARAMS.base_url;
				var url = domain + '/admincp/dictricts/getdictricts';
				if(provice_id > 0){
					jQuery('#ajaxLoadingPost').css('display', 'block');
					jQuery.ajax({
						type: "POST",
						url: url,
						data: "provice_id=" + encodeURI(provice_id) + "&estates_id=" + encodeURI(estates_id),
						success: function(data){
							jQuery('select[name="txtDictrict"]').html(data);
							jQuery('#ajaxLoadingPost').css('display', 'none');
						}
					});	
				}			
			});
	   });
		if(jQuery('select[name="txtProvice"]').val()>0){
			var provice_id = jQuery('select[name="txtProvice"]').val();
			var estates_id = jQuery('input[name="txtId"]').val();
			//ajax load dictrict
			var domain = BASEPARAMS.base_url;
			var url = domain + '/admincp/dictricts/getdictricts';
			if(provice_id > 0){
				jQuery('#ajaxLoadingPost').css('display', 'block');
				jQuery.ajax({
					type: "POST",
					url: url,
					data: "provice_id=" + encodeURI(provice_id) + "&estates_id=" + encodeURI(estates_id),
					success: function(data){
						jQuery('select[name="txtDictrict"]').html(data);
						jQuery('#ajaxLoadingPost').css('display', 'none');
					}
				});	
			}
		}
	}
}
COMMENT = {
	init:function(){
		jQuery('.txtclicknote').click(function(){
			var frmcomment = jQuery('#frmcomment').val();
			var pid = jQuery('input[name="txtId"]').val();
			var url = BASEPARAMS.base_url + '/admincp/ajaxupComment';
			if(frmcomment==''){
				jAlert( 'Nội dung báo cáo không được trống!', 'Cảnh báo');
				return false;
			}else{
				if(pid>0){
					jQuery.ajax({
						type: "POST",
						url: url,
						data: "frmcomment="+encodeURI(frmcomment) + "&pid="+encodeURI(pid),
						success: function(data){
							if(data!=''){
								jQuery('.list-comment ul').append(data);
								jQuery('#frmcomment').val('');
								COMMENT.delete();
								return false;
							}
						}
					});
				}
			}
		});
	},

	delete:function(){
		jQuery('.comment-delete').click(function(){
			var id = jQuery(this).attr('data');
			var url = BASEPARAMS.base_url + '/admincp/ajaxdeleteComment';
			removeItem = jQuery(this);
			if(id > 0){
				jConfirm('Bạn muốn xóa [OK]:Yes[Cancel]:No?', 'Xác nhận', function(r) {
					if(r){
						jQuery.ajax({
							type: "POST",
							url: url,
							data: "id="+encodeURI(id),
							success: function(data){
								if(data == 'ok'){
									removeItem.parent('li').remove();
									location.reload();
									return false;
								}
							}
						});
					}
				});
			}
		});
	},
	popup_comment:function(){
		jQuery('a.item-comment, .x_check_comment').unbind("click").click(function(){
			var orderId = jQuery(this).attr('rel');
			jQuery('.pidComment').attr('rel', orderId);
			jQuery('#dialog_comment').dialog({
				title: 'Comment đơn hàng',
				width: 700,
				resizable: false,
				modal: true,
				close: function(){}
			});
			
			jQuery('.ui-widget-overlay').live('click', function() {
			     jQuery('#dialog_comment').dialog( "close" );
		    });

		  	//get all comment of pid
		  	var url_all_comment = BASEPARAMS.base_url + '/admincp/popupAjaxAllComment';
		  	jQuery('.list-comment ul').html('');
		  	jQuery.ajax({
				type: "POST",
				url: url_all_comment,
				data: "pid="+encodeURI(orderId),
				success: function(data){
					if(data!=''){
						jQuery('.list-comment ul').append(data);
						jQuery('#frmcomment').val('');
						COMMENT.delete();
						return false;
					}
				}
			});

			//get detail order of pid
			 var url_all_detail_order = BASEPARAMS.base_url + '/admincp/ajaxAllDetailOrder';
		 	 jQuery('.detail-product-order').html('');
		 	 jQuery.ajax({
				type: "POST",
				url: url_all_detail_order,
				data: "pid="+encodeURI(orderId),
				success: function(data){
					if(data!=''){
						jQuery('.detail-product-order').append(data);
						return false;
					}
				}
			});
		  	//comment pid
			jQuery('.txtclickcomment').unbind("click").click(function(){
			
				var frmcomment = jQuery('#frmcomment').val();
				var pid = jQuery('span.pidComment').attr('rel');
				var url = BASEPARAMS.base_url + '/admincp/ajaxupComment';
				if(frmcomment==''){
					jAlert( 'Nội dung báo cáo không được trống!', 'Cảnh báo');
					return false;
				}else{
					if(pid>0){
						jQuery.ajax({
							type: "POST",
							url: url,
							data: "frmcomment="+encodeURI(frmcomment) + "&pid="+encodeURI(pid),
							success: function(data){
								if(data!=''){
									jQuery('.list-comment ul').append(data);
									jQuery('#frmcomment').val('');
									COMMENT.delete();
									location.reload();
									return false;
								}
							}
						});
					}
				}
			});

		});
	},
}
INPUT_TIME = {
	config:function(){
		//config datepicker
		jQuery.datepicker.regional['vi'] = {
					closeText: 'Đóng',
					prevText: '&#x3c;Trước',
					nextText: 'Tiếp&#x3e;',
					currentText: 'Hôm nay',
					monthNames: ['Tháng Một', 'Tháng Hai', 'Tháng Ba', 'Tháng Tư', 'Tháng Năm', 'Tháng Sáu',
					'Tháng Bảy', 'Tháng Tám', 'Tháng Chín', 'Tháng Mười', 'Tháng Mười Một', 'Tháng Mười Hai'],
					monthNamesShort: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
					'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
					dayNames: ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'],
					dayNamesShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
					dayNamesMin: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
					weekHeader: 'Tu',
					dateFormat: 'dd/mm/yy',
					firstDay: 0,
					isRTL: false,
					showMonthAfterYear: false,
					yearSuffix: ''
		};
		jQuery.datepicker.setDefaults(jQuery.datepicker.regional['vi']); 
	},
	showDatepicker:function(tagClick){
		jQuery(tagClick).datepicker();
	},
}

UPDATE_CHECKING = {
	update:function(){
		jQuery(".update_status").unbind("click").click(function(){
			var pid = jQuery(this).attr('rel');
			jQuery('.pidupdatestatus').attr('rel', pid);
			jQuery('#dialog_update_status').dialog({
				title: 'Cập nhật trang thái đơn hàng',
				width: 300,
				resizable: false,
				modal: true,
				close: function(){}
			});
			
			jQuery('.ui-widget-overlay').live('click', function() {
			     jQuery('#dialog_update_status').dialog( "close" );
		    });

			var text_status = jQuery(this).parent('span.item-status').attr('data-check');
			jQuery('select[name="statusUpdate"] option').each(function(){
				var textStatus = jQuery(this).val();
				jQuery(this).removeAttr('selected');
				if(text_status == textStatus){
					jQuery(this).attr('selected', 'selected');
				}
			});

		    jQuery('.txtupdatestatus').unbind("click").click(function(){
				var pid = jQuery('span.pidupdatestatus').attr('rel');
				var checking = jQuery('select[name="statusUpdate"]').val();
				var url = BASEPARAMS.base_url + '/admincp/ajaxUpdateStatus';
				if(checking == ''){
					jAlert('Trạng thái ko được trống!', 'Cảnh báo');
					return false;
				}else{
					if(pid>0){
						jQuery.ajax({
							type: "POST",
							url: url,
							data: "checking="+encodeURI(checking) + "&pid="+encodeURI(pid),
							success: function(data){
								if(data!=''){
									location.reload();
									return false;
								}
							}
						});
					}
				}
			});
		})
	}
}

LOAD_PRINT = {
	init:function(){
		jQuery('a.item-print').unbind("click").click(function(event){
			var orderId = jQuery(this).attr('rel');
		  	event.preventDefault();
    	  	window.open(BASEPARAMS.base_url+'/admincp/order/printer?order='+orderId, 'In phiếu', 'width=445, height=800');
		});
	},
}