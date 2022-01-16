<?php


class Feedback extends Model implements JsonSerializable
{
    private User $user;
    private Transporter $transporter;
    private int $note;
    private string $message;

    /**
     * Feedback constructor.
     * @param User $user
     * @param Transporter $transporter
     * @param int $note
     * @param string $message
     */
    public function __construct(User $user, Transporter $transporter, int $note, string $message)
    {
        $this->user = $user;
        $this->transporter = $transporter;
        $this->note = $note;
        $this->message = $message;
    }


    public static function addFeedBack($user_id, $transporter_id, $note, $message){
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO notes (`user_id`, `transporter_id`, `note`, `message`) VALUES (:user_id, :transporter_id, :note, :message)");
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":transporter_id", $transporter_id, PDO::PARAM_INT);
        $stmt->bindValue(":note", $note);
        $stmt->bindValue(":message", $message);
        try {
            return $stmt->execute();
        }catch (Exception $e){

        }
        return false;
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}