<?php

namespace MODELS;

class Thread
{
    private $id;
    private $pembuat;
    private $tanggalPembuatan;
    private $status;

    public function __construct($id, $pembuat, $tanggalPembuatan, $status)
    {
        $this->setId($id);
        $this->setPembuat($pembuat);
        $this->setTanggalPembuatan($tanggalPembuatan);
        $this->setStatus($status);
    }

    // getter
    public function getId()
    {
        return $this->id;
    }

    public function getPembuat()
    {
        return $this->pembuat;
    }

    public function getTanggalPembuatan()
    {
        return $this->tanggalPembuatan;
    }

    public function getStatus()
    {
        return $this->status;
    }


    // setter
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setPembuat(string $pembuat)
    {
        $this->pembuat = $pembuat;
    }

    public function setStatus(string $status)
    {
        $this->status->$status;
    }

    public function setTanggalPembuatan(string $tanggalPembuatan)
    {
        $this->tanggalPembuatan = $tanggalPembuatan;
    }
}
