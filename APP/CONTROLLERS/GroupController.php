<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../MODELS/Group.php");
require_once(__DIR__ . "/../SERVICE/GroupService.php");
require_once(__DIR__ . "/../config.php");

#endregion

#region USE
use MODELS\Group;
use SERVICE\GroupService;
use MIDDLEWARE\AuthMiddleware;
use Exception;

#endregion

class GroupController
{
    #region FIELDS
    private $service;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->service = new GroupService();
    }
    #endregion

    public function createGroup($group): bool
    {
        $this->assertKeysExist($group, array("pembuat", "nama", "deskripsi", "pembuat", "tnggal_dibuat", "jenis"), "Create group");
        $group = $this->mapToGroup($group);
        $group->setKode("", true);
        return $this->service->createGroup($group);
    }

    public function getGroup($data)
    {
        if (isset($data['id']))
            return $this->service->getGroupById($data['id']);
        if (isset($data['name']))
            if (!isset($data['limit']))
                throw new Exception("Data is incomplete, no limit");
        if (!isset($data['page']))
            throw new Exception("Data is incomplete, no page");
        return $this->service->getGroupByName($data['name'], $data['limit'], $data['page'], (AuthMiddleware::getLoggedInAccount()['jenis'] == ACCOUNT_ROLE[0]));
    }

    public function updateGroup($data)
    {
        $this->assertKeysExist($data, array("nama", "deskripsi","jenis", "id"), "Create group");
        $group = $this->mapToGroup($data);
        return $this->service->updateGroup($group);
    }

    public function deleteGroup($data){
        $this->assertKeysExist($data, array("id"));
        return $this->service->deleteGroup($data["id"]);
    }

    private function assertKeysExist(array $data, array $keys, string $context = 'Mapper'): void
    {
        $missing = [];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            throw new Exception(
                $context . ' missing required keys: ' . implode(', ', $missing)
            );
        }
    }
    private function mapToGroup(array $data): Group
    {
        $obj = new Group();

        if (array_key_exists('id', $data)) {
            $obj->setId($data['id']);
        }

        if (array_key_exists('pembuat', $data)) {
            $obj->setPembuat($data['pembuat']);
        }

        if (array_key_exists('nama', $data)) {
            $obj->setNama($data['nama']);
        }

        if (array_key_exists('deskripsi', $data)) {
            $obj->setDeskripsi($data['deskripsi']);
        }

        if (array_key_exists('tanggal_dibuat', $data)) {
            $obj->setTanggalDibuat($data['tanggal_dibuat']);
        }

        if (array_key_exists('jenis', $data)) {
            $obj->setJenis($data['jenis']);
        }

        if (array_key_exists('kode', $data)) {
            $obj->setKode($data['kode']);
        }

        return $obj;
    }
}
