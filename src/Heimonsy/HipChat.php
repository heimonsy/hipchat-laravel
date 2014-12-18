<?php

namespace Heimonsy;

use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use GorkaLaucirica\HipchatAPIv2Client\Client;
use GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI;
use GorkaLaucirica\HipchatAPIv2Client\Model\Message;
use Buzz\Client\Curl;
use Buzz\Browser;


class HipChat
{
    public $client;
    public $roomApi;
    public $roomId;

    public function __construct($token, $roomId)
    {
        if (empty($token) || empty($roomId)) {
            throw \Exception('token or room id not configure');
        }
        $this->roomId = $roomId;

        $curl = new Curl();
        $curl->setTimeout(15);

        $this->client = new Client(new OAuth2($token), new Browser($curl));
        $this->roomApi = new RoomAPI($this->client);
    }

    public function notify($info, $notify = true, $color = Message::COLOR_GREEN)
    {
        $message = $this->getMessage($info, $notify, $color);
        $this->roomApi->sendRoomNotification($this->roomId, $message);
    }

    public function notifyAll($info, $notify = true, $color = Message::COLOR_GREEN)
    {
        static $room;
        if ($room === null) {
            $room = $this->roomApi->getRoom($this->roomId);
        }

        $metion = '';
        foreach ($room->getParticipants() as $participant) {
            $metion .=  "@{$participant->getMentionName()} ";
        }
        $this->notify("{$metion}\n{$info}", $notify, $color);
    }

    public function getMessage($message, $notify, $color)
    {
        return (new Message())->setMessage($message)->setColor($color)->setNotify($notify)->setMessageFormat(Message::FORMAT_TEXT);
    }
}
