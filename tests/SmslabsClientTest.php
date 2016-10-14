<?php

namespace Ittoolspl\Smslabs\tests;

use Ittoolspl\Smslabs\VO\Sender;
use Ittoolspl\Smslabs\VO\SmsDetails;
use Ittoolspl\Smslabs\VO\SmsIn;
use Ittoolspl\Smslabs\VO\SmsOut;
use Ittoolspl\Smslabs\Exception\EmptySMSQueueException;
use Ittoolspl\Smslabs\HttpClient;
use Ittoolspl\Smslabs\SmslabsClient;
use PHPUnit\Framework\TestCase;

class SmslabsClientTest extends TestCase
{
    private $validResult = [
        0 => [
            'phone_number' => '+48790000000',
            'message' => 'Top secret SMS',
            'flash' => 0,
            'expiration' => 0,
            'sender_id' => 'ITtools',
        ],
        1 => [
            'phone_number' => '+48790000000',
            'message' => 'message',
            'flash' => true,
            'expiration' => 10,
            'sender_id' => 'ITtools',
            'send_date' => 1477960066
        ],
    ];

    protected function tearDown()
    {
        \Mockery::close();
    }

    public function testSmslabsClientAddSmsToQueueValid()
    {
        $sendDateTime = new \DateTime();
        $sendDateTime->setTimestamp($this->validResult[1]['send_date']);

        $sms = new  SmslabsClient('', '');
        $sms->setSenderId('ITtools');
        $addResult = $sms->add('+48790000000', 'Top secret SMS');
        $sms->add('+48790000000', 'message', true, 10, $sendDateTime);
        $queue = $sms->getSmsQueue();

        $this->assertTrue($this->validResult == $queue);
        $this->assertCount(2, $queue);
        $this->assertInstanceOf(SmslabsClient::class, $addResult);
    }

    public function testSmslabsClientCheckSettersValid()
    {
        $sms = new  SmslabsClient('', '');
        $setExpirationMinutesResult = $sms->setExpirationMinutes(1);
        $setClientResult = $sms->setClient(new HttpClient('', ''));
        $setSenderIdResult = $sms->setSenderId('');
        $setFlashMessageResult = $sms->setFlashMessage(true);
        $setSendDateTimeResult = $sms->setSendDateTime(new \DateTime());

        $this->assertInstanceOf(SmslabsClient::class, $setExpirationMinutesResult);
        $this->assertInstanceOf(SmslabsClient::class, $setSenderIdResult);
        $this->assertInstanceOf(SmslabsClient::class, $setClientResult);
        $this->assertInstanceOf(SmslabsClient::class, $setFlashMessageResult);
        $this->assertInstanceOf(SmslabsClient::class, $setSendDateTimeResult);
    }

    public function testSmslabsClientAddSmsToQueueValidThreeMsg()
    {
        $validResultThree = [
            0 => [
                'phone_number' => '+48790000000',
                'message' => 'Top secret SMS',
                'flash' => 0,
                'expiration' => 0,
                'sender_id' => 'ITtools',
            ],
            1 => [
                'phone_number' => '+48790000001',
                'message' => 'Top secret SMM',
                'flash' => 0,
                'expiration' => 0,
                'sender_id' => 'ITtools',
            ],
            2 => [
                'phone_number' => '+48790000002',
                'message' => 'Top secret SMV',
                'flash' => 0,
                'expiration' => 0,
                'sender_id' => 'ITtools',
            ],
        ];

        $sms = new SmslabsClient('', '');
        $sms->setSenderId('ITtools');
        $sms->add('+48790000000', 'Top secret SMS');
        $sms->add('+48790000001', 'Top secret SMM');
        $sms->add('+48790000002', 'Top secret SMV');
        $queue = $sms->getSmsQueue();

        $this->assertTrue($validResultThree == $queue);
        $this->assertCount(3, $queue);
    }

    public function testSmslabsClientAddSmsToQueueInvalidMessage()
    {
        $sms = new  SmslabsClient('', '');
        $sms->setSenderId('ITtools');
        $sms->add('+48790000000', 'Not secret SMS');

        $this->assertNotTrue($this->validResult == $sms->getSmsQueue());
    }

    public function testSmslabsClientAddSmsToQueueInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone number');

        $sms = new SmslabsClient('', '');
        $sms->setSenderId('ITtools');
        $sms->add('+48000000', 'Not secret SMS');
    }

    public function testSmslabsClientSendValid()
    {
        $response = [
            'account' => 1593,
            'sms_id' => '5813f6f4b5ca20c1767b23ca',
        ];

        $sendDate = new \DateTime();

        $sms = new SmslabsClient('', '');
        $sms->setSenderId('ITtools.pl');
        $sms->setFlashMessage(1);
        $sms->setExpirationMinutes(900);
        $sms->setSendDateTime($sendDate);
        $sms->add('+48790000000', 'message');
        $sms->setClient($this->mockHttpClient($response));
        $sendResult = $sms->send();

        $status = $sms->getSentStatus();

        $this->assertInstanceOf(HttpClient::class, $sms->getClient());
        $this->assertInstanceOf(SmslabsClient::class, $sendResult);
        $this->assertEquals('ITtools.pl', $sms->getSenderId());
        $this->assertEquals(1, $sms->isFlashMessage());
        $this->assertEquals(900, $sms->getExpirationMinutes());
        $this->assertEquals($sendDate, $sms->getSendDateTime());
        $this->assertEquals(1593, $status[0]->getAccount());
        $this->assertEquals('5813f6f4b5ca20c1767b23ca', $status[0]->getSmsId());
    }

    public function testSmslabsClientSendEmptyQueue()
    {
        $this->expectException(EmptySMSQueueException::class);
        $this->expectExceptionMessage('No messages to send');

        $sms = new SmslabsClient('', '');
        $sms->send();
    }

    public function testSmslabsClientSendInvalidExpirationMinutes0()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Valid values: 1 - 5520');

        $sms = new SmslabsClient('', '');
        $sms->setExpirationMinutes(0);
    }

    public function testSmslabsClientSendInvalidExpirationMinutes1()
    {
        $sms = new SmslabsClient('', '');
        $sms->setExpirationMinutes(1);

        $this->assertEquals(1, $sms->getExpirationMinutes());
    }

    public function testSmslabsClientSendInvalidExpirationMinutes5520()
    {
        $sms = new SmslabsClient('', '');
        $sms->setExpirationMinutes(5520);

        $this->assertEquals(5520, $sms->getExpirationMinutes());
    }

    public function testSmslabsClientSendInvalidExpirationMinutes5521()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Valid values: 1 - 5520');

        $sms = new SmslabsClient('', '');
        $sms->setExpirationMinutes(5521);
    }

    public function testSmslabsClientSendMissingSenderId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('SenderId is missing');

        $sms = new SmslabsClient('', '');
        $sms->add('+48790000000', 'message');
        $sms->send();
    }

    public function testSmslabsClientGetAvailableSendersValid()
    {
        $response = [
            0 => ['id' => '57cdab26b5c123f15aaaaf1b', 'name' => 'ITTools.pl', 'sender' => '48111111111'],
            1 => ['id' => '58168706b5c123216aaaa6e0', 'name' => 'ITtools', 'sender' => '48000000000'],
            2 => ['id' => '57cec6feb5c123453aaaaf1d', 'name' => 'ITTools', 'sender' => 'ITTools'],
            3 => ['id' => '581686c5b5c123666aaaa6de', 'name' => 'ITtools', 'sender' => 'ITtools'],
        ];

        $sms = new SmslabsClient('', '');
        $sms->setClient($this->mockHttpClient($response));
        $senders = $sms->getAvailableSenders();

        $this->assertCount(4, $senders);
        $this->assertInstanceOf(Sender::class, $senders[0]);
        $this->assertEquals('57cdab26b5c123f15aaaaf1b', $senders[0]->getId());
        $this->assertEquals('ITtools', $senders[3]->getName());
        $this->assertEquals('48000000000', $senders[1]->getSender());
    }

    public function testSmslabsClientGetAccountBalanceValid()
    {
        $response = ['account' => 1526];

        $sms = new SmslabsClient('', '');
        $sms->setClient($this->mockHttpClient($response));
        $balance = $sms->getAccountBalance();

        $this->assertEquals(15.26, $balance->getBalance());
    }

    public function testSmslabsClientGetSmsInValid()
    {
        $response = [
            0 => [
                '_id' => '5800fb5ab5ca20fb5cc03887',
                's_cnt' => 1,
                's_con' => 'Dzieki',
                'no_to' => '48111111111',
                'in_t' => 1476459354,
                'no_fr' => '48000000000',
                'stat' => 3,
            ],
            1 => [
                '_id' => '57cec738b5ca20a359291f1b',
                's_cnt' => 1,
                's_con' => 'Test',
                'no_to' => '48111111111',
                'in_t' => 1473169208,
                'no_fr' => '48000000000',
                'stat' => 3,
            ],
        ];

        $sms = new SmslabsClient('', '');
        $sms->setClient($this->mockHttpClient($response));
        $smsIn = $sms->getSmsIn();

        $this->assertCount(2, $smsIn);
        $this->assertInstanceOf(SmsIn::class, $smsIn[0]);
        $this->assertEquals('57cec738b5ca20a359291f1b', $smsIn[1]->getId());
        $this->assertEquals('Dzieki', $smsIn[0]->getContent());
    }

    public function testSmslabsClientGetSmsOutValid()
    {
        $response = [
            0 => [
                '_id' => '58050a182134242142341721',
                'del_t' => 1476725279,
                's_cnt' => 1,
                'price' => 795,
                's_con' => 'test',
                'no_to' => '+48111111111',
                'in_t' => 1476725272,
                'stat' => 3,
                'stat_d' => 'OK',
                'no_fr' => 'ITTools',
            ],
            1 => [
                '_id' => '523435345235354354581720',
                'del_t' => 1476725218,
                's_cnt' => 1,
                'price' => 795,
                's_con' => 'test',
                'no_to' => '+48790222500',
                'in_t' => 1476725207,
                'stat' => 3,
                'stat_d' => 'OK',
                'no_fr' => 'ITtools',
            ],
        ];

        $sms = new SmslabsClient('', '');
        $sms->setClient($this->mockHttpClient($response));
        $smsOut = $sms->getSmsOut();

        $this->assertCount(2, $smsOut);
        $this->assertInstanceOf(SmsOut::class, $smsOut[0]);
        $this->assertEquals('523435345235354354581720', $smsOut[1]->getId());
        $this->assertEquals('+48111111111', $smsOut[0]->getNumberTo());
    }

    public function testSmslabsClientGetSmsDetailsValid()
    {
        $response = [
            'id' => '58047f3eb5ca207a48581725',
            'from' => 'ITTools',
            'number_to' => '+48790222500',
            'status' => 3,
            'sms_count' => 1,
            'sms_content' => 'test',
            'price' => 795,
        ];

        $sms = new SmslabsClient('', '');
        $sms->setClient($this->mockHttpClient($response));
        $smsDetails = $sms->getSmsDetails('58047f3eb5ca207a48581725');

        $this->assertInstanceOf(SmsDetails::class, $smsDetails);
        $this->assertEquals('58047f3eb5ca207a48581725', $smsDetails->getId());
        $this->assertEquals('ITTools', $smsDetails->getFrom());
    }

    public function testSmslabsClientGetSmsDetailsInvalidID()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid SMS ID');

        $sms = new SmslabsClient('', '');
        $sms->getSmsDetails('test');
    }

    private function mockHttpClient(array $response)
    {
        $httpClientMock = \Mockery::mock(HttpClient::class);
        $httpClientMock
            ->shouldReceive('sendRequest')
            ->andReturn($response);

        return $httpClientMock;
    }
}
