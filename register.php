<?php
	require("../../config.php");
	require("functions.php");
	#echo $serverHost;

	
	$signupFirstName = "";
	$signupFamilyName = "";
	$signupEmail = "";
	$signupPic = "";
	$signupAddress = "";
	$signupPhoneNumber = "";
	$signupGender = "";
	$signupBirthDay = null;
	$signupBirthMonth = null;
	$signupBirthYear = null;
	$signupBirthDate = null;
	$gender = "";
	$notice= "";
	
	# Vigade muutujad
	$signupFirstNameError = "";
	$signupFamilyNameError = "";
	$signupBirthDayError = "";
	$signupGenderError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$signupPicError = "";
	$signupAddressError = "";
	$signupPhoneNumberError = "";
	
	
	#Kas luuakse uut kasutajat, vajutati nuppu?
	if(isset ($_POST["signupButton"])){
	
	//kontrollime, kas kirjutati eesnimi
	if (isset ($_POST["signupFirstName"])){
		if (empty ($_POST["signupFirstName"])){
			$signupFirstNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFirstName = test_input($_POST["signupFirstName"]);
		}
	}
	
	//kontrollime, kas kirjutati perekonnanimi
	if (isset ($_POST["signupFamilyName"])){
		if (empty ($_POST["signupFamilyName"])){
			$signupFamilyNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFamilyName = test_input($_POST["signupFamilyName"]);
		}
	}

	#Kas päev on sisestatud
	if (isset ($_POST["signupBirthDay"])){
		$signupBirthDay = $_POST["signupBirthDay"];
		echo $signupBirthDay;
	}
	#kas kuu on sisestatud
	if (isset ($_POST["signupBirthMonth"])){
		$signupBirthMonth = $_POST["signupBirthMonth"];
		echo $signupBirthMonth;
	}
	#kas aasta on sisestatud?
	if (isset ($_POST["signupBirthYear"])){
		$signupBirthYear = $_POST["signupBirthYear"];
		echo $signupBirthYear;
	}
	
	# Kontrollime, kas sisestatud kuuppäev on valiidne?
	if (isset ($_POST["signupBirthDay"]) and isset ($_POST["signupBirthMonth"]) and isset ($_POST["signupBirthYear"])){
	if (checkdate(intval($_POST["signupBirthMonth"]), intval ($_POST["signupBirthDay"]), intval(["signupBirthDay"]))){
		$birthDate = date_create($_POST["signupBirthMonth"] ."/" . $_POST["signupBirthDay"] ."/" . $_POST["signupBirthYear"]);
		$signupBirthDate = date_format($birthDate, "Y-m-d");
		echo $signupBirthDate;
		
		} else {
			$signupBirthDayError = "Sünnikuupäev pole valiidne!";
		}
	} else {
		$signupBirthDayError = "Kuupäev pole sisestatud!";
	}
	
	//Kontrollime, kas isikukood on sisestatud?
	if (isset ($_POST["signupPic"])){
		if (empty ($_POST["signupPic"])){
			$signupPicError ="NB! Väli on kohustuslik!";
		} else {
			$signupPic = test_input($_POST["signupPic"]);
		}
	}
	
	//Kontrollime, kas aadress on sisestatud?
	if (isset ($_POST["signupAddress"])){
		if (empty ($_POST["signupAddress"])){
			$signupAddressError ="NB! Väli on kohustuslik!";
		} else {
			$signupAddress = test_input($_POST["signupAddress"]);
		}
	}
	
	//Kontrollime, kas telefoninumber on sisestatud?
	if (isset ($_POST["signupPhoneNumber"])){
		if (empty ($_POST["signupPhoneNumber"])){
			$signupPhoneNumberError ="NB! Väli on kohustuslik!";
		} else {
			$signupPhoneNumber = test_input($_POST["signupPhoneNumber"]);
		}
	}
	
	//kontrollime, kas kirjutati kasutajanimeks email
	if (isset ($_POST["signupEmail"])){
		if (empty ($_POST["signupEmail"])){
			$signupEmailError ="NB! Väli on kohustuslik!";
		} else {
			$signupEmail = test_input($_POST["signupEmail"]);
						
			$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL);
			$signupEmail = filter_var($signupEmail, FILTER_VALIDATE_EMAIL);
		}
	}
	
	if (isset ($_POST["signupPassword"])){
		if (empty ($_POST["signupPassword"])){
			$signupPasswordError = "NB! Väli on kohustuslik!";
		} else {
			//polnud tühi
			if (strlen($_POST["signupPassword"]) < 8){
				$signupPasswordError = "NB! Liiga lühike salasõna, vaja vähemalt 8 tähemärki!";
			}
		}
	}
	
	if (isset($_POST["gender"]) && !empty($_POST["gender"])){ //kui on määratud ja pole tühi
			$gender = intval($_POST["gender"]);
		} else {
			$signupGenderError = " (Palun vali sobiv!) Määramata!";
	}
	
	# Uue kasutaja lisamine andmebaasi
	if (empty($signupFirstNameError) and empty($signupBirthDayError) and empty ($signupGenderError) 
	and empty ($signupEmailError) and empty ($signupPasswordError) and !empty($_POST["signupPassword"])){
		#echo "Hakkan andmeid salvestama!";
		$signupPassword = hash("sha512", $_POST["signupPassword"]);
		
		signup($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupPic, $signupAddress, $signupPhoneNumber, $signupEmail, $signupPassword);
		
	}
	
	} #uue kasutaja loomise lõpp
	
	//Tekitame kuupäeva valiku
	$signupDaySelectHTML = "";
	$signupDaySelectHTML .= '<select name="signupBirthDay">' ."\n";
	$signupDaySelectHTML .= '<option value="" selected disabled>päev</option>' ."\n";
	for ($i = 1; $i < 32; $i ++){
		if($i == $signupBirthDay){
			$signupDaySelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
		} else {
			$signupDaySelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ." \n";
		}
		
	}
	$signupDaySelectHTML.= "</select> \n";
	#Sünnikuu valik
	$signupMonthSelectHTML = "";
	$monthNamesEt = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$signupMonthSelectHTML .= '<select name="signupBirthMonth">' ."\n";
	$signupMonthSelectHTML .= '<option value="" selected disabled>kuu</option>' ."\n";
	foreach ($monthNamesEt as $key=>$month){
		if ($key + 1 == $signupBirthMonth){
			$signupMonthSelectHTML .= '<option value="' .($key + 1) .'" selected>' .$month .'</option>' ."\n";
		} else {
		$signupMonthSelectHTML .= '<option value="' .($key + 1) .'">' .$month .'</option>' ."\n";
		}
	}
	$signupMonthSelectHTML .= "</select> \n";
	
	//Tekitame aasta valiku
	$signupYearSelectHTML = "";
	$signupYearSelectHTML .= '<select name="signupBirthYear">' ."\n";
	$signupYearSelectHTML .= '<option value="" selected disabled>aasta</option>' ."\n";
	$yearNow = date("Y");
	for ($i = ($yearNow - 10); $i > 1900; $i --){
		if($i == $signupBirthYear){
			$signupYearSelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
		} else {
			$signupYearSelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ."\n";
		}
		
	}
	$signupYearSelectHTML.= "</select> \n";
	
	
	
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>uue kasutaja loomine</title>
</head>

<body>
	
	<h2>Loo kasutaja</h2>
	<p>Uus kasutaja</p>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Eesnimi </label>
		<input name="signupFirstName" type="text" value="<?php echo $signupFirstName; ?>">
		<span> <?php echo $signupFirstNameError ?><span>
		<br>
		<label>Perekonnanimi </label>
		<input name="signupFamilyName" type="text" value="<?php echo $signupFamilyName; ?>">
		<span> <?php echo $signupFamilyNameError ?><span>
		<br>
		<label>Sisesta oma sünnikuupäev</label>
		<?php
			echo $signupDaySelectHTML ."\n" . $signupMonthSelectHTML ."\n" . $signupYearSelectHTML;

		?>	
		<span> <?php echo $signupBirthDayError ?> <span>		
		<br><br>
		<label>Sugu</label><span>
		<br>
		<input type="radio" name="gender" value="1" <?php if ($gender == '1') {echo 'checked';} ?>><label>Mees</label> <!-- Kõik läbi POST'i on string!!! -->
		<input type="radio" name="gender" value="2" <?php if ($gender == '2') {echo 'checked';} ?>><label>Naine</label>
		<span> <?php echo $signupGenderError ?> <span>
		<br><br>
		<label>Isikukood</label>
		<input name="signupPic" type="text" value="<?php echo $signupPic; ?>">
		<span> <?php echo $signupPicError ?><span>
		<br><br>
		<label>Aadress</label>
		<input name="signupAddress" type="text" value="<?php echo$signupAddress; ?>">
		<span><?php echo $signupAddressError ?><span>
		<br><br>
		<label>Telefoninumber</label>
		<input name="signupPhoneNumber" type="text" value="<?php echo $signupPhoneNumber; ?>">
		<span><?php echo $signupPhoneNumberError ?><span>
		<br><br>
		<label>Kasutajanimi (E-post)</label>
		<input name="signupEmail" type="email" value="<?php echo $signupEmail; ?>">
		<span> <?php echo $signupEmailError ?> <span>
		<br><br>
		<label>Salasõna</label>
		<input name="signupPassword" placeholder="Salasõna" type="password">
		<span> <?php echo $signupPasswordError ?> <span>
		<br><br>

		
		<input name="signupButton" type="submit" value="Loo kasutaja">
	</form>
	
</body>
</html>
