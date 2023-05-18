create database laundry;

use laundry;

create table pelanggan(
    id_pelanggan int not null,
    nama varchar(64),
    alamat address,
    kontak varchar(20),
    primary key (id_pelanggan)
);

create table permintaan_cucian(
    no_cucian int not null,
    id_pelanggan int not null,
    id_item int not null,
    jenis_cucian varchar(30),
    jumlah_cucian int,
    prioritas_cucian varchar(30),
    tgl_cucian date,
    primary key (no_cucian)
);

create table item_cucian(
    id_item int not null,
    no_cucian int not null,
    jenis_item varchar(30),
    ukuran varchar(10),
    warna varchar(10),
    primary key (id_item)
);

create table transaksi(
    no_transaksi int not null,
    id_pelanggan int not null,
    no_cucian int not null,
    id_item int not null,
    stat varchar(30),
    subtotal varchar(30),
    primary key (no_transaksi)
);