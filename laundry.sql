-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2023 at 06:49 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundry`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateTransaction` (IN `p_no_cucian` INT)   BEGIN
    DECLARE v_id_pelanggan INT;
    DECLARE v_id_status_transaksi INT;
    DECLARE v_subtotal DECIMAL(18, 2);

    -- Dapatkan id_pelanggan dari permintaan cucian
    SELECT id_pelanggan INTO v_id_pelanggan FROM PermintaanCucian WHERE no_cucian = p_no_cucian;

    -- Set id_status_transaksi sesuai dengan kebutuhan
    SET v_id_status_transaksi = 1; -- Ganti dengan id_status_transaksi yang sesuai

    -- Hitung subtotal sebagai jumlah harga_itemCucian pada ItemCucian dengan no_cucian yang sama
    SELECT SUM(ic.harga_itemCucian) INTO v_subtotal
    FROM ItemCucian ic
    WHERE ic.no_cucian = p_no_cucian;

    -- Tambahkan entri baru ke tabel Transaksi
    INSERT INTO Transaksi (tgl_transaksi, no_cucian, id_pelanggan, id_status_transaksi, subtotal)
    VALUES (CURRENT_DATE(), p_no_cucian, v_id_pelanggan, v_id_status_transaksi, v_subtotal);

    -- Alihkan ke halaman daftar_transaksi.php
    SELECT LAST_INSERT_ID() AS no_transaksi;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `role`) VALUES
(1, 'admin1', 'adminpassword1', 'admin'),
(2, 'admin2', 'adminpassword2', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id_item` int(11) NOT NULL,
  `jenis_item` varchar(255) NOT NULL,
  `harga_per_item` decimal(18,2) NOT NULL,
  `id_prioritas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id_item`, `jenis_item`, `harga_per_item`, `id_prioritas`) VALUES
(1, 'Baju', 17000.00, 1),
(2, 'Baju', 16000.00, 2),
(3, 'Baju', 15000.00, 3),
(4, 'Baju', 14000.00, 4),
(5, 'Celana', 22000.00, 1),
(6, 'Celana', 21000.00, 2),
(7, 'Celana', 20000.00, 3),
(8, 'Celana', 19000.00, 4),
(9, 'Kemeja', 22000.00, 1),
(10, 'Kemeja', 21000.00, 2),
(11, 'Kemeja', 20000.00, 3),
(12, 'Kemeja', 19000.00, 4),
(13, 'Jaket', 28000.00, 1),
(14, 'Jaket', 27000.00, 2),
(15, 'Jaket', 26000.00, 3),
(16, 'Jaket', 25000.00, 4),
(17, 'Sweater', 25000.00, 1),
(18, 'Sweater', 24000.00, 2),
(19, 'Sweater', 23000.00, 3),
(20, 'Sweater', 22000.00, 4),
(21, 'Jas', 35000.00, 1),
(22, 'Jas', 34000.00, 2),
(23, 'Jas', 33000.00, 3),
(24, 'Jas', 32000.00, 4),
(25, 'Rok', 18000.00, 1),
(26, 'Rok', 17000.00, 2),
(27, 'Rok', 16000.00, 3),
(28, 'Rok', 15000.00, 4),
(29, 'Scarf', 12000.00, 1),
(30, 'Scarf', 11000.00, 2),
(31, 'Scarf', 10000.00, 3),
(32, 'Scarf', 9000.00, 4),
(33, 'Topi', 10000.00, 1),
(34, 'Topi', 9000.00, 2),
(35, 'Topi', 8000.00, 3),
(36, 'Topi', 7000.00, 4),
(37, 'Sarung Tangan', 8000.00, 1),
(38, 'Sarung Tangan', 7000.00, 2),
(39, 'Sarung Tangan', 6000.00, 3),
(40, 'Sarung Tangan', 5000.00, 4),
(41, 'Kaos Kaki', 7000.00, 1),
(42, 'Kaos Kaki', 6000.00, 2),
(43, 'Kaos Kaki', 5000.00, 3),
(44, 'Kaos Kaki', 4000.00, 4),
(45, 'Selimut', 40000.00, 1),
(46, 'Selimut', 39000.00, 2),
(47, 'Selimut', 38000.00, 3),
(48, 'Selimut', 37000.00, 4),
(49, 'Handuk', 15000.00, 1),
(50, 'Handuk', 14000.00, 2),
(51, 'Handuk', 13000.00, 3),
(52, 'Handuk', 12000.00, 4),
(53, 'Bantal', 12000.00, 1),
(54, 'Bantal', 11000.00, 2),
(55, 'Bantal', 10000.00, 3),
(56, 'Bantal', 9000.00, 4),
(57, 'Gorden', 30000.00, 1),
(58, 'Gorden', 29000.00, 2),
(59, 'Gorden', 28000.00, 3),
(60, 'Gorden', 27000.00, 4);

--
-- Triggers `item`
--
DELIMITER $$
CREATE TRIGGER `update_harga_itemCucian_after_update_item` AFTER UPDATE ON `item` FOR EACH ROW BEGIN
    UPDATE ItemCucian
    SET harga_itemCucian = NEW.harga_per_item * jumlah_item
    WHERE id_item = NEW.id_item;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `itemcucian`
--

CREATE TABLE `itemcucian` (
  `no_itemCucian` int(11) NOT NULL,
  `no_cucian` int(11) DEFAULT NULL,
  `id_item` int(11) DEFAULT NULL,
  `ukuran` varchar(50) NOT NULL,
  `warna` varchar(50) NOT NULL,
  `jumlah_item` int(11) DEFAULT NULL,
  `harga_itemCucian` decimal(18,2) NOT NULL,
  `id_prioritas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `itemcucian`
--

INSERT INTO `itemcucian` (`no_itemCucian`, `no_cucian`, `id_item`, `ukuran`, `warna`, `jumlah_item`, `harga_itemCucian`, `id_prioritas`) VALUES
(1, 1, 1, 'L', 'hitam', 1, 17000.00, 1),
(2, 1, 1, 'L', 'putih', 2, 34000.00, 1),
(3, 1, 49, 'L', 'lainnya', 1, 15000.00, 1);

--
-- Triggers `itemcucian`
--
DELIMITER $$
CREATE TRIGGER `hitung_harga_itemCucian` BEFORE INSERT ON `itemcucian` FOR EACH ROW BEGIN
    DECLARE harga DECIMAL(18, 2);
    SET harga = (SELECT harga_per_item FROM Item WHERE id_item = NEW.id_item);
    SET NEW.harga_itemCucian = harga * NEW.jumlah_item;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hitung_total_item` AFTER INSERT ON `itemcucian` FOR EACH ROW BEGIN
    UPDATE PermintaanCucian
    SET total_item = (
        SELECT SUM(jumlah_item)
        FROM ItemCucian
        WHERE no_cucian = NEW.no_cucian
    )
    WHERE no_cucian = NEW.no_cucian;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_harga_itemCucian` BEFORE UPDATE ON `itemcucian` FOR EACH ROW BEGIN
    DECLARE harga DECIMAL(18, 2);
    SET harga = (SELECT harga_per_item FROM Item WHERE id_item = NEW.id_item);
    SET NEW.harga_itemCucian = harga * NEW.jumlah_item;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_total_item` AFTER UPDATE ON `itemcucian` FOR EACH ROW BEGIN
    UPDATE PermintaanCucian
    SET total_item = (
        SELECT SUM(jumlah_item)
        FROM ItemCucian
        WHERE no_cucian = NEW.no_cucian
    )
    WHERE no_cucian = NEW.no_cucian;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_total_item_after_delete` AFTER DELETE ON `itemcucian` FOR EACH ROW BEGIN
    DECLARE no_cucian_val INT;
    SET no_cucian_val = OLD.no_cucian;
    
    UPDATE PermintaanCucian
    SET total_item = (
        SELECT SUM(jumlah_item)
        FROM ItemCucian
        WHERE no_cucian = no_cucian_val
    )
    WHERE no_cucian = no_cucian_val;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profil_image_url` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'pelanggan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `alamat`, `telepon`, `username`, `password`, `profil_image_url`, `role`) VALUES
(3, 'Kepi', 'Bogor', '08123456789', 'siganteng123', '$2y$10$oA4NGPA2EdIC1nE3EWgReOFXW7/7ZAcPCmgekmEvsAAzlq4qj2yNi', 'uploads/bigsmoke.png', 'pelanggan'),
(8, 'Alip', 'Isten', '0810929292', 'siganteng1', '$2y$10$5FFjX/aEdNfN0INSAy9m7uat6yVpM.xJO3CtTzICFk.LViME6zfPO', NULL, 'pelanggan'),
(9, 'Rumi', 'Cikuda', '0810929292', 'siganteng12', '$2y$10$1zW.Lh/D70if93N3Ji/4veHKd/GDANZy5U3j9jnETTL.X4YhkIale', NULL, 'pelanggan');

-- --------------------------------------------------------

--
-- Table structure for table `permintaancucian`
--

CREATE TABLE `permintaancucian` (
  `no_cucian` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_prioritas` int(11) DEFAULT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `total_item` int(11) DEFAULT NULL,
  `id_status_cucian` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permintaancucian`
--

INSERT INTO `permintaancucian` (`no_cucian`, `id_pelanggan`, `id_prioritas`, `tgl_masuk`, `tgl_selesai`, `total_item`, `id_status_cucian`) VALUES
(1, 3, 1, '2023-05-28', '2023-05-29', 4, 1);

--
-- Triggers `permintaancucian`
--
DELIMITER $$
CREATE TRIGGER `set_tanggal_masuk_tanggal_selesai_before_insert` BEFORE INSERT ON `permintaancucian` FOR EACH ROW BEGIN
    DECLARE v_durasi INT;
    DECLARE v_tanggal_selesai DATE;

    -- Dapatkan durasi berdasarkan prioritas
    SELECT durasi INTO v_durasi FROM Prioritas WHERE id_prioritas = NEW.id_prioritas;

    -- Hitung tanggal selesai berdasarkan durasi
    SET v_tanggal_selesai = DATE_ADD(NEW.tgl_masuk, INTERVAL v_durasi DAY);

    -- Set nilai tanggal selesai pada record yang akan dimasukkan
    SET NEW.tgl_selesai = v_tanggal_selesai;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_tanggal_selesai_before_update` BEFORE UPDATE ON `permintaancucian` FOR EACH ROW BEGIN
    DECLARE v_durasi INT;
    DECLARE v_tanggal_selesai DATE;

    -- Dapatkan durasi berdasarkan prioritas
    SELECT durasi INTO v_durasi FROM Prioritas WHERE id_prioritas = NEW.id_prioritas;

    -- Hitung tanggal selesai berdasarkan durasi
    SET v_tanggal_selesai = DATE_ADD(NEW.tgl_masuk, INTERVAL v_durasi DAY);

    -- Set nilai tanggal selesai pada record yang akan diubah
    SET NEW.tgl_selesai = v_tanggal_selesai;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `prioritas`
--

CREATE TABLE `prioritas` (
  `id_prioritas` int(11) NOT NULL,
  `jenis_prioritas` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `durasi` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prioritas`
--

INSERT INTO `prioritas` (`id_prioritas`, `jenis_prioritas`, `keterangan`, `durasi`, `image_url`) VALUES
(1, 'Instant', 'Keterangan Instant', 1, 'uploads/instant.png'),
(2, 'Express', 'Keterangan Express', 2, 'uploads/express.png'),
(3, 'Fast', 'Keterangan Fast', 3, 'uploads/fast.png'),
(4, 'Regular', 'Keterangan Regular', 5, 'uploads/regular.png');

-- --------------------------------------------------------

--
-- Table structure for table `statuscucian`
--

CREATE TABLE `statuscucian` (
  `id_status_cucian` int(11) NOT NULL,
  `jenis_status_cucian` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statuscucian`
--

INSERT INTO `statuscucian` (`id_status_cucian`, `jenis_status_cucian`) VALUES
(1, 'Belum diproses'),
(2, 'Sedang diproses'),
(3, 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `statustransaksi`
--

CREATE TABLE `statustransaksi` (
  `id_status_transaksi` int(11) NOT NULL,
  `jenis_status_transaksi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statustransaksi`
--

INSERT INTO `statustransaksi` (`id_status_transaksi`, `jenis_status_transaksi`) VALUES
(1, 'Belum Bayar'),
(2, 'Sudah Dibayar');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `no_transaksi` int(11) NOT NULL,
  `tgl_transaksi` date DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `no_cucian` int(11) DEFAULT NULL,
  `id_status_transaksi` int(11) DEFAULT NULL,
  `subtotal` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`no_transaksi`, `tgl_transaksi`, `id_pelanggan`, `no_cucian`, `id_status_transaksi`, `subtotal`) VALUES
(1, '2023-05-29', 3, 1, 2, 66000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_prioritas` (`id_prioritas`);

--
-- Indexes for table `itemcucian`
--
ALTER TABLE `itemcucian`
  ADD PRIMARY KEY (`no_itemCucian`),
  ADD KEY `no_cucian` (`no_cucian`),
  ADD KEY `id_prioritas` (`id_prioritas`),
  ADD KEY `id_item` (`id_item`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `permintaancucian`
--
ALTER TABLE `permintaancucian`
  ADD PRIMARY KEY (`no_cucian`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_prioritas` (`id_prioritas`),
  ADD KEY `id_status_cucian` (`id_status_cucian`);

--
-- Indexes for table `prioritas`
--
ALTER TABLE `prioritas`
  ADD PRIMARY KEY (`id_prioritas`);

--
-- Indexes for table `statuscucian`
--
ALTER TABLE `statuscucian`
  ADD PRIMARY KEY (`id_status_cucian`);

--
-- Indexes for table `statustransaksi`
--
ALTER TABLE `statustransaksi`
  ADD PRIMARY KEY (`id_status_transaksi`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`no_transaksi`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `no_cucian` (`no_cucian`),
  ADD KEY `id_status_transaksi` (`id_status_transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `itemcucian`
--
ALTER TABLE `itemcucian`
  MODIFY `no_itemCucian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `permintaancucian`
--
ALTER TABLE `permintaancucian`
  MODIFY `no_cucian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prioritas`
--
ALTER TABLE `prioritas`
  MODIFY `id_prioritas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `statuscucian`
--
ALTER TABLE `statuscucian`
  MODIFY `id_status_cucian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `statustransaksi`
--
ALTER TABLE `statustransaksi`
  MODIFY `id_status_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `no_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`id_prioritas`) REFERENCES `prioritas` (`id_prioritas`);

--
-- Constraints for table `itemcucian`
--
ALTER TABLE `itemcucian`
  ADD CONSTRAINT `itemcucian_ibfk_1` FOREIGN KEY (`no_cucian`) REFERENCES `permintaancucian` (`no_cucian`),
  ADD CONSTRAINT `itemcucian_ibfk_2` FOREIGN KEY (`id_prioritas`) REFERENCES `prioritas` (`id_prioritas`),
  ADD CONSTRAINT `itemcucian_ibfk_3` FOREIGN KEY (`id_item`) REFERENCES `item` (`id_item`);

--
-- Constraints for table `permintaancucian`
--
ALTER TABLE `permintaancucian`
  ADD CONSTRAINT `permintaancucian_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `permintaancucian_ibfk_2` FOREIGN KEY (`id_prioritas`) REFERENCES `prioritas` (`id_prioritas`),
  ADD CONSTRAINT `permintaancucian_ibfk_3` FOREIGN KEY (`id_status_cucian`) REFERENCES `statuscucian` (`id_status_cucian`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`no_cucian`) REFERENCES `permintaancucian` (`no_cucian`),
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_status_transaksi`) REFERENCES `statustransaksi` (`id_status_transaksi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
