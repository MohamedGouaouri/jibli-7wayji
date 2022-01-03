<?php


class Transaction extends Model implements JsonSerializable
{
    private Transporter $transporter;
    private Announcement $announcement;

    /**
     * Transaction constructor.
     * @param Transporter $transporter
     * @param Announcement $announcement
     */
    public function __construct(Transporter $transporter, Announcement $announcement)
    {
        $this->transporter = $transporter;
        $this->announcement = $announcement;
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
                     Announcement::byId($announcement_id)
                   );
               }
        }catch (Exception $e){
//            echo $e->getMessage();
        }
        return null;
    }
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            "transporter" => $this->transporter->jsonSerialize(),
            "announcement" => $this->announcement->jsonSerialize()
        ];
    }
}