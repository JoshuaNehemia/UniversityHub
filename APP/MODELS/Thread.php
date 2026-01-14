<?php

namespace MODELS;

use Exception;

class Thread
{
    #region FIELDS
    private int $id;
    private int $idgrup;
    private string $pembuat;
    private string $tanggalPembuatan;
    private string $status;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
    }
    #endregion

    #region GETTER
    public function getId(): int
    {
        return $this->id;
    }

    public function getPembuat(): string
    {
        return $this->pembuat;
    }

    public function getTanggalPembuatan(): string
    {
        return $this->tanggalPembuatan;
    }

    public function getIdgrup(): int
    {
        return $this->idgrup;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    #endregion

    #region SETTER
    public function setId(int $id): void
    {
        if ($id <= 0) {
            throw new Exception("Thread ID must be a positive integer.");
        }
        $this->id = $id;
    }

    public function setIdgrup(int $idgrup): void
    {
        if ($idgrup <= 0) {
            throw new Exception("Group ID must be a positive integer.");
        }
        $this->idgrup = $idgrup;
    }

    public function setPembuat(string $pembuat): void
    {
        if (trim($pembuat) === '') {
            throw new Exception("Pembuat cannot be empty.");
        }
        $this->pembuat = $pembuat;
    }

    public function setStatus(string $status): void
    {
        if (trim($status) === '') {
            throw new Exception("Status cannot be empty.");
        }

        // FIXED: was $this->status->$status;
        $this->status = $status;
    }

    public function setTanggalPembuatan(string $tanggalPembuatan): void
    {
        if (!preg_match(DATETIME_REGEX, $tanggalPembuatan)) {
            throw new Exception(
                "Invalid tanggalPembuatan format. Expected YYYY-MM-DD HH:MM:SS."
            );
        }

        $this->tanggalPembuatan = $tanggalPembuatan;
    }
    #endregion

    #region UTILITIES
    public function toArray(): array
    {
        $data = [];

        if (isset($this->id)) {
            $data['id'] = $this->id;
        }

        if (isset($this->idgrup)) {
            $data['idgrup'] = $this->idgrup;
        }

        if (isset($this->pembuat)) {
            $data['pembuat'] = $this->pembuat;
        }

        if (isset($this->tanggalPembuatan)) {
            $data['tanggal_pembuatan'] = $this->tanggalPembuatan;
        }

        if (isset($this->status)) {
            $data['status'] = $this->status;
        }

        return $data;
    }

    #endregion
}
