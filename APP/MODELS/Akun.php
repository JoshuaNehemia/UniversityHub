<?php

namespace MODELS;

#region REQUIRE
require_once(__DIR__ . '/../config.php');
#endregion

#region USE
use Exception;
#endregion


class Akun
{
    #region FIELDS
    private string $username;

    private string $nama;

    private string $jenis;
    #endregion

    #region CONSTRUCTOR
    public function __construct(string $username = null, string $nama = null, string $jenis = null)
    {
        if (!empty($username))
            $this->setUsername($username);
        if (!empty($nama))
            $this->setNama($nama);
        if (!empty($jenis))
            $this->setJenis($jenis);
    }
    #endregion

    #region GETTER
    public function getUsername()
    {
        return $this->username;
    }

    public function getNama()
    {
        return $this->nama;
    }

    public function getJenis()
    {
        return $this->jenis;
    }
    #endregion

    #region SETTER
    public function setUsername(string $username)
    {
        if (trim($username) == "")
            throw new Exception("Username tidak boleh kosong");
        $this->username = $username;
    }

    public function setNama(string $nama)
    {
        if (trim($nama) == "")
            throw new Exception("Nama tidak boleh kosong");
        $this->nama = $nama;
    }

    public function setJenis(string $jenis)
    {
        $jenis = strtoupper($jenis);
        if ($jenis == "")
            throw new Exception("Jenis tidak boleh kosong");
        if (!in_array($jenis, ACCOUNT_ROLE)) {
            throw new Exception("Jenis akun illegal. Harus: " . implode(',', ACCOUNT_ROLE));
        }
        $this->jenis = $jenis;
    }

    #endregion

    #region UTILITIES
    public function toArray(): array
    {
        return array(
            "username" => $this->getUsername(),
            "nama" => $this->getNama(),
            "jenis" => $this->getJenis()
        );
    }

    public function fromArray(array $data)
    {
        $this->setUsername($data["username"]);
        $this->setNama($data["nama"]);
        $this->setJenis($data["jenis"]);
    }

    #endregion
}
