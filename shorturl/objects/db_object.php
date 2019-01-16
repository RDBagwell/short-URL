<?php
/**
*  Using Late Static Bindings ie static:: instead of self:: as the class is extended by other classes
*/
class Db_object {

    //Sets the vars up in the class
    public static function instantiation($record){
        // using get_called_class() to call the class
        $calling_class =  get_called_class();

        $obj = new $calling_class;
        foreach ($record as $attributes => $value) {
            //Check if the object has attributes
            if ($obj->has_attribute($attributes)) {
                    $obj->$attributes = $value;
            }
        }
        return $obj;
    }

    //Checks the array for a key(attribute)
    public function has_attribute($attributes){
        // Gets the vars in the class
        $object_properties = get_object_vars($this);
        return array_key_exists($attributes, $object_properties);
    }


    // Gets $db_table_fields and puts it in an array
    public function properties(){

            $properties = array();
            foreach (static::$db_table_fields as $db_field) {

                    if (property_exists($this, $db_field)) {
                            $properties[$db_field] = $this->$db_field;
                    }
            }
            return $properties;
    }

    // Gets $db_table_fields uisng properties() and clean values.
    public function clean_properties(){
        global $database;

        $clean_properties = array();

        foreach ($this->properties() as $key => $value) {
            $clean_properties[$key] = $database->escape_string($value);
        }
        return $clean_properties;

    }
    
    public static function set_query($sql){
        global $database;
        $result_set = $database->query($sql);
        $obj_array = array();
        while ($row = $result_set->fetch(PDO::FETCH_ASSOC)) {
            $obj_array[] = static::instantiation($row);
        }
        return $obj_array;
    }

    public static function get_all(){
        return static::set_query("SELECT * FROM ".static::$db_table);
    }

    public static function get_all_where($where){
        $result_array = static::set_query("SELECT * FROM ".static::$db_table.$where);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function get_by_id($id){
        $result_array = static::set_query("SELECT * FROM ".static::$db_table." WHERE id = $id");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public function save(){
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create(){
        global $database;

        $properties = $this->clean_properties();

        $sql  = "INSERT INTO ".static::$db_table." (".implode(", ", array_keys($properties)).")";
        $sql .= " VALUES ('";
        $sql .= implode("', '", array_values($properties)) ;
        $sql .="');";

        if ($database->query($sql)) {
            $this->id = $database->insert_id();
            return true;
        } else {
            return false;
        }

    }

    public function update(){
        global $database;
        $properties = $this->clean_properties();
        $properties_pairs = array();

        foreach ($properties as $key => $value) {
            $properties_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE ".static::$db_table." SET ";
        $sql .= implode(",", $properties_pairs);
        $sql .=" WHERE id = ";
        $sql .= $database->escape_string($this->id).";";
        $num = $database->query($sql)->rowCount();
        return ($num == 1) ? true : false;
    }

    public function delete(){
        global $database;
        $sql = "DELETE FROM ".static::$db_table;
        $sql .=" WHERE id = ";
        $sql .= $database->escape_string($this->id).";";
        $row = $database->query($sql)->rowCount();
        return ($row == 1) ? true : false;
    }
    
    public static function count_all() {
        global $database;
        $sql = "SELECT COUNT(*) FROM ".static::$db_table;
        $result_set = $database->query($sql);
        $row = $result_set->fetch(PDO::FETCH_ASSOC);
        return array_shift($row);
    }

}
