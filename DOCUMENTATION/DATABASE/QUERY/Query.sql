-- LOG IN -----------------------------------------------------------------------------------------------------

-- AKUN

-- DOSEN
SELECT `username`,`npk_dosen` FROM `akun` INNER JOIN `dosen` ON `akun`.`npk_dosen` = `dosen`.`npk` WHERE `username` = ? AND `password` = ?;

-- MAHASISWA
SELECT `username`,`npk_dosen` FROM `akun` INNER JOIN `dosen` ON `akun`.`npk_dosen` = `dosen`.`npk` WHERE `username` = ? AND `password` = ?;
