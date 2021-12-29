<?php


class CertificationDemand extends Model
{

    public static function all()
    {
        // TODO: Implement all() method.
    }

    public static function limit($rows)
    {
        // TODO: Implement limit() method.
    }

    public static function save_certification_demand($transporter_id): bool {
        $pdo = DB::connect();
        try {
            $stmt = $pdo->prepare("INSERT INTO certification_demands (`transporter_id`) VALUES (:transporter_id)");
            $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
            if ($stmt->execute()){
                return true;
            }
            return  false;
        }catch (Exception $e){
            return false;
        }
    }
}