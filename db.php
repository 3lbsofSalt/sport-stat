<?php

require_once('settings.php');

class db {

    public $table;     //Database table
    public $database;  //Database
    public $host;      
    public $username;
    public $password;
    public $link = 0;

    var $errors;

    function __construct() {
	global $SETTINGS;
	$this->database = $SETTINGS['database'];
	$this->host = $SETTINGS['host'];
	$this->username = $SETTINGS['username'];
	$this->password = $SETTINGS['password'];
    }

    function connect() {
	if(is_int($this->link)){
	    $this->link = new mysqli($this->host, $this->username, $this->password, $this->database);
	    if($this->link->connect_errno) {
		$this->errors = "Database didn't connect because: " . $this->link->connect_errno;
	    }
	}
    }

    function query($string, $force_array = false) {
	$this->connect();
	$res = $this->link->query($string);
	if($res) {
	    if(is_bool($res)) {
		return true;
	    }
	    $rows = $res->num_rows;
	    if($rows > 1 or $force_array){
		return $res->fetch_all($resulttype = MYSQL_ASSOC);
	    } else {
		return $res->fetch_assoc();
	    }
	}
    }
    
    function setTable($table) {
	$this->table = $table;
    }
    
    function addPlayer($array) {                                      	/* Adds a complete player stats; $array should be a key=>value array with the keys of playerNum, name, pos(ition), goals, assists; */
	
	$this->connect();

	$this->setTable('playerStats');
	$string = "INSERT INTO " . $this->table . " ";                	/* Beginning of query to insert in database */
	
	$num = count($array);                                         	/* Counts # of array keys */
	foreach($array as $key=>$value) {
	    $keys .= " " .$key;			 			/* Adds a key to the final array each time around */
	    
	    $value = mysqli_real_escape_string($this->link, $value);	/* Escapes values from having any illegal characters */
	    $values .= ' "'.$value.'"';					/* Adds escaped value to the values array */
	  
	    if($num > 1){						/* This block wont be executed on the last array value; It adds the commas between the keys and values arrays */
		$keys .= ",";
		$values .= ",";
	    }
	    $num -= 1;							/* Decrements to keep track of current array value */
	}
	
	$string .= '(' .$keys. ') VALUES (' .$values. ')';		/* Finishes creating the query string; It ends up looking like:
									 * INSERT INTO $table ($key, $key, $key) VALUES ("$value", "$value", "$value");
									 */
	
	
	$this->link->query($string);				/* Performs query */
	
	return true;
    }

    function updateStats($array){
	$this->connect();
	$this->setTable('playerStats');
	
	$string = "UPDATE " . $this->table . " SET ";

	$update = '';

	$num = count($array);
	foreach($array as $key=>$value) {
	    $update .= $key . ' = "' . $value . '"';
	    if($num > 1) {
		$update .= ', ';
	    }
	    $num -= 1;
	}

	$string .= $update . ' WHERE playerNum = ' . $array['playerNum'];
	
	$this->link->query($string);
	
    }

    function deletePlayer($playNum){
	$this->connect();
	$this->setTable('playerStats');

	$string = "DELETE FROM " . $this->table . ' WHERE playerNum = "' . $playNum . '"';


	$this->link->query($string);
    }
	
    function getStats() {
	$this->connect();
	$this->setTable('playerStats');

	$string = "SELECT * FROM playerStats";
	$results = $this->link->query($string);

	$html = "<table style='width:100%'>";

	foreach($results as $array){
	    $html .= "<tr class='row'>";
	    foreach($array as $value){
		$html .= "<td class='column'>" . $value . "</td>";
	    }
	    $html .= "</tr>";
	}

	$html .= "</table>";
	echo($html);
    }
}
?>
