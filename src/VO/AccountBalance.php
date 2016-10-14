<?php
declare(strict_types=1);

namespace Ittoolspl\Smslabs\VO;

class AccountBalance
{
    /**
     * @var float
     */
    private $balance;

    /**
     * AccountBalance constructor.
     * @param float $balance
     */
    public function __construct(float $balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return float
     */
    public function getBalance() : float
    {
        return $this->balance;
    }
}
