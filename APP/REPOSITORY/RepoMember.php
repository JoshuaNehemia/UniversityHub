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
    public function addMember(int $id_group, string $username): bool
    {
        $sql = "INSERT INTO member_grup (idgrup, username) VALUES (?, ?)";

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare add member group statement: " . $conn->error);
            }

            $stmt->bind_param("is", $id_group, $username);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn)
                $conn->close();
        }
    }
    #endregion

    #region RETRIEVE
    public function findGroupMember(int $id_group)
    {
        $sql = "SELECT 
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

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare add member group statement: " . $conn->error);
            }

            $stmt->bind_param("i", $id_group);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
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
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn)
                $conn->close();
        }
    }
    #endregion

    #region DELETE
    public function deleteMember(int $id_group, string $username): bool
    {
        $sql = "DELETE FROM member_grup WHERE idgrup = ? AND username = ?";

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare delete member group statement: " . $conn->error);
            }

            $stmt->bind_param("is", $id_group, $username);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn)
                $conn->close();
        }
    }

    #endregion
}