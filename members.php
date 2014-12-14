<?php
session_start();
include("./etc/bdd.php");
include("./etc/functions.php");
if(isset($_SESSION["id"])) update_timestamp($_SESSION["id"]);
if(!isset($_SESSION["username"])) redire("index.php");

if(isset($_GET["id"]))
{
	if(is_numeric($_GET["id"]))
	{

	}else redire("members.php?id=0");
}else redire("members.php?id=0");

$nbPageMax = 10;
$query = $db->prepare("SELECT COUNT(*) AS number FROM users");
$query->execute();
$data = $query->fetch(PDO::FETCH_ASSOC);
$element_total = $data['number'];
$nbPage = pages_number($nbPageMax);
$num = $nbPageMax * $_GET["id"];
if($_GET["id"] >= $nbPage) redire("members.php?id=0");

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
			<div id="members_t">
			<center>
			<table>
				<tr>
					<th>Avatar</th>
					<th>Nom d'utilisateur</th>
					<th>Status</th>
				</tr>
				<?php
				$query = $GLOBALS["db"]->prepare("SELECT * FROM users ORDER BY id DESC LIMIT :num, :nbPageMax ");
				$query->bindValue(":num" , $num, PDO::PARAM_INT);
				$query->bindValue(":nbPageMax" , $nbPageMax, PDO::PARAM_INT);
				$query->execute();
				while($user = $query->fetch(PDO::FETCH_ASSOC))
				{
					echo "<tr>";
						if(empty($user["avatarurl"])){echo "<td><img src='img/avatars/default.jpg' width='30' height='30' /></td>";}else{echo "<td><img src='".$user["avatarurl"]."' width='30' height='30' /></td>";};
						echo "<td><a href='profile.php?id=".$user['id']."'>".$user["username"]."</a></td>";
						if(is_online($user["id"])){echo "<td><img src='img/online-icon.png'/></td>";}else{echo "<td><img src='img/offline-icon.png'/></td>";};
					echo "</tr>";
				}

				?>
			</table>
			<br/><br/>
			<?php
			$i=0;
				while($i < $nbPage)
				{
					echo "<a class='page_n' href='members.php?id=$i'>$i</a>";
					$i++;
				}
			?>
			</center>
			</div>
		</center>
		<br/><br/>

		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> 
		

</body>

</html>