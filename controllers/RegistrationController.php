<?php


class RegistrationController
{
    public function register($name, $family_name, $email, $password, $address, $is_client, $wilayas): bool {
        if ($is_client){

            $added = Client::add($name, $family_name, $email, password_hash($password, PASSWORD_BCRYPT), $address);

            return $added;
        }
        else{
            Transporter::add($name, $family_name, $email, password_hash($password, PASSWORD_BCRYPT));
            // register wilayas for a transporter
            $transporter_id = Transporter::get_by_email($email)[0]["transporter_id"];
            foreach ($wilayas as $wilaya){
                Transporter::add_wilaya($transporter_id, $wilaya);
            }
            return true;
        }
    }
}