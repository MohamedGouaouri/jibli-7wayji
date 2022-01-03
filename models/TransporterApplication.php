<?php


class TransporterApplication extends Model implements JsonSerializable
{
    // model members
    private Transporter $transporter;
    private Announcement $announcement;

    /**
     * TransporterApplication constructor.
     * @param Transporter $transporter
     * @param Announcement $announcement
     */


    public function __construct(Transporter $transporter, Announcement $announcement)
    {
        $this->transporter = $transporter;
        $this->announcement = $announcement;
    }


    public static function all()
    {
        // TODO: Implement all() method.
    }


    public static function add($transporter_id, $announcement_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO transporter_applications (`transporter_id`, `announcement_id`) VALUES (:transporter_id, :announcement_id)");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        try {
            if ($stmt->execute()){
                return true;
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
        return false;
    }
    public static function exists($transporter_id, $announcement_id): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM transporter_applications WHERE transporter_id = :transporter_id AND announcement_id  = :announcement_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        try {
            if ($stmt->execute()){
                if (count($stmt->fetchAll()) > 0){
                    return true;
                }
            }

        }catch (Exception $e){
            echo $e->getMessage();
        }
        return false;
    }

    public static function getAllOfTransporter($transporter_id): array
    {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM transporter_applications WHERE transporter_id = :transporter_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $applications = array();
        try {
            if ($stmt->execute()){
                $applications_db = $stmt->fetchAll();

                foreach ($applications_db as $application){
                    array_push($applications, new TransporterApplication(
                        Transporter::get_by_id($transporter_id),
                        Announcement::byId($application["announcement_id"])
                    ));
                }
                return $applications;
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
        return $applications;
    }
    public static function getAllOfAnnouncement($announcement_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM transporter_applications WHERE announcement_id = :announcement_id");
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        $applications = array();
        try {
            if ($stmt->execute()){
                $applications_db = $stmt->fetchAll();

                foreach ($applications_db as $application){
                    array_push($applications, new TransporterApplication(
                        Transporter::get_by_id($application["transporter_id"]),
                        Announcement::byId($announcement_id)
                    ));
                }
                return $applications;
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
        return $applications;
    }


    /** Delete application
     * @param $transporter_id
     * @param $announcement_id
     * @return bool
     */
    public static function delete($transporter_id, $announcement_id): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("DELETE FROM transporter_applications WHERE transporter_id = :transporter_id AND announcement_id = :announcement_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        try {
            if (self::exists($transporter_id, $announcement_id)){
                if ($stmt->execute()){
                    return true;
                }
            }

        }catch (Exception $e){

        }
        return false;
    }

    /**
     * @return Transporter
     */
    public function getTransporter(): Transporter
    {
        return $this->transporter;
    }

    /**
     * @return Announcement
     */
    public function getAnnouncement(): Announcement
    {
        return $this->announcement;
    }



    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return array_merge(
            $this->transporter->jsonSerialize(),
            $this->announcement->jsonSerialize()
        );
    }
}