<?php
namespace REPOSITORY;

#region REQUIRE
require_once(__DIR__ . '/../CORE/DatabaseConnection.php');
require_once(__DIR__ . '/../MODELS/Akun.php');
require_once(__DIR__ . '/../MODELS/Mahasiswa.php');
require_once(__DIR__ . '/../MODELS/Dosen.php');
#endregion

#region USE
use CORE\DatabaseConnection;
use MODELS\Akun;
use MODELS\Mahasiswa;
use MODELS\Dosen;
use Exception;
#endregion

class RepoAccount
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
    public function createAkun(
        string $username,
        string $password,
        ?string $nrp = null,
        ?string $npk = null,
        $conn = null
    ): bool {
        $sql = "
            INSERT INTO akun (username, password, nrp_mahasiswa, npk_dosen, isadmin)
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmt = null;

        try {
            if (!$conn) {
                $conn = $this->db->connect();
            }
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare create akun statement: " . $conn->error);
            }
            $isAdmin = ($nrp === null && $npk === null) ? 1 : 0;

            $stmt->bind_param(
                'ssssi',
                $username,
                $password,
                $nrp,
                $npk,
                $isAdmin
            );

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            return $stmt->affected_rows === 1;
        } catch (Exception $e) {
            if ($conn)
                $conn->rollback();
            if ($e->getCode() === 1062) {
                throw new Exception("This username already exists");
            }
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
        }
    }

    public function createMahasiswa(Mahasiswa $mahasiswa, string $password): bool
    {
        $sql = "
            INSERT INTO mahasiswa (nrp, nama, gender, tanggal_lahir, angkatan, foto_extention)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $conn->begin_transaction();
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare create mahasiswa statement");
            }

            $data = $mahasiswa->toArray();

            $stmt->bind_param(
                'ssssss',
                $data['nrp'],
                $data['nama'],
                $data['gender'],
                $data['tanggal_lahir'],
                $data['angkatan'],
                $data['foto_extention']
            );

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $this->createAkun($data['username'], $password, $data['nrp'], null, $conn);

            $conn->commit();
            return true;
        } catch (Exception $e) {
            if ($conn)
                $conn->rollback();

            if ($e->getCode() === 1062) {
                throw new Exception("This NRP already exists");
            }
            throw $e;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }

    public function createDosen(Dosen $dosen, string $password)
    {
        $sql = "INSERT INTO dosen (npk, nama, foto_extension) VALUES (?, ?, ?)";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $conn->begin_transaction();
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare create dosen statement");
            }

            $data = $dosen->toArray();

            $stmt->bind_param(
                "sss",
                $data['npk'],
                $data['nama'],
                $data['foto_extention']
            );

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $this->createAkun(
                $data['username'],
                $password,
                null,
                $data['npk'],
                $conn
            );
            $conn->commit();
            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            if ($conn)
                $conn->rollback();

            if ($e->getCode() === 1062) {
                throw new Exception("This NRP already exists");
            }
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
    public function login(string $username, string $password)
    {
        $row = $this->fetchAccountRow($username);

        if (!password_verify($password, $row['password'])) {
            throw new Exception("Username or password is incorrect");
        }

        return $this->mapAccountObject($row);
    }

    public function findAccountByUsername(string $username)
    {
        $row = $this->fetchAccountRow($username);
        return $this->mapAccountObject($row);
    }

    private function fetchAccountRow(string $username): array
    {
        $sql = "
            SELECT a.*, m.*, d.*,m.nama AS 'm_nama', d.nama AS 'd_nama'
            FROM akun a
            LEFT JOIN mahasiswa m ON a.nrp_mahasiswa = m.nrp
            LEFT JOIN dosen d ON a.npk_dosen = d.npk
            WHERE a.username = ?
        ";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare account query");
            }

            $stmt->bind_param('s', $username);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row;
        } finally {
            if ($stmt)
                $stmt->close();
            if ($conn) {
                $this->db->close();
            }
        }
    }

    #region RETRIEVE MAHASISWA 
    public function findAllMahasiswaByName(int $limit, int $offset, string $keyword): array
    {
        $sql = "
        SELECT 
            m.nrp,
            a.username,
            m.nama,
            m.gender,
            m.tanggal_lahir,
            m.angkatan,
            m.foto_extention
        FROM mahasiswa m
        INNER JOIN akun a ON m.nrp = a.nrp_mahasiswa
        WHERE m.nama LIKE ?
        LIMIT ? OFFSET ?
    ";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare find mahasiswa by name statement");
            }

            $keyword = "%{$keyword}%";
            $offset = $offset * $limit;

            $stmt->bind_param("sii", $keyword, $limit, $offset);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $list = [];

            while ($row = $result->fetch_assoc()) {
                $list[] = new Mahasiswa(
                    $row["username"],
                    $row["nama"],
                    $row["nrp"],
                    $row["tanggal_lahir"],
                    $row["gender"],
                    $row["angkatan"],
                    $row["foto_extention"]
                );
            }

            return $list;
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


    public function findMahasiswaByNRP(string $nrp): Mahasiswa
    {
        $sql = "
        SELECT 
            m.nrp,
            a.username,
            m.nama,
            m.gender,
            m.tanggal_lahir,
            m.angkatan,
            m.foto_extention
        FROM mahasiswa m
        INNER JOIN akun a ON m.nrp = a.nrp_mahasiswa
        WHERE m.nrp = ?
    ";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare find mahasiswa by NRP statement");
            }

            $stmt->bind_param("s", $nrp);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $row = null;
            if (!($row = $result->fetch_assoc())) {
                throw new Exception("Failed to retrieve Mahasiswa: Not Found");
            }
            return new Mahasiswa(
                $row["username"],
                $row["nama"],
                $row["nrp"],
                $row["tanggal_lahir"],
                $row["gender"],
                $row["angkatan"],
                $row["foto_extention"]
            );


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


    public function findMahasiswaByUsername(string $username): Mahasiswa
    {
        $sql = "
        SELECT 
            a.username,
            a.nrp_mahasiswa,
            m.nama,
            m.gender,
            m.tanggal_lahir,
            m.angkatan,
            m.foto_extention
        FROM akun a
        INNER JOIN mahasiswa m 
            ON a.nrp_mahasiswa = m.nrp
        WHERE a.username = ?
    ";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare find mahasiswa by username statement");
            }

            $stmt->bind_param("s", $username);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Mahasiswa not found with given username");
            }

            $row = $result->fetch_assoc();

            return new Mahasiswa(
                $row["username"],
                $row["nama"],
                $row["nrp_mahasiswa"],
                $row["tanggal_lahir"],
                $row["gender"],
                $row["angkatan"],
                $row["foto_extention"]
            );
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
    #region RETRIEVE DOSEN
    public function findDosenByUsername(string $username): Dosen
    {
        $sql = "
        SELECT a.username, d.npk, d.nama, d.foto_extension
        FROM akun a
        INNER JOIN dosen d ON a.npk_dosen = d.npk
        WHERE a.username = ?
    ";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare get dosen by username statement");
            }

            $stmt->bind_param("s", $username);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Dosen tidak ditemukan");
            }

            $row = $result->fetch_assoc();
            $dosen = new Dosen();
            $dosen->setUsername($row['username']);
            $dosen->setNama($row['nama']);
            $dosen->setNPK($row['npk']);
            $dosen->setFotoExtention($row['foto_extension']);
            return $dosen;
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
    public function findDosenByNPK(string $npk): Dosen
    {
        $sql = "
        SELECT a.username, d.npk, d.nama, d.foto_extension
        FROM akun a
        INNER JOIN dosen d ON a.npk_dosen = d.npk
        WHERE a.npk_dosen = ?
    ";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare get dosen by username statement");
            }

            $stmt->bind_param("s", $npk);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("Dosen tidak ditemukan");
            }

            $row = $result->fetch_assoc();
            $dosen = new Dosen();
            $dosen->setUsername($row['username']);
            $dosen->setNama($row['nama']);
            $dosen->setNPK($row['npk']);
            $dosen->setFotoExtention($row['foto_extension']);
            return $dosen;
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
    public function findAllDosenByName(int $limit, int $offset, string $keyword): array
    {
        $sql = "
        SELECT a.username, d.nama, d.npk, d.foto_extension
        FROM dosen d
        INNER JOIN akun a ON d.npk = a.npk_dosen
        WHERE d.nama LIKE ?
        LIMIT ? OFFSET ?
    ";

        $stmt = null;
        $conn = null;
        $offset = $offset * $limit;
        $keyword = "%{$keyword}%";

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare get dosen by name statement");
            }

            $stmt->bind_param("sii", $keyword, $limit, $offset);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $result = $stmt->get_result();
            $list = [];

            while ($row = $result->fetch_assoc()) {
                $dosen = new Dosen();
                $dosen->setUsername($row['username']);
                $dosen->setNama($row['nama']);
                $dosen->setNPK($row['npk']);
                $dosen->setFotoExtention($row['foto_extension']);
                $list[] = $dosen;
            }

            return $list;
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
    public function updateAccount(string $username, string $newPassword): bool
    {
        $sql = "UPDATE akun SET password = ? WHERE username = ?";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $newPassword, $username);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
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

    public function updateMahasiswa(Mahasiswa $mahasiswa): bool
    {
        $sql = "UPDATE mahasiswa
                SET 
                    nrp = ?, 
                    nama = ?, 
                    gender = ?, 
                    tanggal_lahir = ?, 
                    angkatan = ?, 
                    foto_extention = ?
                WHERE nrp = (
                    SELECT nrp_mahasiswa 
                    FROM akun 
                    WHERE username = ?
                )
            ";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare update mahasiswa statement");
            }

            $data = $mahasiswa->toArray();

            $stmt->bind_param(
                "ssssiss",
                $data['nrp'],
                $data['nama'],
                $data['gender'],
                $data['tanggal_lahir'],
                $data['angkatan'],
                $data['foto_extention'],
                $data['username']
            );

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            if ($stmt->affected_rows !== 1) {
                throw new Exception("No mahasiswa data was updated");
            }

            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
            if ($conn) {
                $this->db->close();
            }
        }
    }
    public function updateDosen(Dosen $dosen): bool
    {
        $sql = "UPDATE dosen
                SET 
                    npk = ?, 
                    nama = ?, 
                    foto_extension = ?
                WHERE nrp = (
                    SELECT npk_dosen 
                    FROM akun 
                    WHERE username = ?
                )
            ";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare update dosen statement");
            }

            $data = $dosen->toArray();

            $stmt->bind_param(
                "ssssiss",
                $data['npk'],
                $data['nama'],
                $data['foto_extension'],
                $data['username']
            );

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion

    #region DELETE
    public function deleteAkun(string $username): bool
    {
        $sql = "DELETE FROM akun WHERE username = ?";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare delete akun statement");
            }

            $stmt->bind_param('s', $username);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
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
    public function deleteAkunByNRP(string $nrp): bool
    {
        $sql = "DELETE FROM akun WHERE nrp_mahasiswa = ?";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare delete akun statement");
            }

            $stmt->bind_param('s', $nrp);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
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
    public function deleteAkunByNPK(string $npk): bool
    {
        $sql = "DELETE FROM akun WHERE npk_dosen = ?";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare delete akun statement");
            }

            $stmt->bind_param('s', $npk);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
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
    public function deleteMahasiswa(string $nrp): bool
    {
        $sql = "DELETE FROM mahasiswa WHERE nrp = ?";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare delete mahasiswa statement");
            }

            $stmt->bind_param("s", $nrp);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
            if ($conn) {
                $this->db->close();
            }
        }
    }

    public function deleteDosen(string $npk): bool
    {

        $sql = "DELETE FROM dosen WHERE npk = ?";

        $stmt = null;
        $conn = null;

        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare delete dosen statement");
            }

            $stmt->bind_param("s", $npk);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            return $stmt->affected_rows === 1;

        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
            if ($conn) {
                $this->db->close();
            }
        }
    }
    #endregion

    #region MAPPER

    private function mapAccountObject(array $row): Akun|Dosen|Mahasiswa
    {
        if (!empty($row['nrp'])) {
            $m = new Mahasiswa();
            $m->setUsername($row['username']);
            $m->setNama($row['m_nama']);
            $m->setJenis(ACCOUNT_ROLE[0]);
            $m->setNRP($row['nrp']);
            $m->setTanggalLahir($row['tanggal_lahir']);
            $m->setGender($row['gender']);
            $m->setAngkatan($row['angkatan']);
            $m->setFotoExtention($row['foto_extention']);
            return $m;
        }

        if (!empty($row['npk'])) {
            $d = new Dosen();
            $d->setUsername($row['username']);
            $d->setNama($row['d_nama']);
            $d->setJenis(ACCOUNT_ROLE[1]);
            $d->setNPK($row['npk']);
            $d->setFotoExtention($row['foto_extension']);
            return $d;
        }

        if ((int) $row['isadmin'] === 1) {
            $a = new Akun();
            $a->setUsername($row['username']);
            $a->setJenis(ACCOUNT_ROLE[2]);
            $a->setNama("ADMIN");
            return $a;
        }

        throw new Exception("Invalid account type");
    }
    #endregion
}
