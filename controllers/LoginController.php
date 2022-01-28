<?php


class LoginController
{
    public function adminAuthenticate(){
        Session::start();
        Session::set("logged_in", true);
        Session::set("is_admin", true);
    }
    public function authenticate($email, $password, $is_client): bool {

        if ($is_client){
            $client = User::get_by_email($email);
            if ($client != null){
                $verified = password_verify($password, $client->getPassword());
                echo $client->getUserId();
                if ($verified){
                    Session::start();
                    Session::set("user_id", $client->getUserId());
                    Session::set("logged_in", true);
                    Session::set("is_client", true);
                    Route::router("vtc", "index.php");
                    return true;
                }
            }
        }
        else{
            $transporter = Transporter::get_by_email($email);
            if ($transporter != null){
                $verified = password_verify($password, $transporter->getPassword());
                $validated = $transporter->isValidated() == 1;
                if ($verified && $validated){
                    Session::start();
                    Session::set("user_id", $transporter->getUserId());
                    Session::set("loggedIn", true);
                    Session::set("is_client", false);
                    Route::router("vtc", "index.php");
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

    public function adminLogout()
    {
        Session::start();
        Session::delete("logged_in");
        Session::delete("is_admin");
        Route::router("vtc", "admin");
    }
}