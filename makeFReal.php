<?php

$link = 0;

if(is_int($link)) {
    $link = new mysqli($host, $username, $pass, $db);
    if($link->connect_errno){
        echo('Please no');
	exit();
    }
}

$link->query("CREATE TABLE playerStats (ID int NOT NULL AUTO_INCREMENT, playerNum int(8), name varchar(50), pos varchar(3), goals int(8), assists int(8), PRIMARY KEY (ID))");
$link->query("CREATE TABLE users (user_id int(40) UNSIGNED NOT NULL AUTO_INCREMENT, username varchar(15) NOT NULL UNIQUE, pwd char(60) NOT NULL, reg_date int(40) NOT NULL, PRIMARY KEY(user_id))");
echo("SUCCESS");

exit();
?>
