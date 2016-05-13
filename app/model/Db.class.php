<?php
class Db {
    private static $_instance = null;
    private static $connection = null;
    
	//Creates the object
    private function __construct() {
        $host = DB_HOST;
        $dbName = DB_DATABASE;
        $dbInfo = "mysql:host={$host};dbname={$dbName}";
        $dbUser = DB_USER;
        $dbPassword = DB_PASS;

        try {
            // try to create a db connection with a PDO (php data object)
            self::$connection = new PDO($dbInfo, $dbUser, $dbPassword);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            die("<h1>Connection failed</h1><p>{$ex}</p>");
        }
    }
    
	//Returns an instance of the class
	public static function instance() {
		if(self::$_instance === null) {
			self::$_instance = new Db();
		}
		return self::$_instance;
	}
    
	//Returns the connection
    public static function connection() {
		if(self::$_instance === null) {
			self::$_instance = new Db();
		}
		return self::$connection;
    }
}
