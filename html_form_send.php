<?php

//et pääseks ligi sessioonile ja funktsioonidele
	require("../../config.php");
	require("functions.php");
	
	#muutujad
	$loginEmail = "";
	$notice= "";
	
	#Kui on sisse loginud, siis pealehele
	if(isset($SESSION["userId"])){
		header("Location: index.php");
		exit();
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: index.php");
		exit();
	}
	
	#Kas logiti sisse
	if(isset ($_POST["signinButton"])){
	
	//kas on kasutajanimi sisestatud
	if (isset ($_POST["loginEmail"])){
		if (empty ($_POST["loginEmail"])){
			$loginEmailError ="NB! Ilma selleta ei saa sisse logida!";
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}
	
	if(!empty($loginEmail) and !empty($_POST["loginPassword"])){
		#echo "Logime sisse!";
		$notice = signin($loginEmail, $_POST["loginPassword"]);
	}
	
	}

	if(isset($_POST['email'])) {
     
    // CHANGE THE TWO LINES BELOW
    $email_to = "randoa@tlu.ee";
     
    $email_subject = "Lehe tagasiside";
     
     
    function died($error) {
        // your error code can go here
        echo "Kahjuks tekkis tõrge. ";
        echo "Error: .<br /><br />";
        echo $error."<br /><br />";
        echo "Palun parandage vead.<br /><br />";
        die();
    }
     
    // validation expected data exists
    if(!isset($_POST['first_name']) ||
        !isset($_POST['last_name']) ||
        !isset($_POST['email']) ||
        !isset($_POST['telephone']) ||
        !isset($_POST['comments'])) {
        died('Kahjuks tekkis tõrge');       
    }
     
    $first_name = $_POST['first_name']; // required
    $last_name = $_POST['last_name']; // required
    $email_from = $_POST['email']; // required
    $telephone = $_POST['telephone']; // not required
    $comments = $_POST['comments']; // required
     
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
  if(!preg_match($email_exp,$email_from)) {
    $error_message .= 'Emaili aadress ei sobi.<br />';
  }
    $string_exp = "/^[A-Za-z .'-]+$/";
  if(!preg_match($string_exp,$first_name)) {
    $error_message .= 'Eesnimi ei sobi.<br />';
  }
  if(!preg_match($string_exp,$last_name)) {
    $error_message .= 'Perekonnanimi ei sobi.<br />';
  }
  if(strlen($comments) < 2) {
    $error_message .= 'Kommentaar ei sobi.<br />';
  }
  if(strlen($error_message) > 0) {
    died($error_message);
  }
    $email_message = "Detailid.\n\n";
     
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
     
    $email_message .= "Eesnimi: ".clean_string($first_name)."\n";
    $email_message .= "Perekonnanimi: ".clean_string($last_name)."\n";
    $email_message .= "Email: ".clean_string($email_from)."\n";
    $email_message .= "Telefoni number: ".clean_string($telephone)."\n";
    $email_message .= "Kommentaar: ".clean_string($comments)."\n";
     
     
// create email headers
$headers = 'From: '.$email_from."\r\n".
'Vasta: '.$email_from."\r\n" .
'X-Mailer: PHP/' . phpversion();
@mail($email_to, $email_subject, $email_message, $headers);  
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
				<h2>Aitäh, et võtsite meiega ühendust. Võtame Teiega peagi ühendust!</h2>
				
		
				</div>
		
			</div>

			<div id="sidebar">
				<div id="feeds">
					
					<?php if( isset($_SESSION['userId']) && !empty($_SESSION['userId']) )
						{
					?>
					<p>Tere, <?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?></p>
					<p><a href="addsale.php">Lisa kuulutus</a></p>
					<a href="mylistings.php">Minu kuulutused</a><br>
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
				All Rights Reserved
			</p>
			
		</div>
	</div>


	
</body>
</html>