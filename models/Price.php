<?php

// Manipulate prices table
class Price extends Model implements JsonSerializable
{
    private Wilaya $start_point;
    private Wilaya $end_point;
    private float $price;

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
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    private static string $table_name = "prices";

    /**
     * Price constructor.
     * @param Wilaya $start_point
     * @param Wilaya $end_point
     * @param float $price
     */
    public function __construct(Wilaya $start_point, Wilaya $end_point, float $price)
    {
        $this->start_point = $start_point;
        $this->end_point = $end_point;
        $this->price = $price;
    }

    public static function all(){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM prices_view");
        $prices = array();
        try {

            if ($stmt->execute()){
                $prices_db = $stmt->fetchAll();

                foreach ($prices_db as $pricing){
                    array_push($prices, new Price(
                        new Wilaya($pricing["start_point"], $pricing["start_wilaya_name"]),
                        new Wilaya($pricing["end_point"], $pricing["end_wilaya_name"]),
                        $pricing["price"]
                    ));
                }
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
        return $prices;
    }


    public static function price(int $from, int $to): ?Price{
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM prices_view WHERE start_point = :from AND end_point = :to");
        $stmt->bindValue(":from", $from, PDO::PARAM_INT);
        $stmt->bindValue(":to", $to, PDO::PARAM_INT);
        try {

            if ($stmt->execute()){
                $prices = $stmt->fetchAll();
                if (count($prices) > 0){
                    $price = $prices[0];
                    return new Price(
                        new Wilaya($price["start_point"], $price["start_wilaya_name"]),
                        new Wilaya($price["end_point"], $price["end_wilaya_name"]),
                        $price["price"]
                    );
                }
            }
            return null;

        }catch (Exception $e){
            echo $e->getMessage();
        }
        return null;
    }

    /**
     * @param int $from
     * @param int $to
     * @param float $price
     * @return bool
     */
    public static function addPricing(int $from, int $to, float $price)
    {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO prices (`start_point`, `end_point`, `price`) VALUES (:from, :to, :price)");
        $stmt->bindValue(":from", $from);
        $stmt->bindValue(":to", $to);
        $stmt->bindValue(":price", $price);
        try {
            return $stmt->execute();
        }catch (Exception $e){

        }
        return false;
    }

    // update pricing
    public static function updatePricing(int $from, int $to, float $price)
    {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE prices SET price = :price WHERE start_point = :from AND end_point = :to");
        $stmt->bindValue(":from", $from);
        $stmt->bindValue(":to", $to);
        $stmt->bindValue(":price", $price);
        try {
            return $stmt->execute();
        }catch (Exception $e){

        }
        return false;
    }


    public static function exists($from, $to){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM prices WHERE start_point = :from AND end_point = :to");
        $stmt->bindValue(":from", $from, PDO::PARAM_INT);
        $stmt->bindValue(":to", $to, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }catch (Exception $e){

        }
        return false;
    }




    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return array_merge(
            $this->start_point->jsonSerialize(),
            $this->end_point->jsonSerialize(),
            [
                "price" => $this->price
            ],
        );
    }
}