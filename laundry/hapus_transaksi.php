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

// Memeriksa apakah parameter no_transaksi telah diberikan
if (!isset($_GET['no_transaksi'])) {
  header("Location: daftar_transaksi.php");
  exit;
}

$noTransaksi = $_GET['no_transaksi'];

// Query untuk mendapatkan informasi transaksi berdasarkan nomor transaksi
$query = "SELECT * FROM Transaksi WHERE no_transaksi = $noTransaksi";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Memeriksa apakah transaksi ditemukan
if (!$row) {
  header("Location: daftar_transaksi.php");
  exit;
}

// Menghapus transaksi jika konfirmasi penghapusan diterima
if (isset($_POST['hapus'])) {
  $deleteQuery = "DELETE FROM Transaksi WHERE no_transaksi = $noTransaksi";
  $resultDelete = mysqli_query($conn, $deleteQuery);

  if ($resultDelete) {
    // Transaksi berhasil dihapus, kembali ke halaman daftar transaksi
    header("Location: daftar_transaksi.php");
    exit;
  } else {
    // Kesalahan saat menghapus transaksi
    echo "Terjadi kesalahan saat menghapus transaksi.";
  }
}

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<main id="main">
  <section id="hapus-transaksi" class="hapus-transaksi">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Konfirmasi Penghapusan Transaksi</h2>
          <p>Anda akan menghapus transaksi dengan nomor: <?php echo $row['no_transaksi']; ?></p>
          <p>Apakah Anda yakin ingin melanjutkan?</p>
          <form action="hapus_transaksi.php?no_transaksi=<?php echo $noTransaksi; ?>" method="POST">
            <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
            <a href="daftar_transaksi.php" class="btn btn-secondary">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
