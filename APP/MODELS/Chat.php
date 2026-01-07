<?php
namespace MODELS;

class Chat
{
    #region FIELDS
    private $id;
    private $pengirim;
    private $isi;
    private $tanggalPembuatan;
    #endregion
    
    #region CONSTRUCTOR
    public function __construct()
    {
    }
    #endregion

    #region GETTER
    public function getId()
    {
        return $this->id;
    }

    public function getPengirim()
    {
        return $this->pengirim;
    }

    public function getIsi()
    {
        return $this->isi;
    }

    public function getTanggalPembuatan()
    {
        return $this->tanggalPembuatan;
    }
    #endregion
    #region SETTER
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setPengirim(string $pengirim)
    {
        $this->pengirim = $pengirim;
    }

    public function setIsi(string $isi)
    {
        if ($isi == "")
            $this->isi = "Message is failed to sent, please re-sent it";
        $this->isi = $isi;
    }

    public function setTanggalPembuatan(string $tanggalPembuatan)
    {
        if ($tanggalPembuatan == "")
            $this->tanggalPembuatan = date("Y-m-d-H-i-s");
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

        if (isset($this->pengirim)) {
            $data['pengirim'] = $this->pengirim;
        }

        if (isset($this->isi)) {
            $data['isi'] = $this->isi;
        }

        if (isset($this->tanggalPembuatan)) {
            $data['tanggal_pembuatan'] = $this->tanggalPembuatan;
        }

        return $data;
    }
    #endregion
}

?>