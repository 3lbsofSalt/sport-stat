<?php

require_once('db.php');

class auth {

    public $db;

    var $userData = array('username' => 'Guest',
                          'password' => 'Guest');
    
    function __construct() {
        $this->db = new db;
        $this->db->table = 'users';

        if(array_key_exists('user', $_SESSION)){
            if(array_key_exists('token',$_SESSION)){
                $check = $this->check($_SESSION['user']['username'], $_SESSION['token']);
                if(!check) {
                    $session_unset();
                    $this->guest();
                }
            } else {
                $this->guest();
            }            
        } else {
            $this->guest();
        }
    }

    function guest(){
        $_SESSION['user'] = $this->userData;
    }
    
    function register($raw) {
        $this->db->table ='users';
        $this->db->connect();
        if($raw) {
            $errors = $this->validate($raw);
            if(!$errors) {
                $data = array();
                $data['reg_date'] = time();
                $data['password'] = $this->hash_password($data['reg_date'], $raw['password']);
                $data['username'] = $raw['username'];
                
                $string = 'INSERT INTO users (username, pwd, reg_date) VALUES ("'. $data['username'] . '", "' . $data['password'] . '", ' . $data['reg_date']. ')';
                echo($string);
                $this->db->link->query($string);
                return $this->db->link;
            } else {
                foreach($errors as $key=>$value){
                    echo($errors . ' ' . $value . '<br />');
                }
                return $errors;
            }
            return false;
        }
        return false;
    }

    function login($username, $password) {
        $this->db->connect();
        $this->db->table = 'users';
        $string = "SELECT * FROM users WHERE username = '$username'";
        $res = $this->db->link->query($string);
        $res->fetch_assoc();
        if($res) {
            foreach($res as $key=>$array){
                $user = $array;
            }
            
            $tmp_pass = $this->hash_password($user['reg_date'], $password);
      
            if($tmp_pass == $user['pwd']){
                echo "true";
                $token = session_id();
                $user['token'] = $this->set_token($user['user_id'], $token, $user['pwd']);
                unset($user['pwd']);
                $SESSION['user'] = $user;
                return true;
            } else {
                $this->guest();
                return false;
            }
        }
        return false;
    }

    function check($username, $token) {
        $string = "SELECT * FROM users WHERE username = '$username'";
        $tmp = $this->db->link->query($string);
        if(!$tmp) {
            return false;
        } else if($tmp['token'] != $token){
            return false;
        }
        return true;
    }

    private function set_token($user_id, $session_id, $password) {
        $date = date('Ymd');
        $raw_token = $session_id.$date.substr($password, 7, 20);
        $data = array();
        $data['id'] = $user_id;
        $data['token'] = hash('sha256', $raw_token);
        $string = "UPDATE users SET (id, token) VALUES ('".$data['id']."', '".$data['token'].")";
        $this->db->link->query($string);
    }
    
    //Expect 'username', 'password', and 'password2' in $array
    
    private function validate($array){
        $errors = array(); //1 not submitted, 2 not unique, 3 not matching, 4 not long enough
        if(array_key_exists('password', $array) && array_key_exists('password2', $array)){
            if($array['password'] != $array['password2']){
                $errors['password'] = 3;
            }
            if(strlen($array['password']) < 8){
                $errors['password'] = 4;
            }
        } else {
            $errors['password'] = 1;
        }
        $unique = array(
            'username'=>$array['username']
        );
        
        $this->db->setTable('users');
        foreach($unique as $key=>$value){
            $this->db->connect();
            $string = ("SELECT * FROM $this->table WHERE username = " . $array['username']);
       
            $test = $this->db->link->query($string);
            if($test){
              $errors[$key] = 2;
            }
        }

        $req = array('username');
        foreach($req as $value){
            if(!array_key_exists($value, $array)){
                $errors[$value] = 1;
            }elseif(strlen($array[$value])< 1){
                var_dump(strlen($array[$value]));
                $errors[$value] = 1;
            }
        }
        if(sizeof($errors)){
            return $errors;
        } else {
            return false;
            }
    }

    private function hash_password($reg_date, $pass){
        $pre = $this->encode($reg_date);
        $pos = substr($reg_date, 5, 1);
        $post = $this->encode($reg_date * (substr($reg_date, $pos, 1)));
        //Inject password hash here
        if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH){
            $salt = '$2y$11$' . substr(md5($pre), 0, 22);
        }
        $password = crypt($pre.$pass.$post, $salt);
        return $password;
    }

    public $encoder = array(
        "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",
        "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
        0,2,3,4,5,6,7,8,9
    );

    private function encode($d){
        $k = $this->encoder;
        preg_match_all('/[1-9][0-9]|[0-9]/', $d, $a);
        $n ="";
        $o=count($k);
        foreach($a[0]as$i){
            if($i<$o){
                $n.=$k[$i];
            }else{
                $n.='1'.$k[$i-$o];
            }
        }
        return $n;
    }
}
?>
