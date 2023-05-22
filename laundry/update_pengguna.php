<?php
include "config.php";

// Memeriksa apakah pengguna telah login
session_start();

// Periksa apakah pengguna telah login atau alihkan ke halaman login jika belum
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

// Periksa apakah data pengguna yang akan diupdate sudah tersedia
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Query untuk mendapatkan data pengguna berdasarkan ID
  $query = "SELECT * FROM Pelanggan WHERE id_pelanggan = $id";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $nama = $row['nama'];
    $alamat = $row['alamat'];
    $telepon = $row['telepon'];
  } else {
    // Jika data pengguna tidak ditemukan, redirect ke halaman pengguna
    header("Location: pengguna.php");
    exit;
  }
}

// Proses update pengguna hanya saat form disubmit
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $telepon = $_POST['telepon'];

  // Query untuk melakukan update data pengguna
  $query = "UPDATE Pelanggan SET nama = '$nama', alamat = '$alamat', telepon = '$telepon' WHERE id_pelanggan = $id";
  mysqli_query($conn, $query);

  // Redirect kembali ke halaman pengguna setelah update
  header("Location: pengguna.php");
  exit;
}

mysqli_close($conn);
?>

<?php include 'header.php'; ?>

<main id="main">
  <section id="edit-pengguna" class="edit-pengguna">
    <div class="container">
      <h4>Edit Pengguna</h4>
      <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group">
          <label for="nama">Nama:</label>
          <input type="text" name="nama" id="nama" value="<?php echo $nama; ?>" required>
        </div>
        <div class="form-group">
          <label for="alamat">Alamat:</label>
          <textarea name="alamat" id="alamat" required><?php echo $alamat; ?></textarea>
        </div>
        <div class="form-group">
          <label for="telepon">Telepon:</label>
          <input type="text" name="telepon" id="telepon" value="<?php echo $telepon; ?>" required>
        </div>
        <div class="form-group">
          <input type="submit" name="update" value="Simpan">
          <a href="pengguna.php">Batal</a>
        </div>
      </form>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
