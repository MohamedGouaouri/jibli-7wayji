<?php


class Announcement extends Model implements JsonSerializable
{
    private int $announcement_id;
    private User $user;
    private Wilaya $start_point;
    private Wilaya $end_point;
    private string $type;
    private float $weight;
    private float $volume;
    private string $status;
    private string $message;
    private string $posted_at;
    private bool $validated;
    private float $price;

    private static string $table_name = "announcements";

    /**
     * Announcement constructor.
     * @param int $announcement_id
     * @param User $user
     * @param Wilaya $start_point
     * @param Wilaya $end_point
     * @param string $type
     * @param float $weight
     * @param float $volume
     * @param string $status
     * @param string $message
     * @param string $posted_at
     * @param bool $validated
     * @param float $price
     */
    public function __construct(int $announcement_id, User $user, Wilaya $start_point, Wilaya $end_point, string $type, float $weight, float $volume, string $status, string $message, string $posted_at, bool $validated, float $price)
    {
        $this->announcement_id = $announcement_id;
        $this->user = $user;
        $this->start_point = $start_point;
        $this->end_point = $end_point;
        $this->type = $type;
        $this->weight = $weight;
        $this->volume = $volume;
        $this->status = $status;
        $this->message = $message;
        $this->posted_at = $posted_at;
        $this->validated = $validated;
        $this->price = $price;
    }


    /** Gets The list of announcements
     * This function return the list of announcements for clients and transporters independently
     * @param bool $is_transporter
     * @return array
     */
    public static function all(bool $is_transporter): array
    {
        // TODO: Implement all() method.
        $announcements = array();
        $db_result =  DB::query("SELECT * FROM " . self::$table_name . ", users, wilayas WHERE clients.client_id = announcements.client_id AND clients.start_point = wilayas.wilaya_id AND clients.end_point = wilayas.wilaya_id");
        if ($is_transporter){
            foreach ($db_result as $r){
                $transporter = Transporter::get_by_id($r["user_id"]);
                array_push($announcements, new Announcement(
                    $r["announcement_id"],
                    $transporter,
                    new Wilaya($r["wilaya_id"],$r["wilaya_name"]),
                    new Wilaya($r["wilaya_id"],$r["wilaya_name"]),
                    $r["type"],
                    $r["weight"],
                    $r["volume"],
                    $r["status"],
                    $r["message"],
                    $r["posted_at"],
                    $r["validated"],
                    $r["price"],
                ));
            }
        }else{
            foreach ($db_result as $r){
                $client = User::get_by_id($r["user_id"]);
                array_push($announcements, new Announcement(
                    $r["announcement_id"],
                    $client,
                    new Wilaya($r["start_point"],$r["start_wilaya_name"]),
                    new Wilaya($r["end_point"],$r["end_wilaya_name"]),
                    $r["type"],
                    $r["weight"],
                    $r["volume"],
                    $r["status"],
                    $r["message"],
                    $r["posted_at"],
                    $r["validated"],
                    $r["price"],
                ));

            }
        }
        return $announcements;
    }


    /** Gets the list of announcements for a specific user
     * @param $user_id
     * @param $is_transporter
     * @return array|null
     */
    public static function allOfUser($user_id, $is_transporter): ?array
    {
        $announcements = array();

        $pdo = DB::connect();

        $stmt = $pdo->prepare("SELECT R.*, w1.wilaya_name AS start_wilaya_name, w2.wilaya_name AS end_wilaya_name FROM (SELECT a.*, name, family_name, email, password, address  FROM announcements a JOIN users u ON u.user_id = a.user_id WHERE u.user_id = :user_id) AS R, wilayas w1, wilayas w2 WHERE R.start_point = w1.wilaya_id AND w2.wilaya_id = R.end_point;");

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        if ($stmt->execute()){

            $db_result =  $stmt->fetchAll();
            if ($is_transporter){
                foreach ($db_result as $r){
                    $transporter = Transporter::get_by_id($r["user_id"]);
                    array_push($announcements, new Announcement(
                        $r["announcement_id"],
                        $transporter,
                        new Wilaya($r["start_point"],$r["start_wilaya_name"]),
                        new Wilaya($r["end_point"],$r["end_wilaya_name"]),
                        $r["type"],
                        $r["weight"],
                        $r["volume"],
                        $r["status"],
                        $r["message"],
                        $r["posted_at"],
                        $r["validated"],
                        $r["price"],
                    ));

                }
            }else{
                foreach ($db_result as $r){
                    $client = User::get_by_id($r["user_id"]);
                    array_push($announcements, new Announcement(
                        $r["announcement_id"],
                        $client,
                        new Wilaya($r["start_point"],$r["start_wilaya_name"]),
                        new Wilaya($r["end_point"],$r["end_wilaya_name"]),
                        $r["type"],
                        $r["weight"],
                        $r["volume"],
                        $r["status"],
                        $r["message"],
                        $r["posted_at"],
                        $r["validated"],
                        $r["price"],
                    ));

                }
            }
            return $announcements;
        }
        return null;
    }


    /**
     * Gets a limited number of announcements of all users
     * @param $rows
     * @return array
     */
    public static function limit($rows)
    {

        $announcements = array();
        $db_result =  DB::query("SELECT * FROM announcements_view LIMIT ". $rows);

        foreach ($db_result as $r){
            $transporter = null; //TODO change this
            $client = User::get_by_id($r["user_id"]);
            if ($transporter != null){
                    array_push($announcements, new Announcement(
                        $r["announcement_id"],
                        $transporter,
                        new Wilaya($r["start_point"],$r["start_wilaya_name"]),
                        new Wilaya($r["end_point"],$r["end_wilaya_name"]),
                        $r["type"],
                        $r["weight"],
                        $r["volume"],
                        $r["status"],
                        $r["message"],
                        $r["posted_at"],
                        $r["validated"],
                        $r["price"],
                    ));
                }else{
                    array_push($announcements, new Announcement(
                        $r["announcement_id"],
                        $client,
                        new Wilaya($r["start_point"],$r["start_wilaya_name"]),
                        new Wilaya($r["end_point"],$r["end_wilaya_name"]),
                        $r["type"],
                        $r["weight"],
                        $r["volume"],
                        $r["status"],
                        $r["message"],
                        $r["posted_at"],
                        $r["validated"],
                        $r["price"],
                    ));
                }


            }
        return $announcements;
    }


    /**
     * @param int $id
     * @return Announcement
     */
    public static function byId(int $id): ?Announcement{
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT R.*, w1.wilaya_name AS start_wilaya_name, w2.wilaya_name AS end_wilaya_name FROM (SELECT a.*, name, family_name, email, password, address  FROM announcements a JOIN users u ON u.user_id = a.user_id WHERE a.announcement_id = :announcement_id) AS R, wilayas w1, wilayas w2 WHERE R.start_point = w1.wilaya_id AND w2.wilaya_id = R.end_point;");
        $stmt->bindValue(":announcement_id", $id, PDO::PARAM_INT);

        if ($stmt->execute()){
            $db_result =  $stmt->fetchAll();
                foreach ($db_result as $r){
                    $transporter = Transporter::get_by_id($r["user_id"]);
                    if ($transporter != null){
                        return new Announcement(
                            $r["announcement_id"],
                            $transporter,
                            new Wilaya($r["start_point"],$r["start_wilaya_name"]),
                            new Wilaya($r["end_point"],$r["end_wilaya_name"]),
                            $r["type"],
                            $r["weight"],
                            $r["volume"],
                            $r["status"],
                            $r["message"],
                            $r["posted_at"],
                            $r["validated"],
                            $r["price"]);
                    }else{
                        $client = User::get_by_id($r["user_id"]);
                        return new Announcement(
                            $r["announcement_id"],
                            $client,
                            new Wilaya($r["start_point"],$r["start_wilaya_name"]),
                            new Wilaya($r["end_point"],$r["end_wilaya_name"]),
                            $r["type"],
                            $r["weight"],
                            $r["volume"],
                            $r["status"],
                            $r["message"],
                            $r["posted_at"],
                            $r["validated"],
                            $r["price"],
                        );
                    }
                }
        }
        return null;
    }

    /** Search by criteria
     * @param int $start_point
     * @param int $end_point
     * @return array|null
     */
    public static function byCriteria(int $start_point, int $end_point){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM announcements_view WHERE start_point = :start_point AND end_point = :end_point");
        $stmt->bindValue(":start_point", $start_point, PDO::PARAM_INT);
        $stmt->bindValue(":end_point", $end_point, PDO::PARAM_INT);
        try {
            if ($stmt->execute()){
                $announcements = array();
                $db_result =  $stmt->fetchAll();
                foreach ($db_result as $r){
                    $transporter = Transporter::get_by_id($r["user_id"]);
                    if ($transporter != null){
                        array_push($announcements, new Announcement(
                            $r["announcement_id"],
                            $transporter,
                            new Wilaya($r["start_point"],$r["start_wilaya_name"]),
                            new Wilaya($r["end_point"],$r["end_wilaya_name"]),
                            $r["type"],
                            $r["weight"],
                            $r["volume"],
                            $r["status"],
                            $r["message"],
                            $r["posted_at"],
                            $r["validated"],
                            $r["price"],
                        ));
                    }else{
                        $client = User::get_by_id($r["user_id"]);
                        if ($client != null){
                            array_push($announcements, new Announcement(
                                $r["announcement_id"],
                                $client,
                                new Wilaya($r["start_point"],$r["start_wilaya_name"]),
                                new Wilaya($r["end_point"],$r["end_wilaya_name"]),
                                $r["type"],
                                $r["weight"],
                                $r["volume"],
                                $r["status"],
                                $r["message"],
                                $r["posted_at"],
                                $r["validated"],
                                $r["price"],
                            ));
                        }
                    }

                }
                return $announcements;
            }

        }catch (Exception $e){
            echo $e->getMessage();
        }
        return  null;
    }

    /**
     * @param $user_id
     * @param $start_point
     * @param $end_point
     * @param $type
     * @param $weight
     * @param $volume
     * @param $message
     * @return bool
     */
    public static function add($user_id, $start_point, $end_point, $type, $weight, $volume, $message){
        $pdo = DB::connect();

        $stmt = $pdo->prepare("INSERT INTO announcements (`user_id`, `start_point`, `end_point`, `type`, `weight`, `volume`,`status`, `message`) VALUES (:user_id, :start_point, :end_point, :type, :weight, :volume,:status, :message)");
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":start_point", $start_point, PDO::PARAM_INT);
        $stmt->bindValue(":end_point", $end_point, PDO::PARAM_STR);
        $stmt->bindValue(":type", $type, PDO::PARAM_STR);
        $stmt->bindValue(":weight", $weight);
        $stmt->bindValue(":volume", $volume);
        $stmt->bindValue(":status", "pending", PDO::PARAM_STR);
        $stmt->bindValue(":message", $message);


        try {
            if ($stmt->execute()){
                return true;
            }
            return false;
        }catch (Exception $e){
            echo $e->getMessage();
        }
        return false;
    }


    /**
     * @param $announcement_id
     * @return bool
     */
    public static function delete($announcement_id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("DELETE FROM announcements WHERE announcement_id = :announcement_id");
        $stmt->bindValue(":announcement_id", $announcement_id, PDO::PARAM_INT);
        if ($stmt->execute()){
            return true;
        }
        return  false;
    }

    /**
     * @return int
     */
    public function getAnnouncementId(): int
    {
        return $this->announcement_id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Wilaya
     */
    public function getStartPoint(): Wilaya
    {
        return $this->start_point;
    }

    /**
     * @return Wilaya
     */
    public function getEndPoint(): Wilaya
    {
        return $this->end_point;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @return float
     */
    public function getVolume(): float
    {
        return $this->volume;
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
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getPostedAt(): string
    {
        return $this->posted_at;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return self::$table_name;
    }


    public function jsonSerialize()
    {
        return [
            "id" => $this->announcement_id,
            "user" => $this->user,
            "start_point" => $this->start_point,
            "end_point" => $this->end_point,
            "type" => $this->type,
            "weight" => $this->weight,
            "volume" => $this->volume,
            "status" => $this->status,
            "message" => $this->message,
            "posted_at" => $this->posted_at,
            "validated" => $this->validated,
            "price" => $this->price
        ];
    }
}