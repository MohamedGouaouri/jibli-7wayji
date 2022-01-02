<?php


class ApplicationController
{
    public function add($transporter_id, $announcement_id){
        return TransporterApplication::add($transporter_id, $announcement_id);
    }

    public function exists($transporter_id, $announcement_id){
        return TransporterApplication::exists($transporter_id, $announcement_id);
    }


    /**
     *  Transporter application management
     */
    public function apply(){
        header("Content-Type: application/json");

        // check authorization first
        if (Auth::isAuthorizedTransporter()){

            // get announcement
            $announcement_id = $_POST["id"];

            $transporter_id = Auth::user()->getUserId();

            $controller = new ApplicationController();

            if ($controller->exists($transporter_id, $announcement_id)){
                // Prevent the transporter from multiple application
                echo json_encode(["success" => false, "message" => "You have already applied to this announcement"]);
                return;
            }

            $controller->add($transporter_id, $announcement_id);

            header("Content-Type: application/json");
            echo json_encode(["success" => true, "message" => "You application is sent"]);
        }

    }
}