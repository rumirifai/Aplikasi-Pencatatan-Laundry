<?php
$host = 'localhost'; // Ganti dengan host database Anda
$user = 'root'; // Ganti dengan username database Anda
$password = 'kahfi7185'; // Ganti dengan password database Anda
$database = 'laundry'; // Ganti dengan nama database Anda

$conn = mysqli_connect($host, $user, $password, $database);

// Periksa koneksi
if (mysqli_connect_errno()) {
    echo "Gagal terhubung ke MySQL: " . mysqli_connect_error();
    exit();
}
