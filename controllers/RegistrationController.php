<?php


class RegistrationController
{


    public function index(){
        $wilayas = (new WilayaController())->get_all();
        View::make("auth/register.html.twig", [
            "title" => "VTC application",
            "wilayas" => $wilayas
        ]);
    }

    public function register($name, $family_name, $email, $phone_number, $password, $address, $is_client, $wilayas): bool {
        if ($is_client){
            $added = User::add($name, $family_name, $phone_number, $email, password_hash($password, PASSWORD_BCRYPT), $address);
            return $added;
        }
        else{
            Transporter::add($name, $family_name, $phone_number, $email, password_hash($password, PASSWORD_BCRYPT), $address);
            // register wilayas for a transporter
            $transporter = Transporter::get_by_email($email);
            foreach ($wilayas as $wilaya){
                Transporter::add_wilaya($transporter->getUserId(), $wilaya);
            }
            return true;
        }
    }
}