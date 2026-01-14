<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../MODELS/Thread.php");
require_once(__DIR__ . "/../SERVICE/ThreadService.php");
require_once(__DIR__ . "/../config.php");
#endregion

#region USE
use SERVICE\ThreadService;
use MODELS\Thread;
use Exception;
#endregion

class ThreadController
{
    #region FIELDS
    private $service;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->service = new ThreadService();
    }
    #endregion

    public function create($data)
    {
        if (!isset($data['idgrup']))
            throw new Exception("Data incomplete: missing group id");
        if (!isset($data['username']))
            throw new Exception("Data incomplete: missing username");

        $thread = $this->mapDataToThread($data);
        $result = $this->service->createThread($data['idgrup'], $thread);

        if ($result) {
            $threadId = $this->service->getLastInsertedId();
            return ['success' => true, 'id' => $threadId, 'idthread' => $threadId];
        }
        
        return ['success' => false];
    }

    public function get($data)
    {
        if (isset($data['id'])) {
            return $this->service->getThreadById($data['id']);
        }

        if (!isset($data['idgrup']))
            throw new Exception("Data incomplete: missing group id or thread id");
        return $this->service->getThread($data['idgrup']);
    }

    public function update($data)
    {
        if (!isset($data['id']))
            throw new Exception("Data incomplete: missing thread id");
        $thread = $this->mapDataToThread($data);
        return $this->service->updateThread($thread);
    }

    public function delete($data)
    {
        if (!isset($data['id']))
            throw new Exception("Data incomplete: missing thread id");

        return $this->service->closeThread($data['id']);
    }

    private function checkData(array $data): void
    {

    }

    private function mapDataToThread(array $data): Thread
    {
        $thread = new Thread();

        if (array_key_exists('id', $data) && $data['id'] !== null) {
            $thread->setId((int) $data['id']);
        }
        
        if (array_key_exists('username', $data) && $data['username'] !== null) {
            $thread->setPembuat($data['username']);
        }

        if (array_key_exists('tanggal_pembuatan', $data) && $data['tanggal_pembuatan'] !== null) {
            $thread->setTanggalPembuatan($data['tanggal_pembuatan']);
        }

        $status = $data['status'] ?? 'OPEN';
        $thread->setStatus($status);

        return $thread;
    }
}