<?php


class Client extends Model{
//    private string $name;
//    private string $family_name;
//    private string $email;
//    private string $password;
//    private string $address;
    private static string $table_name = "clients";

    public static function all()
    {
        // TODO: Implement all() method.
//        return DB::query("SELECT * FROM " . self::$table_name);
    }

    public static function limit($rows)
    {
        // TODO: Implement limit() method.
//        return DB::query("SELECT * FROM " . self::$table_name . " LIMIT " . $rows);
    }
}