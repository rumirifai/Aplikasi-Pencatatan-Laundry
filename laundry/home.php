<?php
session_start();

// Periksa status login
if (!isset($_SESSION['login_id'])) {
  header('Location: login.php');
  exit();
}

// Sambungan ke database
require_once 'config.php';

// Menggunakan $conn untuk melakukan operasi database

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Laundry Management System - Home</title>

  <!-- Tambahkan kode lainnya yang diperlukan untuk tampilan -->

</head>

<body>
  <!-- Tambahkan konten tampilan lainnya seperti header, navbar, dll. -->

  <h1>Welcome to Home Page</h1>

  <!-- Tambahkan kode HTML lainnya sesuai kebutuhan -->

</body>

</html>
