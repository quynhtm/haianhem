function refreshCaptcha(){
	var img = document.images['img_code'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.round(1000*Math.random());
}