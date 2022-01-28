<?php


class Transporter extends User implements JsonSerializable
{
    private bool   $certified;
    private $status;
    private bool $validated;
    private float $inventory;

    /**
     * Transporter constructor.
     * @param int $transporter_id
     * @param string $name
     * @param string $family_name
     * @param string $phone_number
     * @param string $email
     * @param string $password
     * @param string $address
     * @param bool $is_certified
     * @param $status
     * @param bool $validated
     * @param float $inventory
     */
    public function __construct(int $transporter_id, string $name, string $family_name, string $phone_number, string $email, string $password, string $address,bool $is_certified, $status, bool $validated, float $inventory)
    {
        User::__construct($transporter_id, $name, $family_name, $phone_number, $email, $password, $address);
        $this->certified = $is_certified;
        $this->status = $status;
        $this->validated = $validated;
        $this->inventory = $inventory;
    }


    // Add new Transporter

    /**
     * @param $name
     * @param $family_name
     * @param $phone_number
     * @param $email
     * @param $password
     * @param $address
     * @return bool
     */
    public static function add($name, $family_name, $phone_number, $email, $password, $address): bool {
        $pdo = DB::connect();

        // add transporter as a user
        $stmt = $pdo->prepare("INSERT INTO users (`name`, `family_name`, `phone_number`,`email`, `password`, `address`) VALUES (:name, :family_name, :phone_number, :email, :password, :address)");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":family_name", $family_name, PDO::PARAM_STR);
        $stmt->bindValue(":phone_number", $phone_number, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":address", $address, PDO::PARAM_STR);
        try {
            if ($stmt->execute()){
                $transporter_id = User::get_by_email($email)->getUserId();
                // add the transporter to the transporter's table
                $stmt = $pdo->prepare("INSERT INTO transporters (`transporter_id`) VALUES (:transporter_id)");
                $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_STR);
                try {
                    if ($stmt->execute()){
                        return true;
                    }
                }catch (Exception $e){
                    echo $e->getMessage();
                }
            }

        }catch (Exception $e){
            echo $e->getMessage();
        }
        return false;
    }

    public static function all()
    {
        $transporters = array();
        $db_result = DB::query("SELECT * FROM transporters_view");
        foreach ($db_result as $r){
            array_push($transporters, new Transporter(
                $r["transporter_id"],
                $r["name"],
                $r["family_name"],
                $r["phone_number"],
                $r["email"],
                $r["password"],
                $r["address"],
                $r["is_certified"],
                $r["status"],
                $r["validated"],
                $r["inventory"],
            ));
        }
        return $transporters;
    }
    public static function allUnBanned()
    {
        $transporters = array();
        $db_result = DB::query("SELECT * FROM transporters_view WHERE banned = FALSE");
        foreach ($db_result as $r){
            array_push($transporters, new Transporter(
                $r["transporter_id"],
                $r["name"],
                $r["family_name"],
                $r["phone_number"],
                $r["email"],
                $r["password"],
                $r["address"],
                $r["is_certified"],
                $r["status"],
                $r["validated"],
                $r["inventory"],
            ));
        }
        return $transporters;
    }

    public static function get_by_email($email): ?Transporter{
        echo "hello";
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM transporters_view WHERE email = :email");
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        if ($stmt->execute()){
            $transporters = $stmt->fetchAll();
            if (count($transporters) > 0){
                $transporter = $transporters[0];
                return new Transporter(
                    $transporter["transporter_id"],
                    $transporter["name"],
                    $transporter["family_name"],
                    $transporter["phone_number"],
                    $transporter["email"],
                    $transporter["password"],
                    $transporter["address"],
                    $transporter["is_certified"],
                    $transporter["status"],
                    $transporter["validated"],
                    $transporter["inventory"],
                );
            }


        }
        return null;
    }
    public static function get_by_id($id): ?Transporter{
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM transporters_view WHERE transporter_id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        if ($stmt->execute()){
            $transporters = $stmt->fetchAll();
            if (count($transporters) > 0){
                $transporter = $transporters[0];
                return new Transporter(
                    $transporter["transporter_id"],
                    $transporter["name"],
                    $transporter["family_name"],
                    $transporter["phone_number"],
                    $transporter["email"],
                    $transporter["password"],
                    $transporter["address"],
                    $transporter["is_certified"],
                    $transporter["status"],
                    $transporter["validated"],
                    $transporter["inventory"],
                );

            }
        }
        return null;
    }

    // Trajectory related methods
    public static function add_wilaya($transporter_id, $wilaya_id): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO covered_wilayas VALUES (:transporter_id, :wilaya_id)");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":wilaya_id", $wilaya_id, PDO::PARAM_INT);
        if ($stmt->execute()){
            return true;
        }
        return false;
    }
    
    
    public static function delete_wilaya($transporter_id, $wilaya_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("DELETE FROM covered_wilayas WHERE transporter_id = :transporter_id AND wilaya_id = :wilaya_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":wilaya_id", $wilaya_id, PDO::PARAM_INT);
        if ($stmt->execute()){
            return true;
        }
        return false;
    }


    public static function getByTrajectory($start_point, $end_point){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM trajectories WHERE start_point = :start_point AND end_point = :end_point");
        $stmt->bindValue(":start_point", $start_point, PDO::PARAM_INT);
        $stmt->bindValue(":end_point", $end_point, PDO::PARAM_INT);
        try {
            if ($stmt->execute()){
                $trajectories_db = $stmt->fetchAll();
                $transporters = array();
                foreach ($trajectories_db as $trajectory){
                    array_push($transporters, Transporter::get_by_id($trajectory["transporter_id"]));
                }
                return $transporters;
            }
        }catch (Exception $e){

        }
        return null;
    }


    // get all covered wilayas
    public static function getCoveredWilayas($transporter_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM covered_wilayas_view WHERE transporter_id = :transporter_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $covered_wilayas = array();
        try {
            if ($stmt->execute()){
                $covered_wilayas_db = $stmt->fetchAll();
                foreach ($covered_wilayas_db as $value){
                    array_push($covered_wilayas, new Wilaya($value["wilaya_id"], $value["wilaya_name"]));
                }
            }
        }catch (Exception $e){

        }
        return $covered_wilayas;
    }

    public static function getNonCoveredWilayas($transporter_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM wilayas WHERE wilaya_id NOT IN (SELECT wilaya_id FROM covered_wilayas_view WHERE transporter_id = :transporter_id)");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $covered_wilayas = array();
        try {
            if ($stmt->execute()){
                $covered_wilayas_db = $stmt->fetchAll();
                foreach ($covered_wilayas_db as $value){
                    array_push($covered_wilayas, new Wilaya($value["wilaya_id"], $value["wilaya_name"]));
                }
            }
        }catch (Exception $e){

        }
        return $covered_wilayas;
    }


    public static function updateInventory(int $transporter_id, float $new_val): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE transporters SET inventory = :new_val WHERE transporter_id = :id");
        $stmt->bindValue(":id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":new_val", $new_val);
        try {
           return $stmt->execute();
        }catch (Exception $e){

        }
        return false;
    }


    // validate transporter
    public static function validate($transporter_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE transporters SET validated = TRUE WHERE transporter_id = :transporter_id");
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        try {
            if ($stmt->execute()){
                return true;
            }
        }catch (Exception $e){

        }
        return false;
    }


    public function isCertified(): bool
    {
        return $this->certified;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return float
     */
    public function getInventory(): float
    {
        return $this->inventory;
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
        return array_merge(
            parent::jsonSerialize(),
            [
                "certified" => $this->certified,
                "validated" => $this->validated,
                "inventory" => $this->inventory
            ]
        );
    }
}