<?php


class AdminController
{


    public function index()
    {
//        (new LoginController())->adminAuthenticate();
        View::make("admin/admin.html.twig");
    }

    public function clients_index()
    {
        View::make("admin/clients.html.twig", [
            "clients" => User::allClients(),
            "banned_clients" => User::allBanned()
        ]);
    }

    public function banned_users()
    {
        View::make("admin/banned.html.twig", [
            "banned_users" => User::allBanned()
        ]);
    }

    public function ban_user($user_id)
    {
        User::banUser($user_id);
        header("Content-Type: application/json");
        echo json_encode(["success" => true, "message" => "User banned successfully"]);
    }

    public function unban_user($user_id)
    {
        User::unbanUser($user_id);
        header("Content-Type: application/json");
        echo json_encode(["success" => true, "message" => "User unbanned successfully"]);
    }

    public function transporters_index()
    {
        View::make("admin/transporters.html.twig",[
            "transporters" => Transporter::all(),
            "certification_demands" => CertificationDemand::all()
        ]);
    }

    public function validate_transporter($transporter_id)
    {
        Transporter::validate($transporter_id);
        header("Content-Type: application/json");
        echo json_encode(["success" => true, "message" => "La demande de transport a ete confirme"]);
    }

    public function announcements_index()
    {
        View::make("admin/announcements.html.twig", [
            "announcements" => Announcement::all(),
            "current_transports" => Transaction::getAllRunningTransports(),
            "archived_transactions" => Transaction::getArchivedTransports()
        ]);
    }

    public function pricing_index()
    {
        View::make("admin/pricing.html.twig", [
            "pricing" => Price::all(),
            "wilayas" => (new WilayaController())->get_all()
        ]);
    }

    public function analytics_index()
    {
        View::make("admin/analytics.html.twig");
    }

    public function api()
    {
        $controller = new AnalyticsController();
        header("Content-Type: application/json");
        echo json_encode([
            "users" => [
                "nb_users" => $controller->nbUsers(),
                "nb_transporters" => $controller->nbTransporters(),
                "nb_banned" => $controller->nbBannedUsers(),

            ],
            "announcements" => [
                "nb_validated" => $controller->nbValidatedAnnouncements(),
                "nb_archived" => $controller->nbArchivedAnnouncements(),
                "nb_non_validated" => $controller->nbNonValidatedAnnouncements()
            ]
        ]);
    }

    public function update_pricing($from, $to, $price)
    {
        header("Content-Type: application/json");
        // check if is_admin
        if (Price::exists($from, $to)){
            // just update
            $updated = Price::updatePricing($from, $to, $price);
            if ($updated){
                echo json_encode([
                    "success" => true,
                    "message" => "Le prix a ete modifier avec success"
                ]);
            }
            else{
                echo json_encode([
                    "success" => false,
//                    "message" => "Le prix a ete modifier avec success"
                ]);
            }
        }else{
            $added = Price::addPricing($from, $to, $price);
            if ($added){
                echo json_encode([
                    "success" => true,
                    "message" => "Le prix a ete ajouter avec success"
                ]);
            }
            else{
                echo json_encode([
                    "success" => true,
//                    "message" => "Le prix a ete modifier avec success"
                ]);
            }
        }
    }

    public function post_news($title, $synopsis, $content){
        $added = News::add($title, $synopsis, $content, "");
        header("Content-Type: application/json");
        if ($added){
            echo json_encode([
                "success" => true,
                "message" => "The post has been added"
            ]);
        }else{
            echo json_encode([
                "success" => false,
                "message" => "We can't add this post"
            ]);
        }
    }

    public function delete_news($id)
    {
        $deleted = (new NewsController())->delete($id);
        header("Content-Type: application/json");
        if ($deleted){
            echo json_encode([
                "success" => true,
                "message" => "News entry deleted"
            ]);
        }
        else{
            echo json_encode([
                "success" => false,
                "message" => "Error on delete"
            ]);
        }
    }

    public function loginPage()
    {
        View::make("admin/login.php.twig");
    }
}