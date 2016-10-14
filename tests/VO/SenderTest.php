<?php

namespace Ittoolspl\Smslabs\Tests\Entity;

use Ittoolspl\Smslabs\VO\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testValid()
    {
        $senderData = ['id' => 'id', 'name' => 'name', 'sender' => 'sender'];

        $sender = Sender::createFromResponseArray($senderData);

        $this->assertEquals('id', $sender->getId());
        $this->assertEquals('name', $sender->getName());
        $this->assertEquals('sender', $sender->getSender());
    }
}
