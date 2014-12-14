<?php
	session_start();
	include("./etc/bdd.php");
	include("./etc/functions.php");
	if(isset($_SESSION["id"])) update_timestamp($_SESSION["id"]);

	if(!isset($_SESSION["username"]))
	{
		redire("index.php");
	}
	if(isset($_GET["id"]))
	{
		$id = htmlspecialchars(trim($_GET["id"]));
		if(is_numeric($id))
		{
			$profile_id = $id;

			$query = $db->prepare("SELECT * FROM users WHERE id=:profile_id");
			$query->bindValue(':profile_id', $profile_id, PDO::PARAM_STR);
			$query->execute();
			$user_infos = $query->fetch(PDO::FETCH_ASSOC);
			
			if($user_infos["username"] == "") redire("index.php");
		}else redire("index.php");
	}else redire("index.php");
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
			<?php if($_SESSION["id"] == $profile_id) echo "<a href='config.php'><img class='config_s' style='position:absolute' src='./img/config_ico.png'/></a>"; ?>
			<?php if(is_online($profile_id)){echo "<img class='status'  src='img/online-icon.png'/>";}else{echo "<img class='status'  src='img/offline-icon.png'/>";} ?>
			<img class"avatar" src="<?php if($user_infos["avatarurl"] != ""){echo $user_infos["avatarurl"];}else{echo "http://www.timlanefitness.com/images/img_placeholder_avatar.jpg";} ?>" width="100" height="100"/><br/><br/>
			<span class="info_title">Nom d'utilisateur : </span><?php echo $user_infos["username"]; ?> <br/>
			<span class="info_title">Dernière connexion : </span><?php echo $user_infos["lastconnection"]; ?> <br/>
			<span class="info_title">Date d'enregistrement : </span><?php echo $user_infos["registrationdate"]; ?> <br/>
			<span class="info_title">Site web : </span><a href "<?php echo $user_infos["website"]; ?>"><?php echo $user_infos["website"]; ?></a><br/>
			<span class="info_title">Email : </span><?php echo $user_infos["email"]; ?> <br/>
		</div>
		</center>

		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> 

</body>

</html>
