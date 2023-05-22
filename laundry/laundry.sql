-- Tabel Pelanggan
CREATE TABLE Pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    telepon VARCHAR(15) NOT NULL
);

-- Tabel Prioritas
CREATE TABLE Prioritas (
    id_prioritas INT AUTO_INCREMENT PRIMARY KEY,
    jenis_prioritas VARCHAR(255) NOT NULL
);

-- Tabel PermintaanCucian
CREATE TABLE PermintaanCucian (
    no_cucian INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT,
    id_prioritas INT,
    no_itemCucian INT,
    tgl_masuk DATE,
    tgl_selesai DATE,
    total_item INT,
    status_cucian VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_pelanggan) REFERENCES Pelanggan(id_pelanggan),
    FOREIGN KEY (id_prioritas) REFERENCES Prioritas(id_prioritas)
);

-- Tabel Item
CREATE TABLE Item (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    jenis_item VARCHAR(255) NOT NULL,
    id_prioritas INT,
    harga_per_item_reg DECIMAL(10, 2) NOT NULL,
    harga_per_item_exp DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_prioritas) REFERENCES Prioritas(id_prioritas)
);

-- Tabel ItemCucian
CREATE TABLE ItemCucian (
    no_itemCucian INT AUTO_INCREMENT PRIMARY KEY,
    no_cucian INT,
    id_item INT,
    ukuran VARCHAR(50) NOT NULL,
    warna VARCHAR(50) NOT NULL,
    jumlah_item INT,
    harga_itemCucian DECIMAL(10, 2) NOT NULL,
    id_prioritas INT,
    FOREIGN KEY (no_cucian) REFERENCES PermintaanCucian(no_cucian),
    FOREIGN KEY (id_prioritas) REFERENCES Prioritas(id_prioritas),
    FOREIGN KEY (id_item) REFERENCES Item(id_item)
);

-- Tabel Transaksi
CREATE TABLE Transaksi (
    no_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT,
    no_cucian INT,
    status_transaksi VARCHAR(255) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_pelanggan) REFERENCES Pelanggan(id_pelanggan),
    FOREIGN KEY (no_cucian) REFERENCES PermintaanCucian(no_cucian)
);

-- Tabel Admin
CREATE TABLE Admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tabel Pelanggan
INSERT INTO Pelanggan (nama, alamat, telepon) VALUES
('John Doe', 'Jl. Contoh No. 123', '081234567890'),
('Jane Smith', 'Jl. Contoh No. 456', '087654321098');

-- Tabel Prioritas
INSERT INTO Prioritas (jenis_prioritas) VALUES
('Reguler'),
('Express');

-- Tabel PermintaanCucian
INSERT INTO PermintaanCucian (id_pelanggan, id_prioritas, no_itemCucian, tgl_masuk, tgl_selesai, total_item, status_cucian) VALUES
(1, 1, 1, '2023-05-01', '2023-05-03', 5, 'Selesai'),
(2, 2, 2, '2023-05-02', '2023-05-04', 3, 'Proses');

-- Tabel Item
INSERT INTO Item (jenis_item, id_prioritas, harga_per_item_reg, harga_per_item_exp) VALUES
('Baju', 1, 5000, 8000),
('Celana', 1, 7000, 10000);

-- Tabel ItemCucian
INSERT INTO ItemCucian (no_cucian, id_item, ukuran, warna, jumlah_item, harga_itemCucian, id_prioritas) VALUES
(1, 1, 'L', 'Merah', 3, 15000, 1), -- Item cucian no_cucian 1, jenis_item 'Baju', ukuran 'L', warna 'Merah', jumlah_item 3, harga_itemCucian 15000 rupiah, dengan prioritas 'Reguler'
(2, 2, 'M', 'Biru', 2, 14000, 2); -- Item cucian no_cucian 2, jenis_item 'Celana', ukuran 'M', warna 'Biru', jumlah_item 2, harga_itemCucian 14000 rupiah, dengan prioritas 'Express'

-- Tabel Transaksi
INSERT INTO Transaksi (id_pelanggan, no_cucian, status_transaksi, subtotal) VALUES
(1, 1, 'Selesai', 15000),
(2, 2, 'Proses', 14000);

-- Tabel Admin
INSERT INTO Admin (username, password) VALUES
('admin1', 'password1'),
('admin2', 'password2');