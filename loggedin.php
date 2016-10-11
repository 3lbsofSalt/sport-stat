<?php
session_start();
require_once('dontInsert.php');
if(!empty($_POST['username']) && !empty($_POST['password'])) {
    $auth = new auth;
    if($auth->login($_POST['username'], $_POST['password'])){ 
	echo('<!DOCTYPE html><html><body> <h1>Bloomin Fraggs!</h1>You made it!!! </body></html>');
	
	
    } else {
	header("Location: login.php");
	$_SESSION['error'] = 'Wrong credentials';
    }
} else {
    header("Location: login.php");
    $_SESSION['error'] = 'You made it a little';
}

?>
