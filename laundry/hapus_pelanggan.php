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
  // Mendapatkan ID pelanggan yang akan dihapus dari parameter URL
  $id_pelanggan = $_GET['id'];

  // Periksa apakah formulir dikirimkan dengan metode POST
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus semua transaksi yang terkait dengan pelanggan yang dihapus
    $queryDeleteTransaksi = "DELETE FROM transaksi WHERE id_pelanggan = '$id_pelanggan'";
    $resultDeleteTransaksi = mysqli_query($conn, $queryDeleteTransaksi);

    // Hapus semua item cucian yang terkait dengan permintaan cucian pelanggan yang dihapus
    $queryDeleteItemCucian = "DELETE ic FROM itemcucian ic INNER JOIN permintaancucian pc ON ic.no_cucian = pc.no_cucian WHERE pc.id_pelanggan = '$id_pelanggan'";
    $resultDeleteItemCucian = mysqli_query($conn, $queryDeleteItemCucian);

    // Hapus semua permintaan cucian yang terkait dengan pelanggan yang dihapus
    $queryDeletePermintaan = "DELETE FROM permintaancucian WHERE id_pelanggan = '$id_pelanggan'";
    $resultDeletePermintaan = mysqli_query($conn, $queryDeletePermintaan);

    // Hapus pelanggan dari tabel Pelanggan berdasarkan ID
    $queryDeletepelanggan = "DELETE FROM Pelanggan WHERE id_pelanggan = '$id_pelanggan'";
    $resultDeletepelanggan = mysqli_query($conn, $queryDeletepelanggan);

    if ($resultDeleteTransaksi && $resultDeleteItemCucian && $resultDeletePermintaan && $resultDeletepelanggan) {
      // pelanggan, permintaan cucian, item cucian, dan transaksi berhasil dihapus, alihkan ke halaman daftar pelanggan
      header("Location: pelanggan.php");
      exit;
    } else {
      // Kesalahan saat menghapus pelanggan, permintaan cucian, item cucian, dan transaksi
      echo "Terjadi kesalahan saat menghapus pelanggan, permintaan cucian, item cucian, dan transaksi.";
    }
  }
} else {
  // Parameter ID tidak tersedia atau kosong, alihkan ke halaman pelanggan
  header("Location: pelanggan.php");
  exit;
}

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
      <h2>Hapus Pelanggan</h2>
      <p>Apakah Anda yakin ingin menghapus pelanggan ini?</p>
      <form method="POST">
        <input type="submit" name="hapus" value="Hapus" class="btn btn-danger">
        <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
