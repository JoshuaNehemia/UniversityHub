<?php

namespace MODELS;

require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/Dosen.php');
require_once(__DIR__ . '/Mahasiswa.php');
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');

use MODELS\Mahasiswa;
use MODELS\Dosen;
use CORE\DatabaseConnection;
use Exception;

class Group extends DatabaseConnection
{
    private $id;
    private $pembuat;
    private $nama;
    private $deskripsi;
    private $tanggalDibuat;
    private $jenis;
    private $kode;


    // ================================================================================================
    // CONSTRUCTOR
    // ================================================================================================
    public function __construct(
        $id = null,
        $pembuat = null,
        $nama = null,
        $deskripsi = null,
        $tanggalDibuat = null,
        $jenis = null,
        $kode = null
    ) {
        parent::__construct();
        $this->id = $id;
        $this->pembuat = $pembuat;
        $this->nama = $nama;
        $this->deskripsi = $deskripsi;
        $this->tanggalDibuat = $tanggalDibuat;
        $this->jenis = $jenis;
        $this->kode = $kode;
    }


    // ================================================================================================
    // GETTER
    // ================================================================================================
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


    // ================================================================================================
    // SETTER
    // ================================================================================================
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

    public function setTanggalDibuat()
    {
        if (empty($tanggal)) {
            $this->tanggalDibuat = date("Y-m-d H:i:s");
            return;
        }
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
    // ================================================================================================
    // FUNCTION
    // ================================================================================================
    public static function readArray(array $group, $id = null): Group
    {
        $g = new Group();
        $g->setPembuat($group['pembuat']);
        $g->setNama($group['nama']);
        $g->setDeskripsi($group['deskripsi']);
        $g->setTanggalDibuat();
        $g->setJenis($group['jenis']);
        if ($id != null) $g->setId($id);
        return $g;
    }

    public function getArray()
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

    // ================================================================================================
    // CREATE
    // ================================================================================================
    public function create()
    {
        $stmt = null;
        try {
            $sql = "INSERT INTO grup 
                    (username_pembuat, nama, deskripsi, tanggal_pembentukan, jenis, kode_pendaftaran)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query (Create): " . $this->conn->error);
            }

            $stmt->bind_param(
                "ssssss",
                $this->pembuat,
                $this->nama,
                $this->deskripsi,
                $this->tanggalDibuat,
                $this->jenis,
                $this->kode
            );

            if (!$stmt->execute()) {
                throw new Exception("Gagal mengeksekusi pembuatan grup: " . $stmt->error);
            }

            $this->setId($this->conn->insert_id);
            return $this;
        } catch (Exception $e) {
            throw new Exception("Error Create Group: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    // ================================================================================================
    // UPDATE
    // ================================================================================================
    public function update()
    {
        $stmt = null;
        try {
            if (!$this->id) throw new Exception("ID Grup tidak ditemukan untuk proses update.");

            $sql = "UPDATE grup SET
                        nama = ?,
                        deskripsi = ?,
                        jenis = ?
                    WHERE idgrup = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query (Update): " . $this->conn->error);
            }

            $stmt->bind_param(
                "sssi",
                $this->nama,
                $this->deskripsi,
                $this->jenis,
                $this->id
            );

            if (!$stmt->execute()) {
                throw new Exception("Gagal memperbarui data grup: " . $stmt->error);
            }

            return $this;
        } catch (Exception $e) {
            throw new Exception("Error Update Group: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    // ================================================================================================
    // DELETE
    // ================================================================================================
    public function delete()
    {
        $stmt = null;
        try {
            if (!$this->id) throw new Exception("ID Grup tidak ditemukan untuk proses delete.");

            $sql = "DELETE FROM grup WHERE idgrup = ?";
            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query (Delete): " . $this->conn->error);
            }

            $stmt->bind_param("i", $this->id);

            if (!$stmt->execute()) {
                throw new Exception("Gagal menghapus grup: " . $stmt->error);
            }
            $num = $stmt->affected_rows;
            if ($num != 1) throw new Exception("Tidak ada grup yang dihapus");
            return true;
        } catch (Exception $e) {
            throw new Exception("Error Delete Group: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    // ================================================================================================
    // GET GROUP BY ID 
    // ================================================================================================
    public static function getGroupById($id): Group
    {
        $instance = new self();
        $stmt = null;

        try {
            $sql = "SELECT * FROM grup WHERE idgrup = ?";
            $stmt = $instance->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query (GetById): " . $instance->conn->error);
            }

            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("Gagal mengambil data grup: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if (!$row) throw new Exception("Tidak ada grup yang sesuai");

            return new Group(
                $row['idgrup'],
                $row['username_pembuat'],
                $row['nama'],
                $row['deskripsi'],
                $row['tanggal_pembentukan'],
                $row['jenis'],
                $row['kode_pendaftaran']
            );
        } catch (Exception $e) {
            throw new Exception("Error Get Group By ID: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
            // Note: Kita tidak menutup koneksi ($instance->conn) disini karena mungkin dipakai ulang,
            // tapi menutup prepared statement ($stmt) itu wajib.
        }
    }

    // ================================================================================================
    // SEARCH GROUP
    // ================================================================================================
    public static function getAllGroupByName(int $limit, int $offset, $search = "")
    {
        $instance = new self();
        $stmt = null;
        $offset *= $limit;
        try {
            $like = "%$search%";
            $sql = "SELECT * FROM grup WHERE nama LIKE ? LIMIT ? OFFSET ?;";

            $stmt = $instance->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query (Search): " . $instance->conn->error);
            }

            $stmt->bind_param("sii", $like, $limit, $offset);

            if (!$stmt->execute()) {
                throw new Exception("Gagal mencari grup: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $groups = [];

            while ($row = $result->fetch_assoc()) {
                $groups[] = new Group(
                    $row['idgrup'],
                    $row['username_pembuat'],
                    $row['nama'],
                    $row['deskripsi'],
                    $row['tanggal_pembentukan'],
                    $row['jenis'],
                    $row['kode_pendaftaran']
                );
            }

            return $groups;
        } catch (Exception $e) {
            throw new Exception("Error Search Group: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }
    public static function getAllGroupByNameForMahasiswa(int $limit, int $offset, $search = "")
    {
        $instance = new self();
        $stmt = null;
        $offset *= $limit;
        try {
            $like = "%$search%";
            $sql = "SELECT * FROM grup WHERE nama LIKE ? AND jenis= 'Publik' LIMIT ? OFFSET ?;";

            $stmt = $instance->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query (Search): " . $instance->conn->error);
            }

            $stmt->bind_param("sii", $like, $limit, $offset);

            if (!$stmt->execute()) {
                throw new Exception("Gagal mencari grup: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $groups = [];

            while ($row = $result->fetch_assoc()) {
                $groups[] = new Group(
                    $row['idgrup'],
                    $row['username_pembuat'],
                    $row['nama'],
                    $row['deskripsi'],
                    $row['tanggal_pembentukan'],
                    $row['jenis'],
                    $row['kode_pendaftaran']
                );
            }

            return $groups;
        } catch (Exception $e) {
            throw new Exception("Error Search Group: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    // ================================================================================================
    // ADD MEMBER
    // ================================================================================================
    public function addMember($username)
    {
        $stmt = null;
        try {
            $sql = "INSERT INTO member_grup (idgrup, username) VALUES (?, ?)";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query (Add Member): " . $this->conn->error);
            }

            $stmt->bind_param("is", $this->id, $username);

            if (!$stmt->execute()) {
                throw new Exception("Gagal menambahkan anggota ke grup: " . $stmt->error);
            }

            if ($stmt->affected_rows != 1) {
                throw new Exception("Tidak menambahkan anggota ke grup, tidak ada perubahan");
            }
            return true;
        } catch (Exception $e) {
            // Bisa handle error duplicate entry (kode 1062) biar pesan lebih enak
            if ($this->conn->errno == 1062) {
                throw new Exception("User tersebut sudah menjadi anggota grup ini.");
            }
            throw new Exception("Error Add Member: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    // ================================================================================================
    // DELETE MEMBER
    // ================================================================================================
    public function deleteMember($username)
    {
        $stmt = null;
        try {
            $sql = "DELETE FROM member_grup WHERE idgrup = ? AND username = ?";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query (Delete Member): " . $this->conn->error);
            }

            $stmt->bind_param("is", $this->id, $username);

            if (!$stmt->execute()) {
                throw new Exception("Gagal menghapus anggota dari grup: " . $stmt->error);
            }

            return true;
        } catch (Exception $e) {
            throw new Exception("Error Delete Member: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    // ================================================================================================
    // GET ALL MEMBERS (FULL DATA)
    // ================================================================================================
    public function getAllMember()
    {
        $stmt = null;
        try {
            $sql = "
                SELECT 
                    a.username,
                    m.nama AS 'nama_mahasiswa',
                    m.nrp, 
                    m.foto_extention,
                    d.nama AS 'nama_dosen',
                    d.npk,
                    d.foto_extension
                FROM member_grup mg
                JOIN akun a ON mg.username = a.username
                LEFT JOIN mahasiswa m ON a.nrp_mahasiswa = m.nrp
                LEFT JOIN dosen d ON a.npk_dosen = d.npk
                WHERE mg.idgrup = ?
            ";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query (Get Members): " . $this->conn->error);
            }

            $stmt->bind_param("i", $this->id);

            if (!$stmt->execute()) {
                throw new Exception("Gagal mengambil data anggota: " . $stmt->error);
            }

            $members = [
                "DOSEN" => [],
                "MAHASISWA" => []
            ];

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                if (!empty($row["npk"])) {
                    unset($row['nrp']);
                    unset($row['nama_mahasiswa']);
                    unset($row['foto_extention']);
                    $members["DOSEN"][] = $row;
                }
                if (!empty($row["nrp"])) {
                    unset($row['npk']);
                    unset($row['nama_dosen']);
                    unset($row['foto_extentsion']);
                    $members["MAHASISWA"][] = $row;
                }
            }

            return $members;
        } catch (Exception $e) {
            throw new Exception("Error Get All Members: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    public static function isMember(int $idgrup, string $username): bool
    {
        $instance = new self();
        $stmt = null;

        try {
            $sql = "SELECT 1 FROM member_grup WHERE idgrup = ? AND username = ? LIMIT 1;";

            $stmt = $instance->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query Check Member: " . $instance->conn->error);
            }

            $stmt->bind_param("is", $idgrup, $username);
            $stmt->execute();

            return $stmt->get_result()->num_rows > 0;
        } catch (Exception $e) {
            throw new Exception("Error Checking Member: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }


    // ================================================================================================
    // SEARCH GROUP
    // ================================================================================================
    public static function getAllGroupJoinedByUser(string $username, int $limit, int $offset, string $search = "")
    {
        $instance = new self();
        $stmt = null;
        $offset *= $limit;

        try {
            $like = "%$search%";

            $sql = "(SELECT g.* 
            FROM member_grup mg
            INNER JOIN grup g ON mg.idgrup = g.idgrup
            WHERE mg.username = ?
            AND g.nama LIKE ?)

            UNION

            (SELECT *
            FROM `grup`
            WHERE username_pembuat = ?
            AND nama LIKE ?
            )

            LIMIT ? OFFSET ?;";


            $stmt = $instance->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Gagal mempersiapkan query Get Joined Group: " . $instance->conn->error);
            }

            $stmt->bind_param("ssssii", $username, $like, $username, $like, $limit, $offset);

            if (!$stmt->execute()) {
                throw new Exception("Gagal mengambil grup yang diikuti: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $groups = [];

            while ($row = $result->fetch_assoc()) {
                $groups[] = new Group(
                    $row['idgrup'],
                    $row['username_pembuat'],
                    $row['nama'],
                    $row['deskripsi'],
                    $row['tanggal_pembentukan'],
                    $row['jenis'],
                    $row['kode_pendaftaran']
                );
            }

            return $groups;
        } catch (Exception $e) {
            throw new Exception("Gagal mendapatkan grup yang diikuti: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }
}
