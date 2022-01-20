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
}