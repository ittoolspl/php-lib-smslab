<?php
declare(strict_types=1);

namespace Ittoolspl\Smslabs\VO;

class SmsSentResponse
{
    private $account;
    private $smsId;

    /**
     * SmsSentResponse constructor.
     * @param int $account
     * @param string $smsId
     */
    public function __construct(int $account, string $smsId)
    {
        $this->account = $account;
        $this->smsId   = $smsId;
    }

    /**
     * @return int
     */
    public function getAccount() : int
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getSmsId() : string
    {
        return $this->smsId;
    }
}
