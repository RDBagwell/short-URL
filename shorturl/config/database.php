<?php
    require_once("new_config.php");
    
    class Database {

        public $connection;
        public $db;

        function __construct(){
            $this->db = $this->db_open_connection();
        }

        public function db_open_connection(){
            try {
                $this->connection = new PDO("mysql:host=".DB_HOST."; dbname=".DB_NAME.";",DB_USER, DB_PASSWORD);
            } catch (PDOException $ex) {
                echo "Connection error: " . $ex->getMessage();
            }
            return $this->connection;
        }

        public function query($sql) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        public function escape_string($string){
            $sanitized_strin = htmlspecialchars(strip_tags($string));
            return $sanitized_strin;
        }
        
        public function insert_id(){
            return $this->db->lastInsertId();
        }

        
    }  // End Database Class

    $database = new Database();