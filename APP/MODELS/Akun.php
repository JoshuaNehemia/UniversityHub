<?php

namespace MODELS;

require_once(__DIR__ . '/../config.php');

use Exception;

class Akun
{
    private $username;
    private $nama;
    private $jenis;

    // ================================================================================================
    // CONSTRUCTOR
    // ================================================================================================
    public function __construct(string $username, string $nama, string $jenis)
    {
        $this->setUsername($username);
        $this->setNama($nama);
        $this->setJenis($jenis);
    }

    // ================================================================================================
    // GETTER
    // ================================================================================================

    public function getUsername()
    {
        return $this->username;
    }

    public function getJenis()
    {
        return $this->jenis;
    }

    public function getNama()
    {
        return $this->nama;
    }

    // ================================================================================================
    // SETTER
    // ================================================================================================
    public function setUsername(string $username)
    {
        if ($username == "") throw new Exception("Username tidak boleh kosong", 1);
        $this->username = $username;
    }

    public function setNama(string $nama)
    {
        if ($nama == "") throw new Exception("Nama tidak boleh kosong", 1);
        $this->nama = $nama;
    }

    public function setJenis(string $jenis)
    {
        $jenis = strtoupper($jenis);
        if ($jenis == "") throw new Exception("Jenis tidak boleh kosong");
        if(!in_array($jenis,ACCOUNT_ROLE)) throw new Exception("Jenis akun yang diberikan illegal, {$jenis} tidak terdapat pada sistem. Harus berupa, " + implode(',',ACCOUNT_ROLE));
        $this->jenis = $jenis;
    }
}
