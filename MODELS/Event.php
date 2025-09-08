<?php

namespace MODELS;

class Event
{
    private $id;
    private $judul;
    private $slug;
    private $tanggal;
    private $keterangan;
    private $jenis;
    private $posterExtension;

    public function __construct($id, $judul, $slug, $tanggal, $keterangan, $jenis, $posterExtension)
    {
        $this->setId($id);
        $this->setJudul($judul);
        $this->setSlug($slug);
        $this->setTanggal($tanggal);
        $this->setKeterangan($keterangan);
        $this->setJenis($jenis);
        $this->setPosterExtension($posterExtension);
    }
    
    // GETTER
    public function getId()
    {
        return $this->id;
    }

    public function getJudul()
    {
        return $this->judul;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getTanggal()
    {
        return $this->tanggal;
    }
    public function getKeterangan()
    {
        return $this->keterangan;
    }
    public function getJenis()
    {
        return $this->jenis;
    }
    public function getPosterExtension()
    {
        return $this->posterExtension;
    }

    // SETTER
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setJudul(string $judul)
    {
        $this->judul = $judul;
    }

    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function setTanggal(string $tanggal)
    {
        if ($tanggal == "") $this->tanggal = date("Y-m-d-H-i-s");
        $this->tanggal = $tanggal;
    }
    public function setKeterangan(string $keterangan)
    {
        $this->keterangan = $keterangan;
    }
    public function setJenis(string $jenis)
    {
        $this->jenis = $jenis;
    }
    public function setPosterExtension(string $posterExtension)
    {
        $this->posterExtension;
    }
}
