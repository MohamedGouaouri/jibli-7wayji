<?php


class TransporterApplication extends Model
{

    public static function all()
    {
        // TODO: Implement all() method.
    }

    public static function limit($rows)
    {
        // TODO: Implement limit() method.
    }

    public static function add($transporter_id, $announcement_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO transporter_applications (`transporter_id`, `announcement_id`) VALUES (:transporter_id, :announcement_id)");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        if ($stmt->execute()){
            return true;
        }
        return false;
    }
    public static function exists($transporter_id, $announcement_id): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM transporter_applications WHERE transporter_id = :transporter_id AND announcement_id  = :announcement_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        if ($stmt->execute()){
            if (count($stmt->fetchAll()) > 0){
                return true;
            }
        }
        return false;
    }
}