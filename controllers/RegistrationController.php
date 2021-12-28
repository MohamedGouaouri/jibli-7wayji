<?php


class RegistrationController
{
    public function register($name, $family_name, $email, $password, $address, $is_client): bool {
        if ($is_client){

            $added = Client::add($name, $family_name, $email, password_hash($password, PASSWORD_BCRYPT), $address);

            return $added;
        }
        else{

           $added = Transporter::add($name, $family_name, $email, password_hash($password, PASSWORD_BCRYPT));
            return $added;
        }
    }
}