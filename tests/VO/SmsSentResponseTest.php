<?php

namespace Ittoolspl\Smslabs\Tests\Entity;

use Ittoolspl\Smslabs\VO\SmsSentResponse;
use PHPUnit\Framework\TestCase;

class SmsSentResponseTest extends TestCase
{
    public function testValid()
    {
        $balance = new SmsSentResponse(12345, 'abcdf');

        $this->assertEquals('12345', $balance->getAccount());
        $this->assertEquals('abcdf', $balance->getSmsId());
    }
}
