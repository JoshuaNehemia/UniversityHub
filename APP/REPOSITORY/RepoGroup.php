<?php
namespace REPOSITORY;

#region REQUIRE
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');
require_once(__DIR__ . '/../MODELS/Group.php');
#endregion

#region USE
use CORE\DatabaseConnection;
use MODELS\Group;
use Exception;
#endregion

class RepoGroup
{
    #region FIELDS
    private DatabaseConnection $db;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->db = new DatabaseConnection();
    }
    #endregion

    #region CREATE
    public function createGroup(Group $group): bool
    {
        $sql = "INSERT INTO grup 
            (username_pembuat, nama, deskripsi, tanggal_pembentukan, jenis, kode_pendaftaran)
            VALUES (?, ?, ?, ?, ?, ?);";
        $stmt = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare createGroup statement: " . $conn->error);
            }

            $pembuat = $group->getPembuat();
            $nama = $group->getNama();
            $deskripsi = $group->getDeskripsi();
            $tanggal = $group->getTanggalDibuat();
            $jenis = $group->getJenis();
            $kode = $group->getKode();

            $stmt->bind_param(
                "ssssss",
                $pembuat,
                $nama,
                $deskripsi,
                $tanggal,
                $jenis,
                $kode
            );

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }
            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion

    #region RETRIEVE
    public function findGroupById(int $id): Group
    {
        $sql = "SELECT * FROM grup WHERE idgrup = ?";

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare find group statement: " . $conn->error);
            }

            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if (!$row)
                throw new Exception("Group not found");

            return $this->groupMapper($row);
        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }

    public function findAllGroupByName(string $name, int $limit, int $page, $is_mahasiswa): array
    {
        $mahasiswa_display = $is_mahasiswa ? "AND jenis = 'Publik'" : "";
        $sql = "SELECT * FROM grup WHERE nama LIKE ? {$mahasiswa_display} LIMIT ? OFFSET ?;";

        $name = "%{$name}%";
        $offset = $page * $limit;
        $stmt = null;
        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare find group statement: " . $conn->error);
            }

            $stmt->bind_param("sii", $name, $limit, $offset);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $groups = [];
            while ($row = $result->fetch_assoc()) {
                $groups[] = $this->groupMapper($row);
            }


            return $groups;
        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }

    public function findAllGroupByUsername(string $username, int $limit, int $page, $is_mahasiswa = true): array
    {
        $sql = "SELECT g.* FROM grup g INNER JOIN member_grup mg ON mg.idgrup = g.idgrup WHERE mg.username = ? LIMIT ? OFFSET ?";
        $offset = $page * $limit;
        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare find group statement: " . $conn->error);
            }

            $stmt->bind_param("sii", $username, $limit, $offset);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $groups = [];
            while ($row = $result->fetch_assoc()) {
                $groups[] = $this->groupMapper($row);
            }


            return $groups;
        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion

    #region UPDATE
    public function updateGroup(Group $group): bool
    {
        $sql = "UPDATE grup SET
                nama = ?,
                deskripsi = ?,
                jenis = ?
            WHERE idgrup = ?
        ";

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare update group statement: " . $conn->error);
            }

            $data = $group->toArray();

            $nama = $data['nama'];
            $deskripsi = $data['deskripsi'];
            $jenis = $data['jenis'];
            $id = $data['id'];

            $stmt->bind_param("sssi", $nama, $deskripsi, $jenis, $id);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }
            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion
    #region DELETE
    public function deleteGroup(int $id_group): bool
    {
        $sql = "DELETE FROM grup WHERE idgrup = ?;";

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare delete group statement: " . $conn->error);
            }


            $stmt->bind_param("i", $id_group);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }
            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion

    #region MAPPER
    private function groupMapper(array $row): Group
    {
        $group = new Group();
        $group->setId($row["idgrup"]);
        $group->setPembuat($row['username_pembuat']);
        $group->setNama($row['nama']);
        $group->setDeskripsi($row['deskripsi']);
        $group->setTanggalDibuat($row['tanggal_pembentukan']);
        $group->setJenis($row['jenis']);
        $group->setKode($row['kode_pendaftaran']);
        return $group;
    }
    #endregion

}