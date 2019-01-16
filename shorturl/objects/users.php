<?php

class Users extends Db_object{
    protected static $db_table = "users";
    protected static $db_table_fields = array('firstName', 'lastName', 'username', 'email', 'password');
   
    public $id;
    public $firstName;
    public $lastName;
    public $username;
    public $email;
    public $password;

    public static function hash_password($password){
        return crypt($password, '$5$$O1TnnkIBV');
    }

    public static function verify_user($username, $password){
    	global $database;
        $username = $database->escape_string($username);
        $password = $database->escape_string($password);
        $passwordCrypt = self::hash_password($password);
        $sql = "SELECT * FROM ".self::$db_table." WHERE ";
        $sql .= "username = '{$username}' ";
        $sql .= "AND password = '{$passwordCrypt}';";
        $result_array = self::set_query($sql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public function dose_user_exsist(){
        $sql = "SELECT * FROM ".self::$db_table." WHERE ";
        $sql .= "username = '{$this->username}' ";
        $result_array = self::set_query($sql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

}