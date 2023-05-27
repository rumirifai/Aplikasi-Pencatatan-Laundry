<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Mengubah status cucian jika ada permintaan perubahan status
if (isset($_POST['no_cucian']) && isset($_POST['status'])) {
  $noCucian = $_POST['no_cucian'];
  $status = $_POST['status'];

  // Update status cucian dan total_item dalam database
  $updateQuery = "UPDATE PermintaanCucian SET id_status_cucian = '$status', total_item = (SELECT SUM(jumlah_item) FROM itemCucian WHERE no_cucian = $noCucian) WHERE no_cucian = $noCucian";
  mysqli_query($conn, $updateQuery);
}

// Menghapus permintaan cucian jika ada permintaan penghapusan
if (isset($_GET['hapus'])) {
  $noCucian = $_GET['hapus'];

  // Hapus permintaan cucian dari database
  $deleteQuery = "DELETE FROM PermintaanCucian WHERE no_cucian = $noCucian";
  mysqli_query($conn, $deleteQuery);

  // Update total_item setelah menghapus permintaan cucian
  $updateTotalItemQuery = "UPDATE PermintaanCucian SET total_item = (SELECT SUM(jumlah_item) FROM itemCucian WHERE no_cucian = $noCucian) WHERE no_cucian = $noCucian";
  mysqli_query($conn, $updateTotalItemQuery);
}

// Query untuk mendapatkan daftar permintaan cucian dari tabel PermintaanCucian
$query = "SELECT pc.*, p.nama, pri.jenis_prioritas, sc.jenis_status_cucian, SUM(ic.jumlah_item) AS total_item
FROM PermintaanCucian pc
INNER JOIN Pelanggan p ON pc.id_pelanggan = p.id_pelanggan
LEFT JOIN Prioritas pri ON pc.id_prioritas = pri.id_prioritas
LEFT JOIN StatusCucian sc ON pc.id_status_cucian = sc.id_status_cucian
LEFT JOIN itemCucian ic ON pc.no_cucian = ic.no_cucian
GROUP BY pc.no_cucian";
$result = mysqli_query($conn, $query);

?>

<?php include 'header.php'; ?>

<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 70px;
    /* Adjust the value as needed */
  }
</style>

<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Daftar Permintaan Cucian</h2>
          <div class="text-right">
            <a href="tambah_permintaan_cucian.php" class="btn btn-primary">Tambah Permintaan Cucian</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
                <th>No. Cucian</th>
                <th>Nama Pelanggan</th>
                <th>Prioritas</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Selesai</th>
                <th>Total Item</th>
                <th>Status Cucian</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php
                $noCucian = $row['no_cucian'];
                $queryCheckTransaksi = "SELECT COUNT(*) AS jumlah_transaksi FROM Transaksi WHERE no_cucian = $noCucian";
                $resultCheckTransaksi = mysqli_query($conn, $queryCheckTransaksi);
                $rowCheckTransaksi = mysqli_fetch_assoc($resultCheckTransaksi);
                $jumlahTransaksi = $rowCheckTransaksi['jumlah_transaksi'];
                ?>
                <tr>
                  <td>
                    <?php echo $row['no_cucian']; ?>
                  </td>
                  <td>
                    <?php echo $row['nama']; ?>
                  </td>
                  <td>
                    <?php echo $row['jenis_prioritas']; ?>
                  </td>
                  <td>
                    <?php echo $row['tgl_masuk']; ?>
                  </td>
                  <td>
                    <?php echo $row['tgl_selesai']; ?>
                  </td>
                  <td>
                    <?php echo $row['total_item']; ?>
                  </td>
                  <td>
                    <?php echo $row['jenis_status_cucian']; ?>
                  </td>
                  <td>
                    <?php if (!empty($row['no_transaksi'])): ?>
                      <a href="detail_transaksi.php?no_transaksi=<?php echo $row['no_transaksi']; ?>"
                        class="btn btn-primary btn-sm">Detail Transaksi</a>
                    <?php else: ?>
                      <?php if ($jumlahTransaksi > 0): ?>
                        <a href="#" class="btn btn-secondary btn-sm" disabled>Sudah Masuk Transaksi</a>
                      <?php elseif ($row['total_item'] == 0): ?>
                        <a href="#" class="btn btn-secondary btn-sm" disabled>Total Item Kosong</a>
                      <?php else: ?>
                        <a href="buat_transaksi.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                          class="btn btn-success btn-sm">Lanjutkan ke Transaksi</a>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($row['jenis_status_cucian'] != 'Selesai' && empty($row['no_transaksi'])): ?>
                      <a href="edit_permintaan_cucian.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                        class="btn btn-primary btn-sm">Edit</a>
                      <a href="hapus_permintaan_cucian.php?hapus=<?php echo $row['no_cucian']; ?>"
                        class="btn btn-danger btn-sm">Hapus</a>
                    <?php elseif ($row['jenis_status_cucian'] != 'Selesai' && !empty($row['no_transaksi'])): ?>
                      <a href="edit_permintaan_cucian.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                        class="btn btn-primary btn-sm">Edit</a>
                    <?php endif; ?>
                    <a href="detail_cucian.php?no_cucian=<?php echo $row['no_cucian']; ?>"
                      class="btn btn-info btn-sm">Detail Cucian</a>
                  </td>
                </tr>
              <?php endwhile; ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
