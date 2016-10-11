<!DOCTYPE>
    <html>
	<body>
	    <form action="loggedin.php" method="post">
		<label for="username" >Username </label>
		<input name="username" type="text"/>
		<label for="password" > Password </label>
		<input name="password" type="password" />
		<input name="submit" type="submit" />
	    </form>

	    <?php
	    
	    session_start();
	    echo($_SESSION['start']);
	    echo($_SESSION['error']);
	    unset($_SESSION['start']);
	    unset($_SESSION['error']);
	    ?>
	    
	    
	</body>
    </html>
