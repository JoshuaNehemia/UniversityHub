<?php

namespace MODEL;

class Group
{
    private $id;
    private $madeBy;
    private $nama;
    private $deskripsi;
    private $tanggalDibuat;
    private $jenis; // ENUM (isinya apa ini?)
    private $kode;
    private $listMember[];
    private $listThread[];
    /**
     * Constructor untuk Class Mahasiswa
     *
     * @param int $id id grup
     * @param string $madeBy pembuat grup
     * @param string $nama nama grup
     * @param string $deskripsi deskripsi grup
     * @param string $tanggalDibuat tanggal pembuatan grup
     * @param string $jenis jenis grup
     * @param string $kode kode pendaftarann
     */
    public function __construct($id, $madeBy, $nama, $deskripsi, $tanggalDibuat, $jenis, $kode,$listMember,$listThread) {
        $this->setId($id);
        $this->setMadeBy($madeBy);
        $this->setNama($nama);
        $this->setDeskripsi($deskripsi);
        $this->setTanggalDibuat($tanggalDibuat);
        $this->setJenis($jenis);
        $this->setKode($kode);
        $this->setListMember($listMember);
        $this->setListThread($listThread);
    }


    // Getter

    /**
     * Mendapatkan nilai id
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Mendapatkan username pembuat grup
     * @return string $madeBy
     */
    public function getMadeBy()
    {
        return $this->madeBy;
    }
    /**
     * Mendapatkan nama grup
     * @return string $nama
     */
    public function getNama()
    {
        return $this->nama;
    }
    /**
     * Mendapatkan deksripsi grup
     * @return string $nama
     */
    public function getDeskripsi()
    {
        return $this->deskripsi;
    }
    /**
     * Mendapatkan tanggal grup dibuat
     * @return string $tanggalDibuat
     */
    public function getTanggalDibuat()
    {
        return $this->tanggalDibuat;
    }
    /**
     * Mendapatkan jenis grup
     * @return string $jenis
     */
    public function getJenis()
    {
        return $this->jenis;
    }
    /**
     * Mendapatkan kode grup
     * @return string $kode
     */
    public function getKode()
    {
        return $this->kode;
    }
    /**
     * Mendapatkan kode grup
     * @return array $listMember
     */
    public function getListMember()
    {
        return $this->listMember;
    }
    /**
     * Mendapatkan kode grup
     * @return array $listThread
     */
    public function getListThread()
    {
        return $this->listMember;
    }
    
    // Setter 
    /**
     * Merubah nilai id grup pada database ke dalam atribut id
     * @param int $id
     */
    public function setId($id)
    {
        if ($id == null) $this->id = 0;
        else $this->id = $id;
    }

    /**
     * Merubah nilai username pembuat grup
     * @param string $madeBy
     */
    public function setMadeBy($madeBy)
    {
        if ($madeBy == "") $this->madeBy = "Tidak Dicantumkan";
        else $this->madeBy = $madeBy;
    }

    /**
     * Merubah nilai nama grup
     * @param string $nama
     */
    public function setNama($nama)
    {
        if ($nama == "") $this->nama = "Grup Baru";
        else $this->nama = $nama;
    }

    /**
     * Merubah nilai deskripsi grup
     * @param string $deskripsi
     */
    public function setDeskripsi($deskripsi)
    {
        if ($deskripsi == "") $this->deskripsi = "Tidak Ada Deskripsi Grup";
        else $this->deskripsi = $deskripsi;
    }
    /**
     * Merubah nilai tanggal pembuatan grup
     * @param string $tanggal
     */
    public function setTanggalDibuat($tanggal)
    {
        if ($tanggal == "") $this->tanggalDibuat = "1970-01-01";
        else $this->tanggalDibuat = $tanggal;
    }

    /**
     * Merubah nilai jenis grup (APA INI???)
     * @param string $jenis
     */
    public function setJenis($jenis)
    {
        if ($jenis == "") $this->jenis = "Normal";
        else $this->jenis = $jenis;
    }
    /**
     * Merubah nilai kode pendaftaran grup (APA INI???)
     * @param string $kode
     */
    public function setKode($kode)
    {
        if ($kode == "") $this->kode = "0000";
        else $this->kode = $kode;
    }

     /**
     * Merubah  list member grup
     * @param array $listMember
     */
    public function setListMember($listMember)
    {
        if ($listMember == null) $this->listMember = [];
        else $this->listMember = $listMember;
    }
     /**
     * Merubah  list thread
     * @param array $listThread
     */
    public function setListThread($listThread)
    {
        if ($listThread == null) $this->listThread = [];
        else $this->listThread = $listThread;
    }

    // Function
    /**
     * Menambah member grup
     * @param Akun $listMember
     */
    public function addMember($member)
    {
        if($member == null) return;
        $this->listMember[] = $member;
    }

     /**
     * Menambah isi thread
     * @param Thread $listThread
     */
    public function addThread($thread)
    {
        if($thread == null) return;
        $this->listThread[] = $thread;
    }


}
