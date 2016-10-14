<?php

namespace Ittoolspl\Smslabs\Tests\Entity;

use Ittoolspl\Smslabs\VO\SmsOut;
use PHPUnit\Framework\TestCase;

class SmsOutTest extends TestCase
{
    public function testValid()
    {
        $timestampDel = 1477708372;
        $timestampInc = 1545698574;

        $dateTimeDel = new \DateTime();
        $dateTimeInc = new \DateTime();

        $dateTimeDel->setTimestamp($timestampDel);
        $dateTimeInc->setTimestamp($timestampInc);

        $smsData = [
            '_id' => 'abcdef',
            'del_t' => $timestampDel,
            's_cnt' => 10,
            'price' => 2234,
            's_con' => 'tessssssst',
            'no_to' => '+48600000000',
            'in_t' => $timestampInc,
            'stat' => 3,
            'stat_d' => 'OK',
            'no_fr' => '+48612345678',
        ];

        $sms = SmsOut::createFromResponseArray($smsData);

        $this->assertEquals('abcdef', $sms->getId());
        $this->assertEquals($dateTimeDel, $sms->getDeliveryTime());
        $this->assertEquals(10, $sms->getCount());
        $this->assertEquals(2234, $sms->getPrice());
        $this->assertEquals('tessssssst', $sms->getContent());
        $this->assertEquals('+48600000000', $sms->getNumberTo());
        $this->assertEquals($dateTimeInc, $sms->getIncomingTime());
        $this->assertEquals(3, $sms->getStatus());
        $this->assertEquals('OK', $sms->getStatusD());
        $this->assertEquals('+48612345678', $sms->getNumberFrom());
    }

    public function testValidMissingDeliveryTime()
    {
        $timestampDel = 0;
        $timestampInc = 1545698574;

        $dateTimeDel = new \DateTime();
        $dateTimeInc = new \DateTime();

        $dateTimeDel->setTimestamp($timestampDel);
        $dateTimeInc->setTimestamp($timestampInc);

        $smsData = [
            '_id' => 'abcdef',
            's_cnt' => 10,
            'price' => 2234,
            's_con' => 'tessssssst',
            'no_to' => '+48600000000',
            'in_t' => $timestampInc,
            'stat' => 3,
            'stat_d' => 'OK',
            'no_fr' => '+48612345678',
        ];

        $sms = SmsOut::createFromResponseArray($smsData);

        $this->assertEquals('abcdef', $sms->getId());
        $this->assertEquals($dateTimeDel, $sms->getDeliveryTime());
        $this->assertEquals(10, $sms->getCount());
        $this->assertEquals(2234, $sms->getPrice());
        $this->assertEquals('tessssssst', $sms->getContent());
        $this->assertEquals('+48600000000', $sms->getNumberTo());
        $this->assertEquals($dateTimeInc, $sms->getIncomingTime());
        $this->assertEquals(3, $sms->getStatus());
        $this->assertEquals('OK', $sms->getStatusD());
        $this->assertEquals('+48612345678', $sms->getNumberFrom());
    }
}
