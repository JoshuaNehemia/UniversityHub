<?php

namespace MODELS;

require_once(__DIR__ . '/../../DATABASE/Connection.php');

use DATABASE\Connection;
use Exception;

class Event
{
    // Typed properties matching DB columns
    private int $id;         // Maps to 'idevent'
    private int $idGrup;     // Maps to 'idgrup'
    private string $judul;
    private string $slug;    // Maps to 'judul-slug'
    private string $tanggal;
    private string $keterangan;
    private string $jenis;
    private string $posterExtension; // Maps to 'poster_extension'

    public function __construct(
        int $id,
        int $idGrup,
        string $judul, 
        string $slug, 
        string $tanggal, 
        string $keterangan, 
        string $jenis, 
        string $posterExtension
    ) {
        $this->setId($id);
        $this->setIdGrup($idGrup);
        $this->setJudul($judul);
        $this->setSlug($slug);
        $this->setTanggal($tanggal);
        $this->setKeterangan($keterangan);
        $this->setJenis($jenis);
        $this->setPosterExtension($posterExtension);
    }

    // ==========================================================================
    // GETTERS
    // ==========================================================================

    public function getId(): int { return $this->id; }
    public function getIdGrup(): int { return $this->idGrup; }
    public function getJudul(): string { return $this->judul; }
    public function getSlug(): string { return $this->slug; }
    public function getTanggal(): string { return $this->tanggal; }
    public function getKeterangan(): string { return $this->keterangan; }
    public function getJenis(): string { return $this->jenis; }
    public function getPosterExtension(): string { return $this->posterExtension; }

    // ==========================================================================
    // SETTERS (With Validation)
    // ==========================================================================

    public function setId(int $id): void
    {
        if ($id < 0) throw new Exception("ID must be a non-negative integer.");
        $this->id = $id;
    }

    public function setIdGrup(int $idGrup): void
    {
        if ($idGrup <= 0) throw new Exception("Group ID must be a positive integer.");
        $this->idGrup = $idGrup;
    }

    public function setJudul(string $judul): void
    {
        $judul = trim($judul);
        if (empty($judul)) throw new Exception("Judul cannot be empty.");
        if (strlen($judul) > 45) throw new Exception("Judul cannot exceed 45 characters.");
        $this->judul = $judul;
    }

    public function setSlug(string $slug): void
    {
        $slug = trim($slug);
        if (!preg_match('/^[a-z0-9-]+$/', $slug)) throw new Exception("Slug contains invalid characters.");
        if (strlen($slug) > 45) throw new Exception("Slug cannot exceed 45 characters.");
        $this->slug = $slug;
    }

    public function setTanggal(string $tanggal): void
    {
        if (empty($tanggal)) {
            $this->tanggal = date("Y-m-d H:i:s");
            return;
        }
        // Simple regex check for Y-m-d H:i:s
        if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $tanggal)) {
            throw new Exception("Invalid date format. Expected format: Y-m-d H:i:s");
        }
        $this->tanggal = $tanggal;
    }

    public function setKeterangan(string $keterangan): void
    {
        $this->keterangan = trim($keterangan);
    }

    public function setJenis(string $jenis): void
    {
        $allowedJenis = ['Privat', 'Publik'];
        $normalized = ucfirst(strtolower($jenis));
        if (!in_array($normalized, $allowedJenis)) {
            throw new Exception("Invalid 'jenis'. Allowed: " . implode(', ', $allowedJenis));
        }
        $this->jenis = $normalized;
    }

    public function setPosterExtension(string $posterExtension): void
    {
        $ext = str_replace('.', '', strtolower($posterExtension));
        // Allow empty extension if no poster is uploaded? If not, remove empty check.
        if ($ext !== "" && !in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
             throw new Exception("Invalid file extension.");
        }
        if (strlen($ext) > 4) throw new Exception("Extension too long.");
        $this->posterExtension = $ext;
    }

    // ==========================================================================
    // DATABASE CRUD OPERATIONS
    // ==========================================================================

    /**
     * INSERT: Saves a new event to the database
     */
    public function save(): void
    {
        // Note: `judul-slug` has a hyphen, so it MUST be wrapped in backticks ` `
        $sql = "INSERT INTO `event` 
                (`idgrup`, `judul`, `judul-slug`, `tanggal`, `keterangan`, `jenis`, `poster_extension`) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) throw new Exception("Database Error: " . Connection::getConnection()->error);

            // 'issssss' -> int, string, string, string, string, string, string
            $stmt->bind_param(
                'issssss',
                $this->idGrup,
                $this->judul,
                $this->slug,
                $this->tanggal,
                $this->keterangan,
                $this->jenis,
                $this->posterExtension
            );

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Set the new auto-increment ID to the object
                $this->id = $stmt->insert_id;
            } else {
                throw new Exception("Failed to insert event. No rows affected.");
            }

            $stmt->close();
        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    /**
     * UPDATE: Updates an existing event in the database
     */
    public function update(): void
    {
        $sql = "UPDATE `event` SET 
                `idgrup` = ?, 
                `judul` = ?, 
                `judul-slug` = ?, 
                `tanggal` = ?, 
                `keterangan` = ?, 
                `jenis` = ?, 
                `poster_extension` = ? 
                WHERE `idevent` = ?";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);

            if ($stmt === false) throw new Exception("Database Error: " . Connection::getConnection()->error);

            // 'issssssi' -> Last 'i' is for idevent (WHERE clause)
            $stmt->bind_param(
                'issssssi',
                $this->idGrup,
                $this->judul,
                $this->slug,
                $this->tanggal,
                $this->keterangan,
                $this->jenis,
                $this->posterExtension,
                $this->id
            );

            $stmt->execute();
            // Note: affected_rows might be 0 if you save without changing data. 
            // That is not necessarily an error in UPDATE.

            $stmt->close();
        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    /**
     * DELETE: Removes the event from the database
     */
    public function delete(): void
    {
        $sql = "DELETE FROM `event` WHERE `idevent` = ?";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            
            $stmt->bind_param('i', $this->id);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("Delete failed. Event ID {$this->id} not found.");
            }

            $stmt->close();
        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    // ==========================================================================
    // STATIC FINDER METHODS
    // ==========================================================================

    /**
     * Find Event by ID
     * @param int $id
     * @return Event|null
     */
    public static function findById(int $id): ?Event
    {
        $sql = "SELECT * FROM `event` WHERE `idevent` = ?";
        $event = null;

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $event = new Event(
                    (int)$row['idevent'],
                    (int)$row['idgrup'],
                    $row['judul'],
                    $row['judul-slug'], // Note the hyphen key
                    $row['tanggal'],
                    $row['keterangan'],
                    $row['jenis'],
                    $row['poster_extension']
                );
            }
            $stmt->close();
            return $event;

        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    /**
     * Find all events belonging to a specific Group
     * @param int $idGrup
     * @return Event[]
     */
    public static function getByGroupId(int $idGrup): array
    {
        $sql = "SELECT * FROM `event` WHERE `idgrup` = ? ORDER BY `tanggal` DESC";
        $events = [];

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param('i', $idGrup);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $events[] = new Event(
                    (int)$row['idevent'],
                    (int)$row['idgrup'],
                    $row['judul'],
                    $row['judul-slug'],
                    $row['tanggal'],
                    $row['keterangan'],
                    $row['jenis'],
                    $row['poster_extension']
                );
            }
            $stmt->close();
            return $events;

        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }

    /**
     * Search events by Title
     * @param string $keyword
     * @return Event[]
     */
    public static function searchByTitle(string $keyword): array
    {
        $sql = "SELECT * FROM `event` WHERE `judul` LIKE ? ORDER BY `tanggal` DESC";
        $events = [];
        $term = "%" . $keyword . "%";

        try {
            Connection::startConnection();
            $stmt = Connection::getConnection()->prepare($sql);
            $stmt->bind_param('s', $term);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $events[] = new Event(
                    (int)$row['idevent'],
                    (int)$row['idgrup'],
                    $row['judul'],
                    $row['judul-slug'],
                    $row['tanggal'],
                    $row['keterangan'],
                    $row['jenis'],
                    $row['poster_extension']
                );
            }
            $stmt->close();
            return $events;

        } catch (Exception $e) {
            throw $e;
        } finally {
            Connection::closeConnection();
        }
    }
}