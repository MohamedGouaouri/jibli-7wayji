<?php


class News
{
    private int $id;
    private string $title;
    private string $synopsis;
    private string $content;
    private string $paths;

    /**
     * News constructor.
     * @param int $id
     * @param string $title
     * @param string $synopsis
     * @param string $content
     * @param string $paths
     */
    public function __construct(int $id, string $title, string $synopsis, string $content, string $paths)
    {
        $this->id = $id;
        $this->title = $title;
        $this->synopsis = $synopsis;
        $this->content = $content;
        $this->paths = $paths;
    }


    /** Adds a news
     * @param string $title
     * @param string $synopsis
     * @param string $content
     * @param string $paths
     * @return bool
     */
    public static function add(string $title, string $synopsis, string $content, string $paths){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO news (`title`, `synopsis`, `content`, `paths`) VALUES (:title, :synopsis, :content, :paths)");
        $stmt->bindValue(":title", $title);
        $stmt->bindValue(":synopsis", $synopsis);
        $stmt->bindValue(":content", $content);
        $stmt->bindValue("paths", $paths);
        try {
            return $stmt->execute();
        }catch (Exception $e){

        }
        return false;
    }

    public static function all(){
        $result_db = DB::query("SELECT * FROM news");
        $news = array();
        foreach ($result_db as $value){
            array_push($news, new News($value["id"], $value["title"], $value["synopsis"], $value["content"], $value["paths"]));
        }
        return $news;
    }

    public static function byId($id){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        try {
            if ($stmt->execute()){
                $result_db = $stmt->fetchAll();
                if (count($result_db) > 0){
                    $result = $result_db[0];
                    return new News($result["id"], $result["title"],$result["synopsis"], $result["content"], $result["paths"]);
                }
            }
        }catch (Exception $e){

        }
        return  null;
    }

    /** Deletes a news entry from db
     * @param $id
     * @return bool
     */
    public static function delete($id)
    {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("DELETE FROM news WHERE id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        try {
            return $stmt->execute();
        }catch (Exception $e){

        }
        return false;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSynopsis(): string
    {
        return $this->synopsis;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getPaths(): string
    {
        return $this->paths;
    }


}