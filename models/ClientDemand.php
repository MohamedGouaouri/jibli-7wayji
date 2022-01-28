<?php


class ClientDemand implements JsonSerializable
{

    private Transporter $transporter;
    private Announcement $announcement;

    /**
     * ClientDemand constructor.
     * @param Transporter $transporter
     * @param Announcement $announcement
     */
    public function __construct(Transporter $transporter, Announcement $announcement)
    {
        $this->transporter = $transporter;
        $this->announcement = $announcement;
    }

    public static function add($transporter_id, $announcement_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO client_demands (`transporter_id`, `announcement_id`) VALUES (:transporter_id, :announcement_id)");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        try {
            if ($stmt->execute()){
                return true;
            }
        }catch (Exception $e){
        }
        return false;
    }
    public static function exists($transporter_id, $announcement_id): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM client_demands WHERE transporter_id = :transporter_id AND announcement_id  = :announcement_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        try {
            if ($stmt->execute()){
                if (count($stmt->fetchAll()) > 0){
                    return true;
                }
            }

        }catch (Exception $e){
        }
        return false;
    }

    public static function all()
    {
        $result_db = DB::query("SELECT * FROM client_demands_view");
        $demands = array();
        foreach ($result_db as $value){
            array_push($demands, new ClientDemand(
                Transporter::get_by_id($value["transporter_id"]),
                Announcement::byId($value["announcement_id"]))
            );
        }
        return $demands;
    }

    public static function delete($transporter_id, $announcement_id)
    {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("DELETE FROM client_demands WHERE transporter_id = :transporter_id AND announcement_id = :announcement_id");
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

    public static function to(int $transporter_id)
    {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM client_demands_view WHERE transporter_id = :transporter_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $demands = array();
        try {
            if ($stmt->execute()){
                $result_db = $stmt->fetchAll();
                foreach ($result_db as $value){
                    array_push($demands, new ClientDemand(
                            Transporter::get_by_id($value["transporter_id"]),
                            Announcement::byId($value["announcement_id"]))
                    );
                }
                return $demands;
            }
        }catch (Exception $e){

        }
        return $demands;
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
        return [
            "transporter" => $this->transporter,
            "announcement" => $this->announcement
        ];
    }
}