<?php


class AnnouncementController extends Controller{

    public function index(){
        if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
            $user = Auth::user();

            $result = $this->getAllOfUser($user->getUserId(), Auth::isAuthorizedTransporter());
            View::make("client/announcements.html.twig", [
                "title" => "VTC client portal",
                "isAuthenticated" => true,
                "user" => $user,
                "announcements" => $result,
            ]);
            return;
        }
        Route::router("vtc", "login");
    }


    public function search($from, $to){
        if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
            $controller = new AnnouncementController();
            $result = $controller->getAnnouncementByCriteria($from, $to);
            header("Content-Type: application/json");
            echo json_encode(["success" => true, "announcements" => $result]);
        }
    }


    public function show($id){
        if (Auth::isAuthorizedClient() || Auth::isAuthorizedTransporter()){
            $user = Auth::user();
            View::make("announcements/details.html.twig", [
                "isClient" => Session::get("is_client"),
                "user" => $user,
                "announcement" => $this->getById($id)[0],
            ]);
            return;
        }
    }

    // get  announcements
    public function getLimitedAnnouncements(int $number){
        return Announcement::limit($number);
    }

    public function getAllAnnouncements(): array {
        return Announcement::all();
    }

    public function getAllOfUser($user_id, $is_transporter){

        return Announcement::allOfUser($user_id, $is_transporter);
    }

    public function getById(int $id): array {
        return Announcement::byId($id);
    }


    /** Gets all announcements using the search criteria start_point - end_point
     * @param int $from
     * @param int $to
     * @return array|null
     */
    public function getAnnouncementByCriteria(int $from, int $to){
        return Announcement::byCriteria($from, $to);
    }


    public function addNewAnnouncement($start_point,
                                       $end_point,
                                       $type,
                                       $weight,
                                       $volume,
                                       $message): bool {


        // get user id from session
        $user_id = 1 ;
        return Announcement::add(
            $user_id,
            $start_point,
            $end_point,
            $type,
            $weight,
            $volume,
            $message);
    }


    public function delete($announcement_id, $client_id): bool {
        return Announcement::delete($announcement_id);
    }

}