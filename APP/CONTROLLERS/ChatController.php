<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../MODELS/Chat.php");
require_once(__DIR__ . "/../SERVICE/ChatService.php");
require_once(__DIR__ . "/../config.php");

#endregion

#region USE
use MODELS\Chat;
use SERVICE\ChatService;
use Exception;

#endregion

class ChatController
{
    #region FIELDS
    private $service;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->service = new ChatService();
    }
    #endregion

    public function createChat($data): array
    {
        if (!isset($data["idthread"]))
            throw new Exception("Data incomplete: missing thread id");
        if (!isset($data["username"]))
            throw new Exception("Data incomplete: missing sender username");
        if (!isset($data["isi"]))
            throw new Exception("Data incomplete: missing isi");
        $chat = new Chat();
        $chat->setPengirim($data["username"]);
        $chat->setIsi($data["isi"]);
        return $this->service->addChat($data['idthread'], $chat)->toArray();
    }

    public function getChat($data)
    {
        if (!isset($data["idthread"]))
            throw new Exception("Data incomplete: missing thread id");
        return $this->service->getThreadChat($data["idthread"]);
    }

    public function updateChat($data)
    {
        if (!isset($data["id"]))
            throw new Exception("Data incomplete: missing chat id");
        if (!isset($data["isi"]))
            throw new Exception("Data incomplete: missing isi");
        $chat = new Chat();
        $chat->setId($data['id']);
        $chat->setIsi($data["isi"]);
        return $this->service->updateChat($chat);
    }

    public function deleteChat($data)
    {
        if (!isset($data["id"]))
            throw new Exception("Data incomplete: missing chat id");
        return $this->service->deleteChat($data['id']);
    }


}
