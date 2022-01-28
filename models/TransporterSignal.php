<?php


class TransporterSignal extends Signal
{

    public static function all()
    {
        $db_result = DB::query("SELECT * FROM transporter_signals");
        $signals = array();
        foreach ($db_result as $value){
            array_push($signals, new ClientSignal(
                Transporter::get_by_id($value["transporter_id"]),
                User::get_by_id($value["user_id"]),
                $value["message"]
            ));
        }
        return $signals;
    }

    public static function allOf(int $id)
    {
        // TODO: Implement allOf() method.
    }

    public static function add($client_id, $transporter_id, $message)
    {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO transporter_signals (`transporter_id`, `user_id`, `message`) VALUES (:transporter_id, :client_id, :message)");
        $stmt->bindValue(":client_id", $client_id, PDO::PARAM_INT);
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":message", $message);
        try {
            return $stmt->execute();
        }catch (Exception $e){

        }
        return false;
    }
}