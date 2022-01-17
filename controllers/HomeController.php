<?php


class HomeController extends Controller
{
    public function index(){
        // Checks if the client is authenticated
        if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
            $controller = new AnnouncementController();
            $result = $controller->getLimitedAnnouncements( 8);
            $user = Auth::user();
            View::make("index.html.twig",
                [
                    "title" => "VTC application",
                    "announcements" => $result,
                    "isAuthenticated" => true,
                    "user" => $user,
                    "is_transporter" => Auth::isAuthorizedTransporter(),
                    "wilayas" => (new WilayaController())->get_all()
                ]);
        }else{
            $controller = new AnnouncementController();
            $result = $controller->getLimitedAnnouncements(8);
            View::make("index.html.twig",
                [
                    "title" => "VTC application",
                    "announcements" => $result,
                    "isAuthenticated" => false,
                    "wilayas" => (new WilayaController())->get_all()
                ]);
        }

    }


    /** Searches for announcements based on the start and the end points
     * @param $from
     * @param $to
     */
    public function search($from, $to){
        $controller = new AnnouncementController();
        $result = $controller->getAnnouncementByCriteria($from, $to);
        header("Content-Type: application/json");
        echo json_encode(["success" => true, "announcements" => $result]);
    }

    public function presentation()
    {
        View::make("presentation.html.twig");
    }
}