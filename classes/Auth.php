<?php


class Auth
{
    /*
     * Get the current logged in user
     * */
    public static function user(): ?Client
    {
        Session::start();
        $user_id = Session::get("user_id");
        if ($user_id != null){
            // query the DB for the user
            $clients = Client::get_by_id($user_id);
            if ($clients != null){
                $client = $clients[0];
                return new Client(
                    $user_id,
                    $client["name"],
                    $client["family_name"],
                    $client["email"],
                    $client["password"],
                    $client["address"]
                );
            }
        }
        return null;
    }
    public static function isAuthorized(): bool {
        return self::user() == null ? false : true;
    }
}