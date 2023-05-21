CREATE DATABASE laundry;

USE laundry;

CREATE TABLE prioritas
(
    id_prioritas INT NOT NULL PRIMARY KEY,
    jenis_prioritas CHAR(15)
);

CREATE TABLE item
(
    id_item CHAR(3) NOT NULL PRIMARY KEY,
    jenis_item VARCHAR(25)
)

CREATE TABLE pelanggan
(
    id_pelanggan CHAR(5) NOT NULL PRIMARY KEY,
    nama VARCHAR(64),
    alamat VARCHAR(128),
    telepon CHAR(12)
);

CREATE TABLE permintaan_cucian
(
    no_cucian INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_pelanggan CHAR(6),
    id_prioritas INT,
    no_itemCucian INT, 
    jumlah_cucian INT,
    status_cucian VARCHAR(15),
    tgl_mulai DATE,
    tgl_selesai DATE,
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    FOREIGN KEY (id_prioritas) REFERENCES prioritas(id_prioritas),
    FOREIGN KEY (no_itemCucian) REFERENCES item_cucian(no_itemCucian)
);

CREATE TABLE item_cucian
(
    no_itemCucian INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    no_cucian INT,
    id_item CHAR(3),
    ukuran CHAR(3),
    warna CHAR(10),
    harga_item INT,
    FOREIGN KEY (no_cucian) REFERENCES permintaan_cucian(no_cucian),
    FOREIGN KEY (id_item) REFERENCES item(id_item)
);

CREATE TABLE transaksi
(
    no_transaksi CHAR(6) NOT NULL PRIMARY KEY,
    id_pelanggan CHAR(5),
    no_cucian INT,
    no_itemCucian INT,
    status_transaksi VARCHAR(20),
    subtotal INT,
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    FOREIGN KEY (no_cucian) REFERENCES permintaan_cucian(no_cucian),
    FOREIGN KEY (no_itemCucian) REFERENCES item_cucian(no_itemCucian)
)