<?php

namespace MODELS;

#region REQUIRE
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');
#endregion

#region USE
use CORE\DatabaseConnection;
use Exception;
#endregion

class Event extends DatabaseConnection
{
    #region FIELDS
    private int $id;
    private string $judul;
    private string $slug;
    private string $tanggal;
    private string $keterangan;
    private string $jenis;
    private string $posterExtension;
    #endregion 

    #region CONSTRUCTOR
    public function __construct(
        ?int $id = null,
        ?string $judul = null,
        ?string $slug = null,
        ?string $tanggal = null,
        ?string $keterangan = null,
        ?string $jenis = null,
        ?string $posterExtension = null
    ) {
        parent::__construct(); 

        if ($id !== null) $this->setId($id);
        if ($judul !== null) $this->setJudul($judul);
        if ($slug !== null) $this->setSlug();
        if ($tanggal !== null) $this->setTanggal($tanggal);
        if ($keterangan !== null) $this->setKeterangan($keterangan);
        if ($jenis !== null) $this->setJenis($jenis);
        if ($posterExtension !== null) $this->setPosterExtension($posterExtension);
    }
    #endregion
    
    #region GETTER
    public function getId(): int
    {
        return $this->id;
    }
    public function getJudul(): string
    {
        return $this->judul;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function getTanggal(): string
    {
        return $this->tanggal;
    }
    public function getKeterangan(): string
    {
        return $this->keterangan;
    }
    public function getJenis(): string
    {
        return $this->jenis;
    }
    public function getPosterExtension(): string
    {
        return $this->posterExtension;
    }
    #endregion

    #region SETTER
    public function setId(int $id): void
    {
        if ($id < 0) throw new Exception("Id tidak boleh negative.");
        $this->id = $id;
    }

    public function setJudul(string $judul): void
    {
        $judul = trim($judul);
        if (empty($judul)) throw new Exception("Judul tidak bisa kosong.");
        if (strlen($judul) > 45) throw new Exception("Judul tidak bisa melebihi 45 characters.");
        $this->judul = $judul;
    }

    public function setSlug(): void
    {
        $judul = $this->getJudul();
        $slug = trim($judul);
        $slug = explode(" ", $slug);
        $slug = implode("-", $slug);
        $this->slug = $slug;
    }

    public function setTanggal(string $tanggal): void
    {
        if (!preg_match(DATETIME_REGEX, $tanggal)) {
            throw new Exception("Invalid date format. Ganti format menjadi: YYYY-MM-DD HH:MM:SS");
        }
        $this->tanggal = $tanggal;
    }

    public function setKeterangan(string $keterangan): void
    {
        $this->keterangan = trim($keterangan);
    }

    public function setJenis(string $jenis): void
    {
        $normalized = ucfirst(strtolower($jenis));
        if (!in_array($normalized, GROUP_TYPES)) {
            throw new Exception("Jenis tidak boleh {$jenis} harus: " . implode(', ', GROUP_TYPES));
        }
        $this->jenis = $normalized;
    }

    public function setPosterExtension(string $posterExtension): void
    {
        if (empty($posterExtension)) throw new Exception("Extention tidak dapat kosong");
        if (!in_array($posterExtension, ALLOWED_PICTURE_EXTENSION)) throw new Exception("Extention illegal, Upload file berupa: " . implode(', ', ALLOWED_PICTURE_EXTENSION));
        $this->posterExtension = $posterExtension;
    }

    function toArray(){
        return array(
            "id"=>$this->getId(),
            "judul"=>$this->getJudul(),
            "slug"=>$this->getSlug(),
            "tanggal"=>$this->getTanggal(),
            "keterangan"=>$this->getKeterangan(),
            "jenis"=>$this->getJenis(),
            "poster_extention"=>$this->getPosterExtension()
        );
    }
    #endregion
    
}
