-- MySQL Workbench Synchronization
-- Generated: 2025-09-04 15:23
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: hdinata

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

ALTER SCHEMA `fullstack`  DEFAULT CHARACTER SET utf8  DEFAULT COLLATE utf8_general_ci ;

CREATE TABLE IF NOT EXISTS `fullstack`.`mahasiswa` (
  `nrp` CHAR(9) NOT NULL,
  `nama` VARCHAR(45) NULL DEFAULT NULL,
  `gender` ENUM('Pria', 'Wanita') NULL DEFAULT NULL,
  `tanggal_lahir` DATE NULL DEFAULT NULL,
  `angkatan` YEAR NULL DEFAULT NULL,
  `foto_extention` VARCHAR(4) NULL DEFAULT NULL,
  PRIMARY KEY (`nrp`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `fullstack`.`dosen` (
  `npk` CHAR(6) NOT NULL,
  `nama` VARCHAR(45) NULL DEFAULT NULL,
  `foto_extension` VARCHAR(4) NULL DEFAULT NULL,
  PRIMARY KEY (`npk`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `fullstack`.`akun` (
  `username` VARCHAR(20) NOT NULL,
  `password` VARCHAR(100) NULL DEFAULT NULL,
  `nrp_mahasiswa` CHAR(9) NULL DEFAULT NULL,
  `npk_dosen` CHAR(6) NULL DEFAULT NULL,
  `isadmin` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`username`),
  INDEX `fk_akun_mahasiswa_idx` (`nrp_mahasiswa` ASC) VISIBLE,
  INDEX `fk_akun_dosen1_idx` (`npk_dosen` ASC) VISIBLE,
  CONSTRAINT `fk_akun_mahasiswa`
    FOREIGN KEY (`nrp_mahasiswa`)
    REFERENCES `fullstack`.`mahasiswa` (`nrp`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_akun_dosen1`
    FOREIGN KEY (`npk_dosen`)
    REFERENCES `fullstack`.`dosen` (`npk`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `fullstack`.`grup` (
  `idgrup` INT(11) NOT NULL AUTO_INCREMENT,
  `username_pembuat` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(45) NULL DEFAULT NULL,
  `deskripsi` VARCHAR(45) NULL DEFAULT NULL,
  `tanggal_pembentukan` DATETIME NULL DEFAULT NULL,
  `jenis` ENUM('Privat', 'Publik') NULL DEFAULT NULL,
  `kode_pendaftaran` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idgrup`),
  INDEX `fk_grup_akun1_idx` (`username_pembuat` ASC) VISIBLE,
  CONSTRAINT `fk_grup_akun1`
    FOREIGN KEY (`username_pembuat`)
    REFERENCES `fullstack`.`akun` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `fullstack`.`thread` (
  `idthread` INT(11) NOT NULL AUTO_INCREMENT,
  `username_pembuat` VARCHAR(20) NOT NULL,
  `idgrup` INT(11) NOT NULL,
  `tanggal_pembuatan` DATETIME NULL DEFAULT NULL,
  `status` ENUM('Open', 'Close') NULL DEFAULT 'Open',
  PRIMARY KEY (`idthread`),
  INDEX `fk_thread_akun1_idx` (`username_pembuat` ASC) VISIBLE,
  INDEX `fk_thread_grup1_idx` (`idgrup` ASC) VISIBLE,
  CONSTRAINT `fk_thread_akun1`
    FOREIGN KEY (`username_pembuat`)
    REFERENCES `fullstack`.`akun` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_thread_grup1`
    FOREIGN KEY (`idgrup`)
    REFERENCES `fullstack`.`grup` (`idgrup`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `fullstack`.`chat` (
  `idchat` INT(11) NOT NULL AUTO_INCREMENT,
  `idthread` INT(11) NOT NULL,
  `username_pembuat` VARCHAR(20) NOT NULL,
  `isi` TEXT NULL DEFAULT NULL,
  `tanggal_pembuatan` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`idchat`),
  INDEX `fk_chat_thread1_idx` (`idthread` ASC) VISIBLE,
  INDEX `fk_chat_akun1_idx` (`username_pembuat` ASC) VISIBLE,
  CONSTRAINT `fk_chat_thread1`
    FOREIGN KEY (`idthread`)
    REFERENCES `fullstack`.`thread` (`idthread`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_chat_akun1`
    FOREIGN KEY (`username_pembuat`)
    REFERENCES `fullstack`.`akun` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `fullstack`.`member_grup` (
  `idgrup` INT(11) NOT NULL,
  `username` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`idgrup`, `username`),
  INDEX `fk_grup_has_akun_akun1_idx` (`username` ASC) VISIBLE,
  INDEX `fk_grup_has_akun_grup1_idx` (`idgrup` ASC) VISIBLE,
  CONSTRAINT `fk_grup_has_akun_grup1`
    FOREIGN KEY (`idgrup`)
    REFERENCES `fullstack`.`grup` (`idgrup`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_grup_has_akun_akun1`
    FOREIGN KEY (`username`)
    REFERENCES `fullstack`.`akun` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `fullstack`.`event` (
  `idevent` INT(11) NOT NULL AUTO_INCREMENT,
  `idgrup` INT(11) NOT NULL,
  `judul` VARCHAR(45) NULL DEFAULT NULL,
  `judul-slug` VARCHAR(45) NULL DEFAULT NULL,
  `tanggal` DATETIME NULL DEFAULT NULL,
  `keterangan` TEXT NULL DEFAULT NULL,
  `jenis` ENUM('Privat', 'Publik') NULL DEFAULT NULL,
  `poster_extension` VARCHAR(4) NULL DEFAULT NULL,
  PRIMARY KEY (`idevent`),
  INDEX `fk_event_grup1_idx` (`idgrup` ASC) VISIBLE,
  CONSTRAINT `fk_event_grup1`
    FOREIGN KEY (`idgrup`)
    REFERENCES `fullstack`.`grup` (`idgrup`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
