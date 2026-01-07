<?php

namespace REPOSITORY;

#region REQUIRE
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');
require_once(__DIR__ . '/../MODELS/Thread.php');
#endregion

#region USE
use CORE\DatabaseConnection;
use MODELS\Thread;
use Exception;
#endregion

class RepoThread
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
    public function create(Thread $thread, int $idgrup): Thread
    {
        $sql = "
            INSERT INTO thread
            (username_pembuat, idgrup, tanggal_pembuatan, status)
            VALUES (?, ?, ?, ?)
        ";

        $conn = null;
        $stmt = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare CREATE thread: " . $conn->error);
            }

            $pembuat = $thread->getPembuat();
            $tanggal = $thread->getTanggalPembuatan();
            $status = $thread->getStatus();

            $stmt->bind_param(
                "siss",
                $pembuat,
                $idgrup,
                $tanggal,
                $status
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute CREATE thread: " . $stmt->error);
            }

            $thread->setId($conn->insert_id);
            return $thread;

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
    public function findById(int $id): ?Thread
    {
        $sql = "SELECT * FROM thread WHERE idthread = ? LIMIT 1";

        $conn = null;
        $stmt = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare RETRIEVE thread: " . $conn->error);
            }

            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute RETRIEVE thread: " . $stmt->error);
            }

            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {

                $thread = new Thread();
                $thread->setId($row['idthread']);
                $thread->setPembuat($row['username_pembuat']);
                $thread->setTanggalPembuatan($row['tanggal_pembuatan']);
                $thread->setStatus($row['status']);

                return $thread;
            }

            return null;

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
    public function update(Thread $thread): bool
    {
        $sql = "
            UPDATE thread
            SET status = ?, tanggal_pembuatan = ?
            WHERE idthread = ?
        ";

        $conn = null;
        $stmt = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare UPDATE thread: " . $conn->error);
            }

            $status = $thread->getStatus();
            $tanggal = $thread->getTanggalPembuatan();
            $id = $thread->getId();

            $stmt->bind_param(
                "ssi",
                $status,
                $tanggal,
                $id
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute UPDATE thread: " . $stmt->error);
            }

            return $stmt->affected_rows > 0;

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
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM thread WHERE idthread = ?";

        $conn = null;
        $stmt = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare DELETE thread: " . $conn->error);
            }

            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute DELETE thread: " . $stmt->error);
            }

            return $stmt->affected_rows > 0;

        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion
}
