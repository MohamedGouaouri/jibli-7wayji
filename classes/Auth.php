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
                return User::get_by_id($user_id);
            }else{
                return Transporter::get_by_id($user_id);
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

    public static function isAdmin(): bool {
        Session::start();
        return Session::exists("is_admin");
    }
}