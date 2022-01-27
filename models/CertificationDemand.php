<?php


class CertificationDemand extends Model implements JsonSerializable
{
    private Transporter $transporter;
    private string $status;
    private string $date;

    /**
     * CertificationDemand constructor.
     * @param Transporter $transporter
     * @param string $status
     * @param string $date
     */
    public function __construct(Transporter $transporter, string $status, string $date)
    {
        $this->transporter = $transporter;
        $this->status = $status;
        $this->date = $date;
    }

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
    public static function validate_certification_demand(){
        $pdo = DB::connect();
//        $stmt = $pdo->prepare("UPDATE")
    }

    // get all certification demands
    public static function all(){
        $result = DB::query("SELECT * FROM certification_demands");
        $demands = array();
        foreach ($result as $value){
            array_push($demands, new CertificationDemand(
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
                return new CertificationDemand(
                    Transporter::get_by_id($result["transporter_id"]),
                    $result["status"],
                    $result["demand_date"]
                );
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



    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            "transporter" => $this->getTransporter(),
            "status" => $this->getStatus(),
            "date" => $this->getDate()
        ];
    }
}