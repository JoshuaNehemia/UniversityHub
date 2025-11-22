<?php

namespace MODELS;

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

    // --- GETTERS ---

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

    // --- SETTERS ---

    public function setId(int $id): void
    {
        if ($id <= 0) {
            throw new Exception("ID must be a positive integer.");
        }
        $this->id = $id;
    }

    public function setJudul(string $judul): void
    {
        $judul = trim($judul);
        
        if (empty($judul)) {
            throw new Exception("Judul cannot be empty.");
        }
        
        if (strlen($judul) > 255) {
            throw new Exception("Judul cannot exceed 255 characters.");
        }

        $this->judul = $judul;
    }

    public function setSlug(string $slug): void
    {
        $slug = trim($slug);

        // Regex: Only allows lowercase letters, numbers, and hyphens
        if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            throw new Exception("Slug contains invalid characters. Use only lowercase alphanumeric and dashes.");
        }

        $this->slug = $slug;
    }

    public function setTanggal(string $tanggal): void
    {
        if (empty($tanggal)) {
            throw new Exception("Invalid date format. Expected format: Y-m-d H:i:s");
        }

        // Validate format strictly as a string using Regex (Y-m-d H:i:s)
        if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $tanggal)) {
            throw new Exception("Invalid date format. Expected format: Y-m-d H:i:s");
        }

        $this->tanggal = $tanggal;
    }

    public function setKeterangan(string $keterangan): void
    {
        // Clean input to prevent basic XSS or extra whitespace
        $this->keterangan = trim($keterangan);
    }

    public function setJenis(string $jenis): void
    {
        // Example Constraint: restrict 'jenis' to specific categories
        $allowedJenis = ['concert', 'webinar', 'workshop', 'meetup'];
        
        if (!in_array(strtolower($jenis), $allowedJenis)) {
            throw new Exception("Invalid 'jenis'. Allowed: " . implode(', ', $allowedJenis));
        }

        $this->jenis = strtolower($jenis);
    }

    public function setPosterExtension(string $posterExtension): void
    {

    }
}