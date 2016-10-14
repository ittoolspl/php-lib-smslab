<?php
declare(strict_types=1);

namespace Ittoolspl\Smslabs\VO;

class SmsDetails
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $numberTo;

    /**
     * @var int
     */
    private $status;

    /**
     * @var int
     */
    private $smsCount;

    /**
     * @var string
     */
    private $smsContent;

    /**
     * @var float
     */
    private $priceInGrosz;

    /**
     * SmsDetails constructor.
     * @param string $id
     * @param string $from
     * @param string $numberTo
     * @param int $status
     * @param int $smsCount
     * @param string $smsContent
     * @param float $priceInGrosz
     */
    public function __construct(
        string $id,
        string $from,
        string $numberTo,
        int $status,
        int $smsCount,
        string$smsContent,
        float $priceInGrosz
    ) {
        $this->id           = $id;
        $this->from         = $from;
        $this->numberTo     = $numberTo;
        $this->status       = $status;
        $this->smsCount     = $smsCount;
        $this->smsContent   = $smsContent;
        $this->priceInGrosz = $priceInGrosz;
    }

    /**
     * Creates SmsDetails object by array
     * @param array $response
     * @return SmsDetails
     */
    public static function createFromResponseArray(array $response) : SmsDetails
    {
        return new self(
            $response['id'],
            $response['from'],
            $response['number_to'],
            $response['status'],
            $response['sms_count'],
            $response['sms_content'],
            ($response['price'] / 100)
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
    public function getFrom() : string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getNumberTo() : string
    {
        return $this->numberTo;
    }

    /**
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getSmsCount() : int
    {
        return $this->smsCount;
    }

    /**
     * @return string
     */
    public function getSmsContent() : string
    {
        return $this->smsContent;
    }

    /**
     * @return float
     */
    public function getPriceInGrosz() : float
    {
        return $this->priceInGrosz;
    }
}
