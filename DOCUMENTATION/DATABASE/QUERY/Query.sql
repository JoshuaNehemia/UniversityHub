-- LOG IN -----------------------------------------------------------------------------------------------------

-- AKUN

-- DOSEN
SELECT `username`,`nrp`,`nama`,`foto_extension` FROM `akun` INNER JOIN `dosen` ON `akun`.`npk_dosen` = `dosen`.`npk` WHERE `username` = ? AND `password` = ?;
-- MAHASISWA
SELECT `username`,`nrp`,`nama`,`gender`,`tanggal_lahir`,`angkatan`,`foto_extention` FROM `akun` INNER JOIN `mahasiswa` ON `akun`.`npk_dosen` = `mahasiswa`.`nrp` WHERE `username` = ? AND `password` = ?;

-- BUAT AKUN --------------------------------------------------------------------------------------------------
