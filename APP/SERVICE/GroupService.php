<?php

namespace SERVICE;

#region REQUIRE
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../MODELS/Group.php");
require_once(__DIR__ . "/../REPOSITORY/RepoGroup.php");
#endregion

#region USE
use MODELS\Group;
use REPOSITORY\RepoGroup;
#endregion

class GroupService
{
    #region FIELDS
    private $repo;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->repo = new RepoGroup();
    }
    #endregion

    #region FUNCTION
    public function createGroup(Group $group): bool
    {
        return $this->repo->createGroup($group);
    }

    public function getGroupById(int $group_id): array
    {
        return $this->repo->findGroupById($group_id)->toArray();
    }
    public function getGroupByName(string $name, int $limit, int $page, bool $is_mahasiswa): array
    {
        $arr = $this->repo->findAllGroupByName($name, $limit, $page, $is_mahasiswa);
        $res = [];
        foreach ($arr as $key => $value) {
            $res[] = $value->toArray();
        }
        return $res;
    }
    public function updateGroup($group)
    {
        return $this->repo->updateGroup($group);
    }

    public function deleteGroup($id_group)
    {
        return $this->repo->deleteGroup($id_group);
    }
    #endregion
}