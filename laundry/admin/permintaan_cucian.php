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

  // Update status cucian dalam database
  $updateQuery = "UPDATE PermintaanCucian SET status_cucian = '$status' WHERE no_cucian = $noCucian";
  mysqli_query($conn, $updateQuery);
}

// Query untuk mendapatkan daftar permintaan cucian dari tabel PermintaanCucian
$query = "SELECT pc.*, p.nama, pri.jenis_prioritas 
          FROM PermintaanCucian pc
          INNER JOIN Pelanggan p ON pc.id_pelanggan = p.id_pelanggan
          LEFT JOIN Prioritas pri ON pc.id_prioritas = pri.id_prioritas";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>



<?php include 'header.php'; ?>

<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 70px; /* Adjust the value as needed */
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
              <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td><?php echo $row['no_cucian']; ?></td>
                  <td><?php echo $row['nama']; ?></td>
                  <td><?php echo $row['jenis_prioritas']; ?></td>
                  <td><?php echo $row['tgl_masuk']; ?></td>
                  <td><?php echo $row['tgl_selesai']; ?></td>
                  <td><?php echo $row['total_item']; ?></td>
                  <td>
                    <form action="permintaan_cucian.php" method="POST">
                      <input type="hidden" name="no_cucian" value="<?php echo $row['no_cucian']; ?>">
                      <div class="input-group">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                          <option value="Menunggu" <?php if ($row['status_cucian'] == 'Menunggu') echo 'selected'; ?>>Menunggu</option>
                          <option value="Sedang Diproses" <?php if ($row['status_cucian'] == 'Sedang Diproses') echo 'selected'; ?>>Sedang Diproses</option>
                          <option value="Selesai" <?php if ($row['status_cucian'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                        </select>
                      </div>
                    </form>
                  </td>
                  <td>
                    <a href="detail_cucian.php?no_cucian=<?php echo $row['no_cucian']; ?>" class="btn btn-primary btn-sm">Detail Cucian</a>
                    <a href="edit_permintaan_cucian.php?no_cucian=<?php echo $row['no_cucian']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="hapus_permintaan_cucian.php?no_cucian=<?php echo $row['no_cucian']; ?>" class="btn btn-danger btn-sm">Hapus</a>
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
