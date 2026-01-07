<?php

namespace CORE;

#region REQUIRE
require_once(__DIR__ . "/../config.php");
#endregion

use Exception;

class MediaDatabase
{
    private string $database_address = __DIR__ . "/../DATABASE/";

    private array $folders = [
        "MAHASISWA" => "PROFILE/MAHASISWA/",
        "DOSEN" => "PROFILE/DOSEN/",
        "POSTER" => "EVENT/",
        "EVENT" => "EVENT/",
    ];

    public function delete_image(string $file_name, string $type): bool
    {
        if (empty($file_name)) {
            throw new Exception("File name is empty");
        }

        if (!isset($this->folders[$type])) {
            throw new Exception("Invalid media type");
        }

        $basePath = $this->database_address . $this->folders[$type];

        if (!is_dir($basePath)) {
            return false;
        }

        if (pathinfo($file_name, PATHINFO_EXTENSION)) {
            $path = $basePath . $file_name;

            if (file_exists($path) && unlink($path)) {
                return true;
            }

            return false;
        }

        foreach (ALLOWED_PICTURE_EXTENSION as $ext) {
            $path = $basePath . $file_name . "." . $ext;

            if (file_exists($path) && unlink($path)) {
                return true;
            }
        }

        return false;
    }

    public function store_image(array $file, string $save_as, string $type): string
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Upload error");
        }

        if (!isset($this->folders[$type])) {
            throw new Exception("Invalid media type");
        }

        if (!isset($file['name'], $file['tmp_name'], $file['size'])) {
            throw new Exception("File is not valid");
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, ALLOWED_PICTURE_EXTENSION)) {
            throw new Exception("File type is not valid");
        }

        if ($file['size'] > MAX_IMAGE_SIZE) {
            throw new Exception("File size exceeds limit");
        }

        $savePath = $this->database_address . $this->folders[$type];

        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        $path = $savePath . $save_as . "." . $ext;

        if (file_exists($path)) {
            unlink($path);
        }

        if (!move_uploaded_file($file['tmp_name'], $path)) {
            throw new Exception("Failed to upload");
        }

        return $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));;
    }

    public function rename_image(string $old_name, string $new_name, string $type): bool
    {
        if (empty($old_name) || empty($new_name)) {
            throw new Exception("File name cannot be empty");
        }

        if (!isset($this->folders[$type])) {
            throw new Exception("Invalid media type");
        }

        $basePath = $this->database_address . $this->folders[$type];

        if (!is_dir($basePath)) {
            throw new Exception("Media directory not found");
        }

        if (pathinfo($old_name, PATHINFO_EXTENSION)) {
            $oldPath = $basePath . $old_name;

            if (!file_exists($oldPath)) {
                throw new Exception("Source file not found");
            }

            if (!pathinfo($new_name, PATHINFO_EXTENSION)) {
                $new_name .= "." . pathinfo($old_name, PATHINFO_EXTENSION);
            }

            return rename($oldPath, $basePath . $new_name);
        }

        foreach (ALLOWED_PICTURE_EXTENSION as $ext) {
            $oldPath = $basePath . $old_name . "." . $ext;

            if (file_exists($oldPath)) {
                if (!pathinfo($new_name, PATHINFO_EXTENSION)) {
                    $new_name .= "." . $ext;
                }

                return rename($oldPath, $basePath . $new_name);
            }
        }

        throw new Exception("Source file not found");
    }
}
