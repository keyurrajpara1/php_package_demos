<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require("../db/DB.php");

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Server started";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $currentDatetime = date("Y-m-d H:i:s");

        $db = new \DB();
        $messageArray = json_decode($msg, true);
        $userId = $messageArray["userId"];
        $message = $messageArray["msg"];

        $lastId = $db->saveChatRoom($userId, $message, $currentDatetime);

        if($lastId){
            $user   = $db->getDataById($userId);
            $messageArray['from'] = $user["name"];
            $messageArray['dt'] = $currentDatetime;
        }

        foreach ($this->clients as $client) {
            if ($from == $client) {
                $messageArray['from']  = "Me";
            } else {
                $messageArray['from']  = $user['name'];
            }
            // if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send(json_encode($messageArray));
            // }
        }

    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}