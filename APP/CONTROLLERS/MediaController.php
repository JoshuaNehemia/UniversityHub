<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../SERVICE/MediaService.php");
require_once(__DIR__ . "/../config.php");

#endregion

#region USE
use SERVICE\MediaService;
use Exception;
#endregion

class MediaController
{
    #region FIELDS
    private $service;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->service = new MediaService();
    }
    #endregion

    public function save(array $files, array $data)
    {
        if (!isset($data["command"])) {
            throw new Exception("Data incomplete: Command is not sent, save new image (SAVE) or change picture (UPDATE)");
        }
        switch ($data["command"]) {
            case "SAVE":
                $this->checkSaveData($data);
                return $this->service->savePicture($files['image'], $data['name'], $data['type']);
                break;
            case "UPDATE":
                $this->checkUpdateData($data);
                return $this->service->updatePicture($files['image'], $data['name'], $data['type']);
                break;
            default:
                break;
        }
    }

    public function rename(array $data)
    {
        $this->checkRenameData($data);
        return $this->service->renamePicture($data['old_name'], $data['new_name'], $data['ext'], $data['type']);
    }

    private function checkSaveData($data)
    {
        if (!isset($data["name"])) {
            throw new Exception("Data incomplete: file name is not sent");
        }
        if (!isset($data["type"])) {
            throw new Exception("Data incomplete: file type is not sent (MAHASISWA/DOSEN/EVENT)");
        }
    }

    private function checkUpdateData($data)
    {

        if (!isset($data["name"])) {
            throw new Exception("Data incomplete: file name is not sent");
        }
        if (!isset($data["type"])) {
            throw new Exception("Data incomplete: file type is not sent (MAHASISWA/DOSEN/EVENT)");
        }
    }
    private function checkRenameData($data)
    {

        if (!isset($data["ext"])) {
            throw new Exception("Data incomplete: file extension is not sent");
        }
        if (!isset($data["old_name"])) {
            throw new Exception("Data incomplete: file old name is not sent");
        }
        if (!isset($data["new_name"])) {
            throw new Exception("Data incomplete: file new name is not sent");
        }
        if (!isset($data["type"])) {
            throw new Exception("Data incomplete: file type is not sent (MAHASISWA/DOSEN/EVENT)");
        }
    }



}