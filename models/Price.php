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