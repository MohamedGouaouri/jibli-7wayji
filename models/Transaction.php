<?php


class Transaction extends Model implements JsonSerializable
{
    private Transporter $transporter;
    private Announcement $announcement;
    private bool $validated;

    /**
     * Transaction constructor.
     * @param Transporter $transporter
     * @param Announcement $announcement
     * @param bool $validated
     */
    public function __construct(Transporter $transporter, Announcement $announcement, bool $validated)
    {
        $this->transporter = $transporter;
        $this->announcement = $announcement;
        $this->validated = $validated;
    }

    public static function add($transporter_id, $announcement_id, $validated): ?Transaction
    {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO transport (`transporter_id`, `announcement_id`, `validated`) VALUES (:transporter_id, :announcement_id, :validated)");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        $stmt->bindValue(":validated", $validated, PDO::PARAM_BOOL);
        try {
               if ($stmt->execute()){
                   return new Transaction(
                     Transporter::get_by_id($transporter_id),
                     Announcement::byId($announcement_id),
                   $validated
                   );
               }
        }catch (Exception $e){
//            echo $e->getMessage();
        }
        return null;
    }

    public static function getOfTransporter($transporter_id): array {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM transport WHERE transporter_id = :transporter_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $transports = array();
        try {
            if ($stmt->execute()){
                $transports_db = $stmt->fetchAll();
                foreach ($transports_db as $value){
                    array_push($transports, new Transaction(
                        Transporter::get_by_id($transporter_id),
                        Announcement::byId($value["announcement_id"]),
                        $value["validated"]
                    ));
                }
            }
        }catch (Exception $e){

        }
        return $transports;
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


    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            "transporter" => $this->transporter->jsonSerialize(),
            "announcement" => $this->announcement->jsonSerialize(),
            "validated" => $this->validated
        ];
    }
}