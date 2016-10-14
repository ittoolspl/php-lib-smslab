<?php

namespace Ittoolspl\Smslabs\Tests\Entity;

use Ittoolspl\Smslabs\VO\SmsDetails;
use PHPUnit\Framework\TestCase;

class SmsDetailsTest extends TestCase
{
    public function testValid()
    {
        $smsData = [
            'id' => 'rem ips',
            'from' => '+48791234567',
            'number_to' => '+48790666666',
            'status' => 3,
            'sms_count' => 21,
            'sms_content' => 'Lorem ipsum',
            'price' => 175,
        ];

        $sms = SmsDetails::createFromResponseArray($smsData);

        $this->assertEquals('rem ips', $sms->getId());
        $this->assertEquals('+48791234567', $sms->getFrom());
        $this->assertEquals('+48790666666', $sms->getNumberTo());
        $this->assertEquals(3, $sms->getStatus());
        $this->assertEquals(21, $sms->getSmsCount());
        $this->assertEquals('Lorem ipsum', $sms->getSmsContent());
        $this->assertEquals(1.75, $sms->getPriceInGrosz());
    }
}
