<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../config.php");

use Exception;

class UploadController
{
    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = UPLOAD_DATABASE;
    }

    private function getFileExtension(string $fileName): string
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }

    private function saveFoto(array $file, string $savePath, string $saveAs): string
    {
        $ext = $this->getFileExtension($file['name']);

        if (!in_array($ext, ALLOWED_PICTURE_EXTENSION)) {
            throw new Exception("Tipe file tidak valid");
        }

        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        $path = $savePath . $saveAs . "." . $ext;

        if (file_exists($path)) {
            unlink($path);
        }

        if (!move_uploaded_file($file['tmp_name'], $path)) {
            throw new Exception("Gagal upload");
        }

        return $ext;
    }

    public function saveMahasiswaProfilePicture(array $file, string $nrp)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Gagal upload");
        }
        $savePath = $this->uploadDir . "PROFILE/" . ACCOUNT_ROLE[0] . "/";
        return $this->saveFoto($file, $savePath, $nrp);
    }

    public function saveDosenProfilePicture(array $file, string $npk)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Gagal upload");
        }
        $savePath = $this->uploadDir . "PROFILE/" . ACCOUNT_ROLE[1] . "/";
        return $this->saveFoto($file, $savePath, $npk);
    }


    private function renameLocalFile(string $oldPath, string $newBaseName)
    {
        if (!file_exists($oldPath)) {
            throw new Exception("File tidak ditemukan untuk di-rename");
        }

        $directory = dirname($oldPath) . "/";
        $ext = strtolower(pathinfo($oldPath, PATHINFO_EXTENSION));

        $newPath = $directory . $newBaseName . "." . $ext;

        if (!rename($oldPath, $newPath)) {
            throw new Exception("Gagal rename file");
        }
    }

    public function renameMahasiswaProfilePicture($oldnrp, $newnrp, $extension)
    {
        $oldPath = $this->uploadDir . "PROFILE/" . ACCOUNT_ROLE[0] . "/" . $oldnrp . "." . $extension;
        $this->renameLocalFile($oldPath, $newnrp);
    }
    public function renameDosenProfilePicture($oldnpk, $newnpk, $extension)
    {
        $oldPath = $this->uploadDir . "PROFILE/" . ACCOUNT_ROLE[1] . "/" . $oldnpk . "." . $extension;
        $this->renameLocalFile($oldPath, $newnpk);
    }

    public function deleteMahasiswaProfilePicture($nrp, $extension)
    {
        $path = $this->uploadDir . "PROFILE/" . ACCOUNT_ROLE[0] . "/" . $nrp . "." . $extension;
        if (file_exists($path)) {
            unlink($path);
        }
        else throw new Exception("Gagal menghapus gambar");
    }
    public function deleteDosenProfilePicture($npk, $extension)
    {
        $path = $this->uploadDir . "PROFILE/" . ACCOUNT_ROLE[1] . "/" . $npk . "." . $extension;
        if (file_exists($path)) {
            unlink($path);
        }
        else throw new Exception("Gagal menghapus gambar");
    }
}
