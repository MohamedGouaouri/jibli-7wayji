<?php


class CertificationDemand extends Model implements JsonSerializable
{
    private int $id;
    private Transporter $transporter;
    private string $status;
    private string $date;

    /**
     * CertificationDemand constructor.
     * @param int $id
     * @param Transporter $transporter
     * @param string $status
     * @param string $date
     */
    public function __construct(int $id, Transporter $transporter, string $status, string $date)
    {
        $this->id = $id;
        $this->transporter = $transporter;
        $this->status = $status;
        $this->date = $date;
    }

    /** Add new entry in certification demand table
     * @param $transporter_id
     * @return bool
     */
    public static function save_certification_demand($transporter_id): bool {
        $pdo = DB::connect();
        try {
            $stmt = $pdo->prepare("INSERT INTO certification_demands (`transporter_id`) VALUES (:transporter_id)");
            $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
            if ($stmt->execute()){
                return true;
            }
            return  false;
        }catch (Exception $e){
            return false;
        }
    }
    public static function validate_certification_demand(int $id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE certification_demands SET status = 'validated' WHERE id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        try {
            return $stmt->execute();
        }catch (Exception $e){

        }
        return  false;
    }

    public static function reject_certification_demand(int $id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE certification_demands SET status = 'rejected' WHERE id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        try {
            return $stmt->execute();
        }catch (Exception $e){

        }
        return  false;
    }






    // get all certification demands
    public static function all(){
        $result = DB::query("SELECT * FROM certification_demands");
        $demands = array();
        foreach ($result as $value){
            array_push($demands, new CertificationDemand(
                $value["id"],
                Transporter::get_by_id($value["transporter_id"]),
                $value["status"],
                $value["demand_date"]
            ));
        }
        return $demands;
    }

    public static function of(int $transporter_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM certification_demands WHERE transporter_id = :transporter_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        try {
            if ($stmt->execute()){
                $result = $stmt->fetch();
                if ($result){
                    return new CertificationDemand(
                        $result["id"],
                        Transporter::get_by_id($result["transporter_id"]),
                        $result["status"],
                        $result["demand_date"]
                    );
                }
            }
        }catch (Exception $e){

        }
        return null;
    }

    /**
     * @return Transporter
     */
    public function getTransporter(): Transporter
    {
        return $this->transporter;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }





    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            "id" => $this->id,
            "transporter" => $this->getTransporter(),
            "status" => $this->getStatus(),
            "date" => $this->getDate()
        ];
    }
}