<?php
	$db_username="root";
	$db_name="dispo-lan";
	$db_password="";
	$db_host="localhost";
	
	
    $GLOBALS["db"] = $db = new PDO('mysql:host=localhost;dbname=dispo-lan', $db_username, $db_password);
	
	/*
	TABLE users
	id, last-ip, registration-date, email, username, password-hash, avatarurl, website, lastconnect, timestamp
	*/
?>