<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Mendapatkan nomor cucian dari parameter URL
if (isset($_GET['no_cucian'])) {
  $noCucian = $_GET['no_cucian'];
} else {
  header("Location: permintaan_cucian.php");
  exit;
}

// Query untuk mendapatkan detail permintaan cucian berdasarkan nomor cucian
$query = "SELECT pc.*, p.nama, pri.jenis_prioritas, sc.jenis_status_cucian
          FROM PermintaanCucian pc
          INNER JOIN Pelanggan p ON pc.id_pelanggan = p.id_pelanggan
          LEFT JOIN Prioritas pri ON pc.id_prioritas = pri.id_prioritas
          LEFT JOIN StatusCucian sc ON pc.id_status_cucian = sc.id_status_cucian
          WHERE pc.no_cucian = $noCucian";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Memeriksa apakah transaksi sudah ada untuk permintaan cucian tersebut
if (!empty($row['no_transaksi'])) {
  header("Location: detail_transaksi.php?no_transaksi=" . $row['no_transaksi']);
  exit;
}

// Membuat transaksi baru menggunakan prosedur
if (isset($_POST['submit'])) {
  $noCucian = $_POST['no_cucian'];

  // Memanggil prosedur untuk membuat transaksi baru
  $createTransactionQuery = "CALL CreateTransaction($noCucian)";
  mysqli_query($conn, $createTransactionQuery);

  header("Location: daftar_transaksi.php");
  exit;
}
?>

<?php include 'header.php'; ?>

<main id="main">
  <section id="transaksi" class="transaksi">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Transaksi Baru</h2>
          <p>No. Cucian: <?php echo $row['no_cucian']; ?></p>
          <p>Nama Pelanggan: <?php echo $row['nama']; ?></p>
          <p>Prioritas: <?php echo $row['jenis_prioritas']; ?></p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <form action="" method="post">
            <input type="hidden" name="no_cucian" value="<?php echo $noCucian; ?>">
            <button type="submit" name="submit" class="btn btn-success">Buat Transaksi</button>
            <a href="permintaan_cucian.php" class="btn btn-secondary">Kembali</a>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
