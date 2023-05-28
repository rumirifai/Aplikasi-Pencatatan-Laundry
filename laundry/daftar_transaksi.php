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

// Query untuk mendapatkan daftar transaksi dari tabel Transaksi
$query = "SELECT t.*, p.nama AS nama_pelanggan, st.jenis_status_transaksi 
          FROM Transaksi t 
          INNER JOIN Pelanggan p ON t.id_pelanggan = p.id_pelanggan
          INNER JOIN StatusTransaksi st ON t.id_status_transaksi = st.id_status_transaksi";
$result = mysqli_query($conn, $query);

// Mengubah status transaksi jika ada permintaan perubahan
if (isset($_POST['ubah_status'])) {
  $noTransaksi = $_POST['no_transaksi'];
  $statusTransaksi = $_POST['status_transaksi'];

  // Periksa apakah status transaksi yang dipilih valid
  $validStatus = ['Belum Bayar', 'Sudah Dibayar'];
  if (in_array($statusTransaksi, $validStatus)) {
    // Dapatkan id_status_transaksi baru
    $getStatusQuery = "SELECT id_status_transaksi FROM StatusTransaksi WHERE jenis_status_transaksi = '$statusTransaksi'";
    $statusResult = mysqli_query($conn, $getStatusQuery);
    $row = mysqli_fetch_assoc($statusResult);
    $idStatusTransaksi = $row['id_status_transaksi'];

    // Update status transaksi ke database
    $updateQuery = "UPDATE Transaksi SET id_status_transaksi = $idStatusTransaksi WHERE no_transaksi = $noTransaksi";
    $resultUpdate = mysqli_query($conn, $updateQuery);

    if ($resultUpdate) {
      // Status transaksi berhasil diubah, refresh halaman
      header("Location: daftar_transaksi.php");
      exit;
    } else {
      // Kesalahan saat mengubah status transaksi
      echo "Terjadi kesalahan saat mengubah status transaksi.";
    }
  }
}

// Menghapus transaksi jika ada permintaan penghapusan
if (isset($_GET['hapus'])) {
  $noTransaksi = $_GET['hapus'];

  // Hapus transaksi dari database
  $deleteQuery = "DELETE FROM Transaksi WHERE no_transaksi = $noTransaksi";
  $resultDelete = mysqli_query($conn, $deleteQuery);

  if ($resultDelete) {
    // Transaksi berhasil dihapus, refresh halaman
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
<link rel="stylesheet" href="assets/vendor/DataTables/dataTables.min.css">
<link rel="stylesheet" href="assets/css/styles.css">
<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Daftar Transaksi</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <table id="tabel-transaksi" class="table table-bordered">
            <thead>
              <tr>
                <th>No.</th>
                <th>ID Transaksi</th>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal Transaksi</th>
                <th>Subtotal</th>
                <th>Status Transaksi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $num = 1;
              while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                  <td><?php echo $num++; ?></td>
                  <td><?php echo $row['no_transaksi']; ?></td>
                  <td><?php echo $row['id_pelanggan']; ?></td>
                  <td><?php echo $row['nama_pelanggan']; ?></td>
                  <td><?php echo $row['tgl_transaksi']; ?></td>
                  <td><?php echo $row['subtotal']; ?></td>
                  <td>
                    <?php if ($row['jenis_status_transaksi'] == 'Sudah Dibayar'): ?>
                      <?php echo $row['jenis_status_transaksi']; ?>
                    <?php else: ?>
                      <form action="daftar_transaksi.php" method="POST">
                        <input type="hidden" name="no_transaksi" value="<?php echo $row['no_transaksi']; ?>">
                        <select name="status_transaksi" onchange="this.form.submit()">
                          <option value="<?php echo $row['jenis_status_transaksi']; ?>" selected><?php echo $row['jenis_status_transaksi']; ?></option>
                          <option value="Belum Bayar">Belum Bayar</option>
                          <option value="Sudah Dibayar">Sudah Dibayar</option>
                        </select>
                        <input type="hidden" name="ubah_status" value="1">
                      </form>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($row['jenis_status_transaksi'] != 'Sudah Dibayar'): ?>
                      <a href="hapus_transaksi.php?no_transaksi=<?php echo $row['no_transaksi']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                    <?php else: ?>
                      <a href="download_receipt.php?no_transaksi=<?php echo $row['no_transaksi']; ?>" class="btn btn-primary btn-sm">Download Receipt</a>
                    <?php endif; ?>
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

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#tabel-transaksi').DataTable();
  });
</script>