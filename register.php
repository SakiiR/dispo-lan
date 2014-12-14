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
		$email = htmlspecialchars(trim($_POST["email"]));
		$password1 = htmlspecialchars(trim($_POST["password1"]));
		$password2 = htmlspecialchars(trim($_POST["password2"]));
		$ip = $_SERVER["REMOTE_ADDR"];
		$date = date("d/m/o à G:i:s");
		$timestamp = time();

		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			if($password1 == $password2)
			{
				$password = md5($password1);
				$query = $db->prepare("SELECT * FROM users WHERE username=:username OR email=:email AND password=:password");
				$query->bindValue(':username', $username, PDO::PARAM_STR);
				$query->bindValue(':password', $password, PDO::PARAM_STR);
				$query->bindValue(':email', $email, PDO::PARAM_STR);
				$query->execute();
				if($query->rowCount() < 1)
				{
					$query = $db->prepare("INSERT INTO users VALUES('', :username, :password, :ip, :date, :lastconnect, :email, '', '', :timestamp);");
					$query->bindValue(':username', $username, PDO::PARAM_STR);
					$query->bindValue(':password', $password, PDO::PARAM_STR);
					$query->bindValue(':ip', $ip, PDO::PARAM_STR);
					$query->bindValue(':date', $date, PDO::PARAM_STR);
					$query->bindValue(':lastconnect', $date, PDO::PARAM_STR);
					$query->bindValue(':email', $email, PDO::PARAM_STR);
					$query->bindValue(':timestamp', $timestamp, PDO::PARAM_INT);
					$query->execute();
					$success = "Votre compte à été créé , il ne vous reste plus qu'à vous connecter <a href='login.php'>ICI</a>";
				}else $error = "Un compte existe déjà avec l'adresse email:$email ou avec le nomd'utilisateur:$username";
			}else $error = "Les mots de passe doivent être identiques";
		}else $error = "L'adresse email est invalide elle doit être sous la forme suivante : exemple@exemple.com";
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
	<div class="titulo">Enregistrement</div>
	<?php echo "<center><span class='error'>$error</span></center><br/>"; ?>
	<?php echo "<center><span class='success'>$success</span></center><br/>"; ?>
	<form action="" method="POST" enctype="application/x-www-form-urlencoded">
    	<input name="username" type="text" required title="Entrez un nom d'utilisateur" placeholder="Nom d'utilisateur" data-icon="U"><br/><br/>
    	<input name="email" type="text" required title="Entrez une adresse email" placeholder="Email" data-icon="U"><br/><br/>
        <input name="password1" type="password" required title="Entrez un mot de passe" placeholder="Mot de passe" data-icon="x"><br/><br/>
        <input name="password2" type="password" required title="Répétez votre mot de passe" placeholder="Répétition du mot de passe" data-icon="x">
        <br/> <br/> 
        <input class="submit_i" type="submit" name="submit_f" value="S'enregistrer"><br/><br/>
    </form>
</section>
</body>

</html>