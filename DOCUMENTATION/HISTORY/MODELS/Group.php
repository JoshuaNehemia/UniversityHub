<?php

namespace MODELS;

require_once(__DIR__ . '/../../DATABASE/Connection.php');

use DATABASE\Connection;
use Exception;
use mysqli_sql_exception;

class Group
{
    // Properties
    private $id;
    private $madeBy;
    private $nama;
    private $deskripsi;
    private $tanggalDibuat;
    private $jenis;
    private $kode;
    private $listMember;
    private $listThread;

    /**
     * Constructor
     */
    public function __construct($id, $madeBy, $nama, $deskripsi, $tanggalDibuat, $jenis, $kode, $listMember = [], $listThread = [])
    {
        $this->id = $id;
        $this->madeBy = $madeBy;
        $this->nama = $nama;
        $this->deskripsi = $deskripsi;
        $this->tanggalDibuat = $tanggalDibuat;
        $this->jenis = $jenis;
        $this->kode = $kode;
        // Ensure these are arrays
        $this->listMember = $listMember ?? [];
        $this->listThread = $listThread ?? [];
    }

// ==========================================================================
    // GETTERS & SETTERS
    // ==========================================================================

    /**
     * @return int
     */
    public function getId() 
    { 
        return $this->id; 
    }

    /**
     * @param int $id
     * @throws Exception if ID is not a positive integer
     */
    public function setId($id) 
    { 
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID must be a positive integer greater than 0.");
        }
        $this->id = (int)$id; 
    }

    /**
     * @return string
     */
    public function getMadeBy() 
    { 
        return $this->madeBy; 
    }

    /**
     * @param string $madeBy
     * @throws Exception if username is empty
     */
    public function setMadeBy($madeBy) 
    { 
        if (empty(trim($madeBy))) {
            throw new Exception("Creator username (MadeBy) cannot be empty.");
        }
        $this->madeBy = $madeBy; 
    }

    /**
     * @return string
     */
    public function getNama() 
    { 
        return $this->nama; 
    }

    /**
     * @param string $nama
     * @throws Exception if group name is empty or too short
     */
    public function setNama($nama) 
    { 
        if (empty(trim($nama))) {
            throw new Exception("Group name cannot be empty.");
        }
        if (strlen($nama) < 3) {
            throw new Exception("Group name must be at least 3 characters long.");
        }
        $this->nama = $nama; 
    }

    /**
     * @return string
     */
    public function getDeskripsi() 
    { 
        return $this->deskripsi; 
    }

    /**
     * @param string $deskripsi
     * @throws Exception if description is empty
     */
    public function setDeskripsi($deskripsi) 
    { 
        if (empty(trim($deskripsi))) {
            throw new Exception("Description cannot be empty.");
        }
        $this->deskripsi = $deskripsi; 
    }

    /**
     * @return string
     */
    public function getTanggalDibuat() 
    { 
        return $this->tanggalDibuat; 
    }

    /**
     * @param string $tanggalDibuat
     * @throws Exception if date is empty
     */
    public function setTanggalDibuat($tanggalDibuat) 
    { 
        if (empty($tanggalDibuat)) {
            throw new Exception("Date created cannot be empty.");
        }
        // Optional: Add Regex check for YYYY-MM-DD if strict format is needed
        $this->tanggalDibuat = $tanggalDibuat; 
    }

    /**
     * @return string
     */
    public function getJenis() 
    { 
        return $this->jenis; 
    }

    /**
     * @param string $jenis
     * @throws Exception if type is not 'Privat' or 'Publik'
     */
    public function setJenis($jenis) 
    { 
        $validTypes = ['Privat', 'Publik'];
        // Case-sensitive check. Use strict in_array check.
        if (!in_array($jenis, $validTypes)) {
            throw new Exception("Invalid Group Type. Must be 'Privat' or 'Publik'.");
        }
        $this->jenis = $jenis; 
    }

    /**
     * @return string
     */
    public function getKode() 
    { 
        return $this->kode; 
    }

    /**
     * @param string $kode
     * Sets default to '0000' if empty, otherwise enforces string
     */
    public function setKode($kode) 
    { 
        // Logic: If empty string provided, default to 0000, otherwise accept it
        if (empty(trim($kode))) {
            $this->kode = "0000";
        } else {
            $this->kode = $kode;
        }
    }

    /**
     * @return array
     */
    public function getListMember() 
    { 
        return $this->listMember; 
    }

    /**
     * @param array $listMember
     * @throws Exception if input is not an array
     */
    public function setListMember($listMember) 
    { 
        if (!is_array($listMember)) {
            throw new Exception("Member list must be an array.");
        }
        $this->listMember = $listMember; 
    }

    /**
     * @return array
     */
    public function getListThread() 
    { 
        return $this->listThread; 
    }

    /**
     * @param array $listThread
     * @throws Exception if input is not an array
     */
    public function setListThread($listThread) 
    { 
        if (!is_array($listThread)) {
            throw new Exception("Thread list must be an array.");
        }
        $this->listThread = $listThread; 
    }


    // ==========================================================================
    // CORE DATABASE OPERATIONS (GROUP)
    // ==========================================================================

    /**
     * Saves the current object to the database (INSERT)
     */
    public function save()
    {
        $sql = "INSERT INTO `grup` 
                (`username_pembuat`, `nama`, `deskripsi`, `tanggal_pembentukan`, `jenis`, `kode_pendaftaran`) 
                VALUES (?, ?, ?, ?, ?, ?)";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) throw new Exception("Database Error: " . Connection::getConnection()->error);

            // 'ssssss' = 6 strings
            $stmt->bind_param(
                'ssssss',
                $this->madeBy,
                $this->nama,
                $this->deskripsi,
                $this->tanggalDibuat,
                $this->jenis,
                $this->kode
            );

            $stmt->execute();

            // Update the ID of this object with the new auto-increment ID
            if ($stmt->affected_rows > 0) {
                $this->id = $stmt->insert_id;
            } else {
                throw new Exception("Failed to insert group. No rows affected.");
            }

            $stmt->close();
        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    /**
     * Static method to find a Group by ID
     * @param int $id
     * @return Group|null
     */
    public static function findById($id)
    {
        $sql = "SELECT * FROM `grup` WHERE `idgrup` = ?"; // Assuming 'idgrup' is the PK based on your schema snippet
        $group = null;

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $group = new Group(
                    $row['idgrup'], // Adapted to likely PK name
                    $row['username_pembuat'],
                    $row['nama'],
                    $row['deskripsi'],
                    $row['tanggal_pembentukan'],
                    $row['jenis'],
                    $row['kode_pendaftaran'],
                    [], []
                );
            }
            $stmt->close();
            return $group;
        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    /**
     * Static method to search Groups by name
     * @param string $keyword
     * @return Group[]
     */
    public static function searchByName($keyword)
    {
        $sql = "SELECT * FROM `grup` WHERE `nama` LIKE ?";
        $groups = [];
        $term = "%" . $keyword . "%";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param('s', $term);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $groups[] = new Group(
                    $row['idgrup'],
                    $row['username_pembuat'],
                    $row['nama'],
                    $row['deskripsi'],
                    $row['tanggal_pembentukan'],
                    $row['jenis'],
                    $row['kode_pendaftaran'],
                    [], []
                );
            }
            $stmt->close();
            return $groups;
        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    // ==========================================================================
    // MEMBER MANAGEMENT (CRUD for member_grup table)
    // ==========================================================================

    /**
     * CREATE: Add a user to this group
     * @param string $username
     * @return bool
     */
    public function addMember($username)
    {
        $sql = "INSERT INTO `member_grup` (`idgrup`, `username`) VALUES (?, ?)";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param('is', $this->id, $username);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            // Error 1062 is MySQL Duplicate Entry code
            if ($e->getCode() == 1062) {
                throw new Exception("User '$username' is already a member of this group.");
            }
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    /**
     * READ: Get all members of this group
     * Populates $this->listMember
     * @return array
     */
    public function fetchMembers()
    {
        // Join with 'akun' table to get user details
        $sql = "SELECT a.* FROM `akun` a
                JOIN `member_grup` mg ON a.username = mg.username
                WHERE mg.idgrup = ?";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param('i', $this->id);
            $stmt->execute();
            $result = $stmt->get_result();

            $this->listMember = []; // Reset list

            while ($row = $result->fetch_assoc()) {
                // Ideally, instantiate Akun objects here.
                // Example: $this->listMember[] = new Akun($row['username'], $row['nama']...);
                
                // For now, we store the data array
                $this->listMember[] = $row;
            }

            $stmt->close();
            return $this->listMember;
        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    /**
     * UPDATE: Swap a member (Replace oldUsername with newUsername)
     * @param string $oldUsername
     * @param string $newUsername
     * @return bool
     */
    public function changeMember($oldUsername, $newUsername)
    {
        $sql = "UPDATE `member_grup` SET `username` = ? WHERE `idgrup` = ? AND `username` = ?";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            // 'sis' -> string, int, string
            $stmt->bind_param('sis', $newUsername, $this->id, $oldUsername);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("Cannot update: Member not found or new username already exists in group.");
            }

            $stmt->close();
            return true;
        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    /**
     * DELETE: Remove a member from this group
     * @param string $username
     * @return bool
     */
    public function removeMember($username)
    {
        $sql = "DELETE FROM `member_grup` WHERE `idgrup` = ? AND `username` = ?";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param('is', $this->id, $username);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }
}