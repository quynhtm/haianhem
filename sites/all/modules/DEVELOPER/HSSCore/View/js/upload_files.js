/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/

jQuery(document).ready(function(){
	try{
		var url_upload = BASEPARAMS.base_url+'/admincp/product/uploadFile';
		UPLOAD_FILE.uploadhtml5(url_upload);
		UPLOAD_FILE.edit();
	}catch (e){
    }
});

/*upload with html5*/
UPLOAD_FILE = {
    uploadhtml5:function(url){
        jQuery("#txtImagesPost").html5Uploader({
            name: "txtImagesPost",
            onServerLoadStart:function(e,file){
                var aType = file.type.split('/');
				if(aType[0] =='image'){
                    var id = UPLOAD_FILE.slugify(file.name);
					jQuery('#load_file_ajax').append('<div class = "rowprocess" id = "'+id+'" ></div>');
					jQuery('#'+id+' p').css('width','10%');
                }
            },
			onServerProgress:function(e,file){
				if(e.lengthComputable){
					var id = UPLOAD_FILE.slugify(file.name);
					var percentComplete=(e.loaded/e.total)*100;
					jQuery('#'+id+' p').css('width',percentComplete+'%');
				}
			},

			onServerLoad:function(e,file){
				var id = UPLOAD_FILE.slugify(file.name);
				var link_duplicate = jQuery('#'+id+' p img').attr('alt');
				jQuery('input[value="'+link_duplicate+'"]').remove();
				jQuery('#'+id+' p').remove();
				//remove duplicatefile
				UPLOAD_FILE.ajax_delete(link_duplicate);
			},

			onSuccess:function(e, file, data){
				var id = UPLOAD_FILE.slugify(file.name);
				jQuery('#'+id).append('<input type="hidden" name="link_file[]" value="'+data.src+'" >');
				var data_link = jQuery('#'+id+' input').val();
				var html = '<div class = "rowprocess" id = "'+id+'" >'
					+'<p style="width:75px!important">'
						+'<img src="'+BASEPARAMS.base_url+'/uploads/images/product/'+data_link+'" alt="'+data_link+'" width="50" height="50"/>'
						+'<a id="del'+id+'"  href="javascript:void(0)" class="textr delete ">Xóa ảnh</a>'
					+'</p>'
					+'</div>';
				jQuery('#load_file_ajax').append(html);
				UPLOAD_FILE.dellink(id,data.src);
			},
			postUrl: url,
       });
    },

    slugify:function (text){
        text=text.replace(/[^-a-zA-Z0-9,&\s]+/ig,'');
        text=text.replace(/-/gi,"_");
        text=text.replace(/\s/gi,"-");
        return text;
    },
	//delete click
	dellink:function(id,link){
	    jQuery('#del'+id).click(function(){
			UPLOAD_FILE.ajax_delete(link);
			jQuery('div#'+id).remove();
            return false;
        });
    },
	//delete focus
	ajax_delete:function(link){
		if(typeof link != 'undefined'){
			jQuery.ajax({
				type: "POST",
				url: BASEPARAMS.base_url+'/admincp/product/deleteFile',
				data: "link=" + encodeURI(link),
				success: function(data){
				}
			});
		}
		return false;
	},
	//remove file in db
	edit:function(){
		 jQuery('a.del_file').click(function(){
			var id_file = jQuery(this).attr('rel');
			var link_file = jQuery(this).attr('title');
			jQuery('div#id_file'+id_file).remove();
			UPLOAD_FILE.ajax_delete(link_file);
			//delete file in db
			jQuery.ajax({
					type: "POST",
					url: BASEPARAMS.base_url+'/admincp/product/deleteDBFile',
					data: "id_file=" + encodeURI(id_file) + "&link=" + encodeURI(link_file),
					success: function(data){}
				});
            return false;
        });
	},
};