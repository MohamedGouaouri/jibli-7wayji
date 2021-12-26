<?php


class Client extends Model{
    private string $client_id;
    private string $name;
    private string $family_name;
    private string $email;
    private string $password;
    private string $address;

    private static string $table_name = "clients";

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->client_id;
    }

    /**
     * Client constructor.
     * @param string $client_id
     * @param string $name
     * @param string $family_name
     * @param string $email
     * @param string $password
     * @param string $address
     */
    public function __construct($client_id, string $name, string $family_name, string $email, string $password, string $address)
    {
        $this->client_id = $client_id;
        $this->name = $name;
        $this->family_name = $family_name;
        $this->email = $email;
        $this->password = $password;
        $this->address = $address;
    }

    public static function all()
    {
        // TODO: Implement all() method.
//        return DB::query("SELECT * FROM " . self::$table_name);
    }

    public static function limit($rows)
    {
        // TODO: Implement limit() method.
//        return DB::query("SELECT * FROM " . self::$table_name . " LIMIT " . $rows);
    }
    public static function add($name, $family_name, $email, $password, $address): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO clients (`name`, `family_name`, `email`, `password`, `address`) VALUES (:name, :family_name, :email, :password, :address)");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":family_name", $family_name, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":address", $address, PDO::PARAM_STR);


        if ($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    public static function get_by_id($id){
        $pdo = DB::connect();

        $stmt = $pdo->prepare("SELECT * FROM clients WHERE client_id = :id");

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()){
            return $stmt->fetchAll();
        }
        return null;
    }

    public static function get_by_email($email){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = :email");
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        if ($stmt->execute()){
            return $stmt->fetchAll();
        }
        return null;
    }


    // ========================= Getters =================

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
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return self::$table_name;
    }


}