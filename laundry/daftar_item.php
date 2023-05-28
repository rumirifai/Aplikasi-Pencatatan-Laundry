<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Query untuk mendapatkan daftar item dari tabel Item
$query = "SELECT i.*, p.jenis_prioritas 
          FROM Item i 
          LEFT JOIN Prioritas p ON i.id_prioritas = p.id_prioritas";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<style>
  /* Additional styles to adjust the content position */
  #main {
    padding-top: 70px;
    /* Adjust the value as needed */
  }
</style>
<link rel="stylesheet" href="assets/vendor/DataTables/dataTables.min.css">
<main id="main">
  <section id="admin" class="admin">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h2>Daftar Item</h2>
          <div class="text-right">
            <a href="tambah_item.php" class="btn btn-primary">Tambah Item</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <table id = "tabel-item" class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Jenis Item</th>
                <th>Prioritas</th>
                <th>Harga per Item</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td><?php echo $row['id_item']; ?></td>
                  <td><?php echo $row['jenis_item']; ?></td>
                  <td><?php echo $row['jenis_prioritas']; ?></td>
                  <td><?php echo $row['harga_per_item']; ?></td>
                  <td>
                    <a href="edit_item.php?id=<?php echo $row['id_item']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="hapus_item.php?id=<?php echo $row['id_item']; ?>" class="btn btn-danger btn-sm">Hapus</a>
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
    $('#tabel-item').DataTable();
  });
</script>