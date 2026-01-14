<?php

namespace SERVICE;

#region REQUIRE
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../MODELS/Thread.php");
require_once(__DIR__ . "/../REPOSITORY/RepoThread.php");
#endregion

#region USE
use MODELS\Thread;
use REPOSITORY\RepoThread;
#endregion

class ThreadService
{
    #region FIELDS
    private $repo;
    private int $lastInsertId = 0;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->repo = new RepoThread();
    }
    #endregion

    #region FUNCTION
    public function createThread(int $group_id, Thread $thread)
    {
        $result = $this->repo->create($group_id, thread: $thread);
        if ($result) {
            $this->lastInsertId = $this->repo->getLastInsertedId();
        }
        return $result;
    }
    
    public function getLastInsertedId(): int
    {
        return $this->lastInsertId;
    }
    
    public function getThread(int $group_id)
    {
        $arr = $this->repo->findByGroupId($group_id);
        $res = [];
        foreach( $arr as $thread ){
            $res[] = $thread->toArray();
        }
        return $res;
    }
    
    public function getThreadById(int $thread_id)
    {
        $thread = $this->repo->findById($thread_id);
        return $thread ? $thread->toArray() : null;
    }
    
    public function updateThread(Thread $thread)
    {
        return $this->repo->update($thread);
    }
    public function deleteThread(int $thread_id)
    {
        return $this->repo->delete($thread_id);
    }
    
    public function closeThread(int $thread_id)
    {
        return $this->repo->updateStatus($thread_id, 'Close');
    }
    #endregion
}