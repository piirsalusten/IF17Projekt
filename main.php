<?php
	//et pääseks ligi sessioonile ja funktsioonidele
	require("../../config.php");
	require("functions.php");
	
	#muutujad
	$loginEmail = "";
	$notice= "";
	
	#Kui on sisse loginud, siis pealehele
	if(isset($SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: main.php");
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

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Pealkiri!
	</title>
</head>
<style>
table, th, td {
    border: 1px solid black;
}
</style>
<body>

<?php if( isset($_SESSION['userId']) && !empty($_SESSION['userId']) )
{
?>
	<h1>Tere, <?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?></h1>
	<p><a href="addItem.php">Lisa kuulutus</a></p>
	<a href="?logout=1">Logi välja!</a>
<?php }else{ ?>
	<h1>Teretulemast meie müügiportaalile! Kuulutuste lisamiseks logi sisse! :) </h1>
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
	
	<p> Kategooriad: </p>
	<p><a href="cars.php">Autod</a></p>
	<p><a href="usersphotos.php">Tööriistad</a></p>
	<p><a href="uploadphoto.php">Kodutarbed</a></p>
	<center>
	<p> Viimati üleslaetud kuulutused: </p>
	<span><?php echo publicPhotos(); ?> </span>
	</center>
</body>
</html>
