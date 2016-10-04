<!DOCTYPE html>
<html>
  
    <?php

    error_reporting(E_ALL);
    session_start();

    //phpinfo();
    
    require_once('db.php');
    $db = new db;

    if(!empty($_POST['playNum'])){
	
    	$raw = array();
	if(!empty($_POST['playNum'])){
	    $raw['playerNum'] = $_POST['playNum'];
	}
	if(!empty($_POST['name'])){
	    $raw['name'] = $_POST['name'];
	}
	if(!empty($_POST['position'])){
	    $raw['pos'] = $_POST['position'];
	}
	if(!empty($_POST['goals'])){
	    $raw['goals'] = $_POST['goals'];
	}
	if(!empty($_POST['assists'])){
	    $raw['assists'] = $_POST['assists'];
	}

	if($_POST['action'] == 'add') {
	    $db->addPlayer($raw);
	} else if($_POST['action'] == 'edit') {
	    $db->updateStats($raw);
	} else if($_POST['action'] == 'delete') {
	    $db->deletePlayer($raw['playerNum']);
	} 
    }
    
    ?>
    
    <head>
	<title>Hockey Stat Hooks</title>
    </head>
    <body>
	<form action="index.php" method="post">
	    <select name="action" >
		<option value="edit">Edit Stats</option>
		<option value="add">Add Player</option>
		<option value="delete">Remove Player</option>
	    </select>
	    
	    <label for="name"> Name: </label>
	    <input name="name" type="text" /> <br />
	    <label for="playNum" > Player Number: </label>
	    <input name="playNum" type="text" required /> <br />
	    <label for="position" > Position: </label>
	    <input name="position" type="text" />
	    <label for="goals" > Goals: </label>
	    <input name="goals" type="text" />
	    <label for="assists" > Assists: </label>
	    <input name="assists" type="text" />

	    <input name="submit" type="submit" />
	</form>

	<br />
	<br />
	<?php
	$db->getStats();
	?>
    </body>
</html>
