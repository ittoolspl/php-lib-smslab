<?php

namespace Ittoolspl\Smslabs\Tests\Entity;

use Ittoolspl\Smslabs\VO\AccountBalance;
use PHPUnit\Framework\TestCase;

class AccountBalanceTest extends TestCase
{
    public function testValid()
    {
        $balance = new AccountBalance(159.36);

        $this->assertEquals(159.36, $balance->getBalance());
    }
}
