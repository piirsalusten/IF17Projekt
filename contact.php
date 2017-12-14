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
				<h1>Kontakt</h1>
				<!--<h3>Rando Aljaste</h3>
				<img src="kontaktpilt/rando.jpg" width="200" height="200" alt="Rando">
				<h3>Mihkel Mägi</h3>
				<img src="kontaktpilt/mihkel.jpg" width="200" height="200" alt="Mihkel">
				<h3>Sten Piirsalu</h3>
				<img src="kontaktpilt/sten.jpeg" width="200" height="200" alt="Sten">
				<br>
				<br>
				<h1>Kontaktinfo</h1>//-->
				<p>Saada meile sõnum täites tagasiside vormi.</p>
				<br>
				<form name="htmlform" method="post" action="html_form_send.php">
					<table width="450px">
					</tr>
					<tr>
					 <td valign="top">
					  <label for="first_name">Eesnimi *</label>
					 </td>
					 <td valign="top">
					  <input  type="text" name="first_name" maxlength="50" size="30">
					 </td>
					</tr>
					 
					<tr>
					 <td valign="top"">
					  <label for="last_name">Perekonnanimi *</label>
					 </td>
					 <td valign="top">
					  <input  type="text" name="last_name" maxlength="50" size="30">
					 </td>
					</tr>
					<tr>
					 <td valign="top">
					  <label for="email">E-post *</label>
					 </td>
					 <td valign="top">
					  <input  type="text" name="email" maxlength="80" size="30">
					 </td>
					 
					</tr>
					<tr>
					 <td valign="top">
					  <label for="telephone">Telefoni number</label>
					 </td>
					 <td valign="top">
					  <input  type="text" name="telephone" maxlength="30" size="30">
					 </td>
					</tr>
					<tr>
					 <td valign="top">
					  <label for="comments"></label>
					 </td>
					 <td valign="top">
					  <textarea  name="comments" maxlength="1000" cols="25" rows="6"></textarea>
					 </td>
					 
					</tr>
					<tr>
					 <td colspan="2" style="text-align:center">
					  <input type="submit" value="Saada"></a>
					 </td>
					</tr>
					</table>
					</form>
		
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