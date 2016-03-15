<?php
if( !class_exists('HSSThumbsImageBase') ){

	    class HSSThumbsImageBase{

		private $__name = 'mod Thumbs Base';

		/**
		 *  check the folder is existed, if not make a directory and set permission is 755
		 */
		public function makeDir( $path ){
			$folders = explode ( '/',  ( $path ) );
			$tmppath =  DRUPAL_ROOT.'/uploads/images/thumbs'.'/';

			if( !file_exists($tmppath) ) {
				mkdir($tmppath, 0777, true);
			};
			for( $i = 0; $i < count ( $folders ) - 1; $i ++) {

				if (! file_exists ( $tmppath . $folders [$i] ) && ! mkdir( $tmppath . $folders [$i], 0777) ) {
					return false;
				}
				$tmppath = $tmppath . $folders [$i] . '/';
			}

			return true;
		}

		public function renderThumb( $path, $width=100, $height=100, $title='', $isThumb=true, $image_quanlity = 100, $returnPath = false, $rel=''){
			$picture_path = 'http://'.$_SERVER['HTTP_HOST'].$GLOBALS['base_path'];
			if( !preg_match("/.jpg|.jpeg|.JPEG|.JPG|.png|.gif/",strtolower($path)) ) return '&nbsp;';
			if( $isThumb ){
				if(empty($image_quanlity)){
					$image_quanlity = 100;
				}
				$imagSource = DRUPAL_ROOT.'/uploads/images/'. $path;
				if( file_exists($imagSource)  ) {
					$path =  $width."x".$height.'/'.$path;

					$thumbPath = DRUPAL_ROOT.'/uploads/images/thumbs/'. $path;

					if( !file_exists($thumbPath) ) {
						$thumb = PhpThumbFactory::create( $imagSource  );

						$thumb->setOptions( array('jpegQuality'=> $image_quanlity) );
						if( !$this->makeDir( $path ) ) {
								return '';
						}
						$thumb->adaptiveResize( $width, $height);

						$thumb->save( $thumbPath  );
						chmod($thumbPath, 0777);
					}
					$path = $picture_path.'uploads/images/thumbs/'.$path;
				}
			}
			if( $returnPath ){
				return $path;
			}
			else{
				return '<img src="'.$path.'" title="'.$title.'" alt="'.$title.'" rel="'.$rel.'"/>';
			 }
		}

		public function renderThumbResize( $path, $width=100, $height=100, $title='', $isThumb=true, $image_quanlity = 100, $returnPath = false, $id=''){
			$picture_path = 'http://'.$_SERVER['HTTP_HOST'].$GLOBALS['base_path'];
			if( !preg_match("/.jpg|.jpeg|.JPEG|.JPG|.png|.gif/",strtolower($path)) ) return '&nbsp;';
			if( $isThumb ){
				if(empty($image_quanlity)){
					$image_quanlity = 100;
				}
				$imagSource = DRUPAL_ROOT.'/uploads/images/'. $path;
				if( file_exists($imagSource)  ) {
					$path =  $width."x".$height.'/'.$path;

					$thumbPath = DRUPAL_ROOT.'/uploads/images/thumbs/'. $path;

					if( !file_exists($thumbPath) ) {
						$thumb = PhpThumbFactory::create( $imagSource  );

						$thumb->setOptions( array('jpegQuality'=> $image_quanlity) );
						if( !$this->makeDir( $path ) ) {
								return '';
						}
						$thumb->resize( $width, $height);

						$thumb->save( $thumbPath  );
					}
					$path = $picture_path.'uploads/images/thumbs/'.$path;
				}
			}
			if( $returnPath ){
				return $path;
			}
			else{
				return '<img src="'.$path.'" title="'.$title.'" alt="'.$title.'"/>';
			 }
		}

		public function renderThumbBase( $path, $width=100, $height=100, $alt='', $isThumb=true, $image_quanlity, $returnPath = false,$id= ''){
			$ThumbPrefix			= "thumb_";
			$RandomNumber 	= rand(0, 99999);
			$picture_path = 'http://'.$_SERVER['HTTP_HOST'].$GLOBALS['base_path'];
			if( !preg_match("/.jpg|.jpeg|.JPEG|.JPG|.png|.gif/",strtolower($path)) ) return '&nbsp;';
			if( $isThumb ){
				if(empty($image_quanlity)){
					$image_quanlity = 100;
				}
				$imagSource = DRUPAL_ROOT.'/uploads/images/'. $path;
				$ImageExt = substr($path, strrpos($path, '.'));
				$ImageNameFile = substr($path, 0, strrpos($path, '.'));
				$ImageExt = str_replace('.','',$ImageExt);
				$nameThumbImage = $ThumbPrefix.$ImageNameFile.'_'.$RandomNumber.'.'.$ImageExt;
				if( file_exists($imagSource)  ) {
					$paths =  $width."x".$height.'/'.$path;
					$thumbPath = DRUPAL_ROOT.'/uploads/images/thumbs/'. $paths;
					$pathsize = $picture_path.'uploads/images/'.$path;
					list($CurWidth,$CurHeight,$ImageType) = getimagesize($imagSource);
					switch(strtolower($ImageType)){
						case 1:
							$CreatedImage = imagecreatefromgif($imagSource);
							break;
						case 2:
							$CreatedImage = imagecreatefromjpeg($imagSource);
							break;
						case 3:
							$CreatedImage = imagecreatefrompng($imagSource);
							break;
						default:
							die('Unsupported File!'); //output error
					}
					if( !file_exists($thumbPath) ) {
						if( !$this->makeDir( $paths ) ) {
							return '';
						}
						$this->saveCustom( $imagSource  );
					}
					//echo $image_quanlity."--";
					$resizeImagePath = $this->resizeImage($CurWidth,$CurHeight,$width,$height,$thumbPath,$CreatedImage,$image_quanlity);
					if($resizeImagePath){
						$path = $picture_path.'uploads/images/thumbs/'.$paths;
					}else{
						die('Resize Error'); //output error
					}

				}
			}
			if( $returnPath ){
				return $path;
			}
			else{
				return '<img src="'.$path.'" alt="'.$alt.'"/>';
			 }
		}

		public function renderThumbBaseNormal( $path, $width=100, $height=100, $alt='', $isThumb=true, $image_quanlity, $returnPath = false,$id= ''){
			$ThumbPrefix			= "thumb_";
			$RandomNumber 	= rand(0, 99999);
			$picture_path = 'http://'.$_SERVER['HTTP_HOST'].$GLOBALS['base_path'];
			if( !preg_match("/.jpg|.jpeg|.JPEG|.JPG|.png|.gif/",strtolower($path)) ) return '&nbsp;';
			if( $isThumb ){
				if(empty($image_quanlity)){
					$image_quanlity = 100;
				}
				$imagSource = DRUPAL_ROOT.'/uploads/images/'. $path;
				$ImageExt = substr($path, strrpos($path, '.'));
				$ImageNameFile = substr($path, 0, strrpos($path, '.'));
				$ImageExt = str_replace('.','',$ImageExt);
				$nameThumbImage = $ThumbPrefix.$ImageNameFile.'_'.$RandomNumber.'.'.$ImageExt;
				if( file_exists($imagSource)  ) {
					$paths =  $width."x".$height.'/'.$path;
					$thumbPath = DRUPAL_ROOT.'/uploads/images/thumbs/'. $paths;
					$pathsize = $picture_path.'uploads/images/'.$path;
					list($CurWidth,$CurHeight,$ImageType) = getimagesize($imagSource);
					switch(strtolower($ImageType)){
						case 1:
							$CreatedImage = imagecreatefromgif($imagSource);
							break;
						case 2:
							$CreatedImage = imagecreatefromjpeg($imagSource);
							break;
						case 3:
							$CreatedImage = imagecreatefrompng($imagSource);
							break;
						default:
							die('Unsupported File!'); //output error
					}
					if( !file_exists($thumbPath) ) {
						if( !$this->makeDir( $paths ) ) {
							return '';
						}
						$this->saveCustom( $imagSource  );
					}
					//echo $image_quanlity."--";
					$resizeImagePath = $this->resizeImage($CurWidth,$CurHeight,$width,$height,$thumbPath,$CreatedImage,$image_quanlity);
					if($resizeImagePath){
						$path = $picture_path.'uploads/images/thumbs/'.$paths;
					}else{
						die('Resize Error'); //output error
					}

				}
			}
			if( $returnPath ){
				return $path;
			}
			else{
				return '<img src="'.$path.'" alt="'.$alt.'"/>';
			 }
		}


		public function saveCustom ($fileName, $format = null){
			@chmod($fileName, 0777);
			return true;
		}

		public function resizeImage($CurWidth,$CurHeight,$MaxWidth,$MaxHeight,$DestFolder,$SrcImage,$image_quanlity){


			$ImageScale      	= min($MaxWidth/$CurWidth, $MaxHeight/$CurHeight);
			$NewWidth  			= ceil($ImageScale*$CurWidth);
			$NewHeight 			= ceil($ImageScale*$CurHeight);
			if (($CurWidth<=$MaxWidth) && ($CurHeight<=$MaxHeight))
			{
				$NewWidth=$CurWidth;
				$NewHeight 	=$CurHeight;
			}
			$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
			if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
			{
				// copy file
				@chmod($DestFolder, 0777);
				if(empty($image_quanlity)){
					$image_quanlity = 100;
				}
				//echo $image_quanlity;
				if(imagejpeg($NewCanves,$DestFolder,$image_quanlity))
				{
					imagedestroy($NewCanves);
					//die();
					return true;
				}
			}
			return true;
		}

		public function renderThumbCropCenter( $path, $width=100, $height=100, $title='', $isThumb=true, $image_quanlity = 100, $returnPath = false){
			$picture_path = 'http://'.$_SERVER['HTTP_HOST'].$GLOBALS['base_path'];
			if( !preg_match("/.jpg|.jpeg|.JPEG|.JPG|.png|.gif/",strtolower($path)) ) return '&nbsp;';
			if( $isThumb ){
				if(empty($image_quanlity)){
					$image_quanlity = 100;
				}
				$imagSource = DRUPAL_ROOT.'/uploads/images/'. $path;
				if( file_exists($imagSource)  ) {
					$path =  $width."x".$height.'/'.$path;

					$thumbPath = DRUPAL_ROOT.'/uploads/images/thumbs/'. $path;
					if( !file_exists($thumbPath) ) {
						$thumb = PhpThumbFactory::create( $imagSource  );

						$thumb->setOptions( array('jpegQuality'=> $image_quanlity) );
						if( !$this->makeDir( $path ) ) {
								return '';
						}
						$thumb->cropFromCenter( $width, $height);

						$thumb->save( $thumbPath  );
					}
					$path = $picture_path.'uploads/images/thumbs/'.$path;
				}
			}
			if( $returnPath ){
				return $path;
			}
			else{
				return '<img src="'.$path.'" alt="'.$title.'"/>';
			 }
		}

	}
}
?>
