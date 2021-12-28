<?php


class Announcement extends Model
{
    private int $announcement_id;
    private Client $client;
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
     * @param Client $client
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
    public function __construct(int $announcement_id, Client $client, Wilaya $start_point, Wilaya $end_point, string $type, float $weight, float $volume, string $status, string $message, string $posted_at, bool $validated, float $price)
    {
        $this->announcement_id = $announcement_id;
        $this->client = $client;
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


    public static function all(): array
    {
        // TODO: Implement all() method.
        $announcements = array();
        $db_result =  DB::query("SELECT * FROM " . self::$table_name . ", clients, wilayas WHERE clients.client_id = announcements.client_id AND clients.start_point = wilayas.wilaya_id AND clients.end_point = wilayas.wilaya_id");
        foreach ($db_result as $r){
            array_push($announcements, new Announcement(
                $r["announcement_id"],
                new Client($r["client_id"], $r["name"], $r["family_name"], $r["email"], $r["password"], $r["address"]),
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
        return $announcements;
    }


    public static function allOfClient($client_id): ?array
    {
        $announcements = array();

        $pdo = DB::connect();

        $stmt = $pdo->prepare("SELECT R.*, w1.wilaya_name AS start_wilaya_name, w2.wilaya_name AS end_wilaya_name FROM (SELECT a.*, name, family_name, email, password, address  FROM announcements a JOIN clients c ON c.client_id = a.client_id WHERE c.client_id = :client_id) AS R, wilayas w1, wilayas w2 WHERE R.start_point = w1.wilaya_id AND w2.wilaya_id = R.end_point;");

        $stmt->bindValue(":client_id", $client_id, PDO::PARAM_INT);

        if ($stmt->execute()){

            $db_result =  $stmt->fetchAll();
            foreach ($db_result as $r){

                array_push($announcements, new Announcement(
                    $r["announcement_id"],
                    new Client($r["client_id"], $r["name"], $r["family_name"], $r["email"], $r["password"], $r["address"]),
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
            return $announcements;
        }
        return null;
    }


    public static function limit($rows)
    {
        $announcements = array();
        $db_result =  DB::query("SELECT * FROM announcements_view LIMIT ". $rows);
        foreach ($db_result as $r){
            array_push($announcements, new Announcement(
                $r["announcement_id"],
                new Client($r["client_id"], $r["name"], $r["family_name"], $r["email"], $r["password"], $r["address"]),
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
        return $announcements;
    }


    public static function byId(int $id){
        $announcements = array();

        $pdo = DB::connect();

        $stmt = $pdo->prepare("SELECT R.*, w1.wilaya_name AS start_wilaya_name, w2.wilaya_name AS end_wilaya_name FROM (SELECT a.*, name, family_name, email, password, address  FROM announcements a JOIN clients c ON c.client_id = a.client_id WHERE a.announcement_id = :announcement_id) AS R, wilayas w1, wilayas w2 WHERE R.start_point = w1.wilaya_id AND w2.wilaya_id = R.end_point;");

        $stmt->bindValue(":announcement_id", $id, PDO::PARAM_INT);

        if ($stmt->execute()){
            $db_result =  $stmt->fetchAll();
            foreach ($db_result as $r){

                array_push($announcements, new Announcement(
                    $r["announcement_id"],
                    new Client($r["client_id"], $r["name"], $r["family_name"], $r["email"], $r["password"], $r["address"]),
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
            return $announcements;
        }
        return null;
    }

    public static function byCriteria(int $start_point, int $end_point){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT announcement_id AS id, start_point, end_point, type, weight, volume FROM " . self::$table_name . " WHERE start_point = :start_point AND end_point = :end_point AND status = 'APPROVED'");
        $stmt->bindValue(":start_point", $start_point, PDO::PARAM_INT);
        $stmt->bindValue(":end_point", $end_point, PDO::PARAM_INT);
        if ($stmt->execute()){
            return $stmt->fetchAll();
        }
        return  null;
    }

    public static function add($client_id, $start_point, $end_point, $type, $weight, $volume, $message){
        $pdo = DB::connect();

        $stmt = $pdo->prepare("INSERT INTO announcements (`client_id`, `start_point`, `end_point`, `type`, `weight`, `volume`,`status`, `message`) VALUES (:client_id, :start_point, :end_point, :type, :weight, :volume,:status, :message)");
        $stmt->bindValue(":client_id", $client_id, PDO::PARAM_INT);
        $stmt->bindValue(":start_point", $start_point, PDO::PARAM_INT);
        $stmt->bindValue(":end_point", $end_point, PDO::PARAM_STR);
        $stmt->bindValue(":type", $type, PDO::PARAM_STR);
        $stmt->bindValue(":weight", $weight);
        $stmt->bindValue(":volume", $volume);
        $stmt->bindValue(":status", "pending", PDO::PARAM_STR);
        $stmt->bindValue(":message", $message);

        if ($stmt->execute()){
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getAnnouncementId(): int
    {
        return $this->announcement_id;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
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


}