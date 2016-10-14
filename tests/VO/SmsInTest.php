<?php

namespace Ittoolspl\Smslabs\Tests\Entity;

use Ittoolspl\Smslabs\VO\SmsIn;
use PHPUnit\Framework\TestCase;

class SmsInTest extends TestCase
{
    public function testConstructAndGetters()
    {
        $timestamp = 1477707542;
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($timestamp);

        $smsInData = [
            '_id' => 'asd',
            's_cnt' => 2,
            's_con' => 'test',
            'no_to' => '+48790000000',
            'in_t' => $timestamp,
            'no_fr' => '+48791111111',
            'stat' => 3,
        ];

        $sms = SmsIn::createFromResponseArray($smsInData);

        $this->assertEquals('asd', $sms->getId());
        $this->assertEquals(2, $sms->getCount());
        $this->assertEquals('test', $sms->getContent());
        $this->assertEquals('+48791111111', $sms->getNumberFrom());
        $this->assertEquals($dateTime, $sms->getReceiveTime());
        $this->assertEquals('+48790000000', $sms->getNumberTo());
        $this->assertEquals(3, $sms->getStatus());
    }
}
