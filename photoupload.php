<?php
	require("functions.php");
	require("classes/Photoupload.class.php");
	$notice = "";
	
	
	//kui pole sisseloginud, siis sisselogimise lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//kui logib välja
	if (isset($_GET["logout"])){
		//lõpetame sessiooni
		session_destroy();
		header("Location: login.php");
	}

	
	
	//Algab foto laadimise osa
	$target_dir = "../../pics/";
	$target_file = "";
	$uploadOk = 1;
	$maxWidth = 600;
	$maxHeight = 400;
	$marginHor = 10; //vesimärgi kaugus servast
	$marginVer = 10;
	
	//Kas vajutati SUBMIT nuppu
	if(isset($_POST["submit"])) {
		//kas fail on valitud
		if(!empty($_FILES["fileToUpload"]["name"])){
			
			//fikseerin faili nimelaiendi
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			
			//ajatempel
			$timeStamp = microtime(1) * 10000;
			
			//fikseerin nime
			//$target_file = $target_dir . pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .$timeStamp ."." .$imageFileType;
			$target_file = "hmv_" .$timeStamp ."." .$imageFileType;
			
			//Kas on pildi failitüüp
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$notice .= "Fail on pilt - " . $check["mime"] . ". ";
				$uploadOk = 1;
			} else {
				$notice .= "See pole pildifail. ";
				$uploadOk = 0;
			}
			
			//Piirame faili suuruse
			if ($_FILES["fileToUpload"]["size"] > 1000000) {
				$notice .= "Pilt on liiga suur! ";
				$uploadOk = 0;
			}
			
			//Piirame failitüüpe
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				$notice .= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
				$uploadOk = 0;
			}
			
			//Kas saab laadida?
			if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
			} else {		
				
				//pildi laadimine classi abil
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->resizePhoto($maxWidth, $maxHeight);
				$myPhoto->addWatermark("../../graphics/hmv_logo.png", $marginHor, $marginVer);
				$myPhoto->addTextWatermark("Heade mõtete veeb");
				$notice = $myPhoto->savePhoto($target_dir, $target_file);
				$myPhoto->clearImages();
				//$myPhoto->saveOriginal($target_dir, $target_file);
				unset($myPhoto);
				
			
				} //saab salvestada lõppeb
		}else{
			$notice = "Palun valige kõigepealt pildifail!";
			
		}
	}
?>

