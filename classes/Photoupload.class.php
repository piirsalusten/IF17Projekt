<?php
	class Photoupload {
		
		private $tempName;
		private $imageFileType;
		private $myTempImage;
		private $myImage;
		public $exifToImage;
		
		function __construct($name, $type){
			$this->tempName = $name;
			$this->imageFileType = $type;
		}
		
		private function createImage(){
			//lähtudes failitüübist, loome pildiobjekti
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			if($this->imageFileType == "png"){
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
			if($this->imageFileType == "gif"){
				$this->myTempImage = imagecreatefromgif($this->tempName);
			}
		}
		

		public function resizePhoto($width, $height){
			$this->createImage();
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			
			$sizeRatio = 1;
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeRatio = $imageHeight / $height;
			}
			//parameetrid 0 ja 0 on originaalist võetava osa ülemise vasaku nurga koordinaadid
			$this->myImage = $this->resize_image($this->myTempImage, $imageWidth, $imageHeight, 0, 0, round($imageWidth / $sizeRatio), round($imageHeight / $sizeRatio));
		}
		
		
		public function addWatermark($marginHor, $marginVer){
			//lisame vesimärgi
			$stamp = imagecreatefrompng("images/tlu_watermark.png");
			$stampWidth = imagesx($stamp);
			$stampHeight = imagesy($stamp);
			$stampPosX = imagesx($this->myImage) - $stampWidth - $marginHor;
			$stampPosY = imagesy($this->myImage) - $stampHeight - $marginVer;
			imagecopy($this->myImage, $stamp, $stampPosX, $stampPosY, 0, 0, $stampWidth, $stampHeight);
		}	
		
		public function savePhoto($directory, $fileName){
			//salvestame pildifaili
			$target_file = $directory .$fileName;
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				if(imagejpeg($this->myImage, $target_file, 90)){
					$notice = "Faili salvestamine õnnestus! ";
				} else {
					$notice = "Faili salvestamine ebaõnnestus! ";
				}
			}
			if($this->imageFileType == "png"){
				if(imagepng($this->myImage, $target_file, 6)){
					$notice = "Faili salvestamine õnnestus! ";
				} else {
					$notice = "Faili salvestamine ebaõnnestus! ";
				}
			}
			if($this->imageFileType == "gif"){
				if(imagegif($this->myImage, $target_file)){
					$notice = "Faili salvestamine õnnestus! ";
				} else {
					$notice = "Faili salvestamine ebaõnnestus! ";
				}
			}
			return $notice;
		}
		
		
		public function createThumbnail($directory, $filename, $width, $height){
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			
			$sizeRatio = 1;
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeRatio = $imageHeight / $height;
			}
			//teen kindlaks, kust tuleb ruudu kujuliseks kärpida
			if($imageWidth > $imageHeight){
				$origX = round(($imageWidth - $imageHeight) / 2);
				$origY = 0;
				$cutSize = $imageHeight;
			} else {
				$origX = 0;
				$origY = round(($imageHeight - $imageWidth) / 2);
				$cutSize = $imageWidth;
			}
			//parameetrid 0 ja 0 on originaalist võetava osa ülemise vasaku nurga koordinaadid
			$myThumbnail = $this->resize_image($this->myTempImage, $imageWidth, $imageHeight, $origX, $origY, $width, $height);
			$target_file = $directory .$filename;
			//Thumbnail on igal juhul jpg
			if(imagejpeg($myThumbnail, $target_file, 90)){
				$notice = "Pisipildi salvestamine õnnestus! ";
			} else {
				$notice = "Pisipildi salvestamine ebaõnnestus! ";
			}

		}
		
		public function clearImages(){
			imagedestroy($this->myTempImage);
			imagedestroy($this->myImage);
		}
		
	} // class'i lõpp
?>