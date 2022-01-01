<?php


class ClientController
{
    public function index(){
        if (Auth::isAuthorizedClient()){
            $client = Auth::user();
            $controller = new AnnouncementController();
            $result = $controller->getLimitedAnnouncements(8);
            View::make("client/index.html.twig", [
                "title" => "VTC client portal",
                "loggedIn" => true,
                "client" => $client,
                "announcements" => $result,
                "wilayas" => (new WilayaController())->get_all()
            ]);
            return;
        }
        Route::router("vtc", "login");
    }

    public function profile(){
        if (Auth::isAuthorizedClient()){
            $client = Auth::user();
            View::make("client/profile.html.twig", [
                "isAuthenticated" => true,
                "user" => $client
            ]);
            return;
        }
        Route::router("vtc", "login");
    }
}