<?php

final class myPDO {

    private static $_PDOInstance   = null;
    private static $_DSN           = null;
    private static $_username      = null;
    private static $_password      = null;
    private static $_driverOptions = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ) ;


    private function __construct() {
        //Never use it :D
    }

    //Get the unique instance of myPDO
    public static function getInstance() {
        if (is_null(self::$_PDOInstance)) {
            if (self::hasConfiguration()) {
                self::$_PDOInstance = new PDO(self::$_DSN, self::$_username, self::$_password, self::$_driverOptions);
            }
            else {
                throw new Exception(__CLASS__ . ": Configuration not set");
            }
        }
        return self::$_PDOInstance;
    }

    public static function setConfiguration($dsn, $username='', $password='', array $driver_options=array()) {
        self::$_DSN           = $dsn;
        self::$_username      = $username;
        self::$_password      = $password;
        self::$_driverOptions = $driver_options + self::$_driverOptions;
    }

    private static function hasConfiguration() {
        return self::$_DSN !== null;
    }
}
