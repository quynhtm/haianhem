jQuery(document).ready(function($){
  
  BACK_TOP.init();
 
  SUBMIT_POST.contact();
  SUBMIT_POST.order();
  SUBMIT_POST.search();
  
  TAB_SELECT.init();
  
  CLICK_VIEW_MORE.init();
  CLICK_VIEW_MORE.init_more_link();
  
  SHOP_CART.add_cart();
  SHOP_CART.update_cart();
  SHOP_CART.del_one_item();
  SHOP_CART.del_all_item();
  SHOP_CART.payment_order();
  
  CLICK_SIZE_PRODUCT.init();
  //BACK_CROLL_NAME.init();
  //BOOKMARKS.init();
  //CALC_TOTAL_PRICE_VIEW.calc();
  //FIXED.init();
});

BACK_TOP={
	init:function(){
		 jQuery(window).scroll(function() {
            if(jQuery(window).scrollTop() > 0) {
				jQuery("div#back-top-wrapper").fadeIn();
			} else {
				jQuery("div#back-top-wrapper").fadeOut();
			}
		});
		jQuery("div#back-top-wrapper, .gotop").click(function(){
			jQuery("html, body").animate({scrollTop: 0}, 1000);
			return false;
		});
	}
}

BOOKMARKS={
	init:function(){
		jQuery(".bookmark").click(function(e){
			jAlert( 'Nhấn CTRL+D và click link để bookmark!', 'Cảnh báo');
		});
	}	
}

SUBMIT_POST = {
	contact:function(){
		jQuery('#submitContact').click(function(){
			var valid = true;
			if(jQuery('#txtName').val() == ''){
				jQuery('#txtName').addClass('error');
				valid = false;
			}else{
				jQuery('#txtName').removeClass('error');
			}
			
			if(jQuery('#txtMobile').val() == ''){
				jQuery('#txtMobile').addClass('error');
				valid = false;
			}else{
				
				var regex = /^[0-9-+]+$/;
				var phone = jQuery('#txtMobile').val();
				if (regex.test(phone)) {
			        jQuery('#txtMobile').removeClass('error');
			    }else{
					jQuery('#txtMobile').addClass('error');	
				}
			}
			if(jQuery('#txtAddress').val() == ''){
				jQuery('#txtAddress').addClass('error');
				valid = false;
			}else{
				jQuery('#txtAddress').removeClass('error');
			}
			
			if(jQuery('#txtMessage').val() == ''){
				jQuery('#txtMessage').addClass('error');
				valid = false;
			}else{
				jQuery('#txtMessage').removeClass('error');
			}
			if(valid==false){
				return false;
			}
			return valid;
		});
	},
	order:function(){
		jQuery('#submitOrder').click(function(){
			var valid = true;
			if(jQuery('#txtName').val() == ''){
				jQuery('#txtName').addClass('error');
				valid = false;
			}else{
				jQuery('#txtName').removeClass('error');
			}
			
			if(jQuery('#txtMobile').val() == ''){
				jQuery('#txtMobile').addClass('error');
				valid = false;
			}else{
				
				var regex = /^[0-9-+]+$/;
				var phone = jQuery('#txtMobile').val();
				if (regex.test(phone)) {
			        jQuery('#txtMobile').removeClass('error');
			    }else{
					jQuery('#txtMobile').addClass('error');	
				}
			}
			if(jQuery('#txtAddress').val() == ''){
				jQuery('#txtAddress').addClass('error');
				valid = false;
			}else{
				jQuery('#txtAddress').removeClass('error');
			}
			
			if(jQuery('#txtMessage').val() == ''){
				jQuery('#txtMessage').addClass('error');
				valid = false;
			}else{
				jQuery('#txtMessage').removeClass('error');
			}
			if(valid==false){
				return false;
			}
			return valid;
		});
	},
	search:function(){
		jQuery(".btn-search").click(function(){
			var keyword = jQuery(".keyword").val();
			if(keyword == ''){
				jAlert("Bạn vui lòng nhập từ khóa!", 'Cảnh báo');
				return false;
			}else{

				jQuery("#frmsearch").submit();

			}

		});
	}
}

TAB_SELECT= {
	init:function(){
		jQuery(".ul-dieukien-noibat-khuyenmai li").click(function(){
			jQuery(".ul-dieukien-noibat-khuyenmai li").removeClass("selected");
			jQuery(".dieukien-noibat-view div.tab-content-hiden").hide();
			jQuery(this).addClass("selected");
			var tabshow = jQuery(this).attr("data");
			jQuery(".dieukien-noibat-view div."+ tabshow).show();
			
		});
	}
}

BACK_CROLL_NAME={
	init:function(){
		jQuery('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	      var target = jQuery(this.hash);
	      target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
	        jQuery('html,body').animate({
	          scrollTop: target.offset().top
	        }, 1000);
	        return false;
	      }
	    }
	  });
	}
}

CLICK_VIEW_MORE = {
	init:function(){
		jQuery(".view-more-guide-buy-pay").click(function(){
			if(jQuery(".content-guide-buy-pay").hasClass('act')){
				jQuery(".content-guide-buy-pay").removeClass('act');
				jQuery(".view-more-guide-buy-pay").text('Xem thêm');
			}else{
				jQuery(".content-guide-buy-pay").addClass('act');
				jQuery(".view-more-guide-buy-pay").text('Thu gọn');
			}
			return false;
		});
	},
	init_more_link(){
		
		jQuery(".click-more-view-cat").click(function(){
			if(jQuery(".list-link-cat").hasClass('act')){
				jQuery(".list-link-cat").removeClass('act');
			}else{
				jQuery(".list-link-cat").addClass('act');			}
			return false;
		});
	}
}

SHOP_CART = {
	add_cart:function(){
		jQuery('div#btn-add-cart').click(function(){
			var url = BASEPARAMS.base_url + '/add-cart';
			var pid = jQuery(this).attr('data-cart');
			var psize = jQuery('#productSize').val();
			var pnum = jQuery('#productNum').val();

			if(pid>0){
				jQuery.ajax({
					type: "POST",
					url: url,
					data: "pid="+encodeURI(pid),
					data: "pid="+encodeURI(pid) + "&psize="+encodeURI(psize)+ "&pnum="+encodeURI(pnum),
					success: function(data){
						if(data=='notAddCart'){
							jAlert('Không tồn tại sản phẩm!', 'Cảnh báo');
							return false;
						}else{
							jAlert('Đã thêm vào giỏ hàng!', 'Thông báo');
							window.location.reload();	
						}
					}
				});
			}
		});
	},
	update_cart:function(){
		jQuery('#updateCart').click(function(){
			var updateCart = BASEPARAMS.base_url + '/shop-cart';
			jConfirm('Bạn có muốn cập nhật đơn hàng không [OK]:Đồng ý [Cancel]:Bỏ qua ?', 'Xác nhận', function(r) {
				if(r){
					jQuery('#txtFormShopCart').attr('action', updateCart).submit();
				}
			});
			return true;
		});
	},
	del_one_item:function(){
		jQuery('.delOneItemCart').click(function(){
			var url = BASEPARAMS.base_url + '/del-one-item-cart';
			var pid = jQuery(this).attr('data');
			var psize = jQuery(this).attr('data-size');
			jConfirm('Bạn có muốn xóa không [OK]:Đồng ý [Cancel]:Bỏ qua ?', 'Xác nhận', function(r) {
				if(r){
					jQuery.ajax({
						type: "POST",
						url: url,
						data: "pid="+encodeURI(pid) + "&psize="+encodeURI(psize),
						success: function(data){
							if(data != ''){
								window.location.reload();
							}
						}
					});	
				}
			});
			return true;	
		});	
	},
	del_all_item:function(){
		jQuery('#dellAllCart').click(function(e){
			var url = BASEPARAMS.base_url + '/del-all-item-in-cart';
			var all = jQuery(this).attr('data');
			jConfirm('Bạn có muốn xóa không [OK]:Đồng ý [Cancel]:Bỏ qua ?', 'Xác nhận', function(r) {
				if(r){
					jQuery.ajax({
						type: "POST",
						url: url,
						data: "all="+encodeURI(all),
						success: function(data){
							if(data != ''){
								window.location.reload();
							}
						}
					});	
				}
			});
			return true;
		});	
	},
	payment_order:function(){
		jQuery('#submitPaymentOrder').click(function(){
			var valid = true;
			if(jQuery('#txtName').val() == ''){
				jQuery('#txtName').addClass('error');
				valid = false;
			}else{
				jQuery('#txtName').removeClass('error');
			}
			
			if(jQuery('#txtMobile').val() == ''){
				jQuery('#txtMobile').addClass('error');
				valid = false;
			}else{
				
				var regex = /^[0-9-+]+$/;
				var phone = jQuery('#txtMobile').val();
				if (regex.test(phone)) {
			        jQuery('#txtMobile').removeClass('error');
			    }else{
					jQuery('#txtMobile').addClass('error');	
				}
			}
			if(jQuery('#txtAddress').val() == ''){
				jQuery('#txtAddress').addClass('error');
				valid = false;
			}else{
				jQuery('#txtAddress').removeClass('error');
			}
			
			if(jQuery('#txtMessage').val() == ''){
				jQuery('#txtMessage').addClass('error');
				valid = false;
			}else{
				jQuery('#txtMessage').removeClass('error');
			}
			if(valid==false){
				return false;
			}
			return valid;
		});
	}
}

CALC_TOTAL_PRICE_VIEW = {
	format_number:function(nStr){
		nStr += '';
	    var x = nStr.split('.');
	    var x1 = x[0];
	    var x2 = x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    }
	    return x1 + x2;
	},
	calc:function(){
		jQuery("#txtNumView").change(function(){
			var num = jQuery(this).val();
			var price = jQuery('.line-price-total').attr('data-price');
			var total_price = CALC_TOTAL_PRICE_VIEW.format_number(num * price);
			jQuery("#total-price").text(total_price);
		});
		var num = jQuery("#txtNumView").val();
		var price = jQuery('.line-price-total').attr('data-price');
		var total_price = CALC_TOTAL_PRICE_VIEW.format_number(num * price);
		jQuery("#total-price").text(total_price);
	}
}

FIXED = {
	init:function(){
		var menu_site = jQuery('.fixed-header'),
			pos_menu  = menu_site.offset();
		jQuery(window).scroll(function(){
			if(jQuery(this).scrollTop() >= "110"){
				jQuery(".fixed-header").addClass('heade-fixed');
			}else if(jQuery(this).scrollTop() <= pos_menu.top){
				jQuery(".fixed-header").removeClass('heade-fixed');
			}
		});
	}
}

CLICK_SIZE_PRODUCT = {
	init:function(){
		var data_no = jQuery('.size-product-right .product-size li:nth-child(1)').attr('data-no');
		jQuery('#message-qty #suggestion .no').text(data_no);
		jQuery('.size-product-right .product-size li').each(function(){
			var data_no = jQuery(this).attr('data-no');
			var data_size = jQuery(this).attr('data-size');
			if(data_no > 0){
				jQuery(this).addClass('act');
				jQuery('input#productSize').val(data_size);
				jQuery('input#productNum').val(data_no);
				jQuery('#message-qty #suggestion .no').text(data_no);
				return false;
			}
		});
		jQuery('.size-product-right .product-size li').click(function(){
			if(jQuery(this).hasClass('disable')){
				jQuery(this).removeClass('act');
			}else{
				jQuery('.size-product-right .product-size li').removeClass('act');
				jQuery(this).addClass('act');
				var data_no = jQuery(this).attr('data-no');
				var data_size = jQuery(this).attr('data-size');
				jQuery('#message-qty #suggestion .no').text(data_no);
				jQuery('input#productSize').val(data_size);
				jQuery('input#productNum').val(data_no);
			}
		});
	}
}