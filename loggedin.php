<?php
session_start();
require_once('authenticate.php');
if(!empty($_POST['username']) && !empty($_POST['password'])) {
    $auth = new auth;
    if($auth->login($_POST['username'], $_POST['password'])){ 
		//
		//If login is valid
		//


		//Regular Page function
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
<!DOCTYPE html>
<html>
    <head>
        <title>Change Stats</title>   
    </head>
    <body>
     	<form action="loggedin.php" method="post">
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
    </body>
</html>

    <?php
	//
	//If invalid authentication
	//
    } else {
	header("Location: login.php");
	$_SESSION['error'] = 'Wrong credentials';
    }
} else {
    header("Location: login.php");
    $_SESSION['error'] = 'You made it a little';
}

?>
