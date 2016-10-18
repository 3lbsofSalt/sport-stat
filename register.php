<!DOCTYPE html>
<html>
    <?php
    session_start();
    require_once('authenticate.php');

    $auth = new auth();
    if(!empty($_POST['username']) && !empty(['pass'])){
	$raw['username'] = $_POST['username'];
	$raw['password'] = $_POST['pass'];
    $raw['password2'] = $_POST['confirm'];
	$auth->register($raw);
    }
    ?>
    <body>
	<form action="register.php" method="post">
	    <label for="username">Useranme:</label>
	    <input type="text" name="username" />
	    <label for="pass">Password:</label>
	    <input type="password" name="pass"/>
	    <label for="confirm" >Confirm Pasword</label>
	    <input type="password" name="confirm" >
	    <input type="submit" name="submit" /></form>
	</form>
    </body>
</html>
