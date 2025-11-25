<?php

namespace MODELS;

require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../upload.php');
use Exception;

class Event
{
    private int $id;
    private string $judul;
    private string $slug;
    private string $tanggal;
    private string $keterangan;
    private string $jenis;
    private string $posterExtension;

    // ================================================================================================
    // CONSTRUCTOR
    // ================================================================================================
    public function __construct(
        int $id,
        string $judul,
        string $slug,
        string $tanggal,
        string $keterangan,
        string $jenis,
        string $posterExtension
    ) {
        $this->setId($id);
        $this->setJudul($judul);
        $this->setSlug($slug);
        $this->setTanggal($tanggal);
        $this->setKeterangan($keterangan);
        $this->setJenis($jenis);
        $this->setPosterExtension($posterExtension);
    }

    // ================================================================================================
    // GETTER
    // ================================================================================================

    public function getId(): int
    {
        return $this->id;
    }
    public function getIdGrup(): int
    {
        return $this->idGrup;
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

    // ================================================================================================
    // SETTER
    // ================================================================================================
    public function setId(int $id): void
    {
        if ($id < 0) throw new Exception("ID must be a non-negative integer.");
        $this->id = $id;
    }

    public function setIdGrup(int $idGrup): void
    {
        if ($idGrup <= 0) throw new Exception("Group ID must be a positive integer.");
        $this->idGrup = $idGrup;
    }

    public function setJudul(string $judul): void
    {
        $judul = trim($judul);
        if (empty($judul)) throw new Exception("Judul cannot be empty.");
        if (strlen($judul) > 45) throw new Exception("Judul cannot exceed 45 characters.");
        $this->judul = $judul;
    }

    public function setSlug(string $slug): void
    {
        $slug = trim($slug);
        if (!preg_match('/^[a-z0-9-]+$/', $slug)) throw new Exception("Slug contains invalid characters.");
        if (strlen($slug) > 45) throw new Exception("Slug cannot exceed 45 characters.");
        $this->slug = $slug;
    }

    public function setTanggal(string $tanggal): void
    {
        if (empty($tanggal)) {
            $this->tanggal = date("Y-m-d H:i:s");
            return;
        }
        if (!preg_match(DATETIME_REGEX, $tanggal)) {
            throw new Exception("Invalid date format. Expected format: Y-m-d H:i:s");
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
            throw new Exception("Invalid 'jenis'. Allowed: " . implode(', ', GROUP_TYPES));
        }
        $this->jenis = $normalized;
    }

    public function setPosterExtension(string $posterExtension): void
    {
        if (empty($posterExtension)) throw new Exception("Extention tidak dapat kosong");
        if  (!in_array($posterExtension,ALLOWED_PICTURE_EXTENSION)) throw new Exception("Extention illegal, Upload file berupa: " . implode(', ', ALLOWED_PICTURE_EXTENSION));
        $this->posterExtension = $posterExtension;
    }

}
