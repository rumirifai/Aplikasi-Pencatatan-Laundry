<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Periksa apakah parameter ID tersedia dan bukan kosong
if (isset($_GET['id']) && !empty($_GET['id'])) {
  // Mendapatkan ID prioritas yang akan dihapus dari parameter URL
  $idPrioritas = $_GET['id'];

  // Periksa apakah formulir dikirimkan dengan metode POST
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perbarui nilai id_prioritas menjadi NULL dalam tabel PermintaanCucian untuk prioritas yang akan dihapus
    $updatePermintaanCucianQuery = "UPDATE PermintaanCucian SET id_prioritas = NULL WHERE id_prioritas = $idPrioritas";
    $resultUpdatePermintaanCucian = mysqli_query($conn, $updatePermintaanCucianQuery);

    // Periksa apakah pembaruan PermintaanCucian berhasil
    if ($resultUpdatePermintaanCucian) {
      // Hapus prioritas dari tabel Prioritas berdasarkan ID
      $deleteQuery = "DELETE FROM Prioritas WHERE id_prioritas = $idPrioritas";
      $resultDelete = mysqli_query($conn, $deleteQuery);

      if ($resultDelete) {
        // Prioritas berhasil dihapus, alihkan ke halaman daftar_prioritas.php
        header("Location: prioritas.php");
        exit;
      } else {
        // Kesalahan saat menghapus prioritas
        echo "Terjadi kesalahan saat menghapus prioritas.";
      }
    } else {
      // Kesalahan saat memperbarui PermintaanCucian
      echo "Terjadi kesalahan saat memperbarui PermintaanCucian.";
    }
  }
} else {
  // Parameter ID tidak tersedia atau kosong, alihkan ke halaman daftar_prioritas.php
  header("Location: prioritas.php");
  exit;
}

mysqli_close($conn);
?>

<?php include 'header.php'; ?>
<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 80px; /* Adjust the value as needed */
  }
</style>
<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <h2>Hapus Prioritas</h2>
      <p>Apakah Anda yakin ingin menghapus prioritas ini?</p>
      <form method="POST">
        <input type="submit" name="hapus" value="Hapus" class="btn btn-danger">
        <a href="prioritas.php" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
