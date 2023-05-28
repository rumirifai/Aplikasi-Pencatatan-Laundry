<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Inisialisasi pesan error dan tampilan tombol kembali
$errorMessage = '';
$showBackButton = false;

// Periksa apakah parameter hapus tersedia dan tidak kosong
if (isset($_GET['hapus']) && !empty($_GET['hapus'])) {
  $noCucian = $_GET['hapus'];

  // Ambil data permintaan cucian dari database
  $query = "SELECT pc.*, p.nama, t.no_transaksi FROM PermintaanCucian pc
            INNER JOIN Pelanggan p ON pc.id_pelanggan = p.id_pelanggan
            LEFT JOIN Transaksi t ON pc.no_cucian = t.no_cucian
            WHERE pc.no_cucian = $noCucian";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);

    // Periksa apakah no_transaksi sudah ada
    $no_transaksi = $data['no_transaksi'];
    if (!empty($no_transaksi)) {
      // Jika no_transaksi sudah ada, tidak bisa dihapus
      $errorMessage = "Permintaan cucian dengan no_transaksi sudah ada dan tidak dapat dihapus.";
      $showBackButton = true;
    }
  } else {
    // Tidak ada data permintaan cucian yang sesuai, alihkan kembali ke halaman sebelumnya
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
  }
} else {
  // Parameter hapus tidak tersedia atau kosong, alihkan kembali ke halaman sebelumnya
  header("Location: {$_SERVER['HTTP_REFERER']}");
  exit;
}

// Proses penghapusan permintaan cucian dan transaksi terkait jika tombol "Hapus" ditekan
if (isset($_POST['hapus'])) {
  // Hapus item cucian terkait dari database
  $deleteItemCucianQuery = "DELETE FROM ItemCucian WHERE no_cucian = $noCucian";
  $resultDeleteItemCucian = mysqli_query($conn, $deleteItemCucianQuery);

  if ($resultDeleteItemCucian) {
    // Hapus permintaan cucian dari database
    $deletePermintaanCucianQuery = "DELETE FROM PermintaanCucian WHERE no_cucian = $noCucian";
    $resultDeletePermintaanCucian = mysqli_query($conn, $deletePermintaanCucianQuery);

    if ($resultDeletePermintaanCucian) {
      // Permintaan cucian dan item cucian terkait berhasil dihapus, alihkan ke halaman permintaan cucian
      header("Location: permintaan_cucian.php");
      exit;
    } else {
      // Kesalahan saat menghapus permintaan cucian
      $errorMessage = "Terjadi kesalahan saat menghapus permintaan cucian.";
      $showBackButton = true;
    }
  } else {
    // Kesalahan saat menghapus item cucian terkait
    $errorMessage = "Terjadi kesalahan saat menghapus item cucian terkait.";
    $showBackButton = true;
  }
}

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <h2>Hapus Permintaan Cucian</h2>
      <?php if (!empty($errorMessage)) : ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php if ($showBackButton) : ?>
          <a href="permintaan_cucian.php" class="btn btn-primary">Kembali</a>
        <?php endif; ?>
      <?php else : ?>
        <p>Apakah Anda yakin ingin menghapus permintaan cucian ini?</p>
        <table class="table">
          <thead>
            <tr>
              <th>No. Cucian</th>
              <th>Nama Pelanggan</th>
              <!-- Tambahkan kolom lain yang sesuai -->
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $data['no_cucian']; ?></td>
              <td><?php echo $data['nama']; ?></td>
              <!-- Tambahkan kolom lain yang sesuai -->
            </tr>
          </tbody>
        </table>
        <form method="POST">
          <input type="submit" name="hapus" value="Hapus" class="btn btn-danger">
          <a href="permintaan_cucian.php" class="btn btn-secondary">Batal</a>
        </form>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
