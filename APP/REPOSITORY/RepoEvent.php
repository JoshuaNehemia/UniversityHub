<?php

namespace REPOSITORY;

#region REQUIRE
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');
require_once(__DIR__ . '/../MODELS/Event.php');
#endregion

#region USE
use CORE\DatabaseConnection;
use MODELS\Event;
use Exception;
#endregion

class RepoEvent
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

    #region RETRIEVE
    public function findEventById(int $id)
    {
        $sql = "SELECT * FROM event WHERE idevent = ?";
        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare retrieve event statement: " . $conn->error);
            }

            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if (!$row) {
                throw new Exception("No Event found");
            }

            return $this->mapper($row);
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

    public function findAllEventByGroupId(int $idgroup, $keyword, int $limit, int $page): array
    {

        $sql = "SELECT * FROM event WHERE idgrup = ? and judul LIKE ? ORDER BY tanggal DESC LIMIT ? OFFSET ?";
        $keyword = "%" . $keyword . "%";
        $offset = $page * $limit;
        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt)
                throw new Exception("Prepare failed: " . $conn->error);

            $stmt->bind_param(
                "isii",
                $idgroup,
                $keyword,
                $limit,
                $offset
            );
            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $events = [];

            while ($row = $result->fetch_assoc()) {
                $events[] = $this->mapper($row);
            }

            return $events;
        } catch (Exception $e) {
            throw new Exception("Error fetching group events: " . $e->getMessage());
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }

    public function getAllEventByUsername(string $username, $keyword, $limit, $page): array
    {
        $sql = "SELECT e.* FROM event e
                    JOIN member_grup mg ON e.idgrup = mg.idgrup
                    WHERE mg.username = ? AND e.judul LIKE ? 
                    ORDER BY e.tanggal DESC
                    LIMIT ? OFFSET ?";
        $keyword = "%" . $keyword . "%";
        $offset = $page * $limit;
        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt)
                throw new Exception("Prepare failed: " . $conn->error);

            $stmt->bind_param("ssii", $username, $keyword, $limit, $offset);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $events = [];

            while ($row = $result->fetch_assoc()) {
                $events[] = $this->mapper($row);
            }
            return $events;
        } catch (Exception $e) {
            throw new Exception("Error fetching user events: " . $e->getMessage());
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion

    #region CREATE
    #region CREATE
    public function createEvent(int $idgroup, Event $event): Event
    {
        $sql = "INSERT INTO event 
            (idgrup, judul, `judul-slug`, tanggal, keterangan, jenis, poster_extension)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare create event statement: " . $conn->error);
            }

            $eve = $event->toArray();

            $stmt->bind_param(
                "issssss",
                $idgroup,
                $eve['judul'],
                $eve['judul-slug'],
                $eve['slug'],
                $eve['tanggal'],
                $eve['keterangan'],
                $eve['jenis'],
                $eve['poster_extention']
            );

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $event->setId((int) $conn->insert_id);
            return $event;

        } catch (Exception $e) {
            throw new Exception("Error creating event: " . $e->getMessage());
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion

    #endregion

    #region UPDATE
#region UPDATE
    public function update(Event $event): bool
    {
        $sql = "UPDATE event SET
                judul = ?,
                `judul-slug` = ?,
                tanggal = ?,
                keterangan = ?,
                jenis = ?,
                poster_extension = ?
            WHERE idevent = ?";

        $stmt = null;
        $conn = null;

        try {
            if (!$event->getId()) {
                throw new Exception("Event ID is required for update.");
            }

            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare update event statement: " . $conn->error);
            }

            $eve = $event->toArray();

            $stmt->bind_param(
                "ssssss",
                $eve['judul'],
                $eve['judul-slug'],
                $eve['slug'],
                $eve['tanggal'],
                $eve['keterangan'],
                $eve['jenis'],
                $eve['poster_extention'],
                $eve['id']
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

    #endregion

    #region DELETE
#region DELETE
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM event WHERE idevent = ?";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare delete event statement: " . $conn->error);
            }

            $stmt->bind_param("i", $id);

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

    #endregion

    #region MAPPER

    public function mapper(array $row): Event
    {
        $event = new Event();

        $event->setId((int) $row['idevent']);
        $event->setJudul($row['judul']);
        $event->setSlug();
        $event->setTanggal($row['tanggal']);
        $event->setKeterangan($row['keterangan']);
        $event->setJenis($row['jenis']);
        $event->setPosterExtension($row['poster_extension']);

        return $event;
    }

    #endregion
}