<?php
	require("../../config.php");
	require("functions.php");
	require("classes/Photoupload.class.php");
	$notice = "";

	//Sisselogimise kontroll(tuleb muuta veel leht kuhu tagasi viib)
	if(!isset($_SESSION["userId"])){
		header("Location: index.php");
		exit();
	}

	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: index.php");
		exit();
	}
	$productName = "";
	$productNameError = "";
	$productDesc = "";
	$productDescError = "";
	$productPrice = "";
	$productPriceError = "";
	$productCategory = "";
	$productCategoryError = "";
	$fileToUploadError = "";
	$productUserId = "";
	$categoryError = "";
	
	
	//pildi värgid-särgid
	$target_dir = "kuulutuspics/";
	$target_file = "";
	$uploadOk = 1;
	$maxWidth = 600;
	$maxHeight = 400;
	$marginHor = 10; //vesimärgi kaugus servast
	$marginVer = 10;
	
	$id = 1;
	$_SESSION["userId"] = $id;
	
	if(isset ($_POST["submit"])){
	
	if (isset ($_POST["productName"])){
		if (empty ($_POST["productName"])){
			$productNameError ="Toote nime lisamine on kohustuslik!";
		} else {
			$productName = test_input($_POST["productName"]);
		}
	}
	
	if (isset ($_POST["productDesc"])){
		if (empty ($_POST["productDesc"])){
			$productDescError ="Toote kirjeldus on kohustuslik!";
		} else {
			$productDesc = test_input($_POST["productDesc"]);
		}
	}
	
	if (isset ($_POST["productPrice"])){
		if (empty ($_POST["productPrice"])){
			$productPriceError ="Toote kirjeldus on kohustuslik!";
		} else {
			$productPrice = test_input($_POST["productPrice"]);
		}
	}
	
	if (isset($_POST["categories"]) && !empty($_POST["categories"])){
			$productCategory = intval($_POST["categories"]);
			} else {
				$productCategoryError = "Toote kategooria valimine on kohustuslik!";
			}
	
	// A L G N E   P I L D I  Ü L E S L A A D I M I N E
	/*if(!empty($_FILES["fileToUpload"]["name"])){
		$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
		$timeStamp = microtime(1) *10000;
		$target_file = $target_dir . "kuulutus_" .$timeStamp ."." .$imageFileType;
	} else {
		$fileToUploadError = "Pilt peab olema valitud!";
	}*/
	
	//U U S   K U I D   I L M S E L T    K A T K I N E   Ü L E S L A A D I M I N E
if(!empty($_FILES["fileToUpload"]["name"])){
	$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
	$timeStamp = microtime(1) * 10000;
	$target_file = "hmv_" .$timeStamp ."." .$imageFileType;
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if($check !== false) {
		$notice .= "Fail on pilt - " . $check["mime"] . ". ";
		$uploadOk = 1;
	} else {
		$notice .= "See pole pildifail. ";
		$uploadOk = 0;
	}
	if ($_FILES["fileToUpload"]["size"] > 1000000) {
		$notice .= "Pilt on liiga suur! ";
		$uploadOk = 0;
	}
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
		$notice .= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
		$uploadOk = 0;
	}
	if ($uploadOk == 0) {
		$notice .= "Vabandust, pilti ei laetud üles! ";
	} else {		
		//pildi laadimine classi abil
		$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
		$myPhoto->resizePhoto($maxWidth, $maxHeight);
		$myPhoto->addWatermark("/images/tlu_watermark.png", $marginHor, $marginVer);
		$notice = $myPhoto->savePhoto($target_dir, $target_file);
		$myPhoto->clearImages();
		unset($myPhoto);
	} 
}else{
	$notice = "Palun valige kõigepealt pildifail!";
}
	
	# Uue kuulutuse lisamine andmebaasi
	if (empty($productNameError) and empty($productDescError) and empty ($productPriceError) 
	and empty ($productCategoryError) and empty ($fileToUploadError)){
		echo "Hakkan andmeid salvestama!";
		addSale($productName, $productCategory, $productPrice, $productDesc, $target_file);
	}
	
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>tlushop.ee</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" href="tlu_watermark.png">
</head>
<body>

	<div id="main">

		<div class="container">

			<div id="header">
				<div id="logo">
					<h1>Logo</h1>
				</div>

				<div style="clear:both"></div>

				<ul id="menu">
					<li><a href="#">Avaleht</a></li>
					<li><a href="#">Tere</a></li>
					<li><a href="#">Tore</a></li>
					<li><a href="#">Kontakt</a></li>
				</ul>
				<div style="clear:both"></div>

			</div>

			<div id="content">
				<h2>Lisa kuulutus</h2>
				<br>
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
				<label>Toote nimi: </label>
				<input name="tooteNimi type="text" value="<?php echo $productName; ?>">
				<span> <?php echo $productNameError ?><span>
				<br>
				<label>Vali toote kategooria:</label>
				
				<select name="Categories">
					<option value="1">Elektroonika</option>
					<option value="2">Riideesemed</option>
					<option value="3">Mööbel</option>
					<option value="4">Muu</option>
				</select>
				<span> <?php echo $categoryError ?><span>
				<br><br>
				<label>toote hind: </label>
				<input name="tooteHind type="text" value="<?php echo $productPrice; ?>">
				<span> <?php echo $productPriceError ?><span>
				<br><br>
				<label>Kuulutuse kirjeldus: </label>
				<br>
				<textarea name="productDesc" rows="5" cols="40"><?php echo $productDesc; ?></textarea>
				<span> <?php echo $productDescError ?><span>
				<br><br>
				<label>Valige pilt tootest:</label>
				<input type="file" name="fileToUpload" id="fileToUpload">
				<span> <?php echo $fileToUploadError ?><span>
				<br><br>
				<input type="submit" value="Lae üles" name="submit">
				</div>
		
			</div>

			<div id="sidebar">
				<div id="feeds">
					<h3>Logi sisse/registreeri</h3>	
					<p>lolwhat teretore</p>
				</div>

			
				</div>

		
			</div>
			<div style="clear:both"></div>

		</div>

	</div>

	<div id="footer">
		<div class="container">
			<p>Copyright &copy; 2017 tlushop.ee <br>
				All Right Reserved
			</p>
			
		</div>
	</div>


	
</body>
</html>