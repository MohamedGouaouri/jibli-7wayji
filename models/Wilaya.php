<?php


class Wilaya extends Model
{
    private string $wilaya_id;
    private string $wilaya_name;
    private static string $table_name = "wilayas";

    /**
     * Wilaya constructor.
     * @param string $wilaya_id
     * @param string $wilaya_name
     */
    public function __construct(string $wilaya_id, string $wilaya_name)
    {
        $this->wilaya_id = $wilaya_id;
        $this->wilaya_name = $wilaya_name;
    }

    public static function all(): array
    {
        $wilayas = array();
        $db_results = DB::query("SELECT * FROM " . self::$table_name);
        foreach ($db_results as $result){
            array_push($wilayas, new Wilaya($result["wilaya_id"], $result["wilaya_name"]));
        }
        return $wilayas;
    }

    public static function limit($rows)
    {
        // TODO: Implement limit() method.
    }

    /**
     * @return string
     */
    public function getWilayaId(): string
    {
        return $this->wilaya_id;
    }

    /**
     * @return string
     */
    public function getWilayaName(): string
    {
        return $this->wilaya_name;
    }

}