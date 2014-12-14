<?php
    $id = "1337";
    if(isset($_SESSION["username"]))
    {
        $query = $db->prepare("SELECT * FROM users WHERE username=:username;");
        $query->bindValue(":username", $_SESSION["username"], PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        $id = $user["id"];
    }

?>
<center>
<div class="container">

            <ul id="nav">
                <li><a >Bienvenue <?php if(isset($_SESSION["username"])){echo $_SESSION["username"];}else{echo "visiteur";} ?></a></li>
                <?php if(isset($_SESSION["username"])) echo '<li><a href="news.php">News</a></li>';?>
                <?php if(isset($_SESSION["username"])) echo '<li><a href="config.php">Configuration</a></li>';?>
                <?php if(isset($_SESSION["username"])) echo '<li><a href="profile.php?id='.$id.'">Profile</a></li>';?>
                <?php if(isset($_SESSION["username"])) echo '<li><a href="members.php?id=0">Membres</a></li>';?>
                <?php if(!isset($_SESSION["username"])) echo '<li><a href="register.php">Enregistrement</a></li>';?>
                <?php if(!isset($_SESSION["username"])) echo '<li><a href="login.php">Connexion</a></li>';?>
                <?php if(isset($_SESSION["username"])) echo '<li><a href="logout.php">Deconnexion</a></li>';?>
                <div id="lavalamp"></div>
            </ul>

        </div>
</center>


<!-- 
                <li><a class="hsubs" href="#">Menu 2</a>
                    <ul class="subs">
                        <li><a href="#">Submenu 2-1</a></li>
                        <li><a href="#">Submenu 2-2</a></li>
                        <li><a href="#">Submenu 2-3</a></li>
                        <li><a href="#">Submenu 2-4</a></li>
                        <li><a href="#">Submenu 2-5</a></li>
                        <li><a href="#">Submenu 2-6</a></li>
                        <li><a href="#">Submenu 2-7</a></li>
                        <li><a href="#">Submenu 2-8</a></li>
                    </ul>
                </li>

-->
