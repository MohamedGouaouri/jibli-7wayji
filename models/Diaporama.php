<?php


class Diaporama implements JsonSerializable
{

    private string $path;

    /**
     * Diaporama constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }


    public static function all(){
        $db_result = DB::query("SELECT * FROM diaporama_images");
        $images = array();
        foreach ($db_result as $value){
            array_push($images, new Diaporama($value["path"]));
        }
        return $images;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    public function jsonSerialize()
    {
        return [
          "path" => $this->path
        ];
    }
}