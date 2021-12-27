<?php


class LoginController
{
    public function authenticate($email, $password, $is_client): bool {
        if ($is_client){
            $client = Client::get_by_email($email);

            if ($client != null){

                // client found
                $client = $client[0];
                // 1. check password
                $verified = password_verify($password, $client["password"]);
                if ($verified){
                    Session::start();
                    Session::set("user_id", $client["client_id"]);
                    Session::set("loggedIn", true);
                    return true;
                }
            }
        }
        return false;
    }
    public function logout(){
        Session::start();
        Session::forget();
    }
}