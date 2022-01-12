<?php


class Transaction extends Model implements JsonSerializable
{
    private Transporter $transporter;
    private Announcement $announcement;
    private bool $validated;
    private bool $done;

    /**
     * Transaction constructor.
     * @param Transporter $transporter
     * @param Announcement $announcement
     * @param bool $validated
     * @param bool $done
     */
    public function __construct(Transporter $transporter, Announcement $announcement, bool $validated, bool $done = false)
    {
        $this->transporter = $transporter;
        $this->announcement = $announcement;
        $this->validated = $validated;
        $this->done = $done;
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
                     $validated,

                   );
               }
        }catch (Exception $e){
//            echo $e->getMessage();
        }
        return null;
    }


    public static function get($transporter_id, $announcement_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM transport WHERE transporter_id = :transporter_id AND announcement_id = :announcement_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);

        try {
            if ($stmt->execute()){
                $transport_db = $stmt->fetchAll();
                if (count($transport_db) > 0){
                    $transport_db = $transport_db[0];
                    return new Transaction(
                        Transporter::get_by_id($transporter_id),
                        Announcement::byId($transport_db["announcement_id"]),
                        $transport_db["validated"],
                        $transport_db["done"]
                    );
                }
            }
        }catch (Exception $e){

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
                    // TODO: Filter running transactions
                    array_push($transports, new Transaction(
                        Transporter::get_by_id($transporter_id),
                        Announcement::byId($value["announcement_id"]),
                        $value["validated"],
                        $value["done"]
                    ));
                }
            }
        }catch (Exception $e){

        }
        return $transports;
    }
    public static function getOfClient($client_id): array {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT a.*, transporter_id, transport.validated as validated_transport ,done FROM transport JOIN announcements a on transport.announcement_id = a.announcement_id AND user_id = :client_id");
        $transports = array();
        try {
            if ($stmt->execute()){
                $transports_db = $stmt->fetchAll();
                foreach ($transports_db as $value){
                    // TODO: Filter running transactions
                    array_push($transports, new Transaction(
                        Transporter::get_by_id($value["transporter_id"]),
                        Announcement::byId($value["announcement_id"]),
                        $value["validated_transport"], // because of renaming columns in join
                        $value["done"]
                    ));
                }
            }
        }catch (Exception $e){

        }
        return $transports;
    }

    /**
     * Get all running transaction which means done = false and validated = true
     * It uses running_transports_view to get the list of running transactions
     * for a specific client
     * @param $transporter_id
     * @return array|null
     */
    public static function getRunningTransports($transporter_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM running_transports_view WHERE transporter_id = :transporter_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $running = array();
        try {
            if ($stmt->execute()){
                $running_db = $stmt->fetchAll();
                foreach ($running_db as $r){
                    array_push($running, new Transaction(
                        Transporter::get_by_id($transporter_id),
                        Announcement::byId($r["announcement_id"]),
                        $r["validated"],
                        true
                    ));
                }
            }
        }catch (Exception $e){
            return null;
        }
        return $running;
    }

    public static function finishTransport($transporter_id, $announcement_id): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE transport SET done = TRUE WHERE transporter_id = :transporter_id AND announcement_id = :announcement_id");
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

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done;
    }



    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            "transporter" => $this->transporter->jsonSerialize(),
            "announcement" => $this->announcement->jsonSerialize(),
            "validated" => $this->validated,
            "done" => $this->done
        ];
    }
}