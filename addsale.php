<?php
	require("../../config.php");
	require("functions.php");
	require("classes/Photoupload.class.php");
	$notice = "";

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
	
	
	$target_dir = "kuulutuspics/";
	$thumbs_dir = "thumbnails/";
	$target_file = "";
	$thumb_file = "";
	$uploadOk = 1;
	$imageFileType = "";
	$maxWidth = 550;
	$thumbsize = 100;
	$maxHeight = 400;
	$marginVer = 10;
	$marginHor = 10;
	
	
	
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
	
	if (isset($_POST["Categories"]) ){
		$productCategory = $_POST["Categories"];
	}
	
	if(!empty($_FILES["fileToUpload"]["name"])){
		$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
		$timeStamp = microtime(1) * 10000;
		$target_file = "kuulutus_" .$timeStamp ."." .$imageFileType;
		$thumb_file = "kuulutus_" .$timeStamp .".jpg";
		
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
			//PILDI LAADIMINE CLASSI ABIL
			$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
			$myPhoto->resizePhoto($maxWidth, $maxHeight);
			$myPhoto->addWatermark($marginHor, $marginVer);
			$notice .= $myPhoto->savePhoto($target_dir, $target_file);
			$notice .= $myPhoto->createThumbnail($thumbs_dir, $thumb_file, $thumbsize, $thumbsize);
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
					
				</div>

				<div style="clear:both"></div>

			<ul id="menu">
					<li><a href="index.php"><pealkiri>AVALEHT</pealkiri></a></li>
					<li><a href=""><pealkiri>KUULUTUSED</pealkiri></a>
				<ul>
				
					<li><a href="electronics.php"><pealkiri>ELEKTROONIKA</pealkiri></a></li>
					<li><a href="clothes.php"><pealkiri>RIIDEESEMED</pealkiri></a></li>
					<li><a href="furniture.php"><pealkiri>MÖÖBEL</pealkiri></a></li>
					<li><a href="others.php"><pealkiri>MUU</pealkiri></a></li>
				
				</ul>
					<li><a href="questions.php"><pealkiri>REEGLID</pealkiri></a></li>
					<li><a href="contact.php"><pealkiri>KONTAKT</pealkiri></a></li>
				
				<div style="clear:both"></div>

				</div>

			<div id="content">
				<h2>Lisa kuulutus</h2>
				<br>
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
				<label>Toote nimi: </label>
				<input name="productName" type="text" value="<?php echo $productName; ?>">
				<span> <?php echo $productNameError ?><span>
				<br>
				<label>Vali toote kategooria:</label>
				
				<select name="Categories">
					<option value="1">Elektroonika</option>
					<option value="2">Riideesemed</option>
					<option value="3">Mööbel</option>
					<option value="4">Muu</option>
				</select>
				<span> <?php echo $productCategoryError ?><span>
				<br><br>
				<label>toote hind: </label>
				<input name="productPrice" type="text" value="<?php echo $productPrice; ?>">
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
					
					<?php if( isset($_SESSION['userId']) && !empty($_SESSION['userId']) )
						{
					?>
					<p>Tere, <?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?></p>
					
					<a href="?logout=1">Logi välja!</a>
					<?php }else{ ?>
					<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<label>Kasutajanimi (E-post): </label>
					<br>
					<input name="loginEmail" type="email" value="<?php echo $loginEmail; ?>">
					<br><br>
					<label>Parool: </label>
					<br>
					<input name="loginPassword" placeholder="Salasõna" type="password">
					<br><br>
					<input name="signinButton" type="submit" value="Logi sisse"> <span> <?php echo $notice ?><span>
					</form>
					<a href="register.php">Registreeri!</a>
					<?php } ?>
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