<?php


class ApplicationController
{
    /**
     * @param $transporter_id
     * @param $announcement_id
     * @return bool
     */
    public function add($transporter_id, $announcement_id){
        return TransporterApplication::add($transporter_id, $announcement_id);
    }

    /**
     * @param $transporter_id
     * @param $announcement_id
     * @return bool
     */
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

            if (!ClientDemand::exists($transporter_id, $announcement_id)){
                if ($controller->exists($transporter_id, $announcement_id)){
                    // Prevent the transporter from doing multiple application

                    echo json_encode(["success" => false, "message" => "You have already applied to this announcement"]);
                    return;
                }

                $controller->add($transporter_id, $announcement_id);

                header("Content-Type: application/json");
                echo json_encode(["success" => true, "message" => "You application is sent"]);

            }else{
                header("Content-Type: application/json");
                echo json_encode(["success" => false, "message" => "Ce client vous a deja demande de faire un transport"]);

            }
        }

    }


    /**
     * Shows the view of all application notifications from a transporter
     */
    public function index(){
        if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
            $user = Auth::user();
            // iterate through each announcement and get the list of applications
            $announcements = Announcement::allOfUser($user->getUserId(), Auth::isAuthorizedTransporter());
            $all_applications = array();
            foreach ($announcements as $announcement){
                // get the list of applications
                $applications_per_announcement = TransporterApplication::getAllOfAnnouncement($announcement->getAnnouncementId());
                $all_applications = array_merge($all_applications, $applications_per_announcement);
            }
            View::make("user/notifications.html.twig", [
                "isAuthenticated" => true,
                "user" => Auth::user(),
                "applications" => $all_applications
            ]);
        }
    }


    /**
     * Refuse transporter application
     * @param $transporter_id
     * @param $announcement_id
     */
    public function refuse($transporter_id, $announcement_id){
        if (Auth::isAuthorizedClient()){
            header("Content-Type: application/json");
            $deleted = TransporterApplication::delete($transporter_id, $announcement_id);
            if ($deleted){
                header("Content-Type: application/json");
                echo json_encode(["success" => true, "message" => "The application is refused"]);
            }
            header("Content-Type: application/json");
            echo json_encode(["success" => false, "message" => "An error occured"]);
        }
    }

    public function client_demand($announcement_id, $transporter_id){
        if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
            $added = ClientDemand::add($transporter_id, $announcement_id);
            header("Content-Type: application/json");
            if ($added){
                echo json_encode(["success" => true, "message" => "Votre demande de transport a ete envoye"]);
            }
            else{
                echo json_encode(["success" => false, "message" => "Vous pouvez pas refaire la demande de transport"]);
            }

        }
    }
}