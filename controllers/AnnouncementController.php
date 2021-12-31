<?php


class AnnouncementController extends Controller{
    // get  announcements
    public function getLimitedAnnouncements(int $number){
        echo "hello";
        return Announcement::limit($number);
    }

    public function getAllAnnouncements(): array {
        return Announcement::all();
    }

    public function getAllOfClient($client_id){

        return Announcement::allOfClient($client_id);
    }

    public function getById(int $id): array {
        return Announcement::byId($id);
    }

    public function getAnnouncementByCriteria(int $from, int $to, bool $isAuthenticated){
        if (!$isAuthenticated){
            return Announcement::byCriteria($from, $to);
        }else{
            return Announcement::byCriteria($from, $to);
        }
    }


    public function addNewAnnouncement($start_point,
                                       $end_point,
                                       $type,
                                       $weight,
                                       $volume,
                                       $message): bool {


        $client_id = Auth::user()->getClientId();

        return Announcement::add(
            $client_id,
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