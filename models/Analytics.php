<?php


class Analytics
{
//    private int $nb_users;

    public static function nbUsers(): int{
        $result = DB::query("SELECT COUNT(*) as nb_users FROM users WHERE  banned = FALSE");
        return $result[0]["nb_users"];
    }

    public static function nbTransporters(): int{
        $result = DB::query("SELECT COUNT(*) as nb_users FROM transporters WHERE validated = TRUE");
        return $result[0]["nb_users"];
    }

    public static function nbBannedUsers(){
        $result = DB::query("SELECT COUNT(*) as nb_users FROM users WHERE banned = TRUE;");
        return $result[0]["nb_users"];
    }

    public static function nbValidatedAnnouncements()
    {
        $result = DB::query("SELECT COUNT(*) as nb FROM announcements WHERE validated = TRUE AND archived = FALSE;");
        return $result[0]["nb"];
    }
    public static function nbNonValidatedAnnouncements()
    {
        $result = DB::query("SELECT COUNT(*) AS nb FROM announcements WHERE validated = FALSE AND archived = FALSE;");
        return $result[0]["nb"];
    }

    public static function nbArchivedAnnouncements()
    {
        $result = DB::query("SELECT COUNT(*) AS nb FROM announcements WHERE validated = TRUE AND archived = TRUE;");
        return $result[0]["nb"];
    }



}