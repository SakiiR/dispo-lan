<?php
	session_start();
	include("etc/functions.php");
	session_destroy();
	$_SESSION["logged"] = "false";
	redire("index.php");
?>