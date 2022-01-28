<?php


abstract class Signal implements JsonSerializable
{
    protected ?Transporter $transporter;
    protected ?User $client;
    protected string $message;

    /**
     * Signal constructor.
     * @param Transporter $transporter
     * @param User $client
     * @param string $message
     */
    public function __construct(?Transporter $transporter, ?User $client, string $message)
    {
        $this->transporter = $transporter;
        $this->client = $client;
        $this->message = $message;
    }

    /**
     * @return Transporter
     */
    public function getTransporter(): ?Transporter
    {
        return $this->transporter;
    }

    /**
     * @return User
     */
    public function getClient(): ?User
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    abstract public static function all();
    abstract public static function allOf(int $id);

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}