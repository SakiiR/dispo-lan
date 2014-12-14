<?php
	session_start();
	include("./etc/bdd.php");
	include("./etc/functions.php");
	if(isset($_SESSION["id"])) update_timestamp($_SESSION["id"]);
	if(!isset($_SESSION["id"])) redire("index.php");
	$query = $GLOBALS["db"]->prepare("SELECT * FROM users WHERE id=:id");
	$query->bindValue(":id", $_SESSION["id"], PDO::PARAM_STR);
	$query->execute();
	$user = $query->fetch(PDO::FETCH_ASSOC);

	// ---- FILE UPLOAD -----------------------------------------------------------------------------------------
	$errors = array();
	if(isset($_POST["submit_f"]))
	{
		$avatar = $_FILES["avatar"]["name"];
		$avatarTmp = $_FILES["avatar"]["tmp_name"];

		if(!empty($avatarTmp))
		{
			$image = explode('.', $avatar);
			$extention = $image[1];
			if(strtolower($extention) == "jpg")
			{
				if(empty($image[2]))
				{
					if($_FILES['avatar']['size'] <= $_POST["MAX_FILE_SIZE"])
					{
						if($_FILES['avatar']['type'] == "image/jpeg")
						{
							$filename = "img/avatars/".md5($_SESSION["username"]).".jpg";
							move_uploaded_file($_FILES['avatar']['tmp_name'], $filename);
							$query = $GLOBALS["db"]->prepare("UPDATE users SET avatarurl=:filename WHERE id=:id");
							$query->bindValue(":filename", $filename, PDO::PARAM_STR);
							$query->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
							$query->execute();
							redire("config.php");
						}else $errors[] = "Fais pas de connerie Jack";
					}else $errors[] = "Veuillez envoyer une image plus petite que 1048576 octets";
				}else $errors[] = "Evite ça Pierrot l'escargot !";
			}else $errors[] = "Veuillez envoyer une image jpeg (*.jpg)";
		}else $errors[] = "Veuillez devez spécifier un fichier";
	}
	//-----------------------------------------------------------------------------------------------------------

	// ---- Password Changing -----------------------------------------------------------------------------------
	$errors2 = array();
	if(isset($_POST["submit_p"]))
	{
		$aPass = htmlspecialchars(trim($_POST["actual_p"]));
		$pass1 = htmlspecialchars(trim($_POST["new_p"]));
		$pass2 = htmlspecialchars(trim($_POST["new_p2"]));
		if(!empty($aPass) && !empty($pass1) && !empty($pass2) && $pass1 == $pass2)
		{
			$aPass = md5($aPass);
			$query = $GLOBALS["db"]->prepare("SELECT * FROM users WHERE password=:password AND id=:id");
			$query->bindValue(":password", $aPass, PDO::PARAM_STR);
			$query->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
			$query->execute();

			if($query->rowCount() == 1)
			{
				$pass = md5($pass1);
				$query = $GLOBALS["db"]->prepare("UPDATE users SET password=:pass WHERE id=:id");
				$query->bindValue(":pass", $pass, PDO::PARAM_STR);
				$query->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
				$query->execute();
				$errors2[] = "Votre mot de passe à été changé avec succes !";
			}else $errors2[] = "Vous avez entré un mot de passe incorrect";
		}else $errors2[] = "Les deux mots de passe doivent être égales , et vous devez remplir tout les champs";

	}
	//-----------------------------------------------------------------------------------------------------------

	// ---- Web Site Updating -----------------------------------------------------------------------------------
	$error = "";
	if(isset($_POST["submit_w"]))
	{
		$website = htmlspecialchars(trim($_POST["website"]));
		if(!empty($website))
		{
			if(isURL($website))
			{
				$query = $GLOBALS["db"]->prepare("UPDATE users SET website=:website WHERE id=:id");
				$query->bindValue(":website", $website, PDO::PARAM_STR);
				$query->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
				$query->execute();
				$error = "Site web mis à jour";
			}else $error = "Site web invalide";
		}else $error = "Veuillez entrer un site web"; 
	}
	//-----------------------------------------------------------------------------------------------------------

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
		<br/><br/><br/>
		<center>
		<div id="profile">
		<h2>Configurations</h2><br/>

		<hr/>

		<h4><u>Mot de passe</u></h4><br/>
		<?php
		foreach($errors2 as $error)
		{
			echo "<span class='error'>$error</span><br/>";
		}
		?>
		<form method="post" action="">
			Mot de passe actuel : <input type="password" name="actual_p"/><br/><br/><br/>
			Nouveau mot de passe : <input type="password" name="new_p"/><br/><br/>
			Répétez votre nouveau mot de passe : <input type="password" name="new_p2"/><br/><br/>
			<input type="submit" name="submit_p" value="Changer mot de passe"/><br/><br/>
		</form>

		<hr/>

		<h4><u>Avatar</u></h4><br/>
		<center>
		<?php

		foreach($errors as $error)
		{
			echo "<span class='error'>$error</span><br/>";
		}

		?>
			<img height="100" width="100" src=" <?php echo $user["avatarurl"]; ?> "/>
			<form method="POST" action="" enctype="multipart/form-data">
			Selectionnez une image (max Ko)<br/><br/><input type="file" name="avatar" value="Fichier"/><br/><br/>
			<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
			<input type="submit" name="submit_f" value="Envoyer L'image"/><br/>
			</form>
		</center>
		<br/>
		<hr/>

		<h4><u>Site WEB</u></h4><br/>
		<?php echo "<span class='error'>$error</span>"; ?>
		<form method="POST" action="">
			De la forme www.google.com<br/>
			Site WEB : <input type="text" name="website"/><br/><br/>
			<input type="submit" name="submit_w" />
		</form>


		</div>
		<br/><br/>
		</center>

		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> 
		

</body>

</html>