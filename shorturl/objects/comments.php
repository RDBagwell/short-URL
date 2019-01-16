<?php

class Comments extends Db_object{
    protected static $db_table = "comments";
    protected static $db_table_fields = array('uid', 'body', 'commenter');
   
    public $id;
    public $uid;
    public $body;
    public $commenter;

    public static function getLatestCommentsByLimit($limit = 5){
    	$sql = "SELECT * FROM ".self::$db_table. " ORDER BY id DESC LIMIT ".$limit;
    	$result_array = self::set_query($sql);
        return $result_array;
    }
    public static function getLatestComments(){
    	$sql = "SELECT * FROM ".self::$db_table. " ORDER BY id DESC ";
    	$result_array = self::set_query($sql);
        return $result_array;
    }
}