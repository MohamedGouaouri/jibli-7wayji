<?php


class Auth
{
    /*
     * Get the current logged in user
     * */
    public static function user()
    {
        Session::start();
        $user_id = Session::get("user_id");

        if ($user_id != null){

            if (Session::get("is_client") == true){

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
            }else{
                $transporters = Transporter::get_by_id($user_id);
                if ($transporters != null){
                    $transporter = $transporters[0];
                    $t = new Transporter(
                        $transporter["name"],
                        $transporter["family_name"],
                        $transporter["email"],
                        $transporter["is_certified"],
                        $transporter["status"],
                        $transporter["inventory"],
                    );

                    return $t;
                }
            }
        }

        return null;
    }
    public static function isAuthorizedClient(): bool {

        return (self::user() != null) && (Session::get("is_client") == true);
    }
    public static function isAuthorizedTransporter(): bool {

        return (self::user() != null) && (Session::get("is_client") == false);
    }
}