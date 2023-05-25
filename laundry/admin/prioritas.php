<?php
include "config.php";

// Memeriksa apakah pelanggan telah login
session_start();

// Periksa apakah pelanggan telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Query untuk mendapatkan daftar prioritas dari tabel Prioritas
$query = "SELECT * FROM Prioritas";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php include 'head.php'; ?>
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
          <h2>Daftar Prioritas</h2>
          <div class="text-right">
            <a href="tambah_prioritas.php" class="btn btn-primary">Tambah Prioritas</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Jenis Prioritas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td><?php echo $row['id_prioritas']; ?></td>
                  <td><?php echo $row['jenis_prioritas']; ?></td>
                  <td>
                    <a href="edit_prioritas.php?id=<?php echo $row['id_prioritas']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="hapus_prioritas.php?id=<?php echo $row['id_prioritas']; ?>" class="btn btn-danger btn-sm">Hapus</a>
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
