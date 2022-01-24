<?php


class Weight extends Model
{
    private float $weight;
    private string $description;
    private float $price;

    /**
     * Weight constructor.
     * @param float $weight
     * @param string $description
     * @param float $price
     */
    public function __construct(float $weight, string $description, float $price)
    {
        $this->weight = $weight;
        $this->description = $description;
        $this->price = $price;
    }

    public static function all(){
        $db_result = DB::query("SELECT * FROM weights");
        $weights = array();
        foreach ($db_result as $result){
            array_push($weights,
                new Weight(
                    $result["weight"],
                    $result["description"],
                    $result["unit_price"]
                )
            );
        }
        return $weights;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }


}