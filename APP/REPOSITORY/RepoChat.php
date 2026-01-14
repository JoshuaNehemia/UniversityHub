<?php

namespace REPOSITORY;

#region REQUIRE
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');
require_once(__DIR__ . '/../MODELS/Chat.php');
#endregion

#region USE
use CORE\DatabaseConnection;
use MODELS\Chat;
use Exception;
#endregion

class RepoChat
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
    public function create(int $idThread, Chat $chat): Chat
    {
        $sql = "
            INSERT INTO chat
            (idthread, username_pembuat, isi, tanggal_pembuatan)
            VALUES (?, ?, ?, CURRENT_TIMESTAMP)
        ";

        $conn = null;
        $stmt = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare CREATE chat: " . $conn->error);
            }

            $pengirim = $chat->getPengirim();
            $isi = $chat->getIsi();

            $stmt->bind_param(
                "iss",
                $idThread,
                $pengirim,
                $isi
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute CREATE chat: " . $stmt->error);
            }

            $chat->setId($conn->insert_id);
            return $chat;

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
    public function findByThreadId(int $idThread): array
    {
        $sql = "
            SELECT
                c.idchat,
                c.username_pembuat,
                c.isi,
                c.tanggal_pembuatan,
                m.nama AS nama_mahasiswa,
                d.nama AS nama_dosen
            FROM chat c
            JOIN akun a ON a.username = c.username_pembuat
            LEFT JOIN mahasiswa m ON m.nrp = a.nrp_mahasiswa
            LEFT JOIN dosen d ON d.npk = a.npk_dosen
            WHERE c.idthread = ?
            ORDER BY c.tanggal_pembuatan ASC
        ";

        $conn = null;
        $stmt = null;
        $chats = [];

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare RETRIEVE chat: " . $conn->error);
            }

            $stmt->bind_param("i", $idThread);

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute RETRIEVE chat: " . $stmt->error);
            }

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $nama = null;
                if (!empty($row["nama_mahasiswa"])) {
                    $nama = $row["nama_mahasiswa"];
                } elseif (!empty($row["nama_dosen"])) {
                    $nama = $row["nama_dosen"];
                } else {
                    $nama = $row["username_pembuat"]; 
                }

                $chat = new Chat();
                $chat->setId($row["idchat"]);
                $chat->setPengirim($row["username_pembuat"]); 
                $chat->setNamaPengirim($nama);                
                $chat->setIsi($row["isi"]);
                $chat->setTanggalPembuatan($row["tanggal_pembuatan"]);

                $chats[] = $chat;
            }

            return $chats;

        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn)
                $this->db->close();
        }
    }
    #endregion

    #region UPDATE
    public function update(Chat $chat): bool
    {
        $sql = "
            UPDATE chat
            SET isi = ?
            WHERE idchat = ?
        ";

        $conn = null;
        $stmt = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare UPDATE chat: " . $conn->error);
            }
            $isi = $chat->getIsi();
            $id = $chat->getId();

            $stmt->bind_param(
                "si",
                $isi,
                $id
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute UPDATE chat: " . $stmt->error);
            }

            return $stmt->affected_rows === 1;

        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }

    #region DELETE
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM chat WHERE idchat = ?";

        $conn = null;
        $stmt = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare DELETE chat: " . $conn->error);
            }

            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute DELETE chat: " . $stmt->error);
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
