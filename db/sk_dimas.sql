/*
 Navicat Premium Data Transfer

 Source Server         : MYSQL
 Source Server Type    : MySQL
 Source Server Version : 50733
 Source Host           : localhost:3306
 Source Schema         : sk_dimas

 Target Server Type    : MySQL
 Target Server Version : 50733
 File Encoding         : 65001

 Date: 07/02/2022 23:24:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_item
-- ----------------------------
DROP TABLE IF EXISTS `tbl_item`;
CREATE TABLE `tbl_item`  (
  `kodeitem` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kodekategori` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `satuan` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `namaitem` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `stok` int(11) NULL DEFAULT NULL,
  `stokminimum` int(11) NULL DEFAULT NULL,
  `gambar` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statusitem` int(1) NULL DEFAULT NULL,
  `dateadditem` datetime NULL DEFAULT NULL,
  `dateupditem` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`kodeitem`) USING BTREE,
  INDEX `fk_kodekategori_tbl_item`(`kodekategori`) USING BTREE,
  CONSTRAINT `fk_kodekategori_tbl_item` FOREIGN KEY (`kodekategori`) REFERENCES `tbl_kategori` (`kodekategori`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of tbl_item
-- ----------------------------
INSERT INTO `tbl_item` VALUES ('ITEM001', 'KAT001', 'PCS', 'Sabun Mandi Pria Aroma Melati', 'Sabun adalah produk yang digunakan sebagai pembersih dengan media air. Secara umum berbentuk padatan (batang) dan ada juga yang cair. Masing-masing bentuk tentunya mempunyai keuntungan tersendiri diberbagai sarana publik. Jika diterapkan pada suatu permukaan, air bersabun secara efektif dapat mengikat partikel dalam suspensi yang mudah dibawa oleh air bersih. Di era milenial ini, deterjen sintetik telah menggantikan sabun sebagai alat bantu untuk mencuci atau membersihkan.\r\n\r\nSabun merupakan campuran minyak atau lemak (nabati, seperti minyak zaitun atau hewani, seperti lemak kambing) dengan alkali atau basa (seperti natrium atau kalium hidroksida) melalui suatu proses yang disebut dengan saponifikasi. Lemak akan terhidrolisis oleh basa, menghasilkan gliserol dan sabun mentah. Secara tradisional, alkali yang digunakan adalah kalium yang dihasilkan dari pembakaran tumbuhan seperti arang kayu.', 10, 20, 'pic_1643792747_sabun.jpg', 1, '2022-02-02 09:05:47', '2022-02-02 09:05:47');
INSERT INTO `tbl_item` VALUES ('ITEM002', 'KAT001', 'PCS', 'Sabun Mandi Wanita Aroma Melati', 'sabun mandi untuk pria dengan aroma melati', 0, 0, 'pic_1643891519_IMG_20210709_203515.jpg', 1, '2022-02-03 12:31:59', '2022-02-03 12:31:59');

-- ----------------------------
-- Table structure for tbl_itemkeluardt
-- ----------------------------
DROP TABLE IF EXISTS `tbl_itemkeluardt`;
CREATE TABLE `tbl_itemkeluardt`  (
  `kodeitemkeluardt` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kodeitemkeluar` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kodeitem` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jumlah` decimal(10, 0) NULL DEFAULT NULL,
  PRIMARY KEY (`kodeitemkeluardt`) USING BTREE,
  INDEX `fk_kodeitemkeluar_tbl_itemkeluardt`(`kodeitemkeluar`) USING BTREE,
  INDEX `fk_kodeitem_tbl_itemkeluardt`(`kodeitem`) USING BTREE,
  CONSTRAINT `fk_kodeitem_tbl_itemkeluardt` FOREIGN KEY (`kodeitem`) REFERENCES `tbl_item` (`kodeitem`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_kodeitemkeluar_tbl_itemkeluardt` FOREIGN KEY (`kodeitemkeluar`) REFERENCES `tbl_itemkeluarhd` (`kodeitemkeluar`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of tbl_itemkeluardt
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_itemkeluarhd
-- ----------------------------
DROP TABLE IF EXISTS `tbl_itemkeluarhd`;
CREATE TABLE `tbl_itemkeluarhd`  (
  `kodeitemkeluar` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kodepelanggan` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kodeuser` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tanggalitemkeluar` date NULL DEFAULT NULL,
  `keteranganitemkeluar` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `dateadditemkeluar` datetime NULL DEFAULT NULL,
  `dateupditemkeluar` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`kodeitemkeluar`) USING BTREE,
  INDEX `fk_kodeuser_tbl_itemkeluarhd`(`kodeuser`) USING BTREE,
  INDEX `fk_kodepelanggan_tbl_itemkeluarhd`(`kodepelanggan`) USING BTREE,
  CONSTRAINT `fk_kodepelanggan_tbl_itemkeluarhd` FOREIGN KEY (`kodepelanggan`) REFERENCES `tbl_pelanggan` (`kodepelanggan`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_kodeuser_tbl_itemkeluarhd` FOREIGN KEY (`kodeuser`) REFERENCES `tbl_user` (`kodeuser`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of tbl_itemkeluarhd
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_itemmasukdt
-- ----------------------------
DROP TABLE IF EXISTS `tbl_itemmasukdt`;
CREATE TABLE `tbl_itemmasukdt`  (
  `kodeitemmasukdt` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kodeitemmasuk` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kodeitem` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jumlah` decimal(10, 0) NULL DEFAULT NULL,
  PRIMARY KEY (`kodeitemmasukdt`) USING BTREE,
  INDEX `fk_kodeitemmasuk_tbl_itemmasukdt`(`kodeitemmasuk`) USING BTREE,
  CONSTRAINT `fk_kodeitemmasuk_tbl_itemmasukdt` FOREIGN KEY (`kodeitemmasuk`) REFERENCES `tbl_itemmasukhd` (`kodeitemmasuk`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of tbl_itemmasukdt
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_itemmasukhd
-- ----------------------------
DROP TABLE IF EXISTS `tbl_itemmasukhd`;
CREATE TABLE `tbl_itemmasukhd`  (
  `kodeitemmasuk` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kodesupplier` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kodeuser` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tanggalitemmasuk` date NULL DEFAULT NULL,
  `keteranganitemmasuk` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `dateadditemmasuk` datetime NULL DEFAULT NULL,
  `dateupditemmasuk` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`kodeitemmasuk`) USING BTREE,
  INDEX `fk_kodesupplier_tbl_itemmasukhd`(`kodesupplier`) USING BTREE,
  INDEX `fk_kodeuser_tbl_itemmasukhd`(`kodeuser`) USING BTREE,
  CONSTRAINT `fk_kodesupplier_tbl_itemmasukhd` FOREIGN KEY (`kodesupplier`) REFERENCES `tbl_supplier` (`kodesupplier`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_kodeuser_tbl_itemmasukhd` FOREIGN KEY (`kodeuser`) REFERENCES `tbl_user` (`kodeuser`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of tbl_itemmasukhd
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_kategori
-- ----------------------------
DROP TABLE IF EXISTS `tbl_kategori`;
CREATE TABLE `tbl_kategori`  (
  `kodekategori` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `namakategori` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statuskategori` int(1) NULL DEFAULT NULL,
  `dateaddkategori` datetime NULL DEFAULT NULL,
  `dateupdkategori` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`kodekategori`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_kategori
-- ----------------------------
INSERT INTO `tbl_kategori` VALUES ('KAT001', 'Sabun', 1, '2022-02-02 08:55:41', '2022-02-02 08:55:41');
INSERT INTO `tbl_kategori` VALUES ('KAT002', 'Shampo', 1, '2022-02-02 08:55:57', '2022-02-02 08:55:57');
INSERT INTO `tbl_kategori` VALUES ('KAT003', 'Dupa', 1, '2022-02-02 08:56:06', '2022-02-02 08:56:06');

-- ----------------------------
-- Table structure for tbl_pelanggan
-- ----------------------------
DROP TABLE IF EXISTS `tbl_pelanggan`;
CREATE TABLE `tbl_pelanggan`  (
  `kodepelanggan` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `namapelanggan` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `noteleponpelanggan` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamatpelanggan` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statuspelanggan` int(1) NULL DEFAULT NULL,
  `dateaddpelanggan` datetime NULL DEFAULT NULL,
  `dateupdpelanggan` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`kodepelanggan`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_pelanggan
-- ----------------------------
INSERT INTO `tbl_pelanggan` VALUES ('PEL001', 'Ni Kadek Dwita Karisma', '0883774837', 'Gianyar', 1, '2022-02-03 12:49:20', '2022-02-03 12:49:20');
INSERT INTO `tbl_pelanggan` VALUES ('PEL002', 'I Ketut Suariana', '073664787238', 'Kampung Jawa', 1, '2022-02-03 12:49:39', '2022-02-03 12:49:39');
INSERT INTO `tbl_pelanggan` VALUES ('PEL003', 'Gusti Lanang Trisna', '038847738', 'Gianyar', 1, '2022-02-03 12:49:53', '2022-02-03 12:49:53');

-- ----------------------------
-- Table structure for tbl_supplier
-- ----------------------------
DROP TABLE IF EXISTS `tbl_supplier`;
CREATE TABLE `tbl_supplier`  (
  `kodesupplier` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `namasupplier` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `noteleponsupplier` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamatsupplier` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statussupplier` int(1) NULL DEFAULT NULL,
  `dateaddsupplier` datetime NULL DEFAULT NULL,
  `dateupdsupplier` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`kodesupplier`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tbl_supplier
-- ----------------------------
INSERT INTO `tbl_supplier` VALUES ('SUP001', 'UD. Sabun Wangi Pratama Sekar', '089378728937', 'Jalan Raya Menjangan', 1, '2022-02-03 12:42:27', '2022-02-03 12:42:27');
INSERT INTO `tbl_supplier` VALUES ('SUP002', 'CV. Kerja Baju Putih Lestari', '0938849938', 'Jalan Pulau Menjangan', 1, '2022-02-03 12:43:16', '2022-02-03 12:43:16');

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user`  (
  `kodeuser` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `username` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nohp` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alamat` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `akses` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jk` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `statususer` int(1) NULL DEFAULT NULL,
  `dateadduser` datetime NULL DEFAULT NULL,
  `dateupduser` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`kodeuser`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of tbl_user
-- ----------------------------
INSERT INTO `tbl_user` VALUES ('USER001', 'admin', 'eyJpdiI6IjlHTTJtM25NQ2ZWRDhTWXlnZHh0Q0E9PSIsInZhbHVlIjoiNVB5YVdTME03WDNrUm5HaGdzTzlNUT09IiwibWFjIjoiYzcxODI0MTg3MmI5NzNmODlmZTljNWZkNjczYjg5NDAzN2ExMDk2MDZiODFlYjcyOTdmOWY1MTZhMTNmZDU0NCJ9', 'Admin Sistem', '--', '--', '--', 'ADMIN', 'L', 1, '2022-01-20 10:19:07', '2022-01-20 10:19:07');
INSERT INTO `tbl_user` VALUES ('USER002', 'maya', 'eyJpdiI6Iis2STA2YWR6cmpYcTlNekRHWWVzTVE9PSIsInZhbHVlIjoidVdyRStMZEhWbk83Y2hiSGFMVnBndz09IiwibWFjIjoiNTc1NzFhZjRiODI3ZTg0MGU1MzBmMzE2ODBlNzAxOTE1YTZkNGFlZDk1N2QzNTA5YzJmNTExNDRlYTA0YTA4ZCIsInRhZyI6IiJ9', 'Maya Ayuning', 'maya@gmail.com', '', '', 'STAFF', 'P', 1, '2022-02-03 12:39:13', '2022-02-03 12:39:13');
INSERT INTO `tbl_user` VALUES ('USER003', 'chandra', 'eyJpdiI6IlpwcXc1dytLZWdhYUxDZzZvWElEMmc9PSIsInZhbHVlIjoiU1gvc0R4Z3U4RU9LdGVvNjFuZXB5dz09IiwibWFjIjoiMDc4YjViMzhkMjlhOTQwZjQ4NmUxODUxNDk2NDU3MzUyZWFlNzMxOGZhOGEyMDQ2MjQzODE3MmMxMjYwYjNlNyIsInRhZyI6IiJ9', 'Chandra Dewi', 'chandra_dewi1205@yahoo.com', '', '', 'STAFF', 'P', 1, '2022-02-03 12:39:34', '2022-02-03 12:39:34');

SET FOREIGN_KEY_CHECKS = 1;
