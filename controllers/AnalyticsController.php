<?php


class AnalyticsController
{
    public function nbUsers(){
        return Analytics::nbUsers();
    }
    public function nbTransporters(){
        return Analytics::nbTransporters();
    }
    public function nbBannedUsers(){
        return Analytics::nbBannedUsers();
    }

    public function nbValidatedAnnouncements(){
        return Analytics::nbValidatedAnnouncements();
    }

    public function nbNonValidatedAnnouncements(){
        return Analytics::nbNonValidatedAnnouncements();
    }

    public function nbArchivedAnnouncements(){
        return Analytics::nbArchivedAnnouncements();
    }

}