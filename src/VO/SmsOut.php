<?php
declare(strict_types=1);

namespace Ittoolspl\Smslabs\VO;

class SmsOut
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $deliveryTime;

    /**
     * @var int
     */
    private $count;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $numberTo;

    /**
     * @var \DateTime
     */
    private $incomingTime;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $statusD;

    /**
     * @var string
     */
    private $numberFrom;

    /**
     * SmsOut constructor.
     * @param string $id
     * @param int $deliveryTime
     * @param int $count
     * @param float $price
     * @param string $content
     * @param string $numberTo
     * @param int $incomingTime
     * @param int $status
     * @param string $statusD
     * @param string $numberFrom
     */
    public function __construct(
        string $id,
        int $deliveryTime,
        int $count,
        float $price,
        string $content,
        string $numberTo,
        int $incomingTime,
        int $status,
        string $statusD,
        string $numberFrom
    ) {
        if ($deliveryTime !== null) {
            $deliveryTimeDT = new \DateTime();
            $deliveryTimeDT->setTimestamp($deliveryTime);
            $this->deliveryTime = $deliveryTimeDT;
        }

        $incomingTimeDT = new \DateTime();
        $incomingTimeDT->setTimestamp($incomingTime);
        $this->incomingTime = $incomingTimeDT;

        $this->id         = $id;
        $this->count      = $count;
        $this->price      = $price;
        $this->content    = $content;
        $this->numberTo   = $numberTo;
        $this->status     = $status;
        $this->statusD    = $statusD;
        $this->numberFrom = $numberFrom;
    }

    /**
     * @param array $response
     * @return SmsOut
     */
    public static function createFromResponseArray(array $response) : SmsOut
    {
        return new self(
            $response['_id'],
            array_key_exists('del_t', $response) ? $response['del_t'] : 0,
            $response['s_cnt'],
            $response['price'],
            $response['s_con'],
            $response['no_to'],
            $response['in_t'],
            $response['stat'],
            $response['stat_d'],
            $response['no_fr']
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
     * @return \DateTime
     */
    public function getDeliveryTime() : \DateTime
    {
        return $this->deliveryTime;
    }

    /**
     * @return int
     */
    public function getCount() : int
    {
        return $this->count;
    }

    /**
     * @return float
     */
    public function getPrice() : float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getNumberTo() : string
    {
        return $this->numberTo;
    }

    /**
     * @return \DateTime
     */
    public function getIncomingTime() : \DateTime
    {
        return $this->incomingTime;
    }

    /**
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getStatusD() : string
    {
        return $this->statusD;
    }

    /**
     * @return string
     */
    public function getNumberFrom() : string
    {
        return $this->numberFrom;
    }
}
