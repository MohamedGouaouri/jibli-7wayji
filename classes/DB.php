<?php
require "config/db.php";

class DB{
    private static string $host = HOST;
    private static string $dbname = DBNAME;
    private static string $user = USERNAME;
    private static string $password = PASSWORD;
    public static function connect() {
        $pdo = new PDO("mysql:host=".self::$host.";dbname=".self::$dbname.";charset=utf8", self::$user, self::$password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function query($query, $params = array()) {

        $pdo = self::connect();
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}