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

// Query untuk mendapatkan daftar pelanggan dari tabel Pelanggan
$query = "SELECT * FROM Pelanggan";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<link rel="stylesheet" href="assets/vendor/DataTables/dataTables.min.css">
<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Daftar Pelanggan</h2>
          <div class="text-right">
            <a href="tambah_pelanggan.php" class="btn btn-primary btn-sm">Tambah Pelanggan</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <table id="tabel-pelanggan" class="table table-bordered">
            <thead>
              <tr>
                <th>No.</th>
                <th>ID Pelanggan</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Telepon</th>
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
                  <td><?php echo $row['id_pelanggan']; ?></td>
                  <td><?php echo $row['nama']; ?></td>
                  <td><?php echo $row['alamat']; ?></td>
                  <td><?php echo $row['telepon']; ?></td>
                  <td>
                    <a href="edit_pelanggan.php?id=<?php echo $row['id_pelanggan']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="hapus_pelanggan.php?id=<?php echo $row['id_pelanggan']; ?>" class="btn btn-danger btn-sm">Hapus</a>
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

<script src="assets/vendor/DataTables/DataTables-1.13.4/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#tabel-pelanggan').DataTable();
  });
</script>
