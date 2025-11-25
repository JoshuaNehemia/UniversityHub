<?php

namespace MODELS;

use Exception;

class Group
{
    private $id;
    private $pembuat;
    private $nama;
    private $deskripsi;
    private $tanggalDibuat;
    private $jenis;
    private $kode;


    // ================================================================================================
    // CONSTRUCTOR
    // ================================================================================================
    public function __construct($id, $pembuat, $nama, $deskripsi, $tanggalDibuat, $jenis, $kode)
    {
        $this->id = $id;
        $this->pembuat = $pembuat;
        $this->nama = $nama;
        $this->deskripsi = $deskripsi;
        $this->tanggalDibuat = $tanggalDibuat;
        $this->jenis = $jenis;
        $this->kode = $kode;
    }


    // ================================================================================================
    // GETTER
    // ================================================================================================
    public function getId()
    {
        return $this->id;
    }

    public function getPembuat()
    {
        return $this->pembuat;
    }

    public function getNama()
    {
        return $this->nama;
    }

    public function getDeskripsi()
    {
        return $this->deskripsi;
    }

    public function getTanggalDibuat()
    {
        return $this->tanggalDibuat;
    }

    public function getJenis()
    {
        return $this->jenis;
    }

    public function getKode()
    {
        return $this->kode;
    }


    public function getListMember()
    {
        return $this->listMember;
    }

    public function getListThread()
    {
        return $this->listThread;
    }


    // ================================================================================================
    // SETTER
    // ================================================================================================
    public function setId(int $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID must be a positive integer greater than 0.");
        }
        $this->id = (int)$id;
    }

    public function setPembuat($pembuat)
    {
        if (empty(trim($pembuat))) {
            throw new Exception("Creator username (pembuat) cannot be empty.");
        }
        $this->pembuat = $pembuat;
    }

    public function setNama($nama)
    {
        if (empty(trim($nama))) {
            throw new Exception("Group name cannot be empty.");
        }
        if (strlen($nama) < 3) {
            throw new Exception("Group name must be at least 3 characters long.");
        }
        $this->nama = $nama;
    }

    public function setDeskripsi($deskripsi)
    {
        if (empty(trim($deskripsi))) {
            throw new Exception("Description cannot be empty.");
        }
        $this->deskripsi = $deskripsi;
    }

    public function setTanggalDibuat($tanggalDibuat)
    {
        if (empty($tanggalDibuat)) {
            throw new Exception("Date created cannot be empty.");
        }
        $this->tanggalDibuat = $tanggalDibuat;
    }

    public function setJenis($jenis)
    {
        $validTypes = ['Privat', 'Publik'];
        if (!in_array($jenis, $validTypes)) {
            throw new Exception("Invalid Group Type. Must be 'Privat' or 'Publik'.");
        }
        $this->jenis = $jenis;
    }

    public function setKode($kode)
    {
        if (empty(trim($kode))) {
            $this->kode = "0000";
        } else {
            $this->kode = $kode;
        }
    }

    public function setListMember($listMember)
    {
        if (!is_array($listMember)) {
            throw new Exception("Member list must be an array.");
        }
        $this->listMember = $listMember;
    }

    public function setListThread($listThread)
    {
        if (!is_array($listThread)) {
            throw new Exception("Thread list must be an array.");
        }
        $this->listThread = $listThread;
    }
}
