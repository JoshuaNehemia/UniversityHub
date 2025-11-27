<?php

namespace MODELS;

require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../upload.php');
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');

use CORE\DatabaseConnection;
use Exception;

class Event extends DatabaseConnection
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
        if ($slug !== null) $this->setSlug($slug);
        if ($tanggal !== null) $this->setTanggal($tanggal);
        if ($keterangan !== null) $this->setKeterangan($keterangan);
        if ($jenis !== null) $this->setJenis($jenis);
        if ($posterExtension !== null) $this->setPosterExtension($posterExtension);
    }

    // ================================================================================================
    // GETTER
    // ================================================================================================

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

    // ================================================================================================
    // SETTER
    // ================================================================================================
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

    public function setSlug(string $slug): void
    {
        $slug = trim($slug);
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

    function getArray(){
        return array(
            "id"=>$this->getId(),
            "judul"=>$this->getJudul(),
            "slug"=>$this->getSlug(),
            "tanggal"=>$this->getTanggal(),
            "keterangan"=>$this->getKeterangan(),
            "jenis"=>$this->getJenis(),
            "poster_extension"=>$this->getPosterExtension()
        );
    }

    // ================================================================================================
    // CRUD
    // ================================================================================================
    public function getEvent(int $id): ?self
    {
        $stmt = null;
        try {
            $sql = "SELECT * FROM event WHERE idevent = ?";
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) throw new Exception("Prepare failed: " . $this->conn->error);

            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                return new self(
                    (int)$row['idevent'],
                    $row['judul'],
                    $row['judul-slug'],
                    $row['tanggal'],
                    $row['keterangan'],
                    $row['jenis'],
                    $row['poster_extension']
                );
            }

            return null;
        } catch (Exception $e) {
            throw new Exception("Error finding event: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    public static function getAllGroupEvent(int $idgroup): array
    {
        $db = new self();
        $conn = $db->conn;
        $stmt = null;
        try {
            $sql = "SELECT * FROM event WHERE idgrup = ? ORDER BY tanggal DESC";
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

            $stmt->bind_param("i", $idgroup);
            $stmt->execute();

            $result = $stmt->get_result();
            $events = [];

            while ($row = $result->fetch_assoc()) {
                $events[] = new self(
                    (int)$row['idevent'],
                    $row['judul'],
                    $row['judul-slug'],
                    $row['tanggal'],
                    $row['keterangan'],
                    $row['jenis'],
                    $row['poster_extension']
                );
            }
            return $events;
        } catch (Exception $e) {
            throw new Exception("Error fetching group events: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    public static function getAllUserEvent(string $username): array
    {
        $db = new self();
        $conn = $db->conn;
        $stmt = null;
        try {
            $sql = "SELECT e.* FROM event e
                    JOIN member_grup mg ON e.idgrup = mg.idgrup
                    WHERE mg.username = ?
                    ORDER BY e.tanggal DESC";
            
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

            $stmt->bind_param("s", $username);
            $stmt->execute();

            $result = $stmt->get_result();
            $events = [];

            while ($row = $result->fetch_assoc()) {
                $events[] = new self(
                    (int)$row['idevent'],
                    $row['judul'],
                    $row['judul-slug'],
                    $row['tanggal'],
                    $row['keterangan'],
                    $row['jenis'],
                    $row['poster_extension']
                );
            }
            return $events;
        } catch (Exception $e) {
            throw new Exception("Error fetching user events: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    public function create(int $idgroup): int
    {
        $stmt = null;
        try {
            if (empty($this->judul)) throw new Exception("Judul belum di-set.");
            
            $sql = "INSERT INTO event (idgrup, judul, `judul-slug`, tanggal, keterangan, jenis, poster_extension) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) throw new Exception("Prepare failed: " . $this->conn->error);

            $stmt->bind_param(
                "issssss", 
                $idgroup, 
                $this->judul, 
                $this->slug, 
                $this->tanggal, 
                $this->keterangan, 
                $this->jenis, 
                $this->posterExtension
            );

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $this->id = (int)$this->conn->insert_id;
            
            return $this->id;

        } catch (Exception $e) {
            throw new Exception("Gagal menyimpan event: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    public function update(): bool
    {
        $stmt = null;
        try {
            if (empty($this->id)) throw new Exception("ID Event tidak ditemukan untuk proses update.");

            $sql = "UPDATE event SET 
                    judul = ?, 
                    `judul-slug` = ?, 
                    tanggal = ?, 
                    keterangan = ?, 
                    jenis = ?, 
                    poster_extension = ? 
                    WHERE idevent = ?";

            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) throw new Exception("Prepare failed: " . $this->conn->error);

            // Types: i(idgrup), s, s, s, s, s, s, i(idevent)
            $stmt->bind_param(
                "ssssssi",
                $this->judul,
                $this->slug,
                $this->tanggal,
                $this->keterangan,
                $this->jenis,
                $this->posterExtension,
                $this->id
            );

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            return true;

        } catch (Exception $e) {
            throw new Exception("Gagal mengupdate event: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

    /**
     * DELETE: Menghapus data
     */
    public function delete(): bool
    {
        $stmt = null;
        try {
            if (empty($this->id)) throw new Exception("ID Event tidak ditemukan untuk proses delete.");

            $sql = "DELETE FROM event WHERE idevent = ?";
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) throw new Exception("Prepare failed: " . $this->conn->error);

            $stmt->bind_param("i", $this->id);

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            return true;

        } catch (Exception $e) {
            throw new Exception("Gagal menghapus event: " . $e->getMessage());
        } finally {
            if ($stmt) $stmt->close();
        }
    }

}
