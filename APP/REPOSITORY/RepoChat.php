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
            VALUES (?, ?, ?, ?)
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
            $isi      = $chat->getIsi();
            $tanggal  = $chat->getTanggalPembuatan();

            $stmt->bind_param(
                "isss",
                $idThread,
                $pengirim,
                $isi,
                $tanggal
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute CREATE chat: " . $stmt->error);
            }

            $chat->setId($conn->insert_id);
            return $chat;

        } finally {
            if ($stmt) $stmt->close();
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
            SELECT *
            FROM chat
            WHERE idthread = ?
            ORDER BY tanggal_pembuatan ASC
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

                $chat = new Chat();
                $chat->setId($row["idchat"]);
                $chat->setPengirim($row["username_pembuat"]);
                $chat->setTanggalPembuatan($row["tanggal_pembuatan"]);
                $chat->setIsi($row["isi"]);

                $chats[] = $chat;
            }

            return $chats;

        } finally {
            if ($stmt) $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion

    #region UPDATE
    public function update(Chat $chat): bool
    {
        $sql = "
            UPDATE chat
            SET isi = ?, tanggal_pembuatan = ?
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
            $isi     = $chat->getIsi();
            $tanggal = $chat->getTanggalPembuatan();
            $id      = $chat->getId();

            $stmt->bind_param(
                "ssi",
                $isi,
                $tanggal,
                $id
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to execute UPDATE chat: " . $stmt->error);
            }

            return $stmt->affected_rows > 0;

        } finally {
            if ($stmt) $stmt->close();
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
            if ($stmt) $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion
}
