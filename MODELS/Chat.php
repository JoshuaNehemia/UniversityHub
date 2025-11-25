<?php
namespace MODELS;

class Chat{
    private $id;
    private $pengirim;
    private $isi;
    private $tanggalPembuatan;

    public function __construct($id,$pengirim,$isi,$tanggalPembuatan){
        $this->setId($id);
        $this->setPengirim($pengirim);
        $this->setIsi($isi);
        $this->setTanggalPembuatan($tanggalPembuatan);
    }

    // GETTER
    public function getId(){
        return $this->id;
    }

    public function getPengirim(){
        return $this->pengirim;
    }

    public function getIsi(){
        return $this->isi;
    }

    public function getTanggalPembuatan(){
        return $this->tanggalPembuatan;
    }

    // SETTER
    public function setId(int $id){
        $this->id=$id;
    }

    public function setPengirim(string $pengirim){
        $this->pengirim=$pengirim;
    }

    public function setIsi(string $isi){
        if($isi == "") $this->isi = "Message is failed to sent, please re-sent it";
        $this->isi=$isi;
    }

    public function setTanggalPembuatan(string $tanggalPembuatan){
        if($tanggalPembuatan == "") $this->tanggalPembuatan = date("Y-m-d-H-i-s");
        $this->tanggalPembuatan = $tanggalPembuatan;
    }
}

?>