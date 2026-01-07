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
            throw new Exception("Data incomplete missing group id");
        if (!isset($data['username']))
            throw new Exception("Data incomplete missing username username");
        $thread = $this->mapDataToThread($data);
        return $this->service->createThread($data['idgrup'], $thread);
    }
    public function get($data)
    {
        if (!isset($data['idgrup']))
            throw new Exception("Data incomplete missing group id");
        return $this->service->getThread($data['idgrup']);
    }
    public function update($data)
    {
        if (!isset($data['id']))
            throw new Exception("Data incomplete missing thread id");
        $thread = $this->mapDataToThread($data);
        return $this->service->updateThread( $thread);
    }

    public function delete($data)
    {
        
        if (!isset($data['id']))
            throw new Exception("Data incomplete missing thread id");
        return $this->service->deleteThread($data['id']);
    }

    private function checkData(array $data): void
    {
        $requiredKeys = [
            'status'
        ];

        $missing = [];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data) || $data[$key] === null) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            throw new Exception(
                "Thread data is incomplete. Missing or null keys: " . implode(', ', $missing)
            );
        }
    }


    private function mapDataToThread(array $data): Thread
    {
        $this->checkData($data);

        $thread = new Thread();

        if (array_key_exists('id', $data) && $data['id'] !== null) {
            $thread->setId((int) $data['id']);
        }
        if (array_key_exists('tanggal_pembuatan', $data) && $data['tanggal_pembuatan'] !== null) {
            $thread->setTanggalPembuatan((int) $data['tanggal_pembuatan']);
        }
        if (array_key_exists('username', $data) && $data['username'] !== null) {
            $thread->setPembuat((int) $data['username']);
        }

        $thread->setStatus($data['status']);

        return $thread;
    }

}