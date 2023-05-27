-- Membuat Tabel Pelanggan
CREATE TABLE Pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    telepon VARCHAR(15) NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'pelanggan'
);

-- Membuat Tabel Prioritas
CREATE TABLE Prioritas (
    id_prioritas INT AUTO_INCREMENT PRIMARY KEY,
    jenis_prioritas VARCHAR(255) NOT NULL,
    keterangan VARCHAR(255),
    durasi INT,
    image_url VARCHAR (255)
);

-- Membuat Tabel StatusCucian
CREATE TABLE StatusCucian (
    id_status_cucian INT AUTO_INCREMENT PRIMARY KEY,
    jenis_status_cucian VARCHAR(255) NOT NULL
);

-- Membuat Tabel PermintaanCucian
CREATE TABLE PermintaanCucian (
    no_cucian INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT,
    id_prioritas INT,
    tgl_masuk DATE,
    tgl_selesai DATE,
    total_item INT,
    id_status_cucian INT,
    FOREIGN KEY (id_pelanggan) REFERENCES Pelanggan(id_pelanggan),
    FOREIGN KEY (id_prioritas) REFERENCES Prioritas(id_prioritas),
    FOREIGN KEY (id_status_cucian) REFERENCES StatusCucian(id_status_cucian)
);

-- Membuat Tabel Item
CREATE TABLE Item (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    jenis_item VARCHAR(255) NOT NULL,
    harga_per_item DECIMAL(18, 2) NOT NULL,
    id_prioritas INT,
    FOREIGN KEY (id_prioritas) REFERENCES Prioritas(id_prioritas)
);

-- Membuat Tabel ItemCucian
CREATE TABLE ItemCucian (
    no_itemCucian INT AUTO_INCREMENT PRIMARY KEY,
    no_cucian INT,
    id_item INT,
    ukuran VARCHAR(50) NOT NULL,
    warna VARCHAR(50) NOT NULL,
    jumlah_item INT,
    harga_itemCucian DECIMAL(18, 2) NOT NULL,
    id_prioritas INT,
    FOREIGN KEY (no_cucian) REFERENCES PermintaanCucian(no_cucian),
    FOREIGN KEY (id_prioritas) REFERENCES Prioritas(id_prioritas),
    FOREIGN KEY (id_item) REFERENCES Item(id_item)
);

-- Membuat Tabel StatusTransaksi
CREATE TABLE StatusTransaksi (
    id_status_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    jenis_status_transaksi VARCHAR(255) NOT NULL
);

-- Membuat Tabel Transaksi
CREATE TABLE Transaksi (
    no_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    tgl_transaksi DATE,
    id_pelanggan INT,
    no_cucian INT,
    id_status_transaksi INT,
    subtotal DECIMAL(18, 2) NOT NULL,
    FOREIGN KEY (id_pelanggan) REFERENCES Pelanggan(id_pelanggan),
    FOREIGN KEY (no_cucian) REFERENCES PermintaanCucian(no_cucian),
    FOREIGN KEY (id_status_transaksi) REFERENCES StatusTransaksi(id_status_transaksi)
);

-- Membuat Tabel Admin
CREATE TABLE Admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'admin'
);

-- Trigger untuk menghitung total_item pada PermintaanCucian setelah Insert pada ItemCucian
DELIMITER //
CREATE TRIGGER hitung_total_item
AFTER INSERT ON ItemCucian
FOR EACH ROW
BEGIN
    UPDATE PermintaanCucian
    SET total_item = (
        SELECT SUM(jumlah_item)
        FROM ItemCucian
        WHERE no_cucian = NEW.no_cucian
    )
    WHERE no_cucian = NEW.no_cucian;
END //
DELIMITER ;

-- Trigger untuk menghitung harga_itemCucian pada ItemCucian sebelum Insert
DELIMITER //
CREATE TRIGGER hitung_harga_itemCucian
BEFORE INSERT ON ItemCucian
FOR EACH ROW
BEGIN
    DECLARE harga DECIMAL(18, 2);
    SET harga = (SELECT harga_per_item FROM Item WHERE id_item = NEW.id_item);
    SET NEW.harga_itemCucian = harga * NEW.jumlah_item;
END //
DELIMITER ;

-- Trigger untuk memperbarui total_item pada PermintaanCucian setelah Update pada ItemCucian
DELIMITER //
CREATE TRIGGER update_total_item
AFTER UPDATE ON ItemCucian
FOR EACH ROW
BEGIN
    UPDATE PermintaanCucian
    SET total_item = (
        SELECT SUM(jumlah_item)
        FROM ItemCucian
        WHERE no_cucian = NEW.no_cucian
    )
    WHERE no_cucian = NEW.no_cucian;
END //
DELIMITER ;

-- Trigger untuk memperbarui harga_itemCucian pada ItemCucian setelah Update
DELIMITER //
CREATE TRIGGER update_harga_itemCucian
BEFORE UPDATE ON ItemCucian
FOR EACH ROW
BEGIN
    DECLARE harga DECIMAL(18, 2);
    SET harga = (SELECT harga_per_item FROM Item WHERE id_item = NEW.id_item);
    SET NEW.harga_itemCucian = harga * NEW.jumlah_item;
END //
DELIMITER ;


-- Trigger untuk memperbarui harga_itemCucian saat harga per item diubah
DELIMITER //
CREATE TRIGGER update_harga_itemCucian_after_update_item
AFTER UPDATE ON Item
FOR EACH ROW
BEGIN
    UPDATE ItemCucian
    SET harga_itemCucian = NEW.harga_per_item * jumlah_item
    WHERE id_item = NEW.id_item;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER update_total_item_after_delete
AFTER DELETE ON ItemCucian
FOR EACH ROW
BEGIN
    DECLARE no_cucian_val INT;
    SET no_cucian_val = OLD.no_cucian;
    
    UPDATE PermintaanCucian
    SET total_item = (
        SELECT SUM(jumlah_item)
        FROM ItemCucian
        WHERE no_cucian = no_cucian_val
    )
    WHERE no_cucian = no_cucian_val;
END //
DELIMITER ;

DELIMITER //

CREATE PROCEDURE CreateTransaction(
    IN p_no_cucian INT
)
BEGIN
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
END //

DELIMITER ;

-- Trigger BEFORE INSERT untuk mengatur tanggal masuk dan tanggal selesai
DELIMITER //

CREATE TRIGGER set_tanggal_masuk_tanggal_selesai_before_insert
BEFORE INSERT ON PermintaanCucian
FOR EACH ROW
BEGIN
    DECLARE v_durasi INT;
    DECLARE v_tanggal_selesai DATE;

    -- Dapatkan durasi berdasarkan prioritas
    SELECT durasi INTO v_durasi FROM Prioritas WHERE id_prioritas = NEW.id_prioritas;

    -- Hitung tanggal selesai berdasarkan durasi
    SET v_tanggal_selesai = DATE_ADD(NEW.tgl_masuk, INTERVAL v_durasi DAY);

    -- Set nilai tanggal selesai pada record yang akan dimasukkan
    SET NEW.tgl_selesai = v_tanggal_selesai;
END //

DELIMITER ;


-- Trigger BEFORE UPDATE untuk mengatur tanggal selesai saat mengubah tanggal masuk
DELIMITER //

CREATE TRIGGER set_tanggal_selesai_before_update
BEFORE UPDATE ON PermintaanCucian
FOR EACH ROW
BEGIN
    DECLARE v_durasi INT;
    DECLARE v_tanggal_selesai DATE;

    -- Dapatkan durasi berdasarkan prioritas
    SELECT durasi INTO v_durasi FROM Prioritas WHERE id_prioritas = NEW.id_prioritas;

    -- Hitung tanggal selesai berdasarkan durasi
    SET v_tanggal_selesai = DATE_ADD(NEW.tgl_masuk, INTERVAL v_durasi DAY);

    -- Set nilai tanggal selesai pada record yang akan diubah
    SET NEW.tgl_selesai = v_tanggal_selesai;
END //

DELIMITER ;



-- Menambahkan data ke tabel Pelanggan
INSERT INTO Pelanggan (nama, alamat, telepon, username, password) VALUES
('John Doe', 'Jl. Raya No. 123', '081234567890', 'johndoe', 'password123'),
('Jane Smith', 'Jl. Utama No. 456', '089876543210', 'janesmith', 'password456');

-- Mengisi tabel Prioritas
INSERT INTO Prioritas (jenis_prioritas, keterangan, durasi, image_url)
VALUES
    ('Instant', 'Keterangan Instant', 1, 'https://example.com/instant.jpg'),
    ('Express', 'Keterangan Express', 2, 'https://example.com/express.jpg'),
    ('Fast', 'Keterangan Fast', 3, 'https://example.com/fast.jpg'),
    ('Regular', 'Keterangan Regular', 5, 'https://example.com/regular.jpg');


-- Mengisi tabel StatusCucian
INSERT INTO StatusCucian (jenis_status_cucian) VALUES
    ('Belum diproses'),
    ('Sedang diproses'),
    ('Selesai');

-- Menambahkan data ke tabel PermintaanCucian
INSERT INTO PermintaanCucian (id_pelanggan, id_prioritas, tgl_masuk, total_item, id_status_cucian) VALUES
(1, 2, '2023-05-25', 0, 1),
(2, 3, '2023-05-26', 0, 1);

-- Menambahkan data ke tabel Item
INSERT INTO Item (jenis_item, harga_per_item, id_prioritas) VALUES
('Baju', 17000, 1),
('Baju', 15000, 2),
('Celana', 22000, 1),
('Celana', 20000, 2),
('Kemeja', 22000, 1),
('Kemeja', 20000, 2);

-- Menambahkan data ke tabel ItemCucian
INSERT INTO ItemCucian (no_cucian, id_item, ukuran, warna, jumlah_item, harga_itemCucian, id_prioritas) VALUES
(1, 1, 'M', 'Merah', 2, 0, 1),
(1, 3, 'L', 'Biru', 3, 0, 1),
(2, 1, 'S', 'Hitam', 1, 0, 1),
(2, 3, 'XL', 'Putih', 2, 0, 1);

-- Menambahkan data ke tabel StatusTransaksi
INSERT INTO StatusTransaksi (jenis_status_transaksi) VALUES
('Belum Bayar'),
('Sudah Dibayar');

-- Menambahkan data ke tabel Admin
INSERT INTO Admin (username, password) VALUES
('admin1', 'adminpassword1'),
('admin2', 'adminpassword2');
