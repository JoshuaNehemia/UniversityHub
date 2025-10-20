<?php
require_once(__DIR__ .'/../DATABASE/Connection.php');

use DATABASE\Connection;
// DEFINE ========================================================================================================================
define("ENUM_JENIS", array("MAHASISWA", "DOSEN", "ADMIN"));

// FUNCTION ======================================================================================================================
function GetAccountList($jenis, $offset, $limit, $keyword)
{
    if (!empty($keyword)) {
        $keyword = "%" . $keyword . "%";
        if ($jenis === ENUM_JENIS[0]) {
            return GetListMahasiswaByNama($offset, $limit, $keyword);
        } else if ($jenis === ENUM_JENIS[1]) {
            return GetListDosenByNama($offset, $limit, $keyword);
        }
    } else {
        if ($jenis === ENUM_JENIS[0]) {
            return GetListMahasiswa($offset, $limit);
        } else if ($jenis === ENUM_JENIS[1]) {
            return GetListDosen($offset, $limit);
        }
    }
}
function GetNumRows($jenis, $keyword)
{
    if (!empty($keyword)) {
        $keyword = "%" . $keyword . "%";
        if ($jenis === ENUM_JENIS[0]) {
            return GetCountMahasiswaByNama($keyword);
        } else if ($jenis === ENUM_JENIS[1]) {
            return GetCountDosenByNama($keyword);
        }
    } else {
        if ($jenis === ENUM_JENIS[0]) {
            return GetCountMahasiswa();
        } else if ($jenis === ENUM_JENIS[1]) {
            return GetCountDosen();
        }
    }
}

function GetListMahasiswa($offset, $limit)
{
    $sql = "SELECT ak.username,ma.* FROM akun AS ak INNER JOIN mahasiswa AS ma ON ak.`nrp_mahasiswa` = ma.`nrp` LIMIT ?,?;";
    try {
        Connection::startConnection();
        $stmt = Connection::getConnection()->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Gagal request ke database");
        }
        $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    } catch (Exception $e) {
        throw $e;
    } finally {
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
    }
}
function GetCountMahasiswa()
{
    $sql = "SELECT COUNT(*) AS 'total' FROM `akun` AS ak INNER JOIN `mahasiswa` AS ma ON ak.`nrp_mahasiswa` = ma.`nrp`";
    try {
        Connection::startConnection();
        $stmt = Connection::getConnection()->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Gagal request ke database");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $numRows = $row['total'];
        }
        $stmt->close();
        return $numRows;
    } catch (Exception $e) {
        throw $e;
    } finally {
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
    }
}
function GetListMahasiswaByNama($offset, $limit, $keyword)
{
    $sql = "SELECT ak.`username`,ma.* FROM `akun` AS ak INNER JOIN `mahasiswa` AS ma ON ak.`nrp_mahasiswa` = ma.`nrp` WHERE ma.`nama` LIKE ? LIMIT ?,?;";
    try {
        Connection::startConnection();
        $stmt = Connection::getConnection()->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Gagal request ke database");
        }
        $stmt->bind_param('sii', $keyword, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    } catch (Exception $e) {
        throw $e;
    } finally {
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
    }
}

function GetCountMahasiswaByNama($keyword)
{
    $sql = "SELECT COUNT(*) AS 'total' FROM `akun` AS ak INNER JOIN `dosen` AS ds ON ak.`npk_dosen` = ds.`npk`";
    try {
        Connection::startConnection();
        $stmt = Connection::getConnection()->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Gagal request ke database");
        }
        $stmt->bind_param('sii', $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $numRows = $row['total'];
        }
        $stmt->close();
        return $numRows;
    } catch (Exception $e) {
        throw $e;
    } finally {
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
    }
}

function GetListDosen($offset, $limit)
{
    $sql = "SELECT ak.`username`,ds.* FROM `akun` AS ak INNER JOIN `dosen` AS ds ON ak.`npk_dosen` = ds.`npk` LIMIT ?,?;";
    try {
        Connection::startConnection();
        $stmt = Connection::getConnection()->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Gagal request ke database");
        }
        $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    } catch (Exception $e) {
        throw $e;
    } finally {
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
    }
}
function GetCountDosen()
{
    $sql = "SELECT COUNT(*) AS 'total' FROM `akun` AS ak INNER JOIN `dosen` AS ds ON ak.`npk_dosen` = ds.`npk`;";
    try {
        Connection::startConnection();
        $stmt = Connection::getConnection()->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Gagal request ke database");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $numRows = $row['total'];
        }
        $stmt->close();
        return $numRows;
    } catch (Exception $e) {
        throw $e;
    } finally {
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
    }
}
function GetListDosenByNama($offset, $limit, $keyword)
{
    $sql = "SELECT ak.`username`,ds.* FROM `akun` AS ak INNER JOIN `dosen` AS ds ON ak.`npk_dosen` = ds.`npk` WHERE ds.`nama` LIKE ? LIMIT ?,? ;";
    try {
        Connection::startConnection();
        $stmt = Connection::getConnection()->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Gagal request ke database");
        }
        $stmt->bind_param('sii', $keyword, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $numRows = $row['total'];
        }
        $stmt->close();
        return $numRows;
    } catch (Exception $e) {
        throw $e;
    } finally {
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
    }
}
function GetCountDosenByNama($keyword)
{
    $sql = "SELECT  COUNT(*) AS 'total' FROM `akun` AS ak INNER JOIN `dosen` AS ds ON ak.`npk_dosen` = ds.`npk` WHERE ds.`nama` LIKE ?;";
    try {
        Connection::startConnection();
        $stmt = Connection::getConnection()->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Gagal request ke database");
        }
        $stmt->bind_param('s', $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $numRows = $row['total'];
        }
        $stmt->close();
        return $numRows;
    } catch (Exception $e) {
        throw $e;
    } finally {
        if (Connection::getConnection() !== null) {
            Connection::closeConnection();
        }
    }
}
function GetAccountByUsername($username)
{
    $sql = "SELECT a.username, 
                    a.nrp_mahasiswa AS nrp, 
                    a.npk_dosen AS npk, 
                    a.isadmin,
                    m.nama AS nama_mhs, 
                    m.gender, 
                    m.tanggal_lahir, 
                    m.angkatan, 
                    m.foto_extention AS foto_mhs,
                    d.nama AS nama_dosen, 
                    d.foto_extension AS foto_dosen
            FROM akun a
            LEFT JOIN mahasiswa m ON a.nrp_mahasiswa = m.nrp
            LEFT JOIN dosen d ON a.npk_dosen = d.npk
            WHERE a.username = ?";

    try {
        Connection::startConnection();
        $stmt = Connection::getConnection()->prepare($sql);

        if ($stmt === false) {
            throw new Exception("SQL Error: " . Connection::getConnection()->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            return null;
        }

        $row = $result->fetch_assoc();

        // Tentukan jenis akun
        if ($row['isadmin'] == 1) {
            $row['jenis'] = "ADMIN";
        } elseif (!empty($row['npk'])) {
            $row['jenis'] = "DOSEN";
        } else {
            $row['jenis'] = "MAHASISWA";
        }

        return $row;
    } catch (Exception $e) {
        throw $e;
    } finally {
        Connection::closeConnection();
    }
}
