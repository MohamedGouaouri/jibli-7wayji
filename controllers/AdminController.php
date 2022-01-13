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
            "transporters" => Transporter::all()
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
            "announcements" => Announcement::all(false)
        ]);
    }
}