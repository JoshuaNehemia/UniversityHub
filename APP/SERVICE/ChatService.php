<?php

namespace SERVICE;

#region REQUIRE
require_once(__DIR__ . "/../MODELS/Chat.php");
require_once(__DIR__ . "/../REPOSITORY/RepoChat.php");
#endregion

#region USE
use MODELS\Chat;
use REPOSITORY\RepoChat;
#endregion

class ChatService
{
    #region FIELDS
    private $repo;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->repo = new RepoChat();
    }
    #endregion

    #region FUNCTION
    public function addChat($thread_id, $chat): Chat
    {
        return $this->repo->create($thread_id, $chat);
    }
    public function getThreadChat($thread_id)
    {
        $arr = $this->repo->findByThreadId($thread_id);
        $chats = [];
        foreach ($arr as $chat) {
            $chats[] = $chat->toArray();
        }
        return $chats;
    }
    public function updateChat($chat)
    {
        return $this->repo->update($chat);
    }
    public function deleteChat($id_chat)
    {
        return $this->repo->delete($id_chat);
    }
    #endregion
}