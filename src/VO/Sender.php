<?php
declare(strict_types=1);

namespace Ittoolspl\Smslabs\VO;

class Sender
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $sender;

    /**
     * Sender constructor.
     * @param string $id
     * @param string $name
     * @param string $sender
     */
    public function __construct(string $id, string $name, string $sender)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->sender = $sender;
    }

    /**
     * @param array $response
     * @return Sender
     */
    public static function createFromResponseArray(array $response) : Sender
    {
        return new self(
            $response['id'],
            $response['name'],
            $response['sender']
        );
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSender() : string
    {
        return $this->sender;
    }
}
