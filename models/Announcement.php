<?php


class Announcement extends Model
{
    private static string $table_name = "announcements";

    public static function all()
    {
        // TODO: Implement all() method.
        return DB::query("SELECT * FROM " . self::$table_name);
    }

    public static function limit($rows)
    {
        // TODO: Implement limit() method.
        return DB::query("SELECT * FROM " . self::$table_name . " LIMIT " . $rows);
    }

    public static function only(int $limit){
        return DB::query("SELECT announcement_id AS id, start_point, end_point, type, weight, volume FROM " . self::$table_name . " LIMIT ". $limit);
    }

    public static function byCriteria(int $start_point, int $end_point){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT announcement_id AS id, start_point, end_point, type, weight, volume FROM " . self::$table_name . " WHERE start_point = :start_point AND end_point = :end_point");
        $stmt->bindValue(":start_point", $start_point, PDO::PARAM_INT);
        $stmt->bindValue(":end_point", $end_point, PDO::PARAM_INT);
        if ($stmt->execute()){
            return $stmt->fetchAll();
        }
        return  null;
    }
}