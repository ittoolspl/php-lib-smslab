<?php
declare(strict_types=1);

namespace Ittoolspl\Smslabs;

use Ittoolspl\Smslabs\VO\AccountBalance;
use Ittoolspl\Smslabs\VO\Sender;
use Ittoolspl\Smslabs\VO\SmsDetails;
use Ittoolspl\Smslabs\VO\SmsIn;
use Ittoolspl\Smslabs\VO\SmsOut;
use Ittoolspl\Smslabs\VO\SmsSentResponse;
use Ittoolspl\Smslabs\Exception\EmptySMSQueueException;

class SmslabsClient
{
    const SEND_SMS_URL = '/sendSms';
    const SENDERS_URL = '/senders';
    const SMS_STATUS_URL = '/smsStatus';
    const SMS_LIST_URL = '/sms';
    const SMS_IN_URL = '/smsIn';
    const ACCOUNT_URL = '/account';

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var int
     */
    private $isFlashMessage = 0;

    /**
     * @var string
     */
    private $senderId;

    /**
     * @var int
     */
    private $expirationMinutes = 0;

    /**
     * @var \DateTime
     */
    private $sendDateTime;

    /**
     * @var array[]
     */
    private $smsToSend = [];

    /**
     * @var SmsSentResponse[]
     */
    private $smsStatus = [];

    /**
     * Smslabs constructor.
     * @param string $appKey
     * @param string $secretKey
     */
    public function __construct(string $appKey, string $secretKey)
    {
        $this->client = new HttpClient($appKey, $secretKey);
    }

    /**
     * @return HttpClient
     */
    public function getClient() : HttpClient
    {
        return $this->client;
    }

    /**
     * @param HttpClient $client
     * @return SmslabsClient $this
     */
    public function setClient(HttpClient $client) : SmslabsClient
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return int
     */
    public function isFlashMessage() : int
    {
        return $this->isFlashMessage;
    }

    /**
     * @param int $isFlashMessage
     * @return SmslabsClient $this
     */
    public function setFlashMessage(int $isFlashMessage) : SmslabsClient
    {
        $this->isFlashMessage = $isFlashMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getSenderId() : string
    {
        return $this->senderId;
    }

    /**
     * @param string $senderId
     * @return SmslabsClient $this
     */
    public function setSenderId(string $senderId) : SmslabsClient
    {
        $this->senderId = $senderId;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationMinutes() : int
    {
        return $this->expirationMinutes;
    }

    /**
     * @param int $expirationMinutes
     * @return SmslabsClient $this
     */
    public function setExpirationMinutes(int $expirationMinutes) : SmslabsClient
    {
        if ($expirationMinutes < 1 || $expirationMinutes > 5520) {
            throw new \InvalidArgumentException('Valid values: 1 - 5520');
        }

        $this->expirationMinutes = $expirationMinutes;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSendDateTime() : \DateTime
    {
        return $this->sendDateTime;
    }

    /**
     * @param \DateTime $sendDateTime
     * @return SmslabsClient $this
     */
    public function setSendDateTime(\DateTime $sendDateTime) : SmslabsClient
    {
        $this->sendDateTime = $sendDateTime;

        return $this;
    }

    /**
     * @param string $phoneNumber
     * @param string $message
     * @param bool $isFlashMessage
     * @param int $expirationMinutes
     * @param \DateTime $sendDateTime
     * @return SmslabsClient $this
     */
    public function add(
        string $phoneNumber,
        string $message,
        bool $isFlashMessage = null,
        int $expirationMinutes = null,
        \DateTime $sendDateTime = null
    ) : SmslabsClient {
        if (!$this->checkPhoneNumber($phoneNumber)) {
            throw new \InvalidArgumentException('Invalid phone number');
        }

        $sms = [
            'phone_number' => $phoneNumber,
            'message' => $message,
            'flash' => $isFlashMessage === null ? $this->isFlashMessage : $isFlashMessage,
            'expiration' => $expirationMinutes === null ? $this->expirationMinutes : $expirationMinutes,
            'sender_id' => $this->senderId,
        ];

        if ($sendDateTime instanceof \DateTime) {
            $sms['send_date'] = $sendDateTime->getTimestamp();
        } elseif ($this->sendDateTime instanceof \DateTime) {
            $sms['send_date'] = $this->sendDateTime->getTimestamp();
        }

        $this->smsToSend[] = $sms;

        return $this;
    }

    /**
     * @param string $phoneNumber
     * @return bool
     */
    private function checkPhoneNumber(string $phoneNumber) : bool
    {
        return (bool)preg_match('/^\+[0-9]{10,13}$/', $phoneNumber);
    }

    /**
     * @return SmslabsClient $this
     */
    public function send() : SmslabsClient
    {
        if (empty($this->smsToSend)) {
            throw new EmptySMSQueueException('No messages to send');
        }

        if ($this->senderId === null) {
            throw new \InvalidArgumentException('SenderId is missing');
        }

        foreach ($this->smsToSend as $key => $sms) {
            $httpResponse = $this->client->sendRequest(self::SEND_SMS_URL, $sms, 'PUT');

            $this->smsStatus[] = new SmsSentResponse($httpResponse['account'], $httpResponse['sms_id']);

            unset($this->smsToSend[$key]);
        }

        return $this;
    }

    /**
     * @return SmsSentResponse[]
     */
    public function getSentStatus() : array
    {
        return $this->smsStatus;
    }

    /**
     * @return Sender[]
     */
    public function getAvailableSenders() : array
    {
        $sendersResponse = $this->client->sendRequest(self::SENDERS_URL);

        $list = [];

        foreach ($sendersResponse as $sender) {
            $list[] = Sender::createFromResponseArray($sender);
        }

        return $list;
    }

    /**
     * @return AccountBalance
     */
    public function getAccountBalance() : AccountBalance
    {
        $balanceResponse = $this->client->sendRequest(self::ACCOUNT_URL);

        return new AccountBalance($balanceResponse['account'] / 100);
    }

    /**
     * @return SmsIn[]
     */
    public function getSmsIn() : array
    {
        $smsInResponse = $this->client->sendRequest(self::SMS_IN_URL);

        $list = [];

        foreach ($smsInResponse as $smsIn) {
            $list[] = SmsIn::createFromResponseArray($smsIn);
        }

        return $list;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return SmsOut[]
     */
    public function getSmsOut(int $offset = 0, int $limit = 100) : array
    {
        $smsOutResponse = $this->client->sendRequest(self::SMS_LIST_URL . '?offset=' . $offset . '&limit=' . $limit);

        $list = [];

        foreach ($smsOutResponse as $smsOut) {
            $list[] = SmsOut::createFromResponseArray($smsOut);
        }

        return $list;
    }

    /**
     * @param string $smsId
     * @return bool
     */
    private function isValidSmsId(string $smsId) : bool
    {
        return (bool) preg_match('/[0-9a-f]{24}/', $smsId);
    }

    /**
     * @param string $smsId
     * @return SmsDetails
     */
    public function getSmsDetails(string $smsId) : SmsDetails
    {
        if (!$this->isValidSmsId($smsId)) {
            throw new \InvalidArgumentException('Invalid SMS ID');
        }

        $detailsResponse = $this->client->sendRequest(self::SMS_STATUS_URL . '?id=' . $smsId);

        $smsDetails = SmsDetails::createFromResponseArray($detailsResponse);

        return $smsDetails;
    }

    /**
     * @return array[]
     */
    public function getSmsQueue() : array
    {
        return $this->smsToSend;
    }
}
