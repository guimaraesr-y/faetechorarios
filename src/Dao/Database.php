<?php

namespace App\Dao;

class Database
{
    private static $db_name;
    private static $db_user;
    private static $db_pass;

    public static function init()
    {
        self::$db_name = getenv('APP_ENV') == 'production' ? getenv('DB_NAME') : getenv('DB_NAME_DEV');
        self::$db_user = getenv('APP_ENV') == 'production' ? getenv('DB_USER') : getenv('DB_USER_DEV');
        self::$db_pass = getenv('APP_ENV') == 'production' ? getenv('DB_PASS') : getenv('DB_PASS_DEV');
        self::createDatabase();
    }

    public static function getConnection()
    {
        global $db_name, $db_user, $db_pass;

        if (empty(self::$db_name) || empty(self::$db_user)) {
            self::init(); // Certifica-se de que as variáveis estejam inicializadas
        }

        return new \PDO("mysql:host=localhost;dbname=" . self::$db_name, self::$db_user, self::$db_pass, [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }

    public static function createDatabase()
    {
        $con = self::getConnection();
        // implement
        // $con->exec($sql);
    }
}