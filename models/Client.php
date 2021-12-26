<?php


class Client extends Model{

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
    public static function add($name, $family_name, $email, $password, $address): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO clients (`name`, `family_name`, `email`, `password`, `address`) VALUES (:name, :family_name, :email, :password, :address)");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":family_name", $family_name, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":address", $address, PDO::PARAM_STR);


        if ($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public static function get_by_email($email){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = :email");
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        if ($stmt->execute()){
            return $stmt->fetchAll();
        }
        return null;
    }
}