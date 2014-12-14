<?php

function redire($page)
{
    //sleep(2);
    header("Location:$page");
    exit(0);
}

function is_online($user_id)
{
    $timestamp = time();
    $query = $GLOBALS["db"]->prepare("SELECT * FROM users WHERE id=:id");
    $query->bindValue(":id", $user_id, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
    $user_timestamp = $user["timestamp"];
		
    if(($user_timestamp + 30) <= $timestamp)
    {
        return false;
    }else return true;
}

function update_timestamp($user_id)
{
    $time = time();
    $query = $GLOBALS["db"]->prepare("UPDATE users SET timestamp=:time WHERE id=:id");
    $query->bindParam(":time", $time, PDO::PARAM_INT);
    $query->bindParam(":id", $user_id, PDO::PARAM_INT);
    $query->execute();
}

function pages_number($nbMax) 
{
    $query = $GLOBALS["db"]->prepare("SELECT COUNT(*) AS number FROM users");
    $query->execute();
    $data = $query->fetch(PDO::FETCH_ASSOC);
    $nbLogs = $data['number'];
    $nbPage = ceil($nbLogs / $nbMax);
    return $nbPage;
}

function png2jpg($originalFile, $outputFile) 
{
    $image = imagecreatefrompng($originalFile);
    imagejpeg($image, $outputFile, 50);
    imagedestroy($image);
}

function isURL($url) 
{
    $reg_exp = "/^(http(s?):\/\/)?(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/";
    return (preg_match($reg_exp, $url));
}
?>