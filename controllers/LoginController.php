<?php


class LoginController
{
    public function authenticate($email, $password, $is_client): bool {

        if ($is_client){

            $client = Client::get_by_email($email);
            if ($client != null){

                // 1. check password
                $verified = password_verify($password, $client->getPassword());
                if ($verified){
                    Session::start();
                    Session::set("user_id", $client->getClientId());
                    Session::set("logged_in", true);
                    Session::set("is_client", true);
                    return true;
                }
            }
        }
        else{

            $transporter = Transporter::get_by_email($email);

            if ($transporter != null){


                // 1. check password
                $verified = password_verify($password, $transporter->getPassword());
                $validated = $transporter->isValidated() == 1;
                if ($verified && $validated){
                    Session::start();
                    Session::set("user_id", $transporter->getTransporterId());
                    Session::set("loggedIn", true);
                    Session::set("is_client", false);
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