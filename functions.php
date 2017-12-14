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
				header("Location: index.php");
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
		$stmt->bind_param("sssisssss", $signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupPic, $signupAddress, $signupPhoneNumber, $signupEmail, $signupPassword);
		if($stmt->execute()){
			header("Location: index.php");
			exit();
		} else {
			echo "Tekkis viga: " .$stmt->error;
		}	
	}
	
	function addSale($productName, $productCategory, $productPrice, $productDesc, $target_file){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO epproducts(epusers_id, product_name, Category, Price, productDesc, pictureName) VALUES (?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("isiiss", $_SESSION["userId"], $productName, $productCategory, $productPrice, $productDesc, $target_file);
		if($stmt->execute()){
			header("Location: index.php");
			exit();
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
	
	function latestItems(){
		$notice = "";
		$picDir = "thumbnails";
		$contact = "Email: Kartulipuder123@hot.ee Tel:584874594" ;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, pictureName, product_name, productDesc, Price from epproducts WHERE sold = 0 ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_result ($id,$pictureName, $productName, $productDesc, $Price);
		$stmt->execute();
		
		while($stmt->fetch()){
			$notice .=  '<tr><td><img src="' . $picDir . '/' . $pictureName  . '" alt="Auto"></td><td><a href="Item.php?id=' .$id . '">'. $productName .'</a> </td><td><h3>'. $Price .'€</h3></td></tr>' ;
			
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function myItems(){
		$notice = "";
		$picDir = "thumbnails";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, pictureName, product_name, Price from epproducts WHERE epusers_id = ? AND sold = 0 ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result ($id ,$pictureName, $productName,  $Price);
		$stmt->execute();
		
		while($stmt->fetch()){
			$notice .=  '<tr><td><img src="' . $picDir . '/' . $pictureName . '" alt="Auto"></td><td><a href="Item.php?id=' .$id . '">'. $productName .'</a> </td><td><h3>'. $Price .'€</h3></td></tr>' ;
			
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
function getItem($itemId, $userId){
		$notice = "";
		$picDir = "kuulutuspics";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli -> prepare("Select epproducts.id, epproducts.epusers_id, epproducts.pictureName, epproducts.product_name, epproducts.productDesc, epproducts.price, epusers.firstname, epusers.email ,epusers.phone_number from epproducts
inner join epusers ON epproducts.epusers_id = epusers.id
where epproducts.id = ?");
		echo $mysqli->error;
		$stmt->bind_param("i", $itemId);
		$stmt->bind_result($id, $epusers_id, $pictureName, $productName, $productDesc, $Price, $firstname, $email,$tel);
		$stmt->execute();
		if($stmt->fetch()){$notice .=  '<h2>'. $productName .'</h2><img src="' . $picDir . '/' . $pictureName . '" alt="Auto"><br>'. $productDesc . '<br><br>'. $firstname .'<br>'.$email.'<br>'. $tel .'<h3>'. $Price .'€</h3>';
		if($userId == $epusers_id){
			$notice .= '<p><a href="?id=' .$id.'; &delete=1">Kustuta see kuulutus</a></p>';
		}
		} else {
			$stmt->close();
		    $mysqli->close();
			header("Location: index.php");
			exit();
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function deleteItem($id){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("update epproducts set sold = 1 where id=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $id);
		$stmt->execute();
		
		$stmt->close();
		$mysqli->close();
	}
	
	
	function latestElectronics(){
		$notice = "";
		$picDir = "thumbnails";
		$contact = "Email: Kartulipuder123@hot.ee Tel:584874594" ;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, pictureName, product_name, productDesc, Price from epproducts WHERE sold = 0 AND Category = 1 ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_result ($id,$pictureName, $productName, $productDesc, $Price);
		$stmt->execute();
		
		while($stmt->fetch()){
			$notice .=  '<tr><td><img src="' . $picDir . '/' . $pictureName  . '" alt="Auto"></td><td><a href="Item.php?id=' .$id . '">'. $productName .'</a> </td><td><h3>'. $Price .'€</h3></td></tr>' ;
			
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
		function latestClothes(){
		$notice = "";
		$picDir = "thumbnails";
		$contact = "Email: Kartulipuder123@hot.ee Tel:584874594" ;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, pictureName, product_name, productDesc, Price from epproducts WHERE sold = 0 AND Category = 2 ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_result ($id,$pictureName, $productName, $productDesc, $Price);
		$stmt->execute();
		
		while($stmt->fetch()){
			$notice .=  '<tr><td><img src="' . $picDir . '/' . $pictureName  . '" alt="Auto"></td><td><a href="Item.php?id=' .$id . '">'. $productName .'</a> </td><td><h3>'. $Price .'€</h3></td></tr>' ;
			
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function latestFurniture(){
		$notice = "";
		$picDir = "thumbnails";
		$contact = "Email: Kartulipuder123@hot.ee Tel:584874594" ;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, pictureName, product_name, productDesc, Price from epproducts WHERE sold = 0 AND Category = 3 ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_result ($id,$pictureName, $productName, $productDesc, $Price);
		$stmt->execute();
		
		while($stmt->fetch()){
			$notice .=  '<tr><td><img src="' . $picDir . '/' . $pictureName  . '" alt="Auto"></td><td><a href="Item.php?id=' .$id . '">'. $productName .'</a> </td><td><h3>'. $Price .'€</h3></td></tr>' ;
			
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function latestOthers(){
		$notice = "";
		$picDir = "thumbnails";
		$contact = "Email: Kartulipuder123@hot.ee Tel:584874594" ;
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, pictureName, product_name, productDesc, Price from epproducts WHERE sold = 0 AND Category = 4 ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_result ($id,$pictureName, $productName, $productDesc, $Price);
		$stmt->execute();
		
		while($stmt->fetch()){
			$notice .=  '<tr><td><img src="' . $picDir . '/' . $pictureName  . '" alt="Auto"></td><td><a href="Item.php?id=' .$id . '">'. $productName .'</a> </td><td><h3>'. $Price .'€</h3></td></tr>' ;
			
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}