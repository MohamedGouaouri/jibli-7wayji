<?php


class User extends Model implements JsonSerializable {
    public int $user_id;
    protected string $name;
    protected string $family_name;
    protected string $phone_number;
    protected string $email;
    protected string $password;
    protected string $address;
    protected bool $banned;


    /**
     * Client constructor.
     * @param string $user_id
     * @param string $name
     * @param string $family_name
     * @param string $phone_number
     * @param string $email
     * @param string $password
     * @param string $address
     */
    public function __construct($user_id, string $name, string $family_name, string $phone_number ,string $email, string $password, string $address, bool $banned = false)
    {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->family_name = $family_name;
        $this->phone_number = $phone_number;
        $this->email = $email;
        $this->password = $password;
        $this->address = $address;
        $this->banned = $banned;
    }

    public static function add($name, $family_name, $phone_number, $email, $password, $address): bool {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO users (`name`, `family_name`, `phone_number`, `email`, `password`, `address`) VALUES (:name, :family_name, :phone_number ,:email, :password, :address)");
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":family_name", $family_name, PDO::PARAM_STR);
        $stmt->bindValue(":phone_number", $phone_number, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":address", $address, PDO::PARAM_STR);


        if ($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    public static function get_by_id($id): ?User{

        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :id");

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()){
            $clients = $stmt->fetchAll();
            if (count($clients) > 0){

                $client = $clients[0];
                $usr = new User(
                    $id,
                    $client["name"],
                    $client["family_name"],
                    $client["phone_number"],
                    $client["email"],
                    $client["password"],
                    $client["address"],
                    $client["banned"]
                );
                return $usr;
            }
        }
        return null;
    }

    public static function get_by_email($email): ?User{
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        if ($stmt->execute()){
            $clients = $stmt->fetchAll();
            if (count($clients) > 0){
                $client = $clients[0];
                return new User(
                    $client["user_id"],
                    $client["name"],
                    $client["family_name"],
                    $client["phone_number"],
                    $client["email"],
                    $client["password"],
                    $client["address"],
                    $client["banned"],
                );
            }


        }
        return null;
    }


    // Get all clients (non transporters)
    public static function allClients(){
        $clients_db = DB::query("SELECT * FROM clients"); // clients is a view
        $clients = array();
        foreach ($clients_db as $client) {
            array_push($clients, new User(
                $client["client_id"], // because of view
                $client["name"],
                $client["family_name"],
                $client["phone_number"],
                $client["email"],
                $client["password"],
                $client["address"],
            ));
        }
        return $clients;
    }
    public static function allBanned(){
        $clients_db = DB::query("SELECT * FROM users WHERE banned = TRUE"); // clients is a view
        $clients = array();
        foreach ($clients_db as $client) {
            array_push($clients, new User(
                $client["user_id"],
                $client["name"],
                $client["family_name"],
                $client["phone_number"],
                $client["email"],
                $client["password"],
                $client["address"],
                $client["banned"],
            ));
        }
        return $clients;
    }

    // ban user
    public static function banUser($user_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE users SET banned = TRUE WHERE user_id = :user_id");
        $stmt->bindValue(":user_id", $user_id);
        if ($stmt->execute()){
            return true;
        }
        return  false;
    }
    // unban user
    public static function unbanUser($user_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE users SET banned = FALSE WHERE user_id = :user_id");
        $stmt->bindValue(":user_id", $user_id);
        if ($stmt->execute()){
            return true;
        }
        return  false;
    }


    // update profile (Email only)
    public static function update($user_id, $email, $phone_number, $address){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE users SET email = :email, phone_number = :phone_number, address = :address WHERE user_id = :user_id");
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":phone_number", $phone_number);
        $stmt->bindValue(":address", $address);
        try {
            return $stmt->execute();
        }catch (Exception $e){

        }
        return false;
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {

        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->banned;
    }



    public function jsonSerialize()
    {
        return [
            "id" => $this->user_id,
            "name" => $this->name,
            "family_name" => $this->family_name,
            "phone_number" => $this->phone_number,
            "email" => $this->email,
            "address" => $this->address
        ];
    }
}