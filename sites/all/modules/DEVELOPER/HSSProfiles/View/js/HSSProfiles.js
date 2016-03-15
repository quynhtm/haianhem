jQuery(document).ready(function($){
	REGISTER.init();
	REGISTER.change_info();
	REGISTER.change_pass();
});
REGISTER = {
	init:function(){
		jQuery('#submitRegister').click(function(){
			var valid = true;
			
			if(jQuery('#txtFullName').val() == ''){
				jQuery('#txtFullName').addClass('error');
				valid = false;
			}else{
				jQuery('#txtFullName').removeClass('error');
			}

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
			
			if(jQuery('#txtPass').val() == '' || jQuery('#txtPass').val().length < 6){
				jQuery('#txtPass').addClass('error');
				valid = false;
			}else{
				jQuery('#txtPass').removeClass('error');
			}
			
			if(jQuery('#txtPass').val() != jQuery('#txtRePass').val()){
				jQuery('#txtRePass').addClass('error');
				valid = false;
			}else{
				jQuery('#txtRePass').removeClass('error');
			}	
		
			if(valid == false){
				return false;
			}
			return valid;
		});
	},
	change_info:function(){
		jQuery('#submitChangeInfo').click(function(){
			var valid = true;
			
			if(jQuery('#txtFullName').val() == ''){
				jQuery('#txtFullName').addClass('error');
				valid = false;
			}else{
				jQuery('#txtFullName').removeClass('error');
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

			if(jQuery('#txtEmail').val() == ''){
				jQuery('#txtEmail').addClass('error');
				valid = false;
			}else{
				jQuery('#txtEmail').removeClass('error');
			}
			var email =jQuery('#txtEmail').val();
			if(!email.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/)){
				jQuery('#txtEmail').addClass('error');
				valid = false;
			}else{
				jQuery('#txtEmail').removeClass('error');
			}

			if(jQuery('#txtAddress').val() == ''){
				jQuery('#txtAddress').addClass('error');
				valid = false;
			}else{
				jQuery('#txtAddress').removeClass('error');
			}
		
			if(valid == false){
				return false;
			}
			return valid;
		});
	},
	change_pass:function(){
		jQuery('#submitChangePass').click(function(){
			var valid = true;
			
			if(jQuery('#txtName').val() == ''){
				jQuery('#txtName').addClass('error');
				valid = false;
			}else{
				jQuery('#txtName').removeClass('error');
			}
			

			if(jQuery('#txtPass').val() == '' || jQuery('#txtPass').val().length < 6){
				jQuery('#txtPass').addClass('error');
				valid = false;
			}else{
				jQuery('#txtPass').removeClass('error');
			}
			
			if(jQuery('#txtPass').val() != jQuery('#txtRePass').val()){
				jQuery('#txtRePass').addClass('error');
				valid = false;
			}else{
				jQuery('#txtRePass').removeClass('error');
			}	
		
			if(valid == false){
				return false;
			}
			return valid;
		});
	},
}