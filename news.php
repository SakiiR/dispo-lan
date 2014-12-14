<?php
	session_start();
	include("./etc/bdd.php");
	include("./etc/functions.php");
	if(isset($_SESSION["id"])) update_timestamp($_SESSION["id"]);
?>

<!doctype html>
<html lang="fr">
<html>

<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Dispo LAN</title>

		<script src="js/menu.js"></script>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/menu.css" />
</head>

<body>
		<center><a href="index.php" class="banner">Dispo LAN</a></center><br/><br/>
		<?php include("menu.php"); ?>


		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> 
		

</body>

</html>