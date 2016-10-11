<?php
session_start();

require_once("db.php");
$db = new db;
$bd->getStats();
?>

<!-- Where you want the database
type this EXACTLY: <?php require_once("insertThis.php"); ?>
   -->
