<?php

namespace iutnc\netvod\db;

use PDO;
use PDOException;
class ConnectionFactory
{
    public static $db = null;
    public static $config = [];

    public static function setConfig($iniFile): void
    {
        self::$config = parse_ini_file($iniFile);
    }

    public static function makeConnection()
    {
        if (self::$db === null) {
            try {
                $dsn = self::$config['driver'] .
                    ':host=' . self::$config['host'] .
                    ';dbname=' . self::$config['dataBase'];
                self::$db = new PDO($dsn, self::$config['username'], self::$config['password'], [
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                ]);
                self::$db->prepare("SET NAMES 'utf8'")->execute();
            } catch (PDOException $e) {
                print("Erreur de connexion à la base de données");
                print($e->getMessage());
                die();
            }
        }
        return self::$db;
    }

}