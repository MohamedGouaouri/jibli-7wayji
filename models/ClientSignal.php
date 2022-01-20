<?php


class ClientSignal extends Signal
{

    public static function all()
    {
        // TODO: Implement all() method.
        $db_result = DB::query("SELECT * FROM client_signals");
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
}