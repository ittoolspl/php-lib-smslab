<?php
declare(strict_types=1);

namespace Ittoolspl\Smslabs\VO;

class SmsIn
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $count;

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
    private $receiveTime;

    /**
     * @var string
     */
    private $numberFrom;

    /**
     * @var int
     */
    private $status;

    /**
     * SmsIn constructor.
     * @param string $id
     * @param int $count
     * @param string $content
     * @param string $numberTo
     * @param int $receiveTime
     * @param string $numberFrom
     * @param int $status
     */
    public function __construct(
        string $id,
        int $count,
        string $content,
        string $numberTo,
        int $receiveTime,
        string $numberFrom,
        int $status
    ) {
        $receiveTimeDT = new \DateTime();
        $receiveTimeDT->setTimestamp($receiveTime);

        $this->id          = $id;
        $this->count       = $count;
        $this->content     = $content;
        $this->numberTo    = $numberTo;
        $this->receiveTime = $receiveTimeDT;
        $this->numberFrom  = $numberFrom;
        $this->status      = $status;
    }

    /**
     * @param array $response
     * @return SmsIn
     */
    public static function createFromResponseArray(array $response) : SmsIn
    {
        return new self(
            $response['_id'],
            $response['s_cnt'],
            $response['s_con'],
            $response['no_to'],
            $response['in_t'],
            $response['no_fr'],
            $response['stat']
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
     * @return int
     */
    public function getCount() : int
    {
        return $this->count;
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
    public function getReceiveTime() : \DateTime
    {
        return $this->receiveTime;
    }

    /**
     * @return string
     */
    public function getNumberFrom() : string
    {
        return $this->numberFrom;
    }

    /**
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }
}
