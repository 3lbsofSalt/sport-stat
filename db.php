<?php

require_once('settings.php');

class db {

    public $table;     //Database table
    public $database;  //Database
    public $host;      //Host ip address
    public $username;  //host username
    public $password;  //host password
    public $link = 0;  //Database variable, used for most of the querys and functions
    
    var $errors;  		//Currently unimplemented errors variable

    function __construct() {	//Constructs object and sets variables needed for connect
	global $SETTINGS;
	$this->database = $SETTINGS['database'];
	$this->host = $SETTINGS['host'];
	$this->username = $SETTINGS['username'];
	$this->password = $SETTINGS['password'];
    }

    function connect() {		//Connects to the database
	if(is_int($this->link)){	//Only connect if $link is not an object
	    $this->link = new mysqli($this->host, $this->username, $this->password, $this->database);
	    if($this->link->connect_errno) { //Error handleing
		$this->errors = "Database didn't connect because: " . $this->link->connect_errno;
	    }
	    return $this->link;
	}
    }

	//Query function used to retrieve data
	//$string is the query string, $force array will make the return value be an multidimensional array if set to true
    function query($string, $force_array = false) {
	$this->connect();
	$res = $this->link->query($string);
	if($res) {	//If query contains a value
	    if(is_bool($res)) { //if results are boolean it returns a bool
		return true;
	    }
	    $rows = $res->num_rows;
	    if($rows > 1 or $force_array){ //Returns all rows in a multidimensional array
		return $res->fetch_all($resulttype = MYSQL_ASSOC);
	    } else {
		return $res->fetch_assoc(); //Return associative array
	    }
	}
    }
    
    function setTable($table) {	//Used to set objects database table in a secure way
	$this->table = $table;
    }
    
    function addPlayer($array) {              						// Adds a complete player stats; $array should be a key=>value array with the keys of playerNum, name, pos(ition), goals, assists; */
	
	$this->connect();

	$this->setTable('playerStats');
	$string = "INSERT INTO " . $this->table . " ";                	// Beginning of query to insert in database */
	
	$num = count($array);                                         	// Counts # of array keys */
	foreach($array as $key=>$value) {
	    $keys .= " " .$key;			 								// Adds a key to the final array each time around */
	    
	    $value = mysqli_real_escape_string($this->link, $value);	// Escapes values from having any illegal characters */
	    $values .= ' "'.$value.'"';									// Adds escaped value to the values array */
	  
		if($num > 1){												// This block wont be executed on the last array value; It adds the commas between the keys and values arrays */
			$keys .= ",";
			$values .= ",";
	    }
	    $num -= 1;													// Decrements to keep track of current array value */
	}
	
	$string .= '(' .$keys. ') VALUES (' .$values. ')';				/* Finishes creating the query string; It ends up looking like:
									 									* INSERT INTO $table ($key, $key, $key) VALUES ("$value", "$value", "$value");
									 								*/
	
	
	$this->link->query($string);									/* Performs query */
	
	return true;
    }

	//Updates statistics based on an array that contains values in the database
	//Currently accepst an array with int playerNum, varchar name, varchar pos, int goals, int assists
	//playerNum is required.
    function updateStats($array){									
	$this->connect();
	$this->setTable('playerStats');
	
	$string = "UPDATE " . $this->table . " SET ";

	$update = '';

	$num = count($array); 
	foreach($array as $key=>$value) { 				//Runs through array values adding them to the update string
	    $update .= $key . ' = "' . $value . '"';	
	    if($num > 1) {
		$update .= ', ';
	    }
	    $num -= 1;
	}

	$string .= $update . ' WHERE playerNum = ' . $array['playerNum'];
	
	$this->link->query($string);
	
    }

	//Deletes player based on their Player Number
	//requires playerNum
    function deletePlayer($playNum){
	$this->connect();
	$this->setTable('playerStats');

	$string = "DELETE FROM " . $this->table . ' WHERE playerNum = "' . $playNum . '"';


	$this->link->query($string);
    }
	
	//Gathers all player stats and injects the html based on where the function was called
    function getStats() {
	$this->connect();
	$this->setTable('playerStats');

	$string = "SELECT playerNum, name, pos, goals, assists FROM playerStats";
	$results = $this->link->query($string);

	$html = "<table style='width:auto;' class='statTable'>";
	$html .= "<tr class='row top'> <td class='topstuf column'>Player Number</td><td class='topstuf column'>Name</td><td class='topstuf column'>Position</td><td class='topstuf column'>Goals</td><td class='topstuf column'>Assists</td></tr>";
	foreach($results as $array){
	    $html .= "<tr class='row'>";
	    foreach($array as $value){
		$html .= "<td class='column'>" . $value . "</td>";
	    }
	    $html .= "</tr> <br />";
	}

	$html .= "</table>";
	$html .= "<style>";
	$html .= ".column {font-family:Trebuchet, Trebuchet MS;";
	$html .= "text-align: center; border: 2px black solid; width:236px;}";
	$html .= ".topstuf{ background-color: #fac31e;}";
	$html .= ".statTable{border-collapse:collapse; position:absolute; bottom: 140px;}";
	
	echo($html);
    }
}
?>
