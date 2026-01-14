<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../MODELS/Group.php");
require_once(__DIR__ . "/../SERVICE/GroupService.php");
require_once(__DIR__ . "/../config.php");
// --- PERBAIKAN 1: Tambahkan Require AuthMiddleware ---
require_once(__DIR__ . "/../MIDDLEWARE/AuthMiddleware.php"); 
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
        $this->assertKeysExist($group, array("nama", "deskripsi", "jenis"), "Create group");

        $group['pembuat'] = AuthMiddleware::getLoggedInAccount()['username']; 
        $group['tanggal_dibuat'] = date("Y-m-d H:i:s"); 

        $groupObj = $this->mapToGroup($group);
        
        $groupObj->setKode("", true); 

        return $this->service->createGroup($groupObj);
    }

    public function getGroup($data)
    {
        if (isset($data['id']))
            return $this->service->getGroupById((int)$data['id']);

        if (!isset($data['limit']) || !isset($data['page'])) {
            $data['limit'] = 100; $data['page'] = 0;
        }
        if (isset($data['mine']) && $data['mine'] == 'true') {
            $username = AuthMiddleware::getLoggedInAccount()['username'];
            return $this->service->getGroupByUsername($username, $data['limit'], $data['page']);
        }
        $name = $data['name'] ?? '';
        $isMahasiswa = (AuthMiddleware::getLoggedInAccount()['jenis'] == 'MAHASISWA');
        return $this->service->getGroupByName($name, $data['limit'], $data['page'], $isMahasiswa);
    }

    public function updateGroup($data)
    {
        $this->assertKeysExist($data, array("nama", "deskripsi","jenis", "id"), "Update group");
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