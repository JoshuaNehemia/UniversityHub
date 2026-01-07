<?php

namespace SERVICE;

#region REQUIRE
require_once(__DIR__ . "/../CORE/MediaDatabase.php");
require_once(__DIR__ . "/../REPOSITORY/RepoAccount.php");
require_once(__DIR__ . "/../REPOSITORY/RepoEvent.php");
#endregion

#region USE
use CORE\MediaDatabase;
use REPOSITORY\RepoAccount;
use REPOSITORY\RepoEvent;
use Exception;
#endregion

class MediaService
{
    #region FIELDS
    private $repo;
    private $repo_account;
    private $repo_event;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->repo = new MediaDatabase();
        $this->repo_account = new RepoAccount();
        $this->repo_event = new RepoEvent();
    }
    #endregion

    #region FUNCTION
    public function savePicture($file, $name, $type)
    {
        $ext = $this->repo->store_image($file, $name, $type);
        if ($type == "MAHASISWA") {
            $account = $this->repo_account->findMahasiswaByNRP($name);
            $account->setFotoExtention($ext);
            $result = $this->repo_account->updateMahasiswa($account);
        } else if ($type == "DOSEN") {
            $account = $this->repo_account->findDosenByNPK($name);
            $account->setFotoExtention($ext);
            $result = $this->repo_account->updateDosen($account);
        } else if ($type == "POSTER") {
            $event = $this->repo_event->findEventById($name);
            $event->setPosterExtension($ext);
            $result = $this->repo_event->updateEvent($event);
        } else {
            throw new Exception("Image type is missing");
        }
        return $result;
    }
    public function updatePicture($file, $name, $type)
    {
        $old_ext = "";
        if ($type == "MAHASISWA") {
            $account = $this->repo_account->findMahasiswaByNRP($name);
            $old_ext = $account->getFotoExtention();
        } else if ($type == "DOSEN") {
            $account = $this->repo_account->findDosenByNPK($name);
            $old_ext = $account->getFotoExtention();
        } else if ($type == "POSTER") {
            $event = $this->repo_event->findEventById($name);
            $old_ext = $event->getPosterExtension();
        } else {
            throw new Exception("Image type is missing");
        }
        $this->repo->delete_image($name . "." . $old_ext, $type);
        return $this->savePicture($file, $name, $type);
    }

    public function renamePicture($old_name, $new_name, $ext, $type)
    {
        return $this->repo->rename_image($old_name . "." . $ext, $new_name . "." . $ext, $type);
    }
    #endregion

}
?>