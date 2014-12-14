<?php
	session_start();
	include("./etc/bdd.php");
	include("./etc/functions.php");
	if(isset($_SESSION["id"])) update_timestamp($_SESSION["id"]);
	$error = "";
	$success = "";
	if(isset($_POST["submit_f"]))
	{
		$username = htmlspecialchars(trim($_POST["username"]));
		$password = htmlspecialchars(trim($_POST["password"]));
		$date = date("d/m/o à G:i:s");
		$ip = $_SERVER["REMOTE_ADDR"];
		$timestamp = time();

		if(!empty($username) && !empty($password))
		{
			
			$password=md5($password);
			$query = $db->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
			$query->bindValue(':username', $username, PDO::PARAM_STR);
			$query->bindValue(':password', $password, PDO::PARAM_STR);
			$query->execute();
			$user_id = $query->fetch(PDO::FETCH_ASSOC)["id"];
			if($query->rowCount() >= 1)
			{	
				$query = $db->prepare("UPDATE users SET timestamp=:timestamp, lastip=:lastip, lastconnection=:lastconnect WHERE id=:id");
				$query->bindParam(":lastip", $ip, PDO::PARAM_STR);
				$query->bindParam(":lastconnect", $date, PDO::PARAM_STR);
				$query->bindParam(":id", $user_id, PDO::PARAM_INT);
				$query->bindParam(":timestamp", $timestamp, PDO::PARAM_INT);
				$query->execute();
				$success = "Connexion ..";
				$_SESSION["logged"] = "true";
				$_SESSION["username"] = $username;
				$_SESSION["id"] = $user_id;
				redire("index.php");
				//Success
			}else $error = "Aucun compte correspondant";
		}else $error = "Vous devez remplir tout les champs";
	}
?>

<!doctype html>
<html lang="fr">
<html>

<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Dispo LAN</title>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/forms.css" />
</head>

<body>
	<center><a href="index.php" class="banner">Dispo LAN</a></center>
	<section class="login">
	<div class="titulo">Se connecter</div>
	
	<?php if($error != "") echo "<center><span class='error'>$error</span></center><br/>"; ?>
	<?php if($success != "") echo "<center><span class='success'>$success</span></center><br/>"; ?>
	<form action="" method="post" enctype="application/x-www-form-urlencoded">
    	<input class="edit" name="username" type="text" required title="Entrer un nom d'utilisateur" placeholder="Nom d'utilisateur" data-icon="U"><br/> <br/> 
        <input class="edit" name="password" type="password" required title="Entrer un mot de passe" placeholder="Mot de passe" data-icon="x"><br/> <br/> 
        <input class="submit_i" type="submit" name="submit_f" value="Se Connecter"><br/> <br/> 
    </form>
</section>
</body>

</html>