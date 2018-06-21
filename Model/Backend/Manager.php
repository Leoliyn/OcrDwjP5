<?php
namespace Backend;//namespace OpenClassrooms\DWJP5\Commun\Model;
//require_once("Model/Backend/Config.php");

class Manager extends Config {
           
    protected function dbConnect() {
  
               $db = new \PDO("mysql:host=".self::DBHOST.";dbname=".self::DBNAME.";charset=".self::CHARSET,self::DBUSER,self::DBPASS);
               $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
               return $db;
    }
        
}