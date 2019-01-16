<?php

class Url extends Db_object{
    protected static $db_table = "url";
    protected static $db_table_fields = array('url', 'alias');
    public $id;
    public $url;
    public $alias;
    public $click;

    public function setURL(){
        $isURL = $this->checkURL($this->url);
        if($isURL){
            
            if($this->save()){
                return "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}{$this->alias}";
            } 
            
        } else {
            return false;
        }
    }

    public function getURL($alias){
        $where = " WHERE alias = '{$alias}'";
        $result =  $this->get_all_where($where);
        if(!$result){
            return false;
        } else{
            return $result;
        }
    }

    public function generateAlias($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function checkURL($url){
        $result = filter_var($url, FILTER_VALIDATE_URL) ? true : false;
        return $result;
    }

}