<?php


class Transporter extends Model
{
    private int $transporter_id;


    private string $name;
    private string $family_name;
    private string $email;
    private string $password;


    private bool   $certified;
    private  $status;
    private bool $validated;
    private float $inventory;

    private static string $table_name = "transporters";

    /**
     * @return bool
     */


    /**
     * Transporter constructor.
     * @param int $transporter_id
     * @param string $name
     * @param string $family_name
     * @param string $email
     * @param bool $is_certified
     * @param $status
     * @param float $inventory
     */
    public function __construct(int $transporter_id, string $name, string $family_name, string $email, string $password, bool $is_certified, $status, bool $validated, float $inventory)
    {
        $this->transporter_id = $transporter_id;
        $this->name = $name;
        $this->family_name = $family_name;
        $this->email = $email;
        $this->password = $password;
        $this->certified = $is_certified;
        $this->status = $status;
        $this->validated = $validated;
        $this->inventory = $inventory;
    }


    // Add new Transporter
    public static function add($name, $family_name, $email, $password): bool {
        $pdo = DB::connect();

        $stmt = $pdo->prepare("INSERT INTO transporters (`name`, `family_name`, `email`, `password`) VALUES (:name, :family_name, :email, :password)");

        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":family_name", $family_name, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);

        if ($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public static function all()
    {
        // TODO: Implement all() method.
        $transporters = array();
        $db_result = DB::query("SELECT * FROM transporters");
        foreach ($db_result as $r){
            array_push($transporters, new Transporter(
                $r["transporter_id"],
                $r["name"],
                $r["family_name"],
                $r["email"],
                $r["password"],
                $r["is_certified"],
                $r["status"],
                $r["validated"],
                $r["inventory"],
            ));
        }
        return $transporters;
    }

    public static function get_by_email($email): ?Transporter{
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM transporters WHERE email = :email");
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        if ($stmt->execute()){
            $transporter = $stmt->fetchAll()[0];
            return new Transporter(
                $transporter["transporter_id"],
                $transporter["name"],
                $transporter["family_name"],
                $transporter["email"],
                $transporter["password"],
                $transporter["is_certified"],
                $transporter["status"],
                $transporter["validated"],
                $transporter["inventory"],
            );

        }
        return null;
    }
    public static function get_by_id($id): ?Transporter{
        $pdo = DB::connect();

        $stmt = $pdo->prepare("SELECT * FROM transporters WHERE transporter_id = :id");

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()){
            $transporter = $stmt->fetchAll()[0];
            return new Transporter(
                $transporter["transporter_id"],
                $transporter["name"],
                $transporter["family_name"],
                $transporter["email"],
                $transporter["password"],
                $transporter["is_certified"],
                $transporter["status"],
                $transporter["validated"],
                $transporter["inventory"],
            );

        }
        return null;
    }


    public static function add_wilaya($transporter_id, $wilaya_id): bool {
        $pdo = DB::connect();

        $stmt = $pdo->prepare("INSERT INTO covered_wilayas VALUES (:transporter_id, :wilaya_id)");
        echo "hello";
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":wilaya_id", $wilaya_id, PDO::PARAM_INT);
        if ($stmt->execute()){
            return true;
        }
        return false;
    }


    public static function limit($rows)
    {
        // TODO: Implement limit() method.
    }


    // Getters
    /**
     * @return int
     */
    public function getTransporterId(): int
    {
        return $this->transporter_id;
    }

    public function isCertified(): bool
    {
        return $this->certified;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->family_name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
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



    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

}