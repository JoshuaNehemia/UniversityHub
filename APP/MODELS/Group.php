<?php

namespace MODELS;

#region REQUIRE
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/Dosen.php');
require_once(__DIR__ . '/Mahasiswa.php');
#endregion
#region USE
use MODELS\Mahasiswa;
use MODELS\Dosen;
use Exception;
#endregion
class Group
{
    #region FIELDS
    private $id;
    private $pembuat;
    private $nama;
    private $deskripsi;
    private $tanggalDibuat;
    private $jenis;
    private $kode;
    #endregion

    #region CONSTRUCTOR
    public function __construct(
    ) {
    }
    #endregion

    #region GETTER
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
    #endregion

    #region SETTER
    public function setId(int $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID harus berupa angka positif lebih dari 0.");
        }
        $this->id = (int)$id;
    }

    public function setPembuat($pembuat)
    {
        if (empty(trim($pembuat))) {
            throw new Exception("Username pembuat tidak boleh kosong.");
        }
        $this->pembuat = $pembuat;
    }

    public function setNama($nama)
    {
        if (empty(trim($nama))) {
            throw new Exception("Nama grup tidak boleh kosong.");
        }
        if (strlen($nama) < 3) {
            throw new Exception("Nama grup harus memiliki minimal 3 karakter.");
        }
        $this->nama = $nama;
    }

    public function setDeskripsi($deskripsi)
    {
        if (empty(trim($deskripsi))) {
            throw new Exception("Deskripsi tidak boleh kosong.");
        }
        $this->deskripsi = $deskripsi;
    }

    public function setTanggalDibuat($tanggalDibuat)
    {
        if (empty(trim($tanggalDibuat))) throw new Exception("Group tanggal dibuat can't be empty");
        if(!(preg_match_all(DATETIME_REGEX, $tanggalDibuat)))throw new Exception("Group tanggal dibuat is not in correct format YYYY-MM-DD HH:mm:SS");
        $this->tanggalDibuat = $tanggalDibuat;
    }

    public function setJenis($jenis)
    {
        $normalized = ucfirst(strtolower($jenis));
        if (defined('GROUP_TYPES') && !in_array($normalized, GROUP_TYPES)) {
            throw new Exception("Jenis Grup tidak valid. Harus 'Privat' atau 'Publik'.");
        }
        $this->jenis = $normalized;
    }

    public function setKode($kode)
    {
        if (empty(trim($kode))) {
            $this->kode = "0000";
        } else {
            $this->kode = $kode;
        }
    }
    #endregion

    #region UTILITIES
    public function toArray()
    {
        return array(
            "id" => $this->getId(),
            "pembuat" => $this->getPembuat(),
            "nama" => $this->getNama(),
            "deskripsi" => $this->getDeskripsi(),
            "tanggal_dibuat" => $this->getTanggalDibuat(),
            "jenis" => $this->getJenis(),
            "kode" => $this->getKode()
        );
    }
    #endregion
}
