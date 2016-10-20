<?php
require_once('settings.php');
global $SETTINGS;
$db = $SETTINGS['database'];
$host = $SETTINGS['host'];
$username = $SETTINGS['username'];
$pass = $SETTINGS['password'];

$link = 0;

echo("Credentials Test: \n");

if(is_int($link)) {
    $link = new mysqli($host, $username, $pass, $db);
    if($link->connect_errno){
        echo("Connection Failed;\n");
        echo('Error #: ' . $link->connect_errno . "\n");
        echo ('Error Description: '.$link->connect_error . "\n");
	exit();
    } else {
        echo("Test Successful.\n");
    }
}

echo("Testing users table: \n");
$res = $link->query("SELECT * FROM users WHERE username='bleh'");
if($res){
    $link->query("DELETE FROM users WHERE username='bleh'");
}

$res = $link->query("INSERT INTO users (username, pwd, reg_date) VALUES ('bleh', 'duh', '800000')");
if($res == false){
    echo ("INSERT failed.\n");
    exit();
} else {
    echo ("INSERT success.\n");
}

$res = $link->query("SELECT * FROM users");
if($res == false) {
    echo("SELECT failed.\n");
    exit();
} else {
    echo("SELECT successful.\n");
}

echo("users table okay. \n");
echo("Testing playerStats table \n");

$res = $link->query("SELECT * FROM playerStats WHERE playerNum='89'");
if($res){
    $link->query("DELETE FROM playerStats WHERE playerNum='89'");
}

$res = $link->query("INSERT INTO playerStats (playerNum, name, pos, goals, assists) VALUES ('89', 'FiddleHand Mick', 'DA', '87', '8000')");
if($res == false){
    echo ("INSERT failed.\n");
    exit();
} else {
    echo ("INSERT success.\n");
}

$res = $link->query("SELECT * FROM playerStats");
if($res == false) {
    echo("SELECT failed.\n");
    exit();
} else {
    echo("SELECT successful.\n");
}

echo("playerStats table okay\n");
echo("Deleting bogus inserts.\n");

$res = $link->query("DELETE FROM users WHERE username='bleh'");
if($res == false){
    echo("user DELETE failed.\n");
} else {
    echo("user DELETE complete.\n");
}

$res = $link->query("DELETE FROM playerStats WHERE playerNum='89'");
if($res == false){
    echo("playerStats DELETE failed.\n");
} else {
    echo("playerStats DELETE complete.\n");
}

echo("Database should work!\n");
echo("Looks Like you have all the bells and whistles falling apart at the seams! Exactly what you wanted!");