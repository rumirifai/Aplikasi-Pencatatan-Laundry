<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Mendapatkan nama pelanggan dari session atau sumber lainnya
$nama = $_SESSION['username'];

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<style>
  /* Additional styles to adjust the content position */
  #admin {
    padding-top: 80px; /* Adjust the value as needed */
  }
</style>

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <h4>Selamat datang, <?php echo $nama; ?></h4>
      <!-- Konten Halaman Admin -->
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
