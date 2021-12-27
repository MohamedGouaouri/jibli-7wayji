<?php


class AnnouncementController extends Controller{
    // get all announcements
    public function getAnnouncements(bool $isAuthenticated, int $number = null){
        // Check for permissions
        if (!$isAuthenticated){
            // show only
            return Announcement::only($number);
        }
        else{
            return Announcement::limit($number);
        }
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


}