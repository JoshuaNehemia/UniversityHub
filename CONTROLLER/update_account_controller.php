<?php
require_once('../MODELS/Akun.php');
require_once('../DATABASE/Connection.php');
session_start();

use DATABASE\Connection;

if (!isset($_SESSION['currentAccount']) || $_SESSION['currentAccount']->getJenis() !== 'ADMIN') {
    $_SESSION['error_msg'] = "Anda tidak memiliki akses.";
    header("Location: ../ADMIN/daftar_akun.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../ADMIN/daftar_akun.php");
    exit();
}

$oldUsername = $_POST['old_username'] ?? '';
$newUsername = trim($_POST['username'] ?? '');
$password    = $_POST['password'] ?? '';
$jenis       = $_POST['jenis'] ?? '';
$nama        = trim($_POST['nama'] ?? '');
$nrp         = trim($_POST['nrp'] ?? '');
$npk         = trim($_POST['npk'] ?? '');
$gender      = $_POST['gender'] ?? '';
$tanggal     = $_POST['tanggal_lahir'] ?? '';
$angkatan    = $_POST['angkatan'] ?? '';

$uploadExt = null;

try {
    Connection::startConnection();
    $conn = Connection::getConnection();
    if ($conn === null) throw new Exception("Gagal koneksi database");

    $conn->begin_transaction();

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png','gif'];
        $name = $_FILES['foto']['name'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            throw new Exception("Ekstensi file tidak diizinkan");
        }
        $uploadExt = '.' . $ext;
        $targetName = ($newUsername !== '' ? $newUsername : $oldUsername) . $uploadExt;
        $targetDir = __DIR__ . '/../UPLOADS/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $targetPath = $targetDir . $targetName;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
            throw new Exception("Gagal menyimpan file foto");
        }
    }

    if ($jenis === 'MAHASISWA') {
        $sql = "SELECT nrp_mahasiswa FROM akun WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare error: " . $conn->error);
        $stmt->bind_param('s', $oldUsername);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $oldNrp = $row['nrp_mahasiswa'];
        $stmt->close();

        if ($oldNrp === null) throw new Exception("Data mahasiswa tidak ditemukan di akun");

        $sql = "SELECT tanggal_lahir, foto_extention FROM mahasiswa WHERE nrp = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $oldNrp);
        $stmt->execute();
        $res = $stmt->get_result();
        $dataLama = $res->fetch_assoc();
        $stmt->close();

        if ($tanggal !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            throw new Exception("Format tanggal lahir tidak valid (harus YYYY-MM-DD)");
        }

        $tanggalToSave = ($tanggal !== '') ? $tanggal : $dataLama['tanggal_lahir'];
        $fotoToSave    = ($uploadExt !== null) ? $uploadExt : $dataLama['foto_extention'];

        $sql = "UPDATE mahasiswa SET nrp = ?, nama = ?, gender = ?, tanggal_lahir = ?, angkatan = ?, foto_extention = ? WHERE nrp = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare error: " . $conn->error);
        $stmt->bind_param('sssssss', $nrp, $nama, $gender, $tanggalToSave, $angkatan, $fotoToSave, $oldNrp);
        $stmt->execute();
        if ($stmt->affected_rows < 0) throw new Exception("Gagal update mahasiswa");
        $stmt->close();

        if ($password !== '') {
            $sql = "UPDATE akun SET username = ?, password = ?, nrp_mahasiswa = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare error: " . $conn->error);
            $stmt->bind_param('ssss', $newUsername, $password, $nrp, $oldUsername);
        } else {
            $sql = "UPDATE akun SET username = ?, nrp_mahasiswa = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare error: " . $conn->error);
            $stmt->bind_param('sss', $newUsername, $nrp, $oldUsername);
        }
        $stmt->execute();
        if ($stmt->affected_rows < 0) throw new Exception("Gagal update akun (mahasiswa)");
        $stmt->close();

    } elseif ($jenis === 'DOSEN') {
        $sql = "SELECT npk_dosen FROM akun WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare error: " . $conn->error);
        $stmt->bind_param('s', $oldUsername);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $oldNpk = $row['npk_dosen'];
        $stmt->close();

        if ($oldNpk === null) throw new Exception("Data dosen tidak ditemukan di akun");

        $sql = "SELECT foto_extention FROM dosen WHERE npk = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $oldNpk);
        $stmt->execute();
        $res = $stmt->get_result();
        $dataLama = $res->fetch_assoc();
        $stmt->close();

        $fotoToSave = ($uploadExt !== null) ? $uploadExt : $dataLama['foto_extention'];

        $sql = "UPDATE dosen SET npk = ?, nama = ?, foto_extention = ? WHERE npk = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare error: " . $conn->error);
        $stmt->bind_param('ssss', $npk, $nama, $fotoToSave, $oldNpk);
        $stmt->execute();
        if ($stmt->affected_rows < 0) throw new Exception("Gagal update dosen");
        $stmt->close();

        if ($password !== '') {
            $sql = "UPDATE akun SET username = ?, password = ?, npk_dosen = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare error: " . $conn->error);
            $stmt->bind_param('ssss', $newUsername, $password, $npk, $oldUsername);
        } else {
            $sql = "UPDATE akun SET username = ?, npk_dosen = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare error: " . $conn->error);
            $stmt->bind_param('sss', $newUsername, $npk, $oldUsername);
        }
        $stmt->execute();
        if ($stmt->affected_rows < 0) throw new Exception("Gagal update akun (dosen)");
        $stmt->close();
    } else {
        throw new Exception("Jenis akun tidak valid");
    }

    if ($oldUsername !== $newUsername) {
        $updates = [
            "UPDATE grup SET username_pembuat = ? WHERE username_pembuat = ?",
            "UPDATE thread SET username_pembuat = ? WHERE username_pembuat = ?",
            "UPDATE chat SET username_pembuat = ? WHERE username_pembuat = ?",
            "UPDATE member_grup SET username = ? WHERE username = ?"
        ];
        foreach ($updates as $sql) {
            $stmt = $conn->prepare($sql);
            if (!$stmt) throw new Exception("Prepare error (cascade): " . $conn->error);
            $stmt->bind_param('ss', $newUsername, $oldUsername);
            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->commit();

    $_SESSION['success_msg'] = "Data akun berhasil diperbarui.";
    header("Location: ../ADMIN/daftar_akun.php");
    exit();

} catch (Exception $e) {
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }
    $_SESSION['error_msg'] = "Terjadi error: " . $e->getMessage();
    header("Location: ../ADMIN/edit_data_akun.php?username=" . urlencode($oldUsername));
    exit();
} finally {
    Connection::closeConnection();
}
