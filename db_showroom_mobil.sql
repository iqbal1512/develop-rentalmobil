-- ============================================================
-- DATABASE: db_showroom_mobil
-- Sistem Penjualan & Pembelian Mobil Bekas
-- Berdasarkan: Class Diagram + Analisis Bisnis Proyek-1
-- ============================================================

CREATE DATABASE IF NOT EXISTS `db_showroom_mobil`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `db_showroom_mobil`;

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- TABLE: users
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user`    INT(11)      NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(50)  NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `nama`       VARCHAR(100) NOT NULL,
  `role`       ENUM('admin','owner') NOT NULL DEFAULT 'admin',
  `value`      TINYINT(1)   NOT NULL DEFAULT 1 COMMENT '1=aktif 0=nonaktif',
  `created_at` DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE: supplier
-- ============================================================
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id_supplier`   INT(11)      NOT NULL AUTO_INCREMENT,
  `nama_supplier` VARCHAR(100) NOT NULL,
  `alamat`        TEXT         NOT NULL,
  `telepon`       VARCHAR(20)  DEFAULT NULL,
  `email`         VARCHAR(100) DEFAULT NULL,
  `no_hp`         VARCHAR(20)  DEFAULT NULL,
  `created_at`    DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE: mobil
-- ============================================================
DROP TABLE IF EXISTS `mobil`;
CREATE TABLE `mobil` (
  `id_mobil`     INT(11)        NOT NULL AUTO_INCREMENT,
  `id_supplier`  INT(11)        NOT NULL,
  `nama_mobil`   VARCHAR(100)   NOT NULL,
  `warna`        VARCHAR(50)    NOT NULL,
  `vendor`       VARCHAR(50)    NOT NULL,
  `tipe`         VARCHAR(50)    NOT NULL,
  `no_polisi`    VARCHAR(20)    DEFAULT NULL,
  `tahun`        YEAR           DEFAULT NULL,
  `harga_beli`   DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `harga_jual`   DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `stok`         INT(11)        NOT NULL DEFAULT 1,
  `status_jual`  ENUM('tersedia','terjual','dipesan') NOT NULL DEFAULT 'tersedia',
  `status_mobil` ENUM('baru','bekas') NOT NULL DEFAULT 'bekas',
  `foto`         VARCHAR(255)   DEFAULT NULL,
  `keterangan`   TEXT           DEFAULT NULL,
  `created_at`   DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mobil`),
  KEY `fk_mobil_supplier` (`id_supplier`),
  CONSTRAINT `fk_mobil_supplier`
    FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE: customer
-- ============================================================
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id_customer` INT(11)      NOT NULL AUTO_INCREMENT,
  `nama`        VARCHAR(100) NOT NULL,
  `alamat`      TEXT         NOT NULL,
  `telepon`     VARCHAR(20)  DEFAULT NULL,
  `no_ktp`      VARCHAR(30)  NOT NULL UNIQUE,
  `email`       VARCHAR(100) DEFAULT NULL,
  `no_zip`      VARCHAR(10)  DEFAULT NULL,
  `created_at`  DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE: pembelian (beli mobil dari supplier/penjual)
-- Proses Bisnis: tunai/transfer, bukti kwitansi
-- ============================================================
DROP TABLE IF EXISTS `pembelian`;
CREATE TABLE `pembelian` (
  `id_pembelian`       INT(11)        NOT NULL AUTO_INCREMENT,
  `id_supplier`        INT(11)        NOT NULL,
  `id_mobil`           INT(11)        NOT NULL,
  `id_user`            INT(11)        NOT NULL,
  `tgl_pembelian`      DATE           NOT NULL,
  `harga_beli`         DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `jumlah_pembelian`   INT(11)        NOT NULL DEFAULT 1,
  `total_harga`        DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `metode_bayar`       ENUM('tunai','transfer') NOT NULL DEFAULT 'tunai',
  `bukti_transfer`     VARCHAR(255)   DEFAULT NULL COMMENT 'jika transfer',
  `no_kwitansi`        VARCHAR(50)    DEFAULT NULL,
  `status_pembelian`   ENUM('proses','selesai','batal') NOT NULL DEFAULT 'proses',
  `keterangan_kondisi` TEXT           DEFAULT NULL,
  `created_at`         DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `updated_at`         DATETIME       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembelian`),
  KEY `fk_pembelian_supplier` (`id_supplier`),
  KEY `fk_pembelian_mobil`    (`id_mobil`),
  KEY `fk_pembelian_user`     (`id_user`),
  CONSTRAINT `fk_pembelian_supplier`
    FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pembelian_mobil`
    FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pembelian_user`
    FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE: pemesanan (booking + uang muka dari customer)
-- Proses Bisnis:
--   - Bukti Pesanan Rp.500.000
--   - DP 30% harga mobil dalam 1 minggu + fotocopy KTP
--   - Jika lewat 1 minggu -> batal, bukti pesanan hangus
-- ============================================================
DROP TABLE IF EXISTS `pemesanan`;
CREATE TABLE `pemesanan` (
  `id_pemesanan`     INT(11)        NOT NULL AUTO_INCREMENT,
  `id_customer`      INT(11)        NOT NULL,
  `id_mobil`         INT(11)        NOT NULL,
  `id_user`          INT(11)        NOT NULL,
  `tgl_pesan`        DATE           NOT NULL,
  `tgl_jatuh_tempo`  DATE           NOT NULL COMMENT 'tgl_pesan + 7 hari',
  `biaya_bukti_pesan` DECIMAL(15,2) NOT NULL DEFAULT 500000 COMMENT 'Rp.500.000',
  `harga_jual`       DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `harga_jual_jadi`  DECIMAL(15,2)  NOT NULL DEFAULT 0 COMMENT 'setelah nego',
  `dp_persen`        DECIMAL(5,2)   NOT NULL DEFAULT 30 COMMENT '30% dari harga',
  `nominal_dp`       DECIMAL(15,2)  NOT NULL DEFAULT 0 COMMENT '30% harga_jual_jadi',
  `dp_awal_dibayar`  DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `sisa_dp_internal` DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `ktp_diterima`     TINYINT(1)     NOT NULL DEFAULT 0 COMMENT '1=sudah 0=belum',
  `status_pemesanan` ENUM('menunggu','dp_masuk','diproses','selesai','batal') NOT NULL DEFAULT 'menunggu',
  `catatan`          TEXT           DEFAULT NULL,
  `created_at`       DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pemesanan`),
  KEY `fk_pemesanan_customer` (`id_customer`),
  KEY `fk_pemesanan_mobil`    (`id_mobil`),
  KEY `fk_pemesanan_user`     (`id_user`),
  CONSTRAINT `fk_pemesanan_customer`
    FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pemesanan_mobil`
    FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pemesanan_user`
    FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE: penjualan
-- Proses: setelah STNK selesai (~2 minggu) + pelunasan
-- ============================================================
DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE `penjualan` (
  `id_penjualan`    INT(11)        NOT NULL AUTO_INCREMENT,
  `id_pemesanan`    INT(11)        NOT NULL,
  `id_user`         INT(11)        NOT NULL,
  `tgl_penjualan`   DATE           NOT NULL,
  `total_harga`     DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `total_dibayar`   DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `sisa_tagihan`    DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `status_lulus`    ENUM('proses','lulus','gagal') NOT NULL DEFAULT 'proses',
  `status_lunas`    ENUM('belum_lunas','lunas') NOT NULL DEFAULT 'belum_lunas',
  `proses_stnk`     ENUM('belum','proses','selesai') NOT NULL DEFAULT 'belum' COMMENT '~2 minggu',
  `proses_bpkb`     ENUM('belum','proses','selesai') NOT NULL DEFAULT 'belum' COMMENT '~2 bulan',
  `catatan`         TEXT           DEFAULT NULL,
  `created_at`      DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penjualan`),
  KEY `fk_penjualan_pemesanan` (`id_pemesanan`),
  KEY `fk_penjualan_user`      (`id_user`),
  CONSTRAINT `fk_penjualan_pemesanan`
    FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_penjualan_user`
    FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE: pembayaran
-- Proses: tunai->kwitansi, transfer->verifikasi bukti->kwitansi
-- ============================================================
DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id_pembayaran`     INT(11)        NOT NULL AUTO_INCREMENT,
  `id_pemesanan`      INT(11)        NOT NULL,
  `id_penjualan`      INT(11)        NOT NULL,
  `id_user`           INT(11)        NOT NULL,
  `jenis_pembayaran`  ENUM('bukti_pesan','dp','pelunasan','cicilan') NOT NULL DEFAULT 'dp',
  `metode_bayar`      ENUM('tunai','transfer') NOT NULL DEFAULT 'tunai',
  `tgl_bayar`         DATE           NOT NULL,
  `jumlah_bayar`      DECIMAL(15,2)  NOT NULL DEFAULT 0,
  `bukti_transfer`    VARCHAR(255)   DEFAULT NULL,
  `no_kwitansi`       VARCHAR(50)    DEFAULT NULL,
  `status_verifikasi` ENUM('menunggu','terverifikasi','ditolak') NOT NULL DEFAULT 'menunggu',
  `keterangan`        TEXT           DEFAULT NULL,
  `created_at`        DATETIME       DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  KEY `fk_pembayaran_pemesanan` (`id_pemesanan`),
  KEY `fk_pembayaran_penjualan` (`id_penjualan`),
  KEY `fk_pembayaran_user`      (`id_user`),
  CONSTRAINT `fk_pembayaran_pemesanan`
    FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pembayaran_penjualan`
    FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pembayaran_user`
    FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE: penyerahan_mobil
-- Proses: unit diantar/diambil + Surat Jalan, STNK, BPKB
-- ============================================================
DROP TABLE IF EXISTS `penyerahan_mobil`;
CREATE TABLE `penyerahan_mobil` (
  `id_penyerahan`   INT(11)      NOT NULL AUTO_INCREMENT,
  `id_penjualan`    INT(11)      NOT NULL,
  `id_user`         INT(11)      NOT NULL,
  `metode_serah`    ENUM('diambil','diantar') NOT NULL DEFAULT 'diambil',
  `alamat_antar`    TEXT         DEFAULT NULL COMMENT 'jika diantar',
  `tgl_serah_unit`  DATE         DEFAULT NULL,
  `tgl_serah_stnk`  DATE         DEFAULT NULL COMMENT '~2 minggu setelah DP',
  `tgl_serah_bpkb`  DATE         DEFAULT NULL COMMENT '~2 bulan',
  `no_surat_jalan`  VARCHAR(50)  DEFAULT NULL COMMENT 'jika diantar',
  `kondisi_serah`   ENUM('baik','cacat','rusak') NOT NULL DEFAULT 'baik',
  `catatan_petugas` TEXT         DEFAULT NULL,
  `estimasi_layan`  TEXT         DEFAULT NULL,
  `created_at`      DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penyerahan`),
  KEY `fk_penyerahan_penjualan` (`id_penjualan`),
  KEY `fk_penyerahan_user`      (`id_user`),
  CONSTRAINT `fk_penyerahan_penjualan`
    FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_penyerahan_user`
    FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE: laporan
-- ============================================================
DROP TABLE IF EXISTS `laporan`;
CREATE TABLE `laporan` (
  `id_laporan`         INT(11)      NOT NULL AUTO_INCREMENT,
  `jenis_laporan`      ENUM('pembelian','penjualan','pembayaran','pemesanan') NOT NULL,
  `periode_start_date` DATE         NOT NULL,
  `periode_akhir_date` DATE         NOT NULL,
  `dibuat_oleh`        INT(11)      NOT NULL,
  `created_at`         DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_laporan`),
  KEY `fk_laporan_user` (`dibuat_oleh`),
  CONSTRAINT `fk_laporan_user`
    FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id_user`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DATA
-- password: "password" (bcrypt) -- ganti saat deploy!
-- ============================================================
INSERT INTO `users` (`username`,`password`,`nama`,`role`,`value`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Showroom', 'admin', 1),
('owner', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Owner Showroom Mobil', 'owner', 1);

INSERT INTO `supplier` (`nama_supplier`,`alamat`,`telepon`,`email`,`no_hp`) VALUES
('PT. Auto Prima Jaya','Jl. Raya Industri No.15, Jakarta','021-5551234','autoprimajaya@email.com','081234567890'),
('CV. Mobil Nusantara','Jl. Soekarno-Hatta No.88, Bandung','022-4445678','mobilnusantara@email.com','082345678901'),
('UD. Kencana Motor','Jl. Pemuda No.33, Surabaya','031-3337890','kencanamotor@email.com','083456789012');

INSERT INTO `mobil` (`id_supplier`,`nama_mobil`,`warna`,`vendor`,`tipe`,`tahun`,`no_polisi`,`harga_beli`,`harga_jual`,`stok`,`status_jual`,`status_mobil`) VALUES
(1,'Toyota Avanza','Putih','Toyota','MPV',2022,'B 1234 ABC',145000000,175000000,3,'tersedia','bekas'),
(1,'Toyota Innova','Silver','Toyota','MPV',2021,'B 5678 DEF',245000000,285000000,2,'tersedia','bekas'),
(2,'Honda Brio','Merah','Honda','City Car',2023,'D 1111 GHI',135000000,160000000,4,'tersedia','bekas'),
(2,'Honda HR-V','Hitam','Honda','SUV',2020,'D 2222 JKL',270000000,315000000,1,'tersedia','bekas'),
(3,'Mitsubishi Xpander','Abu-abu','Mitsubishi','MPV',2022,'L 3333 MNO',195000000,230000000,2,'tersedia','bekas'),
(3,'Daihatsu Terios','Biru','Daihatsu','SUV',2019,'L 4444 PQR',160000000,190000000,1,'tersedia','bekas');

INSERT INTO `customer` (`nama`,`alamat`,`telepon`,`no_ktp`,`email`) VALUES
('Budi Santoso','Jl. Mawar No.5, Jakarta Selatan','081111111111','3171012345678901','budi@email.com'),
('Siti Rahayu','Jl. Melati No.10, Bandung','082222222222','3273024567890123','siti@email.com'),
('Ahmad Fauzi','Jl. Kenanga No.15, Surabaya','083333333333','3578036789012345','ahmad@email.com');
