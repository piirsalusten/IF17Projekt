<?php	
	$database = "if17_sten";
	require("../../config.php");
	//alustame sessiooni
	session_start();
	
	//sisselogimise funktsioon
	function signin($email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, password FROM epusers WHERE email = ?");
		$stmt->bind_param("s",$email);
		$stmt->bind_result($id, $firstnameFromDb, $lastnameFromDb, $emailFromDb, $passwordFromDb);
		$stmt->execute();
		
		
		//kontrollime kasutajat
		if($stmt->fetch()){
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb){
				$notice = "Kõik korras! Logisimegi sisse!";
				
				//salvestame sessioonimuutujad
				$_SESSION["userId"] = $id;
				$_SESSION["firstname"] = $firstnameFromDb;
				$_SESSION["lastname"] = $lastnameFromDb;
				$_SESSION["userEmail"] = $emailFromDb;
				
				//liigume pealehele
				header("Location: main.php");
				exit();	
			} else {
				$notice = "Sisestasite vale salasõna!";
			}
		} else {
			$notice = "sellist kasutajat (" .$email .") ei ole";
		}
		return $notice;
	}

	//uue kasutaja andmebaasi lisamine
	//ühendus serveriga
	function signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupPic, $signupAddress, $signupPhoneNumber, $signupEmail, $signupPassword){
		//andmebaasiühendus
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//käsk serverile
		$stmt = $mysqli->prepare("INSERT INTO epusers(firstname, lastname, birthday, gender, pic, address, phone_number,  email, password) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		//s - string ehk tekst
		//i - integer ehk täisarv
		//d - decimal, ujukomaarv
		$stmt->bind_param("sssisssss", $signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupPic, $signupAddress, $signupPhoneNumber, $signupEmail, $signupPassword);
		//stmt->execute();  - tuleb  enne vigu kontrollida
		if($stmt->execute()){
			echo "Läks väga hästi!";
		} else {
			echo "Tekkis viga: " .$stmt->error;
		}	
	}
	
	function addSale($productName, $productCategory, $productPrice, $productDesc, $target_file){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO epproducts(epusers_id, product_name, Category, Price, productDesc, pictureName) VALUES (?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("isiiss" $_SESSION["userId"], $productName, $productCategory, $productPrice, $productDesc, $target_file);
		if($stmt->execute()){
			echo "kuulutus lisati üles!";
		} else {
			echo "Tekkis viga: " .$stmt->error;
		}	
		$stmt->close();
		$mysqli->close();
	}
	
	//sisestuse kontrollimine
	function test_input($data){
		$data = trim($data); //eemaldab lõpust tühiku, tab, vms
		$data = stripslashes($data); //eemaldab "\"
		$data = htmlspecialchars($data); //eemaldab keelatud märgid
		return $data;
	}	
	
	function publicPhotos(){
		$notice = "";
		$picDir = "../thumbs/";
		$desc = "Natukene kasutatud, aga töötab!!!Natukene kasutatud, aga töötab!!!Natukene kasutatud, aga töötab!!!Natukene kasutatud, aga töötab!!!";
		$contact = "Email: Kartulipuder123@hot.ee Tel:584874594" ;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT thumbnail from vpphotos order by id DESC");
		echo $mysqli->error;
		$stmt->bind_result ($thumbnailName);
		$stmt->execute();
		
		while($stmt->fetch()){
			$notice .=  '<table style="width:50%"> <tr><td><img src="' . $picDir . '/' . $thumbnailName . '" alt="Auto"></td><td>'. $desc.'<br> <button type="button" onclick=document.getElementById("demo").innerHTML ="'.$contact.'">Näita kontaktandmeid</button><p id="demo"></p></td><td> HIND: 190€ </td></tr></table> <br>' ;
			
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	
	
	
	
	