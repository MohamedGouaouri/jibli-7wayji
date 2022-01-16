<?php


class AdminController
{


    public function index()
    {
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
            "announcements" => Announcement::all(false),
            "current_transports" => Transaction::getAllRunningTransports(),
            "archived_transactions" => Transaction::getArchivedTransports()
        ]);
    }

    public function pricing_index()
    {
        View::make("admin/pricing.html.twig", [
            "pricing" => Price::all()
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
}